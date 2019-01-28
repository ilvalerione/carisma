<?php

namespace Carisma\Http\Requests;


class FilterRequest extends CarismaRequest
{
    /**
     * Get the action instance specified by the request.
     *
     * @return \Carisma\Actions\Action
     */
    public function filter()
    {
        return $this->availableFilters()->first(function ($filter) {
            return $filter->name() == $this->route('filter');
        }) ?: abort(404);
    }

    /**
     * Get the possible actions for the request.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function availableFilters()
    {
        return $this->newResource()->availableFilters($this);
    }
}