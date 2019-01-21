<?php

namespace Carisma\Actions;

use Carisma\Http\Requests\ActionRequest;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

abstract class Action
{
    /**
     * The name of the action.
     *
     * @var string
     */
    public $name;

    /**
     * The callback used to authorize running the action.
     *
     * @var Closure|null
     */
    protected $runCallback;

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
     * Perform the action on the given models.
     *
     * @param  \Carisma\Http\Requests\ActionRequest  $request
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    abstract public function run($request, $models);

    /**
     * Execute the action for the given request.
     *
     * @param  \Carisma\Http\Requests\ActionRequest  $request
     * @return mixed
     */
    public function handleAction(ActionRequest $request)
    {
        return $this->run($request, $this->selectedEntities($request));
    }

    /**
     * Get the selected models for the action declared in "ids" url parameter.
     * If ids is empty will be called "run" passing a raw model instance
     *
     * @param ActionRequest $request
     * @return mixed
     */
    protected function selectedEntities(ActionRequest $request)
    {
        $model = $request->model();

        if(empty($request->ids)){
            return $model;
        }

        return $model->whereKey(explode(',', $request->ids))->get();
    }

    /**
     * Determine if the action is executable for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return bool
     */
    public function authorizedToRun(Request $request, $model)
    {
        return $this->runCallback ? call_user_func($this->runCallback, $request, $model) : true;
    }

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