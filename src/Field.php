<?php

namespace Carisma;

use Carisma\Http\Requests\CarismaRequest;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class Field
{
    /**
     * The displayable name of the field.
     *
     * @var string
     */
    public $name;

    /**
     * The attribute / column name of the field.
     *
     * @var string
     */
    protected $attribute;

    /**
     * The field's resolved value.
     *
     * @var mixed
     */
    protected $value;

    /**
     * The callback to be used to resolve the field's value.
     *
     * @var \Closure
     */
    protected $resolveCallback;

    /**
     * The validation rules for creation and updates.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * The validation rules for creation.
     *
     * @var array
     */
    protected $creationRules = [];

    /**
     * The validation rules for updates.
     *
     * @var array
     */
    protected $updateRules = [];

    /**
     * Field constructor.
     *
     * @param string $name
     * @param mixed $attribute
     */
    public function __construct($name, $attribute = null)
    {
        $this->name = $name;
        $this->attribute = $attribute ?: $name;
    }

    /**
     * Create a new Field.
     *
     * @return Field
     */
    public static function make(...$arguments) :Field
    {
        return new static(...$arguments);
    }

    /**
     * Resolve the field's value.
     *
     * @param mixed $model
     * @return mixed
     */
    public function resolve($model)
    {
        if (is_callable($this->resolveCallback)) {
            return call_user_func($this->resolveCallback, $model->{$this->attribute});
        }

        return $model->{$this->attribute};
    }

    /**
     * Define the callback that should be used to resolve the field's value.
     *
     * @param  callable  $resolveCallback($modelAttribute)
     * @return $this
     */
    public function resolveUsing(callable $resolveCallback)
    {
        $this->resolveCallback = $resolveCallback;

        return $this;
    }

    /**
     * Set the validation rules for the field.
     *
     * @param  callable|array|string  $rules
     * @return $this
     */
    public function rules($rules)
    {
        $this->rules = ($rules instanceof Rule || is_string($rules)) ? func_get_args() : $rules;

        return $this;
    }

    /**
     * Get the validation rules for this field.
     *
     * @param  \Carisma\Http\Requests\CarismaRequest  $request
     * @return array
     */
    public function getRules(CarismaRequest $request)
    {
        return [$this->attribute => is_callable($this->rules)
            ? call_user_func($this->rules, $request)
            : $this->rules, ];
    }

    /**
     * Get the creation rules for this field.
     *
     * @param  \Carisma\Http\Requests\CarismaRequest  $request
     * @return array|string
     */
    public function getCreationRules(CarismaRequest $request)
    {
        $rules = [$this->attribute => is_callable($this->creationRules)
            ? call_user_func($this->creationRules, $request)
            : $this->creationRules, ];

        return array_merge_recursive(
            $this->getRules($request), $rules
        );
    }

    /**
     * Set the creation validation rules for the field.
     *
     * @param  callable|array|string  $rules
     * @return $this
     */
    public function creationRules($rules)
    {
        $this->creationRules = ($rules instanceof Rule || is_string($rules)) ? func_get_args() : $rules;

        return $this;
    }

    /**
     * Get the update rules for this field.
     *
     * @param  \Carisma\Http\Requests\CarismaRequest  $request
     * @return array
     */
    public function getUpdateRules(CarismaRequest $request)
    {
        $rules = [$this->attribute => is_callable($this->updateRules)
            ? call_user_func($this->updateRules, $request)
            : $this->updateRules, ];

        return array_merge_recursive(
            $this->getRules($request), $rules
        );
    }

    /**
     * Set the creation validation rules for the field.
     *
     * @param  callable|array|string  $rules
     * @return $this
     */
    public function updateRules($rules)
    {
        $this->updateRules = ($rules instanceof Rule || is_string($rules)) ? func_get_args() : $rules;

        return $this;
    }

    /**
     * Get the validation attribute for the field.
     *
     * @param  \Carisma\Http\Requests\CarismaRequest  $request
     * @return string
     */
    public function getValidationAttribute(CarismaRequest $request)
    {
        return $this->validationAttribute ?? Str::singular($this->attribute);
    }
}