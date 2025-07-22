<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\PaymentMethod;
use App\Models\CashCategory;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create roles and permissions
        $adminRole = Role::create(['name' => 'admin']);
        $cashierRole = Role::create(['name' => 'cashier']);

        // Create permissions
        $permissions = [
            'view_transactions',
            'manage_products',
            'manage_categories',
            'manage_payment_methods',
            'manage_cash_transactions',
            'manage_cash_categories',
            'manage_inventory',
            'manage_users',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Give all permissions to admin
        $adminRole->givePermissionTo($permissions);

        // Give limited permissions to cashier
        $cashierRole->givePermissionTo([
            'view_transactions',
            'manage_inventory',
        ]);

        // Assign admin role to admin user
        $admin->assignRole($adminRole);

        // Create a cashier user
        $cashier = User::factory()->create([
            'name' => 'Kasir',
            'email' => 'kasir@example.com',
            'password' => Hash::make('password'),
        ]);

        // Assign cashier role to cashier user
        $cashier->assignRole($cashierRole);

        // Create payment methods
        $paymentMethods = [
            [
                'name' => 'Tunai',
                'code' => 'cash',
                'description' => 'Pembayaran tunai',
                'config' => [
                    'requires_verification' => false,
                    'auto_confirm' => true,
                ],
                'is_active' => true,
            ],
            [
                'name' => 'QRIS',
                'code' => 'qris',
                'description' => 'Pembayaran menggunakan QRIS',
                'config' => [
                    'requires_verification' => true,
                    'auto_confirm' => false,
                    'merchant_id' => '123456789',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::updateOrCreate(
                ['code' => $method['code']],
                $method
            );
        }

        // Create additional payment methods
        foreach (['Transfer Bank', 'E-Wallet'] as $name) {
            PaymentMethod::factory()->create([
                'name' => $name,
                'code' => strtolower(str_replace(' ', '-', $name)) . '-' . rand(1000, 9999),
            ]);
        }

        // Create system cash categories
        $cashCategories = [
            [
                'name' => 'Penjualan',
                'description' => 'Pemasukan dari penjualan produk',
                'type' => 'income',
                'is_system' => true,
            ],
            [
                'name' => 'Biaya Operasional',
                'description' => 'Pengeluaran untuk biaya operasional',
                'type' => 'expense',
                'is_system' => true,
            ],
            [
                'name' => 'Pendapatan Lain',
                'description' => 'Pemasukan dari sumber lain',
                'type' => 'income',
                'is_system' => true,
            ],
        ];

        foreach ($cashCategories as $category) {
            CashCategory::updateOrCreate(
                ['name' => $category['name']],
                array_merge($category, ['is_active' => true])
            );
        }

        // Create regular cash categories
        CashCategory::factory()->income()->count(3)->create();
        CashCategory::factory()->expense()->count(3)->create();

        // Create product categories
        $categories = [
            'Minuman',
            'Makanan',
            'Snack',
            'Topping',
            'Lainnya',
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['name' => $name],
                ['is_active' => true]
            );
        }

        // Create products with variants
        Category::all()->each(function ($category) {
            $products = Product::factory()
                ->count(5)
                ->create([
                    'category_id' => $category->id,
                ]);

            // Create variants for each product
            $products->each(function ($product) {
                // Size variant
                ProductVariant::factory()
                    ->size()
                    ->create(['product_id' => $product->id]);

                // Sugar level variant
                ProductVariant::factory()
                    ->sugarLevel()
                    ->create(['product_id' => $product->id]);

                // Topping variant
                ProductVariant::factory()
                    ->topping()
                    ->create(['product_id' => $product->id]);
            });
        });

        // Create some inactive products
        Product::factory()
            ->inactive()
            ->count(3)
            ->create();

        // Create some low stock products
        Product::factory()
            ->lowStock()
            ->count(3)
            ->create();

        // Create some out of stock products
        Product::factory()
            ->outOfStock()
            ->count(3)
            ->create();
    }
}
