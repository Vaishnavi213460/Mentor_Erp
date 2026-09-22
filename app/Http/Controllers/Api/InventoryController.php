<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;

class InventoryController extends Controller
{
    public function index()
    {
        return response()->json(Product::paginate(15));
    }

    public function lowStock()
    {
        return response()->json(Product::lowStock()->get());
    }
}