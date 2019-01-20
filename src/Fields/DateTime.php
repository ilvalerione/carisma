<?php

namespace Carisma\Fields;

use Exception;
use DateTimeInterface;

class DateTime extends Field
{
    /**
     * DateTime constructor.
     *
     * @param string $name
     * @param string|null $attribute
     */
    public function __construct(string $name, string $attribute = null)
    {
        parent::__construct($name, $attribute);

        $this->fillUsing(function ($value) {
            if (! $value instanceof DateTimeInterface) {
                throw new Exception("DateTime field must cast to 'datetime' in Eloquent model.");
            }

            return $value->format('Y-m-d H:i:s');
        });
    }
}