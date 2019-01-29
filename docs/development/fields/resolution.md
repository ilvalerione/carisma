# Field Resolution / Formatting

The `resolveUsing` method allows you to customize how a field is formatted after it is retrieved from your database but before it is sent to the response. This method accepts a callback which receives the raw value of the underlying database column:

```php
Field::make('name')->resolveUsing(function ($value) {
	return ucfirst($value);
}),
```

