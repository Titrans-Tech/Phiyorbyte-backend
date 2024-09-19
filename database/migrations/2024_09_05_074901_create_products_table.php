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
            $table->string('ref_no')->nullable();
            $table->string('categoryname')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('quantity')->nullable();
            $table->string('product_name')->nullable();
            $table->text('body')->nullable();
            $table->float('purchase_price', 8, 2)->nullable();
            $table->float('amount', 8, 2)->nullable();
            $table->float('discount', 8, 2)->nullable();
            $table->string('product_size')->nullable();
            $table->string('product_colors')->nullable();
            $table->string('images1')->nullable();
            $table->string('images2')->nullable();
            $table->string('images3')->nullable();
            $table->string('images4')->nullable();
            $table->string('status')->nullable();
            $table->string('slug')->nullable();
            $table->timestamps();
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
