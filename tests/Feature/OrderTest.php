<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private $base_request_body;


    protected function setUp(): void
    {
        parent::setUp();
        $this->base_request_body = [
            "products" => [
                [
                    "product_id" => 1,
                    "quantity" => 2
                ]
            ]
        ];
    }


    public function test_successful_order(): void
    {
        Event::fake();

        $response = $this->actingAs(User::first(), "sanctum")
            ->postJson("api/order/create", $this->base_request_body);

        // Assert successful order data
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonPath("success", true)
            ->assertJsonPath("data.total_price", 23.98)
            ->assertJsonPath("data.products.0.id", 1)
            ->assertJsonPath("data.products.0.quantity", 2);


        // Assert ingredients decreased correctly
        $this->assertDatabaseHas("ingredients", [
            // beef was 20000g, we made 2 burgers with 150g beef for each. 20000-(2*150)=19700
            "available_amount" => 19700,
            // cheese was 5000g, we made 2 burgers with 30g cheese for each. 5000-(2*300)=4640
            "available_amount" => 4640,
            // onion was 1000g, we made 2 burgers with 20g onion for each. 1000-(2*20)=960
            "available_amount" => 960
        ]);
    }

    public function test_insufficient_ingredients(): void
    {
        Event::fake();

        /**
         * The database is reseeded with 1000g onions ingredient,
         * this can make only 50 burgers (with 20g onion for each)
         * so, let's try to create 51 burger
         */
        $request_body = $this->base_request_body;
        $request_body["products"][0]["quantity"] = 51;

        $response = $this->actingAs(User::first(), "sanctum")
            ->postJson("api/order/create", $request_body);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonPath("success", false)
            ->assertJsonPath("message", "Insufficient Ingredients.")
            ->assertJsonPath("data.error", "Sorry, we're out of ingredients, please try again later");
    }


    public function test_invalid_request_data(): void
    {
        Event::fake();

        $request_body = $this->base_request_body;
        $request_body["products"][0]["id"] = 99;
        unset($request_body["products"][0]["quantity"]);

        $response = $this->actingAs(User::first(), "sanctum")
            ->postJson("api/order/create", $request_body);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
