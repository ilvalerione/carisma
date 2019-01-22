# Resources

```
php artisan carisma:resource User
```


```php

class User extends CarismaResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

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

            Field::make('email')
                ->rules('required', 'max:255', 'email')
                ->creationRules('unique:users,email'),

            Password::make()
                ->creationRules('required', 'string', 'min:6', 'confirmed')
                ->updateRules('nullable', 'string', 'min:6'),

            DateTime::make('email_verified_at')->nullable(),

            $this->merge($this->timestamps()),
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
            // new MostProfitableCustomers(),
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
            // new LogData('log'),
        ];
    }
}

```