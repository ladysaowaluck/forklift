@extends('layouts.app')

@section('content')
    {{-- Ensure FontAwesome is loaded for icons --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

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
            font-size: clamp(0.875rem, 1.5vw, 1rem);
        }

        .card-main-content {
            background-color: #ffffff;
            border: 1px solid var(--color-border);
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
            border-radius: var(--border-radius-md);
        }

        /* Modern Table Styling */
        .modern-table {
            border-collapse: separate;
            border-spacing: 0 0.5rem;
            font-size: 0.875rem;
            white-space: nowrap;
        }

        @media (min-width: 768px) {
            .modern-table {
                border-spacing: 0 0.75rem;
                font-size: 1rem;
            }
        }

        .modern-table thead th {
            border: 0;
            color: #6c757d;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            padding-top: 0;
        }

        @media (min-width: 768px) {
            .modern-table thead th {
                font-size: 0.85rem;
            }
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
        }

        .modern-table tbody td {
            vertical-align: middle;
            border: 0;
        }

        @media (min-width: 768px) {
            .modern-table tbody td {
                padding: 1rem 1.5rem;
            }
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
            padding: 0.3em 0.8em;
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: 50rem;
            color: #fff;
            display: inline-block;
        }

        @media (min-width: 768px) {
            .status-badge {
                padding: 0.4em 1em;
                font-size: 0.85rem;
            }
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
                <!-- <i class="fas fa-truck-pickup"></i>  -->
                <img src="{{ asset('images/forklift.png') }}" alt="Boxes Icon" class="me-2" style="width: 38px; height: auto;">Forklift Management
            </h1>
            <p class="text-secondary fs-6 fs-md-5 fw-medium mb-0">Manage all company forklifts</p>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex justify-content-start justify-content-md-end mb-3 mb-md-4">
            {{-- Assuming the route name is 'forklifts.create' --}}
            <a href="{{ route('forklifts.create') }}"
                class="btn btn-primary btn-sm btn-md-normal text-white px-3 fw-bold w-md-auto"
                style="background-color: var(--color-secondary); border-color: var(--color-primary);">
                <i class="fas fa-plus-circle me-2"></i>Add New Forklift
            </a>
        </div>


        <div class="card card-main-content border-0">
            <div class="card-body p-2 p-md-4">

                <!-- DESKTOP VIEW (Table Layout)  -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table mb-0 modern-table align-middle">
                        <thead class="text-center">
                            <tr>
                                <th class="text-start">Model</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
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
                                    <td>
                                        <span class="status-badge" style="background-color: {{ $statusColor }};">
                                            {{ $forklift->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('forklifts.edit', $forklift->forklift_id) }}"
                                                class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                            <form action="{{ route('forklifts.destroy', $forklift->forklift_id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this forklift?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="fas fa-trash-alt me-1"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center p-4">
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
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-dark fs-6">{{ $forklift->model }}</span>
                                    <span class="status-badge small"
                                        style="background-color: {{ $statusColor }}; font-size: 0.75rem; padding: 0.2rem 0.5rem;">
                                        {{ $forklift->status }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-end gap-2 mt-2 pt-2 border-top">
                                    <a href="{{ route('forklifts.edit', $forklift->forklift_id) }}"
                                        class="btn btn-outline-secondary btn-sm py-1 px-2" style="font-size: 0.8rem;">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('forklifts.destroy', $forklift->forklift_id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this forklift?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2"
                                            style="font-size: 0.8rem;">
                                            <i class="fas fa-trash-alt me-1"></i> Delete
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
        @if ($forklifts->hasPages())>
            <div class="d-flex justify-content-center mt-3 mt-md-4">
                {{ $forklifts->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection