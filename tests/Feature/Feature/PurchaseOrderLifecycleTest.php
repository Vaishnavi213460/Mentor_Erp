<?php

namespace Tests\Feature;

use App\Enums\PurchaseOrderStatus;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Services\PurchaseOrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseOrderLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_increments_when_order_is_received(): void
    {
        $supplier = Supplier::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 10]);

        $poService = app(PurchaseOrderService::class);
        $po = $poService->createPurchaseOrder([
            'supplier_id' => $supplier->id,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 5, 'unit_price' => 20.00]
            ]
        ]);

        $poService->updateStatus($po, PurchaseOrderStatus::APPROVED);
        $poService->updateStatus($po, PurchaseOrderStatus::RECEIVED);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 15,
        ]);
    }

    public function test_modifying_immutable_order_fails(): void
    {
        $supplier = Supplier::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 10]);

        $poService = app(PurchaseOrderService::class);
        $po = $poService->createPurchaseOrder([
            'supplier_id' => $supplier->id,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 5, 'unit_price' => 20.00]
            ]
        ]);

        $poService->updateStatus($po, PurchaseOrderStatus::APPROVED);
        $poService->updateStatus($po, PurchaseOrderStatus::RECEIVED);

        $this->expectException(\App\Exceptions\InvalidStatusTransitionException::class);
        $poService->updateStatus($po, PurchaseOrderStatus::CANCELLED);
    }
}