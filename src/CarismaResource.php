<?php

namespace Carisma;


use Carisma\Filters\Filter;
use Carisma\Http\Requests\CarismaRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class CarismaResource extends JsonResource
{
    use HasHooks,
        Authorizable,
        PerformValidation;

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
     * Eloquent model filter
     *
     * @var Filter
     */
    public static $filter;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];
    public static $creationRules = [];
    public static $updatingRules = [];

    /**
     * ManyToMany relations method to call when create and update resource
     *
     * @var string|array
     */
    public static $manyToManyKeys = [];

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
    public static function getFilteredQuery(CarismaRequest $request)
    {
        return (isset(static::$filter) && is_a(static::$filter, Filter::class, true))
            ? static::newModel()->filter($request->getFilters(), static::$filter)
            : static::newModel();
    }

    /**
     * Custom params list
     *
     * @param CarismaRequest $request
     * @return array
     */
    public static function getRequestParams(CarismaRequest $request)
    {
        return $request->all();
    }

    /**
     * Get string representation of the resource's timestamps
     *
     * @return mixed
     */
    protected function timestamps()
    {
        return $this->merge([
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ]);
    }

    /**
     * Get string representation of the soft deleting date
     *
     * @return mixed
     */
    protected function softDelete()
    {
        return $this->merge([
            'deleted_at' => $this->deleted_at->toDateTimeString(),
        ]);
    }
}