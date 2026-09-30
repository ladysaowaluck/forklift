@extends('layouts.app')

@section('content')
<!-- Lady: <div class="container"> -->
<!-- Gemini: Added fluid padding to keep the form from stretching to edge on mobile screens -->
<div class="container py-3 px-2 px-md-4">
    <!-- Gemini: Wrapped form in a responsive card wrapper to constrain layout width on mobile/desktop -->
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-3 p-md-4">
                    <!-- Lady: <h2 class="my-4">✏️ แก้ไขงาน</h2> -->
                    <!-- Gemini: Reduced heading font size dynamically for mobile displays -->
                    <h2 class="mb-4 text-center fs-4 fs-md-3 fw-bold">✏️ แก้ไขงาน</h2>

                    <form action="{{ route('transactions.update', $transaction->transaction_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Gemini: Grid layout that displays side-by-side on desktop (md+) and stacks vertically on mobile -->
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="forklift_id" class="form-label small fw-semibold">เลือก Forklift</label>
                                <!-- Gemini: Applied small select sizing for better proportions on mobile devices -->
                                <select class="form-select form-select-sm form-select-md-normal" id="forklift_id" name="forklift_id" required>
                                    <option value="">เลือก Forklift</option>
                                    @foreach($forklifts as $forklift)
                                        <option value="{{ $forklift->forklift_id }}" {{ $transaction->forklift_id == $forklift->forklift_id ? 'selected' : '' }}>{{ $forklift->model }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="driver_id" class="form-label small fw-semibold">เลือก Driver</label>
                                <select class="form-select form-select-sm form-select-md-normal" id="driver_id" name="driver_id" required>
                                    <option value="">เลือก Driver</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->driver_id }}" {{ $transaction->driver_id == $driver->driver_id ? 'selected' : '' }}>{{ $driver->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="warehouse_from" class="form-label small fw-semibold">เลือก Warehouse From</label>
                                <select class="form-select form-select-sm form-select-md-normal" id="warehouse_from" name="warehouse_from" required>
                                    <option value="">เลือก Warehouse From</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->warehouse_id }}" {{ $transaction->warehouse_from == $warehouse->warehouse_id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="warehouse_to" class="form-label small fw-semibold">เลือก Warehouse To</label>
                                <select class="form-select form-select-sm form-select-md-normal" id="warehouse_to" name="warehouse_to" required>
                                    <option value="">เลือก Warehouse To</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->warehouse_id }}" {{ $transaction->warehouse_to == $warehouse->warehouse_id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="status" class="form-label small fw-semibold">สถานะ</label>
                                <select class="form-select form-select-sm form-select-md-normal" id="status" name="status" required>
                                    <option value="Pending" {{ $transaction->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Active" {{ $transaction->status == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Completed" {{ $transaction->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        </div>

                        <!-- Lady: 
                        <button type="submit" class="btn btn-success">อัพเดต</button>
                        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">ยกเลิก</a> 
                        -->
                        <!-- unnecessary -->

                        <!-- Gemini: Added flex container with full-width buttons on mobile and inline sizing on desktop -->
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mt-4 pt-2 border-top">
                            <a href="{{ route('transactions.index') }}" class="btn btn-secondary btn-sm btn-md-normal order-2 order-sm-1">ยกเลิก</a>
                            <button type="submit" class="btn btn-success btn-sm btn-md-normal order-1 order-sm-2 px-4">อัพเดต</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection