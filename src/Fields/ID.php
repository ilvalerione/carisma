<?php

namespace Carisma\Fields;


class ID extends Field
{
    /**
     * ID field constructor.
     *
     * @param string $name
     * @param string $attribute
     */
    public function __construct(string $name, string $attribute = null)
    {
        parent::__construct($name, $attribute);

        $this->exceptOnForms();
    }
}