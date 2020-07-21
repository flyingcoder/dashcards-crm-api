<?php

namespace App\Listeners;

use App\Company;
use App\Events\NewClientCreated;
use App\Mail\DynamicEmail;
use App\Repositories\MembersRepository;
use App\Traits\TemplateTrait;
use Illuminate\Support\Facades\Mail;

class NewClientCreatedListener
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
        $this->template_name = 'admin_template:new_client';
        $this->repository = $repository;
    }

    /**
     * Handle the event.
     *
     * @param NewClientCreated $event
     * @return void
     */
    public function handle(NewClientCreated $event)
    {
        $client = $event->client;
        $company = $client->company();
        $client->company_name = (Company::find($client->props['company_id']))->name ?? $company->name;
        $admins = $this->repository->getCompanyAdmins($company)->pluck('email')->toArray();
        $managers = $this->repository->getCompanyManagers($company)->pluck('email')->toArray();
        $admins_managers_emails = array_unique(array_merge($admins, $managers));
        if (count($admins_managers_emails) > 0) {
            $template = $this->getTemplate($this->template_name, $company->id, true);
            if (!is_null($template)) {
                $content = $this->parseTemplate($this->template_name, $template->raw, $client);
                Mail::to($admins_managers_emails)->send(new DynamicEmail($content, 'New Client Created', null));
            }
        }
    }
}
