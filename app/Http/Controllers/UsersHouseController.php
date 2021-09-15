<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UsersHouse;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\UsersHouseResource;
use Illuminate\Support\Facades\DB;

class UsersHouseController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(UsersHouse::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = UsersHouseResource::collection(UsersHouse::all());

        return response()->json(['data' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UsersHouse  $usersHouse
     * @return \Illuminate\Http\Response
     */
    public function show(UsersHouse $usersHouse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UsersHouse  $usersHouse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UsersHouse $usersHouse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UsersHouse  $usersHouse
     * @return \Illuminate\Http\Response
     */
    public function destroy(UsersHouse $usersHouse)
    {
        //
    }
}
