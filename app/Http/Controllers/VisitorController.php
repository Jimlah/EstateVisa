<?php

namespace App\Http\Controllers;

use App\Models\visitor;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = ['message'=>"Index function"];
        return response([$response, 200]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = ['message'=>"Store function"];
        return response([$response, 200]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function show(visitor $visitor)
    {
        $response = ['message'=>"show function"];
        return response([$response, 200]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, visitor $visitor)
    {
        $response = ['message' => "update function"];
        return response([$response, 200]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function destroy(visitor $visitor)
    {
        $response = ['message'=> "destroy function"];
        return response([$response, 200]);
    }
}
