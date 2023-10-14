<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("merchants")->insert([
            "id" => 1,
            "user_id" => DB::table("users")->select("id")->first()->id,
            "name" => "SmashBurger",
            "business_email" => "manager@smashburger.com"
        ]);

        DB::table("products")->insert([
            "id" => 1,
            "merchant_id" => 1,
            "name" => "burger",
            "description" => "The best burger in town",
            "price" => 11.99,
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now()
        ]);
    }
}
