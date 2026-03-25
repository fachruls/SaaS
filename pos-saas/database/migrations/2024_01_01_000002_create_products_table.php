<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('cost_price', 12, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->integer('low_stock_alert')->default(5);
            $table->string('unit', 20)->default('pcs'); // pcs, kg, liter, etc.
            $table->string('image')->nullable();
            $table->string('barcode')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['store_id', 'is_active']);
            $table->index(['store_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
