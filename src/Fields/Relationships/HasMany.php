<?php

namespace Carisma\Fields\Relationships;


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
        return $this->resourceClass::collection($model->{$this->attribute});
    }
}