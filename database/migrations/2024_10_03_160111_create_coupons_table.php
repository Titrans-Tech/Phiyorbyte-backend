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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable(); 
            $table->string('coupon_code')->unique();
            $table->enum('type', ['fixed', 'percent'], 190); 
            $table->decimal('discount', 8, 2); 
            $table->string('valid_from')->nullable();
            $table->string('valid_to')->nullable();
            $table->string('usage_limit')->nullable(); 
            $table->string('used_count')->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
