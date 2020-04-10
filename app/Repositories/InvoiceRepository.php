<?php

namespace App\Repositories;

use App\Invoice;
use App\User;

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
}