<?php

namespace App\Services;

use App\Enums\PurchaseOrderStatus;
use App\Exceptions\InvalidStatusTransitionException;
use App\Models\Product;
use App\Models\PurchaseOrder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseOrderService
{
    /**
     * Create a new Purchase Order with items.
     */
    public function createPurchaseOrder(array $data): PurchaseOrder
    {
        return DB::transaction(function () use ($data) {
            $poNumber = 'PO-' . strtoupper(Str::random(8));

            $purchaseOrder = PurchaseOrder::create([
                'po_number' => $poNumber,
                'supplier_id' => $data['supplier_id'],
                'status' => PurchaseOrderStatus::DRAFT,
                'total_amount' => 0.00,
            ]);

            $totalAmount = 0;

            foreach ($data['items'] as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $totalAmount += $subtotal;

                $purchaseOrder->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal,
                ]);
            }

            $purchaseOrder->update(['total_amount' => $totalAmount]);

            return $purchaseOrder->load('items.product', 'supplier');
        });
    }

    /**
     * Update the status of a Purchase Order and handle stock mutations atomically.
     */
    public function updateStatus(PurchaseOrder $purchaseOrder, PurchaseOrderStatus $targetStatus): PurchaseOrder
    {
        if ($purchaseOrder->status->isImmutable()) {
            throw new InvalidStatusTransitionException("Cannot modify order in {$purchaseOrder->status->value} status.");
        }

        if (!$purchaseOrder->status->canTransitionTo($targetStatus)) {
            throw new InvalidStatusTransitionException("Transition from {$purchaseOrder->status->value} to {$targetStatus->value} is not allowed.");
        }

        return DB::transaction(function () use ($purchaseOrder, $targetStatus) {
            if ($targetStatus === PurchaseOrderStatus::RECEIVED) {
                foreach ($purchaseOrder->items as $item) {
                    // Lock the row for update to eliminate race conditions
                    $product = Product::where('id', $item->product_id)->lockForUpdate()->firstOrFail();
                    $product->increment('stock_quantity', $item->quantity);
                }
            }

            $purchaseOrder->update(['status' => $targetStatus]);

            return $purchaseOrder->fresh(['items.product', 'supplier']);
        });
    }
}