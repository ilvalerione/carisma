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
            $resource::buildIndexQuery($request)
                ->latest()
                ->paginate($request->perPage ?? 25)
        )->map(function ($resource) use ($request){
            return $resource->serializeForIndex($request);
        });
    }
}