@extends('layouts.app')

@section('content')

{{-- Ensure FontAwesome is loaded for icons --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --color-primary: #E60012;
        --color-secondary: #004B8D;
        --color-info: #0086BF;
        --color-success: #198754;
        --color-warning: #ffc107;
        --color-danger: #dc3545;
        --color-background: #f4f6f9;
        --color-border: #e3e6f0;
        --border-radius-md: 0.75rem;
        --box-shadow-subtle: 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    body { 
        background-color: var(--color-background); 
        color: #212529; 
    }

    .card-kpi { 
        background-color: #ffffff; 
        border: 1px solid var(--color-border); 
        border-radius: var(--border-radius-md); 
        box-shadow: var(--box-shadow-subtle); 
        transition: transform 0.3s ease, box-shadow 0.3s ease; 
    }

    .card-kpi:hover { 
        transform: translateY(-5px); 
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.07); 
    }

    .kpi-value { 
        font-size: 2.75rem; 
        font-weight: 700; 
    }

    .kpi-title { 
        color: #6c757d; 
        font-weight: 600; 
        font-size: 1rem; 
    }

    .kpi-progress { 
        height: 6px; 
        border-radius: 50rem; 
        background-color: #e9ecef; 
    }

    .kpi-progress .progress-bar { 
        border-radius: 50rem; 
    }

    .card-main-content { 
        background-color: #ffffff; 
        border: 1px solid var(--color-border); 
        box-shadow: 0 4px 18px rgba(0,0,0,0.05); 
        border-radius: var(--border-radius-md); 
    }

    .modern-table { 
        border-collapse: separate; 
        border-spacing: 0 0.75rem; 
        font-size: 1rem; 
    }

    .modern-table thead th { 
        border: 0; 
        color: #6c757d; 
        font-weight: 700; 
        font-size: 0.85rem; 
        text-transform: uppercase; 
    }

    .modern-table tbody tr { 
        background-color: #fff; 
        border-radius: var(--border-radius-md); 
        box-shadow: var(--box-shadow-subtle); 
        border: 1px solid var(--color-border); 
        transition: all 0.2s ease-out; 
    }

    .modern-table tbody tr:hover { 
        transform: scale(1.01); 
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08); 
        z-index: 10; 
        position: relative; 
        cursor: pointer; 
        border-color: var(--color-primary); 
    }

    .modern-table tbody td { 
        padding: 1.25rem 1.5rem; 
        vertical-align: middle; 
        border: 0; 
    }

    .modern-table tbody td:first-child { 
        border-top-left-radius: var(--border-radius-md); 
        border-bottom-left-radius: var(--border-radius-md); 
    }

    .modern-table tbody td:last-child { 
        border-top-right-radius: var(--border-radius-md); 
        border-bottom-right-radius: var(--border-radius-md); 
    }

    .status-badge { 
        padding: 0.4em 1em; 
        font-size: 0.85rem; 
        font-weight: 700; 
        border-radius: 50rem; 
        color: #fff; 
    }

    .fab { 
        position: fixed; 
        bottom: 25px; 
        right: 25px; 
        width: 60px; 
        height: 60px; 
        border-radius: 50%; 
        background-color: var(--color-primary); 
        color: white; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 1.75rem; 
        box-shadow: 0 5px 15px rgba(230, 0, 18, 0.4); 
        text-decoration: none; 
        transition: all 0.3s ease; 
        z-index: 1050; 
    }

    .fab:hover { 
        transform: translateY(-3px) scale(1.05); 
        box-shadow: 0 8px 25px rgba(230, 0, 18, 0.5); 
        color: white; 
    }

    .rating-stars { 
        white-space: nowrap; 
        color: var(--color-warning); 
    }

    select.form-select {
        -webkit-appearance: none;
        background-image: none;
    }
</style>

