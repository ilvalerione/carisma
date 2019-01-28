# HasMany

The `HasMany` field corresponds to a situation where a resource is connected with many items of another resource. For example, let's assume a `User`model `Has Many` `Post` models related. We may add the relationship to our `User` Carisma resource like so:

```php
use Carisma\Fields\Relationships\HasMany;
use App\Carisma\Post;

/**
 * Get the relationships available for the resource.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return array
 */
public function relationships(Request $request)
{
    return [
    	HasMany::make('posts', Post::class),
    ];
}
```

