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
        Schema::create('ingredient_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId("product_id")->constrained(
                table: "products", column: "id"
            )->cascadeOnDelete();
            $table->foreignId("ingredient_id")->constrained(
                table: "ingredients", column: "id"
            )->cascadeOnDelete();
            $table->integer("required_amount");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_product');
    }
};