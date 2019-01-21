<?php

namespace Carisma;

use Carisma\Fields\DateTime;
use Carisma\Http\Requests\CarismaRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class CarismaResource extends JsonResource
{
    use HasHooks,
        Authorizable,
        PerformValidation,
        PerformSearch,
        FillFields,
        InteractWithFilters,
        InteractWithActions;

    /**
     * The underlying model resource instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $resource;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model;

    /**
     * CarismaResource constructor.
     *
     * @param $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    abstract public function fields(Request $request);

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->availableFields($request)->mapWithKeys(function ($field) {
            return [$field->name => $field->resolve($this)];
        })->all();
    }

    /**
     * Prepare the resource for JSON serialization.
     *
     * @param $request
     * @return array
     */
    public function serializeForIndex($request)
    {
        return $this->indexFields($request)->mapWithKeys(function ($field) {
            return [$field->name => $field->resolve($this)];
        })->all();
    }

    /**
     * Prepare the resource for JSON serialization.
     *
     * @param $request
     * @return array
     */
    public function serializeForDetails($request)
    {
        return $this->detailsFields($request)->mapWithKeys(function ($field) {
            return [$field->name => $field->resolve($this)];
        })->all();
    }


    /**
     * Get the underlying model instance for the resource.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function model()
    {
        return $this->resource;
    }

    /**
     * Get a fresh instance of the model represented by the resource.
     *
     * @return mixed
     */
    public static function newModel()
    {
        $model = static::$model;

        return new $model;
    }

    /**
     * Define the uri path of the resource
     *
     * @return mixed
     */
    public static function uriKey()
    {
        return static::newModel()->getTable();
    }

    /**
     * Apply filters to the model
     *
     * @param CarismaRequest $request
     * @return mixed
     */
    public static function buildIndexQuery(CarismaRequest $request)
    {
        return static::applyFilters(
            $request,
            static::applySearch(static::newModel()->newQuery(), $request->search),
            $request->getFilters()
        );
    }

    /**
     * Get string representation of the resource's timestamps
     *
     * @return mixed
     */
    protected function timestamps()
    {
        return [
            DateTime::make('created_at'),
            DateTime::make('updated_at'),
        ];
    }

    /**
     * Get string representation of the soft deleting date
     *
     * @return mixed
     */
    protected function softDelete()
    {
        return DateTime::make('deleted_at');
    }
}