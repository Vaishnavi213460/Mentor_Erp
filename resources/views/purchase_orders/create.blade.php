@extends('layouts.app')

@section('content')
<h2 class="mb-4">Create Purchase Order</h2>

<form action="/purchase-orders" method="POST" id="poForm">
    @csrf
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="mb-3">
                <label for="supplier_id" class="form-label">Supplier</label>
                <select name="supplier_id" id="supplier_id" class="form-select" required>
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Order Items</h5>
            <button type="button" class="btn btn-sm btn-success" id="addItemRow">+ Add Product</button>
        </div>
        <div class="card-body">
            <table class="table" id="itemsTable">
                <thead>
                    <tr>
                        <th style="width: 40%;">Product</th>
                        <th style="width: 20%;">Quantity</th>
                        <th style="width: 20%;">Unit Price ($)</th>
                        <th style="width: 15%;">Subtotal ($)</th>
                        <th style="width: 5%;"></th>
                    </tr>
                </thead>
                <tbody id="itemRows">
                    <tr class="item-row">
                        <td>
                            <select name="items[0][product_id]" class="form-select product-select" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->unit_cost }}">
                                        {{ $product->name }} (SKU: {{$product->sku }})
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="items[0][quantity]" class="form-control qty-input" min="1" value="1" required>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="items[0][unit_price]" class="form-control price-input" min="0.01" required>
                        </td>
                        <td>
                            <input type="text" class="form-control subtotal-input" readonly value="0.00">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
                <h4>Grand Total: $<span id="grandTotal">0.00</span></h4>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-lg">Submit Purchase Order</button>
</form>

@push('scripts')
<script>
let rowIndex = 1;

function updateCalculations() {
    let grandTotal = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const subtotal = qty * price;
        
        row.querySelector('.subtotal-input').value = subtotal.toFixed(2);
        grandTotal += subtotal;
    });
    document.getElementById('grandTotal').innerText = grandTotal.toFixed(2);
}

document.getElementById('itemsTable').addEventListener('change', function(e) {
    if (e.target.classList.contains('product-select')) {
        const selected = e.target.options[e.target.selectedIndex];
        const price = selected.getAttribute('data-price');
        if (price) {
            const row = e.target.closest('tr');
            row.querySelector('.price-input').value = parseFloat(price).toFixed(2);
            updateCalculations();
        }
    }
});

document.getElementById('itemsTable').addEventListener('input', function(e) {
    if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input')) {
        updateCalculations();
    }
});

document.getElementById('addItemRow').addEventListener('click', function() {
    const template = document.querySelector('.item-row').cloneNode(true);
    template.querySelectorAll('input').forEach(i => i.value = '');
    template.querySelector('.qty-input').value = 1;
    template.querySelector('.subtotal-input').value = '0.00';
    
    template.querySelector('.product-select').name = `items[${rowIndex}][product_id]`;
    template.querySelector('.qty-input').name = `items[${rowIndex}][quantity]`;
    template.querySelector('.price-input').name = `items[${rowIndex}][unit_price]`;
    
    document.getElementById('itemRows').appendChild(template);
    rowIndex++;
});

document.getElementById('itemsTable').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row')) {
        if (document.querySelectorAll('.item-row').length > 1) {
            e.target.closest('tr').remove();
            updateCalculations();
        }
    }
});
</script>
@endpush
@endsection