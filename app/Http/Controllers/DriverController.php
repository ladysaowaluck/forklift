<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    // แสดงรายชื่อคนขับ
    public function index()
    {
        $drivers = Driver::all();
        return view('driver_management.index', compact('drivers'));
    }

    // แสดงหน้าสร้างคนขับ
    public function create()
    {
        return view('driver_management.create');
    }

    // สร้างคนขับ
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'license_no' => 'required',
            'status' => 'required',
        ]);

        Driver::create([
            'name' => $request->name,
            'license_no' => $request->license_no,
            'status' => $request->status,
        ]);

        return redirect()->route('driver.management');
    }

    // แสดงหน้าตัวแก้ไขคนขับ
    public function edit($id)
    {
        $driver = Driver::findOrFail($id);
        return view('driver_management.edit', compact('driver'));
    }

    // อัปเดตข้อมูลคนขับ
    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'license_no' => 'required',
            'status' => 'required',
        ]);

        $driver->name = $request->name;
        $driver->license_no = $request->license_no;
        $driver->status = $request->status;
        $driver->save();

        return redirect()->route('driver.management');
    }

    // ลบคนขับ
    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);
        $driver->delete();
        return redirect()->route('driver.management');
    }
}
