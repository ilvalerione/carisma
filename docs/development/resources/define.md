# Define Resources

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
