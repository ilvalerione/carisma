<?php

namespace Carisma\Http\Requests;

use Carisma\Resource;
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
            abort_if(! $resource::authorizedToViewAny($this), 401);
        });
    }

    /**
     * Get a new instance of the resource being requested.
     *
     * @param null $primaryKey
     * @return Resource
     */
    public function newResource($primaryKey = null) : Resource
    {
        $resource = $this->resource();

        return new $resource(
            !is_null($primaryKey)
                ? $resource::newModel()->findOrFail($primaryKey)
                : $resource::newModel()
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
}