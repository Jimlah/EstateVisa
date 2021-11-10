<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserHouseCollection;
use App\Http\Resources\UserHouseResource;
use App\Models\UserHouse;
use Illuminate\Http\Request;

class UserHouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userHouses = UserHouse::userHouse()->with('house')->paginate(10);

        return $this->response_data(new UserHouseCollection($userHouses));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserHouse  $userHouse
     * @return \Illuminate\Http\Response
     */
    public function show(UserHouse $userHouse)
    {
        $userHouse = $userHouse->load(['user', 'house', 'house.members']);

        return $this->response_data(UserHouseResource::make($userHouse));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserHouse  $userHouse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserHouse $userHouse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserHouse  $userHouse
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserHouse $userHouse)
    {
        //
    }
}
