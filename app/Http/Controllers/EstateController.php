<?php

namespace App\Http\Controllers;

use App\Actions\StoreProfileAction;
use App\Actions\StoreUserAction;
use App\Exports\EstateExport;
use App\Http\Requests\EstateRequest;
use App\Http\Requests\UserEstateProfileRequest;
use App\Http\Resources\EstateResource;
use App\Models\Estate;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EstateController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Estate::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Estate::orderBy('created_at', 'desc')->get();

        return response()->json(['data' => EstateResource::collection($data)], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserEstateProfileRequest $request, StoreUserAction $storeUserAction, StoreProfileAction $storeProfileAction)
    {
        $user = $storeUserAction->execute($request);

        $profile = $storeProfileAction->execute($request, $user);

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
        return response()->json([
            'data' => new EstateResource($estate)
        ], 200);
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

    /**
     * Disable the specified resource from storage.
     *
     * @param  \App\Models\Estate  $estate
     * @return \Illuminate\Http\Response
     * @throws \Exception
     * */
    public function activate(Estate $estate)
    {
        $estate->activateEstate();

        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully disable the Estate'
        ]);
    }

    public function suspend(Estate $estate)
    {
        $estate->suspendEstate();

        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully enable the Estate'
        ]);
    }

    public function deactivate(Estate $estate)
    {
        $estate->deactivateEstate();

        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully deactivated the Estate'
        ]);
    }

    public function import()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully import the Estate'
        ]);
    }

    public function export()
    {
        return Excel::download(new EstateExport, 'estates.xlsx');
    }
}
