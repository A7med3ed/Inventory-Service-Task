<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('sku')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(10);
            $table->string('status', 20)->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'stock_quantity']);
        });

        DB::statement("
            CREATE INDEX idx_products_low_stock
            ON products (stock_quantity, low_stock_threshold)
            WHERE deleted_at IS NULL AND status = 'active'
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
