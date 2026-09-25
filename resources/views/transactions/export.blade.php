@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="my-4 text-center text-gradient fw-bold" style="font-size: 2.5rem; letter-spacing: 2px;">
        <i class="fas fa-truck"></i> Export Transportation Transactions
    </h2>

    <!-- Export Button -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#exportModal">
        Export to Excel
    </button>

    <!-- Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Select Data to Export</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('transactions.export') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="status" class="form-label">Select Status</label>
                            <select name="status" class="form-select">
                                <option value="">Select Status</option>
                                <option value="Assign">Assigned</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Arrived">Arrived</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="forklift_id" class="form-label">Select Forklift</label>
                            <select name="forklift_id" class="form-select">
                                <option value="">Select Forklift</option>
                                @foreach($forklifts as $forklift)
                                    <option value="{{ $forklift->forklift_id }}">{{ $forklift->model }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="driver_id" class="form-label">Select Driver</label>
                            <select name="driver_id" class="form-select">
                                <option value="">Select Driver</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->driver_id }}">{{ $driver->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="warehouse_from" class="form-label">Select Warehouse (From)</label>
                            <select name="warehouse_from" class="form-select">
                                <option value="">Select Warehouse</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="warehouse_to" class="form-label">Select Warehouse (To)</label>
                            <select name="warehouse_to" class="form-select">
                                <option value="">Select Warehouse</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Export</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
