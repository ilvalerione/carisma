<?php

namespace Carisma\Http\Controllers;


use Carisma\Http\Requests\CarismaRequest;
use Illuminate\Routing\Controller;

class StoreController extends Controller
{
    use HasManyToMany;

    /**
     * Store a newly created resource in storage.
     *
     * @param  CarismaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(CarismaRequest $request)
    {
        $resource = $request->resource();

        $resource::authorizeToCreate($request);

        //$resource::validateForCreating($request);

        //$model = $request->model()->fill($resource::getRequestParams($request));
        $model = $resource::fillForCreate(
            $request, $resource::newModel()
        );

        $resource::onSaving($request, $model);
        $resource::onCreating($request, $model);
        $model->save();
        $resource::onCreated($request, $model);
        $resource::onSaved($request, $model);

        return $request->newResource($model->id);
    }
}