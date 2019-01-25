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
     * The URI key of the related resource.
     *
     * @var string
     */
    public $resourceName;

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|null  $resource
     * @param  string|null  $attribute
     * @return void
     */
    public function __construct($name, $resource, $attribute = null)
    {
        parent::__construct($name, $attribute);

        $this->resourceClass = $resource;

        $this->exceptOnForms();
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