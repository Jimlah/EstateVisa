<?php

namespace App\Http\Controllers;

use App\Http\Requests\EstateHouseRequest;
use App\Http\Resources\EstateHouseCollection;
use App\Http\Resources\EstateHouseResource;
use App\Models\EstateHouse;
use App\Models\House;

class EstateHouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $estateHouses = EstateHouse::estateOnly()
            ->with(['estate', 'house', 'house.houseType'])
            ->paginate(10);


        return $this->response_data(new EstateHouseCollection($estateHouses));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EstateHouseRequest $request)
    {
        $house = House::create([
            'name' => $request->name,
            'address' => $request->address,
            'description' => $request->description,
            'house_type_id' => $request->house_type_id
        ]);

        $house->estate()->attach(auth()->user()->estate[0]->id);


        return $this->response_success("New House Created");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EstateHouse  $estateHouse
     * @return \Illuminate\Http\Response
     */
    public function show(EstateHouse $estateHouse)
    {
        return $this->response_data(EstateHouseResource::make($estateHouse));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EstateHouse  $estateHouse
     * @return \Illuminate\Http\Response
     */
    public function update(EstateHouseRequest $request, EstateHouse $estateHouse)
    {
        $estateHouse->house->update($request->only(
            [
                'name',
                'address',
                'description',
                'house_type_id'
            ]
        ));


        return $this->response_success("House Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EstateHouse  $estateHouse
     * @return \Illuminate\Http\Response
     */
    public function destroy(EstateHouse $estateHouse)
    {
        $estateHouse->delete();

        return $this->response_success("House Deleted");
    }
}
