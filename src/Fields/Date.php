<?php

namespace Carisma\Fields;

use Exception;
use DateTimeInterface;

class Date extends Field
{
    /**
     * Date constructor.
     *
     * @param string $name
     * @param string|null $attribute
     */
    public function __construct(string $name, string $attribute = null)
    {
        parent::__construct($name, $attribute);

        $this->resolveUsing(function ($value) {
            if (! $value instanceof DateTimeInterface) {
                throw new Exception("Date field must cast to 'date' in Eloquent model.");
            }

            return $value->format('Y-m-d');
        });
    }
}