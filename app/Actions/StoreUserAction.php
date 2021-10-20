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
        $user = User::firstOrCreate([
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return $user;
    }


    public function update(Request $request, User $user)
    {
        $user->update([
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return $user;
    }
}
