<?php

namespace Carisma;

use Carisma\Fields\Field;
use Carisma\Http\Requests\CarismaRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MergeValue;

abstract class CarismaResource extends JsonResource
{
    use HasHooks,
        Authorizable,
        PerformValidation,
        PerformSearch,
        FillFields,
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
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [];

        foreach ($this->filter($this->fields($request)) as $field) {
            $data[$field->name] = $field->resolve($this);
        }

        return $data;
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
        return [
            Field::make('created_at')->resolveUsing(function($attribute){
                return $attribute->toDateTimeString();
            }),
            Field::make('updated_at')->resolveUsing(function($attribute){
                return $attribute->toDateTimeString();
            })
        ];
    }

    /**
     * Get string representation of the soft deleting date
     *
     * @return mixed
     */
    protected function softDelete()
    {
        return Field::make('deleted_at')->resolveUsing(function($attribute){
            return $attribute->toDateTimeString();
        });
    }
}