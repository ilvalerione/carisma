<?php

namespace Carisma;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait InteractWithFields
{
    /**
     * Get the fields that are available for the given request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function availableFields(Request $request)
    {
        return $this->resolveFields(
            $request, collect($this->filter($this->fields($request)))
        );
    }

    /**
     * Resolve the given fields to their values.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Support\Collection $fields
     * @return \Illuminate\Support\Collection
     */
    protected function resolveFields(Request $request, Collection $fields)
    {
        return $fields->reject(function ($field) use ($request) {
            return !$field->authorize($request);
        })->each(function ($field) {
            $field->resolve($this->resource);
        });
    }

    /**
     * Resolve the index fields.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function indexFields($request)
    {
        return $this->availableFields($request)
            ->reject(function ($field) use ($request) {
                return !$field->showOnIndex;
            });
    }

    /**
     * Resolve the detail fields.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function detailsFields($request)
    {
        return $this->availableFields($request)
            ->reject(function ($field) use ($request) {
                return !$field->showOnDetail;
            });
    }

    /**
     * Fill a new model instance using the given request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return mixed
     */
    public function fillForCreate(Request $request, $model)
    {
        $this->fillFields(
            $request,
            $model,
            $this->creationFields($request)
        );

        return $model;
    }

    /**
     * Fill a new model instance using the given request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return mixed
     */
    public function fillForUpdate(Request $request, $model)
    {
        $this->fillFields(
            $request,
            $model,
            $this->updateFields($request)
        );

        return $model;
    }

    /**
     * Fill the given fields for the model.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  \Illuminate\Support\Collection $fields
     * @return void
     */
    protected function fillFields(Request $request, $model, $fields)
    {
        $fields->map->fill($request, $model);
    }

    /**
     * Resolve the creation fields.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function creationFields(Request $request)
    {
        return $this->removeNonCreationFields($this->availableFields($request));
    }

    /**
     * Remove non-creation fields from the given collection.
     *
     * @param  \Illuminate\Support\Collection $fields
     * @return \Illuminate\Support\Collection
     */
    protected function removeNonCreationFields(Collection $fields)
    {
        return $fields->reject(function ($field) {
            return !$field->showOnCreation ||
                $field->attribute instanceof \Closure; // Exclude computed fields from creation
        });
    }

    /**
     * Resolve the update fields.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function updateFields(Request $request)
    {
        return $this->removeNonUpdateFields($this->availableFields($request));
    }

    /**
     * Remove non-update fields from the given collection.
     *
     * @param  \Illuminate\Support\Collection $fields
     * @return \Illuminate\Support\Collection
     */
    protected function removeNonUpdateFields(Collection $fields)
    {
        return $fields->reject(function ($field) {
            return !$field->showOnUpdate ||
                $field->attribute instanceof \Closure; // Exclude computed fields from update
        });
    }
}