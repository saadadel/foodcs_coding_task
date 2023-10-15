<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Ingredient;
use App\Events\IngredientUpdated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use App\Mail\IngredientInventoryAlert;
use App\Listeners\IngredientUpdatedListener;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendEmailIngredientReachedMinimumTest extends TestCase
{
    use RefreshDatabase;

    public function test_ingredient_updated_event_dispatched(): void
    {
        Event::fake();

        Event::assertListening(
            IngredientUpdated::class,
            IngredientUpdatedListener::class
        );
    }


    public function test_ingredient_updated_listener_sends_email_alert(): void
    {
        Mail::fake();

        $ingredient = Ingredient::create([
            "merchant_id" => 1,
            "name" => "onion",
            "available_amount" => 400,
            "min_amount" => 500,
            "amount_alert_sent" => false
        ]);

        $event = new IngredientUpdated($ingredient);
        $listener = new IngredientUpdatedListener();
        $listener->handle($event);

        Mail::assertSent(IngredientInventoryAlert::class);
        // Assert the listener set the flag to true, to prevent duplicate alerts
        $this->assertEquals($ingredient->amount_alert_sent, true);
    }

    public function test_ingredient_updated_listener_sends_email_only_once(): void
    {
        Mail::fake();

        $ingredient = Ingredient::create([
            "merchant_id" => 1,
            "name" => "onion",
            "available_amount" => 400,
            "min_amount" => 500,
            // Should prevent  the listener fro resending the alert
            "amount_alert_sent" => true
        ]);

        $event = new IngredientUpdated($ingredient);
        $listener = new IngredientUpdatedListener();
        $listener->handle($event);

        Mail::assertNotSent(IngredientInventoryAlert::class);
        $this->assertEquals($ingredient->amount_alert_sent, true);
    }
}
