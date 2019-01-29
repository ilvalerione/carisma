# Computed fields

In addition to displaying fields that are associated with columns in your database, Carisma allows you to create "computed fields". Computed fields may be used to display computed values that are not associated with a database column and are read only by definition.

These fields may be created by passing a callable (instead of a column name) as the second argument to the field's `make` method:

```php
Field::make('name', function ($model){
    return $model->first_name.' '.$model->last_name;
})
```

!> **Model Access** - As you may have noticed in the example above, inside the callback will be injected the `$model` parameter to access the resource's underlying Eloquent model attributes and relationships.

