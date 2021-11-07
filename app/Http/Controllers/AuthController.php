<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserLoginRequest;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{



    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:6|confirmed',
    //     ]);

    //     if ($validator->fails()) {
    //         return response(['errors' => $validator->errors()->all()], 422);
    //     }

    //     $request['password'] = Hash::make($request['password']);
    //     $request['remember_token'] = Str::random(10);
    //     $user = User::create($request->toArray());
    //     $token = $user->createToken('Laravel Password Grant Client')->accessToken;
    //     $response = ['token' => $token];
    //     return response($response, 200);
    // }

    /**
     * login
     *
     * @param UserLoginRequest $request
     * @return
     */
    public function login(UserLoginRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember_me)) {
            $token = auth()->user()->createToken('Application')->accessToken;
            $response = ['token' => $token, 'user' => UserResource::make(auth()->user()), 'role' => auth()->user()->roles(), "message" => "Login Successful", "status" => "success"];
            return response()->json($response, 200);
        }


        $response = ["message" => 'Email or Password Invalid', 'status' => 'error'];

        return response()->json($response, 200);
    }

    public function logout(Request $request)
    {
        $token = auth('api')->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!', 'status' => 'success'];
        return response()->json($response, 200);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset link sent to your email.', 'status' => "success"], 201)
            : response()->json(['message' => 'Unable to send reset link', 'status' => "error"], 401);
    }

    public function passwordReset(Request $request)
    {
        return $request->query('token');
    }
}
