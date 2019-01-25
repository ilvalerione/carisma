<?php

namespace Carisma;

use Illuminate\Http\Request;

trait ResolvesRelationships
{
    /**
     * Get the relationships available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function relationship(Request $request)
    {
        return [];
    }

    /**
     * Get the actions that are available for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function availableRelationships($request)
    {
        $requiredRelationships = explode(',', $request->input('include'));

        return collect($this->filter($this->relationship($request)))
            ->reject(function ($relationship) use ($request, $requiredRelationships){
                return !$relationship->authorize($request)
                    ||
                    !in_array($relationship->name, $requiredRelationships);
            });
    }
}