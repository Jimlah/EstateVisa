<?php

namespace App\Http\Controllers;

use App\Http\Requests\EstateRequest;
use App\Http\Resources\EstateResource;
use App\Models\User;
use App\Models\Estate;
use Illuminate\Http\Request;

class EstateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EstateResource::collection(Estate::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EstateRequest $request)
    {
        $userInstance = new UserController();
        $user = $userInstance->store($request);

        $estate = Estate::create([
            'user_id' => $user->id,
            'name' => $request->input('estate_name'),
            'code' => $request->input('estate_code'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully created a new Estate'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Estate  $estate
     * @return \Illuminate\Http\Response
     */
    public function show(Estate $estate)
    {
        return EstateResource::make($estate);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Estate  $estate
     * @return \Illuminate\Http\Response
     */
    public function update(EstateRequest $request, Estate $estate)
    {
        $estate->name = $request->estate_name;
        $estate->code = $request->estate_code;
        $estate->address = $request->estate_address;
        $estate->logo = $request->estate_logo;

        $estate->save();

        return response()->json([
            'status' => "success",
            'message' => 'You have successfully updated Resource'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estate  $estate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Estate $estate)
    {
        $estate->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully deleted'
        ]);
    }
}