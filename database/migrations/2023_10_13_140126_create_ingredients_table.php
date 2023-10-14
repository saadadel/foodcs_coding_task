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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId("merchant_id")->constrained();
            $table->string("name")->uniqid();
            $table->string("description", 500)->nullable();
            $table->unsignedInteger("available_amount"); // in grams
            $table->unsignedInteger("min_amount"); // in grams
            $table->boolean("amount_alert_sent")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
