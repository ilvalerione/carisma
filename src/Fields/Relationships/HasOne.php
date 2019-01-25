<?php

namespace Carisma\Fields\Relationships;

class HasOne extends Relationship
{
    /**
     * Resolve the field's value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return mixed
     */
    public function resolve($model)
    {
        return new $this->resourceClass($model->{$this->attribute});
    }
}