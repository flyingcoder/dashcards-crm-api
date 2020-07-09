<?php

namespace App\Traits;

use App\Template;


trait TemplateTrait 
{
	protected $allowed_names = [
			'new_team_member' => [
					'slots' => ['[user:email]', '[user:first_name]', '[user:last_name]' ]
				],
			'new_payment' => [
					'slots' => ['[payment:amount]', '[payment:link]' ]
				],
			'reset_password' => [
					'slots' => ['[user:first_name]', '[user:last_name]', '[user:email]', '[reset_password:link]']
				],
			'new_user' => [
					'slots' => ['[user:email]', '[user:first_name]', '[user:last_name]', '[user:profile_link]']
				],
			'new_project' => [
					'slots' => ['[project:title]', '[project:creator:fullname]', '[project:link]']
				],
			'new_notification' => [
					'slots' => ['[user:first_name]', '[user:last_name]', '[user:email]', '[notification:link]']
				],
			'new_client' => [
					'slots' => ['[client:email]', '[client:first_name]', '[client:last_name]', '[client:company_name]', '[client:profile_link]']
				],
			'new_task' => [
					'slots' => ['[task:title]', '[task:link]']
				],
			'task_update' => [
					'slots' => ['[task:title]', '[task:link]']
				],
			'questionaire_send' => [
					'slots' => ['[questionaire:title]', '[questionaire:link]']
				],
			'questionaire_response' => [
					'slots' => ['[questionaire:title]', '[questionaire:id]', '[questionaire:link]' ]
				],
			'invoice_created' => [
					'slots' => ['[invoice:title]', '[invoice:amount]', '[invoice:link]']
				],
		];

	/** 
	 * Get Template names
	 * @return array of strings
	 */
	public function allowedNames()
	{
		return array_keys($this->allowed_names);
	}

	/** 
	 * Get Template by name , override by default if not found
	 * @param $name string
	 * @param $override_from_default boolean
	 * @return App\Template with meta 'template'
	 */
	public function getTemplate($name, $override_from_default = true)
	{
		$template = Template::where('name', $name)->first();
		if ($override_from_default && !$template) {
			$name = str_replace(['admin_template_', 'client_template_'], [''], $name);
			$name = 'global_template_'.$name;
			$template = Template::where('name', $name)->first();
		}
		if (!$template) {
			return null;
		}
		$template->load('meta');
		return $template;
	}

	public function parse($string, $object)
	{
		# code...
	}
}