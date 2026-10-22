@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4 fw-bold">
        <i class="fas fa-file-alt me-2 text-primary"></i> Transaction Search Report
    </h2>

    {{-- Search Filter Form --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-semibold bg-light">
            <i class="fas fa-filter me-1"></i> Search Filters
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('transactions.report') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Status</option>
                            @foreach(['Pending','Assign','In Progress','Arrived','Completed','Rejected'] as $s)
                                <option value="{{ $s }}" @selected(($filters['status'] ?? '') === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Forklift</label>
                        <select name="forklift_id" class="form-select form-select-sm">
                            <option value="">All Forklifts</option>
                            @foreach($forklifts as $f)
                                <option value="{{ $f->forklift_id }}" @selected(($filters['forklift_id'] ?? '') == $f->forklift_id)>{{ $f->model }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Driver</label>
                        <select name="driver_id" class="form-select form-select-sm">
                            <option value="">All Drivers</option>
                            @foreach($drivers as $d)
                                <option value="{{ $d->driver_id }}" @selected(($filters['driver_id'] ?? '') == $d->driver_id)>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Warehouse (From)</label>
                        <select name="warehouse_from" class="form-select form-select-sm">
                            <option value="">All Warehouses</option>
                            @foreach($warehouses as $w)
                                <option value="{{ $w->warehouse_id }}" @selected(($filters['warehouse_from'] ?? '') == $w->warehouse_id)>{{ $w->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Warehouse (To)</label>
                        <select name="warehouse_to" class="form-select form-select-sm">
                            <option value="">All Warehouses</option>
                            @foreach($warehouses as $w)
                                <option value="{{ $w->warehouse_id }}" @selected(($filters['warehouse_to'] ?? '') == $w->warehouse_id)>{{ $w->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">From Date</label>
                        <input type="date" name="date_from" class="form-control form-control-sm"
                            value="{{ $filters['date_from'] ?? '' }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">To Date</label>
                        <input type="date" name="date_to" class="form-control form-control-sm"
                            value="{{ $filters['date_to'] ?? '' }}">
                    </div>
                    
                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm px-4">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                        <a href="{{ route('transactions.report') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-redo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Results --}}
    @if($transactions->isNotEmpty())
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                <span class="fw-semibold">
                    <i class="fas fa-table me-1"></i>
                    Results: {{ $transactions->count() }} transaction(s) found
                </span>

                {{-- Export button: only visible when results exist --}}
                <form method="POST" action="{{ route('transactions.export') }}">
                    @csrf
                    @foreach($filters as $key => $value)
                        @if($value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel me-1"></i> Export to Excel
                    </button>
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm mb-0 small">
                        <thead class="table-dark">
                            <tr>
                                <th>#ID</th>
                                <th>Status</th>
                                <th>Forklift</th>
                                <th>Driver</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Items</th>
                                <th>Created</th>
                                <th>Completed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $t)
                                <tr>
                                    <td>
                                        <a href="{{ route('transactions.show', $t->transaction_id) }}" class="text-decoration-none fw-semibold">
                                            #{{ $t->transaction_id }}
                                        </a>
                                    </td>
                                    <td>
                                        @php
                                            $badge = match($t->status) {
                                                'Completed'   => 'success',
                                                'In Progress' => 'primary',
                                                'Arrived'     => 'info',
                                                'Assigned', 'Assign' => 'warning',
                                                'Rejected'    => 'danger',
                                                default       => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badge }}">{{ $t->status }}</span>
                                    </td>
                                    <td>{{ optional($t->forklift)->model ?? '-' }}</td>
                                    <td>{{ optional($t->driver)->name ?? '-' }}</td>
                                    <td>{{ optional($t->warehouseFrom)->name ?? '-' }}</td>
                                    <td>{{ optional($t->warehouseTo)->name ?? '-' }}</td>
                                    <td>{{ $t->details->count() }} item(s)</td>
                                    <td>{{ optional($t->created_at)->format('d/m/Y') }}</td>
                                    <td>{{ optional($t->completed_at)->format('d/m/Y') ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @elseif(request()->hasAny(['status','forklift_id','driver_id','warehouse_from','warehouse_to','date_from','date_to']))
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-1"></i> No transactions found matching your filters.
        </div>

    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-1"></i> Use the filters above and click <strong>Search</strong> to preview results before exporting.
        </div>
    @endif

</div>
@endsection