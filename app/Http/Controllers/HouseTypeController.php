<?php

namespace App\Http\Controllers;

use App\Http\Requests\HouseTypeRequest;
use App\Models\Estate;
use App\Models\House;
use App\Models\House_type;
use App\Models\User;
use Illuminate\Http\Request;

class HouseTypeController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(House_type::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(
            ['data' => House_type::all()]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HouseTypeRequest $request)
    {
        House_type::create([
            'name' => $request->name,
            'description' => $request->description,
            'code' => $request->code,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully created a new House Type'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\House_type  $house_type
     * @return \Illuminate\Http\Response
     */
    public function show(House_type $house_type)
    {
        return response()->json($house_type);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\House_type  $house_type
     * @return \Illuminate\Http\Response
     */
    public function update(HouseTypeRequest $request, House_type $house_type)
    {
        $house_type->name = $request->name;
        $house_type->code = $request->code;
        $house_type->description = $request->description;

        $house_type->save();

        return response()->json([
            'status' => 'success',
            'message' => 'House type updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\House_type  $house_type
     * @return \Illuminate\Http\Response
     */
    public function destroy(House_type $house_type)
    {
        $house_type->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'House type deleted successfully '
        ], 204);

    }
}