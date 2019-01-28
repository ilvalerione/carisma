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
    public function relationship(Request $request)
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
        return collect($this->filter($this->relationship($request)))
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
    public function resolvesIncludedRelationships($request)
    {
        $requiredRelationships = explode(',', $request->query('include'));

        return $this->availableRelationships($request)
            ->reject(function ($relationship) use ($requiredRelationships) {
                return !in_array($relationship->name, $requiredRelationships);
            })->mapWithKeys(function ($relationship) {
                return [$relationship->name => $relationship->resolve($this->resource)];
            });
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function resolvesRelationship($request)
    {
        return $this->availableRelationships($request)
            ->first(function ($relationship) use ($request) {
                return $relationship->name == $request->relationship;
            })->resolve($this->resource);
    }
}