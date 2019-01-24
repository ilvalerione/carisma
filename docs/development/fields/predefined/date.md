# Date

The `Date` field may be used to store a date value (without time):

```php
use Carisma\Fields\Date;

Date::make('birthday')
```

You may customize the display format of your `DateTime` fields using the `format` method.

```php
Date::make('birthday')->format('d m')
```
