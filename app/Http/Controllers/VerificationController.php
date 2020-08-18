<?php

namespace App\Http\Controllers;

class VerificationController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
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