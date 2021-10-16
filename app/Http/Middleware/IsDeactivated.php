<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class IsDeactivated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        // if ($request->user() !== null) {
        //     $user = User::find($request->user()->id);
        //     if ($user->hasIs_owner(User::ESTATE_SUPER_ADMIN)) {
        //         if($user->estate->status === User::DEACTIVATED) {
        //             return response()->json([
        //                 'message' => 'Your account has been deactivated. Please contact your administration for more
        //                 information.',
        //                 'status' => 'error'
        //             ], 422);
        //         }
        //     }
        // }
        return $response;
    }
}
