<?php

namespace App\Http\Requests\Admin\Enrollments;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->hasAnyRole([
            'admin', 'bidder',
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $rules = [
            'is_accepted'  => ['required', 'boolean'],
            'is_fullfiled' => ['required', 'boolean'],
        ];

        return $rules;
    }
}
