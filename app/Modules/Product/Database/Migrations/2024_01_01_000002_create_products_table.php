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
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(10);
            $table->enum('status', ['active', 'inactive', 'discontinued'])->default('active');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('slug');
            $table->index('sku');
            $table->index('status');
            $table->index('category_id');
            $table->index('is_active');
            $table->index(['sku', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
