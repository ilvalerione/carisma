<?php

namespace Carisma\Http\Controllers;


use Carisma\Http\Requests\CarismaRequest;
use Illuminate\Routing\Controller;

class ShowController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  CarismaRequest  $request
     * @param  int $id
     * @return \Carisma\CarismaResource
     */
    public function index(CarismaRequest $request, $resource, $id)
    {
        return $request->findResourceOrFail($id);
    }
}