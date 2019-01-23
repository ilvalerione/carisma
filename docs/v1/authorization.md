# Authorization

[TOC]

If you provide access to your application to your clients or large team of developers, you may wish to authorize certain requests. For example, perhaps only administrators may delete records of a specific resource. Thankfully, Carisma takes a simple approach to authorization that leverages many of the Laravel features you are already familiar with.

## Policies

To limit which users may view, create, update, or delete resources, Carisma leverages Laravel's [authorization policies](https://laravel.com/docs/authorization#creating-policies). Policies are simple PHP classes that organize authorization logic for a particular model or resource. For example, if your application is a blog, you may have a `Post` model and a corresponding `PostPolicy` within your application.

When manipulating a resource within Carisma, Carisma will automatically attempt to find a corresponding policy for the model. Typically, these policies will be registered in your application's `AuthServiceProvider`. If Carisma detects a policy has been registered for the model, it will automatically check that policy's relevant authorization methods before performing their respective actions, such as:

- `viewAny` *(todo)*
- `view`
- `create`
- `update`
- `delete`
- `restore`
- `forceDelete`

No additional configuration is required! So, for example, to determine which users are allowed to update a `Post` model, you simply need to define an `update` method on the model's corresponding policy class:

```php
<?php

namespace App\Policies;

use App\User;
use App\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the post.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    public function update(User $user, Post $post)
    {
        return $user->type == 'editor';
    }
}
```

> {warninig} Undefined Policy Methods
>
> If a policy exists but is missing a method for a particular action, the user will not be allowed to perform that action. So, if you have defined a policy, don't forget to define all of its relevant authorization methods.

## Hiding Entire Resource (not implemented)

If you would like to hide an entire Carisma resource from resource's list, you may define a `viewAny` method on the model's policy class. If no `viewAny` method is defined for a given policy, Carisma will assume that the user can view the resource:

```php
<?php

namespace App\Policies;

use App\User;
use App\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any posts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return in_array('view-posts', $user->permissions);
    }
}
```

## Disabling Authorization (not implemented)

If one of your Carisma resources' models has a corresponding policy, but you want to disable Carisma authorization for that resource, you may override the `authorizable` method on the Carisma resource:

```php
/**
 * Determine if the given resource is authorizable.
 *
 * @return bool
 */
public static function authorizable()
{
    return false;
}
```

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

> {info} Authorization & The "Can" Method
>
> To learn more about Laravel's authorization helpers and the `can` method, check out the full Laravel [authorization documentation](https://laravel.com/docs/authorization#via-the-user-model).