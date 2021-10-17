<?php

namespace App\Http\Controllers;

use App\Actions\StoreProfileAction;
use App\Actions\StoreUserAction;
use App\Http\Requests\HouseOwnerRequest;
use App\Http\Resources\UserHouseResource;
use App\Models\House;
use App\Models\UserHouse;
use Illuminate\Http\Request;

class HouseOwnerController extends Controller
{
    /**
     * Estate Admins Mange Attaching User to House Controller
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HouseOwnerRequest $request, House $house, StoreUserAction $storeUserAction, StoreProfileAction $storeProfileAction)
    {
        $user = $storeUserAction->execute($request);
        $storeProfileAction->execute($request, $user);

        UserHouse::create([
            'is_owner' => true,
            'user_id' => $user->id,
            'house_id' => $house->id,
        ]);

        return $this->response_success('House owner successfully added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(House $house, UserHouse $userHouse)
    {

        return $this->response_data(UserHouseResource::make($userHouse));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HouseOwnerRequest $request, House $house, UserHouse $userHouse)
    {
        $userHouse->update($request->validated());

        return $this->response_success('House owner successfully updated');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(House $house, UserHouse $userHouse)
    {
        $userHouse->delete();

        return $this->response_success('House owner successfully deleted');
    }
}
