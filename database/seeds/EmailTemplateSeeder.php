<?php

use App\Template;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $key = 'global_template:';

        $templates = [
        	$key.'new_team_member' => 'Welcome user [user:first_name]',
        	$key.'new_payment' => 'New payment has been made! For more info please visit <a href="[payment:link]">payment info</a>.',
        	$key.'reset_password' => 'Your password has been successfully reset.',
        	$key.'new_user' => 'A new user ([user:first_name] [user:last_name] ) has been created.',
        	$key.'new_project' => 'A new project ([project:title]) has been created by [project:creator:fullname]',
        	$key.'new_notification' => 'A notification has been created check [notification:link]',
        	$key.'new_client' => 'A new client ([user:first_name] [user:last_name] ) has been created.',
        	$key.'new_task' => 'A new task ([task:title]) has been created.',
        	$key.'task_update' => 'Task ([task:title]) has been updated',
        	$key.'questionaire_send' => 'Please answer this questionaire. <a href="[questionaire:link]" target="_blank">[questionaire:title]</a>',
        	$key.'questionaire_response' => 'A questionaire response for [questionaire:title] has been submitted',
        	$key.'invoice_send' => 'An invoice was created for you with amount : [invoice:amount]. Check <a href="[invoice:link]" target="_blank">[invoice:title]</a> for more details',
        ];

        foreach ($templates as $key => $template_value) {
        	if (!Template::where('name', $key)->exists()) {
        		$template = Template::create([
        			'company_id' => 0,
        			'name' => $key,
        			'status' => 'active',
        			'replica_type' => 'App\\Template',
        			'created_at' => now()->format('Y-m-d H:i:s')
        		]);
        		$template->setMeta('template', $template_value);
        	}
        }

        echo "Done!";
    }
}
