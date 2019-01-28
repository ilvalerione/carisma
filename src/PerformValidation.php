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
    public function validateForCreate(CarismaRequest $request)
    {
        return Validator::make(
            $request->all(),
            $this->creationFields($request)->mapWithKeys(function ($field) use ($request) {
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
    public function validateForUpdate(CarismaRequest $request)
    {
        return Validator::make(
            $request->all(),
            $this->updateFields($request)->mapWithKeys(function ($field) use ($request) {
                return $field->getUpdateRules($request);
            })->all()
        )->after(function ($validator) use ($request) {
            $this->afterValidation($request, $validator);
            $this->afterValidationOnUpdate($request, $validator);
        })->validate();
    }

    /**
     * Handle any post-validation processing.
     *
     * @param CarismaRequest $request
     * @param  \Illuminate\Validation\Validator $validator
     * @return void
     */
    protected function afterValidation(CarismaRequest $request, $validator)
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
    protected function afterValidationOnCreate(CarismaRequest $request, $validator)
    {
        //
    }

    /**
     * Handle any post-update validation processing.
     *
     * @param CarismaRequest $request
     * @param  \Illuminate\Validation\Validator $validator
     * @return void
     */
    protected function afterValidationOnUpdate(CarismaRequest $request, $validator)
    {
        //
    }
}