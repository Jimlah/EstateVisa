<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Estate;
use App\Models\EstateAdmin;
use Illuminate\Http\Request;
use App\Exports\EstateExport;
use App\Imports\EstateImport;
use App\Actions\StoreUserAction;
use App\Actions\StoreProfileAction;
use App\Http\Requests\EstateRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\EstateResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\EstateAdminResource;
use App\Http\Requests\UserEstateProfileRequest;
use App\Http\Resources\EstateCollection;
use Maatwebsite\Excel\Concerns\ToArray;

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
        $data = Estate::latest('id')->paginate(10);
        return $this->response_data(new EstateCollection($data));
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

        $storeProfileAction->execute($request, $user);

        $estate = Estate::create([
            'name' => $request->name,
            'code' => $request->code,
            'logo' => $request->logo,
            'address' => $request->address,
        ]);

        $admin = $estate->admins()->create([
            'user_id' => $user->id,
            'is_owner' => true,
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
    public function update(
        UserEstateProfileRequest $request,
        Estate $estate,
        StoreUserAction $storeUserAction,
        StoreProfileAction $storeProfileAction
    ) {
        $estate->name = $request->name;
        $estate->code = $request->code;
        $estate->address = $request->address;
        $estate->logo = $request->logo;

        $user = $estate->admins->first()->user;

        $storeUserAction->update($request, $user);
        $storeProfileAction->update($request, $user->profile);

        $estate->save();

        return $this->response_success("Estate Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estate  $estate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Estate $estate)
    {
        $estate->admins->each(function ($admin) {
            $admin->delete();
        });
        $estate->delete();

        return $this->response_success("Estate Deleted");
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
        $estate->admins->each(function ($admin) {
            $admin->activate();
        });

        $estate->activate();

        return $this->response_success("Estate Activated");
    }

    public function suspend(Estate $estate)
    {
        $estate->admins->each(function ($admin) {
            $admin->suspend();
        });

        $estate->suspend();

        return $this->response_success("Estate Suspended");
    }

    public function deactivate(Estate $estate)
    {
        $estate->admins->each(function ($admin) {
            $admin->deactivate();
        });

        $estate->deactivate();

        return $this->response_success("Estate Deactivated");
    }

    public function import(Request $request)
    {
        $file = $request->file('file');
        $excel = (new EstateImport)->queue($file->getPath());
        return $this->response_success('Estate Imported');
    }

    public function export()
    {
        $filename = 'laravel-excel/estates.xlsx';
        $excel =  Excel::store(new EstateExport(), $filename);

        $url = Storage::url("local/" . $filename);

        return $this->response_data([
            'url' => $url,
        ]);
    }
}
