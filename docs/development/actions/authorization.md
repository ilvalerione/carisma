# Authorization

If you would like to only expose a given action to certain users, you may chain the `canRun` method onto your action registration. The `canRun` method accepts a Closure which should return `true` or `false`. The Closure will receive the incoming HTTP request as well as the model the user would like to run the action against:

```php
/**
 * Get the actions available for the resource.
 *
 * @param  \Illuminate\Http\Request $request
 * @return array
 */
public static function actions(Request $request)
{
    return [
        (new EmailAccountProfile)
        	->canRun(function ($request, $model){
        		return $request->user()->can('emailAnyAccountProfile', $model);
        	}),
    ];
}
```

In the example above, we are using Laravel's `Authorizable` trait's `can` method on our `User` model to determine if the authorized user is authorized for the `emailAnyAccountProfile` action. However, since proxying to authorization policy methods is a common use-case for `canRun`, you may use the `canRunWhen` method to achieve the same behavior passing only the name of the ability to check:

```php
/**
 * Get the actions available for the resource.
 *
 * @param  \Illuminate\Http\Request $request
 * @return array
 */
public static function actions(Request $request)
{
    return [
        (new EmailAccountProfile)->canRunWhen('emailAnyAccountProfile'),
    ];
}
```

> Authorization & The "Can" Method
>
> To learn more about Laravel's authorization helpers and the `can` method, check out the full Laravel [authorization documentation](https://laravel.com/docs/authorization#via-the-user-model).