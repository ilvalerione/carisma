<?php

namespace Carisma\Http\Controllers;

use Carisma\Requests\CarismaRequest;
use Illuminate\Database\Eloquent\Model;

trait HasManyToMany
{
    /**
     * Check if exists has many resource to sync
     *
     * @param CarismaRequest $request
     * @param Model $model
     */
    protected function syncManyToMany(CarismaRequest $request, Model $model)
    {
        $resource = $request->resource();

        if(! count($resource::$manyToManyKeys) > 0){
            return;
        }

        foreach ($resource::$manyToManyKeys as $key){
            if($request->has($key)){
                $model->{$key}()->sync($request->input($key));
            }
        }
    }
}