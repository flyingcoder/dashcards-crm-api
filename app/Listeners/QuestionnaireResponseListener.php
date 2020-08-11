<?php

namespace App\Listeners;

use App\Events\QuestionnaireResponse;
use App\Mail\DynamicEmail;
use App\Traits\TemplateTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class QuestionnaireResponseListener implements ShouldQueue
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
     * @param QuestionnaireResponse $event
     * @return void
     */
    public function handle(QuestionnaireResponse $event)
    {
        $formResponse = $event->formResponse;
        $form = $formResponse->form;
        $form->link = $formResponse->link;
        if (isset($form->props['notif_email_receivers']) && !empty($form->props['notif_email_receivers'])) {
            $subject = 'Form response received.';
            $template = $this->getTemplate($event->template_name, $form->company_id, true);
            if (!is_null($template)) {
                $content = $this->parseTemplate($event->template_name, $template->raw, $form);
                Mail::to($form->props['notif_email_receivers'])->send(new DynamicEmail($content, $subject, null));
            }
        }
    }
}
