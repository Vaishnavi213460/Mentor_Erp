<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin Test User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@erp.com',
            'password' => Hash::make('password123'),
        ]);

        // 2. At least 3 Suppliers
        Supplier::create([
            'name' => 'Acme Industrial',
            'email' => 'contact@acme.com',
            'phone' => '1234567890',
            'address' => '123 Tech Park, Suite 100',
        ]);

        Supplier::create([
            'name' => 'Global Logistics',
            'email' => 'sales@globallogistics.com',
            'phone' => '9876543210',
            'address' => '456 Freight Ave, Cargo Hub',
        ]);

        Supplier::create([
            'name' => 'Apex Electronics',
            'email' => 'support@apexelectronics.com',
            'phone' => '5551234567',
            'address' => '789 Silicon Way, Bldg 4',
        ]);

        Supplier::create([
            'name' => 'Jain Industries',
            'email' => 'jain@industries.com',
            'phone' => '1251234567',
            'address' => '123 Street, Washington',
        ]);

        // 3. 10 Inventory Items (Products)
        $products = [
            ['sku' => 'SKU-001', 'name' => 'Wireless Barcode Scanner', 'unit_cost' => 150.00, 'stock_quantity' => 5, 'low_stock_threshold' => 10],
            ['sku' => 'SKU-002', 'name' => 'Thermal Label Printer', 'unit_cost' => 80.00, 'stock_quantity' => 20, 'low_stock_threshold' => 10],
            ['sku' => 'SKU-003', 'name' => 'Heavy Duty Pallet Jack', 'unit_cost' => 350.00, 'stock_quantity' => 3, 'low_stock_threshold' => 5],
            ['sku' => 'SKU-004', 'name' => 'Industrial Packaging Tape (Box)', 'unit_cost' => 25.50, 'stock_quantity' => 50, 'low_stock_threshold' => 15],
            ['sku' => 'SKU-005', 'name' => 'Handheld RFID Terminal', 'unit_cost' => 450.00, 'stock_quantity' => 8, 'low_stock_threshold' => 10],
            ['sku' => 'SKU-006', 'name' => 'Digital Weighing Scale', 'unit_cost' => 120.00, 'stock_quantity' => 12, 'low_stock_threshold' => 5],
            ['sku' => 'SKU-007', 'name' => 'Bubble Wrap Roll 100m', 'unit_cost' => 18.00, 'stock_quantity' => 4, 'low_stock_threshold' => 10],
            ['sku' => 'SKU-008', 'name' => 'Warehouse Safety Vests (Pack of 10)', 'unit_cost' => 40.00, 'stock_quantity' => 25, 'low_stock_threshold' => 8],
            ['sku' => 'SKU-009', 'name' => 'Cardboard Shipping Boxes (50x50x50)', 'unit_cost' => 2.50, 'stock_quantity' => 200, 'low_stock_threshold' => 50],
            ['sku' => 'SKU-010', 'name' => 'Ergonomic Packing Station Desk', 'unit_cost' => 299.99, 'stock_quantity' => 2, 'low_stock_threshold' => 3],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}