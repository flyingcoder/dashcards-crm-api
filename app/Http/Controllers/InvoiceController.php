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

    public function form()
    {       
        return view('pages.invoice-form'); 
    }

    public function template()
    {       
        return view('pages.invoice-template'); 
    }

    public function store()
    {       
        $invoice = Invoice::store(request());
        return $invoice;
    }

    public function update($id)
    {
        $invoice = Invoice::findOrFail($id);

        request()->validate([
            'billed_date' => 'required|date',
            'due_date' => 'required|date'
        ]);

        $invoice->billed_date = request()->billed_date;
        $invoice->due_date = request()->due_date;
        $invoice->notes = request()->notes;
        
        $invoice->save();

        return $invoice;
    }

    public function delete($id)
    {       
        $invoice = Invoice::findOrFail($id);
        return $invoice->destroy($id);
    }


}
