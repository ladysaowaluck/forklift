@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="my-4">📊 รายการขนย้ายสินค้า (Transactions)</h2>

    <a href="{{ route('transactions.create') }}" class="btn btn-primary mb-3">➕ เพิ่มงานใหม่</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Forklift</th>
                <th>Driver</th>
                <th>From Warehouse</th>
                <th>To Warehouse</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->transaction_id }}</td>
                    <td>{{ $transaction->forklift->model ?? 'N/A' }}</td>
                    <td>{{ $transaction->driver->name ?? 'N/A' }}</td>
                    <td>{{ $transaction->warehouseFrom->name ?? 'N/A' }}</td>
                    <td>{{ $transaction->warehouseTo->name ?? 'N/A' }}</td>
                    <td>
                        <span class="badge 
                            @if($transaction->status == 'Pending') bg-warning
                            @elseif($transaction->status == 'Active') bg-primary
                            @else bg-success @endif">
                            {{ $transaction->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('transactions.show', $transaction->transaction_id) }}" class="btn btn-info btn-sm">ดูรายละเอียด</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">ไม่มีข้อมูล Transaction</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
