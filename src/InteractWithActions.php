<?php

namespace Carisma;


use Illuminate\Http\Request;

trait InteractWithActions
{
    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function actions(Request $request)
    {
        return [];
    }

    /**
     * Get the actions that are available for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public static function availableActions($request)
    {
        return collect(static::actions($request))
            ->filter
            ->authorizedToRun($request)
            ->mapWithKeys(function ($action) {
                return [$action->name() => $action];
            });
    }
}