<?php

namespace App\Http\Controllers;
use App\Models\Warehouse;
use App\Models\WarehouseRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\LengthAwarePaginator;

class WarehouseRouteController extends Controller
{

    public function index()
    {
        $warehouses = Warehouse::orderBy('name')->get();
        $routes = Schema::hasTable('routes')
            ? WarehouseRoute::with(['originWarehouse', 'destWarehouse'])
                ->orderBy('route_id', 'desc')
                ->paginate(15)
            : new LengthAwarePaginator([], 0, 15);

        return view('warehouse_management.routes', compact('warehouses', 'routes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'origin_warehouse_id' => 'required|exists:warehouses,warehouse_id|different:dest_warehouse_id',
            'dest_warehouse_id' => 'required|exists:warehouses,warehouse_id',
            'distance' => 'required|numeric|min:0.01',
        ], [
            'origin_warehouse_id.different' => 'Origin and Destination warehouses must be different.',
        ]);

        WarehouseRoute::updateOrCreate(
            [
                'origin_warehouse_id' => $request->origin_warehouse_id,
                'dest_warehouse_id' => $request->dest_warehouse_id,
            ],
            [
                'distance' => $request->distance,
            ]
        );

        return redirect()->back()->with('success', 'Route distance saved successfully.');
    }


    public function update(Request $request, $id)
    {
        $route = WarehouseRoute::findOrFail($id);

        $request->validate([
            'distance' => 'required|numeric|min:0.01',
        ]);

        $route->update([
            'distance' => $request->distance,
        ]);

        return redirect()->back()->with('success', 'Route distance updated successfully.');
    }

    public function destroy($id)
    {
        $route = WarehouseRoute::findOrFail($id);
        $route->delete();

        return redirect()->back()->with('success', 'Route deleted successfully.');
    }
}
