<?php

namespace App\Repositories;

use App\Http\Requests\CreateOrderRequest;
use App\Models\Product;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    public function loadNewOrderProducts(CreateOrderRequest $request) : Collection
    {
        $product_quantity_map = [];

        foreach ($request->products as $product) {

            $product_quantity_map[$product["product_id"]] = $product["quantity"];

        }

        $products = Product::with("ingredients")->findMany(array_keys($product_quantity_map));

        $products->map(function($item, $key) use($product_quantity_map) {

            $item->quantity = $product_quantity_map[$item->id];

        });

        return $products;
    }
}