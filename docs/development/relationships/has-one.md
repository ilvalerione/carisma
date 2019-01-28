# HasOne

The `HasOne` relationship corresponds to a situation where a resource is connected with one another. Let's assume a `User`model `Has One` `Address` model related. We may add the relationship to our `User` Carisma resource like so:

```php
use Carisma\Fields\Relationships\HasOne;
use App\Carisma\Address;

/**
 * Get the relationships available for the resource.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return array
 */
public function relationships(Request $request)
{
    return [
    	HasOne::make('address', Address::class),
    ];
}
```

Like other types of fields, relationship fields will automatically "snake case" the displayable name of the field to determine the underlying relationship method / attribute. However, you may explicitly specify the name of the relationship method by passing it as the third argument to the field's `make` method:

```php
use Carisma\Fields\Relationships\HasOne;

HasOne::make('indirizzo', Address::class, 'address')
```

