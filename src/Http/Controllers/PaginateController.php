<?php

namespace Carisma\Http\Controllers;


use Carisma\Http\Requests\CarismaRequest;
use Illuminate\Routing\Controller;

class PaginateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param CarismaRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(CarismaRequest $request)
    {
        $resource = $request->resource();

        return $resource::collection(
            $resource::getFilteredQuery($request)->latest()->paginate($request->perPage ?? 25)
        );
    }
}