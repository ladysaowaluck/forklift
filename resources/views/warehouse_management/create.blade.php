@extends('layouts.app')

@section('content')
{{-- Ensure FontAwesome is loaded for icons --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --color-primary: #004B8D;
        --color-secondary: #8B0019;
        --color-background: #f4f6f9;
        --color-border: #e3e6f0;
        --border-radius-md: 0.75rem;
    }
    body {
        background-color: var(--color-background);
        color: #212529;
        /* Dynamic fluid typography prevents oversized elements on mobile screens */
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
                            <i class="fas fa-plus-circle me-2"></i>Create New Warehouse
                        </h1>
                        <p class="fs-6 fs-md-5 text-muted mb-0">Add a new warehouse location to the system</p>
                    </div>

                    <form method="POST" action="{{ route('warehouses.store') }}">
                        @csrf

                        {{-- Warehouse Name Field --}}
                        <div class="mb-2 mb-md-3">
                            <label for="name" class="form-label small fw-bold">{{ __('Warehouse Name') }}</label>
                            <input id="name" type="text" class="form-control form-control-sm form-control-md-normal @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        {{-- Location Field --}}
                        <div class="mb-2 mb-md-3">
                            <label for="location" class="form-label small fw-bold">{{ __('Location') }}</label>
                            <input id="location" type="text" class="form-control form-control-sm form-control-md-normal @error('location') is-invalid @enderror" 
                                   name="location" value="{{ old('location') }}" required>
                            @error('location')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                        
                        {{-- Status is Active by default for new warehouses, so we can use a hidden input --}}
                        <input type="hidden" name="status" value="Active">

                        {{-- Submit Button --}}
                        <div class="d-grid gap-2 mt-3 mt-md-4">
                            <button type="submit" class="btn btn-primary btn-sm btn-md-normal text-white fw-bold py-2" style="background-color: var(--color-secondary); border-color: var(--color-primary);">
                                <i class="fas fa-plus-circle me-2"></i>{{ __('Create Warehouse') }}
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