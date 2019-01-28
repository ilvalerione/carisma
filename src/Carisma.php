<?php

namespace Carisma;

use Carisma\Exceptions\CarismaException;
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
        Route::get('{resource}/filters', 'Carisma\Http\Controllers\FilterController@handle');
        Route::post('{resource}/actions', 'Carisma\Http\Controllers\ActionController@handle');

        Route::get('{resource}', 'Carisma\Http\Controllers\PaginateController@index');
        Route::get('{resource}/{id}', 'Carisma\Http\Controllers\ShowController@handle');
        Route::get('{resource}/{id}/relationships/{relationship}', 'Carisma\Http\Controllers\RelationshipController@handle');

        Route::post('{resource}', 'Carisma\Http\Controllers\StoreController@handle');
        Route::put('{resource}/{id}', 'Carisma\Http\Controllers\UpdateController@handle');
        Route::delete('{resource}/{id}', 'Carisma\Http\Controllers\DestroyController@handle');
    }
}