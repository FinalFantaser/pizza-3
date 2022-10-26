<?php

namespace App\Mail;

use App\Models\Shop\Order\Order;
use App\Services\SettingsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    private SettingsService $_settings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->order->load(['customerData', 'pickupPoint', 'items', 'payment', 'deliveryZone']);

        $this->_settings = SettingsService::getInstance();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->_settings->email_subject)->view('mail.order-placed', ['order' => $this->order]);
    }

    // public function envelope()
    // {
    //     return new Envelope(
    //         from: new Address($this->_settings->email_from_address, $this->_settings->email_from_name),
    //         subject: $this->_settings->email_subject,
    //     );
    // } //envelope


    // public function content()
    // {
    //     return new Content(
    //         view: 'mail.order-placed',
    //     );
    // } //content
}
