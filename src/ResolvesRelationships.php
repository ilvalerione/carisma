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
    public function resolvesIncludedRelationships($request)
    {
        $requiredRelationships = explode(',', $request->query('include'));

        return $this->availableRelationships($request)
            ->reject(function ($relationship) use ($requiredRelationships) {
                return !in_array($relationship->name, $requiredRelationships);
            })->mapWithKeys(function ($relationship) {
                $relationship->resolve($this->resource);
                return $relationship->jsonSerialize();
            });
    }

    /**
     * Resolve a given relationship
     *
     * @param \Carisma\Fields\Relationships\Relationship $relationship
     * @return mixed
     */
    public function resolvesRelationship($relationship)
    {
        return tap($relationship, function ($relationship){
            $relationship->resolve($this->resource);
        });
    }
}