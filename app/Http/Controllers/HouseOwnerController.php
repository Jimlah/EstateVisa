<?php

namespace App\Http\Controllers;

use App\Models\HouseOwner;
use Illuminate\Http\Request;
use App\Http\Resources\HouseOwnerCollection;
use App\Http\Resources\HouseOwnerResource;

class HouseOwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $houseOwner = HouseOwner::with(['house', 'user', 'user.profile'])->paginate(10);

        return $this->response_data(new HouseOwnerCollection($houseOwner));
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
     * @param  \App\Models\HouseOwner  $houseOwner
     * @return \Illuminate\Http\Response
     */
    public function show(HouseOwner $houseOwner)
    {
        return $this->response_data(HouseOwnerResource::make($houseOwner));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HouseOwner  $houseOwner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HouseOwner $houseOwner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HouseOwner  $houseOwner
     * @return \Illuminate\Http\Response
     */
    public function destroy(HouseOwner $houseOwner)
    {
        //
    }
}
