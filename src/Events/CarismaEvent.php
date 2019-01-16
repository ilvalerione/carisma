<?php

namespace Carisma\Events;


use Illuminate\Database\Eloquent\Model;

class CarismaEvent
{
    /**
     * @var Model
     */
    public $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}