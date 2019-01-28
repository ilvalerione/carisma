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
            $request->filter()->apply($request, $this->searchQuery($request, $resource))
        )->map(function ($resource) use ($request){
            return $resource->serializeForIndex($request);
        });
    }

    /**
     * Perform search befor filtering
     *
     * @param FilterRequest $request
     * @param string $resource
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function searchQuery($request, $resource)
    {
        return empty($request->query('search'))
            ? $resource::newModel()->newQuery()
            : $resource::filterBySearchParam(
                $resource::newModel()->newQuery(), $request->query('search')
            );
    }
}