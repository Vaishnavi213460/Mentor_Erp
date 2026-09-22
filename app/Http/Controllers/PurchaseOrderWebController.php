<?php

namespace App\Http\Controllers;

use App\Enums\PurchaseOrderStatus;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Services\PurchaseOrderService;
use Illuminate\Http\Request;

class PurchaseOrderWebController extends Controller
{
    public function __construct(protected PurchaseOrderService $poService) {}

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchase_orders.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0.01',
        ]);

        $po = $this->poService->createPurchaseOrder($validated);

        return redirect()->route('purchase-orders.show', $po->id)
            ->with('success', 'Purchase Order created successfully as DRAFT.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'items.product']);
        return view('purchase_orders.show', compact('purchaseOrder'));
    }

    public function updateStatus(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'status' => 'required|string|in:APPROVED,RECEIVED,CANCELLED',
        ]);

        try {
            $targetStatus = PurchaseOrderStatus::from($request->status);
            $this->poService->updateStatus($purchaseOrder, $targetStatus);

            return back()->with('success', "Order status successfully updated to {$request->status}.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}