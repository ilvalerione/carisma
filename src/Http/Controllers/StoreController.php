<?php

namespace Carisma\Http\Controllers;


use Carisma\Http\Requests\CarismaRequest;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  CarismaRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function handle(CarismaRequest $request)
    {
        $resource = $request->newResource();

        $resource->authorizeToCreate($request);

        $resource->validateForCreate($request);

        $model = DB::transaction(function () use ($request, $resource) {
            $model = $resource->fillForCreate($request, $resource::newModel());
            $model->save();
            return $model;
        });

        return $request->newResource($model->id)->serializeForDetails($request);
    }
}