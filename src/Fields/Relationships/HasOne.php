<?php

namespace Carisma\Fields\Relationships;

class HasOne extends Relationship
{
    /**
     * Create a new Relationship field.
     *
     * @param  string  $name
     * @param  string|null  $resource
     * @param  string|null  $attribute
     * @return void
     */
    public function __construct(string $name, string $resource, string $attribute = null)
    {
        // Using callback as attribute for underlying Field class
        // It will be considered automatically as a Computed Field.
        parent::__construct($name, $resource, function ($model) use ($attribute) {
            return new $this->resourceClass($model->{$attribute});
        });
    }
}