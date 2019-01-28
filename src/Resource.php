<?php

namespace Carisma;

use Carisma\Fields\DateTime;
use Carisma\Http\Requests\CarismaRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class Resource extends JsonResource
{
    use HasHooks,
        Authorizable,
        PerformValidation,
        PerformSearch,
        InteractWithFields,
        InteractWithFilters,
        InteractWithActions,
        ResolvesRelationships;

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
     * Define the uri path of the resource
     *
     * @return mixed
     */
    public static function uriKey()
    {
        return static::newModel()->getTable();
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
     * Apply filters to the model
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public static function buildIndexQuery(Request $request, $query = null)
    {
        return static::applySearch($request, $query ?: static::newModel()->newQuery());
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->availableFields($request)
            ->filter(function ($field) use ($request){
                return $field->authorize($request)
                    &&
                    ($field->showOnIndex || $field->showOnDetail);
            })
            ->mapWithKeys(function ($field) {
                return [$field->name => $field->resolve($this->resource)];
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
            return [$field->name => $field->resolve($this->resource)];
        })->merge($this->resolvesIncludedRelationships($request))->all();
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
            return [$field->name => $field->resolve($this->resource)];
        })->merge($this->resolvesIncludedRelationships($request))->all();
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