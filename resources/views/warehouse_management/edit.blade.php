@extends('layouts.app')

@section('content')
{{-- Ensure FontAwesome is loaded for icons --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --color-primary: #E60012;
        --color-secondary: #004B8D;
        --color-background: #f4f6f9;
        --color-border: #e3e6f0;
        --border-radius-md: 0.75rem;
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
</style>

<div class="container-fluid py-4 px-lg-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <div class="card card-main-content">
                <div class="card-body p-4 p-lg-5">

                    {{-- Header --}}
                    <div class="text-center mb-5">
                        <h1 class="fw-bolder" style="color: var(--color-secondary);">
                            <i class="fas fa-edit me-2"></i>Edit Warehouse
                        </h1>
                        <p class="fs-5 text-muted">Update details for: <span class="fw-bold">{{ $warehouse->name }}</span></p>
                    </div>

                    <form method="POST" action="{{ route('warehouses.update', $warehouse->warehouse_id) }}">
                        @csrf
                        @method('PUT')

                        {{-- Warehouse Name Field --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">{{ __('Warehouse Name') }}</label>
                            <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name', $warehouse->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Location Field --}}
                        <div class="mb-3">
                            <label for="location" class="form-label fw-bold">{{ __('Location') }}</label>
                            <input id="location" type="text" class="form-control form-control-lg @error('location') is-invalid @enderror" 
                                   name="location" value="{{ old('location', $warehouse->location) }}" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status Field --}}
                        <div class="mb-4">
                            <label for="status" class="form-label fw-bold">{{ __('Status') }}</label>
                            <select id="status" class="form-select form-select-lg @error('status') is-invalid @enderror" name="status" required>
                                <option value="Active" {{ old('status', $warehouse->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ old('status', $warehouse->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-lg text-white" style="background-color: var(--color-primary);">
                                <i class="fas fa-save me-2"></i> {{ __('Update Warehouse') }}
                            </button>
                        </div>
                    </form>

                    {{-- Back Button --}}
                    <div class="text-center mt-4">
                        <a href="{{ route('warehouses.index') }}" class="btn btn-link text-secondary">Back to Warehouse List</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection