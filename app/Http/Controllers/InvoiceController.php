<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Mail\NewInvoiceEmail;
use App\Policies\InvoicePolicy;
use App\Repositories\InvoiceRepository;
use App\Repositories\TemplateRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class InvoiceController extends Controller
{
    protected $repo;
    protected $trepo;

    public function __construct(InvoiceRepository $repo, TemplateRepository $trepo)
    {
        $this->repo = $repo;
        $this->trepo = $trepo;
    }

    public function index()
    {
        (new InvoicePolicy())->index();

        if(!request()->ajax())
            return view('pages.invoices'); 

        if (auth()->user()->hasRoleLike('client')) {
            return $this->repo->getClientInvoices(auth()->user(), request());    
        }

        $company = auth()->user()->company();

        return $company->paginatedCompanyInvoices(request());
    }

    public function invoice($id)
    {
        (new InvoicePolicy())->index();

        $invoice = Invoice::findOrFail($id);

        (new InvoicePolicy())->view($invoice);

        return $invoice;
    }

    public function store()
    {        
        request()->validate( [
            'date' => 'date',
            'due_date' => 'required|date',
            'title' => 'required',
            'total_amount' => 'required',
            'items' => 'required|string',
            'type' => 'required',
            'billed_from' => 'exists:users,id',
            'billed_to' => 'exists:users,id'
        ]);

        $data = [
            'type' => request()->type,
            'date' => request()->date,
            'user_id' => auth()->user()->id,
            'due_date' => request()->due_date,
            'title' => request()->title,
            'total_amount' => request()->total_amount,
            'items' => request()->items,
            'terms' => request()->terms ?? null,
            'notes' => request()->notes ?? null
        ];

        if(request()->has('project_id'))
            $data['project_id'] = request()->project_id;

        if(request()->has('billed_to'))
            $data['billed_to'] = request()->billed_to;

        if(request()->has('billed_from'))
            $data['billed_from'] = request()->billed_from;

        if(request()->has('discount'))
            $data['discount'] = request()->discount;

        if(request()->has('tax'))
            $data['tax'] = request()->tax;

        if(request()->has('shipping'))
            $data['shipping'] = request()->shipping;

        if(request()->has('company_logo')) {
            $data['company_logo'] = request()->company_logo;
        }

        if(request()->has('is_recurring')) {
            $data['is_recurring'] = request()->is_recurring;
        }

        $props = [
            'send_email' => request()->has('send_email') ? request()->send_email : 'no',
            'template' => request()->has('template') ? request()->template : 1,
        ];
        $data['props'] =  $props;

        $invoice = auth()->user()->invoices()->create($data);

        $items = collect(json_decode($invoice->items));
        unset($invoice->items);
        $invoice->items = $items;
        $invoice->billedFrom = $invoice->billedFrom;
        $invoice->billedTo = $invoice->billedTo;
        $invoice->status = 'pending';
        $invoice->props = $props;

        if (request()->has('send_email') && request()->send_email == 'yes') {
            $invoice->pdf = $this->repo->generatePDF($invoice);
            \Mail::to($invoice->billedTo->email)->send(new NewInvoiceEmail($invoice));
        }

        return $invoice;
    }

    public function update($id)
    {
        $invoice = Invoice::findOrFail($id);

        request()->validate( [
            'date' => 'date',
            'due_date' => 'required|date',
            'title' => 'required',
            'total_amount' => 'required',
            'items' => 'required|string',
            'type' => 'required'
        ]);

        $invoice->date = request()->date;
        $invoice->due_date = request()->due_date;
        $invoice->title = request()->title;
        $invoice->total_amount = request()->total_amount;
        $invoice->items = request()->items;
        $invoice->type = request()->type;
        $invoice->notes = request()->notes ?? null;

        if(request()->has('project_id'))
            $invoice->project_id = request()->project_id;

        if(request()->has('billed_to'))
            $invoice->billed_to = request()->billed_to;

        if(request()->has('billed_from'))
            $invoice->billed_from = request()->billed_from;

        if(request()->has('discount'))
            $invoice->discount = request()->discount;

        if(request()->has('shipping'))
            $invoice->shipping = request()->shipping;

        if(request()->has('tax'))
            $invoice->tax = request()->tax;

        if(request()->has('company_logo')) {
            $invoice->company_logo = request()->company_logo;
        }

        $props = [
            'send_email' => request()->has('send_email') ? request()->send_email : 'no',
            'template' => request()->has('template') ? request()->template : 1,
        ];
        $invoice->props = $props;

        $invoice->save();

        if($invoice->hasMeta('payment_intent')){
            $invoice->removeMeta('payment_intent');
        }

        $invoice->billedFrom = $invoice->billedFrom;
        $invoice->billedTo = $invoice->billedTo;
        $invoice->id = $id;

        return $invoice;
    }

    public function delete($id)
    {       
        $invoice = Invoice::findOrFail($id);
        
        return $invoice->destroy($id);
    }

    public function bulkDelete()
    {
        request()->validate([
            'ids' => 'required|array'
        ]);
        try {
            DB::beginTransaction();
            $invoices = Invoice::whereIn('id', request()->ids)->get();

            if ($invoices) {
                foreach ($invoices as $key => $invoice) {
                    if (!$invoice->delete()) {
                        throw new \Exception("Failed to delete invoice {$invoice->title}!", 1);
                    }
                }
            }
            DB::commit();
            return response()->json(['message' => $invoices->count().' invoice(s) was successfully deleted'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => "Some invoices failed to delete"], 500);
        }
    }

    public function statistics()
    {
        $company = auth()->user()->company();
        $clientGroup = $company->clientTeam();
        $clients = $clientGroup->teamMembers()->with('user')
            ->whereHas('user', function($query) {
                $query->whereNull('deleted_at');
            })->paginate(4);

        foreach ($clients as $key => $client) {
            $row = $client;
            $row->amount = '$'.$this->repo->totalInvoices($client->user, 'billed_to'); //todo filter for status
        }
        
        $data = [];
        $d = new \DateTime(date('Y-m-d'), new \DateTimeZone('UTC')); 
        $d->modify('first day of previous month');
        $year = $d->format('Y'); 
        $month = $d->format('m');

        if (!(request()->has('client_only') && boolval(request()->client_only))) {
            $data = [
                'total_clients' => $clients->total(),
                'current_month_total' => '$'.$this->repo->totalMonthlyClientInvoices($clientGroup),
                'last_month_total' => '$'.$this->repo->totalMonthlyClientInvoices($clientGroup,$month, $year)
            ];
        }

        return response()->json($clients->toArray() + $data, 200);
    }

    public function getPDFInvoice($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->load('billedFrom');
        $invoice->load('billedTo');

        $html = $this->trepo->parseInvoice($invoice, true);
        $location = $this->repo->generatePDF($invoice, $html);

        return response()->json(['url' => $location ], 200);
    }

    public function getParseInvoice($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->load('billedFrom');
        $invoice->load('billedTo');

        $html = $this->trepo->parseInvoice($invoice, false);

        return response()->json(['html' => $html], 200);
    }
}
