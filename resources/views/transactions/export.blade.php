@extends('layouts.app')

@section('content')
<div class="container py-3 px-2 px-md-4">
    {{-- Lady: <h2 class="my-4 text-center text-gradient fw-bold" style="font-size: 2.5rem; letter-spacing: 2px;"> --}}
    {{-- Gemini: Replaced hardcoded font-size with responsive typography classes --}}
    <h2 class="my-3 my-md-4 text-center text-gradient fw-bold fs-3 fs-md-2" style="letter-spacing: 1px;">
        <i class="fas fa-truck"></i> Export Transportation Transactions
    </h2>

    <!-- Export Button -->
    <div class="d-flex justify-content-start mb-3">
        {{-- Lady: <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#exportModal"> --}}
        {{-- Gemini: Added w-100 on small screens and w-auto on medium screens --}}
        <button class="btn btn-success w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#exportModal">
            <i class="fas fa-file-excel me-1"></i> Export to Excel
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        {{-- Lady: <div class="modal-dialog"> --}}
        {{-- Gemini: Center modal and make it wider on desktop for side-by-side fields --}}
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-6 fs-md-5 fw-bold" id="exportModalLabel">Select Data to Export</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 p-md-4">
                    <form method="POST" action="{{ route('transactions.export') }}">
                        @csrf
                        {{-- Gemini: Added Bootstrap grid system to arrange form controls responsively --}}
                        <div class="row row-cols-1 row-cols-md-2 g-3">
                            <div class="col">
                                <label for="status" class="form-label small fw-semibold">Select Status</label>
                                <select name="status" id="status" class="form-select form-select-sm form-select-md-normal">
                                    <option value="">Select Status</option>
                                    <option value="Assign">Assigned</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Arrived">Arrived</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="forklift_id" class="form-label small fw-semibold">Select Forklift</label>
                                <select name="forklift_id" id="forklift_id" class="form-select form-select-sm form-select-md-normal">
                                    <option value="">Select Forklift</option>
                                    @foreach($forklifts as $forklift)
                                        <option value="{{ $forklift->forklift_id }}">{{ $forklift->model }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="driver_id" class="form-label small fw-semibold">Select Driver</label>
                                <select name="driver_id" id="driver_id" class="form-select form-select-sm form-select-md-normal">
                                    <option value="">Select Driver</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->driver_id }}">{{ $driver->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="warehouse_from" class="form-label small fw-semibold">Select Warehouse (From)</label>
                                <select name="warehouse_from" id="warehouse_from" class="form-select form-select-sm form-select-md-normal">
                                    <option value="">Select Warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="warehouse_to" class="form-label small fw-semibold">Select Warehouse (To)</label>
                                <select name="warehouse_to" id="warehouse_to" class="form-select form-select-sm form-select-md-normal">
                                    <option value="">Select Warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer px-0 pb-0 pt-3 mt-3 border-top">
                            <button type="button" class="btn btn-secondary btn-sm btn-md-normal" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btn-sm btn-md-normal px-4">Export</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection