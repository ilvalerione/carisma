<?php

namespace Carisma;

use Carisma\Http\Requests\CarismaRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class CarismaResource extends JsonResource
{
    use HasHooks,
        Authorizable,
        PerformValidation,
        PerformSearch,
        InteractWithFilters;

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
            static::applySearch(static::newModel()->newQuery(), $request->search),
            $request->getFilters()
        );
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