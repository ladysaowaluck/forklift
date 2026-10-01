@extends('layouts.app')

@section('content')

    {{-- FontAwesome for icons --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --color-primary: #ffffff; 
            --color-secondary: #004B8D;
            --color-info: #7CBA4C;
            --color-success: #198754;
            --color-warning: #ffc107;
            --color-danger: #dc3545;
            --color-background: #f4f6f9;
            --color-new-item: #B90019;
            --color-booking: #FA7800;
            --color-progress:  #0070C0;
            --color-border: #e3e6f0;
            --border-radius-md: 0.75rem;
            --box-shadow-subtle: 0 2px 6px rgba(0, 0, 0, 0.04);
        }

        body {
            background-color: var(--color-background);
            color: #212529;
        }

        /* Responsive KPI Cards!!!!!!!! */
        .card-kpi {
            background-color: #ffffff;
            border: 1px solid var(--color-border);
            border-radius: var(--border-radius-md);
            box-shadow: var(--box-shadow-subtle);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-kpi:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.07);
        }

        .kpi-value {
            font-size: clamp(1.75rem, 4vw, 2.75rem);
            font-weight: 700;
            line-height: 1.2;
        }

        .kpi-title {
            color: #6c757d;
            font-weight: 600;
            font-size: 0.875rem;
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
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
            border-radius: var(--border-radius-md);
        }

        .modern-table {
            border-collapse: separate;
            border-spacing: 0 0.75rem;
            font-size: 0.95rem;
        }

        .modern-table thead th {
            border: 0;
            color: #6c757d;
            font-weight: 700;
            font-size: 0.8rem;
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
            transform: scale(1.005);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            z-index: 10;
            position: relative;
            cursor: pointer;
        }

        .modern-table tbody td {
            padding: 1rem 1.25rem;
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
            padding: 0.35em 0.85em;
            font-size: 0.8rem;
            font-weight: 700;
            border-radius: 50rem;
            color: #fff;
            display: inline-block;
        }

        .fab {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--color-new-item);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 4px 12px rgba(0, 75, 141, 0.4);
            text-decoration: none;
            transition: all 0.3s ease;
            z-index: 1050;
        }

        .fab:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 75, 141, 0.5);
            color: white;
        }

        @media (min-width: 768px) {
            .fab {
                bottom: 30px;
                right: 30px;
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }

        .rating-stars {
            white-space: nowrap;
            color: var(--color-warning);
        }

        select.form-select {
            font-size: 0.9rem;
        }

        /* Mobile Responsive Adjustments */
        @media (max-width: 767.98px) {
            .container-fluid {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }

            .card-main-content .card-body {
                padding: 1rem !important;
            }

            .modern-table thead {
                display: none; /* Hide header on mobile */
            }

            .modern-table, 
            .modern-table tbody, 
            .modern-table tr, 
            .modern-table td {
                display: block;
                width: 100%;
            }

            .modern-table tbody tr {
                margin-bottom: 1rem;
                padding: 0.75rem;
            }

            .modern-table tbody td {
                padding: 0.5rem 0.25rem;
                text-align: left !important;
            }

            .modern-table tbody td:first-child,
            .modern-table tbody td:last-child {
                border-radius: 0;
            }
        }
    </style>

    <div class="container-fluid py-3 py-md-4 px-2 px-lg-4">

        {{-- Page Header --}}
        <div class="text-center mb-4 mb-md-5">
            <h1 class="fs-3 fs-md-1 fw-bolder mb-1" style="color: var(--color-secondary);">
                <img src="{{ asset('images/forklift.png') }}" alt="Boxes Icon" class="me-2" style="width: 50px; height: auto;">
                Transportation Dashboard
            </h1>
            <p class="text-secondary fs-6 fw-medium mb-0">Real-time status of all booking transactions</p>
        </div>

        {{-- KPI Cards Section --}}
        <div class="row g-2 g-md-3">
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
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card card-kpi p-1 p-md-2 h-100">
                        <div class="card-body text-center p-2 p-md-3">
                            <p class="kpi-title mb-1 text-truncate">{{ $kpi['title'] }}</p>
                            <h2 class="kpi-value mb-2" style="color: {{ $kpi['color'] }};">{{ $kpi['count'] }}</h2>
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
        <div class="card mt-4 mt-md-5 card-main-content border-0 rounded-3">
            <div class="card-body p-3 p-lg-5">

                {{-- Search Filter Form --}}
                <form method="GET" action="{{ route('transactions.index') }}" class="mb-3 mb-md-4" id="filterForm">
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-sm-6 col-lg">
                            <select class="form-select form-select-sm form-select-md-normal" name="forklift_id">
                                <option value="">All Forklifts</option>
                                @foreach($forklifts as $item)
                                    <option value="{{$item->forklift_id}}" {{ request('forklift_id') == $item->forklift_id ? 'selected' : '' }}>
                                        {{$item->model}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-lg">
                            <select class="form-select form-select-sm form-select-md-normal" name="driver_id">
                                <option value="">All Drivers</option>
                                @foreach($drivers as $item)
                                    <option value="{{$item->driver_id}}" {{ request('driver_id') == $item->driver_id ? 'selected' : '' }}>
                                        {{$item->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-lg">
                            <select class="form-select form-select-sm form-select-md-normal" name="warehouse_from">
                                <option value="">From Warehouse</option>
                                @foreach($warehouses as $item)
                                    <option value="{{$item->warehouse_id}}" {{ request('warehouse_from') == $item->warehouse_id ? 'selected' : '' }}>
                                        {{$item->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-lg">
                            <select class="form-select form-select-sm form-select-md-normal" name="warehouse_to">
                                <option value="">To Warehouse</option>
                                @foreach($warehouses as $item)
                                    <option value="{{$item->warehouse_id}}" {{ request('warehouse_to') == $item->warehouse_id ? 'selected' : '' }}>
                                        {{$item->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-lg">
                            <select class="form-select form-select-sm form-select-md-normal" name="status">
                                <option value="">All Statuses</option>
                                @foreach(['Pending', 'Assigned', 'In Progress', 'Arrived', 'Completed', 'Rejected'] as $status)
                                    <option value="{{$status}}" {{ request('status') == $status ? 'selected' : '' }}>
                                        {{$status}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-lg">
                            <select class="form-select form-select-sm form-select-md-normal" name="driver_rating">
                                <option value="">Any Rating</option>
                                @for ($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ request('driver_rating') == $i ? 'selected' : '' }}>
                                        {{ str_repeat('★', $i) . str_repeat('☆', 5 - $i) }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-12 col-md-auto d-grid">
                            <button type="submit" class="btn btn-secondary btn-sm btn-md-normal">
                                <i class="fas fa-search me-1"></i> Search
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Export Section --}}
                <div class="text-end mb-3">
                    @if(in_array(auth()->user()->role, ['admin', 'checker']))
                        <form action="{{ route('transactions.export') }}" method="POST" class="d-inline-block w-100 w-md-auto">
                            @csrf
                            <input type="hidden" name="forklift_id" value="{{ request('forklift_id') }}">
                            <input type="hidden" name="driver_id" value="{{ request('driver_id') }}">
                            <input type="hidden" name="warehouse_from" value="{{ request('warehouse_from') }}">
                            <input type="hidden" name="warehouse_to" value="{{ request('warehouse_to') }}">
                            <input type="hidden" name="status" value="{{ request('status') }}">
                            <input type="hidden" name="driver_rating" value="{{ request('driver_rating') }}">
                            <button type="submit" class="btn btn-success btn-sm btn-md-normal w-md-auto">
                                <i class="fas fa-file-excel me-2"></i>Export to Excel
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Data Table / Card View -->
                <div class="transaction-container">
                    <table class="table modern-table align-middle d-none d-md-table">
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
                                @php
                                    $statusColor = match ($transaction->status) {
                                        'Assigned' => 'var(--color-booking)',
                                        'In Progress' => 'var(--color-progress)',
                                        'Arrived' => 'var(--color-info)',
                                        'Completed' => 'var(--color-success)',
                                        'Rejected' => 'var(--color-danger)',
                                        default => '#6c757d',
                                    };
                                @endphp

                                <!-- DESKTOP VIEW (Table Row) -->
                                <tr>
                                    <!-- Created By & Driver -->
                                    <td class="text-start">
                                        <a href="{{ route('transactions.show', $transaction->transaction_id) }}" class="text-decoration-none">
                                            <div class="fw-bold text-primary">{{ $transaction->creator->name ?? 'System' }}</div>
                                            <div class="small text-muted">{{ $transaction->driver->name ?? 'Unassigned' }}</div>
                                        </a>
                                    </td>

                                    <!-- Route -->
                                    <td class="text-start">
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold">{{ $transaction->warehouseFrom->name ?? 'N/A' }}</span>
                                            <i class="fas fa-arrow-right mx-2 text-muted small"></i>
                                            <span class="fw-bold">{{ $transaction->warehouseTo->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>

                                    <!-- Date/Time -->
                                    <td class="text-center">
                                        <span class="small text-muted">
                                            {{ $transaction->created_at 
                                                ? $transaction->created_at->timezone('Asia/Bangkok')->format('d M Y H:i') 
                                                : '-' }}
                                        </span>
                                    </td>

                                    <!-- Status Badge -->
                                    <td class="text-center">
                                        <span class="status-badge" style="background-color: {{ $statusColor }};">
                                            {{ $transaction->status }}
                                        </span>
                                    </td>

                                    <!-- Feedback / Reason  -->
                                    <td class="text-center">
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

                    <!-- MOBILE VIEW (Card Layout) Added mobile-only container with d-md-none to render structured cards on small screens -->
                    <div class="d-md-none">
                        @forelse($transactions as $transaction)
                            @php
                                $statusColor = match ($transaction->status) {
                                    'Assigned' => 'var(--color-booking)',
                                    'In Progress' => 'var(--color-progress)',
                                    'Arrived' => 'var(--color-info)',
                                    'Completed' => 'var(--color-success)',
                                    'Rejected' => 'var(--color-danger)',
                                    default => '#6c757d',
                                };
                            @endphp

                            <div class="card mb-3 border shadow-sm rounded-3">
                                <div class="card-body p-3">
                                    <!-- Card Header: Creator/Driver & Status -->
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <a href="{{ route('transactions.show', $transaction->transaction_id) }}" class="text-decoration-none">
                                            <div class="fw-bold text-primary fs-6">{{ $transaction->creator->name ?? 'System' }}</div>
                                            <div class="small text-muted">Driver: {{ $transaction->driver->name ?? 'Unassigned' }}</div>
                                        </a>
                                        <span class="status-badge small" style="background-color: {{ $statusColor }}; padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                            {{ $transaction->status }}
                                        </span>
                                    </div>

                                    <hr class="my-2 opacity-25">

                                    <!-- Route Details -->
                                    <div class="d-flex align-items-center mb-2 small fw-bold text-dark">
                                        <span>{{ $transaction->warehouseFrom->name ?? 'N/A' }}</span>
                                        <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                        <span>{{ $transaction->warehouseTo->name ?? 'N/A' }}</span>
                                    </div>

                                    {{-- Date & Time --}}
                                    <div class="small text-muted mb-2">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $transaction->created_at 
                                            ? $transaction->created_at->timezone('Asia/Bangkok')->format('d M Y H:i') 
                                            : '-' }}
                                    </div>

                                    {{-- Feedback / Rejection Info --}}
                                    @if($transaction->driver_rating || $transaction->status == 'Rejected')
                                        <div class="bg-light p-2 rounded mt-2">
                                            @if($transaction->driver_rating)
                                                <div class="rating-stars small d-flex align-items-center">
                                                    <span class="me-2 text-muted">Rating:</span>
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $transaction->driver_rating ? '' : 'text-muted opacity-25' }}"></i>
                                                    @endfor
                                                </div>
                                                @if($transaction->driver_comment)
                                                    <p class="small text-muted fst-italic mb-0 mt-1">
                                                        "{{ Str::limit($transaction->driver_comment, 40) }}"
                                                    </p>
                                                @endif
                                            @elseif($transaction->status == 'Rejected')
                                                <div class="text-danger fw-bold small">
                                                    <i class="fas fa-exclamation-circle me-1"></i>Rejected
                                                </div>
                                                @if($transaction->rejection_reason)
                                                    <p class="small text-muted fst-italic mb-0 mt-1">
                                                        "{{ Str::limit($transaction->rejection_reason, 40) }}"
                                                    </p>
                                                @endif
                                                <div class="small text-muted mt-1" style="font-size: 0.7rem;">
                                                    By: {{ $transaction->rejectedBy->name ?? 'Driver' }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center p-4 border rounded bg-light">
                                <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                                <p class="mb-0 small text-muted">No transactions found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>


                {{-- Pagination Links --}}
                @if ($transactions->hasPages())
                    <div class="d-flex justify-content-center mt-3 mt-md-4">
                        {{ $transactions->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                @endif

            </div>
        </div>

    </div>

    {{-- Floating Action Button --}}
    @if(in_array(auth()->user()->role, ['admin', 'user', 'checker']))
        <a href="{{ route('transactions.create') }}" class="fab" title="Create New Booking" > 
            <i class="fas fa-plus"></i>
        </a>
    @endif

    <script>
        function resetFilters() {
            const form = document.getElementById('filterForm');
            if (form) {
                form.reset();
            }
            window.location.href = "{{ route('transactions.index') }}";
        }
    </script>

@endsection