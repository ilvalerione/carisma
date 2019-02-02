<?php

namespace Carisma\Fields\Relationships;

use Illuminate\Http\Request;

class HasOne extends Relationship
{
    /**
     * Resolve the field's value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function resolve($model)
    {
        $this->value = (new $this->resourceClass($model->{$this->attribute}))
            ->serializeForDetails(resolve(Request::class));
    }
}