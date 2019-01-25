<?php

namespace Carisma;


use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

trait Authorizable
{
    /**
     * Determine if the given resource is authorizable.
     *
     * @return bool
     */
    public static function authorizable()
    {
        return ! is_null(Gate::getPolicyFor(static::newModel()));
    }

    /**
     * Determine if the resource should be available for the given request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public static function authorizeToViewAny(Request $request)
    {
        throw_unless(static::authorizedToViewAny($request), AuthorizationException::class);
    }

    /**
     * Determine if the resource should be available for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authorizedToViewAny(Request $request)
    {
        if (! static::authorizable()) {
            return true;
        }

        return method_exists(Gate::getPolicyFor(static::newModel()), 'viewAny')
            ? Gate::check('viewAny', get_class(static::newModel()))
            : true;
    }

    /**
     * Determine if the current user can view the given resource or throw an exception.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function authorizeToView(Request $request)
    {
        return $this->authorizeTo($request, 'view') && $this->authorizeToViewAny($request);
    }

    /**
     * Determine if the current user can view the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToView(Request $request)
    {
        return $this->authorizedTo($request, 'view') && $this->authorizedToViewAny($request);
    }

    /**
     * Determine if the current user can create new resources or throw an exception.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public static function authorizeToCreate(Request $request)
    {
        throw_unless(static::authorizedToCreate($request), AuthorizationException::class);
    }

    /**
     * Determine if the current user can create new resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authorizedToCreate(Request $request)
    {
        if (static::authorizable()) {
            return Gate::check('create', get_class(static::newModel()));
        }

        return true;
    }

    /**
     * Determine if the current user can delete the given resource or throw an exception.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     *
     * @throws AuthorizationException
     * @throws \Throwable
     */
    public function authorizeToDelete(Request $request)
    {
        return $this->authorizeTo($request, 'delete');
    }

    /**
     * Determine if the current user can delete the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToDelete(Request $request)
    {
        return $this->authorizedTo($request, 'delete');
    }

    /**
     * Determine if the current user can restore the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToRestore(Request $request)
    {
        return $this->authorizedTo($request, 'restore');
    }

    /**
     * Determine if the current user can force delete the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToForceDelete(Request $request)
    {
        return $this->authorizedTo($request, 'forceDelete');
    }

    /**
     * Determine if the current user can update the given resource or throw an exception.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function authorizeToUpdate(Request $request)
    {
        return $this->authorizeTo($request, 'update');
    }

    /**
     * Determine if the current user can update the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToUpdate(Request $request)
    {
        return $this->authorizedTo($request, 'update');
    }

    /**
     * Determine if the current user has a given ability.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $ability
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function authorizeTo(Request $request, $ability)
    {
        throw_unless($this->authorizedTo($request, $ability), AuthorizationException::class);
    }

    /**
     * Determine if the current user can view the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $ability
     * @return bool
     */
    public function authorizedTo(Request $request, $ability)
    {
        return static::authorizable() ? Gate::check($ability, $this->resource) : true;
    }
}