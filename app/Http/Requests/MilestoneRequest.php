<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class MilestoneRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'end_at' => 'required_with:started_at|after:started_at',
            'status' => 'required',
            'days' => 'required_without:started_at',
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'end_at' => 'end at',
            'started_at' => 'started at',
        ];
    }

    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $errors   = [];
        $messages = $validator->getMessageBag();

        foreach ($messages->keys() as $key) {
            $errors['message'] = $messages->get($key, $this->messageFormat)[0];
        }
        
        throw new HttpResponseException(response()->json($errors, 422));
    }
}
