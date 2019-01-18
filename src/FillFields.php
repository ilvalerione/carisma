<?php

namespace Carisma;


use Carisma\Http\Requests\CarismaRequest;

trait FillFields
{
    /**
     * Fill a new model instance using the given request.
     *
     * @param  \Carisma\Http\Requests\CarismaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return mixed
     */
    public static function fill(CarismaRequest $request, $model)
    {
        collect((new static($model))->fields())
            ->map
            ->fill($request, $model);

        return $model;
    }
}