<?php

namespace App\Traits;

use App\Template;


trait TemplateTrait
{
    /**
     * Get Template names
     * @return array of strings
     */
    public function allowedNames()
    {
        return array_keys($this->allowed_names);
    }

    /**
     * @return array
     */
    public function emailTemplates()
    {
        $data = [];
        foreach ($this->allowed_names as $key => $allowed_name) {
            $data[] = array_merge($allowed_name, ['type' => $key]);
        }
        return $data;
    }

    protected $allowed_names = [
        'new_team_member' => [
            'slots' => ['[user:email]', '[user:first_name]', '[user:last_name]'],
            'description' => 'Will be sent to the email address of the newly created user',
            'title' => 'New Team Member'
        ],
        'new_client' => [
            'slots' => ['[client:email]', '[client:first_name]', '[client:last_name]', '[client:company_name]', '[client:profile_link]'],
            'description' => 'Will be sent to the admins or managers when a newly created client is added',
            'title' => 'New Client Created'
        ],
//        'reset_password' => [
//            'slots' => ['[user:first_name]', '[user:last_name]', '[user:email]', '[user:reset_password_link]'],
//            'description' => 'Will be sent to the user who request a password reset',
//            'title' => 'Reset Password'
//        ],
        'new_user_created' => [
            'slots' => ['[user:email]', '[user:first_name]', '[user:last_name]', '[user:profile_link]'],
            'description' => 'Will be sent to the admins and managers when a new user is added',
            'title' => 'New User Created'
        ],
        'new_project_created' => [
            'slots' => ['[project:title]', '[project:creator:first_name]', '[project:creator:last_name]', '[project:link]'],
            'description' => 'Will be sent to the admins, managers and members involved on the newly created project/campaign',
            'title' => 'New Project Created'
        ],
//        'new_notification' => [
//            'slots' => ['[user:first_name]', '[user:last_name]', '[user:email]', '[notification:link]'],
//            'description' => 'Will be sent to the users the notification intended to',
//            'title' => 'New Email Notification'
//        ],

        'new_task' => [
            'slots' => ['[task:title]', '[task:link]'],
            'description' => 'Will be sent to the users whom the task is assigned',
            'title' => 'New Task Created'
        ],
        'task_update' => [
            'slots' => ['[task:title]', '[task:link]'],
            'description' => 'Will be sent to the users whom the task is assigned',
            'title' => 'Task Updated'
        ],
        'questionnaire_send' => [
            'slots' => ['[questionnaire:title]', '[questionnaire:link]'],
            'description' => 'Will be sent to the users whom the questionnaire intended to',
            'title' => 'Questionnaire Sent'
        ],
        'questionnaire_response' => [
            'slots' => ['[questionnaire:title]', '[questionnaire:id]', '[questionnaire:link]'],
            'description' => 'Will be sent to the users whom the questionnaire supplied notifications from emails',
            'title' => 'Questionnaire Response'
        ],
        'invoice_send' => [
            'slots' => ['[invoice:title]', '[invoice:total_amount]', '[invoice:link]', '[invoice:pdf]'],
            'description' => 'Will be sent to user the invoice intended to',
            'title' => 'Invoice Created'
        ],
        'invoice_paid' => [
            'slots' => ['[invoice:title]', '[invoice:total_amount]', '[invoice:link]'],
            'description' => 'Will be sent to "billed from user" when a payment is done to an invoice',
            'title' => 'Invoice Paid'
        ],
    ];

    /**
     * Get Template by name , override by default if not found
     * @param $name string
     * @param null $companyId
     * @param $override_from_default_if_empty boolean
     * @return App\Template with meta 'template'
     */
    public function getTemplate($name, $companyId = null, $override_from_default_if_empty = true)
    {
        $template = Template::where('name', $name)->where('company_id', $companyId)->first();
        if ($override_from_default_if_empty && !$template) {
            $name = str_replace(['admin_template:', 'client_template:'], [''], $name);
            $name = 'global_template:' . $name;
            $template = Template::where('name', $name)->first();
        }
        if (!$template) {
            return null;
        }
        $template->raw = $template->getMeta('template');
        return $template;
    }

    /**
     * @param $name
     * @param $template
     * @param null $object
     * @return string|string[]
     */
    public function parseTemplate($name, $template, $object = null)
    {
        $split = explode(':', $name);
        $name = count($split) > 1 ? trim($split[1]) : trim($split[0]);
        if (is_null($object) || !isset($this->allowed_names[$name])) {
            return $template;
        }
        $slots = $this->allowed_names[$name]['slots'];

        foreach ($slots as $key => $slot) {
            $split = explode(':', str_replace(['[', ']'], '', $slot));
            if (count($split) == 2) {
                $template = str_replace($slot, @$object->{$split[1]}, $template);
            } elseif (count($split) === 3) {
                $template = str_replace($slot, @$object->{$split[1]}->{$split[2]}, $template);
            }
        }
        return $template;
    }
}