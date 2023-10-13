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
        DB::table("products")->insert([
            "name" => "burger",
            "description" => "The best burger in town",
            "price" => 11.99,
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now()
        ]);
    }
}