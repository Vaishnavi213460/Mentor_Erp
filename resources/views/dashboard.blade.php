@extends('layouts.app')

@section('content')
<h2 class="mb-4">Executive Dashboard</h2>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Total Active Products</h6>
                <h3 class="fw-bold">{{ $totalActiveProducts }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-dark shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Low Stock Alerts</h6>
                <h3 class="fw-bold">{{ $lowStockCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Total Expenditure</h6>
                <h3 class="fw-bold">${{ number_format($totalExpenditure, 2) }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Latest Purchase Orders</h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>PO Number</th>
                    <th>Supplier</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($latestOrders as $po)
                <tr>
                    <td>{{ $po->po_number }}</td>
                    <td>{{ $po->supplier->name }}</td>
                    <td>${{ number_format($po->total_amount, 2) }}</td>
                    <td>
                        <span class="badge bg-{{ match($po->status->value) {
                            'DRAFT' => 'secondary',
                            'APPROVED' => 'primary',
                            'RECEIVED' => 'success',
                            'CANCELLED' => 'danger'
                        } }}">
                            {{ $po->status->value }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('purchase-orders.show', $po->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection