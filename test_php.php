<?php


namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Forklift;
use App\Models\Driver;
use App\Models\Warehouse;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage; // เพิ่มสำหรับการจัดการไฟล์


class TransactionController extends Controller
{


    public function export(Request $request)
    {
        $filters = $request->only([
            'forklift_id', 
            'driver_id', 
            'warehouse_from', 
            'warehouse_to', 
            'status',
            'driver_rating' 
        ]);
    
        return Excel::download(new TransactionsExport($filters), 'transactions_report.xlsx');
    }


    public function index(Request $request)
    {
        $forklifts = Forklift::all();
        $drivers = Driver::all();
        $warehouses = Warehouse::all();


        $query = Transaction::with(['forklift', 'driver', 'warehouseFrom', 'warehouseTo']);
        $query->where('status', '!=', 'Deleted');




        if ($request->filled('forklift_id')) {
            $query->where('forklift_id', (int) $request->forklift_id);
        }


        if ($request->filled('driver_id')) {
            $query->where('driver_id', (int) $request->driver_id);
        }


        if ($request->filled('warehouse_from')) {
            $query->where('warehouse_from', (int) $request->warehouse_from);
        }


        if ($request->filled('warehouse_to')) {
            $query->where('warehouse_to', (int) $request->warehouse_to);
        }


        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }


        if ($request->filled('driver_rating')) {
            $query->where('driver_rating', (int) $request->driver_rating);
        }


        if (auth()->user()->role == 'driver') {
            $driver = Driver::where('user_id', auth()->id())->first();


            if ($driver) {
                $query->where(function ($subQuery) use ($driver) {
                    $subQuery->where('driver_id', $driver->driver_id)
                            ->orWhere('status', 'Pending');
                });
            } else {
                $query->where('status', 'Pending');
            }
        }


        if (auth()->user()->role == 'driver') {
            $query->orderByRaw("CASE WHEN status = 'Pending' THEN 1 ELSE 2 END ASC")
                ->orderBy('created_at', 'ASC');
            
            $transactions = $query->paginate(10);
        } else {
            $transactions = $query->latest()->paginate(10);
        }


        // ส่วนของการนับจำนวนเพื่อใช้ใน Dashboard
        $pendingCount = Transaction::where('status', 'Pending')->count();
        $assignedCount = Transaction::where('status', 'Assigned')->count();
        $inProgressCount = Transaction::where('status', 'In Progress')->count();
        $arrivedCount = Transaction::where('status', 'Arrived')->count();
        $completedCount = Transaction::where('status', 'Completed')->count();
        $rejectedCount = Transaction::where('status', 'Rejected')->count(); 
        $totalTransactions = Transaction::where('status', '!=', 'Deleted')->count();
        
        return view('transactions.index', compact(
            'forklifts', 'drivers', 'warehouses', 'transactions','pendingCount',
            'assignedCount', 'inProgressCount', 'arrivedCount',
            'completedCount', 'rejectedCount', 'totalTransactions'
        ));
    }
        
    public function create()
    {
        $forklifts = Forklift::all();
        $drivers = Driver::all();
        $warehouses = Warehouse::all();
        return view('transactions.create', compact('forklifts', 'drivers', 'warehouses'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'warehouse_from' => 'required|exists:warehouses,warehouse_id',
            'warehouse_to' => 'required|exists:warehouses,warehouse_id',
            'details' => 'required|array',
            'details.*.item_name' => 'required|string',
            'details.*.quantity' => 'required|integer',
            'details.*.unit' => 'required|string',
            'details.*.description' => 'nullable|string',
        ]);
        
        $transaction = Transaction::create([
            'warehouse_from' => $request->warehouse_from,
            'warehouse_to' => $request->warehouse_to,
            'created_by_user_id' => auth()->id(),
            'status' => 'Pending', 
        ]);
        
        foreach ($request->details as $detail) {
            $transaction->details()->create($detail);
        }
        
        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully!');
    }


    public function show($id)
    {
        $forklifts = Forklift::all();
        $transaction = Transaction::with(['forklift', 'driver', 'warehouseFrom', 'warehouseTo', 'details'])->findOrFail($id);
        return view('transactions.show', compact('transaction', 'forklifts'));
    }
    
    public function edit($transaction_id)
    {
        $transaction = Transaction::findOrFail($transaction_id);
        $forklifts = Forklift::all();
        $drivers = Driver::all();
        $warehouses = Warehouse::all();
        return view('transactions.edit', compact('transaction', 'forklifts', 'drivers', 'warehouses'));
    }
    
    public function update(Request $request, $transaction_id)
    {
        $request->validate([
            'forklift_id' => 'required|exists:forklifts,forklift_id',
            'driver_id' => 'required|exists:drivers,driver_id',
            'warehouse_from' => 'required|exists:warehouses,warehouse_id',
            'warehouse_to' => 'required|exists:warehouses,warehouse_id',
            'status' => 'required|in:Pending,Active,Completed',
        ]);


        $transaction = Transaction::findOrFail($transaction_id);
        $transaction->update($request->all());
        return redirect()->route('transactions.index')->with('success', 'Updated successfully!');
    }


    public function destroy($transaction_id)
    {
        $transaction = Transaction::findOrFail($transaction_id);
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully!');
    }


    public function claimTask(Request $request, $transaction_id)
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'driver') {
            return redirect()->back()->with('error', 'You are not authorized to claim this task.');
        }
        $transaction = Transaction::findOrFail($transaction_id);
        
        if ($transaction->status !== 'Pending') {
            return redirect()->back()->with('error', 'This task is no longer available for claiming.');
        }


        $driver = Driver::where('user_id', $user->id)->firstOrFail();
        $request->validate(['forklift_id' => 'required|exists:forklifts,forklift_id']);
        
        $transaction->update([
            'driver_id'   => $driver->driver_id, 
            'status'      => 'Assigned',
            'forklift_id' => $request->input('forklift_id'),
            'claimed_at'  => now(),
        ]);
        
        return redirect()->back()->with('success', 'You have successfully claimed this task.');
    }


    public function rejectTask(Request $request, $transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        $user = auth()->user();


        if ($user->role !== 'driver') {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์กดปฏิเสธ');
        }


        $driver = Driver::where('user_id', $user->id)->first();


        if (!$driver) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลพนักงานขับรถของคุณ');
        }


        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);


        $transaction->update([
            'status' => 'Rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => now(),
            'rejected_by_driver_id' => $driver->driver_id,
        ]);


        return redirect()->route('transactions.index')->with('success', 'ปฏิเสธงานเรียบร้อยแล้ว');
    }


    public function acceptTask(Request $request, $transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        
        if ($transaction->status !== 'Assigned') {
            return redirect()->route('transactions.show', $transactionId)->with('error', 'Cannot start task: Status is ' . $transaction->status);
        }


        $driver = Driver::where('user_id', auth()->id())->firstOrFail();
        if ($transaction->driver_id != $driver->driver_id) {
            return redirect()->route('transactions.show', $transactionId)->with('error', 'This task is not assigned to you.');
        }
        $request->validate(['start_task_image' => 'required|image|max:5120']);
        $imagePath = $request->file('start_task_image')->store('start_images', 'public');
        
        $transaction->status = 'In Progress';
        $transaction->start_task_image_path = $imagePath;
        $transaction->started_at = now();
        $transaction->save();
    
        return redirect()->route('transactions.show', $transactionId)->with('success', 'You have successfully accepted the task.');
    }
    
    public function arrive(Request $request, $transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        
        if ($transaction->status !== 'In Progress') {
            return redirect()->route('transactions.show', $transactionId)->with('error', 'Invalid action: Task is ' . $transaction->status);
        }


        if (auth()->user()->role == 'driver' && $transaction->driver->user_id == auth()->id()) {
            $request->validate(['end_task_image' => 'required|image|max:5120']);
            $imagePath = $request->file('end_task_image')->store('end_images', 'public');


            $transaction->status = 'Arrived';
            $transaction->end_task_image_path = $imagePath;
            $transaction->arrived_at = now();
            $transaction->save();
            return redirect()->route('transactions.show', $transactionId)->with('success', 'You have successfully marked the transaction as arrived.');
        }
        return redirect()->route('transactions.show', $transactionId)->with('error', 'This action cannot be performed.');
    }


    // ฟังก์ชันใหม่: แก้ไขรูปภาพเริ่มต้นงาน
    public function updateStartImage(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $request->validate(['start_task_image' => 'required|image|max:5120']);


        // ลบรูปเก่าออกจาก Storage ถ้ามีอยู่จริง
        if ($transaction->start_task_image_path) {
            Storage::disk('public')->delete($transaction->start_task_image_path);
        }


        // บันทึกรูปใหม่
        $imagePath = $request->file('start_task_image')->store('start_images', 'public');
        $transaction->update(['start_task_image_path' => $imagePath]);


        return redirect()->back()->with('success', 'แก้ไขรูปภาพเริ่มงานเรียบร้อยแล้ว');
    }


    // ฟังก์ชันใหม่: แก้ไขรูปภาพจบงาน (Arrived)
    public function updateEndImage(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $request->validate(['end_task_image' => 'required|image|max:5120']);


        // ลบรูปเก่าออกจาก Storage ถ้ามีอยู่จริง
        if ($transaction->end_task_image_path) {
            Storage::disk('public')->delete($transaction->end_task_image_path);
        }


        // บันทึกรูปใหม่
        $imagePath = $request->file('end_task_image')->store('end_images', 'public');
        $transaction->update(['end_task_image_path' => $imagePath]);


        return redirect()->back()->with('success', 'แก้ไขรูปภาพจบงานเรียบร้อยแล้ว');
    }
    
    public function markAsComplete(Request $request, $transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);


        if ($transaction->status !== 'Arrived') {
            return redirect()->route('transactions.show', $transactionId)->with('error', 'Invalid action: Task is ' . $transaction->status);
        }


        if (auth()->user()->role == 'checker') {
            $transaction->status = 'Completed';
            $transaction->driver_rating = $request->input('driver_rating');
            $transaction->driver_comment = $request->input('driver_comment');
            $transaction->completed_at = now();
            $transaction->save();


            return redirect()->route('transactions.show', $transactionId)->with('success', 'Transaction completed and driver rated.');
        }
        return redirect()->route('transactions.show', $transactionId)->with('error', 'Unauthorized action or invalid status.');
    }
    
    public function monitor()
    {
        return view('transactions.monitor');
    }


    public function getActiveTransactionsApi()
    {
        $transactions = Transaction::with(['forklift', 'driver', 'warehouseFrom', 'warehouseTo'])
            ->latest() 
            ->take(30)
            ->get();
            
        $transactions->each(function ($transaction) {
            $transaction->updated_human = Carbon::parse($transaction->updated_at)->diffForHumans();
            $transaction->updated_raw = $transaction->updated_at->toIso8601String();
        });


        return response()->json($transactions);
    }


    public function softDelete($transaction_id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'UNAUTHORIZED ACTION.');
        }


        $transaction = Transaction::findOrFail($transaction_id);
        
        $transaction->status = 'Deleted'; 
        
        $transaction->save(); 


        return redirect()->route('transactions.index')
                        ->with('success', "Transaction #{$transaction->transaction_id} has been deleted.");
    }
}