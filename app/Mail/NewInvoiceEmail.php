<?php

namespace App\Mail;

use App\Invoice;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewInvoiceEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    public $fromUser;

    /**
     * @var User
     */
    public $toUser;

    /**
     * @var string
     */
    public $dateTime;

    /**
     * @var string
     */
    public $invoice;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice )
    {
        $this->fromUser = $invoice->billedFrom;
        $this->toUser = $invoice->billedTo;
        $this->dateTime = Carbon::now();
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('[' . config('app.name') . '] New Invoice Created')
                    ->view('email.new-invoice-created');
    }
}
