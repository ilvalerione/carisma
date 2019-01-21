<?php

namespace Carisma;

use Illuminate\Http\Request;

trait InteractWithFilters
{
    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the filters for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function availableFilters($request)
    {
        return collect(static::filters($request))->mapWithKeys(function ($filter) {
            return [$filter->name() => $filter];
        })->all();
    }

    /**
     * Apply filters to the model
     *
     * @param Request $request
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function applyFilters(Request $request, $query, $filters)
    {
        $availableFilters = static::availableFilters($request);

        foreach ($filters as $name => $value) {
            if(array_key_exists($name, $availableFilters)){
                ($availableFilters[$name])->apply($request, $query, $value);
            }
        }

        return $query;
    }
}