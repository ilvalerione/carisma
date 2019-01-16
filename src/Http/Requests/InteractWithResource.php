<?php

namespace Carisma\Requests;

use Carisma\CarismaResource;
use Carisma\Facades\Carisma;

trait InteractWithResource
{
    /**
     * Get the class name of the resource being requested.
     *
     * @return mixed
     */
    public function resource()
    {
        return tap(Carisma::resource($this->resource), function ($resource) {
            abort_if(is_null($resource), 404);
        });
    }

    /**
     * Get a new instance of the resource being requested.
     *
     * @param null $primaryKey
     * @return CarismaResource
     */
    public function newResource($primaryKey = null) : CarismaResource
    {
        $resource = $this->resource();

        return new $resource(
            !is_null($primaryKey)
                ? $this->model()->findOrFail($primaryKey)
                : $resource->model()
        );
    }

    /**
     * Find the resource model instance for the request.
     *
     * @param null $primaryKey
     * @return mixed
     */
    public function findResourceOrFail($primaryKey = null)
    {
        $resource = $this->resource();

        return new $resource($this->model()->findOrFail($primaryKey ?? $this->id));
    }

    /**
     * Get a new instance of the underlying model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function model()
    {
        $resource = $this->resource();

        return $resource::newModel();
    }

    /**
     * QueryBuilder by resource model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery()
    {
        return $this->model()->newQuery();
    }
}