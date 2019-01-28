# Defining Fields

Each Carisma resource contains a `fields` method. This method returns an array of fields, which generally extend the `Carisma\Fields\Field` class. Carisma ships with a variety of fields out of the box, including fields for Passwords, dates, and more.

To add a field to a resource, we can simply add it to the resource's `fields` method. Typically, fields may be created using their static `make` method. This method accepts several arguments; however, you usually only need to pass the "human readable" name of the field. Carisma will automatically use the "snake case" of the class name to determine the underlying database column:

```php
use Carisma\Fields\Field;
use Carisma\Fields\Id;

/**
 * Get the fields displayed by the resource.
 *
 * @param  \Illuminate\Http\Request $request
 * @return array
 */
public function fields(Request $request)
{
    return [
        Id::make(),

        Field::make('name')
        	->rules('required', 'string', 'max:255'),
    ];
}
```

# Field column convention

As noted above, Carisma will use the first parameter of field as a name of the attribute in json representation and is use to determine the underlying database column. However, if necessary, you may pass the database column name as the second argument to the field's `make` method:

```php
Field::make('name', 'name_column')
```
