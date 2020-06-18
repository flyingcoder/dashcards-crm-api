<?php

namespace App\Console\Commands;

use App\Invoice;
use App\Mail\NewInvoiceEmail;
use App\Repositories\InvoiceRepository;
use App\Repositories\TemplateRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Tolawho\Loggy\Facades\Loggy;


class GenerateRecurringInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-recurring-invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will generate incoming invoice for those with set is_recurring true';

    protected $repo;
    protected $trepo;
    protected $days_before_remind = 23;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(InvoiceRepository $repo, TemplateRepository $trepo)
    {
        $this->repo = $repo;
        $this->trepo = $trepo;
        parent::__construct();
    }

    /**
     * Create a copy of invoice for recurring
     * @param \App\Invoice $parent
     * @param \Carbon\Carbon $next_due_date
     * @param \Carbon\Carbon $date
     * @return \App\Invoice
     */

    public function createCopy($parent, $next_due_date, $date)
    {
        $parent_props = $parent->props;
        //create next invoice
        $new_invoice = $parent->replicate();
        $new_invoice->due_date = $next_due_date;
        $new_invoice->date = $date->copy()->addMonth();
        $new_invoice->props = [
            'template' => $parent_props['template'],
            'send_email' => $parent_props['send_email'],
        ];
        $new_invoice->is_recurring = false;
        $new_invoice->parent = $parent->id;
        $new_invoice->save();
        
        if ($parent_props['send_email'] == 'yes') {
            $new_invoice->load('billedFrom');
            $new_invoice->load('billedTo');
            
            if ($new_invoice->billedTo) {
                $html = $this->trepo->parseInvoice($new_invoice, true);
                $new_invoice->pdf = $this->repo->generatePDF($new_invoice,$html);
                \Mail::to($new_invoice->billedTo->email)->send(new NewInvoiceEmail($new_invoice));
            }
        }
        return $new_invoice;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $skip = $created = 0;
        $now = now();
        $recurring_invoices = Invoice::where('is_recurring', 1)->whereNull('parent')->get();

        foreach ($recurring_invoices as $key => $parent) {
            //Discontinue invoice if billed from user is deleted
            $billed_from = User::find($parent->billed_from);
            if (!$billed_from) {
                $skip += 1;
                continue;
            }
            //Discontinue invoice if billed to user is deleted
            $billed_to = User::find($parent->billed_to);
            if (!$billed_to) {
                $skip += 1;
                continue;
            }
            
            //get the last instance of recurring invoice from a given parent
            $last = Invoice::where('parent', $parent->id)->latest()->first();
            if ($last) {
                $due_date = Carbon::createFromFormat('Y-m-d H:i:s', $last->due_date.' 00:00:00');
                $date = Carbon::createFromFormat('Y-m-d H:i:s', $last->date.' 00:00:00');
                $next_due_date = $due_date->copy()->addMonth();
                $diff_in_days = abs($now->copy()->diffInDays($next_due_date));
                if ($diff_in_days <= $this->days_before_remind) {
                    $this->createCopy($parent, $next_due_date,$date);
                    $created += 1;
                } else {
                    $skip += 1;
                }
            } else {
                $due_date = Carbon::createFromFormat('Y-m-d H:i:s', $parent->due_date.' 00:00:00');
                $date = Carbon::createFromFormat('Y-m-d H:i:s', $parent->date.' 00:00:00');
                $next_due_date = $due_date->copy()->addMonth();
                $diff_in_days = abs($now->copy()->diffInDays($next_due_date));
                if ($diff_in_days <= $this->days_before_remind) {
                    $this->createCopy($parent, $next_due_date,$date);
                    $created += 1;
                }
            }
        }

        Loggy::write('event', "Done! Skip: ".$skip." Created:".$created);
    }
}
