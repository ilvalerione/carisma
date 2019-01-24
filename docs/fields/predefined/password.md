# Password

The `Password` field will automatically preserve the password that is currently stored in the database if the incoming password field is empty. Therefore, a typical password field definition might look like the following:

```php
Password::make()
    ->creationRules('required', 'string', 'min:6', 'confirmed')
    ->updateRules('nullable', 'string', 'min:6'),
```
