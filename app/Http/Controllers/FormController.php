<?php

namespace App\Http\Controllers;

use App\Events\QuestionnaireResponse;
use App\Form;
use App\Policies\FormPolicy;
use App\Repositories\FormRepository;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class FormController extends Controller
{
    protected $formRepo;
    protected $paginate = 12;

    /**
     * FormController constructor.
     * @param FormRepository $formRepo
     */
    public function __construct(FormRepository $formRepo)
    {
        $this->formRepo = $formRepo;
        $this->paginate = request()->has('per_page') ? request()->per_page : 12;
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function index()
    {
        $company = auth()->user()->company();

        return $this->formRepo->getCompanyForms($company);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function list()
    {
        $company = auth()->user()->company();

        return $this->formRepo->getCompanyFormsList($company);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function form($id)
    {
        $company = auth()->user()->company();

        return $company->forms()->withCount('responses')->with('company')->findOrFail($id);
    }

    /**
     * @param $slug
     * @return Form|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function formBySlug($slug)
    {
        return Form::with('company')->where('slug', request()->slug)->firstOrfail();
    }


    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $company = auth()->user()->company();

        $form = $company->forms()->findOrFail($id);

        (new FormPolicy())->delete($form);

        $form->delete();

        return response()->json(['message' => 'Successfully deleted'], 200);
    }


    /**
     * @return mixed
     */
    public function update()
    {
        request()->validate([
                'questions' => 'required|array',
                'title' => 'required|string',
                'id' => 'required|exists:forms,id',
                'notif_email_receivers' => 'sometimes|array'
            ]);
        $form = auth()->user()->company()->forms()->findOrFail(request()->id);

        (new FormPolicy())->update($form);

        $form->questions = request()->questions;
        $form->title = request()->title;
        $form->status = request()->status ?? 'active';
        $props = $form->props;
        $props['notif_email_receivers'] = request()->notif_email_receivers ?? [];
        $form->props = $props;
        $form->save();

        return $form;
    }

    /**
     * @return mixed
     */
    public function store()
    {
        request()->validate([
                'questions' => 'required|array',
                'title' => 'required|string',
                'notif_email_receivers' => 'sometimes|array'
            ]);

        (new FormPolicy())->create();

        $user = auth()->user();
        return $user->forms()->create([
                'company_id' => $user->company()->id,
                'questions' => request()->questions,
                'title' => request()->title,
                'status' => request()->status,
                'slug' => uniqUuidFrom(),
                'props' => [
                        'notif_email_receivers' => request()->notif_email_receivers ?? []
                    ]
            ]);
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
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

        Mail::send('email.send-email-form', ['content' => request()->message ], function($message) use ($tos, $subject) {
            $company = auth()->user()->company();
            $message->from(auth()->user()->email, $company->name);
            $message->to($tos)->subject($subject);
        });

        $failed = Mail:: failures();
        
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

        event(new QuestionnaireResponse($formResponse));

        return $formResponse;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function formResponses($id)
    {
        $form = Form::findOrFail($id);

        return $form->responses()->with('user')->latest()->paginate($this->paginate);
    }
}

    
