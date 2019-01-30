<?php

namespace Carisma\Fields;

use Carisma\Http\Requests\CarismaRequest;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Closure;
use JsonSerializable;

class Field implements JsonSerializable
{
    use DisplayOptions, Authorization;

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
    public $attribute;

    /**
     * The field's resolved value.
     *
     * @var mixed
     */
    public $value;

    /**
     * The callback to be used to resolve the field's value.
     *
     * @var \Closure
     */
    protected $resolveCallback;

    /**
     * The callback to be used to fill the field's value on the model's attribute.
     *
     * @var \Closure
     */
    protected $fillCallback;

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
     * @param string|callable $attribute
     */
    public function __construct($name = null, $attribute = null)
    {
        $this->name = $name ?? $this->humanize();
        $this->attribute = $attribute ?? $this->name;
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
     * Prepare the field for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [$this->name => $this->value];
    }

    /**
     * Resolve the field's value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function resolve($model)
    {
        $attribute = $this->attribute;

        // If is a computed field
        if ($attribute instanceof Closure
            ||
            (is_callable($attribute) && is_object($attribute))
        ) {
            return $this->value = $attribute($model);
        }

        if (is_callable($this->resolveCallback)) {
            return $this->value = call_user_func($this->resolveCallback, $model->{$attribute});
        }

        $this->value = $model->{$attribute};
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

        return $this->formatFieldRules($request, array_merge_recursive(
            $this->getRules($request), $rules
        ));
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

        return $this->formatFieldRules($request, array_merge_recursive(
            $this->getRules($request), $rules
        ));
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
     * Perform any final formatting of the given validation rules.
     *
     * @param  \Carisma\Http\Requests\CarismaRequest  $request
     * @param array $rules
     * @return array
     */
    public function formatFieldRules($request, $rules)
    {
        $replacements = array_filter([
            '{{resource_id}}' => $request->id,
        ]);

        if (empty($replacements)) {
            return $rules;
        }

        return collect($rules)->map(function ($rules) use ($replacements) {
            return collect($rules)->map(function ($rule) use ($replacements) {
                return is_string($rule)
                    ? str_replace(array_keys($replacements), array_values($replacements), $rule)
                    : $rule;
            })->all();
        })->all();
    }

    /**
     * Get the validation attribute for the field.
     *
     * @param  \Carisma\Http\Requests\CarismaRequest  $request
     * @return string
     */
    /*public function getValidationAttribute(CarismaRequest $request)
    {
        return $this->validationAttribute ?? Str::singular($this->attribute);
    }*/

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  object  $model
     * @return void
     */
    public function fill(Request $request, $model)
    {
        if (isset($this->fillCallback)) {
            $this->fillAttributeFromCallback($request, $model);
        }

        $this->fillAttributeFromRequest($request, $model);
    }

    /**
     * Specify a callback that should be used to hydrate the model attribute for the field.
     *
     * @param  callable  $fillCallback
     * @return $this
     */
    public function fillUsing($fillCallback)
    {
        $this->fillCallback = $fillCallback;

        return $this;
    }

    /**
     * Hydrate the given attribute on the model based on the callback.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param $model
     * @return void
     */
    public function fillAttributeFromCallback(Request $request, $model)
    {
        call_user_func(
            $this->fillCallback, $request, $model, $this->attribute
        );
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  object  $model
     * @return void
     */
    protected function fillAttributeFromRequest(Request $request, $model)
    {
        if($request->exists($this->attribute)){
            $model->{$this->attribute} = $request[$this->attribute];
        }
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