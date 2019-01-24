# Validation

Unless you like to live dangerously, any Carisma fields on the creation / update operation will need some validation. Thankfully, it's a cinch to attach all of the Laravel validation rules you're familiar with to your Carisma resource fields. Let's get started.

## Attaching Rules

When defining a field on a resource, you may use the `rules` method to attach [validation rules](https://laravel.com/docs/validation#available-validation-rules) to the field:

```php
Field::make('name')
	->rules('required', 'max:255'),
```

Of course, if you are leveraging Laravel's support for [validation rule objects](https://laravel.com/docs/validation#using-rule-objects), you may attach those to resources as well:

```php
use App\Rules\ValidateName;

Field::make('name')
    ->rules('required', new ValidateName),
```

Additionally, you may use [custom Closure rules](https://laravel.com/docs/validation#using-closures) to validate your resource fields:

```php
Field::make('name')
    ->rules('required', function($attribute, $value, $fail) {
        if (strtoupper($value) !== $value) {
            return $fail('The '.$attribute.' field must be uppercase.');
        }
    }),
```

## Creation / Update Rules

If you would like to define rules that only apply when a resource is being created or updated, you may use the `creationRules` or `updateRules` methods:

```php
Field::make('email')
    ->rules('required', 'email', 'max:255')
    ->creationRules('unique:users,email')
    ->updateRules('unique:users,email')
```

