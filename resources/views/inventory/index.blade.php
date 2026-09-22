@extends('layouts.app')

@section('content')
<h2 class="mb-4">Inventory Overview</h2>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>SKU</th>
                    <th>Product Name</th>
                    <th>Unit Cost</th>
                    <th>Current Stock</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>
                    <td>${{ number_format($product->unit_cost, 2) }}</td>
                    <td>{{ $product->stock_quantity }}</td>
                    <td>
                        @if($product->stock_quantity < $product->low_stock_threshold)
                            <span class="badge bg-danger">Low Stock</span>
                        @else
                            <span class="badge bg-success">In Stock</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $products->links() }}
</div>
@endsection