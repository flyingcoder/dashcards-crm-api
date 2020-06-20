<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerificationController extends Controller
{
	public function isBelongToCompany()
	{

		request()->validate([
			'type' => 'required|string',
			'id' => 'required|numeric'
		]);
		$value = false;

		if(request()->type == 'user') {
			$value = auth()->user()->company()->members()->where('users.id', request()->id)->exists();
		} elseif (request()->type == 'project') {
			$value = auth()->user()->company()->companyProjects()->where('projects.id', request()->id)->exists();
		} elseif (request()->type == 'service') {
			$value = auth()->user()->company()->services()->where('services.id', request()->id)->exists();
		}

		
		return response()->json(['belong' => $value], 200);
	}
}