<?php

namespace Carisma\Fields\Relationships;

use Carisma\Fields\Field;
use Illuminate\Http\Request;

class Relationship extends Field
{
    /**
     * The class name of the related resource.
     *
     * @var null|string
     */
    protected $resourceClass;

    /**
     * Create a new Relationship field.
     *
     * @param  string  $name
     * @param  string|null  $resourceClass
     * @param  string|null  $attribute
     * @return void
     */
    public function __construct($name, $resourceClass, $attribute = null)
    {
        parent::__construct($name, $attribute);

        $this->resourceClass = $resourceClass;
    }

    /**
     * Determine if the field should be displayed for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorize(Request $request)
    {
        return call_user_func(
                [$this->resourceClass, 'authorizedToViewAny'], $request
            ) && parent::authorize($request);
    }
}