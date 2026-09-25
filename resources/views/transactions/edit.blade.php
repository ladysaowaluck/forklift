@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="my-4">✏️ แก้ไขงาน</h2>

    <form action="{{ route('transactions.update', $transaction->transaction_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="forklift_id" class="form-label">เลือก Forklift</label>
            <select class="form-select" id="forklift_id" name="forklift_id" required>
                <option value="">เลือก Forklift</option>
                @foreach($forklifts as $forklift)
                    <option value="{{ $forklift->forklift_id }}" {{ $transaction->forklift_id == $forklift->forklift_id ? 'selected' : '' }}>{{ $forklift->model }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="driver_id" class="form-label">เลือก Driver</label>
            <select class="form-select" id="driver_id" name="driver_id" required>
                <option value="">เลือก Driver</option>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->driver_id }}" {{ $transaction->driver_id == $driver->driver_id ? 'selected' : '' }}>{{ $driver->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="warehouse_from" class="form-label">เลือก Warehouse From</label>
            <select class="form-select" id="warehouse_from" name="warehouse_from" required>
                <option value="">เลือก Warehouse From</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->warehouse_id }}" {{ $transaction->warehouse_from == $warehouse->warehouse_id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="warehouse_to" class="form-label">เลือก Warehouse To</label>
            <select class="form-select" id="warehouse_to" name="warehouse_to" required>
                <option value="">เลือก Warehouse To</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->warehouse_id }}" {{ $transaction->warehouse_to == $warehouse->warehouse_id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">สถานะ</label>
            <select class="form-select" id="status" name="status" required>
                <option value="Pending" {{ $transaction->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Active" {{ $transaction->status == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Completed" {{ $transaction->status == 'Completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">อัพเดต</button>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">ยกเลิก</a>
    </form>
</div>
@endsection
