<?php

namespace App\Http\Controllers;

use App\Models\Forklift;
use App\Models\Driver;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!in_array(strtolower($user->role ?? ''), ['admin', 'checker'])) {
            abort(403);
        }

        $today = Carbon::today();

        // Today's booking summary
        $todayTotal = Transaction::whereDate('created_at', $today)->where('status', '!=', 'Deleted')->count();
        $todayPending = Transaction::whereDate('created_at', $today)->where('status', 'Pending')->count();
        $todayActive = Transaction::whereDate('created_at', $today)->whereIn('status', ['Accepted', 'In Transit'])->count();
        $todayCompleted = Transaction::whereDate('created_at', $today)->where('status', 'Complete')->count();

        // Resource counts
        $totalDrivers = Driver::count();
        $totalForklifts = Forklift::count();

        // Recent 5 transactions today
        $recentTransactions = Transaction::with(['forklift', 'driver', 'warehouseFrom', 'warehouseTo'])
            ->whereDate('created_at', $today)
            ->where('status', '!=', 'Deleted')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        // Gemini : Aggregate total pallets and items transported by route for today, 7 days, and 1 month (Stacked Bar Chart)
        $routeTransportData = [
            'today' => $this->getRouteTransportData(Carbon::today()->startOfDay()),
            'week'  => $this->getRouteTransportData(Carbon::today()->subDays(6)->startOfDay()),
            'month' => $this->getRouteTransportData(Carbon::today()->subDays(29)->startOfDay()),
        ];

        // Gemini : Aggregate average task completion time and transit time by route for today, 7 days, and 1 month (Horizontal Bar Chart)
        $routeCycleTimeData = [
            'today' => $this->getRouteCycleTimeData(Carbon::today()->startOfDay()),
            'week'  => $this->getRouteCycleTimeData(Carbon::today()->subDays(6)->startOfDay()),
            'month' => $this->getRouteCycleTimeData(Carbon::today()->subDays(29)->startOfDay()),
        ];

        return view('dashboard.index', compact(
            'todayTotal',
            'todayPending',
            'todayActive',
            'todayCompleted',
            'totalDrivers',
            'totalForklifts',
            'recentTransactions',
            'routeTransportData',
            'routeCycleTimeData'
        ));
    }

    // Gemini : Query corridor transport volume (pallets vs items) by route from transactions and transaction_details
    private function getRouteTransportData(Carbon $startDate): array
    {
        $rows = DB::table('transactions as t')
            ->join('transaction_details as td', 't.transaction_id', '=', 'td.transaction_id')
            ->join('warehouses as wf', 't.warehouse_from', '=', 'wf.warehouse_id')
            ->join('warehouses as wt', 't.warehouse_to', '=', 'wt.warehouse_id')
            ->where('t.status', '!=', 'Deleted')
            ->where('t.created_at', '>=', $startDate)
            ->select(
                'wf.name as origin',
                'wt.name as destination',
                DB::raw("SUM(CASE WHEN td.unit IS NOT NULL AND (LOWER(td.unit) LIKE '%pallet%' OR LOWER(td.unit) LIKE '%พาเลท%') THEN td.quantity ELSE 0 END) as pallets"),
                DB::raw("SUM(CASE WHEN td.unit IS NULL OR (LOWER(td.unit) NOT LIKE '%pallet%' AND LOWER(td.unit) NOT LIKE '%พาเลท%') THEN td.quantity ELSE 0 END) as items"),
                DB::raw("SUM(td.quantity) as total_volume")
            )
            ->groupBy('t.warehouse_from', 't.warehouse_to', 'wf.name', 'wt.name')
            ->orderByDesc('total_volume')
            ->limit(10)
            ->get();

        $labels = [];
        $pallets = [];
        $items = [];
        $totals = [];

        foreach ($rows as $row) {
            $labels[] = ($row->origin ?: 'Unknown') . ' → ' . ($row->destination ?: 'Unknown');
            $pallets[] = (int) $row->pallets;
            $items[] = (int) $row->items;
            $totals[] = (int) $row->total_volume;
        }

        return [
            'labels'  => $labels,
            'pallets' => $pallets,
            'items'   => $items,
            'totals'  => $totals,
        ];
    }

    // Gemini : Query transit efficiency and average cycle times between warehouse pairs from transactions and warehouses
    private function getRouteCycleTimeData(Carbon $startDate): array
    {
        $rows = DB::table('transactions as t')
            ->join('warehouses as wf', 't.warehouse_from', '=', 'wf.warehouse_id')
            ->join('warehouses as wt', 't.warehouse_to', '=', 'wt.warehouse_id')
            ->where('t.status', '!=', 'Deleted')
            ->where('t.status', '!=', 'Rejected')
            ->where('t.created_at', '>=', $startDate)
            ->whereNotNull('t.started_at')
            ->where(function ($q) {
                $q->whereNotNull('t.completed_at')
                  ->orWhereNotNull('t.arrived_at');
            })
            ->select(
                'wf.name as origin',
                'wt.name as destination',
                DB::raw("ROUND(AVG(CASE WHEN t.arrived_at IS NOT NULL AND t.arrived_at >= t.started_at THEN TIMESTAMPDIFF(SECOND, t.started_at, t.arrived_at) / 60 ELSE NULL END), 1) as avg_transit_mins"),
                DB::raw("ROUND(AVG(CASE WHEN t.completed_at IS NOT NULL AND t.completed_at >= t.started_at THEN TIMESTAMPDIFF(SECOND, t.started_at, t.completed_at) / 60 ELSE (CASE WHEN t.arrived_at IS NOT NULL AND t.arrived_at >= t.started_at THEN TIMESTAMPDIFF(SECOND, t.started_at, t.arrived_at) / 60 ELSE NULL END) END), 1) as avg_cycle_mins"),
                DB::raw("COUNT(t.transaction_id) as total_trips")
            )
            ->groupBy('t.warehouse_from', 't.warehouse_to', 'wf.name', 'wt.name')
            ->orderByDesc('avg_cycle_mins')
            ->limit(10)
            ->get();

        $labels = [];
        $transitTimes = [];
        $cycleTimes = [];
        $tripCounts = [];

        foreach ($rows as $row) {
            $labels[] = ($row->origin ?: 'Unknown') . ' → ' . ($row->destination ?: 'Unknown');
            $transitTimes[] = (float) ($row->avg_transit_mins ?? 0);
            $cycleTimes[] = (float) ($row->avg_cycle_mins ?? 0);
            $tripCounts[] = (int) $row->total_trips;
        }

        return [
            'labels'       => $labels,
            'transitTimes' => $transitTimes,
            'cycleTimes'   => $cycleTimes,
            'tripCounts'   => $tripCounts,
        ];
    }
}
