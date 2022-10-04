<?php

namespace App\Http\Requests\Admin\Categories;

class CreateRequest extends UpdateRequest
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();

        array_push($rules['image_attachment'], 'required');

        return $rules;
    }
}
