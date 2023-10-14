<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Repositories\ProductRepository;
use App\Services\OrderService;

class OrderController extends BaseController
{
    private $productRepo;

    private $orderService;


    public function __construct(ProductRepository $productRepo, OrderService $orderService)
    {

        $this->productRepo = $productRepo;
        $this->orderService = $orderService;

    }


    public function store(CreateOrderRequest $request)
    {

        // Load products with the ingredients and inject the desired quantity in each product
        $products = $this->productRepo->loadNewOrderProducts($request);


        // Validate sufficient ingredients amount
        if (! $this->orderService->validateSufficientIngredients($products))
        {
            return $this->sendError(
                'Insufficient Ingredients.',
                ['error'=>'Sorry, we\'re out of ingredients, please try again later'],
                422
            );
        }


        // Create the order & deduce the required ingredients amount
        try {
            $order = $this->orderService->createOrder($products);
        } catch (\Throwable $th) {
            return $this->sendError(error: 'Failed, please try again later',code: 500);
        }


        return $this->sendResponse(new OrderResource($order), "Order created successfully");
    }
}