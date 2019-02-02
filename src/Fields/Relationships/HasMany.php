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
        $request = resolve(Request::class);

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