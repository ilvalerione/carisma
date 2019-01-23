# Resources

[TOC]

Carisma is a backend API automation framework for Laravel based applications. Of course, the primary feature of Carisma is the ability to administer your underlying database records using Eloquent by a RESTfull interface with the world. 

Carisma accomplishes this by allowing you to define a Carisma "resource" that corresponds to each Eloquent model in your application.

## Define Resources

By default, Carisma resources are stored in the `app/Carisma` directory of your application. You may generate a new resource using the `carisma:resource` Artisan command:

```shell
php artisan carisma:resource User
```

The most basic and fundamental property of a resource is its `model` property. This property tells Carisma which Eloquent model the resource corresponds to:

```php
use Carisma\Resource;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\User::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    protected static $search = ['name', 'email'];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Field::make('id')->onlyOnIndex(),

            Field::make('name')
                ->rules('required', 'max:255'),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function filters(Request $request)
    {
        return [
            // new MostProfitableCustomers,
        ];
    }

    /**
     * Get the actions available on the entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function actions(Request $request)
    {
        return [
            // new UploadAvatar,
        ];
    }
}
```

Freshly created Carisma resources only contain some fields definitions as an example. Don't worry, we'll add more fields to our resource soon.

## Registering Resources

Before resources are accessible via API endpoints, they must first be registered with Carisma. Resources are typically registered in your `app/Providers/RouteServiceProvider.php` file. This file typically contains various configuration and bootstrapping code related to your application's routes.

**In this way you can access to the APIs built with carisma under any url's prefix, middleware, namespace you want.**

There are two approaches to registering resources. You may use the `resourcesIn` method to instruct Carisma to register all Carisma resources within a given directory. Alternatively, you may use the `resources` method to manually register individual resources:

```php
use App\Carisma\User;
use App\Carisma\Post;

/**
 * Register the application's Carisma resources.
 *
 * @return void
 */
public function mapApiRoutes()
{
    Route::prefix('api')
        ->middleware('api')
        ->group(function (){
            Route::namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            // 1 - Register all resources in the given directory
            Carisma::resourcesIn(app_path('Carisma'))
                ->routes();
            
            // 2 - Register individual resource 
            Carisma::resources([
                User::class,
                Post::class,
            ])->routes();
        });
}
```

> {primary} Thanks to this structure you can easliy mixin your api implementation with APIs generated from Carisma.

Once your resources are registered with Carisma, they will be available via routes:

```shell
curl -i -H "Accept: application/json" -H "Content-Type: application/json" http://hostname/users
```

As uri key Carisma use the table name of the eloquent model related to the resource. You can personalize this key overriding the `uriKey`method in your resource class.

```php
/**
 * Define the uri path of the resource
 *
 * @return mixed
 */
public static function uriKey()
{
    return 'my-posts';
}
```

## Resource Events

All Carisma operations use Eloquent model of the resource so are available all native events you are familiar with. Therefore, it is easy to listen for CRUD events triggered by Carisma and react to them. You have dedicated hooks in your resource for each CRUD event.

```php
// Fired when update and create resource 
public static function onSaving(CarismaRequest $request, Model $model)
public static function onSaved(CarismaRequest $request, Model $model)
    
public static function onCreating(CarismaRequest $request, Model $model)
public static function onCreated(CarismaRequest $request, Model $model)

public static function onUpdating(CarismaRequest $request, Model $model)
public static function onUpdated(CarismaRequest $request, Model $model)

public static function onDeleting(CarismaRequest $request, Model $model)
public static function onDeleted(CarismaRequest $request, Model $model)
```

You are free to override this methods to attach fire actions to the CRUD operations.

