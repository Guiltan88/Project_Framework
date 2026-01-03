<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StaffController extends Controller
{
    public function performance(Request $request)
    {
        $period = $request->get('period', 'week');
        $sortBy = $request->get('sort', 'total_approved');

        // Determine date range
        $dateRange = $this->getDateRange($period);

        // PERBAIKAN: Gunakan role() scope dari Spatie
        $totalStaff = User::role('staff')->count();
        $activeStaff = User::role('staff')->count(); // Tanpa status filter
        $inactiveStaff = 0;

        // Get approvals in period
        $totalApprovals = Booking::whereBetween('approved_at', $dateRange)
            ->whereNotNull('approved_by')
            ->count();

        $todayApprovals = Booking::whereDate('approved_at', today())
            ->whereNotNull('approved_by')
            ->count();

        // Calculate average response time
        $avgResponseTime = Booking::whereNotNull('approved_at')
            ->whereNotNull('created_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, approved_at)) as avg_time')
            ->first()->avg_time ?? 0;
        $avgResponseTime = round($avgResponseTime);

        // Rejection rate
        $totalProcessed = Booking::whereBetween('approved_at', $dateRange)
            ->whereNotNull('approved_by')
            ->count();
        $totalRejections = Booking::whereBetween('approved_at', $dateRange)
            ->where('status', 'rejected')
            ->count();
        $rejectionRate = $totalProcessed > 0 ? round(($totalRejections / $totalProcessed) * 100, 1) : 0;

        // Average daily approvals
        $daysInPeriod = $dateRange[0]->diffInDays($dateRange[1]) + 1;
        $avgDailyApprovals = $daysInPeriod > 0 ? round($totalApprovals / $daysInPeriod, 1) : 0;

        // Fastest response time
        $fastestResponseTime = Booking::whereNotNull('approved_at')
            ->whereNotNull('created_at')
            ->selectRaw('MIN(TIMESTAMPDIFF(MINUTE, created_at, approved_at)) as min_time')
            ->first()->min_time ?? 0;

        // Pending actions (bookings that are pending approval)
        $pendingActions = Booking::where('status', 'pending')->count();

        // Completion rate (approved bookings / total processed bookings)
        $completionRate = $totalProcessed > 0 ? round(($totalApprovals / $totalProcessed) * 100, 1) : 0;

        // **FIXED QUERY: Gunakan whereHas untuk filter role staff**
        $staffs = User::whereHas('roles', function ($query) {
                $query->where('name', 'staff');
            })
            ->select([
                'users.*',
                \DB::raw("(
                    SELECT COUNT(*)
                    FROM bookings
                    WHERE bookings.approved_by = users.id
                    AND bookings.approved_at BETWEEN '{$dateRange[0]}' AND '{$dateRange[1]}'
                ) as total_processed"),
                \DB::raw("(
                    SELECT SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END)
                    FROM bookings
                    WHERE bookings.approved_by = users.id
                    AND bookings.approved_at BETWEEN '{$dateRange[0]}' AND '{$dateRange[1]}'
                ) as total_approved"),
                \DB::raw("(
                    SELECT SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END)
                    FROM bookings
                    WHERE bookings.approved_by = users.id
                    AND bookings.approved_at BETWEEN '{$dateRange[0]}' AND '{$dateRange[1]}'
                ) as total_rejected"),
                \DB::raw("(
                    SELECT ROUND(AVG(TIMESTAMPDIFF(MINUTE, created_at, approved_at)), 0)
                    FROM bookings
                    WHERE bookings.approved_by = users.id
                    AND bookings.approved_at BETWEEN '{$dateRange[0]}' AND '{$dateRange[1]}'
                ) as avg_response_time"),
                \DB::raw("(
                    SELECT ROUND(
                        (SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) / NULLIF(COUNT(*), 0)) * 100,
                        1
                    )
                    FROM bookings
                    WHERE bookings.approved_by = users.id
                    AND bookings.approved_at BETWEEN '{$dateRange[0]}' AND '{$dateRange[1]}'
                ) as rejection_rate")
            ])
            ->orderBy($sortBy, 'desc')
            ->paginate(10);

        // Top performers (simplified)
        $topPerformers = User::role('staff')
            ->withCount(['approvedBookings' => function($query) use ($dateRange) {
                $query->whereBetween('approved_at', $dateRange);
            }])
            ->orderBy('approved_bookings_count', 'desc')
            ->limit(5)
            ->get();

        // Chart data
        $chartData = [];
        $chartLabels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('D');

            $count = Booking::whereDate('approved_at', $date)
                ->where('status', 'approved')
                ->count();
            $chartData[] = $count;
        }

        return view('admin.staff.performance', compact(
            'totalStaff',
            'activeStaff',
            'inactiveStaff',
            'totalApprovals',
            'todayApprovals',
            'avgResponseTime',
            'rejectionRate',
            'totalProcessed',
            'totalRejections',
            'avgDailyApprovals',
            'fastestResponseTime',
            'pendingActions',
            'completionRate',
            'staffs',
            'topPerformers',
            'chartData',
            'chartLabels',
            'period',
            'sortBy'
        ));
    }

    private function getDateRange($period)
    {
        $now = Carbon::now();

        switch ($period) {
            case 'today':
                return [$now->startOfDay(), $now->endOfDay()];
            case 'week':
                return [$now->startOfWeek(), $now->endOfWeek()];
            case 'month':
                return [$now->startOfMonth(), $now->endOfMonth()];
            case 'year':
                return [$now->startOfYear(), $now->endOfYear()];
            default:
                return [$now->startOfWeek(), $now->endOfWeek()];
        }
    }

    public function attendance(Request $request)
    {
        // PERBAIKAN: Gunakan role() scope
        $staffs = User::role('staff')->paginate(10);
        return view('admin.staff.attendance', compact('staffs'));
    }

    public function reports()
    {
        // Report generation
        $monthlyReport = Booking::selectRaw('
                MONTH(approved_at) as month,
                YEAR(approved_at) as year,
                COUNT(*) as total_approvals,
                approved_by as staff_id
            ')
            ->whereNotNull('approved_by')
            ->whereYear('approved_at', now()->year)
            ->groupBy('month', 'year', 'approved_by')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.staff.reports', compact('monthlyReport'));
    }

    // AJAX endpoint for staff details
    public function performanceDetails($id)
    {
        $staff = User::findOrFail($id);

        // PERBAIKAN: Pastikan user adalah staff
        abort_if(!$staff->hasRole('staff'), 404);

        // Get detailed statistics for this staff
        $stats = Booking::where('approved_by', $id)
            ->selectRaw('
                COUNT(*) as total_processed,
                SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected,
                ROUND(AVG(TIMESTAMPDIFF(MINUTE, created_at, approved_at)), 0) as avg_response_time,
                MIN(TIMESTAMPDIFF(MINUTE, created_at, approved_at)) as fastest_response,
                MAX(TIMESTAMPDIFF(MINUTE, created_at, approved_at)) as slowest_response
            ')
            ->first();

        // Recent approvals
        $recentApprovals = Booking::where('approved_by', $id)
            ->with(['user', 'room'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.staff._details', compact('staff', 'stats', 'recentApprovals'));
    }

    // BONUS: Method untuk filter staff dengan query yang aman
    public function filterStaff(Request $request)
    {
        $query = User::query()->role('staff');

        // Filter berdasarkan nama
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan departemen
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        $staffs = $query->paginate(10);

        return view('admin.staff.partials.staff_list', compact('staffs'));
    }
}
