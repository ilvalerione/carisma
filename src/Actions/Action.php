<?php

namespace Carisma\Actions;

use Carisma\Http\Requests\ActionRequest;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
     * Action constructor.
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
     * Perform the action on the given models.
     *
     * @param  \Carisma\Http\Requests\ActionRequest $request
     * @param  \Illuminate\Support\Collection $models
     * @return mixed
     */
    abstract public function run(ActionRequest $request, Collection $models);

    /**
     * Execute the action for the given request.
     *
     * @param  \Carisma\Http\Requests\ActionRequest $request
     * @return mixed
     */
    public function handleRequest(ActionRequest $request)
    {
        $models = $this->models($request);

        if ($models->isEmpty()) {
            return response()
                ->json(['message' => 'Sorry! You are not authorized to perform this action.'], Response::HTTP_UNAUTHORIZED);
        }

        $response = DB::transaction(function () use ($request) {
            $this->run($request, $this->models($request));
        });

        return $response;
    }

    /**
     * Get the selected models for the action declared in "ids" url parameter.
     * If ids is empty will be called "run" passing a raw model instance
     *
     * @param \Carisma\Http\Requests\ActionRequest $request
     * @return \Illuminate\Support\Collection
     */
    protected function models(ActionRequest $request)
    {
        $model = $request->model();

        if($request->isGenericResourceAction()){
            return collect([$model]);
        }
        if ($request->isForAllResources()) {
            $models = $model->all();
        } else {
            $models = $model->whereKey(explode(',', $request->ids))->get();
        }

        return $this->filterModelsByAuthorization($request, $models);
    }

    /**
     * Remove models the user does not have permission to execute the action against.
     *
     * @param \Carisma\Http\Requests\ActionRequest $request
     * @param  \Illuminate\Support\Collection $models
     * @return \Illuminate\Support\Collection
     */
    protected function filterModelsByAuthorization(ActionRequest $request, Collection $models)
    {
        $action = $request->action();

        return $models->filter(function ($model) use ($request, $action) {
            return $action->authorizedToRun($request, $model);
        });
    }

    /**
     * Set the callback to be run to authorize the action on the given model.
     *
     * @param  \Closure $runCallback
     * @return $this
     */
    public function canRun(Closure $runCallback)
    {
        $this->runCallback = $runCallback;

        return $this;
    }

    /**
     * Determine if the action is executable for the given request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Database\Eloquent\Model $model
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
    protected function humanize(): string
    {
        return strtolower(
            Str::snake(class_basename(get_class($this)))
        );
    }
}