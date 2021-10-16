<?php

namespace App\Http\Requests;

use App\Rules\RequiredIfExist;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'firstname' => "required|string",
            'lastname' => "required|string",
            'phone_number' => "required|string",
        ];
    }

    public function messages()
    {
        return [
            'is_owner_id.required' => "Is_owner is required"
        ];
    }
}
