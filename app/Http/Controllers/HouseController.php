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

    public function __construct()
    {
        $this->authorizeResource(House::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json( ["data" => HouseResource::collection(House::all())] );
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
            'houses_types_id' => $request->input('house_type'),
            'code' => $request->input('code'),
            'description' => $request->input('description')
        ]);

        return response()->json([
            'status' => "error",
            'message' => 'You have successfully added a new house to the estate'
        ], 201);
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
            'status' => 'success',
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
            'status' => 'success',
            'message' => 'You have successfully deleted house from the estate'
        ]);
    }
}