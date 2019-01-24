# Run Action

You can run the action using endpoint `/{resource}/actions?action=email_account_profile&resources=1,2,3` that allow you to run action related to the given resource.

As mentioned above you can use the "snake case" of the class name as action's name in the uri parameter. If you want customize this value you can pass your custom name as first argument of your action constructor:

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
        // Run action using "/users/actions?action=email-profile
        new EmailAccountProfile('email-profile'),
    ];
}
```

If `resources` parameter is not exists or is empty the action will be performed on the entire database result-set for the given resource. In this case I recommend you to `chunk` the given collection:

```php
/**
 * Perform the action on the given models.
 *
 * @param  \Carisma\Http\Requests\ActionRequest  $request
 * @param  \Illuminate\Support\Collection  $models
 * @return mixed
 */
public function run(ActionRequest $request, Collection $models)
{
    $models->chunk(200)->each(function ($subCollection) {
        
        foreach ($subCollection as $model){
            (new AccountData($model))->send();
        }
        
    });
}
```
