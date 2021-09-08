<?php

namespace App\Http\Requests;

use App\Models\Estate;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        // try to except the row from the email search
        return [
            'email' => [
                'required',
                'email',
            ],
        ];
    }
}