<?php

namespace App\Http\Controllers;

use App\Actions\StoreProfileAction;
use App\Actions\StoreUserAction;
use App\Http\Resources\HouseCollection;
use App\Http\Resources\HouseResource;
use App\Models\House;
use Illuminate\Http\Request;

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $houses = House::with('owner')->estateHouses()->paginate(10);

        return $this->response_data(new HouseCollection($houses));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $estate = auth()->user()->estate->first();

        $house = House::create($request->all());
        $house->estate()->associate($estate);

        $house->save();

        return $this->response_success("House Created Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\House  $house
     * @return \Illuminate\Http\Response
     */
    public function show(House $house)
    {
        return $this->response_data(HouseResource::make($house->with('houseUsers', 'owner', 'members', 'owner.user', 'owner.user.profile', 'members.user', 'members.user.profile')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\House  $house
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, House $house)
    {
        $house->update($request->all());

        return $this->response_success('User attached to House successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\House  $house
     * @return \Illuminate\Http\Response
     */
    public function destroy(House $house)
    {
        $house->delete();

        return $this->response_success('User detached from House successfully');
    }
}
