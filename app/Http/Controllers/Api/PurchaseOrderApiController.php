<?php

namespace App\Http\Controllers\Api;

use App\Enums\PurchaseOrderStatus;
use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Services\PurchaseOrderService;
use Illuminate\Http\Request;

class PurchaseOrderApiController extends Controller
{
    public function __construct(protected PurchaseOrderService $poService) {}

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

        return response()->json([
            'message' => 'Purchase Order created successfully.',
            'data' => $po,
        ], 201);
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|string|in:APPROVED,RECEIVED,CANCELLED',
        ]);

        $purchaseOrder = PurchaseOrder::findOrFail($id);

        try {
            $targetStatus = PurchaseOrderStatus::from($request->status);
            $updatedPo = $this->poService->updateStatus($purchaseOrder, $targetStatus);

            return response()->json([
                'message' => 'Purchase Order status updated successfully.',
                'data' => $updatedPo,
            ]);
        } catch (\App\Exceptions\InvalidStatusTransitionException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}