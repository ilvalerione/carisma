<?php

namespace Carisma\Http\Controllers;


use Carisma\Http\Requests\CarismaRequest;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  CarismaRequest $request
     * @return \Illuminate\Http\Response
     */
    public function handle(CarismaRequest $request)
    {
        $resource = $request->findResourceOrFail();

        $resource->authorizeToUpdate($request);

        $resource->validateForUpdate($request);

        DB::transaction(function () use ($request, $resource) {
            $model = $resource->resource;
            $resource->fillForUpdate($request, $model);
            $model->save();
        });

        return $resource->serializeForDetails($request);
    }
}