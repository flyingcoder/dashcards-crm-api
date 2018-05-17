<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'milestone_id' => 'required|integer|exists:milestones,id',
            'end_at' => 'required_with:started_at',
            'days' => 'required_without:started_at',
        ];
    }

    public function attributes()
    {
        return [
            'started_at' => 'started at',
            'milestone_id' => 'milestone',
            'end_at' => 'end at',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors   = [];
        $messages = $validator->getMessageBag();

        foreach ($messages->keys() as $key) {
            $errors[$key] = $messages->get($key, $this->messageFormat)[0];
        }
        throw new HttpResponseException(response()->json($errors, 422));
    }
}
