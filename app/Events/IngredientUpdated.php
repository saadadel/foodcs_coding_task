<?php

namespace App\Events;

use App\Models\Ingredient;
use App\Models\Merchant;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IngredientUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ingredient;

    /**
     * Create a new event instance.
     */
    public function __construct(Ingredient $ingredient)
    {
        $this->ingredient = $ingredient;
    }
}
