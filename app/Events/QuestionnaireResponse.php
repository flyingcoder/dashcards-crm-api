<?php

namespace App\Events;

use App\FormResponse;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;


class QuestionnaireResponse
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var FormResponse
     */
    public $formResponse;
    /**
     * @var string
     */
    public $template_name;

    /**
     * Create a new event instance.
     *
     * @param FormResponse $formResponse
     * @param string $template_name
     */
    public function __construct(FormResponse $formResponse, $template_name = 'admin_template:questionnaire_response')
    {
        $this->formResponse = $formResponse;
        $this->template_name = $template_name;
    }

}
