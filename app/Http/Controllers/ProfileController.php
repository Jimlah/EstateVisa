<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Estate;
use App\Models\Profile;
use App\Models\User;
use App\Models\UsersHouse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProfileResource::collection(Profile::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileRequest $request)
    {
        $user = User::create([
            'email' => $request->input('email'),
            'role_id' => $request->input('role_id'),
        ]);

        $profile = Profile::create([
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'phone_number' => $request->input('phone_number'),
            'gender' => $request->input('gender'),
            'user_id' => $user->id,
        ]);

        if ($request->input('estate_name')) {
            $estate = Estate::create([
                'name' => $request->input('estate_name'),
                'code' => $request->input('estate_code'),
            ]);
        }

        if ($request->input('house_id')) {
            UsersHouse::create([
                'user_id' => $user->id,
                'house_id' => $request->input('house_id'),
            ]);
        }

        $profile->estate_id = $estate->id ?? auth()->user()->profile->estate_id;
        $profile->save();

        return response()->json([
            'status' => 'success',
            'message' => 'New Account Created, login details Sent to user mail'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        //
    }
}
