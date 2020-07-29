<?php

namespace App\Repositories;

use App\Company;
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
    public function totalInvoices(User $user, $type = 'all', $status = null)
    {
        if ($type === 'all') {
            $invoice = $user->allInvoices();
        } elseif ($type === 'billed_from') {
            $invoice = $user->billedFromInvoices();
        } elseif ($type === 'billed_to') {
            $invoice = $user->billedToInvoices();
        }
        if (!is_null($status)) {
            $invoice->where('status', strtolower($status));
        }
        return $invoice->sum('total_amount');
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
     * @return mixed
     */
    public function getClientInvoices(User $client)
    {
        list($sortName, $sortValue) = parseSearchParam(request());

        $invoices = Invoice::with(['billedTo', 'billedFrom'])
            ->where(function ($query) use ($client) {
                $query->where('invoices.billed_to', $client->id)
                    ->orWhere('invoices.billed_from', $client->id)
                    ->orWhere('invoices.user_id', $client->id);
            });

        if (request()->has('status') && request()->status != 'all') {
            $invoices->where('status', request()->status);
        }

        if (request()->has('sort') && !empty(request()->sort))
            $invoices->orderBy($sortName, $sortValue);
        else
            $invoices->latest();

        return $invoices->paginate(request()->per_page ?? 20);
    }

    /**
     * @param $object //Project || Campaign
     * @return mixed
     */
    public function getProjectCampaignInvoices($object)
    {
        list($sortName, $sortValue) = parseSearchParam(request());

        $invoices = Invoice::with(['billedTo', 'billedFrom'])
            ->where('project_id', $object->id);

        if (request()->has('status') && request()->status != 'all') {
            $invoices->where('status', request()->status);
        }

        if (request()->has('sort') && !empty(request()->sort))
            $invoices->orderBy($sortName, $sortValue);
        else
            $invoices->latest();

        return $invoices->paginate(request()->per_page ?? 20);
    }
    /**
     * @param Company $company
     * @return mixed
     */
    public function getCompanyInvoices(Company $company)
    {
        list($sortName, $sortValue) = parseSearchParam(request());
        $members_id = $company->membersID();
        $invoices = Invoice::with(['billedTo', 'billedFrom'])
            ->where(function ($query) use ($members_id) {
                $query->whereIn('invoices.billed_to', $members_id)
                    ->orWhereIn('invoices.billed_from', $members_id)
                    ->orWhereIn('invoices.user_id', $members_id);
            });

        if (request()->has('status') && request()->status != 'all') {
            $invoices->where('status', request()->status);
        }

        if (request()->has('sort') && !empty(request()->sort))
            $invoices->orderBy($sortName, $sortValue);
        else
            $invoices->latest();

        return $invoices->paginate(request()->per_page ?? 20);
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