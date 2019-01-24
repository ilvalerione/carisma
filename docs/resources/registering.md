# Registering Resources

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

> Thanks to this structure you can easliy mixin your personal api implementation with APIs generated from Carisma.

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
