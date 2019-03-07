<?php

namespace Carisma;

use Illuminate\Http\Request;

trait ResolvesRelationships
{
    /**
     * Get the relationships available for the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function relationships(Request $request)
    {
        return [];
    }

    /**
     * Get the relationships that are available for the given request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function availableRelationships($request)
    {
        return collect($this->filter($this->relationships($request)))
            ->reject(function ($relationship) use ($request) {
                return !$relationship->authorize($request);
            });
    }

    /**
     * Get the relationships to include in the response
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function resolvesRelationship($request)
    {
        $relationship = $this->availableRelationships($request)
            ->first(function ($item) use ($request) {
                return $item->name == $request->route('relationship');
            });

        return tap($relationship)->resolve($request, $this->resource);
    }

    /**
     * Get the relationships to include in the response
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function resolvesIncludedRelationships($request)
    {
        $requiredRelationships = explode(',', $request->query('include'));

        return $this->availableRelationships($request)
            ->reject(function ($relationship) use ($requiredRelationships) {
                return !in_array($relationship->name, $requiredRelationships);
            })->mapWithKeys(function ($relationship) use ($request) {
                $relationship->resolve($request, $this->resource);
                return $relationship->jsonSerialize();
            });
    }
}