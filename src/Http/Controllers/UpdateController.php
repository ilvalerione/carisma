<?php

namespace Carisma\Http\Controllers;


use Carisma\Http\Requests\CarismaRequest;
use Illuminate\Routing\Controller;

class UpdateController extends Controller
{
    use HasManyToMany;

    /**
     * Update the specified resource in storage.
     *
     * @param  CarismaRequest $request
     * @param $resource
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function index(CarismaRequest $request, $resource, $id)
    {
        $request->findResourceOrFail($id)->authorizeToUpdate($request);

        $resource = $request->resource();

        $resource::validateForUpdate($request);

        $model = $resource::newModel()->findOrFail($id);

        $resource::fillForUpdate($request, $model);

        $resource::onSaving($request, $model);
        $resource::onUpdating($request, $model);
        $model->save();
        $resource::onUpdated($request, $model);
        $resource::onSaved($request, $model);

        return $request->newResource($id);
    }
}