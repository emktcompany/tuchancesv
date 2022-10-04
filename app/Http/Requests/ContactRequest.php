<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
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
            'name'    => ['required', 'string'],
            'message' => ['required', 'string'],
            'email'   => ['required', 'string', 'email'],
            'phone'   => ['required', 'string', 'digits:8'],
        ];
    }
}
