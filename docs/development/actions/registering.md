# Registering Action

Once you have defined an action, you are ready to attach it to a resource. Each resource generated by Carisma contains a `actions` method. To attach an action to a resource, you should simply add it to the array of actions returned by this method:

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
        new EmailAccountProfile,
    ];
}
```