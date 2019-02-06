# Fill Underlying Attribute

When you create or update a resource every attribute on the model will be filled with given attribute values sent in the request to update the underlying database table.

The `fillUsing` method allows you to customize how a field is filled before it is saved on the database. This method accepts a callback which receives the request, the eloquent model instance and the name of the given attribute:

```php
use Carisma\Fields\Field;

Field::make('name')->fillUsing(function($request, $model, $attribute){
    $model->name = ucfirst($request->name);
}),
```

