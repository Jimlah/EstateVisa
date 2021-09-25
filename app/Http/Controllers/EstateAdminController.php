<?php

namespace App\Http\Controllers;

use App\Actions\StoreProfileAction;
use App\Actions\StoreUserAction;
use App\Http\Requests\EstateAdminRequest;
use App\Http\Resources\EstateAdminResource;
use App\Models\Admin;
use App\Models\Estate;
use App\Models\EstateAdmin;
use Illuminate\Http\Request;

class EstateAdminController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(EstateAdmin::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $estateAdmin = EstateAdmin::with('user')->get();

        return $this->response_data(EstateAdminResource::collection($estateAdmin));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EstateAdminRequest $request, StoreUserAction $storeUserAction, StoreProfileAction $storeProfileAction)
    {
        $user = $storeUserAction->execute($request);
        $profile = $storeProfileAction->execute($request, $user);
        $estate = $request->user()->estate[0];;

        $user->estate()->attach($estate);

        return $this->response_success('Admin has been created for your estate');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EstateAdmin  $estateAdmin
     * @return \Illuminate\Http\Response
     */
    public function show(EstateAdmin $estateAdmin)
    {
        return $this->response_data(new EstateAdminResource($estateAdmin));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EstateAdmin  $estateAdmin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EstateAdmin $estateAdmin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EstateAdmin  $estateAdmin
     * @return \Illuminate\Http\Response
     */
    public function destroy(EstateAdmin $estateAdmin)
    {
        //
    }
}
