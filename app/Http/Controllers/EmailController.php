<?php

namespace App\Http\Controllers;


use App\EmailLog;
use App\Mail\DynamicEmail;
use App\Traits\TemplateTrait;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    use TemplateTrait;
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmail()
    {
        request()->validate([
            'to' => 'required',
            'subject' => 'required|string',
            'message' => 'required|string'
        ]);

        Mail::to(request()->to)->send(new DynamicEmail(request()->message, request()->subject, auth()->user()));

        return response()->json(['message' => 'Success'], 200);
    }

    /**
     * @return array
     */
    public function coreTemplates()
    {
        return $this->emailTemplates();
    }

    public function emailLogs()
    {
        return EmailLog::orderBy('id', 'desc')->paginate(25);
    }
}