@extends('layouts.app')

@section('content')
    {{-- Lady : Removed redundant FontAwesome CDN link; Tabler icons are provided globally by layouts.app --}}

    <style>
        :root {
            --color-primary: #ffffff;
            --color-secondary: #004B8D;
            --color-success: #198754;
            --color-info: #0086BF;
            --color-warning: #fd7e14;
            --color-inactive: #6c757d;
            --color-background: #f4f6f9;
            --color-border: #e3e6f0;
            --border-radius-md: 0.75rem;
            --box-shadow-subtle: 0 2px 6px rgba(0, 0, 0, 0.04);
        }

        body {
            background-color: var(--color-background);
            color: #212529;
            font-size: 15px;
        }

        .card-main-content {
            background-color: #ffffff;
            border: 1px solid var(--color-border);
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
            border-radius: var(--border-radius-md);
        }

        .modern-table {
            border-collapse: collapse;
            font-size: 0.875rem;
            width: 100%;
        }

        .modern-table thead th {
            position: sticky;
            top: 0;
            background: #f8fafc;
            color: #475569;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.55rem 0.75rem;
            border-bottom: 1px solid #cbd5e1;
            box-shadow: inset 0 -1px 0 #cbd5e1;
            z-index: 2;
            white-space: nowrap;
        }

        .modern-table tbody tr {
            background-color: #fff;
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.12s ease;
        }

        .modern-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .modern-table tbody td {
            padding: 0.55rem 0.75rem;
            vertical-align: middle;
            border-top: none;
            border-bottom: 1px solid #f1f5f9;
        }

        .modern-table .col-num {
            font-variant-numeric: tabular-nums;
        }

        .status-badge {
            padding: 0.3em 0.8em;
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: 50rem;
            color: #fff;
            display: inline-block;
        }

        .table-responsive {
            overscroll-behavior-x: contain;
            -webkit-overflow-scrolling: touch;
            scrollbar-gutter: stable;
        }

    </style>

    <div class="container-fluid py-3 py-md-4 px-2 px-md-4">

        {{-- Main Header --}}
        <div class="text-center mb-3 mb-md-4">
            <h1 class="fw-bolder fs-3 fs-md-1" style="color: var(--color-secondary);">
                <img src="{{ asset('images/forklift.png') }}" alt="Boxes Icon" class="me-2"
                    style="width: 38px; height: auto;">Forklift Management
            </h1>
            <p class="text-secondary fs-6 fs-md-5 fw-medium mb-0">Manage all company forklifts</p>
        </div>

         <!-- Action Buttons -->
        <div class="d-flex justify-content-start justify-content-md-end mb-3 mb-md-4">
            <button type="button" class="btn btn-action-primary btn-sm px-3 fw-bold w-md-auto"
                data-bs-toggle="modal" data-bs-target="#createForkliftModal">
                <i class="ti ti-plus me-2"></i>Add New Forklift
            </button>
        </div>


        <div class="card card-main-content border-0">
            <div class="card-body p-2 p-md-4">

                <!-- DESKTOP VIEW (Table Layout)  -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table mb-0 modern-table align-middle">
                        <thead>
                            <tr>
                                <th class="text-start">Model</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($forklifts as $forklift)
                                @php
                                    $statusColor = match ($forklift->status) {
                                        'Active' => 'var(--color-success)',
                                        'In Use' => 'var(--color-info)',
                                        'Maintenance' => 'var(--color-warning)',
                                        default => 'var(--color-inactive)',
                                    };
                                @endphp
                                <tr>
                                    <td class="text-start fw-bold">{{ $forklift->model }}</td>
                                    <!-- Display forklift type with visual badge -->
                                    <td class="text-center">
                                        @if(($forklift->type ?? 'Standard') === 'Electric (EV)')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">
                                                <i class="ti ti-bolt me-1"></i>Electric (EV)
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 rounded-pill">
                                                <i class="ti ti-gas-station me-1"></i>Standard
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="status-badge" style="background-color: {{ $statusColor }};">
                                            {{ $forklift->status }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('forklifts.edit', $forklift->forklift_id) }}"
                                                class="btn btn-outline-secondary btn-sm">
                                                <i class="ti ti-edit me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('forklifts.destroy', $forklift->forklift_id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this forklift?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="ti ti-trash me-1"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center p-4">
                                        <p class="mb-0 text-muted">No forklifts found. Please add a new one.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- MOBILE VIEW (Card Layout) -->
                <div class="d-block d-md-none">
                    @forelse($forklifts as $forklift)
                        @php
                            $statusColor = match ($forklift->status) {
                                'Active' => 'var(--color-success)',
                                'In Use' => 'var(--color-info)',
                                'Maintenance' => 'var(--color-warning)',
                                default => 'var(--color-inactive)',
                            };
                        @endphp
                        <div class="card mb-2 border rounded-3 shadow-sm">
                            <div class="card-body p-3">
                                <!-- Display Forklift Model and Type in card header -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="fw-bold text-dark fs-6 d-block">{{ $forklift->model }}</span>
                                        <span class="text-muted small">
                                            @if(($forklift->type ?? 'Standard') === 'Electric (EV)')
                                                <i class="ti ti-bolt text-success me-1"></i>Electric (EV)
                                            @else
                                                <i class="ti ti-gas-station text-secondary me-1"></i>Standard
                                            @endif
                                        </span>
                                    </div>
                                    <span class="status-badge"
                                        style="background-color: {{ $statusColor }};">
                                        {{ $forklift->status }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-end gap-2 mt-2 pt-2 border-top">
                                    <a href="{{ route('forklifts.edit', $forklift->forklift_id) }}"
                                        class="btn btn-outline-secondary btn-sm py-1 px-2">
                                        <i class="ti ti-edit me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('forklifts.destroy', $forklift->forklift_id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this forklift?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2">
                                            <i class="ti ti-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center p-3 border rounded bg-light">
                            <p class="mb-0 small text-muted">No forklifts found. Please add a new one.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

        {{-- Pagination --}}
        @if ($forklifts->hasPages())
            <div class="d-flex justify-content-center mt-3 mt-md-4">
                {{ $forklifts->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <!-- Create Forklift Modal -->
    <div class="modal fade" id="createForkliftModal" tabindex="-1" aria-labelledby="createForkliftModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('forklifts.store') }}" method="POST">
                    @csrf
                    <div class="modal-header text-white" style="background-color: var(--color-secondary);">
                        <h5 class="modal-title fw-bold" id="createForkliftModalLabel">
                            <i class="ti ti-plus me-2"></i>Add New Forklift
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">

                        <div class="mb-3">
                            <label for="modal_forklift_model" class="form-label small fw-bold">Model</label>
                            <input type="text" name="model" id="modal_forklift_model" class="form-control" required placeholder="e.g. Toyota 8FBE15">
                        </div>

                        <div class="mb-3">
                            <label for="modal_plate_number" class="form-label small fw-bold">Plate Number</label>
                            <input type="text" name="plate_number" id="modal_plate_number" class="form-control" required placeholder="e.g. กข-1234">
                        </div>

                        <div class="mb-3">
                            <label for="modal_forklift_type" class="form-label small fw-bold">Type</label>
                            <select name="type" id="modal_forklift_type" class="form-select" required>
                                <option value="Standard" selected>Standard</option>
                                <option value="Electric (EV)">Electric (EV)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="modal_forklift_status" class="form-label small fw-bold">Status</label>
                            <select name="status" id="modal_forklift_status" class="form-select" required>
                                <option value="Active" selected>Active</option>
                                <option value="Maintenance">Maintenance</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-action-primary btn-sm px-4 fw-bold">
                            <i class="ti ti-device-floppy me-1"></i>Save Forklift
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection