<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('short_description', 500)->nullable();
            $table->string('sku')->unique()->nullable();
 
            $table->decimal('price', 10, 2);
            $table->decimal('compare_price', 10, 2)->nullable(); // for "was/now" pricing & promos
 
            $table->integer('stock')->default(0);
            $table->integer('low_stock_threshold')->default(5); // triggers low-stock notification
 
            $table->boolean('is_active')->default(true);   // visibility on storefront
            $table->boolean('is_featured')->default(false); // for landing page "featured products"
 
            $table->timestamps();
            $table->softDeletes(); // keep order history intact even if a product is removed
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
