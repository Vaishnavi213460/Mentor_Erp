<?php

namespace App\Http\Controllers;

use App\Models\Product;

class InventoryWebController extends Controller
{
    public function index()
    {
        $products = Product::paginate(15);
        return view('inventory.index', compact('products'));
    }
}