<?php

namespace App\Http\Controllers;

use App\Models\Estate;
use Illuminate\Http\Request;
use App\Exports\EstateExport;
use App\Imports\EstateImport;
use App\Actions\StoreUserAction;
use App\Actions\StoreProfileAction;
use App\Http\Requests\EstateRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\EstateResource;
use App\Http\Requests\UserEstateProfileRequest;
use App\Http\Resources\EstateAdminResource;
use App\Models\EstateAdmin;

class EstateController extends Controller
{

    public function __construct()
    {
        // $this->authorizeResource(Estate::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = EstateAdmin::with(['estate', 'user'])->get();
        return $this->response_data(EstateAdminResource::collection($data));
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

        return $this->response_success("New Estate Created");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Estate  $estate
     * @return \Illuminate\Http\Response
     */
    public function show(Estate $estate)
    {
        return $this->response_data(new EstateResource($estate));
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

    public function import(Request $request)
    {
        $file = $request->file('file')->store('temp');
        $excel = Excel::import(new EstateImport(), $file);

        dd(Estate::all());

        //

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
