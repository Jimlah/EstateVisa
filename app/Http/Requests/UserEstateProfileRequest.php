<?php

namespace App\Http\Requests;

use App\Http\Requests\UserRequest;
use App\Http\Requests\EstateRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Foundation\Http\FormRequest;

class UserEstateProfileRequest extends FormRequest
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
        $estate = (new EstateRequest())->rules();
        $user = (new UserRequest())->rules();
        $profile = (new ProfileRequest())->rules();
        $custom = [
            'email' => 'bail|unique:users,email'
        ];
        return array_merge($estate, $user, $profile, $custom);
    }
}
