<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class IngredientInventoryAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $ingredient_name;

    public $ingredient_amount;

    public $ingredient_min_amount;


    /**
     * Create a new message instance.
     */
    public function __construct(string $ingredient_name, int $ingredient_amount, int $ingredient_min_amount)
    {
        $this->ingredient_name = $ingredient_name;
        $this->ingredient_amount = $ingredient_amount;
        $this->ingredient_min_amount = $ingredient_min_amount;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ingredient Inventory Alert',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.ingredient.min_reached',
            with: [
                'name' => $this->ingredient_name,
                'amount' => $this->ingredient_amount,
                'min_amount' => $this->ingredient_min_amount
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
