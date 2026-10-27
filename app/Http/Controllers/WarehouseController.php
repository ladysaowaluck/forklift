<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\WarehouseRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::paginate(10);
        $allWarehouses = Warehouse::orderBy('name')->get();
        $routes = Schema::hasTable('routes')
            ? WarehouseRoute::with(['originWarehouse', 'destWarehouse'])->orderBy('route_id', 'desc')->get()
            : collect();
        return view('warehouse_management.index', compact('warehouses', 'allWarehouses', 'routes'));
    }

    public function create()
    {
        return view('warehouse_management.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required',
        ]);

        Warehouse::create([
            'name' => $request->name,
            'location' => $request->location,
        ]);

        return redirect()->route('warehouse.management');
    }
    public function edit($id)
    {
        $warehouse = Warehouse::findOrFail($id);

        return view('warehouse_management.edit', compact('warehouse'));
    }

    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);

        $warehouse->update($request->all());

        return redirect()->route('warehouse.management');
    }

    public function destroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->delete();
        return redirect()->route('warehouse.management');
    }
}
