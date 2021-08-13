<?php

namespace App\Http\Controllers;

use App\Models\Estate;
use App\Models\House;
use App\Models\House_type;
use App\Models\User;
use Illuminate\Http\Request;

class HouseTypeController extends Controller
{
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\House_type  $house_type
     * @return \Illuminate\Http\Response
     */
    public function show(House_type $house_type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\House_type  $house_type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, House_type $house_type)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\House_type  $house_type
     * @return \Illuminate\Http\Response
     */
    public function destroy(House_type $house_type)
    {
        //
    }
}