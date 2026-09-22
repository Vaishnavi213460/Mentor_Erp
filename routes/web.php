<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryWebController;
use App\Http\Controllers\PurchaseOrderWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/dashboard'));

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/inventory', [InventoryWebController::class, 'index'])->name('inventory.index');

Route::get('/purchase-orders/create', [PurchaseOrderWebController::class, 'create'])->name('purchase-orders.create');
Route::post('/purchase-orders', [PurchaseOrderWebController::class, 'store'])->name('purchase-orders.store');
Route::get('/purchase-orders/{purchaseOrder}', [PurchaseOrderWebController::class, 'show'])->name('purchase-orders.show');
Route::patch('/purchase-orders/{purchaseOrder}/status', [PurchaseOrderWebController::class, 'updateStatus'])->name('purchase-orders.status');