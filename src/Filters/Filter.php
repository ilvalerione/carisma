<?php

namespace Carisma\Filters;

use Carisma\Http\Requests\FilterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class Filter
{
    /**
     * The name of the action.
     *
     * @var string
     */
    protected $name;

    /**
     * Filter constructor.
     *
     * @param null $name
     */
    public function __construct($name = null)
    {
        $this->name = $name;
    }

    /**
     * Get the name of the filter.
     *
     * @return string
     */
    public function name()
    {
        return $this->name ?: $this->humanize();
    }

    /**
     * Apply the filter to the given query.
     *
     * @param  \Carisma\Http\Requests\FilterRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract public function apply(FilterRequest $request, $query);

    /**
     * Get the fields available in the response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    /*public function fields(Request $request)
    {
        return [
            Field::make('id'),
            Field::make('name'),
            ...
        ];
    }*/

    /**
     * Humanize the class name into a string.
     *
     * @return string
     */
    protected function humanize() :string
    {
        return strtolower(
            Str::snake(class_basename(get_class($this)))
        );
    }
}