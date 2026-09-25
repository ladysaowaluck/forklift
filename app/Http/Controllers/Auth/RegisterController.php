<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Driver; 
use App\Models\Warehouse;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/transactions';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator 
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:user,admin,driver,checker'],
            'license_no' => ['nullable', 'string'],
            'status' => ['nullable', 'in:Active,Suspended,Inactive'],
        ];
    
        // ตรวจสอบว่า role เป็น 'checker' หรือไม่
        if ($data['role'] == 'checker') {
            $rules['warehouse_id'] = ['required', 'exists:warehouses,warehouse_id'];
        }
    
        return Validator::make($data, $rules);
    }
    
    public function showRegistrationForm()
    {
        $warehouses = Warehouse::all();
        return view('auth.register', compact('warehouses')); 
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);
    
        if ($data['role'] == 'driver') {
            Driver::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'license_no' => $data['license_no'],
                'status' => $data['status'] ?? 'Active',
            ]);
        }
    
        if ($data['role'] == 'checker' && isset($data['warehouse_id'])) {
            $user->warehouse_id = $data['warehouse_id'];
            $user->save();  
        }
    
        return $user;
    }
    
    
}
