<?php

namespace App\Http\Controllers;

use App\Http\Requests\EstateHouseRequest;
use App\Http\Resources\EstateHouseCollection;
use App\Http\Resources\EstateHouseResource;
use App\Models\Estate;
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
        $estateHouses = House::with(['estate', 'houseType', 'user'])
            ->estateHouses()
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
            'house_type_id' => $request->house_type_id,
            'estate_id' => $request->estate ?? auth()->user()->estate->first->id
        ]);


        return $this->response_success("New House Created");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EstateHouse  $estateHouse
     * @return \Illuminate\Http\Response
     */
    public function show(House $house)
    {
        $house = $house->with(['estate', 'houseType', 'user', "user.profile"])->get();
        return $this->response_data(new EstateHouseResource($house));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EstateHouse  $estateHouse
     * @return \Illuminate\Http\Response
     */
    public function update(EstateHouseRequest $request, House $house)
    {
        $house->update($request->only(
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
    public function destroy(House $house)
    {
        $house->delete();

        return $this->response_success("House Deleted");
    }
}
