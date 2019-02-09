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
    public function actions(Request $request)
    {
        return [];
    }

    /**
     * Get the actions that are available for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function availableActions($request)
    {
        return collect($this->actions($request))->mapWithKeys(function ($action) {
            return [$action->name() => $action];
        });
    }
}