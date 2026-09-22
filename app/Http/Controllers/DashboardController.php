<?php

namespace App\Http\Controllers;

use App\Enums\PurchaseOrderStatus;
use App\Models\Product;
use App\Models\PurchaseOrder;

class DashboardController extends Controller
{
    public function index()
    {
        $totalActiveProducts = Product::count();
        $lowStockCount = Product::lowStock()->count();
        $totalExpenditure = PurchaseOrder::where('status', PurchaseOrderStatus::RECEIVED)->sum('total_amount');
        
        $latestOrders = PurchaseOrder::with('supplier')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('totalActiveProducts', 'lowStockCount', 'totalExpenditure', 'latestOrders'));
    }
}