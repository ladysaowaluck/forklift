@extends('layouts.app')

@section('content')
    {{-- Ensure FontAwesome is loaded for icons --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            /* Gemini: Fix syntax typo 'ff--color-primary' -> '--color-primary' */
            --color-primary: #ffffff;
            /* --color-primary: #004B8D; */
            --color-secondary: #004B8D;
            --color-info: #FA7800;
            --color-success: #198754;
            --color-danger: #dc3545;
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
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
            border-radius: var(--border-radius-md);
        }

        /* Styles for image previews */
        .image-preview-container {
            border: 1px solid var(--color-border);
            border-radius: var(--border-radius-md);
            padding: 1rem;
            height: 100%;
            position: relative;
        }

        .image-preview {
            width: 100%;
            max-height: 250px;
            object-fit: cover;
            aspect-ratio: 16 / 9;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .image-preview:hover {
            transform: scale(1.02);
        }

        .btn-edit-image {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 10;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .modal-image-preview {
            max-width: 100%;
            max-height: 300px;
            border-radius: 0.5rem;
            border: 2px dashed var(--color-border);
            display: none;
            margin-top: 1rem;
            object-fit: contain;
        }

        .file-input-label {
            cursor: pointer;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            background-color: var(--color-secondary);
            color: white;
            transition: background-color 0.2s;
            /* Gemini: Ensure full-width display on mobile for better touch target */
            display: inline-block;
            width: 100%;
            text-align: center;
        }

        @media (min-width: 576px) {
            .file-input-label {
                width: auto;
            }
        }

        .file-input-label:hover {
            background-color: #00357a;
        }

        .star-rating .star {
            color: #ccc;
            cursor: pointer;
            transition: color 0.2s;
            font-size: 1.8rem;
        }

        @media (min-width: 576px) {
            .star-rating .star {
                font-size: 2.5rem;
            }
        }

        .star-rating .star.selected,
        .star-rating .star:hover,
        .star-rating .star:hover~.star {
            color: gold;
        }

        .star-rating .star:hover~.star {
            color: #ccc;
        }

        .action-box {
            background-color: #f8f9fa;
            border: 1px dashed var(--color-border);
            padding: 1rem;
            border-radius: var(--border-radius-md);
        }

        @media (min-width: 576px) {
            .action-box {
                padding: 1.5rem;
            }
        }

        .file-input-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            overflow: hidden;
            z-index: -1;
        }

        /* Gemini: Additional responsive enhancements for mobile devices */
        @media (max-width: 576px) {
            .card-main-content .card-body {
                padding: 1.25rem !important;
            }

            .image-preview {
                max-height: 180px;
            }
        }
    </style>

    {{-- Gemini: Adjusted container padding for mobile view --}}
    {{-- <div class="container-fluid py-4 px-lg-4"> - unnecessary - Gemini Lady --}}
    <div class="container-fluid py-2 py-md-4 px-2 px-lg-4">
        <div class="card card-main-content">
            {{-- Gemini: Scaled down card body padding for small screens --}}
            {{-- <div class="card-body p-4 p-lg-5"> - unnecessary - Gemini Lady --}}
            <div class="card-body p-3 p-md-4 p-lg-5">
                {{-- Header and Booking Info --}}
                <div class="text-center mb-3 mb-md-4">
                    {{-- Gemini: Made header font responsive --}}
                    {{-- <h1 class="fw-bolder" style="color: var(--color-secondary);">Booking Details</h1> - unnecessary - Gemini Lady --}}
                    <h2 class="fw-bolder h3-md display-6-lg" style="color: var(--color-secondary);">Booking Details</h2>
                    <p class="fs-6 fs-md-5 text-muted mb-0">Transaction ID: #{{ $transaction->transaction_id }}</p>
                </div>
                <hr class="my-3 my-md-4">

                {{-- Gemini: Removed rigid fs-5 from row to allow flexible responsive font sizes --}}
                {{-- <div class="row g-4 fs-5 mb-5"> - unnecessary - Gemini Lady --}}
                <div class="row g-3 g-md-4 mb-4 mb-md-5 fs-6 fs-md-5">
                    <div class="col-12 col-md-6">
                        <div class="fw-bold">
                            <i class="fas fa-calendar-alt fa-fw me-2 text-muted"></i>
                            Booked Date/Time:
                            <span class="fw-normal d-block d-sm-inline">{{ $transaction->created_at->format('d M Y H:i:s') ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="fw-bold">
                            <i class="fas fa-user-circle fa-fw me-2 text-muted"></i>
                            Booked By:
                            <span class="fw-normal d-block d-sm-inline">{{ $transaction->creator->name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="fw-bold">
                            <i class="fas fa-truck fa-fw me-2 text-muted"></i>
                            Forklift:
                            <span class="fw-normal d-block d-sm-inline">{{ $transaction->forklift->model ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="fw-bold">
                            <i class="fas fa-user-tie fa-fw me-2 text-muted"></i>
                            Driver:
                            <span class="fw-normal d-block d-sm-inline">{{ $transaction->driver->name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="fw-bold">
                            <i class="fas fa-warehouse fa-fw me-2 text-muted"></i>
                            From:
                            <span class="fw-normal d-block d-sm-inline">{{ $transaction->warehouseFrom->name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="fw-bold">
                            <i class="fas fa-dolly fa-fw me-2 text-muted"></i>
                            To:
                            <span class="fw-normal d-block d-sm-inline">{{ $transaction->warehouseTo->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Rejection Alert --}}
                @if($transaction->status == 'Rejected')
                    <div class="alert alert-danger mt-3 mt-md-4 border-start border-5 border-danger shadow-sm">
                        <h5 class="fw-bold"><i class="fas fa-times-circle me-2"></i>Task Rejected</h5>
                        <p class="mb-1"><strong>Reason:</strong> {{ $transaction->rejection_reason }}</p>
                        <p class="mb-1">
                            <strong>Rejected By:</strong>
                            {{ $transaction->rejectedBy->name ?? 'Unknown Driver' }}
                        </p>
                        <small class="text-muted">
                            Rejected at:
                            {{ $transaction->rejected_at ? \Carbon\Carbon::parse($transaction->rejected_at)->format('d M Y H:i') : '-' }}
                        </small>
                    </div>
                @endif

                {{-- Status Tracker --}}
                <h5 class="fw-bold text-center mb-3">Status Tracker</h5>
                @php
                    $statusColor = match ($transaction->status) {
                        'Assigned' => 'var(--color-primary)',
                        'In Progress' => 'var(--color-secondary)',
                        'Arrived' => 'var(--color-info)',
                        'Completed' => 'var(--color-success)',
                        'Rejected' => 'var(--color-danger)',
                        default => '#6c757d',
                    };
                @endphp
                {{-- Gemini: Scaled progress bar height for mobile screens --}}
                {{-- <div class="progress" style="height: 30px; font-size: 1rem;"> - unnecessary - Gemini Lady --}}
                <div class="progress" style="height: 24px; font-size: 0.875rem;">
                    <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated"
                        role="progressbar" style="width: 0%; background-color: {{ $statusColor }};" aria-valuenow="0"
                        aria-valuemin="0" aria-valuemax="100">
                        {{ $transaction->status }}
                    </div>
                </div>

                {{-- Gemini: Adjusted container margins and flexible wrapping --}}
                {{-- <div class="text-center my-5 d-flex flex-wrap justify-content-center gap-3"> - unnecessary - Gemini Lady --}}
                <div class="text-center my-4 my-md-5 d-flex flex-column flex-sm-row flex-wrap justify-content-center gap-2 gap-sm-3">
                    {{-- Form for Driver to Claim an unassigned task --}}
                    @if(auth()->user()->role == 'driver' && $transaction->status == 'Pending' && $transaction->driver_id == null)
                        <div class="action-box w-100 mb-3 mx-auto" style="max-width: 600px;">
                            <h5 class="fw-bold fs-6 fs-md-5">This task is available!</h5>
                            <p class="text-muted small mb-2">Select a forklift to claim this task, or reject if incorrect.</p>
                            <form action="{{ route('transactions.claimTask', $transaction->transaction_id) }}" method="POST"
                                class="mt-2">
                                @csrf
                                <div class="form-group mb-3 text-start">
                                    <label for="forklift_id" class="form-label small">Select Forklift</label>
                                    {{-- Gemini: Mobile responsive select size --}}
                                    {{-- <select name="forklift_id" class="form-select form-select-lg" required> - unnecessary - Gemini Lady --}}
                                    <select name="forklift_id" class="form-select" required>
                                        <option value="" disabled selected>-- Your available forklifts --</option>
                                        @foreach ($forklifts as $forklift)
                                            <option value="{{ $forklift->forklift_id }}">{{ $forklift->model }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- Gemini: Stacking buttons vertically on small devices --}}
                                {{-- <div class="d-flex gap-2 justify-content-center"> - unnecessary - Gemini Lady --}}
                                <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                                    {{-- Gemini: Adapted btn sizes for touch screens --}}
                                    {{-- <button type="submit" class="btn btn-lg text-white" style="background-color: var(--color-primary);"> - unnecessary - Gemini Lady --}}
                                    <button type="submit" class="btn text-white w-100 w-sm-auto"
                                        style="background-color: var(--color-primary);">
                                        <i class="fas fa-hand-paper me-2"></i>Claim Task
                                    </button>
                                    {{-- <button type="button" class="btn btn-lg btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectTaskModal"> - unnecessary - Gemini Lady --}}
                                    <button type="button" class="btn btn-outline-danger w-100 w-sm-auto" data-bs-toggle="modal"
                                        data-bs-target="#rejectTaskModal">
                                        <i class="fas fa-times me-2"></i>Reject Task
                                    </button>
                                </div>
                            </form>
                        </div>
                    @elseif(auth()->user()->role == 'driver' && $transaction->status == 'Pending' && $transaction->driver_id != null)
                        <div class="alert alert-warning w-100 mb-0">This task has already been claimed by another driver.</div>
                    @endif

                    {{-- "Start Task" and "Reject" buttons for Assigned Driver --}}
                    @if(auth()->user()->role == 'driver' && $transaction->status == 'Assigned' && $transaction->driver && $transaction->driver->user_id == auth()->user()->id)
                        {{-- Gemini: Scaled down oversized btn-lg on mobile --}}
                        {{-- <button type="button" class="btn btn-lg text-white" style="background-color: var(--color-primary);" ...> - unnecessary - Gemini Lady --}}
                        <button type="button" class="btn text-white w-100 w-sm-auto" style="background-color: var(--color-primary);"
                            data-bs-toggle="modal" data-bs-target="#startTaskModal">
                            <i class="fas fa-play me-2"></i>Start Task
                        </button>
                        <button type="button" class="btn btn-outline-danger w-100 w-sm-auto" data-bs-toggle="modal"
                            data-bs-target="#rejectTaskModal">
                            <i class="fas fa-times me-2"></i>Reject Task
                        </button>
                    @endif

                    {{-- "Mark as Arrived" button --}}
                    @if(auth()->user()->role == 'driver' && $transaction->status == 'In Progress' && $transaction->driver && $transaction->driver->user_id == auth()->user()->id)
                        <button type="button" class="btn text-white w-100 w-sm-auto" style="background-color: var(--color-primary);"
                            data-bs-toggle="modal" data-bs-target="#arriveTaskModal">
                            <i class="fas fa-map-marker-alt me-2"></i>Mark as Arrived
                        </button>
                    @endif

                    {{-- "Confirm & Rate" button --}}
                    @if(auth()->user()->role == 'checker' && $transaction->status == 'Arrived')
                        <button type="button" class="btn text-white w-100 w-sm-auto" style="background-color: var(--color-primary);"
                            data-bs-toggle="modal" data-bs-target="#ratingModal">
                            <i class="fas fa-clipboard-check me-2"></i>Confirm & Rate Driver
                        </button>
                    @endif
                </div>

                {{-- Image Proofs Section --}}
                @if($transaction->start_task_image_path || $transaction->end_task_image_path)
                    <div class="mt-4 mt-md-5">
                        <h5 class="fw-bold">Image Proofs</h5>
                        <div class="row g-3 g-md-4">
                            @if($transaction->start_task_image_path)
                                <div class="col-12 col-md-6">
                                    <div class="image-preview-container">
                                        <h6 class="text-muted small">Start Task Image</h6>

                                        {{-- Edit Button for Start Image --}}
                                        @if(auth()->user()->role == 'driver' && in_array($transaction->status, ['Assigned', 'In Progress', 'Arrived']) && $transaction->driver->user_id == auth()->id())
                                            <button class="btn btn-sm btn-warning btn-edit-image" data-bs-toggle="modal"
                                                data-bs-target="#editStartImageModal">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </button>
                                        @endif

                                        <a href="{{ Storage::url($transaction->start_task_image_path) }}" target="_blank">
                                            <img src="{{ Storage::url($transaction->start_task_image_path) }}" class="image-preview"
                                                alt="Start Task Image">
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if($transaction->end_task_image_path)
                                <div class="col-12 col-md-6">
                                    <div class="image-preview-container">
                                        <h6 class="text-muted small">End Task (Arrived) Image</h6>

                                        {{-- Edit Button for End Image --}}
                                        @if(auth()->user()->role == 'driver' && $transaction->status == 'Arrived' && $transaction->driver->user_id == auth()->id())
                                            <button class="btn btn-sm btn-warning btn-edit-image" data-bs-toggle="modal"
                                                data-bs-target="#editEndImageModal">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </button>
                                        @endif

                                        <a href="{{ Storage::url($transaction->end_task_image_path) }}" target="_blank">
                                            <img src="{{ Storage::url($transaction->end_task_image_path) }}" class="image-preview"
                                                alt="End Task Image">
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Booking Items Table and Footer Buttons --}}
                <h5 class="fw-bold mt-4 mt-md-5">Booking Items</h5>
                <div class="table-responsive">
                    {{-- Gemini: Added small text class on table for small screens --}}
                    <table class="table table-hover align-middle small">
                        <thead class="table-light">
                            <tr>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->details as $detail)
                                <tr>
                                    <td class="fw-medium">{{ $detail->item_name }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ $detail->unit }}</td>
                                    <td>{{ $detail->description ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr class="my-3 my-md-4">
                {{-- Gemini: Stacked footer action buttons vertically on mobile --}}
                {{-- <div class="d-flex justify-content-between mt-4"> - unnecessary - Gemini Lady --}}
                <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-3 mt-md-4">
                    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary w-100 w-sm-auto">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>

                    @if(auth()->user()->role == 'admin')
                        <form action="{{ route('transactions.softDelete', $transaction->transaction_id) }}" method="POST"
                            onsubmit="return confirm('ยืนยันการลบ Transaction นี้?');" class="w-100 w-sm-auto">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash-alt me-2"></i>Delete Transaction
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Reject Task --}}
    <div class="modal fade" id="rejectTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('transactions.rejectTask', $transaction->transaction_id) }}" method="POST"
                class="modal-content">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-danger">ระบุเหตุผลการปฏิเสธงาน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label text-muted">ทำไมคุณถึงต้องการปฏิเสธงานนี้?</label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="4" class="form-control"
                            placeholder="ระบุเหตุผล..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Reject</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Start Task --}}
    <div class="modal fade" id="startTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Start Task Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('transactions.acceptTask', $transaction->transaction_id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body text-center">
                        <p class="small text-muted">Please attach a photo as proof of starting the task.</p>
                        <input type="file" name="start_task_image" id="start_task_image_input" class="file-input-hidden"
                            accept="image/*" capture="environment" required>
                        <label for="start_task_image_input" class="file-input-label">
                            <i class="fas fa-camera"></i> Attach Photo
                        </label>
                        <img id="startImagePreview" class="modal-image-preview mx-auto" src="#" alt="Image Preview" />
                        <div id="start-image-size-info" class="form-text mt-1"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn text-white" style="background-color: var(--color-primary);">Confirm
                            & Start</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Edit Start Image --}}
    <div class="modal fade" id="editStartImageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Start Task Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('transactions.updateStartImage', $transaction->transaction_id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body text-center">
                        <p class="small text-muted">Please take a new photo to replace the current one.</p>
                        <input type="file" name="start_task_image" id="edit_start_image_input" class="file-input-hidden"
                            accept="image/*" capture="environment" required>
                        <label for="edit_start_image_input" class="file-input-label">
                            <i class="fas fa-camera"></i> Take New Photo
                        </label>
                        <img id="editStartPreview" class="modal-image-preview mx-auto" src="#" alt="New Preview" />
                        <div id="edit-start-size-info" class="form-text mt-1"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Photo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Arrive Task --}}
    <div class="modal fade" id="arriveTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Arrival Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('transactions.arrive', $transaction->transaction_id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body text-center">
                        <p class="small text-muted">Please attach a photo as proof of arrival.</p>
                        <input type="file" name="end_task_image" id="end_task_image_input" class="file-input-hidden"
                            accept="image/*" capture="environment" required>
                        <label for="end_task_image_input" class="file-input-label">
                            <i class="fas fa-camera"></i> Attach Photo
                        </label>
                        <img id="endImagePreview" class="modal-image-preview mx-auto" src="#" alt="Image Preview" />
                        <div id="end-image-size-info" class="form-text mt-1"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn text-white" style="background-color: var(--color-primary);">Confirm
                            Arrival</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Edit End Image --}}
    <div class="modal fade" id="editEndImageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Arrival Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('transactions.updateEndImage', $transaction->transaction_id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body text-center">
                        <p class="small text-muted">Please take a new photo to replace the current one.</p>
                        <input type="file" name="end_task_image" id="edit_end_image_input" class="file-input-hidden"
                            accept="image/*" capture="environment" required>
                        <label for="edit_end_image_input" class="file-input-label">
                            <i class="fas fa-camera"></i> Take New Photo
                        </label>
                        <img id="editEndPreview" class="modal-image-preview mx-auto" src="#" alt="New Preview" />
                        <div id="edit-end-size-info" class="form-text mt-1"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Photo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Rating --}}
    <div class="modal fade" id="ratingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('transactions.complete', $transaction->transaction_id) }}" method="POST"
                class="modal-content">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Rate the Driver's Performance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="star-rating d-flex justify-content-center mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa-regular fa-star star mx-1" data-value="{{ $i }}"></i>
                        @endfor
                    </div>
                    <input type="hidden" name="driver_rating" id="driver_rating" required>
                    <div class="mb-3">
                        <label for="driver_comment" class="form-label">Comment (Optional):</label>
                        <textarea name="driver_comment" id="driver_comment" rows="3" class="form-control"
                            placeholder="Provide feedback..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn text-white" style="background-color: var(--color-primary);">Submit
                        Rating</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let statusProgress = { 'Pending': 5, 'Assigned': 25, 'In Progress': 50, 'Arrived': 75, 'Completed': 100, 'Rejected': 100 };
            let currentStatus = '{{ $transaction->status }}';
            let progressBar = document.getElementById('progress-bar');

            // Form Rating Validation
            const ratingForm = document.querySelector('#ratingModal form');
            if (ratingForm) {
                ratingForm.addEventListener('submit', function (e) {
                    const rating = parseInt(document.getElementById('driver_rating').value);
                    const comment = document.getElementById('driver_comment').value.trim();
                    if (rating <= 3 && comment.length === 0) {
                        e.preventDefault();
                        alert('กรุณาใส่เหตุผล (Remark) หากให้คะแนน 1-3 ดาว');
                    }
                });
            }

            // Progress Bar
            if (progressBar) {
                let targetWidth = statusProgress[currentStatus] || 0;
                progressBar.style.width = targetWidth + '%';
                progressBar.setAttribute('aria-valuenow', targetWidth);
            }

            // Image Processing Logic
            function setupImageUpload(inputId, previewId, sizeInfoId) {
                const imageInput = document.getElementById(inputId);
                const imagePreview = document.getElementById(previewId);
                const imageSizeInfo = document.getElementById(sizeInfoId);

                if (!imageInput) return;

                imageInput.addEventListener('change', function (event) {
                    const file = event.target.files[0];
                    if (!file || !file.type.startsWith('image/')) return;

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const img = new Image();
                        img.onload = function () {
                            const canvas = document.createElement('canvas');
                            const ctx = canvas.getContext('2d');
                            const MAX_WIDTH = 1280, MAX_HEIGHT = 1280;
                            let { width, height } = img;

                            if (width > height) {
                                if (width > MAX_WIDTH) { height *= MAX_WIDTH / width; width = MAX_WIDTH; }
                            } else {
                                if (height > MAX_HEIGHT) { width *= MAX_HEIGHT / height; height = MAX_HEIGHT; }
                            }
                            canvas.width = width;
                            canvas.height = height;
                            ctx.drawImage(img, 0, 0, width, height);

                            canvas.toBlob(function (blob) {
                                imageSizeInfo.textContent = `Processed size: ~${(blob.size / 1024 / 1024).toFixed(2)} MB`;
                                const newFile = new File([blob], file.name, { type: 'image/jpeg', lastModified: Date.now() });
                                const dataTransfer = new DataTransfer();
                                dataTransfer.items.add(newFile);
                                imageInput.files = dataTransfer.files;
                                imagePreview.src = URL.createObjectURL(blob);
                                imagePreview.style.display = 'block';
                            }, 'image/jpeg', 0.8);
                        };
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                });
            }

            // Initialize all upload handlers (Original and Edit)
            setupImageUpload('start_task_image_input', 'startImagePreview', 'start-image-size-info');
            setupImageUpload('end_task_image_input', 'endImagePreview', 'end-image-size-info');
            setupImageUpload('edit_start_image_input', 'editStartPreview', 'edit-start-size-info');
            setupImageUpload('edit_end_image_input', 'editEndPreview', 'edit-end-size-info');

            // Star Rating Logic
            const stars = document.querySelectorAll('.star');
            const ratingInput = document.getElementById('driver_rating');
            const starContainer = document.querySelector('.star-rating');
            if (starContainer) {
                stars.forEach(star => {
                    star.addEventListener('click', () => {
                        ratingInput.value = star.dataset.value;
                        updateStars(ratingInput.value);
                    });
                });
                starContainer.addEventListener('mouseover', e => {
                    if (e.target.classList.contains('star')) updateStars(e.target.dataset.value, true);
                });
                starContainer.addEventListener('mouseout', () => updateStars(ratingInput.value));

                function updateStars(rating) {
                    stars.forEach(star => {
                        star.classList.remove('fa-regular', 'fa-solid', 'selected');
                        if (star.dataset.value <= rating) star.classList.add('fa-solid', 'selected');
                        else star.classList.add('fa-regular');
                    });
                }
            }
        });
    </script>
@endsection