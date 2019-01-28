# Relationships

Each Carisma resource contains a `relationships` method. This method returns an array of relationships, which generally extend the `Carisma\Fields\Field` class.

To add a relationship to a resource, we can simply add it to the resource's `relationships` method. Typically, relationship may be created using their static `make` method. This method accepts the name of the eloquent relationship method / attribute and the related Carisma resource to use to represent the connected resource:

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

From the API users perspective each resource can be connected to one or many elements of another resource.

Obviously there are many technique to implement these types of relationships based on your database schema (*has one, belongs to, has many, has many through, morph to many, etc*...). Carisma is an abstraction so the ORM will be delegated to implement the right technique to query your database.
