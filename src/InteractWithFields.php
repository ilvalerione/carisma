<?php

namespace Carisma;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait InteractWithFields
{
    /**
     * Get the fields that are available for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function availableFields(Request $request)
    {
        return collect($this->filter($this->fields($request)));
    }

    /**
     * Resolve the index fields.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function indexFields($request)
    {
        return $this->availableFields($request)->reject(function ($field) use ($request){
            return ! $field->showOnIndex || ! $field->authorize($request);
        });
    }

    /**
     * Resolve the detail fields.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function detailsFields($request)
    {
        return $this->availableFields($request)->reject(function ($field) use ($request){
            return ! $field->showOnDetail || ! $field->authorize($request);
        });
    }

    /**
     * Fill a new model instance using the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return mixed
     */
    public static function fillForCreate(Request $request, $model)
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return mixed
     */
    public static function fillForUpdate(Request $request, $model)
    {
        static::fillFields(
            $request,
            $model,
            (new static($model))->updateFields($request)
        );

        return $model;
    }

    /**
     * Fill the given fields for the model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  \Illuminate\Support\Collection  $fields
     * @return void
     */
    protected static function fillFields(Request $request, $model, $fields)
    {
        $fields->map->fill($request, $model);
    }

    /**
     * Resolve the creation fields.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function creationFields(Request $request)
    {
        return $this->removeNonCreationFields($this->availableFields($request));
    }

    /**
     * Remove non-creation fields from the given collection.
     *
     * @param  \Illuminate\Support\Collection  $fields
     * @return \Illuminate\Support\Collection
     */
    protected function removeNonCreationFields(Collection $fields)
    {
        return $fields->reject(function ($field) {
            return ! $field->showOnCreation;
        });
    }

    /**
     * Resolve the update fields.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function updateFields(Request $request)
    {
        return $this->removeNonUpdateFields($this->availableFields($request));
    }

    /**
     * Remove non-update fields from the given collection.
     *
     * @param  \Illuminate\Support\Collection  $fields
     * @return \Illuminate\Support\Collection
     */
    protected function removeNonUpdateFields(Collection $fields)
    {
        return $fields->reject(function ($field) {
            return ! $field->showOnUpdate;
        });
    }
}