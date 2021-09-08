<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;

class StoreProfileAction
{
    public function execute(Request $request, User $user)
    {
        resolve(ProfileRequest::class);
        $profile = Profile::create([
            'user_id' => $user->id,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'phone_number' => $request->phone_number,
        ]);

        return $profile;
    }

    public function update(Request $request, Profile $profile)
    {
        $profile->firstname = $request->firstname;
        $profile->lastname = $request->lastname;
        $profile->phone_number = $request->phone_number;
        $profile->save();

        return $profile;
    }
}