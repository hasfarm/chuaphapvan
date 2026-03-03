<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Family;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function index(Request $request)
    {
        $query = Family::withCount('contacts');

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('family_name', 'like', $searchTerm)
                    ->orWhere('family_code', 'like', $searchTerm)
                    ->orWhere('head_name', 'like', $searchTerm)
                    ->orWhere('phone', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $families = $query->latest()->paginate(15)->withQueryString();

        return view('admin.families.index', compact('families'));
    }

    public function create()
    {
        $contacts = Contact::orderBy('full_name')
            ->get(['id', 'full_name', 'dharma_name']);

        return view('admin.families.create', compact('contacts'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        Family::create($validated);

        return redirect()->route('admin.families.index')->with('success', 'Tạo gia đình thành công');
    }

    public function show(Family $family)
    {
        $family->load([
            'contacts' => function ($query) {
                $query->orderBy('full_name')
                    ->with(['relationshipsOut.relatedContact']);
            },
        ]);

        $contactIds = $family->contacts->pluck('id')->all();
        $contactIdMap = array_fill_keys($contactIds, true);

        $nodeMeta = [];
        foreach ($family->contacts as $contact) {
            $label = $contact->full_name;
            if ($contact->dharma_name) {
                $label .= "\n(" . $contact->dharma_name . ')';
            }

            $nodeMeta[$contact->id] = [
                'label' => $label,
                'gender' => $contact->gender,
                'life_status' => $contact->life_status ?? 'alive',
                'life_detail' => $this->resolveLifeDetailLine($contact),
                'is_household_head' => (bool) $contact->is_household_head,
                'is_primary_contact' => (bool) $contact->is_primary_contact,
                'age_sort' => $this->resolveAgeSortValue($contact->solar_birth_date, $contact->solar_birth_year),
            ];
        }

        $edges = [];
        $spouseKeys = [];
        $parentChildKeys = [];

        foreach ($family->contacts as $contact) {
            foreach ($contact->relationshipsOut as $relation) {
                $related = $relation->relatedContact;
                if (!$related || !in_array($related->id, $contactIds, true)) {
                    continue;
                }

                if ($relation->relationship_type === 'spouse') {
                    $a = min($contact->id, $related->id);
                    $b = max($contact->id, $related->id);
                    $pairKey = $a . '-' . $b;
                    if (isset($spouseKeys[$pairKey])) {
                        continue;
                    }
                    $spouseKeys[$pairKey] = true;
                    $edges[] = [
                        'from' => $a,
                        'to' => $b,
                        'type' => 'spouse',
                        'label' => 'Vợ/Chồng',
                    ];
                    continue;
                }

                if ($relation->relationship_type === 'parent') {
                    $parentId = $contact->id;
                    $childId = $related->id;
                    $edgeKey = $parentId . '-' . $childId;
                    if (isset($parentChildKeys[$edgeKey])) {
                        continue;
                    }
                    $parentChildKeys[$edgeKey] = true;
                    $edges[] = [
                        'from' => $parentId,
                        'to' => $childId,
                        'type' => 'parent_child',
                        'label' => 'Con',
                    ];
                    continue;
                }

                if ($relation->relationship_type === 'child') {
                    $parentId = $related->id;
                    $childId = $contact->id;
                    $edgeKey = $parentId . '-' . $childId;
                    if (isset($parentChildKeys[$edgeKey])) {
                        continue;
                    }
                    $parentChildKeys[$edgeKey] = true;
                    $edges[] = [
                        'from' => $parentId,
                        'to' => $childId,
                        'type' => 'parent_child',
                        'label' => 'Con',
                    ];
                }
            }
        }

        $groupParent = [];
        $groupRank = [];
        foreach ($contactIds as $contactId) {
            $groupParent[$contactId] = $contactId;
            $groupRank[$contactId] = 0;
        }

        $findGroup = function (int $contactId) use (&$groupParent, &$findGroup): int {
            if ($groupParent[$contactId] !== $contactId) {
                $groupParent[$contactId] = $findGroup($groupParent[$contactId]);
            }

            return $groupParent[$contactId];
        };

        $unionGroup = function (int $a, int $b) use (&$groupParent, &$groupRank, &$findGroup): void {
            $rootA = $findGroup($a);
            $rootB = $findGroup($b);
            if ($rootA === $rootB) {
                return;
            }

            if ($groupRank[$rootA] < $groupRank[$rootB]) {
                $groupParent[$rootA] = $rootB;
            } elseif ($groupRank[$rootA] > $groupRank[$rootB]) {
                $groupParent[$rootB] = $rootA;
            } else {
                $groupParent[$rootB] = $rootA;
                $groupRank[$rootA]++;
            }
        };

        foreach ($edges as $edge) {
            if (($edge['type'] ?? null) === 'spouse') {
                $from = (int) ($edge['from'] ?? 0);
                $to = (int) ($edge['to'] ?? 0);
                if (isset($contactIdMap[$from]) && isset($contactIdMap[$to])) {
                    $unionGroup($from, $to);
                }
            }
        }

        $groupOutgoing = [];
        $groupIncomingCount = [];
        foreach ($contactIds as $contactId) {
            $root = $findGroup($contactId);
            $groupOutgoing[$root] = $groupOutgoing[$root] ?? [];
            $groupIncomingCount[$root] = $groupIncomingCount[$root] ?? 0;
        }

        $groupEdgeKeys = [];
        foreach ($edges as $edge) {
            if (($edge['type'] ?? null) !== 'parent_child') {
                continue;
            }

            $from = (int) ($edge['from'] ?? 0);
            $to = (int) ($edge['to'] ?? 0);
            if (!isset($contactIdMap[$from]) || !isset($contactIdMap[$to])) {
                continue;
            }

            $fromGroup = $findGroup($from);
            $toGroup = $findGroup($to);
            if ($fromGroup === $toGroup) {
                continue;
            }

            $groupEdgeKey = $fromGroup . '-' . $toGroup;
            if (isset($groupEdgeKeys[$groupEdgeKey])) {
                continue;
            }
            $groupEdgeKeys[$groupEdgeKey] = true;

            $groupOutgoing[$fromGroup][] = $toGroup;
            $groupIncomingCount[$toGroup] = ($groupIncomingCount[$toGroup] ?? 0) + 1;
        }

        $groupLevel = [];
        foreach (array_keys($groupIncomingCount) as $groupId) {
            $groupLevel[$groupId] = 0;
        }

        $queue = new \SplQueue();
        foreach ($groupIncomingCount as $groupId => $incomingCount) {
            if ($incomingCount === 0) {
                $queue->enqueue($groupId);
            }
        }

        while (!$queue->isEmpty()) {
            $currentGroup = $queue->dequeue();
            foreach ($groupOutgoing[$currentGroup] ?? [] as $nextGroup) {
                $groupLevel[$nextGroup] = max($groupLevel[$nextGroup] ?? 0, ($groupLevel[$currentGroup] ?? 0) + 1);
                $groupIncomingCount[$nextGroup]--;
                if ($groupIncomingCount[$nextGroup] === 0) {
                    $queue->enqueue($nextGroup);
                }
            }
        }

        $nodes = [];
        foreach ($contactIds as $contactId) {
            $rootGroup = $findGroup($contactId);
            $nodes[] = [
                'id' => $contactId,
                'label' => $nodeMeta[$contactId]['label'] ?? '',
                'gender' => $nodeMeta[$contactId]['gender'] ?? null,
                'life_status' => $nodeMeta[$contactId]['life_status'] ?? 'alive',
                'life_detail' => $nodeMeta[$contactId]['life_detail'] ?? null,
                'is_household_head' => (bool) ($nodeMeta[$contactId]['is_household_head'] ?? false),
                'is_primary_contact' => (bool) ($nodeMeta[$contactId]['is_primary_contact'] ?? false),
                'age_sort' => (int) ($nodeMeta[$contactId]['age_sort'] ?? 99999999),
                'level' => (int) ($groupLevel[$rootGroup] ?? 0),
            ];
        }

        $familyTreeChart = [
            'nodes' => $nodes,
            'edges' => $edges,
        ];

        $savedChartLayout = $family->chart_layout ?? [];

        return view('admin.families.show', compact('family', 'familyTreeChart', 'savedChartLayout'));
    }

    public function saveChartLayout(Request $request, Family $family)
    {
        $validated = $request->validate([
            'nodes' => 'required|array',
            'nodes.*.id' => 'required|integer|exists:contacts,id',
            'nodes.*.x' => 'required|numeric',
            'nodes.*.y' => 'required|numeric',
        ]);

        $allowedContactIds = $family->contacts()->pluck('id')->all();
        $allowedContactMap = array_fill_keys($allowedContactIds, true);

        $cleanNodes = collect($validated['nodes'])
            ->filter(function ($node) use ($allowedContactMap) {
                return isset($allowedContactMap[(int) ($node['id'] ?? 0)]);
            })
            ->map(function ($node) {
                return [
                    'id' => (int) $node['id'],
                    'x' => (float) $node['x'],
                    'y' => (float) $node['y'],
                ];
            })
            ->values()
            ->all();

        $family->update([
            'chart_layout' => [
                'nodes' => $cleanNodes,
                'saved_at' => now()->toDateTimeString(),
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã lưu bố cục cây gia đình',
        ]);
    }

    public function resetChartLayout(Family $family)
    {
        $family->update([
            'chart_layout' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã reset bố cục cây gia đình',
        ]);
    }

    public function edit(Family $family)
    {
        $contacts = Contact::orderBy('full_name')
            ->get(['id', 'full_name', 'dharma_name']);

        return view('admin.families.edit', compact('family', 'contacts'));
    }

    public function update(Request $request, Family $family)
    {
        $validated = $this->validateData($request, $family->id);
        $family->update($validated);

        return redirect()->route('admin.families.index')->with('success', 'Cập nhật gia đình thành công');
    }

    public function destroy(Family $family)
    {
        $family->delete();
        return redirect()->route('admin.families.index')->with('success', 'Xóa gia đình thành công');
    }

    private function validateData(Request $request, ?int $familyId = null): array
    {
        $familyCodeRule = 'nullable|string|max:100|unique:families,family_code';
        if ($familyId) {
            $familyCodeRule .= ',' . $familyId;
        }

        return $request->validate([
            'family_name' => 'required|string|max:255',
            'family_code' => $familyCodeRule,
            'head_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email:rfc|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
    }

    private function resolveAgeSortValue($solarBirthDate, $solarBirthYear): int
    {
        if ($solarBirthDate) {
            return (int) $solarBirthDate->format('Ymd');
        }

        if ($solarBirthYear) {
            return ((int) $solarBirthYear * 10000) + 1231;
        }

        return 99999999;
    }

    private function resolveLifeDetailLine(Contact $contact): ?string
    {
        $lifeStatus = $contact->life_status ?? 'alive';

        if ($lifeStatus === 'deceased') {
            $deathLunarDate = trim((string) ($contact->death_lunar_date ?? ''));
            $deathLunarYear = trim((string) ($contact->death_lunar_year ?? ''));

            if ($deathLunarDate !== '' || $deathLunarYear !== '') {
                $suffix = $deathLunarYear !== '' ? (' ' . $deathLunarYear) : '';
                return 'Giỗ AL: ' . ($deathLunarDate !== '' ? $deathLunarDate : '—') . $suffix;
            }

            return 'Đã mất';
        }

        $age = null;
        if ($contact->solar_birth_date) {
            try {
                $age = max(0, \Carbon\Carbon::parse($contact->solar_birth_date)->age);
            } catch (\Throwable $exception) {
                $age = null;
            }
        } elseif (!empty($contact->solar_birth_year) && is_numeric($contact->solar_birth_year)) {
            $age = max(0, (int) now()->year - (int) $contact->solar_birth_year);
        }

        return $age !== null ? ('Tuổi: ' . $age) : null;
    }
}
