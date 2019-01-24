# DateTime

The `DateTime` field may be used to store a date-time value.

```php
use Carisma\Fields\DateTime;

DateTime::make('created_at')
```

You may customize the display format of your `DateTime` fields using the `format` method.

```php
DateTime::make('created_at')->format('d-m-Y')
```
