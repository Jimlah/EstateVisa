<?php

namespace App\Http\Controllers;

use App\Http\Requests\HouseTypeRequest;
use App\Http\Resources\EstateResource;
use App\Http\Resources\HouseTypeResource;
use App\Models\HouseType;
use Illuminate\Http\Request;

class HouseTypeController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(HouseType::class);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $houseTypes = HouseType::estateOnly()->get();

        return $this->response_data(HouseTypeResource::collection($houseTypes));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HouseTypeRequest $request)
    {
        HouseType::create([
            'name' => $request->name,
            'estate_id' => auth()->user()->estate_admin[0]->estate_id
        ]);

        return $this->response_success('House type created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HouseType  $houseType
     * @return \Illuminate\Http\Response
     */
    public function show(HouseType $houseType)
    {
        return $this->response_data(new HouseTypeResource($houseType));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HouseType  $houseType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HouseType $houseType)
    {
        $houseType->update([
            'name' => $request->name
        ]);

        return $this->response_success('House type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HouseType  $houseType
     * @return \Illuminate\Http\Response
     */
    public function destroy(HouseType $houseType)
    {
        $houseType->delete();

        return $this->response_success('House type deleted successfully');
    }
}
