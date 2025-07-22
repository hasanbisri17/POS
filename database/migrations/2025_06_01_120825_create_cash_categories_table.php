<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['income', 'expense']);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_system')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        // Insert default cash categories
        DB::table('cash_categories')->insert([
            [
                'name' => 'Penjualan',
                'description' => 'Pemasukan dari penjualan produk',
                'type' => 'income',
                'is_active' => true,
                'is_system' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Modal',
                'description' => 'Pemasukan dari modal usaha',
                'type' => 'income',
                'is_active' => true,
                'is_system' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pembelian Bahan',
                'description' => 'Pengeluaran untuk pembelian bahan baku',
                'type' => 'expense',
                'is_active' => true,
                'is_system' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gaji Karyawan',
                'description' => 'Pengeluaran untuk gaji karyawan',
                'type' => 'expense',
                'is_active' => true,
                'is_system' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Operasional',
                'description' => 'Pengeluaran untuk biaya operasional',
                'type' => 'expense',
                'is_active' => true,
                'is_system' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_categories');
    }
};
