<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class OrderService
{
    public function validateSufficientIngredients(Collection $products)
    {
        foreach ($products as $product) {

            foreach ($product->ingredients as $ing) {

                if ($ing->available_amount < ($ing->pivot->required_amount * $product->quantity)) {
                    return false;
                }
            }
        }
        return true;
    }



    public function createOrder(Collection $products)
    {
        // Transaction to reduce DB calls
        DB::beginTransaction();

        try {
            $order = Order::create([
                "user_id" => Auth::id(),
                "total_price" => 0
            ]);

            foreach ($products as $product) {

                foreach ($product->ingredients as $ing) {

                    $ing->available_amount -= ($ing->pivot->required_amount * $product->quantity);
                    $ing->save();

                }

                $order->products()->attach(
                    $product->id,
                    ["price" => $product->price, "quantity" => $product->quantity]
                );

                $order->total_price += $product->price * $product->quantity;
            }

            $order->save();

        } catch (\Throwable $e) {

            Log::error("Failed order", ["error" => $e]);
            DB::rollBack();
            throw $e;

        }

        DB::commit();

        return $order;
    }
}