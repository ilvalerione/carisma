# HasMany

The `HasMany` field corresponds to a situation where a resource is connected with many otems of another resource. For example, let's assume a `User`model `Has Many` `Post` models. We may add the relationship to our `User` Nova resource like so:

```php
use Carisma\Fields\Relationships\HasMany;
use App\Carisma\Post;

HasMany::make('posts', Post::class)
```

