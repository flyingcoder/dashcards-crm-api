<?php

namespace App\Listeners;

use App\Events\InvoicePaid;
use App\Mail\DynamicEmail;
use App\Traits\TemplateTrait;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class InvoicePaidListener
{
    use TemplateTrait;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  InvoicePaid  $event
     * @return void
     */
    public function handle(InvoicePaid $event)
    {
        $invoice = $event->invoice;
        $billed_from = $invoice->billedFrom;
        $subject =  'Invoice ('.$invoice->title.') has been paid';
        if ($billed_from) {
            $template = $this->getTemplate($event->template_name, $billed_from->company()->id, true);
            if (!is_null($template)) {
                $content = $this->parseTemplate($event->template_name, $template->raw, $invoice);
                Mail::to($billed_from->email)->send(new DynamicEmail($content, $subject, null));
            }
        }
    }
}
