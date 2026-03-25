<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Super Admin ───────────────────────────────────────────────────────
        User::create([
            'store_id' => null,
            'name'     => 'Super Administrator',
            'email'    => 'superadmin@pos-saas.com',
            'password' => 'password123',
            'role'     => 'super_admin',
            'is_active'=> true,
        ]);

        // ── Demo Store ────────────────────────────────────────────────────────
        $store = Store::create([
            'name'      => 'Toko Demo Elektronik',
            'slug'      => 'toko-demo-elektronik',
            'address'   => 'Jl. Sudirman No. 123, Jakarta Pusat',
            'phone'     => '021-12345678',
            'currency'  => 'IDR',
            'is_active' => true,
        ]);

        // Admin toko
        $admin = User::create([
            'store_id' => $store->id,
            'name'     => 'Admin Toko',
            'email'    => 'admin@toko-demo.com',
            'password' => 'password123',
            'role'     => 'admin',
            'is_active'=> true,
        ]);

        // Kasir
        User::create([
            'store_id' => $store->id,
            'name'     => 'Kasir 1',
            'email'    => 'kasir@toko-demo.com',
            'password' => 'password123',
            'role'     => 'cashier',
            'is_active'=> true,
        ]);

        // ── Categories ────────────────────────────────────────────────────────
        // Bypass global scope — we're seeding as Super Admin (no auth)
        $cats = [];
        $catData = [
            ['name' => 'Elektronik',   'color' => '#6366f1'],
            ['name' => 'Aksesoris',    'color' => '#8b5cf6'],
            ['name' => 'Audio',        'color' => '#ec4899'],
            ['name' => 'Komputer',     'color' => '#06b6d4'],
            ['name' => 'Minuman',      'color' => '#10b981'],
            ['name' => 'Makanan',      'color' => '#f59e0b'],
        ];

        foreach ($catData as $data) {
            $cats[] = Category::withoutGlobalScopes()->create([
                'store_id'   => $store->id,
                'name'       => $data['name'],
                'color'      => $data['color'],
                'is_active'  => true,
            ]);
        }

        // ── Products ─────────────────────────────────────────────────────────
        $products = [
            ['Earphone Bluetooth Pro',    'SKU-001', 149000, 80000,  $cats[2]->id, 15],
            ['Charger USB-C 65W',         'SKU-002', 89000,  45000,  $cats[0]->id, 30],
            ['Mouse Gaming RGB',          'SKU-003', 299000, 180000, $cats[3]->id, 8],
            ['Keyboard Mechanical TKL',   'SKU-004', 599000, 350000, $cats[3]->id, 5],
            ['Power Bank 20000mAh',       'SKU-005', 249000, 140000, $cats[0]->id, 12],
            ['Pop Socket Holder',         'SKU-006', 29000,  12000,  $cats[1]->id, 50],
            ['Kabel Data 2m Type-C',      'SKU-007', 39000,  15000,  $cats[1]->id, 40],
            ['Speaker Bluetooth Mini',    'SKU-008', 189000, 110000, $cats[2]->id, 20],
            ['Screen Protector Tempered', 'SKU-009', 49000,  20000,  $cats[1]->id, 35],
            ['Flash Drive 64GB USB 3.0',  'SKU-010', 79000,  40000,  $cats[3]->id, 25],
            ['Air Mineral 600ml',         'SKU-011', 5000,   2500,   $cats[4]->id, 100],
            ['Kopi Sachet 3in1',          'SKU-012', 3000,   1500,   $cats[5]->id, 200],
        ];

        foreach ($products as [$name, $sku, $price, $cost, $catId, $stock]) {
            Product::withoutGlobalScopes()->create([
                'store_id'        => $store->id,
                'category_id'     => $catId,
                'name'            => $name,
                'sku'             => $sku,
                'price'           => $price,
                'cost_price'      => $cost,
                'stock'           => $stock,
                'low_stock_alert' => 5,
                'unit'            => 'pcs',
                'is_active'       => true,
            ]);
        }

        $this->command->info('✅ Super Admin: superadmin@pos-saas.com / password123');
        $this->command->info('✅ Admin Toko : admin@toko-demo.com / password123');
        $this->command->info('✅ Kasir      : kasir@toko-demo.com / password123');
    }
}
