<?php

namespace App\Http\Controllers;

use App\Http\Requests\VisitorFormRequest;
use App\Http\Resources\VisitorCollection;
use App\Http\Resources\VisitorResource;
use App\Models\Visitor;
use App\Notifications\GatePassIssued;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use phpDocumentor\Reflection\Types\This;

class VisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $visitors =  Visitor::with(['user', 'user.profile'])->userOnly()->latest()->paginate(10);

        return $this->response_data(new VisitorCollection($visitors));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VisitorFormRequest $request)
    {

        $visitor = Visitor::create([
            'user_id' => auth()->user()->id,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'estate_id' => $request->estate_id,
            'sent_by' => User::class,
            'expired_at' => $request->expired_at ?? now()->addDays(1),
        ]);

        Notification::send(
            $visitor->estate->admins->each(function ($admin) {
                return $admin->user;
            }),
            new GatePassIssued($visitor)
        );

        return $this->response_success('Created a new visitor');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function show(Visitor $visitor)
    {
        return $this->response_data(VisitorResource::make($visitor->load(['user', 'estate'])));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function update(VisitorFormRequest $request, Visitor $visitor)
    {
        $visitor->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'expired_at' => $request->expired_at ?? $visitor->expired_at,
        ]);

        return $this->response_success('Updated a visitor');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Visitor $visitor)
    {
        $visitor->delete();

        return $this->response_success('Deleted a visitor');
    }
}
