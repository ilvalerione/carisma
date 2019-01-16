<?php

namespace Carisma\Filters;

trait Orderable
{
    /**
     * Apply orderBy criteria also for multiple columns
     *
     * 'order_criteria' => [
     *  'column_1' => 'ASC',
     *  ...
     *  'column_n' => 'DESC'
     * ]
     *
     * @param $criteria
     * @return $this
     */
    public function orderCriteria($criteria)
    {
        foreach (json_decode($criteria, true) as $column => $direction) {
            $this->orderBy($column, $direction);
        }

        return $this;
    }
}
