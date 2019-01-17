<?php

namespace Carisma\Http\Controllers;


use Carisma\Http\Requests\CarismaRequest;
use Illuminate\Routing\Controller;

class DestroyController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param CarismaRequest $request
     * @param  int $id
     * @return mixed
     */
    public function index(CarismaRequest $request, $resource, $id)
    {
        $resource = $request->resource();

        $request->findResourceOrFail($id)->authorizeToDelete($request);

        $instance = $request->model()->findOrFail($id);

        $resource::onDeleting($request, $instance);
        $instance->delete();
        $resource::onDeleted($request, $instance);

        return new $resource($instance);
    }
}