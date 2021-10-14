<?php

namespace App\Http\Controllers;

use App\Models\EstateAdmin;
use Illuminate\Http\Request;
use App\Actions\StoreUserAction;
use App\Exports\EstateAdminExport;
use App\Imports\EstateAdminImport;
use App\Actions\StoreProfileAction;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\EstateAdminRequest;
use App\Http\Resources\EstateAdminCollection;
use App\Http\Resources\EstateAdminResource;
use App\Models\User;

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
        $estateAdmin = EstateAdmin::with(['user', 'user.profile'])->paginate(10);

        return $this->response_data(new EstateAdminCollection($estateAdmin));
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
        $estate = $request->user()->estate->first();

        $user->estate()->attach($estate);

        $user = new User();



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
        $estateAdmin = $estateAdmin->load(['user', 'user.profile']);
        return $this->response_data(new EstateAdminResource($estateAdmin));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EstateAdmin  $estateAdmin
     * @return \Illuminate\Http\Response
     */
    public function update(EstateAdminRequest $request, EstateAdmin $estateAdmin, StoreUserAction $storeUserAction, StoreProfileAction $storeProfileAction)
    {
        $storeUserAction->update($request, $estateAdmin->user);
        $profile = $storeProfileAction->update($request, $estateAdmin->user->profile);

        return $this->response_success('Admin has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EstateAdmin  $estateAdmin
     * @return \Illuminate\Http\Response
     */
    public function destroy(EstateAdmin $estateAdmin)
    {
        $estateAdmin->delete();

        return $this->response_success('Admin has been deleted');
    }

    public function deactivate(EstateAdmin $estateAdmin)
    {
        $estateAdmin->deactivate();

        return $this->response_success('Admin has been deactivated');
    }

    public function activate(EstateAdmin $estateAdmin)
    {
        $estateAdmin->activate();

        return $this->response_success('Admin has been activated');
    }

    public function suspend(EstateAdmin $estateAdmin)
    {
        $estateAdmin->suspend();

        return $this->response_success('Admin has been suspended');
    }

    public function export()
    {
        $filename = 'laravel-excel/estateAdmins.xlsx';
        $excel = Excel::store(new EstateAdminExport(), $filename);

        $url = Storage::url("local/", $filename);

        return $this->response_data([
            'url' => $url
        ]);
    }

    public function import(Request $request)
    {
        $file = $request->file('file');
        $data = (new EstateAdminImport)->queue($file->getPath());

        return $this->response_success('Estate Admin has been imported');
    }
}
