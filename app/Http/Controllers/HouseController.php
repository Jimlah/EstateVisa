<?php

namespace App\Http\Controllers;

use App\Http\Requests\HouseRequest;
use App\Http\Resources\HouseResource;
use App\Models\Estate;
use App\Models\House;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(House::all());
        // return HouseResource::collection(Estate::all()->load("houses"));
        return Estate::all()->load("houses");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HouseRequest $request)
    {
        House::create([
            'estate_id' => $request->input('estate'),
            'houses_types_id' => $request->input('house_type'),
            'code' => $request->input('code')
        ]);

        return response()->json([
            'error' => true,
            'message' => 'You have successfully added a new house to the estate'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\House  $house
     * @return \Illuminate\Http\Response
     */
    public function show(House $house)
    {
        return HouseResource::make($house);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\House  $house
     * @return \Illuminate\Http\Response
     */
    public function update(HouseRequest $request, House $house)
    {
        $house->estate_id = $request->estate;
        $house->houses_types_id = $request->house_type;
        $house->code = $request->code;
        $house->description = $request->description;
        $house->save();

        return response()->json([
            'error' => true,
            'message' => 'You have successfully  updated the house'
        ]);
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

        return response()->json([
            'error' => true,
            'message' => 'You have successfully deleted house from the estate'
        ]);
    }
}