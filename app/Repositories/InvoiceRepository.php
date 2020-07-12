<?php

namespace App\Repositories;

use App\Invoice;
use App\User;
use Illuminate\Support\Facades\File;
use PDF;

class InvoiceRepository
{
    /**
     * @param User $user
     * @param string $type
     * @return int|mixed
     */
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

    /**
     * @param User $user
     * @param string $type
     * @return int
     */
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

    /**
     * @param $group
     * @param null $month
     * @param null $year
     * @return int
     */
    public function totalMonthlyClientInvoices($group, $month = null, $year = null)
    {
        $month = is_null($month) ? date('m') : $month;
        $year = is_null($year) ? date('Y') : $year;
        $user_ids = $group->teamMembers()->with('user')
            ->whereHas('user', function ($query) {
                $query->whereNull('deleted_at');
            })->pluck('user_id');

        if (empty($user_ids)) {
            return 0;
        }
        //todo filter base on status
        return Invoice::whereIn('billed_to', $user_ids)
            ->whereMonth('due_date', $month)
            ->whereYear('due_date', $year)
            ->sum('total_amount');
    }

    /**
     * @param User $client
     * @param $request
     * @return mixed
     */
    public function getClientInvoices(User $client, $request)
    {
        list($sortName, $sortValue) = parseSearchParam($request);

        $invoices = $client->company()->invoices();

        $invoices = $invoices->where(function ($query) use ($client) {
            $query->where('invoices.billed_to', $client->id)
                ->orWhere('invoices.billed_from', $client->id)
                ->orWhere('invoices.user_id', $client->id);
        });

        if ($request->has('sort') && !empty(request()->sort))
            $invoices->orderBy($sortName, $sortValue);

        if (request()->has('per_page') && is_numeric(request()->per_page))
            $this->paginate = request()->per_page;

        $data = $invoices->paginate(20);

        $data->map(function ($invoice) {
            $items = collect(json_decode($invoice->items, true));
            unset($invoice->items);
            $invoice->items = $items;
            $props = collect(json_decode($invoice->props, true));
            unset($invoice->props);
            $invoice->props = $props;
            $invoice->billedTo = User::where('id', $invoice->billed_to)->first();
            $invoice->billedFrom = User::where('id', $invoice->billed_from)->first();
        });

        return $data;
    }

    /**
     * @param Invoice $invoice
     * @param string $html_template
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function generatePDF(Invoice $invoice, $html_template = "")
    {
        $folder = "invoices/" . $invoice->id . '/';
        File::makeDirectory($folder, $mode = 0777, true, true);
        $location = $folder . str_slug($invoice->title, '-') . '.pdf';

        $pdf = PDF::loadHTML($html_template);
        $pdf->save($location);

        return url($location);
    }
}