# Policies

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

!> Undefined Policy Methods
!>
!> If a policy exists but is missing a method for a particular action, the user will not be allowed to perform that action. So, if you have defined a policy, don't forget to define all of its relevant authorization methods.
