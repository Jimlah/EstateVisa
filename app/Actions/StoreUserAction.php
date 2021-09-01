<?php
namespace App\Actions;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Auth\Events\Registered;

class StoreUserAction
{
    public function execute(Request $request)
    {
        resolve(UserRequest::class);
        $user = User::firstOrCreate([
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return $user;
    }
}