<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\AuditPointsConfig;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Audit::query();

        // Admin có thể xem tất cả audits, user thường chỉ xem của mình
        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        // Search global
        if ($request->filled('search')) {
            $query->searchGlobal($request->search);
        }

        // Filter by date range
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        // Filter by greenhouse
        if ($request->filled('greenhouse_id')) {
            $query->byGreenhouse($request->greenhouse_id);
        }

        // Filter by QC
        if ($request->filled('qc_name')) {
            $query->byQC($request->qc_name);
        }

        // Filter by worker
        if ($request->filled('worker_name')) {
            $query->byWorker($request->worker_name);
        }

        // Filter by variety
        if ($request->filled('variety_name')) {
            $query->byVariety($request->variety_name);
        }

        // Sort
        $sortBy = $request->input('sort_by', 'date');
        $sortDirection = $request->input('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        // Pagination
        $perPage = $request->input('per_page', 20);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 20;
        $audits = $query->paginate($perPage);

        // Get distinct values for filters
        // Admin xem tất cả, user thường chỉ xem của mình
        $baseQuery = auth()->user()->isAdmin() ? Audit::query() : Audit::where('user_id', auth()->id());

        $greenhouses = (clone $baseQuery)->distinct('greenhouse_id')->pluck('greenhouse_id');
        $qcs = (clone $baseQuery)->distinct('qc_name')->pluck('qc_name');
        $workers = (clone $baseQuery)->distinct('worker_name')->pluck('worker_name');
        $varieties = (clone $baseQuery)->distinct('variety_name')->pluck('variety_name');

        return view('audits.index', compact('audits', 'greenhouses', 'qcs', 'workers', 'varieties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $currentUser = auth()->user();
        return view('audits.create', compact('currentUser'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'greenhouse_id' => 'required|string|max:255',
            'greenhouse_name' => 'nullable|string|max:255',
            'qc_name' => 'required|string|max:255',
            'picker_code' => 'nullable|string|max:255',
            'worker_name' => 'required|string|max:255',
            'variety_name' => 'required|string|max:255',
            'plot_code' => 'required|string|max:255',
            'bag_weight' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:0',
            'uniformity_qty' => 'required|integer|min:0',
            'urc_weight_qty' => 'required|numeric|min:0',
            'length_qty' => 'required|integer|min:0',
            'damaged_qty' => 'required|integer|min:0',
            'leaf_burn_qty' => 'required|integer|min:0',
            'yellow_spot_qty' => 'required|integer|min:0',
            'wooden_qty' => 'required|integer|min:0',
            'dirty_qty' => 'required|integer|min:0',
            'wrong_label_qty' => 'required|integer|min:0',
            'pest_disease_qty' => 'required|integer|min:0',
        ]);

        $validated['user_id'] = auth()->id();

        // Calculate total_points automatically
        $validated['total_points'] = $this->calculateTotalPoints($validated);

        Audit::create($validated);

        return redirect()->route('audits.index')->with('success', 'Tạo bản ghi Kiểm Soát Chất Lượng thành công');
    }

    /**
     * Calculate total points based on configured point values
     */
    private function calculateTotalPoints(array $data)
    {
        $pointsConfig = AuditPointsConfig::getActivePoints();
        $totalPoints = 0;

        foreach ($pointsConfig as $fieldName => $points) {
            if (isset($data[$fieldName])) {
                $totalPoints += $data[$fieldName] * $points;
            }
        }

        return $totalPoints;
    }

    /**
     * Display the specified resource.
     */
    public function show(Audit $audit)
    {
        return view('audits.show', compact('audit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Audit $audit)
    {
        return view('audits.edit', compact('audit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Audit $audit)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'greenhouse_id' => 'required|string|max:255',
            'greenhouse_name' => 'nullable|string|max:255',
            'qc_name' => 'required|string|max:255',
            'picker_code' => 'nullable|string|max:255',
            'worker_name' => 'required|string|max:255',
            'variety_name' => 'required|string|max:255',
            'plot_code' => 'required|string|regex:/^\d{2}\.\d{4}$/',
            'bag_weight' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:0',
            'uniformity_qty' => 'required|integer|min:0',
            'urc_weight_qty' => 'required|numeric|min:0',
            'length_qty' => 'required|integer|min:0',
            'damaged_qty' => 'required|integer|min:0',
            'leaf_burn_qty' => 'required|integer|min:0',
            'yellow_spot_qty' => 'required|integer|min:0',
            'wooden_qty' => 'required|integer|min:0',
            'dirty_qty' => 'required|integer|min:0',
            'wrong_label_qty' => 'required|integer|min:0',
            'pest_disease_qty' => 'required|integer|min:0',
        ]);

        // Calculate total_points automatically
        $validated['total_points'] = $this->calculateTotalPoints($validated);

        $audit->update($validated);

        return redirect()->route('audits.show', $audit)->with('success', 'Cập nhật bản ghi Kiểm Soát Chất Lượng thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Audit $audit)
    {
        $audit->delete();
        return redirect()->route('audits.index')->with('success', 'Xóa bản ghi Kiểm Soát Chất Lượng thành công');
    }

    /**
     * Export filtered audits to Excel
     */
    public function export(Request $request)
    {
        $query = Audit::query();

        // Admin có thể xem tất cả audits, user thường chỉ xem của mình
        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->searchGlobal($request->search);
        }

        // Filter by date range
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('date', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        if ($request->filled('greenhouse_id')) {
            $query->byGreenhouse($request->greenhouse_id);
        }

        if ($request->filled('qc_name')) {
            $query->byQC($request->qc_name);
        }

        if ($request->filled('worker_name')) {
            $query->byWorker($request->worker_name);
        }

        if ($request->filled('variety_name')) {
            $query->byVariety($request->variety_name);
        }

        // Sort
        $sortBy = $request->input('sort_by', 'date');
        $sortDirection = $request->input('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        // Get all results (no pagination for export)
        $audits = $query->get();

        // Build filter info for Excel header
        $filters = [
            'search' => $request->input('search'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'greenhouse_id' => $request->input('greenhouse_id'),
            'qc_name' => $request->input('qc_name'),
            'worker_name' => $request->input('worker_name'),
            'variety_name' => $request->input('variety_name'),
        ];

        $fileName = 'Kiem_Soat_Chat_Luong_' . now()->format('d-m-Y_His') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AuditsExport($audits, $filters),
            $fileName
        );
    }

    /**
     * Download template Excel for importing audits
     */
    public function downloadTemplate()
    {
        $fileName = 'Audits_Import_Template_' . now()->format('d-m-Y') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AuditsTemplateExport(),
            $fileName
        );
    }

    /**
     * Import audits from Excel file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240', // Max 10MB
        ]);

        try {
            $import = new \App\Imports\AuditsImport();
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));

            $stats = $import->getStats();

            // Build success message
            $message = "Import hoàn tất! ";
            $message .= "Tạo mới: {$stats['created']}. ";

            if ($stats['skipped'] > 0) {
                $message .= "Bỏ qua: {$stats['skipped']}. ";
            }

            // Add errors if any
            if (!empty($stats['errors'])) {
                $errorMessages = implode('<br>', array_slice($stats['errors'], 0, 5)); // Show first 5 errors
                if (count($stats['errors']) > 5) {
                    $errorMessages .= '<br>...và ' . (count($stats['errors']) - 5) . ' lỗi khác.';
                }

                return redirect()->route('audits.index')
                    ->with('warning', $message)
                    ->with('errors', $errorMessages);
            }

            return redirect()->route('audits.index')->with('success', $message);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];

            foreach ($failures as $failure) {
                $errors[] = "Dòng {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return redirect()->route('audits.index')
                ->with('error', 'Import thất bại!')
                ->with('errors', implode('<br>', array_slice($errors, 0, 5)));
        } catch (\Exception $e) {
            return redirect()->route('audits.index')
                ->with('error', 'Import thất bại: ' . $e->getMessage());
        }
    }
}
