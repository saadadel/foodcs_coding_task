<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ingredients')->insert([
            [
                "id" => 1,
                "merchant_id" => 1,
                "name" => "beef",
                "available_amount" => 20000, // 20kg
                "min_amount" => 10000, // 50% of the available_amount
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "id" => 2,
                "merchant_id" => 1,
                "name" => "cheese",
                "available_amount" => 5000, // 5kg
                "min_amount" => 2500, // 50% of the available_amount
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "id" => 3,
                "merchant_id" => 1,
                "name" => "onion",
                "available_amount" => 1000, // 1kg
                "min_amount" => 500, // 50% of the available_amount
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
        ]);
    }
}
