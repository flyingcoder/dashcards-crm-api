<?php

namespace App\Events;

use App\Invoice;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class InvoiceSend
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string
     */
    public $template_name;
    /**
     * @var Invoice
     */
    public $invoice;

    /**
     * Create a new event instance.
     *
     * @param Invoice $invoice
     * @param string $template_name
     */
    public function __construct(Invoice $invoice, $template_name = 'admin_template:invoice_send')
    {
        $this->invoice = $invoice;
        $this->template_name = $template_name;
    }
}
