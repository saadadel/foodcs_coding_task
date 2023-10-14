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
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId("product_id")->constrained(
                table: "products", column: "id"
            );
            $table->foreignId("order_id")->constrained(
                table: "orders", column: "id"
            )->cascadeOnDelete();
            $table->unsignedInteger("quantity");
            $table->unsignedDecimal("price");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product');
    }
};