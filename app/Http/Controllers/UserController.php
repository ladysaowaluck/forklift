<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Warehouse;
use App\Models\Driver;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
    
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where('name', 'like', "%$searchTerm%")
                    ->orWhere('email', 'like', "%$searchTerm%");
        }
    
        $users = $query->with('warehouse')->paginate(10);
    
        return view('user_management.index', compact('users'));
    }


    public function create()
    {

        $warehouses = Warehouse::all();

        return view('user_management.create', compact('warehouses'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,user,driver,checker'],

            'warehouse_id' => ['required_if:role,checker', 'nullable', 'exists:warehouses,warehouse_id'],

            'license_no' => ['required_if:role,driver', 'nullable', 'string', 'max:255'],

            'status' => ['required_if:role,driver', 'nullable', 'string'],
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'warehouse_id' => ($request->role === 'checker') ? $request->warehouse_id : null,
        ]);

        if ($request->role === 'driver') {
            Driver::create([
                'user_id' => $user->id, 
                'name' => $user->name,
                'license_no' => $request->license_no,
                'status' => $request->status,
            ]);
        }
    
        return redirect()->route('user.management')->with('success', 'User created successfully.');
    }
    
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('user_management.show', compact('user'));
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $user->load('driver');
        $warehouses = Warehouse::all(); 
        return view('user_management.edit', compact('user', 'warehouses'));
    }
    
 
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:user,admin,driver,checker',
            'warehouse_id' => 'nullable|exists:warehouses,warehouse_id',
            'license_no' => 'nullable|string|max:50',
            'status' => 'nullable|in:Active,Suspended,Inactive',

            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);
    
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'warehouse_id' => ($request->role === 'checker') ? $request->warehouse_id : null,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);
    
        if ($user->role === 'driver' && $request->role !== 'driver') {
            $driver = Driver::where('user_id', $user->id)->first();
            if ($driver) {
                $driver->delete();
            }
        }
    
        if ($request->role === 'driver') {
            Driver::updateOrCreate(
                ['user_id' => $user->id], 
                [
                    'name' => $user->name,
                    'license_no' => $request->license_no,
                    'status' => $request->status,
                ]
            );
        }
    
        return redirect()->route('user.management')->with('success', 'User updated successfully');
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);
    
        if ($user->role === 'driver') {
            $driver = Driver::where('user_id', $user->id)->first();
            if ($driver) {
                $driver->delete();
            }
        }
    
        $user->delete();
    
        return redirect()->route('user.management')->with('success', 'User deleted successfully');
    }
}
