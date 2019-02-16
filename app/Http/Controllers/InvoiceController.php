<?php

namespace App\Http\Controllers;

use App\Policies\InvoicePolicy;
use Illuminate\Http\Request;
use App\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        (new InvoicePolicy())->index();

        if(!request()->ajax())
            return view('pages.invoices'); 

        $company = auth()->user()->company();

        if(request()->has('all') && request()->all)
            return $company->allCompanyInvoices();

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
        return auth()->user()->storeInvoice();
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

        dd(request()->items);

        $invoice->date = request()->date;
        $invoice->due_date = request()->due_date;
        $invoice->title = request()->title;
        $invoice->total_amount = request()->total_amount;
        //$invoice->items = $items;
        $invoice->type = request()->type;

        if(request()->has('project_id'))
            $invoice->project_id = request()->project_id;

        if(request()->has('billed_to'))
            $invoice->billed_to = request()->billed_to;

        if(request()->has('billed_from'))
            $invoice->billed_from = request()->billed_from;

        if(request()->has('discount'))
            $invoice->discount = request()->discount;

        if(request()->hasFile('company_logo'))
            $invoice->company_logo = request()->file('company_logo')->store('invoice');

        return $invoice;
    }

    public function delete($id)
    {       
        $invoice = Invoice::findOrFail($id);
        
        return $invoice->destroy($id);
    }


}
