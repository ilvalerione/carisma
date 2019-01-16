<?php

namespace Carisma;


use Carisma\Requests\CarismaRequest;
use Illuminate\Support\Facades\Validator;

trait PerformValidation
{
    /**
     * Validate a resource creation request.
     *
     * @param CarismaRequest $request
     * @return array
     */
    public static function validateForCreating(CarismaRequest $request)
    {
        return Validator::make(
            $request->all(),
            array_merge(static::$rules, static::$creationRules)
        )->after(function ($validator) use ($request) {
            static::afterValidation($request, $validator);
            static::afterCreationValidation($request, $validator);
        })->validate();
    }

    /**
     * Validate a resource updating request.
     *
     * @param CarismaRequest $request
     * @return array
     */
    public static function validateForUpdating(CarismaRequest $request)
    {
        return Validator::make(
            $request->all(),
            array_merge(static::$rules, static::$updatingRules)
        )->after(function ($validator) use ($request) {
            static::afterValidation($request, $validator);
            static::afterUpdateValidation($request, $validator);
        })->validate();
    }

    /**
     * Handle any post-validation processing.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    protected static function afterValidation(CarismaRequest $request, $validator)
    {
        //
    }

    /**
     * Handle any post-creation validation processing.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    protected static function afterCreationValidation(CarismaRequest $request, $validator)
    {
        //
    }

    /**
     * Handle any post-update validation processing.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    protected static function afterUpdateValidation(CarismaRequest $request, $validator)
    {
        //
    }
}