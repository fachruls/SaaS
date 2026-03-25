<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('cashier_shift_id')->nullable()->constrained('cashier_shifts')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('tax_percent', 5, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->enum('payment_method', ['cash', 'qris', 'transfer', 'card'])->default('cash');
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->decimal('change_amount', 12, 2)->default(0);
            $table->string('payment_reference')->nullable(); // for QRIS/transfer ref
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled', 'refunded'])->default('completed');
            $table->timestamps();

            $table->index(['store_id', 'status']);
            $table->index(['store_id', 'created_at']);
            $table->index(['store_id', 'cashier_shift_id']);
        });

        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name'); // snapshot at time of sale
            $table->string('product_sku')->nullable();
            $table->decimal('price', 12, 2);
            $table->integer('quantity');
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
        Schema::dropIfExists('transactions');
    }
};
