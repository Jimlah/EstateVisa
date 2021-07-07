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
            'email' => "required|string|unique:users,email",
            'phone_number' => "required|string",
            'role_id' => "required",
            'estate_name' => "required_if:role_id,2",
            'house_id' => "required_if:role_id,4",
            'estate_code' => "required_with:estate_name|unique:estates,code",
        ];
    }

    public function messages()
    {
        return [
            'role_id.required' => "Role is required"
        ];
    }
}
