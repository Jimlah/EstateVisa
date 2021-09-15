<?php

namespace App\Http\Controllers;

use App\Actions\StoreProfileAction;
use App\Actions\StoreUserAction;
use App\Http\Requests\AdminRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Admin::class);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = Admin::all();
        return response()->json(['data' => AdminResource::collection($admins)]);
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
        Admin::create([
            'user_id' => $user->id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Admin created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        return response()->json(['data' => AdminResource::make($admin)]);
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
        $user = $storeUserAction->update($request, $admin->user);
        $profile = $storeProfileAction->update($request, $admin->user->profile);

        return response()->json([
            'status' => 'success',
            'message' => 'Admin updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Admin deleted successfully'
        ]);
    }

    public function deactivate(Admin $admin)
    {
        $admin->deactivated();

        return response()->json([
            'status' => 'success',
            'message' => 'Admin deactivated successfully'
        ]);
    }

    public function activate(Admin $admin)
    {
        $admin->activate();

        return response()->json([
            'status' => 'success',
            'message' => 'Admin activated successfully'
        ]);
    }

    public function suspend(Admin $admin)
    {
        $admin->suspended();

        return response()->json([
            'status' => 'success',
            'message' => 'Admin suspended successfully'
        ]);
    }
}
