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
     * @return \Illuminate\Support\Collection
     */
    public static function availableFilters($request)
    {
        return collect(static::filters($request))
            ->filter
            ->authorizedToRun($request)
            ->mapWithKeys(function ($filter) {
                return [$filter->name() => $filter];
            });
    }
}