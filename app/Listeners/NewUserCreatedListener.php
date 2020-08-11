<?php

namespace App\Listeners;

use App\Events\NewUserCreated;
use App\Mail\DynamicEmail;
use App\Repositories\MembersRepository;
use App\Traits\TemplateTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NewUserCreatedListener implements ShouldQueue
{
    use TemplateTrait, InteractsWithQueue;
    protected $template_name;
    protected $repository;

    /**
     * Create the event listener.
     *
     * @param MembersRepository $repository
     */
    public function __construct(MembersRepository $repository)
    {
        $this->template_name = 'admin_template:new_user_created';
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
        $admins = $this->repository->getCompanyAdmins($company)->pluck('email')->toArray();
        $managers = $this->repository->getCompanyManagers($company)->pluck('email')->toArray();
        $admins_managers_emails = array_unique(array_merge($admins, $managers));
        if (count($admins_managers_emails) > 0) {
            $template = $this->getTemplate($this->template_name, $company->id, true);
            if (!is_null($template)) {
                $content = $this->parseTemplate($this->template_name, $template->raw, $user);
                Mail::to($admins_managers_emails)->send(new DynamicEmail($content, 'New User Created', null));
            }
        }
    }
}
