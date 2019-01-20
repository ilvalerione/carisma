<?php

namespace Carisma\Http\Controllers;


use Carisma\Http\Requests\CarismaRequest;
use Illuminate\Routing\Controller;

class SearchController extends Controller
{
    /**
     * Plain list of resources without pagination
     *
     * @param CarismaRequest $request
     * @return array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(CarismaRequest $request)
    {
        $resource = $request->resource();

        return $resource::collection(
            $resource::buildIndexQuery($request)->latest()->get()
        )->map(function ($resource) use ($request){
            return $resource->serializeForIndex($request);
        });
    }
}