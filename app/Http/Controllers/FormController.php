<?php

namespace App\Http\Controllers;

use App\Form;
use App\Policies\FormPolicy;
use Illuminate\Http\Request;
use Cviebrock\EloquentSluggable\Services\SlugService;
use App\Repositories\FormRepository;

class FormController extends Controller
{
    protected $formRepo;
    protected $paginate = 12;

    public function __construct(FormRepository $formRepo)
    {
        $this->formRepo = $formRepo;
        $this->paginate = request()->has('per_page') ? request()->per_page : 12;
    }

    public function index()
    {
        $company = auth()->user()->company();

        return $this->formRepo->getCompanyForms($company);
    }

    public function list()
    {
        $company = auth()->user()->company();

        return $this->formRepo->getCompanyFormsList($company);
    }

    public function form($id)
    {
        $company = auth()->user()->company();

        return $company->forms()->withCount('responses')->with('company')->findOrFail($id);
    }

    public function formBySlug($slug)
    {
        $form = Form::with('company')->where('slug', request()->slug)->firstOrfail();

        return $form;
    }
    

    public function delete($id)
    {
        $company = auth()->user()->company();

        $form = $company->forms()->findOrFail($id);

        (new FormPolicy())->delete($form);

        $form->delete();

        return response()->json(['message' => 'Successfully deleted'], 200);
    }


    public function update()
    {
        request()->validate([
                'questions' => 'required|array',
                'title' => 'required|string',
                'id' => 'required|exists:forms,id'
            ]);
        $form = auth()->user()->company()->forms()->findOrFail(request()->id);

        (new FormPolicy())->update($form);

        $form->questions = request()->questions;
        $form->title = request()->title;
        $form->status = request()->status ?? 'active';
        $form->save();

        return $form;
    }

    public function store()
    {
        request()->validate([
                'questions' => 'required|array',
                'title' => 'required|string'
            ]);

        (new FormPolicy())->create();

        $user = auth()->user();
        $form = $user->forms()->create([
                'company_id' => $user->company()->id,
                'questions' => request()->questions,
                'title' => request()->title,
                'status' => 'active'
            ]);

        return $form;
    }


    public function sendForm()
    {
        request()->validate([
            'to_emails' => 'required|array',
            'subject' => 'required|string',
            'message' => 'required|string',
            'item_id' => 'required|exists:forms,id'
        ]);

        $form = Form::findOrFail(request()->item_id);
        $tos = request()->to_emails;
        $subject = request()->subject;

        $sents = \Mail::send('email.send-email-form', ['content' => request()->message ], function($message) use ($tos, $subject) {    
            $company = auth()->user()->company();
            $message->from(auth()->user()->email, $company->name);
            $message->to($tos)->subject($subject);    
        });

        $failed = \Mail:: failures();
        
        if (empty($failed)) {
            $form->sents()->create([
                    'user_id' => auth()->user()->id,
                    'props' => [
                            'destinations' => $tos
                        ]
                ]);
        }

        return response()->json(['failed' => $failed ], 200);
    }

    public function saveFormResponse($id)
    {
        request()->validate(['data' => 'required|array']);

        $form = Form::findOrFail($id);

        if ($form->status != 'active') {
            abort(404, 'Form submission is no longer permitted');
        }

        $formResponse = $form->responses()->create([
                'data' => request()->data,
                'ip_address' => request()->ip(),
                'user_id' => request()->user_id ?? null
            ]);

        $url = config('app.frontend_url').'/dashboard/forms/'.$form->id.'/responses';
        \Mail::send('email.received-form-response', ['form_name' => $form->title ,'url' => $url ], 
                function($message) use ($form) { 
                    $message->from(config('mail.from.address', 'admin@dashcards.com'), config('mail.from.name', 'Buzzooka CRM'));
                    $message->to($form->user->email)->subject($form->title." response");    
                });

        return $formResponse;
    }

    public function formResponses($id)
    {
        $form = Form::findOrFail($id);

        $responses = $form->responses()->with('user')->latest()->paginate($this->paginate);
        
        return $responses;
    }
}

    
