<?php

namespace Carisma\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarismaRequest extends FormRequest
{
    use InteractWithResource;

    /**
     * Get filters from params
     *
     * @return array|mixed
     */
    public function getFilters()
    {
        return $this->has('filters') ? json_decode($this->filters, true) : [];
    }
}