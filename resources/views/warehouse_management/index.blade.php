@extends('layouts.app')

@section('content')
    <style>
        :root {
            --color-primary: #ffffff;
            --color-secondary: #004B8D;
            --color-success: #198754;
            --color-warning: #fd7e14;
            /* Orange for Maintenance */
            --color-inactive: #6c757d;
            /* Grey for Inactive */
            --color-background: #f4f6f9;
            --color-border: #e3e6f0;
            --border-radius-md: 0.75rem;
            --box-shadow-subtle: 0 2px 6px rgba(0, 0, 0, 0.04);
        }
/* 
            {
                {
                -- Lady: Mobile viewport font-size clamp shrank mobile UI typography relative to web. Gemini: Standardize base font size to 15px so mobile renders at the same size as desktop. --
            }
        } */

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
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
            letter-spacing: -0.01em;
            white-space: nowrap;
        }

        .modern-table thead th {
            position: sticky;
            top: 0;
            z-index: 3;
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 700;
            font-size: 0.725rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 0.55rem 0.75rem;
            border-top: 0;
            border-bottom: 1px solid var(--color-border);
            box-shadow: inset 0 -1px 0 var(--color-border);
        }

        .modern-table tbody tr {
            background-color: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.15s ease-in-out;
        }

        .modern-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .modern-table tbody td {
            padding: 0.55rem 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.875rem;
            color: #1e293b;
        }

        .modern-table th.col-num,
        .modern-table td.col-num,
        .modern-table .text-end {
            text-align: right !important;
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

        .table-header {
            font-weight: 700;
            color: #2f3336;
        }
    </style>

    <div class="container-fluid py-3 py-md-4 px-2 px-md-4">

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

        {{-- Main Header --}}
        <div class="text-center mb-3 mb-md-4">
            <h1 class="fw-bolder fs-3 fs-md-1" style="color: var(--color-secondary);">
                <img src="{{ asset('images/warehouse.png') }}" alt="Boxes Icon" class="me-2"
                    style="width: 38px; height: auto;">Warehouse Management
            </h1>
            <p class="text-secondary fs-6 fs-md-5 fw-medium mb-0">Manage all company warehouses</p>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex flex-wrap justify-content-start justify-content-md-end align-items-center gap-2 mb-3 mb-md-4">

            <!-- Button to toggle warehouse distances list -->
            <button type="button" class="btn btn-outline-secondary btn-sm px-3 fw-bold" data-bs-toggle="collapse"
                data-bs-target="#warehouseRoutesCollapse" aria-expanded="false">
                <i class="ti ti-route me-1"></i>Warehouse Distances & CO₂ ({{ isset($routes) ? $routes->count() : 0 }})
            </button>

            <!-- Button to open create route distance form modal  -->
            <button type="button" class="btn btn-outline-primary btn-sm px-3 fw-bold" data-bs-toggle="modal"
                data-bs-target="#createRouteModal">
                <i class="ti ti-map-pin me-1"></i>Add Route Distance
            </button>


            <button type="button" class="btn btn-action-primary btn-sm px-3 fw-bold w-md-auto" data-bs-toggle="modal"
                data-bs-target="#createWarehouseModal">
                <i class="ti ti-plus me-2"></i>Add New Warehouse
            </button>
        </div>

        <!-- Warehouse Routes & Distances Collapsible Section -->
        <div class="collapse mb-4" id="warehouseRoutesCollapse">
            <div class="card card-main-content border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-route text-primary fs-5 me-2"></i>
                        <h5 class="mb-0 fw-bold" style="color: var(--color-secondary);">Warehouse Routes & Distances (KM)
                        </h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-action-primary btn-sm me-2" data-bs-toggle="modal"
                            data-bs-target="#createRouteModal">
                            <i class="ti ti-plus me-1"></i>Add Route
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse"
                            data-bs-target="#warehouseRoutesCollapse">
                            <i class="ti ti-x me-1"></i>Close
                        </button>
                    </div>
                </div>
                <div class="card-body p-3 p-md-4">
                    @php
                        $isAdminOrChecker = in_array(strtolower(Auth::user()->role ?? ''), ['admin', 'checker']);
                    @endphp
                    <div class="table-responsive">
                        <table class="table modern-table mb-0 align-middle">
                            <thead class="text-center">
                                <tr>
                                    <th class="text-start">FROM Warehouse (A)</th>
                                    <th style="width: 30px;"></th>
                                    <th class="text-start">TO Warehouse (B)</th>
                                    <th class="text-end col-num">Distance</th>
                                    <th class="text-end col-num">EV CO₂ Saved / Trip</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @forelse($routes ?? [] as $route)
                                    <tr>
                                        <td class="text-start fw-bold">{{ $route->originWarehouse->name ?? 'N/A' }}</td>
                                        <td><i class="ti ti-arrow-right text-muted"></i></td>
                                        <td class="text-start fw-bold">{{ $route->destWarehouse->name ?? 'N/A' }}</td>
                                        <td class="text-end col-num">
                                            <span class="badge bg-light text-dark border px-2 py-1">
                                                {{ number_format($route->distance, 2) }} KM
                                            </span>
                                        </td>
                                        <td class="text-end col-num">
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">
                                                <i class="ti ti-leaf me-1"></i>-{{ number_format($route->co2_reduction, 2) }} kg
                                                CO₂
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1">
                                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editRouteModal{{ $route->route_id }}">
                                                    <i class="ti ti-edit me-1"></i>Edit
                                                </button>
                                                <form action="{{ route('warehouse.routes.destroy', $route->route_id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this route?');"
                                                    class="d-inline">
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
                                        <td colspan="6" class="text-center p-3 text-muted">
                                            No warehouse routes configured yet. Click "Add Route Distance" to start.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-main-content border-0">
            <div class="card-body p-2 p-md-4">

                <div class="table-responsive d-none d-md-block">
                    <table class="table mb-0 modern-table align-middle">
                        <thead class="text-center">
                            <tr>
                                <th class="table-header text-start">Name</th>
                                <th class="table-header text-start">Location</th>
                                <th class="table-header">Status</th>
                                <th class="table-header">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @forelse($warehouses as $warehouse)
                                @php
                                    $statusColor = match ($warehouse->status) {
                                        'Active' => 'var(--color-success)',
                                        'Maintenance' => 'var(--color-warning)',
                                        default => 'var(--color-inactive)',
                                    };
                                @endphp
                                <tr>
                                    <td class="text-start fw-bold">{{ $warehouse->name }}</td>
                                    <td class="text-start text-muted">{{ $warehouse->location }}</td>
                                    <td>
                                        <span class="status-badge" style="background-color: {{ $statusColor }};">
                                            {{ $warehouse->status }}
                                        </span>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('warehouses.edit', $warehouse->warehouse_id) }}"
                                                class="btn btn-outline-secondary btn-sm">
                                                <i class="ti ti-edit me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('warehouses.destroy', $warehouse->warehouse_id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this warehouse?');">
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
                                        <p class="mb-0 text-muted">No warehouses found. Please add a new one.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- MOBILE VIEW (Card Layout) - -->
                <div class="d-block d-md-none">
                    @forelse($warehouses as $warehouse)
                        @php
                            $statusColor = match ($warehouse->status) {
                                'Active' => 'var(--color-success)',
                                'Maintenance' => 'var(--color-warning)',
                                default => 'var(--color-inactive)',
                            };
                         @endphp
                        <div class="card mb-2 border rounded-3 shadow-sm">
                            <div class="card-body p-3">
                                <!-- Name & Status Row -->
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <span class="fw-bold text-dark fs-6">{{ $warehouse->name }}</span>
                                    <span class="status-badge" style="background-color: {{ $statusColor }};">
                                        {{ $warehouse->status }}
                                    </span>
                                </div>

                                <!-- Location Subtitle -->
                                <div class="small text-muted mb-2">
                                    <i class="ti ti-map-pin me-1"></i>{{ $warehouse->location ?? 'No location provided' }}
                                </div>

                                <!-- Action Buttons Row -->
                                <div class="d-flex justify-content-end gap-2 mt-2 pt-2 border-top">
                                    <a href="{{ route('warehouses.edit', $warehouse->warehouse_id) }}"
                                        class="btn btn-outline-secondary btn-sm py-1 px-2">
                                        <i class="ti ti-edit me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('warehouses.destroy', $warehouse->warehouse_id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this warehouse?');">
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
                            <p class="mb-0 small text-muted">No warehouses found. Please add a new one.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

        <!-- Pagination -->
        @if ($warehouses->hasPages())
            <div class="d-flex justify-content-center mt-3 mt-md-4">
                {{ $warehouses->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <!-- Create Route Modal -->
    <div class="modal fade" id="createRouteModal" tabindex="-1" aria-labelledby="createRouteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('warehouse.routes.store') }}" method="POST">
                    @csrf
                    <div class="modal-header text-white" style="background-color: var(--color-secondary);">
                        <h5 class="modal-title fw-bold" id="createRouteModalLabel">
                            <i class="ti ti-plus me-2"></i>Add Route & Distance
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">

                        {{-- Origin Warehouse --}}
                        <div class="mb-3">
                            <label for="modal_origin_warehouse_id" class="form-label small fw-bold">FROM Warehouse
                                (Origin)</label>
                            <select name="origin_warehouse_id" id="modal_origin_warehouse_id" class="form-select" required>
                                <option value="" disabled selected>-- Select Origin Warehouse --</option>
                                @foreach($allWarehouses ?? $warehouses as $wh)
                                    <option value="{{ $wh->warehouse_id }}">{{ $wh->name }}
                                        ({{ $wh->location ?? 'No location' }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Destination Warehouse --}}
                        <div class="mb-3">
                            <label for="modal_dest_warehouse_id" class="form-label small fw-bold">TO Warehouse
                                (Destination)</label>
                            <select name="dest_warehouse_id" id="modal_dest_warehouse_id" class="form-select" required>
                                <option value="" disabled selected>-- Select Destination Warehouse --</option>
                                @foreach($allWarehouses ?? $warehouses as $wh)
                                    <option value="{{ $wh->warehouse_id }}">{{ $wh->name }}
                                        ({{ $wh->location ?? 'No location' }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Distance KM --}}
                        <div class="mb-3">
                            <label for="modal_distance_input" class="form-label small fw-bold">Distance (KM)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0.01" name="distance" id="modal_distance_input"
                                    class="form-control" placeholder="e.g. 2.50" required
                                    oninput="calculateIndexCo2Preview(this.value, 'modal_create_co2_preview')">
                                <span class="input-group-text">KM</span>
                            </div>
                        </div>

                        {{-- Dynamic CO2 preview --}}
                        <div class="p-3 rounded-3 bg-light border text-center">
                            <div class="small text-muted mb-1">Estimated EV Forklift CO₂ Reduction</div>
                            <div class="fw-bold text-success fs-5" id="modal_create_co2_preview">
                                0.00 kg CO₂ / trip
                            </div>
                            <small class="text-muted" style="font-size: 0.75rem;">(Based on 1.20 kg CO₂ reduction per KM for
                                EV forklift)</small>
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

    <!-- Edit Route Modals -->
    @if(isset($routes))
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
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
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
                                        <input type="number" step="0.01" min="0.01" name="distance" class="form-control"
                                            value="{{ $route->distance }}" required
                                            oninput="calculateIndexCo2Preview(this.value, 'modal_edit_co2_preview_{{ $route->route_id }}')">
                                        <span class="input-group-text">KM</span>
                                    </div>
                                </div>

                                <div class="p-3 rounded-3 bg-light border text-center">
                                    <div class="small text-muted mb-1">Estimated EV Forklift CO₂ Reduction</div>
                                    <div class="fw-bold text-success fs-5" id="modal_edit_co2_preview_{{ $route->route_id }}">
                                        -{{ number_format($route->co2_reduction, 2) }} kg CO₂ / trip
                                    </div>
                                    <small class="text-muted" style="font-size: 0.75rem;">(Based on 1.20 kg CO₂ reduction per KM for
                                        EV forklift)</small>
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
    @endif

    <!-- Create Warehouse Modal -->
    <div class="modal fade" id="createWarehouseModal" tabindex="-1" aria-labelledby="createWarehouseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('warehouses.store') }}" method="POST">
                    @csrf
                    <div class="modal-header text-white" style="background-color: var(--color-secondary);">
                        <h5 class="modal-title fw-bold" id="createWarehouseModalLabel">
                            <i class="ti ti-plus me-2"></i>Add New Warehouse
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">

                        <div class="mb-3">
                            <label for="modal_warehouse_name" class="form-label small fw-bold">Warehouse Name</label>
                            <input type="text" name="name" id="modal_warehouse_name" class="form-control" required
                                placeholder="e.g. Central Warehouse">
                        </div>

                        <div class="mb-3">
                            <label for="modal_warehouse_location" class="form-label small fw-bold">Location</label>
                            <input type="text" name="location" id="modal_warehouse_location" class="form-control" required
                                placeholder="e.g. Bangkok, Thailand">
                        </div>

                        <input type="hidden" name="status" value="Active">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-action-primary btn-sm px-4 fw-bold">
                            <i class="ti ti-device-floppy me-1"></i>Save Warehouse
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Lady : Removed stray duplicate closing tags (</div>
    </form>
    </div>
    </div>
    </div>) that broke layout DOM tree. --}}

    <script>
        function calculateIndexCo2Preview(distance, elementId) {
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