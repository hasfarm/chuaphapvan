<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactRelationship;
use App\Models\Family;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::with('family');

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('full_name', 'like', $searchTerm)
                    ->orWhere('dharma_name', 'like', $searchTerm)
                    ->orWhere('phone', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm)
                    ->orWhere('family_name', 'like', $searchTerm)
                    ->orWhereHas('family', function ($familyQuery) use ($searchTerm) {
                        $familyQuery->where('family_name', 'like', $searchTerm)
                            ->orWhere('family_code', 'like', $searchTerm);
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $contacts = $query->latest()->paginate(15)->withQueryString();

        return view('admin.contacts.index', compact('contacts'));
    }

    public function create()
    {
        $families = Family::orderBy('family_name')->get();
        $availableContacts = Contact::orderBy('full_name')->get(['id', 'full_name', 'dharma_name']);
        $contactRelationships = [];

        return view('admin.contacts.create', compact('families', 'availableContacts', 'contactRelationships'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        $this->normalizeLifeStatusData($validated);
        $this->handleInlineFamilyCreation($request, $validated);
        $this->fillLegacyFamilyFields($validated);

        $contact = Contact::create($validated);
        $this->syncFamilyRoles($contact, $validated);
        $this->syncRelationships($contact, $request);

        return redirect()->route('admin.contacts.index')->with('success', 'Tạo phật tử thành công');
    }

    public function show(Contact $contact)
    {
        $contact->load(['family', 'relationshipsOut.relatedContact', 'relationshipsIn.contact']);

        return view('admin.contacts.show', compact('contact'));
    }

    public function edit(Contact $contact)
    {
        $families = Family::orderBy('family_name')->get();
        $availableContacts = Contact::where('id', '!=', $contact->id)
            ->orderBy('full_name')
            ->get(['id', 'full_name', 'dharma_name']);

        $contactRelationships = $contact->relationshipsOut()
            ->get(['relationship_type', 'related_contact_id'])
            ->toArray();

        return view('admin.contacts.edit', compact('contact', 'families', 'availableContacts', 'contactRelationships'));
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $this->validateData($request, $contact->id);
        $this->normalizeLifeStatusData($validated);
        $this->handleInlineFamilyCreation($request, $validated);
        $this->fillLegacyFamilyFields($validated);

        $contact->update($validated);
        $this->syncFamilyRoles($contact, $validated);
        $this->syncRelationships($contact, $request);

        return redirect()->route('admin.contacts.index')->with('success', 'Cập nhật phật tử thành công');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Xóa phật tử thành công');
    }

    private function validateData(Request $request, ?int $contactId = null): array
    {
        return $request->validate([
            'family_id' => 'nullable|exists:families,id',
            'is_household_head' => 'nullable|boolean',
            'is_primary_contact' => 'nullable|boolean',
            'full_name' => 'required|string|max:255',
            'dharma_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email:rfc|max:255',
            'solar_birth_date' => 'nullable|date',
            'solar_birth_year' => 'nullable|integer|min:1900|max:2100',
            'lunar_birth_date' => 'nullable|string|max:255',
            'lunar_birth_year' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'life_status' => 'required|in:alive,deceased',
            'death_solar_date' => 'nullable|date|required_if:life_status,deceased',
            'death_lunar_date' => 'nullable|string|max:255',
            'death_lunar_year' => 'nullable|string|max:255',
            'family_name' => 'nullable|string|max:255',
            'family_head_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'family_address' => 'nullable|string',
            'zodiac_info' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'relationship_type' => 'nullable|array',
            'relationship_type.*' => 'nullable|in:parent,child,spouse',
            'related_contact_id' => 'nullable|array',
            'related_contact_id.*' => 'nullable|integer|exists:contacts,id',
        ]);
    }

    private function normalizeLifeStatusData(array &$validated): void
    {
        if (($validated['life_status'] ?? 'alive') !== 'deceased') {
            $validated['death_solar_date'] = null;
            $validated['death_lunar_date'] = null;
            $validated['death_lunar_year'] = null;
        }
    }

    private function handleInlineFamilyCreation(Request $request, array &$validated): void
    {
        if (!$request->boolean('create_new_family')) {
            return;
        }

        $familyData = $request->validate([
            'new_family_name' => 'required|string|max:255',
            'new_family_code' => 'nullable|string|max:100|unique:families,family_code',
            'new_family_head_name' => 'nullable|string|max:255',
            'new_family_phone' => 'nullable|string|max:30',
            'new_family_email' => 'nullable|email:rfc|max:255',
            'new_family_address' => 'nullable|string',
            'new_family_notes' => 'nullable|string',
            'new_family_status' => 'nullable|in:active,inactive',
        ]);

        $family = Family::create([
            'family_name' => $familyData['new_family_name'],
            'family_code' => $familyData['new_family_code'] ?? null,
            'head_name' => $familyData['new_family_head_name'] ?? null,
            'phone' => $familyData['new_family_phone'] ?? null,
            'email' => $familyData['new_family_email'] ?? null,
            'address' => $familyData['new_family_address'] ?? null,
            'notes' => $familyData['new_family_notes'] ?? null,
            'status' => $familyData['new_family_status'] ?? 'active',
        ]);

        $validated['family_id'] = $family->id;
    }

    private function fillLegacyFamilyFields(array &$validated): void
    {
        $validated['is_household_head'] = (bool) ($validated['is_household_head'] ?? false);
        $validated['is_primary_contact'] = (bool) ($validated['is_primary_contact'] ?? false);

        if (!empty($validated['family_id'])) {
            $family = Family::find($validated['family_id']);
            if ($family) {
                $validated['family_name'] = $family->family_name;
                $validated['family_address'] = $family->address;
                $validated['family_head_name'] = $family->head_name;
            }
        } else {
            $validated['is_household_head'] = false;
            $validated['is_primary_contact'] = false;
            $validated['family_name'] = null;
            $validated['family_address'] = null;
            $validated['family_head_name'] = null;
        }
    }

    private function syncFamilyRoles(Contact $contact, array $validated): void
    {
        if (!$contact->family_id) {
            return;
        }

        if (!empty($validated['is_household_head'])) {
            Contact::where('family_id', $contact->family_id)
                ->where('id', '!=', $contact->id)
                ->update(['is_household_head' => false]);

            $contact->family()->update(['head_name' => $contact->full_name]);
            $contact->updateQuietly(['family_head_name' => $contact->full_name]);
        }

        if (!empty($validated['is_primary_contact'])) {
            Contact::where('family_id', $contact->family_id)
                ->where('id', '!=', $contact->id)
                ->update(['is_primary_contact' => false]);
        }
    }

    private function syncRelationships(Contact $contact, Request $request): void
    {
        $types = $request->input('relationship_type', []);
        $relatedIds = $request->input('related_contact_id', []);

        $contact->relationshipsOut()->delete();

        foreach ($types as $index => $type) {
            $relatedId = $relatedIds[$index] ?? null;

            if (!$type || !$relatedId || (int) $relatedId === (int) $contact->id) {
                continue;
            }

            ContactRelationship::firstOrCreate([
                'contact_id' => $contact->id,
                'related_contact_id' => $relatedId,
                'relationship_type' => $type,
            ]);

            if ($type === 'spouse') {
                ContactRelationship::firstOrCreate([
                    'contact_id' => $relatedId,
                    'related_contact_id' => $contact->id,
                    'relationship_type' => 'spouse',
                ]);
            }

            if ($type === 'parent') {
                ContactRelationship::firstOrCreate([
                    'contact_id' => $relatedId,
                    'related_contact_id' => $contact->id,
                    'relationship_type' => 'child',
                ]);
            }

            if ($type === 'child') {
                ContactRelationship::firstOrCreate([
                    'contact_id' => $relatedId,
                    'related_contact_id' => $contact->id,
                    'relationship_type' => 'parent',
                ]);
            }
        }
    }
}
