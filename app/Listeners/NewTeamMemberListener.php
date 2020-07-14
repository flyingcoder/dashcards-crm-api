<?php

namespace App\Listeners;

use App\Events\NewUserCreated;
use App\Mail\DynamicEmail;
use App\Repositories\MembersRepository;
use App\Traits\TemplateTrait;
use Illuminate\Support\Facades\Mail;

class NewTeamMemberListener
{
    use TemplateTrait;
    protected $template_name;
    protected $repository;

    /**
     * Create the event listener.
     *
     * @param MembersRepository $repository
     */
    public function __construct(MembersRepository $repository)
    {
        $this->template_name = 'admin_template:new_team_member';
        $this->repository = $repository;
    }

    /**
     * Handle the event.
     *
     * @param NewUserCreated $event
     * @return void
     */
    public function handle(NewUserCreated $event)
    {
        $user = $event->user;
        $company = $user->company();
        if ($user) {
            $template = $this->getTemplate($this->template_name, $company->id, true);
            if (!is_null($template)) {
                $content = $this->parseTemplate($this->template_name, $template->raw, $user);
                Mail::to($user->email)->send(new DynamicEmail($content, 'New Team Member', null));
            }
        }
    }
}
