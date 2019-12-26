<?php

namespace App\Http\Controllers;

use App\Form;
use App\Service;
use App\Policies\FormPolicy;
use Illuminate\Http\Request;
use Cviebrock\EloquentSluggable\Services\SlugService;

class FormController extends Controller
{
// Questionnaires
    public function index()
    {
        $company = auth()->user()->company();

        if(!request()->ajax())
            return view('pages.questionnaire');

        return $company->paginatedCompanyForms(request());
    }

    public function projectDetails()
    {
        request()->validate([
            'service_id' => 'exists:services,id'
        ]);

        $service = Service::findOrFail(request()->service_id);

        $form = $service->forms()->first();

        if (!$form) {
            $slug = SlugService::createSlug(Form::class, 'slug', $service->name.' Extra Inputs');
            $form = $service->forms()->create([
                'questions' => collect(request()->fields),
                'user_id' => auth()->user()->id,
                'title' => $service->name.' Extra Inputs',
                'status' => 'active',
                'slug' => $slug
            ]);
        } else {
            $form->update([
                'questions' => collect(request()->fields)
            ]);
        }

        unset($form->questions);

        $form->fields = collect(request()->fields);

        return $form;
    }

    public function getProjectDetails($id)
    {
        $service = Service::findOrFail($id);

        $data = $service->forms()->first();

        if ($data && property_exists($data, 'questions')) {    
            $data->fields = json_decode($data->questions);
            unset($data->questions);
        }

        return $data;
    }

    public function save()
    {
        return view('pages.questionnaire-new');
    }

    public function store()
    {
        (new FormPolicy())->create();

        Form::store(request());
    }

    public function edit()
    {
        return view('pages.questionnaire-new');
    }

    public function load($slug)
    {
        $form = Form::where('slug', $slug)->first();
        dd($form);
        if(!request()->ajax())
            return view('questionnaire-load', ['questions' => $form->questions]);

        return Form::where('slug', $slug)->first();
    }

// Quotation
    public function quotations()
    {
        return view('pages.quotation');
    }

}

    
