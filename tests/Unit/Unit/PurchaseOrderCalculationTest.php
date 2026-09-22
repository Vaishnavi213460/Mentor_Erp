<?php

namespace Tests\Unit;

use App\Models\PurchaseOrderItem;
use Tests\TestCase;

class PurchaseOrderCalculationTest extends TestCase
{
    public function test_line_item_subtotal_calculation(): void
    {
        $item = new PurchaseOrderItem([
            'quantity' => 5,
            'unit_price' => 19.99,
        ]);

        $subtotal = round($item->quantity * $item->unit_price, 2);

        $this->assertEquals(99.95, $subtotal);
    }
}