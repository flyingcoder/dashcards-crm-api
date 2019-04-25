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

        $slug = SlugService::createSlug(Form::class, 'slug', $service->name.' details');

        $form = $service->forms()->create([
            'questions' => collect(request()->fields),
            'user_id' => auth()->user()->id,
            'title' => $service->name.' details',
            'status' => 'active',
            'slug' => $slug
        ]);

        $form->fields = collect(request()->fields);

        return $form;
    }

    public function getProjectDetails($id)
    {
        $service = Service::findOrFail($id);

        return $service->forms;
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

    
