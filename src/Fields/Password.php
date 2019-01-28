<?php

namespace Carisma\Fields;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Password extends Field
{
    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var bool
     */
    public $showOnIndex = false;

    /**
     * Indicates if the element should be shown on the detail view.
     *
     * @var bool
     */
    public $showOnDetail = false;

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  object  $model
     * @return void
     */
    public function fill(Request $request, $model)
    {
        // This check is needed to preserve current password value
        // in case in the request is sent an empty value
        if (! empty($request->{$this->attribute})) {
            $model->{$this->attribute} = Hash::make($request[$this->attribute]);
        }
    }
}