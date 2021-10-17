<?php

namespace App\Http\Controllers;

use App\Http\Requests\VisitorFormRequest;
use App\Http\Resources\VisitorCollection;
use App\Http\Resources\VisitorResource;
use App\Models\Visitor;
use Illuminate\Http\Request;
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
        $visitors =  Visitor::with(['user', 'user.profile'])->paginate(10);

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
        Visitor::create($request->validated());

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
        return $this->response_data(VisitorResource::make($visitor->load((['user', 'estate']))));
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
        $visitor->update($request->validated());

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
