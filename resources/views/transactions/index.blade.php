@extends('layouts.app')
@section('content')
  <style>
    :root {
      --color-primary: #ffffff;
      --color-secondary: #004B8D;
      --color-arrived: #7CBA4C;
      --color-success: #198754;
      --color-warning: #f59e0b;
      --color-danger: #dc2626;
      --color-background: #f8fafc;
      --color-new-item: #B90019;
      --color-booking: #ea580c;
      --color-progress: #0284c7;
      --color-border: #e2e8f0;
      --border-radius-md: 0.875rem;
      --box-shadow-subtle: 0 1px 3px 0 rgba(15, 23, 42, 0.05), 0 1px 2px -1px rgba(15, 23, 42, 0.05);
      --box-shadow-hover: 0 10px 15px -3px rgba(15, 23, 42, 0.07), 0 4px 6px -4px rgba(15, 23, 42, 0.03);
    }

    body {
      background-color: var(--color-background);
      color: #1e293b;
    }

    .card-kpi {
      background-color: #fff;
      border: 1px solid var(--color-border);
      border-radius: var(--border-radius-md);
      box-shadow: var(--box-shadow-subtle);
      transition: all .25s cubic-bezier(.16, 1, .3, 1);
    }

    .card-kpi:hover {
      transform: translateY(-3px);
      box-shadow: var(--box-shadow-hover);
      border-color: #cbd5e1;
    }

    .kpi-value {
      font-size: clamp(1.75rem, 3.5vw, 2.5rem);
      font-weight: 800;
      line-height: 1.15;
      letter-spacing: -.03em;
    }

    .kpi-title {
      color: #64748b;
      font-weight: 600;
      font-size: .8rem;
      text-transform: uppercase;
      letter-spacing: .04em;
    }

    .kpi-progress {
      height: 5px;
      border-radius: 9999px;
      background-color: #f1f5f9;
    }

    .kpi-progress .progress-bar {
      border-radius: 9999px;
    }

    @media(min-width:992px) {
      .kpi-floating-web {
        position: sticky;
        top: var(--topbar-height, 60px);
        z-index: 1015;
        background-color: var(--color-background);
        padding-top: .5rem;
        padding-bottom: .5rem;
      }
    }

    .card-main-content {
      background-color: #fff;
      border: 1px solid var(--color-border);
      box-shadow: var(--box-shadow-subtle);
      border-radius: var(--border-radius-md);
    }

    .modern-table {
      width: 100%;
      border-collapse: collapse;
      font-size: .875rem;
      letter-spacing: -0.01em;
    }

    .modern-table thead th {
      position: sticky;
      top: 0;
      z-index: 4;
      border: 0;
      border-bottom: 1px solid var(--color-border);
      box-shadow: inset 0 -1px 0 var(--color-border);
      color: #64748b;
      font-weight: 700;
      font-size: .725rem;
      text-transform: uppercase;
      letter-spacing: .04em;
      padding: .55rem .75rem;
      background-color: #f8fafc;
      white-space: nowrap;
    }

    .modern-table tbody tr.transaction-row {
      background-color: #fff;
      border-bottom: 1px solid #f1f5f9;
      transition: background-color .15s ease-in-out;
      cursor: pointer;
    }

    .modern-table tbody tr.transaction-row:hover {
      background-color: #f8fafc;
    }

    .modern-table tbody tr.transaction-row[aria-expanded="true"] {
      background-color: #f0f7ff;
    }

    .modern-table tbody tr.transaction-row[aria-expanded="true"] .collapse-toggle-icon {
      transform: rotate(180deg);
    }

    .modern-table tbody td {
      padding: .55rem .75rem;
      vertical-align: middle;
      border-bottom: 1px solid #f1f5f9;
      font-size: .875rem;
      color: #1e293b;
    }

    .modern-table th.col-num,
    .modern-table td.col-num,
    .modern-table .text-end {
      text-align: right !important;
      font-variant-numeric: tabular-nums;
    }

    .modern-table th.col-check,
    .modern-table td.col-check {
      width: 38px;
      text-align: center;
      padding-left: .65rem;
      padding-right: .35rem;
    }

    .modern-table .form-check-input {
      cursor: pointer;
      width: 1.05rem;
      height: 1.05rem;
      border-color: #cbd5e1;
    }

    .modern-table .form-check-input:checked {
      background-color: #0054a6;
      border-color: #0054a6;
    }

    .collapse-toggle-icon {
      transition: transform .2s ease-in-out;
      display: inline-block;
    }

    .collapse-detail-cell {
      border-bottom: 2px solid #cbd5e1 !important;
      background-color: #f8fafc;
    }

    .status-badge {
      padding: .28em .75em;
      font-size: .75rem;
      font-weight: 600;
      border-radius: 50rem;
      color: #fff;
      display: inline-block;
    }

    .form-select,
    .form-control {
      border: 1px solid #cbd5e1;
      border-radius: .5rem;
      font-size: .875rem;
      padding: .45rem .75rem;
      transition: border-color .15s, box-shadow .15s;
    }

    .form-select:focus,
    .form-control:focus {
      border-color: #0070C0;
      box-shadow: 0 0 0 3px rgba(0, 112, 192, .15);
    }

    .fab {
      position: fixed;
      bottom: 24px;
      right: 24px;
      width: 54px;
      height: 54px;
      border-radius: 50%;
      background-color: #0054a6;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      box-shadow: 0 4px 14px rgba(0, 84, 166, .35);
      text-decoration: none;
      border: none;
      transition: all .2s cubic-bezier(.16, 1, .3, 1);
      z-index: 1050;
    }

    .fab:hover {
      background-color: #003d7a;
      transform: translateY(-2px) scale(1.03);
      box-shadow: 0 8px 20px rgba(0, 84, 166, .45);
      color: #fff;
    }

    .fab:active {
      background-color: #003366;
    }

    @media(min-width:768px) {
      .fab {
        bottom: 32px;
        right: 32px;
        width: 58px;
        height: 58px;
        font-size: 1.5rem;
      }
    }

    .rating-stars {
      white-space: nowrap;
      color: #f59e0b;
    }

    .driver-task-card {
      background: #fff;
      border: 1px solid var(--color-border);
      border-radius: var(--border-radius-md);
      box-shadow: var(--box-shadow-subtle);
      transition: all .2s cubic-bezier(.16, 1, .3, 1);
    }

    .driver-task-card:hover {
      transform: translateY(-2px);
      box-shadow: var(--box-shadow-hover);
    }

    .driver-task-card .task-status-bar {
      height: 4px;
      border-radius: var(--border-radius-md) var(--border-radius-md) 0 0;
    }

    @media(max-width:767.98px) {
      body {
        font-size: 15px !important;
      }
      .container-fluid {
        padding-left: .75rem !important;
        padding-right: .75rem !important;
      }
      .card-main-content .card-body {
        padding: 1rem !important;
      }
      .status-badge {
        font-size: 0.8rem !important;
      }
      .modern-table td,
      .modern-table th {
        font-size: 14px !important;
      }
    }
  </style>
  <div class="container-fluid py-3 py-md-4 px-2 px-lg-4">

    @if(auth()->user()->role == 'driver')
      @php
        $driverProfile = \App\Models\Driver::where('user_id', auth()->id())->first();
        $driverName = $driverProfile->name ?? auth()->user()->name;
      @endphp

      <div class="d-flex align-items-center gap-3 mb-4">
        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
          style="width:52px;height:52px;font-size:1.4rem;background:linear-gradient(135deg,#004B8D,#0070C0);flex-shrink:0;">
          {{ strtoupper(substr($driverName, 0, 1)) }}
        </div>
        <div>
          <h1 class="fs-4 fw-bolder mb-0" style="color:var(--color-secondary);">{{ $driverName }}</h1>
          <p class="text-muted small mb-0"><i class="ti ti-calendar me-1"></i>Today:
            {{ now()->timezone('Asia/Bangkok')->format('d M Y') }}</p>
        </div>
      </div>

      <div class="mb-3 d-flex align-items-center gap-2">
        <span class="text-muted small fw-semibold">View:</span>
        <div class="btn-group btn-group-sm" role="group">
          <input type="radio" class="btn-check" name="driverView" id="viewMine" autocomplete="off" checked>
          <label class="btn btn-outline-primary" for="viewMine"><i class="ti ti-user me-1"></i>My Tasks</label>
          <input type="radio" class="btn-check" name="driverView" id="viewAll" autocomplete="off">
          <label class="btn btn-outline-primary" for="viewAll"><i class="ti ti-list me-1"></i>Today's Jobs</label>
        </div>
      </div>

      <div id="driverCards">
        @forelse($transactions as $transaction)
          @php
            $statusColor = match ($transaction->status) {
              'Assigned' => 'var(--color-booking)',
              'In Progress' => 'var(--color-progress)',
              'Arrived' => 'var(--color-arrived)',
              'Completed' => 'var(--color-success)',
              'Rejected' => 'var(--color-danger)',
              default => '#6c757d',
            };
            $isMine = $driverProfile && $transaction->driver_id == $driverProfile->driver_id;
          @endphp
          <div class="driver-task-card mb-3" data-owner="{{ $isMine ? 'mine' : 'other' }}">
            <div class="task-status-bar" style="background-color:{{ $statusColor }};"></div>
            <div class="p-3">
              <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                <a href="{{ route('transactions.show', $transaction->transaction_id) }}" class="text-decoration-none">
                  <div class="fw-bold text-primary">
                    #{{ $transaction->transaction_id }} &mdash; {{ $transaction->warehouseFrom->name ?? 'N/A' }}
                    <i class="ti ti-arrow-right mx-1 text-muted small"></i>{{ $transaction->warehouseTo->name ?? 'N/A' }}
                  </div>
                  <div class="small text-muted mt-1">
                    <i
                      class="ti ti-clock me-1"></i>{{ $transaction->created_at?->setTimezone('Asia/Bangkok')->format('H:i') }}
                    &bull; Created by: {{ $transaction->creator->name ?? 'System' }}
                  </div>
                </a>
                <span class="status-badge" style="background-color:{{ $statusColor }};">{{ $transaction->status }}</span>
              </div>
              <div class="d-flex align-items-center gap-2 small">
                @if($isMine)
                  <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><i
                      class="ti ti-user-check me-1"></i>My Task</span>
                @else
                  <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle"><i
                      class="ti ti-inbox me-1"></i>Available to Claim</span>
                @endif
                @if($transaction->forklift)
                  <span class="text-muted"><i class="ti ti-truck me-1"></i>{{ $transaction->forklift->model }}
                    ({{ $transaction->forklift->type ?? 'Standard' }})</span>
                @endif
              </div>
              @if($transaction->driver_rating)
                <div class="mt-2 small text-muted">Rating:
                  @for($i = 1; $i <= 5; $i++)<i
                  class="ti {{ $i <= $transaction->driver_rating ? 'ti-star-filled text-warning' : 'ti-star text-muted opacity-25' }}"></i>@endfor
                </div>
              @endif
            </div>
          </div>
        @empty
          <div class="text-center p-5 border rounded-3 bg-white">
            <i class="ti ti-clipboard-check text-muted mb-3" style="font-size: 2.5rem;"></i>
            <p class="mb-0 text-muted">No tasks scheduled for today.</p>
          </div>
        @endforelse
      </div>

      @if($transactions->hasPages())
        <div class="d-flex justify-content-center mt-3">
          {{ $transactions->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
      @endif

    @else

      <div class="text-center mb-4 mb-md-5">
        <h1 class="fs-3 fw-bolder mb-1" style="color:var(--color-secondary);">
          <img src="{{ asset('images/tracking.png') }}" alt="Boxes Icon" class="me-2"
            style="width:38px;height:auto;">Transportation Dashboard
        </h1>
        <p class="text-secondary fs-6 fw-medium mb-0">Real-time status of all booking transactions</p>
      </div>

      <div class="kpi-floating-web">
        <div class="row g-2 g-md-3">
          @php
            $kpi_data = [
              ['title' => 'Pending', 'count' => $pendingCount, 'color' => 'var(--color-warning)'],
              ['title' => 'Assigned', 'count' => $assignedCount, 'color' => 'var(--color-booking)'],
              ['title' => 'In Progress', 'count' => $inProgressCount, 'color' => 'var(--color-secondary)'],
              ['title' => 'Arrived', 'count' => $arrivedCount, 'color' => 'var(--color-arrived)'],
              ['title' => 'Completed', 'count' => $completedCount, 'color' => 'var(--color-success)'],
              ['title' => 'Rejected', 'count' => $rejectedCount ?? 0, 'color' => 'var(--color-danger)'],
            ];
          @endphp
          @foreach($kpi_data as $kpi)
            <div class="col-6 col-md-4 col-lg-2">
              <div class="card card-kpi p-1 p-md-2 h-100">
                <div class="card-body text-center p-2 p-md-3">
                  <p class="kpi-title mb-1 text-truncate">{{ $kpi['title'] }}</p>
                  <h2 class="kpi-value mb-2" style="color:{{ $kpi['color'] }};">{{ $kpi['count'] }}</h2>
                  <div class="progress kpi-progress">
                    <div class="progress-bar"
                      style="width:{{ $totalTransactions > 0 ? ($kpi['count'] / $totalTransactions) * 100 : 0 }}%;background-color:{{ $kpi['color'] }};">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <div class="card mt-4 mt-md-5 card-main-content border-0 rounded-3">
        <div class="card-body p-3 p-lg-5">
          <form method="GET" action="{{ route('transactions.index') }}" class="mb-3 mb-md-4" id="filterForm">
            <div class="row g-2 align-items-end">
              <div class="col-12 col-sm-6 col-lg">
                <select class="form-select form-select-sm" name="status">
                  <option value="">All Statuses</option>
                  @foreach(['Pending', 'Assigned', 'In Progress', 'Arrived', 'Completed', 'Rejected'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 col-sm-6 col-lg">
                <select class="form-select form-select-sm" name="forklift_id">
                  <option value="">All Forklifts</option>
                  <!-- Display forklift type in filter options -->
                  @foreach($forklifts as $item)
                    <option value="{{ $item->forklift_id }}" {{ request('forklift_id') == $item->forklift_id ? 'selected' : '' }}>
                      {{ $item->model }} ({{ $item->type ?? 'Standard' }})</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 col-sm-6 col-lg">
                <select class="form-select form-select-sm" name="driver_id">
                  <option value="">All Drivers</option>
                  @foreach($drivers as $item)
                    <option value="{{ $item->driver_id }}" {{ request('driver_id') == $item->driver_id ? 'selected' : '' }}>
                      {{ $item->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 col-sm-6 col-lg">
                <select class="form-select form-select-sm" name="warehouse_from">
                  <option value="">From Warehouse</option>
                  @foreach($warehouses as $item)
                    <option value="{{ $item->warehouse_id }}" {{ request('warehouse_from') == $item->warehouse_id ? 'selected' : '' }}>{{ $item->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 col-sm-6 col-lg">
                <select class="form-select form-select-sm" name="warehouse_to">
                  <option value="">To Warehouse</option>
                  @foreach($warehouses as $item)
                    <option value="{{ $item->warehouse_id }}" {{ request('warehouse_to') == $item->warehouse_id ? 'selected' : '' }}>{{ $item->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 col-sm-6 col-lg">
                <select class="form-select form-select-sm" name="driver_rating">
                  <option value="">Any Rating</option>
                  @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ request('driver_rating') == $i ? 'selected' : '' }}>
                      {{ str_repeat('*', $i) . str_repeat('o', 5 - $i) }}</option>
                  @endfor
                </select>
              </div>
              <div class="col-12 col-sm-6 col-lg">
                <div class="input-group input-group-sm">
                  <span class="input-group-text text-muted small">From</span>
                  <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
              </div>
              <div class="col-12 col-sm-6 col-lg">
                <div class="input-group input-group-sm">
                  <span class="input-group-text text-muted small">To</span>
                  <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
              </div>
              <div class="col-12 col-md-auto d-flex gap-1">
                <button type="submit" class="btn btn-secondary btn-sm"><i class="ti ti-search me-1"></i>Search</button>
                @if(request()->hasAny(['status', 'forklift_id', 'driver_id', 'warehouse_from', 'warehouse_to', 'driver_rating', 'date_from', 'date_to']))
                  <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset"><i
                      class="ti ti-rotate-2"></i></a>
                @endif
              </div>
            </div>
          </form>

          @php
            $isAdminOrChecker = in_array(strtolower(auth()->user()->role ?? ''), ['admin', 'checker']);
            $colCount = $isAdminOrChecker ? 7 : 6;
          @endphp

          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            @if($isAdminOrChecker)
              <form id="exportSelectedForm" method="POST" action="{{ route('transactions.export') }}" class="d-inline-flex align-items-center gap-2">
                @csrf
                <div id="selectedTxInputs"></div>
                <button type="submit" id="btnExportSelected" class="btn btn-success btn-sm fw-semibold" disabled title="Select transactions below to export">
                  <i class="ti ti-file-spreadsheet me-1"></i>Export Selected to Excel (<span id="selectedCount">0</span>)
                </button>
              </form>
            @else
              <div></div>
            @endif

            <div class="d-flex align-items-center gap-2">
              @if(in_array(auth()->user()->role, ['admin', 'user', 'checker']))
                <button type="button" class="btn btn-action-primary btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#createBookingModal">
                  <i class="ti ti-plus me-1"></i>Create New Booking
                </button>
              @endif
              @if(in_array(auth()->user()->role, ['admin', 'checker']))
                <a href="{{ route('transactions.report') }}" class="btn btn-outline-secondary btn-sm">
                  <i class="ti ti-filter me-1"></i>Search Report / Export Filter
                </a>
              @endif
            </div>
          </div>

          <div class="table-responsive">
          <table class="table mb-0 modern-table align-middle">
            <thead class="text-center">
              <tr>
                @if($isAdminOrChecker)
                  <th class="col-check text-center" style="width: 38px;">
                    <input class="form-check-input m-0 align-middle" type="checkbox" id="selectAllTransactions" title="Select All">
                  </th>
                @endif
                <th class="text-start">Created By &amp; Driver</th>
                <th class="text-start">Route</th>
                <th class="text-end col-num">Booked Date/Time</th>
                <th>Status</th>
                <th>Feedback / Rejection Reason</th>
                <th style="width: 36px;"></th>
              </tr>
            </thead>
            <tbody>
              @forelse($transactions as $transaction)
                @php
                  $statusColor = match ($transaction->status) { 'Assigned' => 'var(--color-booking)', 'In Progress' => 'var(--color-progress)', 'Arrived' => 'var(--color-arrived)', 'Completed' => 'var(--color-success)', 'Rejected' => 'var(--color-danger)', default => '#6c757d'};
                @endphp
                <tr class="transaction-row" data-bs-toggle="collapse" data-bs-target="#txDetail{{ $transaction->transaction_id }}" aria-expanded="false" title="Click to view details">
                  @if($isAdminOrChecker)
                    <td class="col-check text-center" onclick="event.stopPropagation();">
                      <input class="form-check-input m-0 align-middle tx-row-check" type="checkbox" value="{{ $transaction->transaction_id }}">
                    </td>
                  @endif
                  <td class="text-start">
                    <div class="fw-bold text-primary">{{ $transaction->creator->name ?? 'System' }}</div>
                    <div class="small text-muted">{{ $transaction->driver->name ?? 'Unassigned' }}</div>
                  </td>
                  <td class="text-start">
                    <div class="d-flex align-items-center">
                      <span class="fw-bold">{{ $transaction->warehouseFrom->name ?? 'N/A' }}</span>
                      <i class="ti ti-arrow-right mx-2 text-muted small"></i>
                      <span class="fw-bold">{{ $transaction->warehouseTo->name ?? 'N/A' }}</span>
                    </div>
                  </td>
                  <td class="text-end col-num"><span
                      class="small text-muted">{{ $transaction->created_at?->setTimezone('Asia/Bangkok')->format('d M Y H:i') ?? '-' }}</span>
                  </td>
                  <td class="text-center"><span class="status-badge"
                      style="background-color:{{ $statusColor }};">{{ $transaction->status }}</span></td>
                  <td class="text-center">
                    @if($transaction->driver_rating)
                      <div class="rating-stars">@for($i = 1; $i <= 5; $i++)<i
                      class="ti {{ $i <= $transaction->driver_rating ? 'ti-star-filled text-warning' : 'ti-star text-muted opacity-25' }}"></i>@endfor</div>
                      @if($transaction->driver_comment)
                        <p class="small text-muted fst-italic mt-1 mb-0">"{{ Str::limit($transaction->driver_comment, 20) }}"</p>
                      @endif
                    @elseif($transaction->status == 'Rejected')
                      <div class="text-danger fw-bold small"><i class="ti ti-alert-circle me-1"></i>Rejected</div>
                      @if($transaction->rejection_reason)
                        <p class="small text-muted fst-italic mt-1 mb-0">"{{ Str::limit($transaction->rejection_reason, 25) }}"</p>
                      @endif
                      <div class="small text-muted mt-1" style="font-size:.75rem;">By:
                        {{ $transaction->rejectedBy->name ?? 'Driver' }}</div>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td class="text-center text-muted">
                    <i class="ti ti-chevron-down collapse-toggle-icon"></i>
                  </td>
                </tr>
                <tr class="detail-row">
                  <td colspan="{{ $colCount }}" class="p-0 border-0 collapse-detail-cell">
                    <div class="collapse" id="txDetail{{ $transaction->transaction_id }}">
                      <div class="p-3 p-md-4 bg-light border-bottom">
                        <div class="card border-0 shadow-sm rounded-3">
                          <div class="card-header bg-white py-2 px-3 d-flex justify-content-between align-items-center border-bottom">
                            <div class="d-flex align-items-center gap-2">
                              <span class="fw-bold" style="color: var(--color-secondary);">
                                <i class="ti ti-receipt me-1"></i>Booking #{{ $transaction->transaction_id }} Details
                              </span>
                              <span class="status-badge" style="background-color: {{ $statusColor }}; font-size: 0.72rem; padding: 0.2rem 0.6rem;">
                                {{ $transaction->status }}
                              </span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                              <a href="{{ route('transactions.show', $transaction->transaction_id) }}" class="btn btn-sm btn-outline-primary" style="font-size: 0.8rem;">
                                <i class="ti ti-external-link me-1"></i>Open Full Page
                              </a>
                              <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#txDetail{{ $transaction->transaction_id }}" style="font-size: 0.8rem;">
                                <i class="ti ti-x me-1"></i>Close
                              </button>
                            </div>
                          </div>
                          <div class="card-body p-3">
                            {{-- Quick Info Grid --}}
                            <div class="row g-2 mb-3 small">
                              <div class="col-6 col-md-3">
                                <span class="text-muted d-block">Forklift:</span>
                                <span class="fw-semibold">
                                  <i class="ti ti-truck text-muted me-1"></i>{{ $transaction->forklift->model ?? 'N/A' }}
                                  @if(!empty($transaction->forklift->type))
                                    <span class="badge bg-light text-dark border ms-1">{{ $transaction->forklift->type }}</span>
                                  @endif
                                </span>
                              </div>
                              <div class="col-6 col-md-3">
                                <span class="text-muted d-block">Driver:</span>
                                <span class="fw-semibold">
                                  <i class="ti ti-user text-muted me-1"></i>{{ $transaction->driver->name ?? 'Unassigned' }}
                                </span>
                              </div>
                              <div class="col-6 col-md-3">
                                <span class="text-muted d-block">Booked By:</span>
                                <span class="fw-semibold">
                                  <i class="ti ti-user-edit text-muted me-1"></i>{{ $transaction->creator->name ?? 'System' }}
                                </span>
                              </div>
                              <div class="col-6 col-md-3">
                                <span class="text-muted d-block">Booked At:</span>
                                <span class="fw-semibold">
                                  <i class="ti ti-clock text-muted me-1"></i>{{ $transaction->created_at?->setTimezone('Asia/Bangkok')->format('d M Y H:i:s') ?? '-' }}
                                </span>
                              </div>
                            </div>

                            @if($transaction->status == 'Rejected')
                              <div class="alert alert-danger py-2 px-3 mb-3 small border-start border-4 border-danger">
                                <strong><i class="ti ti-circle-x me-1"></i>Rejected:</strong> {{ $transaction->rejection_reason }}
                                <span class="text-muted ms-2">(By: {{ $transaction->rejectedBy->name ?? 'Driver' }}{{ $transaction->rejected_at ? ' at ' . \Carbon\Carbon::parse($transaction->rejected_at)->format('d M Y H:i') : '' }})</span>
                              </div>
                            @endif

                            <h6 class="fw-bold mb-2 small" style="color: var(--color-secondary);">
                              <i class="ti ti-packages me-1"></i>Booking Items ({{ $transaction->details->count() }})
                            </h6>
                            @if($transaction->details->count() > 0)
                              <div class="table-responsive mb-3">
                                <table class="table table-sm table-bordered mb-0 align-middle small bg-white">
                                  <thead class="table-light">
                                    <tr>
                                      <th style="width: 40%;">Item Name</th>
                                      <th class="text-center" style="width: 15%;">Quantity</th>
                                      <th class="text-center" style="width: 15%;">Unit</th>
                                      <th style="width: 30%;">Description</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach($transaction->details as $detail)
                                      <tr>
                                        <td class="fw-medium">{{ $detail->item_name }}</td>
                                        <td class="text-center">{{ $detail->quantity }}</td>
                                        <td class="text-center">{{ $detail->unit }}</td>
                                        <td class="text-muted">{{ $detail->description ?? '-' }}</td>
                                      </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                              </div>
                            @else
                              <p class="text-muted small fst-italic mb-3">No specific items listed.</p>
                            @endif

                            @if($transaction->start_task_image_path || $transaction->end_task_image_path)
                              <div class="row g-2 pt-2 border-top">
                                @if($transaction->start_task_image_path)
                                  <div class="col-6 col-md-3">
                                    <span class="text-muted small d-block mb-1">Start Task Image:</span>
                                    <a href="{{ Storage::url($transaction->start_task_image_path) }}" target="_blank">
                                      <img src="{{ Storage::url($transaction->start_task_image_path) }}" class="img-thumbnail rounded" style="max-height: 90px; width: auto; object-fit: cover;" alt="Start Task">
                                    </a>
                                  </div>
                                @endif
                                @if($transaction->end_task_image_path)
                                  <div class="col-6 col-md-3">
                                    <span class="text-muted small d-block mb-1">End Task Image:</span>
                                    <a href="{{ Storage::url($transaction->end_task_image_path) }}" target="_blank">
                                      <img src="{{ Storage::url($transaction->end_task_image_path) }}" class="img-thumbnail rounded" style="max-height: 90px; width: auto; object-fit: cover;" alt="End Task">
                                    </a>
                                  </div>
                                @endif
                              </div>
                            @endif

                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="{{ $colCount }}" class="text-center p-4"><i class="ti ti-package-off text-muted mb-2" style="font-size: 2rem;"></i>
                    <p class="mb-0 text-muted">No transactions found.</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
          </div>

          <!-- Checkbox synchronization and Export Selected to Excel submission handler -->
          <script>
            document.addEventListener('DOMContentLoaded', function () {
              const selectAllTx = document.getElementById('selectAllTransactions');
              const btnExport = document.getElementById('btnExportSelected');
              const selectedCountSpan = document.getElementById('selectedCount');
              const exportForm = document.getElementById('exportSelectedForm');

              function updateSelectedState() {
                const checkedBoxes = Array.from(document.querySelectorAll('.tx-row-check:checked'));
                const uniqueIds = [...new Set(checkedBoxes.map(cb => cb.value))];
                const count = uniqueIds.length;
                if (selectedCountSpan) selectedCountSpan.textContent = count;
                if (btnExport) btnExport.disabled = count === 0;
              }

              if (selectAllTx) {
                selectAllTx.addEventListener('change', function () {
                  document.querySelectorAll('.tx-row-check').forEach(cb => {
                    cb.checked = selectAllTx.checked;
                  });
                  updateSelectedState();
                });
              }

              document.querySelectorAll('.tx-row-check').forEach(cb => {
                cb.addEventListener('change', function () {
                  const val = this.value;
                  document.querySelectorAll(`.tx-row-check[value="${val}"]`).forEach(sibling => {
                    sibling.checked = this.checked;
                  });
                  updateSelectedState();
                });
              });

              if (exportForm) {
                exportForm.addEventListener('submit', function (e) {
                  const checkedBoxes = Array.from(document.querySelectorAll('.tx-row-check:checked'));
                  const uniqueIds = [...new Set(checkedBoxes.map(cb => cb.value))];
                  if (uniqueIds.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one transaction to export.');
                    return;
                  }
                  const container = document.getElementById('selectedTxInputs');
                  container.innerHTML = '';
                  uniqueIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'transaction_ids[]';
                    input.value = id;
                    container.appendChild(input);
                  });
                });
              }
            });
          </script>

          <div class="d-md-none">
            @forelse($transactions as $transaction)
              @php
                $statusColor = match ($transaction->status) { 'Assigned' => 'var(--color-booking)', 'In Progress' => 'var(--color-progress)', 'Arrived' => 'var(--color-arrived)', 'Completed' => 'var(--color-success)', 'Rejected' => 'var(--color-danger)', default => '#6c757d'};
              @endphp
              <!-- Collapsible transaction card for mobile view with record underline/divider and checkbox  -->
              <div class="card mb-3 border shadow-sm rounded-3">
                <div class="card-body p-3">
                  <div class="d-flex justify-content-between align-items-start mb-2" data-bs-toggle="collapse" data-bs-target="#mobileTxDetail{{ $transaction->transaction_id }}" style="cursor: pointer;">
                    <div class="d-flex align-items-center gap-2">
                      @if($isAdminOrChecker)
                        <input class="form-check-input m-0 align-middle tx-row-check" type="checkbox" value="{{ $transaction->transaction_id }}" onclick="event.stopPropagation();">
                      @endif
                      <div>
                        <div class="fw-bold text-primary fs-6">#{{ $transaction->transaction_id }} - {{ $transaction->creator->name ?? 'System' }}</div>
                        <div class="small text-muted">Driver: {{ $transaction->driver->name ?? 'Unassigned' }}</div>
                      </div>
                    </div>
                    <div class="text-end">
                      <span class="status-badge"
                        style="background-color:{{ $statusColor }};padding:.25rem .5rem;font-size:.75rem;">{{ $transaction->status }}</span>
                      <div class="mt-1"><i class="ti ti-chevron-down text-muted small collapse-toggle-icon"></i></div>
                    </div>
                  </div>
                  <hr class="my-2 opacity-25">
                  <div class="d-flex align-items-center mb-2 small fw-bold text-dark" data-bs-toggle="collapse" data-bs-target="#mobileTxDetail{{ $transaction->transaction_id }}" style="cursor: pointer;">
                    <span>{{ $transaction->warehouseFrom->name ?? 'N/A' }}</span>
                    <i class="ti ti-arrow-right mx-2 text-muted"></i>
                    <span>{{ $transaction->warehouseTo->name ?? 'N/A' }}</span>
                  </div>
                  <div class="small text-muted mb-2"><i
                      class="ti ti-clock me-1"></i>{{ $transaction->created_at?->setTimezone('Asia/Bangkok')->format('d M Y H:i') ?? '-' }}
                  </div>
                  @if($transaction->driver_rating || $transaction->status == 'Rejected')
                    <div class="bg-light p-2 rounded mt-2">
                      @if($transaction->driver_rating)
                        <div class="rating-stars small"><span class="me-2 text-muted">Rating:</span>@for($i = 1; $i <= 5; $i++)<i
                        class="ti {{ $i <= $transaction->driver_rating ? 'ti-star-filled text-warning' : 'ti-star text-muted opacity-25' }}"></i>@endfor</div>
                        @if($transaction->driver_comment)
                          <p class="small text-muted fst-italic mb-0 mt-1">"{{ Str::limit($transaction->driver_comment, 40) }}"</p>
                        @endif
                      @elseif($transaction->status == 'Rejected')
                        <div class="text-danger fw-bold small"><i class="ti ti-alert-circle me-1"></i>Rejected</div>
                        @if($transaction->rejection_reason)
                          <p class="small text-muted fst-italic mb-0 mt-1">"{{ Str::limit($transaction->rejection_reason, 40) }}"</p>
                        @endif
                        <div class="small text-muted mt-1" style="font-size:.7rem;">By:
                          {{ $transaction->rejectedBy->name ?? 'Driver' }}</div>
                      @endif
                    </div>
                  @endif

                  <!-- Collapsible detail on mobile card -->
                  <div class="collapse mt-3 pt-3 border-top" id="mobileTxDetail{{ $transaction->transaction_id }}">
                    <div class="small mb-2">
                      <div class="text-muted">Forklift: <span class="fw-semibold text-dark">{{ $transaction->forklift->model ?? 'N/A' }} ({{ $transaction->forklift->type ?? 'Standard' }})</span></div>
                    </div>
                    @if($transaction->details->count() > 0)
                      <h6 class="fw-bold small mt-2 mb-1" style="color: var(--color-secondary);"><i class="ti ti-packages me-1"></i>Items:</h6>
                      <ul class="list-group list-group-flush small mb-3">
                        @foreach($transaction->details as $detail)
                          <li class="list-group-item px-0 py-1 bg-transparent d-flex justify-content-between align-items-center">
                            <span>{{ $detail->item_name }}</span>
                            <span class="badge bg-secondary-subtle text-secondary border">{{ $detail->quantity }} {{ $detail->unit }}</span>
                          </li>
                        @endforeach
                      </ul>
                    @endif
                    <div class="d-flex gap-2 mt-2">
                      <a href="{{ route('transactions.show', $transaction->transaction_id) }}" class="btn btn-sm btn-primary w-100" style="background-color: var(--color-secondary);">
                        <i class="ti ti-external-link me-1"></i>Open Full Page
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            @empty
              <div class="text-center p-4 border rounded bg-light"><i class="ti ti-package-off text-muted mb-2" style="font-size: 2rem;"></i>
                <p class="mb-0 small text-muted">No transactions found.</p>
              </div>
            @endforelse
          </div>

          @if($transactions->hasPages())
            <div class="d-flex justify-content-center mt-3 mt-md-4">
              {{ $transactions->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
          @endif
        </div>
      </div>

    @endif

  </div>

  @if(in_array(auth()->user()->role, ['admin', 'user', 'checker']))
    <!-- Changed FAB from link to modal trigger with Tabler icon -->
    <button type="button" class="fab" title="Create New Booking" data-bs-toggle="modal"
      data-bs-target="#createBookingModal">
      <i class="ti ti-plus"></i>
    </button>
  @endif

  <script>
    const viewMine = document.getElementById('viewMine');
    const viewAll = document.getElementById('viewAll');
    const cards = document.querySelectorAll('#driverCards .driver-task-card');
    function applyFilter() {
      const showMine = viewMine && viewMine.checked;
      cards.forEach(c => { c.style.display = (showMine && c.dataset.owner !== 'mine') ? 'none' : ''; });
    }
    if (viewMine) viewMine.addEventListener('change', applyFilter);
    if (viewAll) viewAll.addEventListener('change', applyFilter);
    applyFilter();
  </script>

  <!-- Create Booking Modal -->
  @if(in_array(auth()->user()->role, ['admin', 'user', 'checker']))
    @php
      $modalStandardItems = [
        'งานส่งขาย – MTL',
        'การเดินทางไปยัง BKK HUB',
        'การขนส่งระหว่างโรงงาน Spare part ,ขนย้ายวัถุดิบ',
        'การขนส่งระหว่างโรงงาน งานขายระหว่างโรงงาน',
        'การขนถ่ายจากรถบรรทุก',
        'การบรรทุกสินค้าขึ้นรถบรรทุก',
        'บริการพิเศษ'
      ];
      $modalStandardUnits = ['Pallet', 'Carton'];
    @endphp
    <div class="modal fade" id="createBookingModal" tabindex="-1" aria-labelledby="createBookingModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
          <form action="{{ route('transactions.store') }}" method="POST" id="modalBookingForm">
            @csrf
            <div class="modal-header text-white" style="background-color: var(--color-secondary);">
              <h5 class="modal-title fw-bold" id="createBookingModalLabel">
                <i class="ti ti-circle-plus me-2"></i>Create New Booking
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">

              <div class="row g-3 mb-3">
                <div class="col-12 col-md-6">
                  <label class="form-label small fw-bold">From Warehouse</label>
                  <select name="warehouse_from" class="form-select" required>
                    <option value="" disabled selected>-- Select a Warehouse --</option>
                    @foreach($warehouses as $warehouse)
                      <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label small fw-bold">To Warehouse</label>
                  <select name="warehouse_to" class="form-select" required>
                    <option value="" disabled selected>-- Select a Warehouse --</option>
                    @foreach($warehouses as $warehouse)
                      <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <hr>

              <h6 class="fw-bold mb-3">Booking Items</h6>
              <div id="modal-transaction-details">
                <div class="border rounded-3 p-3 mb-2 position-relative bg-light" id="modal-detail-0">
                  <div class="row g-2">
                    <div class="col-12">
                      <label class="form-label small fw-semibold">Item Name</label>
                      <select class="form-select form-select-sm modal-item-select" data-index="0" required>
                        <option value="" disabled selected>-- Select an Item --</option>
                        @foreach($modalStandardItems as $item)
                          <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                        <option value="_other">อื่น ๆ...</option>
                      </select>
                      <input type="text" id="modal_item_custom_0"
                        class="form-control form-control-sm mt-2 modal-item-custom" style="display:none;"
                        placeholder="Custom item name">
                      <input type="hidden" name="details[0][item_name]" id="modal_item_final_0">
                    </div>
                    <div class="col-6">
                      <label class="form-label small fw-semibold">Quantity</label>
                      <input type="number" name="details[0][quantity]" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-6">
                      <label class="form-label small fw-semibold">Unit</label>
                      <select class="form-select form-select-sm modal-unit-select" data-index="0" required>
                        <option value="" disabled selected>-- Unit --</option>
                        @foreach($modalStandardUnits as $unit)
                          <option value="{{ $unit }}">{{ $unit }}</option>
                        @endforeach
                        <option value="_other">Other...</option>
                      </select>
                      <input type="text" id="modal_unit_custom_0"
                        class="form-control form-control-sm mt-2 modal-unit-custom" style="display:none;"
                        placeholder="Custom unit">
                      <input type="hidden" name="details[0][unit]" id="modal_unit_final_0">
                    </div>
                    <div class="col-12">
                      <label class="form-label small fw-semibold">Remark</label>
                      <textarea name="details[0][description]" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <button type="button" class="btn btn-outline-success btn-sm mt-2 w-100" id="modal-add-detail">
                <i class="ti ti-plus me-1"></i>Add Another Item
              </button>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-action-primary btn-sm px-4 fw-bold">
                <i class="ti ti-device-floppy me-1"></i>Create Booking
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <script>
      (function () {
        const modalItems = @json($modalStandardItems);
        const modalUnits = @json($modalStandardUnits);
        let modalDetailCount = 1;
        const modalContainer = document.getElementById('modal-transaction-details');

        function handleModalItemChange(sel) {
          const idx = sel.dataset.index;
          const custom = document.getElementById('modal_item_custom_' + idx);
          const final = document.getElementById('modal_item_final_' + idx);
          if (sel.value === '_other') {
            custom.style.display = 'block'; custom.setAttribute('required', 'required'); final.value = custom.value;
          } else {
            custom.style.display = 'none'; custom.removeAttribute('required'); custom.value = ''; final.value = sel.value;
          }
        }

        function handleModalUnitChange(sel) {
          const idx = sel.dataset.index;
          const custom = document.getElementById('modal_unit_custom_' + idx);
          const final = document.getElementById('modal_unit_final_' + idx);
          if (sel.value === '_other') {
            custom.style.display = 'block'; custom.setAttribute('required', 'required'); final.value = custom.value;
          } else {
            custom.style.display = 'none'; custom.removeAttribute('required'); custom.value = ''; final.value = sel.value;
          }
        }

        if (modalContainer) {
          modalContainer.addEventListener('change', function (e) {
            if (e.target.classList.contains('modal-item-select')) handleModalItemChange(e.target);
            if (e.target.classList.contains('modal-unit-select')) handleModalUnitChange(e.target);
          });
          modalContainer.addEventListener('input', function (e) {
            if (e.target.classList.contains('modal-item-custom')) {
              const idx = e.target.id.replace('modal_item_custom_', '');
              const f = document.getElementById('modal_item_final_' + idx);
              if (f) f.value = e.target.value;
            }
            if (e.target.classList.contains('modal-unit-custom')) {
              const idx = e.target.id.replace('modal_unit_custom_', '');
              const f = document.getElementById('modal_unit_final_' + idx);
              if (f) f.value = e.target.value;
            }
          });
          modalContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('modal-remove-detail')) {
              const id = e.target.dataset.id;
              const el = document.getElementById('modal-detail-' + id);
              if (el) el.remove();
            }
          });
        }

        const addDetailBtn = document.getElementById('modal-add-detail');
        if (addDetailBtn) {
          addDetailBtn.addEventListener('click', function () {
            const n = modalDetailCount;
            let itemOpts = modalItems.map(i => `<option value="${i}">${i}</option>`).join('');
            let unitOpts = modalUnits.map(u => `<option value="${u}">${u}</option>`).join('');
            const html = `
                    <div class="border rounded-3 p-3 mb-2 position-relative bg-light" id="modal-detail-${n}">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 modal-remove-detail" data-id="${n}"></button>
                        <div class="row g-2">
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Item Name</label>
                                <select class="form-select form-select-sm modal-item-select" data-index="${n}" required>
                                    <option value="" disabled selected>-- Select an Item --</option>
                                    ${itemOpts}
                                    <option value="_other">อื่น ๆ...</option>
                                </select>
                                <input type="text" id="modal_item_custom_${n}" class="form-control form-control-sm mt-2 modal-item-custom" style="display:none;" placeholder="Custom item name">
                                <input type="hidden" name="details[${n}][item_name]" id="modal_item_final_${n}">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Quantity</label>
                                <input type="number" name="details[${n}][quantity]" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Unit</label>
                                <select class="form-select form-select-sm modal-unit-select" data-index="${n}" required>
                                    <option value="" disabled selected>-- Unit --</option>
                                    ${unitOpts}
                                    <option value="_other">Other...</option>
                                </select>
                                <input type="text" id="modal_unit_custom_${n}" class="form-control form-control-sm mt-2 modal-unit-custom" style="display:none;" placeholder="Custom unit">
                                <input type="hidden" name="details[${n}][unit]" id="modal_unit_final_${n}">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Remark</label>
                                <textarea name="details[${n}][description]" class="form-control form-control-sm" rows="2"></textarea>
                            </div>
                        </div>
                    </div>`;
            modalContainer.insertAdjacentHTML('beforeend', html);
            modalDetailCount++;
          });
        }
      })();
    </script>
  @endif

@endsection