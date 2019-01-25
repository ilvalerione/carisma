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
     * @return \Carisma\Resource
     */
    public function handle(CarismaRequest $request, $resource, $id)
    {
        $resource = $request->findResourceOrFail($id);

        $resource->authorizeToView($request);

        return $resource->serializeForDetails($request);
    }
}