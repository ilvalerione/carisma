<?php

namespace Carisma;


use Carisma\Requests\CarismaRequest;
use Illuminate\Database\Eloquent\Model;

trait HasHooks
{
    /**
     * Before save (create, update)
     *
     * @param CarismaRequest $request
     * @param Model $model
     */
    public static function onSaving(CarismaRequest $request, Model $model)
    {
        //
    }

    /**
     * After save (create, update)
     *
     * @param CarismaRequest $request
     * @param Model $model
     */
    public static function onSaved(CarismaRequest $request, Model $model)
    {
        //
    }

    /**
     * Before create
     *
     * @param CarismaRequest $request
     * @param Model $model
     */
    public static function onCreating(CarismaRequest $request, Model $model)
    {
        //
    }

    /**
     * After create
     *
     * @param CarismaRequest $request
     * @param Model $model
     */
    public static function onCreated(CarismaRequest $request, Model $model)
    {
        //
    }

    /**
     * Before update
     *
     * @param CarismaRequest $request
     * @param Model $model
     */
    public static function onUpdating(CarismaRequest $request, Model $model)
    {
        //
    }

    /**
     * After update
     *
     * @param CarismaRequest $request
     * @param Model $model
     */
    public static function onUpdated(CarismaRequest $request, Model $model)
    {
        //
    }

    /**
     * Before delete
     *
     * @param CarismaRequest $request
     * @param Model $model
     */
    public static function onDeleting(CarismaRequest $request, Model $model)
    {
        //
    }

    /**
     * After delete
     *
     * @param CarismaRequest $request
     * @param Model $model
     */
    public static function onDeleted(CarismaRequest $request, Model $model)
    {
        //
    }
}