# Authorization

If you would like to only expose a given filter to certain users, you may chain the `canRun` method onto your filter registration. The `canRun` method accepts a Closure which should return `true` or `false`. The Closure will receive the incoming HTTP request:

```php
use App\User;

/**
 * Get the filters available for the resource.
 *
 * @param  \Illuminate\Http\Request $request
 * @return array
 */
public static function filters(Request $request)
{
    return [
        (new MostValuableUsers)->canRun(function ($request){
            return $request->user()->can(
                'viewValuableUsers', User::class
            );
        }),
    ];
}
```

In the example above, we are using Laravel's `Authorizable` trait's `can` method on our `User` model to determine if the authorized user is authorized for the `viewValuableUsers` action. 

However, since proxying to authorization policy methods is a common use-case for `canRun`, you may use the `canRunWhen` method to achieve the same behavior. The `canRunWhen` method has the same method signature as the `Illuminate\Foundation\Auth\Access\Authorizable` trait's `can` method:

```php
/**
 * Get the filters available for the resource.
 *
 * @param  \Illuminate\Http\Request $request
 * @return array
 */
public static function filters(Request $request)
{
    return [
        (new MostValuableUsers)
        	->canRunWhen('viewValuableUsers', $this),
    ];
}
```

