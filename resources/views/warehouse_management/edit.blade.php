@extends('layouts.app')

@section('content')
{{-- Ensure FontAwesome is loaded for icons --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --color-primary: #ffffff;
        --color-secondary: #004B8D;
        --color-background: #f4f6f9;
        --color-border: #e3e6f0;
        --border-radius-md: 0.75rem;
    }
    body {
        background-color: var(--color-background);
        color: #212529;
        font-size: clamp(0.875rem, 1.5vw, 1rem);
    }
    .card-main-content {
        background-color: #ffffff;
        border: 1px solid var(--color-border);
        box-shadow: 0 4px 18px rgba(0,0,0,0.05);
        border-radius: var(--border-radius-md);
    }
</style>

<div class="container-fluid py-3 py-md-4 px-2 px-md-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-xl-6">
            <div class="card card-main-content border-0">
                <div class="card-body p-3 p-sm-4 p-lg-5">

                    {{-- Header --}}
                    <div class="text-center mb-3 mb-md-4">
                        <h1 class="fw-bolder fs-3 fs-md-2" style="color: var(--color-secondary);">
                            <i class="fas fa-edit me-2"></i>Edit Warehouse
                        </h1>
                        <p class="fs-6 fs-md-5 text-muted mb-0">Update details for: <span class="fw-bold">{{ $warehouse->name }}</span></p>
                    </div>

                    <form method="POST" action="{{ route('warehouses.update', $warehouse->warehouse_id) }}">
                        @csrf
                        @method('PUT')

                        {{-- Warehouse Name Field --}}
                        <div class="mb-2 mb-md-3">
                            <label for="name" class="form-label small fw-bold">{{ __('Warehouse Name') }}</label>
                            <input id="name" type="text" class="form-control form-control-sm form-control-md-normal @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name', $warehouse->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Location Field --}}
                        <div class="mb-2 mb-md-3">
                            <label for="location" class="form-label small fw-bold">{{ __('Location') }}</label>
                            <input id="location" type="text" class="form-control form-control-sm form-control-md-normal @error('location') is-invalid @enderror" 
                                   name="location" value="{{ old('location', $warehouse->location) }}" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status Field --}}
                        <div class="mb-3 mb-md-4">
                            <label for="status" class="form-label small fw-bold">{{ __('Status') }}</label>
                            <select id="status" class="form-select form-select-sm form-select-md-normal @error('status') is-invalid @enderror" name="status" required>
                                <option value="Active" {{ old('status', $warehouse->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ old('status', $warehouse->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <div class="d-grid gap-2 mt-3 mt-md-4">
                            <button type="submit" class="btn btn-primary btn-sm btn-md-normal text-white fw-bold py-2" style="background-color: var(--color-secondary); border-color: var(--color-primary);">
                                <i class="fas fa-save me-2"></i> {{ __('Update Warehouse') }}
                            </button>
                        </div>
                    </form>

                    {{-- Back Button --}}
                    <div class="text-center mt-3 mt-md-4">
                        <a href="{{ route('warehouses.index') }}" class="btn btn-link btn-sm text-secondary text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i>Back to Warehouse List
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection