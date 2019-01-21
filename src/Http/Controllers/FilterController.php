<?php

namespace Carisma\Http\Controllers;

use Carisma\Http\Requests\FilterRequest;
use Illuminate\Routing\Controller;

class FilterController extends Controller
{
    /**
     * Apply custom filter to the basic index query.
     *
     * @param FilterRequest $request
     * @return mixed
     */
    public function handle(FilterRequest $request)
    {
        $resource = $request->resource();

        return $resource::collection(
            $request->filter()
                ->apply($request, $resource::newModel()->newQuery())
                ->paginate($request->perPage ?? 25)
        )->map(function ($resource) use ($request){
            return $resource->serializeForIndex($request);
        });
    }
}