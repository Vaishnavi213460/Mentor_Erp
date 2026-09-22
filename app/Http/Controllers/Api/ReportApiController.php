<?php

namespace App\Http\Controllers\Api;

use App\Enums\PurchaseOrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class ReportApiController extends Controller
{
    public function supplierSpend()
    {
        $report = Supplier::query()->leftJoin('purchase_orders', function ($join) {
                $join->on('suppliers.id', '=', 'purchase_orders.supplier_id')
                    ->where('purchase_orders.status', '=', PurchaseOrderStatus::RECEIVED->value);
            })->select('suppliers.id','suppliers.name',
                DB::raw('COALESCE(SUM(purchase_orders.total_amount), 0.00) as total_spend')
            )
            ->groupBy('suppliers.id', 'suppliers.name')
            ->orderByDesc('total_spend')
            ->get();

        return response()->json(['data' => $report]);
    }
}