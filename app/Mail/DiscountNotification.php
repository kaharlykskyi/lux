<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DiscountNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected $discount = [];

    protected $user = [];

    /**
     * Create a new message instance.
     *
     * @param $discount
     * @param $user
     */
    public function __construct($discount,$user)
    {
        $this->discount = $discount;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.discount_notification')
            ->with([
                'discount' => $this->discount,
                'user' => $this->user
            ])
            ->subject('Получение скидки');
    }
}
