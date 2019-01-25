# Hiding Entire Resource

If you would like to hide an entire Carisma resource from resource's list, you may define a `viewAny` method on the model's policy class. 

Since when you create a fresh policy class using artisan command `make:policy --model=YourModel` there's no `viewAny` method by default if no `viewAny` method is defined for a given policy, Carisma will assume that the user can view the resource:

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
