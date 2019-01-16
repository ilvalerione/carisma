<?php

namespace Carisma\Http\Controllers;


use Carisma\Requests\CarismaRequest;
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

        $resource::validateForCreating($request);

        $model = $request->model()->fill($resource::getRequestParams($request));

        $resource::onSaving($request, $model);
        $resource::onCreating($request, $model);
        $model->save();
        $this->syncManyToMany($request, $model);
        $resource::onCreated($request, $model);
        $resource::onSaved($request, $model);

        return $request->newResource($model->id);
    }
}