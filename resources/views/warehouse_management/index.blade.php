@extends('layouts.app')

@section('content')
{{-- Ensure FontAwesome is loaded for icons --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --color-primary: #E60012;
        --color-secondary: #004B8D;
        --color-success: #198754;
        --color-warning: #fd7e14; /* Orange for Maintenance */
        --color-inactive: #6c757d; /* Grey for Inactive */
        --color-background: #f4f6f9;
        --color-border: #e3e6f0;
        --border-radius-md: 0.75rem;
        --box-shadow-subtle: 0 2px 6px rgba(0, 0, 0, 0.04);
    }
    body {
        background-color: var(--color-background);
        color: #212529;
    }
    .card-main-content {
        background-color: #ffffff;
        border: 1px solid var(--color-border);
        box-shadow: 0 4px 18px rgba(0,0,0,0.05);
        border-radius: var(--border-radius-md);
    }
    /* Modern Table Styling */
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
        padding-top: 0;
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
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border: 0;
    }
    .modern-table tbody td:first-child { border-top-left-radius: var(--border-radius-md); border-bottom-left-radius: var(--border-radius-md); }
    .modern-table tbody td:last-child { border-top-right-radius: var(--border-radius-md); border-bottom-right-radius: var(--border-radius-md); }
    /* Status Badge */
    .status-badge {
        padding: 0.4em 1em;
        font-size: 0.85rem;
        font-weight: 700;
        border-radius: 50rem;
        color: #fff;
    }
</style>

<div class="container-fluid py-4 px-lg-4">
    
    {{-- Main Header --}}
    <div class="text-center mb-4">
        <h1 class="display-5 fw-bolder" style="color: var(--color-primary);">
            <i class="fas fa-warehouse"></i> Warehouse Management
        </h1>
        <p class="text-secondary fs-5 fw-medium">Manage all company warehouses</p>
    </div>

    {{-- Action Buttons --}}
    <div class="d-flex justify-content-end mb-4">
        <a href="{{ route('warehouses.create') }}" class="btn text-white" style="background-color: var(--color-primary);">
            <i class="fas fa-plus-circle me-2"></i>Add New Warehouse
        </a>
    </div>

    {{-- Main Content Card for Table --}}
    <div class="card card-main-content">
        <div class="card-body p-4 p-lg-5">
            <div class="table-responsive">
                <table class="table modern-table align-middle">
                    <thead class="text-center">
                        <tr>
                            <th class="text-start">Name</th>
                            <th class="text-start">Location</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse($warehouses as $warehouse)
                            <tr>
                                <td class="text-start fw-bold">{{ $warehouse->name }}</td>
                                <td class="text-start text-muted">{{ $warehouse->location }}</td>
                                <td>
                                    @php
                                        $statusColor = match($warehouse->status) {
                                            'Active' => 'var(--color-success)',
                                            'Maintenance' => 'var(--color-warning)',
                                            default => 'var(--color-inactive)',
                                        };
                                    @endphp
                                    <span class="status-badge" style="background-color: {{ $statusColor }};">
                                        {{ $warehouse->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('warehouses.edit', $warehouse->warehouse_id) }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('warehouses.destroy', $warehouse->warehouse_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this warehouse?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center p-5">
                                    <p class="mb-0 text-muted">No warehouses found. Please add a new one.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    @if ($warehouses->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $warehouses->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection