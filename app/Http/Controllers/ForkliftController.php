<?php

namespace App\Http\Controllers;

use App\Models\Forklift;
use Illuminate\Http\Request;

class ForkliftController extends Controller
{
    // แสดงรายการ Forklifts
    public function index()
    {
        $forklifts = Forklift::paginate(10);
        return view('forklift_management.index', compact('forklifts'));
    }

    // แสดงหน้าฟอร์มสร้าง Forklift
    public function create()
    {
        return view('forklift_management.create');
    }

    public function store(Request $request)
    {
        // Validate ข้อมูลที่ส่งมา
        $request->validate([
            'model' => 'required',
            'plate_number' => 'required',
            'status' => 'required|in:Active,Maintenance,Inactive',
        ]);

        // สร้าง Forklift ใหม่ในฐานข้อมูล
        Forklift::create([
            'model' => $request->model,
            'plate_number' => $request->plate_number,
            'status' => $request->status,
        ]);

        return redirect()->route('forklift.management');
    }

    public function edit($forklift_id)
    {
        $forklift = Forklift::where('forklift_id', $forklift_id)->firstOrFail();
        return view('forklift_management.edit', compact('forklift'));
    }

    public function update(Request $request, $forklift_id)
    {
        $forklift = Forklift::where('forklift_id', $forklift_id)->firstOrFail();

        $request->validate([
            'model' => 'required',
            'plate_number' => 'required',
            'status' => 'required',
        ]);

        $forklift->update([
            'model' => $request->model,
            'plate_number' => $request->plate_number,
            'status' => $request->status,
        ]);

        return redirect()->route('forklift.management');
    }

    // ลบ Forklift
    public function destroy($forklift_id)
    {
        $forklift = Forklift::where('forklift_id', $forklift_id)->firstOrFail();
        $forklift->delete();

        return redirect()->route('forklift.management');
    }
}
