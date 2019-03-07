<?php

namespace Carisma\Fields\Relationships;


class ToOne extends Relationship
{
    /**
     * Resolve the field's value.
     *
     * @param  \Carisma\Http\Requests\RelationshipRequest $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function resolve($request, $model)
    {
        $this->value = (new $this->resourceClass($model->{$this->attribute}))
            ->serializeForDetails($request);
    }
}