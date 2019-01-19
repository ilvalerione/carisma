<?php

namespace Carisma;


use Carisma\Http\Requests\CarismaRequest;
use Illuminate\Support\Facades\Validator;

trait PerformValidation
{
    /**
     * Validate a resource creation request.
     *
     * @param CarismaRequest $request
     * @return array
     */
    public static function validateForCreate(CarismaRequest $request)
    {
        return Validator::make(
            $request->all(),
            (new static(static::newModel()))
                ->creationFields($request)
                ->mapWithKeys(function ($field) use ($request) {
                    return $field->getCreationRules($request);
                })->all()
        )->after(function ($validator) use ($request) {
            static::afterValidation($request, $validator);
            static::afterValidationOnCreate($request, $validator);
        })->validate();
    }

    /**
     * Validate a resource updating request.
     *
     * @param CarismaRequest $request
     * @return array
     */
    public static function validateForUpdate(CarismaRequest $request)
    {
        return Validator::make(
            $request->all(),
            (new static(static::newModel()))
                ->updateFields($request)
                ->mapWithKeys(function ($field) use ($request) {
                    return $field->getUpdateRules($request);
                })->all()
        )->after(function ($validator) use ($request) {
            static::afterValidation($request, $validator);
            static::afterValidationOnUpdate($request, $validator);
        })->validate();
    }

    /**
     * Handle any post-validation processing.
     *
     * @param CarismaRequest $request
     * @param  \Illuminate\Validation\Validator $validator
     * @return void
     */
    protected static function afterValidation(CarismaRequest $request, $validator)
    {
        //
    }

    /**
     * Handle any post-creation validation processing.
     *
     * @param CarismaRequest $request
     * @param  \Illuminate\Validation\Validator $validator
     * @return void
     */
    protected static function afterValidationOnCreate(CarismaRequest $request, $validator)
    {
        //
    }

    /**
     * Handle any post-update validation processing.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    protected static function afterValidationOnUpdate(CarismaRequest $request, $validator)
    {
        //
    }
}