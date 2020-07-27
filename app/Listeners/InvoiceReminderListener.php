<?php

namespace App\Listeners;

use App\Events\InvoiceReminder;
use App\Mail\DynamicEmail;
use App\Traits\TemplateTrait;
use Illuminate\Support\Facades\Mail;

class InvoiceReminderListener
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
     * @param InvoiceReminder $event
     * @return void
     */
    public function handle(InvoiceReminder $event)
    {
        $invoice = $event->invoice;
        $billed_to = $invoice->billedTo;
        $subject =  'Invoice Reminder for '.$invoice->title;
        if ($billed_to) {
            $template = $this->getTemplate($event->template_name, $billed_to->company()->id, true);
            if (!is_null($template)) {
                $content = $this->parseTemplate($event->template_name, $template->raw, $invoice);
                Mail::to($billed_to->email)->send(new DynamicEmail($content, $subject, null));
            }
        }
    }
}
