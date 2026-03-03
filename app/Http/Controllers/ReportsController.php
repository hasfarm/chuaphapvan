<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', now()->format('Y-m-d'));
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $selectedQC = $request->input('qc_name');

        // 1. Công nhân được kiểm tra 6 lần/ngày
        $workerCheckCount = Audit::where('date', $selectedDate)
            ->select('picker_code', 'worker_name', DB::raw('COUNT(*) as check_count'))
            ->groupBy('picker_code', 'worker_name')
            ->get()
            ->map(function ($item) {
                $item->meets_requirement = $item->check_count >= 6;
                return $item;
            });

        // 2. Danh sách công nhân được kiểm tra bởi QC
        $workersByQC = [];
        if ($selectedQC) {
            $workersByQC = Audit::where('qc_name', $selectedQC)
                ->where('date', $selectedDate)
                ->select('picker_code', 'worker_name', DB::raw('COUNT(*) as check_count'), DB::raw('AVG(total_points) as avg_points'))
                ->groupBy('picker_code', 'worker_name')
                ->get();
        }

        // Lấy danh sách QC để filter
        $qcList = Audit::select('qc_name')
            ->distinct()
            ->orderBy('qc_name')
            ->pluck('qc_name');

        // 3. Thống kê điểm <95 và >=95 theo tháng
        $currentMonth = Carbon::parse($selectedMonth);
        $previousMonth = $currentMonth->copy()->subMonth();
        $previousYear = $currentMonth->copy()->subYear();

        $currentStats = $this->getMonthlyPointsStats($currentMonth);
        $previousMonthStats = $this->getMonthlyPointsStats($previousMonth);
        $previousYearStats = $this->getMonthlyPointsStats($previousYear);

        // 4. Thống kê lỗi theo loại
        $errorStats = $this->getErrorStats($selectedMonth);

        return view('reports.index', compact(
            'selectedDate',
            'selectedMonth',
            'selectedQC',
            'workerCheckCount',
            'workersByQC',
            'qcList',
            'currentStats',
            'previousMonthStats',
            'previousYearStats',
            'errorStats'
        ));
    }

    private function getMonthlyPointsStats($month)
    {
        $startDate = $month->copy()->startOfMonth();
        $endDate = $month->copy()->endOfMonth();

        // Tính điểm trung bình của từng công nhân trong tháng
        $workerStats = Audit::whereBetween('date', [$startDate, $endDate])
            ->select('picker_code', 'worker_name', DB::raw('AVG(total_points) as avg_points'), DB::raw('COUNT(*) as check_count'))
            ->groupBy('picker_code', 'worker_name')
            ->get();

        $totalWorkers = $workerStats->count();
        $workersBelow95List = $workerStats->where('avg_points', '<', 95)->values();
        $workersAbove95List = $workerStats->where('avg_points', '>=', 95)->values();
        $workersBelow95 = $workersBelow95List->count();
        $workersAbove95 = $workersAbove95List->count();

        return [
            'month' => $month->format('m/Y'),
            'total' => $totalWorkers,
            'below_95' => $workersBelow95,
            'above_95' => $workersAbove95,
            'below_95_percent' => $totalWorkers > 0 ? round(($workersBelow95 / $totalWorkers) * 100, 2) : 0,
            'above_95_percent' => $totalWorkers > 0 ? round(($workersAbove95 / $totalWorkers) * 100, 2) : 0,
            'below_95_list' => $workersBelow95List,
            'above_95_list' => $workersAbove95List,
        ];
    }

    private function getErrorStats($month)
    {
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        $errorTypes = [
            'qty' => 'Số Lượng (QTY)',
            'uniformity_qty' => 'Số Lượng Đồng Đều (Uniformity QTY)',
            'urc_weight_qty' => 'TL ngọn (URC Weight)',
            'length_qty' => 'Ngắn Dài (Length)',
            'damaged_qty' => 'Số Lượng Hư Hỏng (Damaged QTY)',
            'leaf_burn_qty' => 'Cháy Lá (Leaf Burn)',
            'yellow_spot_qty' => 'Đốm Vàng (Yellow Spot)',
            'wooden_qty' => 'Xơ (Wooden)',
            'dirty_qty' => 'Bẩn (Dirty)',
            'wrong_label_qty' => 'Sai Nhãn (Wrong Label)',
            'pest_disease_qty' => 'Sâu Bệnh (Pest Disease)'
        ];

        $stats = [];
        foreach ($errorTypes as $field => $label) {
            $totalErrors = Audit::whereBetween('date', [$startDate, $endDate])
                ->sum($field);

            $workerErrors = Audit::whereBetween('date', [$startDate, $endDate])
                ->where($field, '>', 0)
                ->select('picker_code', 'worker_name', DB::raw("SUM($field) as error_count"))
                ->groupBy('picker_code', 'worker_name')
                ->orderByDesc('error_count')
                ->limit(10)
                ->get();

            $stats[$field] = [
                'label' => $label,
                'total' => $totalErrors,
                'top_workers' => $workerErrors
            ];
        }

        return $stats;
    }

    public function workerTrend(Request $request)
    {
        $pickerCode = $request->input('picker_code');
        $errorType = $request->input('error_type');

        if (!$pickerCode || !$errorType) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        $trend = Audit::where('picker_code', $pickerCode)
            ->select(
                DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                DB::raw("AVG($errorType) as avg_error")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->get();

        return response()->json($trend);
    }

    public function getWorkerAudits(Request $request)
    {
        $pickerCode = $request->input('picker_code');
        $month = $request->input('month');

        if (!$pickerCode || !$month) {
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        $audits = Audit::where('picker_code', $pickerCode)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('id', 'date', 'total_points', 'plot_code', 'greenhouse_name')
            ->orderBy('date', 'desc')
            ->get();

        return response()->json($audits);
    }

    public function exportWorkerChecks(Request $request)
    {
        $selectedDate = $request->input('date', now()->format('Y-m-d'));

        $workerCheckCount = Audit::where('date', $selectedDate)
            ->select('picker_code', 'worker_name', DB::raw('COUNT(*) as check_count'))
            ->groupBy('picker_code', 'worker_name')
            ->orderBy('picker_code')
            ->get()
            ->map(function ($item) {
                $item->meets_requirement = $item->check_count >= 6;
                return $item;
            });

        $dateFormatted = Carbon::parse($selectedDate)->format('d-m-Y');
        $fileName = "Cong_Nhan_Kiem_Tra_{$dateFormatted}.xlsx";

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\WorkerCheckExport($workerCheckCount, $selectedDate),
            $fileName
        );
    }
}
