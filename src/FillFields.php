<?php

namespace Carisma;


use Carisma\Http\Requests\CarismaRequest;

trait FillFields
{
    /**
     * Fill a new model instance using the given request.
     *
     * @param  \Carisma\Http\Requests\CarismaRequest $request
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return mixed
     */
    public static function fillForCreate(CarismaRequest $request, $model)
    {
        static::fillFields(
            $request, $model,
            (new static($model))->creationFields($request)
        );

        return $model;
    }

    /**
     * Fill a new model instance using the given request.
     *
     * @param  \Carisma\Http\Requests\CarismaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return mixed
     */
    public static function fillForUpdate(CarismaRequest $request, $model)
    {
        static::fillFields(
            $request, $model,
            (new static($model))->updateFields($request)
        );

        return $model;
    }

    /**
     * Fill the given fields for the model.
     *
     * @param  \Carisma\Http\Requests\CarismaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  \Illuminate\Support\Collection  $fields
     * @return array
     */
    protected static function fillFields(CarismaRequest $request, $model, $fields)
    {
        return $fields->map->fill($request, $model)->values()->all();
    }
}