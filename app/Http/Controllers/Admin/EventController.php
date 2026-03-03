<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Event;
use App\Models\Family;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::withCount(['families', 'contacts']);

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('event_name', 'like', $searchTerm)
                    ->orWhere('event_type', 'like', $searchTerm)
                    ->orWhere('location', 'like', $searchTerm)
                    ->orWhere('event_lunar_date', 'like', $searchTerm);
            });
        }

        if ($request->filled('event_year')) {
            $query->where('event_year', $request->event_year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->orderByDesc('event_date')->orderByDesc('event_year')->paginate(15)->withQueryString();

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $families = Family::orderBy('family_name')->get(['id', 'family_name', 'family_code']);
        $contacts = Contact::orderBy('full_name')->get(['id', 'full_name', 'dharma_name']);

        return view('admin.events.create', compact('families', 'contacts'));
    }

    public function calendar()
    {
        $events = Event::query()
            ->whereNotNull('event_date')
            ->orderBy('event_date')
            ->get(['id', 'event_name', 'event_type', 'event_date', 'event_start_time', 'event_end_time', 'status'])
            ->map(function (Event $event) {
                $startDate = optional($event->event_date)->format('Y-m-d');
                $startTime = $this->formatTimeForInput($event->event_start_time);
                $endTime = $this->formatTimeForInput($event->event_end_time);
                $startDateTime = ($startDate && $startTime) ? ($startDate . 'T' . $startTime) : $startDate;
                $endDateTime = ($startDate && $endTime) ? ($startDate . 'T' . $endTime) : null;

                return [
                    'id' => $event->id,
                    'title' => $event->display_title,
                    'start' => $startDateTime,
                    'end' => $endDateTime,
                    'allDay' => empty($startTime) && empty($endTime),
                    'url' => route('admin.events.show', $event),
                    'event_date' => $startDate,
                    'event_start_time' => $startTime,
                    'event_end_time' => $endTime,
                    'event_lunar_date' => $event->event_lunar_date,
                    'event_lunar_year' => $event->event_lunar_year,
                    'location' => $event->location,
                    'description' => $event->description,
                    'status' => $event->status,
                    'is_annual' => (bool) $event->is_annual,
                    'extendedProps' => [
                        'event_type' => $event->event_type,
                    ],
                ];
            })
            ->values();

        return view('admin.events.calendar', compact('events'));
    }

    public function calendarStore(Request $request)
    {
        try {
            $validated = $this->validateData($request);
            $validated['event_year'] = $this->resolveEventYear($validated, null);
            $validated['event_lunar_year'] = $this->resolveEventLunarYear($validated, null);
            $event = Event::create($validated);

            return response()->json([
                'message' => 'Tạo sự kiện thành công',
                'event' => $this->transformCalendarEvent($event),
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $exception->errors(),
            ], 422);
        }
    }

    public function calendarUpdate(Request $request, Event $event)
    {
        try {
            $validated = $this->validateData($request);
            $validated['event_year'] = $this->resolveEventYear($validated, $event);
            $validated['event_lunar_year'] = $this->resolveEventLunarYear($validated, $event);
            $event->update($validated);

            return response()->json([
                'message' => 'Cập nhật sự kiện thành công',
                'event' => $this->transformCalendarEvent($event->fresh()),
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $exception->errors(),
            ], 422);
        }
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        $validated['event_year'] = $this->resolveEventYear($validated, null);
        $validated['event_lunar_year'] = $this->resolveEventLunarYear($validated, null);
        $event = Event::create($validated);
        $this->syncParticipants($event, $request);

        return redirect()->route('admin.events.index')->with('success', 'Tạo sự kiện thành công');
    }

    public function show(Event $event)
    {
        $event->load(['families', 'contacts']);

        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $families = Family::orderBy('family_name')->get(['id', 'family_name', 'family_code']);
        $contacts = Contact::orderBy('full_name')->get(['id', 'full_name', 'dharma_name']);

        return view('admin.events.edit', compact('event', 'families', 'contacts'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $this->validateData($request);
        $validated['event_year'] = $this->resolveEventYear($validated, $event);
        $validated['event_lunar_year'] = $this->resolveEventLunarYear($validated, $event);
        $event->update($validated);
        $this->syncParticipants($event, $request);

        return redirect()->route('admin.events.index')->with('success', 'Cập nhật sự kiện thành công');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Xóa sự kiện thành công');
    }

    private function validateData(Request $request): array
    {
        $validated = $request->validate([
            'event_name' => 'nullable|string|max:255',
            'event_year' => 'nullable|integer|min:1900|max:2100',
            'event_date' => 'nullable|date',
            'event_start_time' => 'nullable|date_format:H:i',
            'event_end_time' => 'nullable|date_format:H:i|after:event_start_time',
            'event_lunar_date' => 'nullable|string|max:255',
            'event_lunar_year' => 'nullable|string|max:50',
            'event_type' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_annual' => 'nullable|boolean',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'family_ids' => 'nullable|array',
            'family_ids.*' => 'integer|exists:families,id',
            'contact_ids' => 'nullable|array',
            'contact_ids.*' => 'integer|exists:contacts,id',
        ]);

        $validated['event_name'] = $validated['event_name'] ?? null;

        return $validated;
    }

    private function resolveEventYear(array $validated, ?Event $event = null): int
    {
        if (!empty($validated['event_date'])) {
            try {
                return (int) \Carbon\Carbon::parse($validated['event_date'])->year;
            } catch (\Throwable $exception) {
            }
        }

        if (!empty($validated['event_year'])) {
            return (int) $validated['event_year'];
        }

        if ($event && !empty($event->event_year)) {
            return (int) $event->event_year;
        }

        return (int) now()->year;
    }

    private function resolveEventLunarYear(array $validated, ?Event $event = null): ?string
    {
        if (!empty($validated['event_lunar_year'])) {
            return trim((string) $validated['event_lunar_year']);
        }

        if ($event && !empty($event->event_lunar_year)) {
            return trim((string) $event->event_lunar_year);
        }

        return null;
    }

    private function syncParticipants(Event $event, Request $request): void
    {
        $familyIds = collect($request->input('family_ids', []))
            ->filter()
            ->map(fn ($value) => (int) $value)
            ->unique()
            ->values()
            ->all();

        $contactIds = collect($request->input('contact_ids', []))
            ->filter()
            ->map(fn ($value) => (int) $value)
            ->unique()
            ->values()
            ->all();

        $event->families()->sync($familyIds);
        $event->contacts()->sync($contactIds);
    }

    private function transformCalendarEvent(Event $event): array
    {
        return [
            'id' => $event->id,
            'title' => $event->display_title,
            'start' => $this->resolveCalendarStart($event),
            'end' => $this->resolveCalendarEnd($event),
            'allDay' => empty($event->event_start_time) && empty($event->event_end_time),
            'url' => route('admin.events.show', $event),
            'event_date' => optional($event->event_date)->format('Y-m-d'),
            'event_start_time' => $this->formatTimeForInput($event->event_start_time),
            'event_end_time' => $this->formatTimeForInput($event->event_end_time),
            'event_lunar_date' => $event->event_lunar_date,
            'event_lunar_year' => $event->event_lunar_year,
            'location' => $event->location,
            'description' => $event->description,
            'status' => $event->status,
            'is_annual' => (bool) $event->is_annual,
            'extendedProps' => [
                'event_type' => $event->event_type,
            ],
        ];
    }

    private function formatTimeForInput(?string $time): ?string
    {
        if (empty($time)) {
            return null;
        }

        try {
            return \Carbon\Carbon::createFromFormat('H:i:s', $time)->format('H:i');
        } catch (\Throwable $exception) {
            try {
                return \Carbon\Carbon::parse($time)->format('H:i');
            } catch (\Throwable $innerException) {
                return null;
            }
        }
    }

    private function resolveCalendarStart(Event $event): ?string
    {
        $date = optional($event->event_date)->format('Y-m-d');
        if (empty($date)) {
            return null;
        }

        $startTime = $this->formatTimeForInput($event->event_start_time);
        if (empty($startTime)) {
            return $date;
        }

        return $date . 'T' . $startTime;
    }

    private function resolveCalendarEnd(Event $event): ?string
    {
        $date = optional($event->event_date)->format('Y-m-d');
        if (empty($date)) {
            return null;
        }

        $endTime = $this->formatTimeForInput($event->event_end_time);
        if (empty($endTime)) {
            return null;
        }

        return $date . 'T' . $endTime;
    }
}
