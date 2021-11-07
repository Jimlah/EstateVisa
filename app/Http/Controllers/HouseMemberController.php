<?php

namespace App\Http\Controllers;

use App\Actions\StoreProfileAction;
use App\Actions\StoreUserAction;
use App\Http\Requests\HouseMemberFormRequest;
use App\Models\House;
use App\Models\UserHouse;
use Illuminate\Http\Request;
use App\Http\Resources\UserHouseCollection;
use App\Http\Resources\UserHouseResource;

class HouseMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(House $house)
    {
        $members = $house->members()->paginate(10);

        return $this->response_data(new UserHouseCollection($members));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, House $house, StoreUserAction $storeUserAction, StoreProfileAction $storeProfileAction)
    {
        $user = $storeUserAction->execute($request);
        $storeProfileAction->execute($request, $user);

        $house->houseUsers()->create(
            [
                'user_id' => $user->id,
            ]
        );


        return $this->response_success('New Member added to your house');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(House $house, UserHouse $member)
    {
        $member = $house->members()->findOrFail($member->id);

        return $this->response_data(UserHouseResource::make($member));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HouseMemberFormRequest $request, House $house, UserHouse $member)
    {
        $member = $house->members()->findOrFail($member->id);

        $member->user->update($request->all());
        $member->user->profile->update($request->all());

        return $this->response_success('Member updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(House $house, UserHouse $member)
    {
        $member = $house->members()->findOrFail($member->id);

        $member->delete();

        return $this->response_success('Member deleted');
    }
}
