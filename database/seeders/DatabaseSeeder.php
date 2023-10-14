<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Super User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            ProductSeeder::class,
            IngredientSeeder::class
        ]);

        $ingredient_product = [];
        $ingredients = [
            1 => 150, //beef
            2 => 30, //cheese
            3 => 20 //onion
        ];

        foreach ($ingredients as $ing_id => $required_amount) {
            $ingredient_product[] = [
                "product_id" => 1,
                "ingredient_id" => $ing_id,
                "required_amount" => $required_amount,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ];
        }

        DB::table('ingredient_product')->insert($ingredient_product);
    }
}
