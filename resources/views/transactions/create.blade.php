@extends('layouts.app')

@section('content')
{{-- Ensure FontAwesome is loaded for icons --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --color-primary: #E60012;
        --color-secondary: #004B8D;
        --color-info: #0086BF;
        --color-success: #198754;
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
    .detail-item-card {
        border: 1px solid var(--color-border);
        border-radius: var(--border-radius-md);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        position: relative;
        background-color: #fdfdfd;
    }
    .detail-item-card .remove-detail {
        position: absolute;
        top: 1rem;
        right: 1rem;
    }
    .item-custom-input { 
        display: none;
    }
    .unit-custom-input {
        display: none;
    }
</style>

{{-- Define the standard units and items in a PHP variable for reuse --}}
@php
    $standardUnits = ['Pallet','Carton'];
    $standardItems = [
        'งานส่งขาย – MTL',
        'การเดินทางไปยัง BKK HUB',
        'การขนส่งระหว่างโรงงาน Spare part ,ขนย้ายวัถุดิบ',
        'การขนส่งระหว่างโรงงาน งานขายระหว่างโรงงาน',
        'การขนถ่ายจากรถบรรทุก',
        'การบรรทุกสินค้าขึ้นรถบรรทุก',
        'บริการพิเศษ'
    ];
@endphp

<div class="container-fluid py-4 px-lg-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card card-main-content">
                <div class="card-body p-4 p-lg-5">
                    {{-- Header --}}
                    <div class="text-center mb-5">
                        <h1 class="fw-bolder" style="color: var(--color-secondary);">
                            <i class="fas fa-plus-circle me-2"></i>Create New Booking
                        </h1>
                        <p class="fs-5 text-muted">Fill in the details to create a new transaction</p>
                    </div>

                    <form action="{{ route('transactions.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            {{-- Warehouse From --}}
                            <div class="col-md-6 mb-3">
                                <label for="warehouse_from" class="form-label fw-bold">From Warehouse</label>
                                <select name="warehouse_from" class="form-select" required>
                                    <option value="" disabled selected>-- Select a Warehouse --</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Warehouse To --}}
                            <div class="col-md-6 mb-3">
                                <label for="warehouse_to" class="form-label fw-bold">To Warehouse</label>
                                <select name="warehouse_to" class="form-select" required>
                                    <option value="" disabled selected>-- Select a Warehouse --</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- Transaction Details Section --}}
                        <h4 class="fw-bold mb-3">Booking Items</h4>
                        <div id="transaction-details">
                            {{-- First Item (Visible) --}}
                            <div class="detail-item-card" id="detail-0">
                                <div class="row g-3">
                                    {{-- Item Name Dropdown with Custom Input --}}
                                    <div class="col-12">
                                        <label for="item_select_0" class="form-label">Item Name</label>
                                        <select class="form-select item-select" data-index="0" required>
                                            <option value="" disabled selected>-- Select an Item --</option>
                                            @foreach($standardItems as $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                            <option value="_other">อื่น ๆ...</option>
                                        </select>
                                        <input type="text" id="item_custom_0" class="form-control mt-2 item-custom-input" placeholder="Enter custom item name">
                                        <input type="hidden" name="details[0][item_name]" id="item_final_0">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="details[0][quantity]" class="form-label">Quantity</label>
                                        <input type="number" name="details[0][quantity]" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="unit_select_0" class="form-label">Unit</label>
                                        <select class="form-select unit-select" data-index="0" required>
                                            <option value="" disabled selected>-- Select a Unit --</option>
                                            @foreach($standardUnits as $unit)
                                                <option value="{{ $unit }}">{{ $unit }}</option>
                                            @endforeach
                                            <option value="_other">Other...</option>
                                        </select>
                                        <input type="text" id="unit_custom_0" class="form-control mt-2 unit-custom-input" placeholder="Enter custom unit">
                                        <input type="hidden" name="details[0][unit]" id="unit_final_0">
                                    </div>
                                    <div class="col-12">
                                        <label for="details[0][description]" class="form-label">Remark</label>
                                        <textarea name="details[0][description]" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Add Detail Button --}}
                        <div class="d-grid gap-2 mt-4">
                            <button type="button" class="btn btn-outline-success" id="add-detail">
                                <i class="fas fa-plus me-2"></i>Add Another Item
                            </button>
                        </div>

                        {{-- Submit Button --}}
                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-lg text-white" style="background-color: var(--color-primary);">Create Booking</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let detailCount = 1;
    const detailsContainer = document.getElementById('transaction-details');

    // ** Item Name Handlers **
    function handleItemChange(selectElement) {
        const selectedValue = selectElement.value;
        const index = selectElement.dataset.index;
        const customInput = document.getElementById(`item_custom_${index}`);
        const finalInput = document.getElementById(`item_final_${index}`);

        if (!customInput || !finalInput) return;

        if (selectedValue === '_other') {
            customInput.style.display = 'block';
            customInput.setAttribute('required', 'required');
            customInput.focus();
            finalInput.value = customInput.value;
        } else {
            customInput.style.display = 'none';
            customInput.removeAttribute('required');
            customInput.value = '';
            finalInput.value = selectedValue;
        }
    }

    function handleCustomItemInput(customInputElement) {
        const index = customInputElement.id.split('_').pop();
        const finalInput = document.getElementById(`item_final_${index}`);
        if(finalInput) {
            finalInput.value = customInputElement.value;
        }
    }

    // ** Unit Handlers **
    function handleUnitChange(selectElement) {
        const selectedValue = selectElement.value;
        const index = selectElement.dataset.index;
        const customInput = document.getElementById(`unit_custom_${index}`);
        const finalInput = document.getElementById(`unit_final_${index}`);

        if (!customInput || !finalInput) return;

        if (selectedValue === '_other') {
            customInput.style.display = 'block';
            customInput.setAttribute('required', 'required');
            customInput.focus();
            finalInput.value = customInput.value;
        } else {
            customInput.style.display = 'none';
            customInput.removeAttribute('required');
            customInput.value = '';
            finalInput.value = selectedValue;
        }
    }

    function handleCustomUnitInput(customInputElement) {
        const index = customInputElement.id.split('_').pop();
        const finalInput = document.getElementById(`unit_final_${index}`);
        if(finalInput) {
            finalInput.value = customInputElement.value;
        }
    }

    detailsContainer.addEventListener('change', function(e) {
        if (e.target.classList.contains('unit-select')) {
            handleUnitChange(e.target);
        } else if (e.target.classList.contains('item-select')) { 
            handleItemChange(e.target);
        }
    });

    detailsContainer.addEventListener('input', function(e) {
        if (e.target.classList.contains('unit-custom-input')) {
            handleCustomUnitInput(e.target);
        } else if (e.target.classList.contains('item-custom-input')) {
            handleCustomItemInput(e.target);
        }
    });
    
    const initialItemSelect = document.querySelector('.item-select');
    if (initialItemSelect) {
        handleItemChange(initialItemSelect);
    }
    const initialUnitSelect = document.querySelector('.unit-select');
    if (initialUnitSelect) {
        handleUnitChange(initialUnitSelect);
    }

    document.getElementById('add-detail').addEventListener('click', function () {

        const newDetailContainer = document.createElement('div');
        newDetailContainer.className = 'detail-item-card';
        newDetailContainer.id = `detail-${detailCount}`;


        const standardItems = @json($standardItems);
        let itemOptionsHtml = '';
        standardItems.forEach(item => {
            itemOptionsHtml += `<option value="${item.replace(/"/g, '&quot;')}">${item}</option>`;
        });


        let newDetailHTML = `
            <button type="button" class="btn-close remove-detail" aria-label="Close" data-id="${detailCount}"></button>
            <div class="row g-3">
                <div class="col-12">    
                    <label class="form-label">Item Name</label>
                    <select class="form-select item-select" data-index="${detailCount}" required>
                        <option value="" disabled selected>-- Select an Item --</option>
                        ${itemOptionsHtml}
                        <option value="_other">8. อื่น ๆ...</option>
                    </select>
                    <input type="text" id="item_custom_${detailCount}" class="form-control mt-2 item-custom-input" placeholder="Enter custom item name">
                    <input type="hidden" name="details[${detailCount}][item_name]" id="item_final_${detailCount}">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="details[${detailCount}][quantity]" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Unit</label>
                    <select class="form-select unit-select" data-index="${detailCount}" required>
                        <option value="" disabled selected>-- Select a Unit --</option>
                        @foreach($standardUnits as $unit)
                            <option value="{{ $unit }}">{{ $unit }}</option>
                        @endforeach
                        <option value="_other">Other...</option>
                    </select>
                    <input type="text" id="unit_custom_${detailCount}" class="form-control mt-2 unit-custom-input" placeholder="Enter custom unit">
                    <input type="hidden" name="details[${detailCount}][unit]" id="unit_final_${detailCount}">
                </div>
                <div class="col-12">
                    <label class="form-label">Remark</label>
                    <textarea name="details[${detailCount}][description]" class="form-control" rows="2"></textarea>
                </div>
            </div>
        `;
        
        newDetailContainer.innerHTML = newDetailHTML;
        detailsContainer.appendChild(newDetailContainer);

        handleItemChange(newDetailContainer.querySelector('.item-select'));
        handleUnitChange(newDetailContainer.querySelector('.unit-select'));

        detailCount++;
    });

    detailsContainer.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-detail')) {
            let detailId = e.target.dataset.id;
            if (detailId !== '0') {
                document.getElementById(`detail-${detailId}`).remove();
            }
        }
    });
});
</script>
@endsection