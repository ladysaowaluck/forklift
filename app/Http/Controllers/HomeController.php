<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // ต้องทำการตรวจสอบการล็อกอินก่อนทุกครั้งที่เข้าใช้งานหน้า home
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');  // เปลี่ยนเป็นชื่อหน้าที่ต้องการ redirect ไปหลัง login
    }
}