<div class="container-fluid py-4 px-lg-4">

    <div class="text-center mb-5">
        <h1 class="display-5 fw-bolder" style="color: var(--color-primary);">
            <i class="fas fa-truck-moving"></i> Transportation Dashboard
        </h1>
        <p class="text-secondary fs-5 fw-medium">Real-time status of all booking transactions</p>
    </div>

    {{-- KPI Cards Section --}}
    <div class="row g-4">
        @php
            $kpi_data = [
                ['title' => 'Pending', 'count' => $pendingCount, 'color' => 'var(--color-warning)'], 
                ['title' => 'Assigned', 'count' => $assignedCount, 'color' => 'var(--color-primary)'],
                ['title' => 'In Progress', 'count' => $inProgressCount, 'color' => 'var(--color-secondary)'],
                ['title' => 'Arrived', 'count' => $arrivedCount, 'color' => 'var(--color-info)'],
                ['title' => 'Completed', 'count' => $completedCount, 'color' => 'var(--color-success)'],
                ['title' => 'Rejected', 'count' => $rejectedCount ?? 0, 'color' => 'var(--color-danger)']
            ];
        @endphp

        @foreach($kpi_data as $kpi)
        <div class="col-12 col-sm-6 col-xl-2">
            <div class="card card-kpi p-2">
                <div class="card-body text-center">
                    <p class="kpi-title mb-2 text-truncate">{{ $kpi['title'] }}</p>
                    <h2 class="kpi-value mb-3" style="color: {{ $kpi['color'] }};">{{ $kpi['count'] }}</h2>
                    <div class="progress kpi-progress">
                        <div class="progress-bar" role="progressbar"
                             style="width: {{ $totalTransactions > 0 ? ($kpi['count'] / $totalTransactions) * 100 : 0 }}%; background-color: {{ $kpi['color'] }};">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filter and Content Section --}}
    <div class="card mt-5 card-main-content border-0 rounded-3">
        <div class="card-body p-4 p-lg-5">
            
            {{-- Search Filter Form --}}
            <form method="GET" action="{{ route('transactions.index') }}" class="mb-4" id="filterForm">
                <div class="row g-2 align-items-end">
                    <div class="col-lg col-md-4">
                        <select class="form-select" name="forklift_id">
                            <option value="">All Forklifts</option>
                            @foreach($forklifts as $item)
                                <option value="{{$item->forklift_id}}" {{ request('forklift_id') == $item->forklift_id ? 'selected' : '' }}>
                                    {{$item->model}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg col-md-4">
                        <select class="form-select" name="driver_id">
                            <option value="">All Drivers</option>
                            @foreach($drivers as $item)
                                <option value="{{$item->driver_id}}" {{ request('driver_id') == $item->driver_id ? 'selected' : '' }}>
                                    {{$item->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg col-md-4">
                        <select class="form-select" name="warehouse_from">
                            <option value="">From</option>
                            @foreach($warehouses as $item)
                                <option value="{{$item->warehouse_id}}" {{ request('warehouse_from') == $item->warehouse_id ? 'selected' : '' }}>
                                    {{$item->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg col-md-4">
                        <select class="form-select" name="warehouse_to">
                            <option value="">To</option>
                            @foreach($warehouses as $item)
                                <option value="{{$item->warehouse_id}}" {{ request('warehouse_to') == $item->warehouse_id ? 'selected' : '' }}>
                                    {{$item->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg col-md-4">
                        <select class="form-select" name="status">
                            <option value="">All Statuses</option>
                            @foreach(['Pending','Assigned', 'In Progress', 'Arrived', 'Completed', 'Rejected'] as $status)
                                <option value="{{$status}}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{$status}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg col-md-4">
                        <select class="form-select" name="driver_rating">
                            <option value="">Any Rating</option>
                            @for ($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('driver_rating') == $i ? 'selected' : '' }}>
                                    {{ str_repeat('★', $i) . str_repeat('☆', 5 - $i) }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-auto d-grid">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>

            {{-- Export Section --}}
            <div class="text-end mb-4">
                @if(in_array(auth()->user()->role, ['admin', 'checker']))
                <form action="{{ route('transactions.export') }}" method="POST" class="d-inline-block">
                    @csrf
                    <input type="hidden" name="forklift_id" value="{{ request('forklift_id') }}">
                    <input type="hidden" name="driver_id" value="{{ request('driver_id') }}">
                    <input type="hidden" name="warehouse_from" value="{{ request('warehouse_from') }}">
                    <input type="hidden" name="warehouse_to" value="{{ request('warehouse_to') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="hidden" name="driver_rating" value="{{ request('driver_rating') }}">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i>Export to Excel
                    </button>
                </form>
                @endif
            </div>

            {{-- Data Table --}}
            <div class="table-responsive">
                <table class="table modern-table align-middle">
                    <thead class="text-center">
                        <tr>
                            <th class="text-start">Created By & Driver</th>
                            <th class="text-start">Route</th>
                            <th>Booked Date/Time</th> 
                            <th>Status</th>
                            <th>Feedback / Rejection Reason</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                {{-- Created By & Driver --}}
                                <td class="text-start">
                                    <a href="{{ route('transactions.show', $transaction->transaction_id) }}" class="text-decoration-none">
                                        <div class="fw-bold text-primary">{{ $transaction->creator->name ?? 'System' }}</div>
                                        <div class="small text-muted">{{ $transaction->driver->name ?? 'Unassigned' }}</div>
                                    </a>
                                </td>

                                {{-- Route --}}
                                <td class="text-start">
                                    <div class="d-flex align-items-center">
                                        <span class="fw-bold">{{ $transaction->warehouseFrom->name ?? 'N/A' }}</span>
                                        <span class="mx-3 text-muted fs-4">&#10132;</span>
                                        <span class="fw-bold">{{ $transaction->warehouseTo->name ?? 'N/A' }}</span>
                                    </div>
                                </td>

                                {{-- Date/Time --}}
                                <td class="text-center">
                                    {{ $transaction->created_at
                                        ? $transaction->created_at->timezone('Asia/Bangkok')->format('d M Y H:i')
                                        : '-' }}
                                </td>

                                {{-- Status Badge --}}
                                <td class="text-center">
                                    @php
                                        $statusColor = match($transaction->status) {
                                            'Assigned' => 'var(--color-primary)',
                                            'In Progress' => 'var(--color-secondary)',
                                            'Arrived' => 'var(--color-info)',
                                            'Completed' => 'var(--color-success)',
                                            'Rejected' => 'var(--color-danger)',
                                            default => '#6c757d',
                                        };
                                    @endphp
                                    <span class="status-badge" style="background-color: {{ $statusColor }};">
                                        {{ $transaction->status }}
                                    </span>
                                </td>

                                {{-- Feedback / Reason --}}
                                <td class="text-center">
                                    {{-- If task is Completed and has rating --}}
                                    @if($transaction->driver_rating)
                                        <div class="rating-stars" title="{{ $transaction->driver_rating }} stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $transaction->driver_rating ? '' : 'text-muted opacity-25' }}"></i>
                                            @endfor
                                        </div>
                                        @if($transaction->driver_comment)
                                            <p class="small text-muted fst-italic mt-1 mb-0" title="{{ $transaction->driver_comment }}">
                                                "{{ Str::limit($transaction->driver_comment, 20) }}"
                                            </p>
                                        @endif
                                    
                                    {{-- If task is Rejected --}}
                                    @elseif($transaction->status == 'Rejected')
                                        <div class="text-danger fw-bold small">
                                            <i class="fas fa-exclamation-circle me-1"></i>Rejected
                                        </div>
                                        @if($transaction->rejection_reason)
                                            <p class="small text-muted fst-italic mt-1 mb-0" title="{{ $transaction->rejection_reason }}">
                                                "{{ Str::limit($transaction->rejection_reason, 25) }}"
                                            </p>
                                        @endif
                                        <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                                            By: {{ $transaction->rejectedBy->name ?? 'Driver' }}
                                        </div>

                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="text-center p-5">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <p class="mb-0 fs-5 text-muted">No transactions found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            @if ($transactions->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $transactions->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>
    </div>

</div>

{{-- Floating Action Button for Authorized Roles --}}
@if(auth()->user()->role == 'admin' || auth()->user()->role == 'user' || auth()->user()->role == 'checker')
    <a href="{{ route('transactions.create') }}" class="fab" title="Create New Booking">
        <i class="fas fa-plus"></i>
    </a>
@endif

<script>
    /**
     * Resets all search filters and redirects to the index page.
     */
    function resetFilters() {
        const form = document.getElementById('filterForm');
        if (form) {
            form.reset();
        }
        window.location.href = "{{ route('transactions.index') }}";
    }
</script>

@endsection