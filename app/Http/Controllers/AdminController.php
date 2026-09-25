<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        // ดึงข้อมูลทั้งหมดของ warehouses
        $warehouses = Warehouse::all();
        
        return view('admin.users.edit', compact('user', 'warehouses'));
    }
    

    public function update(Request $request, User $user)
    {
        // ตรวจสอบการกรอกข้อมูล
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin,driver,checker',
            'password' => 'nullable|string|min:8|confirmed',
            'warehouse_id' => 'nullable|exists:warehouses,warehouse_id',
        ]); 
    
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
    
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
    
        if ($request->filled('warehouse_id')) {
            $user->warehouse_id = $request->warehouse_id;
        }
    
        $user->save();
    
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }
    

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete an admin.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }
}
