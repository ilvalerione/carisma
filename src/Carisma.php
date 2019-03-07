<?php

namespace Carisma;

use Carisma\Events\ServingCarisma;
use Carisma\Exceptions\CarismaException;
use Carisma\Http\Middlewares\Authorize;
use Carisma\Http\Middlewares\ServeCarisma;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Finder\Finder;
use Illuminate\Support\Str;
use ReflectionClass;

class Carisma
{
    /**
     * List of available resources.
     * 
     * @var array 
     */
    protected $resources = [
        // 'uri_key' => ResourceClass::class
    ];

    /**
     * The callback that should be used to authenticate Carisma users.
     *
     * @var \Closure
     */
    protected static $authUsing;

    /**
     * Register an event listener for the Carisma "serving" event.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function serving($callback)
    {
        Event::listen(ServingCarisma::class, $callback);
    }

    /**
     * Register the Carisma authentication callback.
     *
     * @param  \Closure  $callback
     * @return static
     */
    public static function auth($callback)
    {
        static::$authUsing = $callback;

        return new static;
    }

    /**
     * Determine if the given request can access the Carisma API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authCheck($request)
    {
        return (static::$authUsing ?: function () {
            return true;
        })($request);
    }

    /**
     * Register list of resources.
     *
     * @param array $resources
     * @return Carisma
     * @throws CarismaException
     */
    public function resources(array $resources) : Carisma
    {
        foreach ($resources as $class){
            $this->addResource($class);
        }

        return $this;
    }

    /**
     * Get a resource class
     *
     * @param string $uriKey
     * @return mixed
     */
    public function resource(string $uriKey)
    {
        return $this->resources[$uriKey];
    }

    /**
     * Register resource
     *
     * @param string $class
     * @return Carisma
     * @throws CarismaException
     */
    public function addResource(string $class) : Carisma
    {
        if(class_exists($class)){
            $this->resources[$class::uriKey()] = $class;
            return $this;
        }

        throw new CarismaException("Resource Class not found for resource: {$class}");
    }

    /**
     * Register all of the resource classes in the given directory.
     *
     * @param  string $directory
     * @return Carisma
     * @throws CarismaException
     * @throws \ReflectionException
     */
    public function resourcesIn($directory) : Carisma
    {
        $namespace = app()->getNamespace();

        foreach ((new Finder)->in($directory)->files() as $resource) {

            $resource = $namespace.str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($resource->getPathname(), app_path().DIRECTORY_SEPARATOR)
                );

            if (is_subclass_of($resource, Resource::class) &&
                ! (new ReflectionClass($resource))->isAbstract()) {
                $this->addResource($resource);
            }
        }

        return $this;
    }

    /**
     * Carisma routes
     */
    public function routes()
    {
        Route::middleware([
            Authorize::class,
            ServeCarisma::class,
        ])->group(function (){
            Route::get('{resource}/filters/{filter}', 'Carisma\Http\Controllers\FilterController@handle');
            Route::post('{resource}/actions/{action}', 'Carisma\Http\Controllers\ActionController@handle');

            Route::get('{resource}', 'Carisma\Http\Controllers\PaginateController@index');
            Route::get('{resource}/{id}', 'Carisma\Http\Controllers\ShowController@handle');

            Route::get('{resource}/{id}/relationships/{relationship}', 'Carisma\Http\Controllers\RelationshipController@get');
            Route::post('{resource}/{id}/relationships/{relationship}/attach', 'Carisma\Http\Controllers\RelationshipController@attach');
            Route::post('{resource}/{id}/relationships/{relationship}/detach', 'Carisma\Http\Controllers\RelationshipController@detach');
            Route::post('{resource}/{id}/relationships/{relationship}/sync', 'Carisma\Http\Controllers\RelationshipController@sync');
            Route::post('{resource}/{id}/relationships/{relationship}/toggle', 'Carisma\Http\Controllers\RelationshipController@toggle');

            Route::post('{resource}', 'Carisma\Http\Controllers\StoreController@handle');
            Route::put('{resource}/{id}', 'Carisma\Http\Controllers\UpdateController@handle');
            Route::delete('{resource}/{id}', 'Carisma\Http\Controllers\DestroyController@handle');
        });
    }
}