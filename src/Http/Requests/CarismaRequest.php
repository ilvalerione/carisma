<?php

namespace Carisma\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarismaRequest extends FormRequest
{
    use InteractWithResource;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
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
    /*public function getFilters()
    {
        return $this->has('filters')
            ? is_string($this->filters) ? json_decode($this->filters, true) : $this->filters
            : [];
    }*/
}