<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobOfferRequest extends FormRequest
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
            'title' => 'required|string|max:50',
            'occupation_id' => 'required|exists:occupations,id',
            'due_date' => 'required|after:yesterday',
            'description' => 'required|string|max:2000',
            'status' => 'nullable|boolean',
        ];
    }
}
