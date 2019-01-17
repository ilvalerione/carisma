<?php

namespace Carisma;

use Carisma\Http\Requests\CarismaRequest;
use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class Field
{
    /**
     * The displayable name of the field.
     *
     * @var string
     */
    protected $name;

    /**
     * The attribute / column name of the field.
     *
     * @var string
     */
    public $attribute;

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
    public $resolveCallback;

    /**
     * The validation rules for creation and updates.
     *
     * @var array
     */
    public $rules = [];

    /**
     * The validation rules for creation.
     *
     * @var array
     */
    public $creationRules = [];

    /**
     * The validation rules for updates.
     *
     * @var array
     */
    public $updateRules = [];

    /**
     * Field constructor.
     *
     * @param string $name
     * @param mixed $attribute
     */
    public function __construct($name, $attribute = null)
    {
        $this->name = $name;
        $this->attribute = $attribute;
    }

    /**
     * Create a new Field.
     *
     * @return static
     */
    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }

    /**
     * Resolve the field's value.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     * @return void
     */
    public function resolve($resource, $attribute = null)
    {
        $attribute = $attribute ?? $this->attribute;

        if ($attribute instanceof Closure || (is_callable($attribute) && is_object($attribute))) {
            return $this->resolveComputedAttribute($attribute);
        }

        if (! $this->resolveCallback) {
            $this->value = $this->resolveAttribute($resource, $attribute);
        }

        $value = data_get($resource, str_replace('->', '.', $attribute), '___missing');

        if (is_callable($this->resolveCallback) && $value !== '___missing') {
            $this->value = call_user_func($this->resolveCallback, $value, $resource);
        }
    }

    /**
     * Resolve the given attribute from the given resource.
     *
     * @param  mixed  $resource
     * @param  string  $attribute
     * @return mixed
     */
    protected function resolveAttribute($resource, $attribute)
    {
        return data_get($resource, str_replace('->', '.', $attribute));
    }

    /**
     * Resolve a computed attribute.
     *
     * @param  callable  $attribute
     * @return void
     */
    protected function resolveComputedAttribute($attribute)
    {
        $this->value = $attribute();

        $this->attribute = 'ComputedField';
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