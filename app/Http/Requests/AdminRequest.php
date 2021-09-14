<?php

namespace App\Http\Requests;

use App\Http\Requests\UserRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
        $user = (new UserRequest())->rules();
        $profile = (new ProfileRequest())->rules();
        return array_merge($user, $profile);
    }
}
