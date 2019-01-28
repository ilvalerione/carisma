<?php

namespace Carisma\Http\Controllers;


use Carisma\Http\Requests\CarismaRequest;
use Illuminate\Routing\Controller;

class UpdateController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  CarismaRequest $request
     * @param $resource
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function handle(CarismaRequest $request, $resource, $id)
    {
        $request->findResourceOrFail($id)->authorizeToUpdate($request);

        $resource = $request->resource();

        $resource::validateForUpdate($request);

        $model = $resource::newModel()->findOrFail($id);

        $resource::fillForUpdate($request, $model);

        $model->save();

        return $request->newResource($id)->serializeForDetails($request);
    }
}