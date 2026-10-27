@extends('layouts.app')

@section('content')
    {{-- Lady : Removed redundant FontAwesome CDN link; Tabler icons are provided globally by layouts.app --}}

    <style>
        :root {
            --color-primary: #ffffff;
            --color-secondary: #004B8D;
            --color-success: #198754;
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

        .modern-table .col-check {
            width: 36px;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .modern-table .col-num {
            font-variant-numeric: tabular-nums;
        }

        .co2-badge {
            background: rgba(25, 135, 84, 0.12);
            color: #198754;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 50rem;
            display: inline-flex;
            align-items: center;
            font-size: 0.82rem;
            border: 1px solid rgba(25, 135, 84, 0.25);
        }
    </style>

    <div class="container-fluid py-3 py-md-4 px-2 px-md-4">

        {{-- Flash Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3" role="alert">
                <i class="ti ti-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-3" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Header --}}
        <div class="text-center mb-3 mb-md-4">
            <h1 class="fw-bolder fs-3 fs-md-1" style="color: var(--color-secondary);">
                <i class="ti ti-route me-2 text-primary"></i>Warehouse Routes & Distances
            </h1>
            <p class="text-secondary fs-6 fs-md-5 fw-medium mb-0">Configure A to B warehouse routes and calculate EV CO2 reduction</p>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 mb-md-4">
            <a href="{{ route('warehouse.management') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-arrow-left me-1"></i>Back to Warehouses
            </a>
            <button type="button" class="btn btn-action-primary btn-sm px-3 fw-bold"
                data-bs-toggle="modal" data-bs-target="#createRouteModal">
                <i class="ti ti-plus me-2"></i>Add Route Distance
            </button>
        </div>

        {{-- Summary Banner --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 bg-primary-subtle text-primary me-3">
                            <i class="ti ti-map-2 fs-2"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Configured Routes</div>
                            <div class="fs-4 fw-bold">{{ $routes->total() ?? $routes->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 bg-success-subtle text-success me-3">
                            <i class="ti ti-leaf fs-2"></i>
                        </div>
                        <div>
                            <div class="text-muted small">EV CO2 Savings Rate</div>
                            <div class="fs-4 fw-bold text-success">~1.20 kg / KM</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-3 bg-warning-subtle text-warning me-3">
                            <i class="ti ti-bolt fs-2"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Electric Forklift Benefit</div>
                            <div class="fs-6 fw-bold">Zero Direct Emission</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Routes Content Card --}}
        <div class="card card-main-content border-0">
            <div class="card-body p-2 p-md-4">

                <div class="table-responsive d-none d-md-block">
                    <table class="table mb-0 modern-table align-middle">
                        <thead>
                            <tr>
                                <th class="text-start">Origin Warehouse (A)</th>
                                <th></th>
                                <th class="text-start">Destination Warehouse (B)</th>
                                <th class="text-end">Distance (KM)</th>
                                <th class="text-end">EV CO2 Reduced / Trip</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($routes as $route)
                                <tr>
                                    <td class="text-start fw-bold">
                                        <i class="ti ti-building-warehouse me-2 text-secondary"></i>{{ $route->originWarehouse->name ?? 'N/A' }}
                                    </td>
                                    <td class="text-center"><i class="ti ti-arrow-right text-muted"></i></td>
                                    <td class="text-start fw-bold">
                                        <i class="ti ti-building-warehouse me-2 text-info"></i>{{ $route->destWarehouse->name ?? 'N/A' }}
                                    </td>
                                    <td class="text-end col-num">
                                        <span class="badge bg-light text-dark border px-2 py-1 fs-6">
                                            {{ number_format($route->distance, 2) }} KM
                                        </span>
                                    </td>
                                    <td class="text-end col-num">
                                        <span class="co2-badge">
                                            <i class="ti ti-leaf me-1"></i>-{{ number_format($route->co2_reduction, 2) }} kg CO₂
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#editRouteModal{{ $route->route_id }}">
                                                <i class="ti ti-edit me-1"></i>Edit
                                            </button>
                                            <form action="{{ route('warehouse.routes.destroy', $route->route_id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this route?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="ti ti-trash me-1"></i>Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center p-4">
                                        <p class="mb-0 text-muted">No warehouse routes configured yet. Click "Add Route Distance" to start.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- MOBILE VIEW (Card Layout) -->
                <div class="d-block d-md-none">
                    @forelse($routes as $route)
                        <div class="card mb-3 border rounded-3 shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="fw-bold fs-6">{{ $route->originWarehouse->name ?? 'N/A' }}</span>
                                    <i class="ti ti-arrow-right text-muted mx-2"></i>
                                    <span class="fw-bold fs-6">{{ $route->destWarehouse->name ?? 'N/A' }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center my-2">
                                    <span class="badge bg-light text-dark border">{{ number_format($route->distance, 2) }} KM</span>
                                    <span class="co2-badge small">-{{ number_format($route->co2_reduction, 2) }} kg CO₂ / trip</span>
                                </div>
                                <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                                    <button type="button" class="btn btn-outline-secondary btn-sm py-1 px-2"
                                        data-bs-toggle="modal" data-bs-target="#editRouteModal{{ $route->route_id }}">
                                        <i class="ti ti-edit me-1"></i>Edit
                                    </button>
                                    <form action="{{ route('warehouse.routes.destroy', $route->route_id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this route?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2">
                                            <i class="ti ti-trash me-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center p-3 border rounded bg-light">
                            <p class="mb-0 small text-muted">No warehouse routes configured yet.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination if present --}}
                @if(method_exists($routes, 'hasPages') && $routes->hasPages())
                    <div class="d-flex justify-content-center mt-3 mt-md-4">
                        {{ $routes->links('pagination::bootstrap-5') }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Create Route Modal --}}
    <div class="modal fade" id="createRouteModal" tabindex="-1" aria-labelledby="createRouteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('warehouse.routes.store') }}" method="POST" id="createRouteForm">
                    @csrf
                    <div class="modal-header text-white" style="background-color: var(--color-secondary);">
                        <h5 class="modal-title fw-bold" id="createRouteModalLabel">
                            <i class="ti ti-plus me-2"></i>Add Route & Distance
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">

                        {{-- Origin Warehouse --}}
                        <div class="mb-3">
                            <label for="origin_warehouse_id" class="form-label small fw-bold">FROM Warehouse (Origin)</label>
                            <select name="origin_warehouse_id" id="origin_warehouse_id" class="form-select" required>
                                <option value="" disabled selected>-- Select Origin Warehouse --</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->warehouse_id }}" {{ old('origin_warehouse_id') == $wh->warehouse_id ? 'selected' : '' }}>
                                        {{ $wh->name }} ({{ $wh->location ?? 'No location' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Destination Warehouse --}}
                        <div class="mb-3">
                            <label for="dest_warehouse_id" class="form-label small fw-bold">TO Warehouse (Destination)</label>
                            <select name="dest_warehouse_id" id="dest_warehouse_id" class="form-select" required>
                                <option value="" disabled selected>-- Select Destination Warehouse --</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->warehouse_id }}" {{ old('dest_warehouse_id') == $wh->warehouse_id ? 'selected' : '' }}>
                                        {{ $wh->name }} ({{ $wh->location ?? 'No location' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Distance KM --}}
                        <div class="mb-3">
                            <label for="distance_input" class="form-label small fw-bold">Distance (KM)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0.01" name="distance" id="distance_input"
                                    class="form-control" placeholder="e.g. 2.50" value="{{ old('distance') }}" required
                                    oninput="calculateCo2Preview(this.value, 'create_co2_preview')">
                                <span class="input-group-text">KM</span>
                            </div>
                        </div>

                        {{-- Dynamic CO2 preview --}}
                        <div class="p-3 rounded-3 bg-light border text-center">
                            <div class="small text-muted mb-1">Estimated EV Forklift CO₂ Reduction</div>
                            <div class="fw-bold text-success fs-5" id="create_co2_preview">
                                0.00 kg CO₂ / trip
                            </div>
                            <small class="text-muted" style="font-size: 0.75rem;">(Based on 1.20 kg CO₂ reduction per KM for EV forklift)</small>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-action-primary btn-sm px-4 fw-bold">
                            <i class="ti ti-device-floppy me-1"></i>Save Route
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Route Modals --}}
    @foreach($routes as $route)
        <div class="modal fade" id="editRouteModal{{ $route->route_id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <form action="{{ route('warehouse.routes.update', $route->route_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header text-white" style="background-color: var(--color-secondary);">
                            <h5 class="modal-title fw-bold">
                                <i class="ti ti-edit me-2"></i>Edit Route Distance
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="mb-3 text-center p-2 rounded bg-light border">
                                <span class="fw-bold">{{ $route->originWarehouse->name ?? 'N/A' }}</span>
                                <i class="ti ti-arrow-right mx-2 text-muted"></i>
                                <span class="fw-bold">{{ $route->destWarehouse->name ?? 'N/A' }}</span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Distance (KM)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0.01" name="distance"
                                        class="form-control" value="{{ $route->distance }}" required
                                        oninput="calculateCo2Preview(this.value, 'edit_co2_preview_{{ $route->route_id }}')">
                                    <span class="input-group-text">KM</span>
                                </div>
                            </div>

                            <div class="p-3 rounded-3 bg-light border text-center">
                                <div class="small text-muted mb-1">Estimated EV Forklift CO₂ Reduction</div>
                                <div class="fw-bold text-success fs-5" id="edit_co2_preview_{{ $route->route_id }}">
                                    -{{ number_format($route->co2_reduction, 2) }} kg CO₂ / trip
                                </div>
                                <small class="text-muted" style="font-size: 0.75rem;">(Based on 1.20 kg CO₂ reduction per KM for EV forklift)</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-action-primary btn-sm px-4 fw-bold">
                                <i class="ti ti-device-floppy me-1"></i>Update Distance
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        function calculateCo2Preview(distance, elementId) {
            const val = parseFloat(distance);
            const el = document.getElementById(elementId);
            if (!isNaN(val) && val > 0) {
                const co2 = (val * 1.20).toFixed(2);
                el.innerText = `-${co2} kg CO₂ / trip`;
            } else {
                el.innerText = '0.00 kg CO₂ / trip';
            }
        }

    </script>
@endsection
