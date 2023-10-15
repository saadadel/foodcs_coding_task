<?php

namespace App\Listeners;

use Throwable;
use App\Events\IngredientUpdated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\IngredientInventoryAlert;
use Illuminate\Contracts\Queue\ShouldQueue;

class IngredientUpdatedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(IngredientUpdated $event): void
    {
        // Ingredient amount reached minimum & email NOT sent
        if (
            $event->ingredient->available_amount <= $event->ingredient->min_amount &&
            !$event->ingredient->amount_alert_sent
        ) {

            Mail::to($event->ingredient->merchant->business_email, $event->ingredient->merchant->name)
                ->send(new IngredientInventoryAlert(
                    $event->ingredient->name,
                    $event->ingredient->available_amount,
                    $event->ingredient->min_amount
                ));

            $event->ingredient->amount_alert_sent = true;
            $event->ingredient->save();
        }

        // Ingredient amount above minimum & email sent
        else if (
            $event->ingredient->available_amount > $event->ingredient->min_amount &&
            $event->ingredient->amount_alert_sent
        ) {
            // set to false to be able to alert again when the amount decrease to min
            $event->ingredient->amount_alert_sent = false;
            $event->ingredient->save();
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::error("Failed IngredientInventoryListener", $exception);
    }
}
