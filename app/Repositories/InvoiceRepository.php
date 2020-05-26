<?php

namespace App\Repositories;

use App\Invoice;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Konekt\PdfInvoice\InvoicePrinter;

class InvoiceRepository
{
	public function totalInvoices(User $user, $type = 'all')
	{	
		if ($type === 'all') {
			return $user->allInvoices()->sum('total_amount');
		} elseif ($type === 'billed_from') {
			return $user->billedFromInvoices()->sum('total_amount');
		} elseif ($type === 'billed_to') {
			return $user->billedToInvoices()->sum('total_amount');
		}
		return 0;
	}

	public function countInvoices(User $user, $type = 'all')
	{	
		if ($type === 'all') {
			return $user->allInvoices()->count();
		} elseif ($type === 'billed_from') {
			return $user->billedFromInvoices()->count();
		} elseif ($type === 'billed_to') {
			return $user->billedToInvoices()->count();
		}
		return 0;
	}

	public function totalMonthlyClientInvoices($group, $month = null, $year = null)
	{
		$month = is_null($month) ? date('m') : $month;
		$year = is_null($year) ? date('Y') : $year;
		$user_ids = $group->teamMembers()->with('user')
				->whereHas('user', function($query) {
					$query->whereNull('deleted_at');
				})->pluck('user_id');

        if (empty($user_ids)) {
        	return 0;
        }
        $total = Invoice::whereIn('billed_to', $user_ids)
        			->whereMonth('due_date', $month)
        			->whereYear('due_date', $year)
        			->sum('total_amount');
        			//todo filter base on status
       return $total;
	}

	public function getClientInvoices(User $client, $request)
	{
		list($sortName, $sortValue) = parseSearchParam($request);

        $invoices = $client->company()->invoices();

        $invoices = $invoices->where(function($query) use($client) {
        		$query->where('invoices.billed_to', $client->id)
        			->orWhere('invoices.billed_from', $client->id)
        			->orWhere('invoices.user_id', $client->id);
	        });

        if($request->has('sort') && !empty(request()->sort))
            $invoices->orderBy($sortName, $sortValue);

        if(request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        $data = $invoices->paginate(20);

        $data->map(function ($invoice) {
            $items = collect(json_decode($invoice->items));
            unset($invoice->items);
            $invoice->items = $items;
            $invoice->billedTo = User::where('id', $invoice->billed_to)->first();
            $invoice->billedFrom = User::where('id', $invoice->billed_from)->first();
        });

        return $data;
	}

	public function generatePDF(Invoice $invoice, $type = 'Invoice')
	{
		$printer = new InvoicePrinter();
          /* Header Settings */
        if ($invoice->company_logo && $invoice->company_logo != 'null') {
        	$printer->setLogo(str_replace(config('app.url').'/', '', $invoice->company_logo));
        }
        $printer->setColor("#3b589e");
        $printer->setType($type);
        $printer->setReference("#INV-".$invoice->id);
        $printer->setDate(Carbon::createFromFormat('Y-m-d', $invoice->date)->toFormattedDateString());
        $printer->setDue(Carbon::createFromFormat('Y-m-d', $invoice->due_date)->toFormattedDateString());
        $printer->setFrom(array($invoice->billedFrom->fullname, $invoice->billedFrom->telephone->formatInternational ?? 'none' ,$invoice->billedFrom->company()->name));
        $printer->setTo(array($invoice->billedTo->fullname, $invoice->billedFrom->telephone->formatInternational ?? 'none' ,$invoice->billedTo->getMeta('company_name')));

        $items = json_decode($invoice->items);
        $total = 0;
        foreach ($items as $key => $item) {
	        $printer->addItem($item->descriptions, '', $item->hours, false, $item->rate, false, $item->amount);
        	$total += $item->amount;
        }
        /* Add totals */
        $printer->addTotal("Tax", $invoice->tax);
        $printer->addTotal("Discount", $invoice->discount);
        $printer->addTotal("Shipping", $invoice->shipping);
        $printer->addTotal("Total due", $invoice->total_amount,true);
        /* Set badge */ 
        $printer->addBadge("Invoice Copy");
        $printer->addTitle("Notes");
        $printer->addParagraph($invoice->notes ?? 'none');
        $printer->addTitle("Terms");
        $printer->addParagraph($invoice->terms ?? 'none');

   		$printer->setFooternote($invoice->billedFrom->company()->name.' via '.config('app.name'));

        $folder = "invoices/".date('Y/m').'/'.$invoice->id."/";
        File::makeDirectory($folder, $mode = 0777, true, true);
        $location = $folder.str_slug($invoice->title, '-').'.pdf';
        $printer->render($location, 'F');

        return url($location);
	}
}