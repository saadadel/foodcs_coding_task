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

        $burger = DB::table("products")->where("name", "burger")->select("id")->first();
        $ingredients = DB::table("ingredients")->whereIn("name", [
            "beef",
            "cheese",
            "onion"
        ])->select("id", "name")->get();
        $ingredient_product = [];
        $required_amount = [
            "beef" => 150,
            "cheese" => 30,
            "onion" => 20
        ];
        foreach ($ingredients as $ing) {
            $ingredient_product[] = [
                "product_id" => $burger->id,
                "ingredient_id" => $ing->id,
                "required_amount" => $required_amount[$ing->name],
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ];
        }
        DB::table('ingredient_product')->insert($ingredient_product);

   }
}