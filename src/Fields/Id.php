<?php

namespace Carisma\Fields;


class Id extends Field
{
    /**
     * ID field constructor.
     *
     * @param string $name
     * @param string $attribute
     */
    public function __construct($name = null, $attribute = null)
    {
        parent::__construct($name, $attribute);

        $this->exceptOnForms();
    }
}