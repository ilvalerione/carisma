<?php

namespace Carisma\Fields;

use Exception;
use DateTimeInterface;

class Date extends Field
{
    /**
     * Indicate that the field's value from the model is nullable.
     *
     * @var boolean
     */
    protected $nullable = false;

    /**
     * The format that should be used to display the date
     *
     * @var string
     */
    protected $format = 'Y-m-d';

    /**
     * Date constructor.
     *
     * @param string $name
     * @param string|null $attribute
     */
    public function __construct(string $name, string $attribute = null)
    {
        parent::__construct($name, $attribute);

        $this->resolveUsing(function ($request, $value) {
            if($this->nullable && !$value){
                return null;
            }

            if (! $value instanceof DateTimeInterface) {
                throw new Exception("Date field must cast to 'date' in Eloquent model.");
            }

            return $value->format($this->format);
        });
    }

    /**
     * Set the field's value from the model to nullable.
     *
     * @return $this
     */
    public function nullable()
    {
        $this->nullable = true;

        return $this;
    }

    /**
     * Set the date format that should be used to display the date.
     *
     * @param string $format
     * @return $this
     */
    public function format($format)
    {
        $this->format = $format;

        return $this;
    }
}