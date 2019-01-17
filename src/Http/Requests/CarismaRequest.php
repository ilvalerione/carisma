<?php

namespace Carisma\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarismaRequest extends FormRequest
{
    use HasTypes, InteractWithResource;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            //
        ];
    }

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