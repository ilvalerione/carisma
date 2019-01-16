<?php

namespace Carisma\Filters;

use Carbon\Carbon;
use EloquentFilter\ModelFilter;

abstract class Filter extends ModelFilter
{
    use Orderable;

    /**
     * Search by query string
     *
     * @param $q
     * @return \Illuminate\Database\Query\Builder|static
     */
    public abstract function q($q);

    public function createdAt($value)
    {
        return $this->filterAsDate('created_at', $value);
    }

    public function updatedAt($value)
    {
        return $this->filterAsDate('updated_at', $value);
    }

    public function filterAsDate($field, $value)
    {
        $param = $value instanceof Carbon ? $value : Carbon::parse($value);
        return $this->whereDate($field, $param->format('Y-m-d'));
    }

    public function filterAsDatetime($field, $value)
    {
        $param = $value instanceof Carbon ? $value : Carbon::parse($value);
        return $this->where($field, $param);
    }
}