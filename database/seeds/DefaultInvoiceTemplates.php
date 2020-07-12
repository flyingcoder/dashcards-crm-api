<?php

use Illuminate\Database\Seeder;

class DefaultInvoiceTemplates extends Seeder
{

    /**
     * @throws Throwable
     */
    public function run()
    {
        $template =  \App\Template::create([
        		'name' => 'Default-1',
        		'company_id' => 0,
        		'status' => 'active',
        		'replica_type' => 'App\\Invoice',
        		'created_at' => now()->format('Y-m-d H:i:s')
	    	]);
        
        $html  = view('invoices.template-1')->render();

        $template->setMeta('template', $html);
    }
}
