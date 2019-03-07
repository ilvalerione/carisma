<?php

namespace Carisma\Fields\Relationships;


class ToMany extends Relationship
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
        $this->value = $this->resourceClass::collection(
            $this->resourceClass::buildIndexQuery(
                $request,
                $model->{$this->attribute}() // <- query builder
            )->get()
        )->map(function ($resource) use ($request){
            return $resource->serializeForIndex($request);
        });
    }
}