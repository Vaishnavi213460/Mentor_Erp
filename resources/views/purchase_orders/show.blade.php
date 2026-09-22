@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Purchase Order: {{ $purchaseOrder->po_number }}</h2>
    <div>
        @if($purchaseOrder->status->value === 'DRAFT')
            <form action="{{ route('purchase-orders.status', $purchaseOrder->id) }}" method="POST" class="d-inline">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="APPROVED">
                <button class="btn btn-primary">Approve Order</button>
            </form>
            <form action="{{ route('purchase-orders.status', $purchaseOrder->id) }}" method="POST" class="d-inline">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="CANCELLED">
                <button class="btn btn-danger">Cancel Order</button>
            </form>
        @elseif($purchaseOrder->status->value === 'APPROVED')
            <form action="{{ route('purchase-orders.status', $purchaseOrder->id) }}" method="POST" class="d-inline">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="RECEIVED">
                <button class="btn btn-success">Mark as Received</button>
            </form>
            <form action="{{ route('purchase-orders.status', $purchaseOrder->id) }}" method="POST" class="d-inline">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="CANCELLED">
                <button class="btn btn-danger">Cancel Order</button>
            </form>
        @endif
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Supplier Info</h5>
                <p class="mb-1"><strong>Name:</strong> {{ $purchaseOrder->supplier->name }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $purchaseOrder->supplier->email }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Order Summary</h5>
                <p class="mb-1"><strong>Status:</strong> <span class="badge bg-secondary">{{ $purchaseOrder->status->value }}</span></p>
                <p class="mb-1"><strong>Total Amount:</strong> ${{ number_format($purchaseOrder->total_amount, 2) }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">Line Items</h5></div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchaseOrder->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->product->sku }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->unit_price, 2) }}</td>
                    <td>${{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection