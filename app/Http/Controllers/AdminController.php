<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Actions\StoreUserAction;
use App\Actions\StoreProfileAction;
use App\Http\Requests\AdminRequest;
use App\Http\Resources\AdminResource;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = Admin::all();

        return $this->response_data(AdminResource::collection($admins));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminRequest $request, StoreUserAction $storeUserAction, StoreProfileAction $storeProfileAction)
    {
        $user = $storeUserAction->execute($request);
        $profile = $storeProfileAction->execute($request, $user);

        $user->admin()->save(new Admin());

        return $this->response_success("Admin successfully created");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        return $this->response_data(AdminResource::make($admin));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(AdminRequest $request, Admin $admin, StoreUserAction $storeUserAction, StoreProfileAction $storeProfileAction)
    {
        $storeUserAction->update($request, $admin->user);
        $storeProfileAction->update($request, $admin->user->profile);

        return $this->response_success("Admin successfully updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        $admin->user->profile->delete();
        $admin->user->delete();
        $admin->delete();

        return $this->response_success("Admin successfully deleted");
    }
}
