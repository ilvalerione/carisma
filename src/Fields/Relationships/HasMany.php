<?php

namespace Carisma\Fields\Relationships;


use Illuminate\Http\Request;

class HasMany extends Relationship
{
    /**
     * Resolve the field's value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return mixed
     */
    public function resolve($model)
    {
        return $this->resourceClass::collection(
            $this->resourceClass::buildIndexQuery(
                resolve(Request::class),
                $model->{$this->attribute}()
            )->get()
        );
    }
}