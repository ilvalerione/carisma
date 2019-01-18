<?php

namespace Carisma;

use Illuminate\Http\Request;

trait InteractWithFilters
{
    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public static function filters() :array
    {
        return [];
    }

    /**
     * Get the filters for the given request.
     *
     * @return array
     */
    public static function availableFilters()
    {
        $filters = [];
        foreach (static::filters() as $filter) {
            $filters[$filter->name()] = $filter;
        }
        return $filters;
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
        $availableFilters = static::availableFilters();

        foreach ($filters as $name => $value) {
            if(array_key_exists($name, $availableFilters)){
                ($availableFilters[$name])->apply($request, $query, $value);
            }
        }

        return $query;
    }
}