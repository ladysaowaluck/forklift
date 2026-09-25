<?php

namespace App\Http\Controllers;

use App\Models\Forklift;
use App\Models\Driver;
use App\Models\Transaction;
use App\Models\Warehouse;

class DashboardController extends Controller
{
    public function index()
    {
        $forklifts_active = Forklift::where('status', 'Active')->count();
        $drivers_active = Driver::where('status', 'Active')->count();
        $transactions_in_progress = Transaction::where('status', 'In Progress')->count();
        $warehouses_active = Warehouse::where('status', 'Active')->count();
        $transactions = Transaction::with(['forklift', 'driver', 'warehouseFrom', 'warehouseTo'])
                                        ->latest()
                                        ->paginate(15);
        return view('dashboard', compact(
                    'forklifts_active',
                    'drivers_active',
                    'transactions_in_progress',
                    'warehouses_active',
                    'transactions' 
        ));    
    }
}
