# Fields

Sometimes you may want to hide certain fields from a group of users. You may easily accomplish this by chaining the `canSee` method onto your field definition. The `canSee` method accepts a Closure which should return `true` or `false`. The Closure will receive the incoming HTTP request:

```php
Field::make('name')
    ->canSee(function ($request) {
        return $request->user()->can('viewProfile', $this);
    }),
```

In the example above, we are using Laravel's `Authorizable` trait's `can` method on our `User` model to determine if the authorized user is authorized for the `viewProfile` action. However, since proxying to authorization policy methods is a common use-case for `canSee`, you may use the `canSeeWhen` method to achieve the same behavior. The `canSeeWhen` method has the same method signature as the `Illuminate\Foundation\Auth\Access\Authorizable` trait's `can` method:

```php
Field::make('name')
	->canSeeWhen('viewProfile', $this),
```

> **Authorization & The "Can" Method**
>
> To learn more about Laravel's authorization helpers and the `can` method, check out the full Laravel [authorization documentation](https://laravel.com/docs/authorization#via-the-user-model).
