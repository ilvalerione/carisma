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