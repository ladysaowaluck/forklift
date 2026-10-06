<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ForkliftController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\Auth\RegisterController; 
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AdminController;


Route::get('/', function () {
    return redirect()->route('login');
});

// Redirect /dashboard to /transactions to prevent 404 errors for existing bookmarks and legacy links without impacting other pages.
Route::redirect('/dashboard', '/transactions');

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.submit');

// เปลี่ยนการลงทะเบียนมาใช้ RegisterController
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');



Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Warehouse Management
Route::get('/warehouse-management', [WarehouseController::class, 'index'])->name('warehouse.management');
Route::get('/warehouse-management-list', [WarehouseController::class, 'warehouseIndex'])->name('warehouse.management.list');

// Forklift Management
Route::get('/forklift-management', [ForkliftController::class, 'index'])->name('forklift.management');
Route::get('/forklift-management-list', [ForkliftController::class, 'forkliftIndex'])->name('forklift.management.list');

// Routes for creating and editing entities
Route::middleware(['auth'])->group(function () {

    // Warehouse Management
    Route::get('/warehouses/create', [WarehouseController::class, 'create'])->name('warehouses.create');
    Route::post('/warehouses', [WarehouseController::class, 'store'])->name('warehouses.store');
    Route::get('/warehouses/{id}/edit', [WarehouseController::class, 'edit'])->name('warehouses.edit');
    Route::put('/warehouses/{id}', [WarehouseController::class, 'update'])->name('warehouses.update');
    Route::delete('/warehouses/{id}', [WarehouseController::class, 'destroy'])->name('warehouses.destroy');
    Route::get('/warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');

    // Forklift Management
    Route::get('/forklifts/create', [ForkliftController::class, 'create'])->name('forklifts.create');
    Route::post('/forklifts', [ForkliftController::class, 'store'])->name('forklifts.store');
    Route::get('/forklifts/{forklift_id}/edit', [ForkliftController::class, 'edit'])->name('forklifts.edit');
    Route::put('/forklifts/{forklift_id}', [ForkliftController::class, 'update'])->name('forklifts.update');
    Route::delete('/forklifts/{forklift_id}', [ForkliftController::class, 'destroy'])->name('forklifts.destroy');
});


// --- ส่วนจัดการ Transaction (รวมฟังก์ชัน Reject และแก้ไขรูปภาพ) ---
Route::middleware(['auth'])->group(function () {
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction_id}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{transaction_id}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction_id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
    
    // Action Routes สำหรับระบบ Forklift
    Route::post('/transactions/{transaction_id}/accept', [TransactionController::class, 'acceptTask'])->name('transactions.acceptTask');
    Route::post('/transactions/{transactionId}/arrive', [TransactionController::class, 'arrive'])->name('transactions.arrive');
    Route::post('/transactions/{transaction}/complete', [TransactionController::class, 'markAsComplete'])->name('transactions.complete');
    Route::post('/transactions/{transaction}/claim', [TransactionController::class, 'claimTask'])->name('transactions.claimTask');
    
    // ฟังก์ชันสำหรับ Reject งาน
    Route::post('/transactions/{transactionId}/reject', [TransactionController::class, 'rejectTask'])->name('transactions.rejectTask');

    // ฟังก์ชันสำหรับแก้ไขรูปภาพ (Start & End Images)
    Route::post('/transactions/{id}/update-start-image', [TransactionController::class, 'updateStartImage'])->name('transactions.updateStartImage');
    Route::post('/transactions/{id}/update-end-image', [TransactionController::class, 'updateEndImage'])->name('transactions.updateEndImage');

    Route::delete('/transactions/{transaction_id}/delete', [TransactionController::class, 'softDelete'])->name('transactions.softDelete');
});

Route::post('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');


Route::middleware(['auth'])->group(function () {
    Route::get('/user-management/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user-management/store', [UserController::class, 'store'])->name('user.store');

    Route::get('/user-management', [UserController::class, 'index'])->name('user.management');

    Route::get('/user-management/{id}', [UserController::class, 'show'])->name('user.show');
    Route::get('/user-management/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user-management/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user-management/{id}', [UserController::class, 'destroy'])->name('user.destroy');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/monitor', [App\Http\Controllers\TransactionController::class, 'monitor'])
        ->name('monitor.index');
});


Route::get('/api/active-transactions', [App\Http\Controllers\TransactionController::class, 'getActiveTransactionsApi'])
    ->name('api.active_transactions');


Route::get('/barcode-scanner', function () {
    return view('barcode.scanner');
})->name('barcode.scanner');