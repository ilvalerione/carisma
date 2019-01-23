<?php

namespace Carisma\Fields;

use Carisma\Resource;
use Closure;
use Illuminate\Http\Request;

trait Authorization
{
    /**
     * The callback used to authorize viewing the card.
     *
     * @var \Closure|null
     */
    public $seeCallback;

    /**
     * Determine if the element should be used for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorize(Request $request)
    {
        return $this->authorizedToSee($request);
    }

    /**
     * Determine if the card should be available for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToSee(Request $request)
    {
        return $this->seeCallback ? call_user_func($this->seeCallback, $request) : true;
    }

    /**
     * Set the callback to be run to authorize viewing the card.
     *
     * @param  \Closure  $callback
     * @return $this
     */
    public function canSee(Closure $callback)
    {
        $this->seeCallback = $callback;

        return $this;
    }

    /**
     * Indicate that the entity can be seen when a given authorization ability is available.
     *
     * @param  string  $ability
     * @param  Resource  $resource
     * @return $this
     */
    public function canSeeWhen($ability, Resource $resource)
    {
        $model = $resource->resource;

        return $this->canSee(function ($request) use ($ability, $model) {
            return $request->user()->can($ability, $model);
        });
    }
}