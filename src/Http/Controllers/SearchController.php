<?php

namespace Carisma\Http\Controllers;


use Carisma\Requests\CarismaRequest;
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
            $resource::getFilteredQuery($request)->latest()->get()
        );
    }
}