# Run filter

You can run the filter using endpoint `/{resource}/filters?filter=most_valuable_users` that allow you to run filters related to the given resource and get back paginated result.

As mentioned above you can use the "snake case" of the class name as filter's name in the uri parameter. If you want customize this value you can pass your custom name as first argument of your filter constructor:

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
        // Run filter using "/users/filters?filter=most-valuable&perPage=10"
    	new MostValuableUsers('most-valuable'),
    ];
}
```
