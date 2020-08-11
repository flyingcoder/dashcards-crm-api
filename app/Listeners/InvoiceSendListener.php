<?php

namespace App\Listeners;

use App\Events\InvoiceSend;
use App\Mail\DynamicEmail;
use App\Traits\TemplateTrait;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class InvoiceSendListener implements ShouldQueue
{
    use TemplateTrait, InteractsWithQueue;
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
     * @param  InvoiceSend  $event
     * @return void
     */
    public function handle(InvoiceSend $event)
    {
        $invoice = $event->invoice;
        $billed_to = $invoice->billedTo;
        $subject =  'Invoice ('.$invoice->title.')';
        if ($billed_to) {
            $template = $this->getTemplate($event->template_name, $billed_to->company()->id, true);
            if (!is_null($template)) {
                $content = $this->parseTemplate($event->template_name, $template->raw, $invoice);
                Mail::to($billed_to->email)->send(new DynamicEmail($content, $subject, null));
            }
        }
    }
}
