<?php

namespace Carisma\Http\Controllers;


use Carisma\Http\Requests\CarismaRequest;
use Illuminate\Routing\Controller;

class StoreController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  CarismaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function handle(CarismaRequest $request)
    {
        $resource = $request->resource();

        $resource::authorizeToCreate($request);

        $resource::validateForCreate($request);

        $model = $resource::fillForCreate($request, $resource::newModel());

        $model->save();

        return $request->newResource($model->id)->serializeForDetails($request);
    }
}