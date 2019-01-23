# Fields

[TOC]

## Defining Fields

Each Carisma resource contains a `fields` method. This method returns an array of fields, which generally extend the `Carisma\Fields\Field` class. Carisma ships with a variety of fields out of the box, including fields for Passwords, dates, and more.

To add a field to a resource, we can simply add it to the resource's `fields` method. Typically, fields may be created using their static `make` method. This method accepts several arguments; however, you usually only need to pass the "human readable" name of the field. Carisma will automatically use the "snake case" of the class name to determine the underlying database column:

```php
/**
 * Get the fields displayed by the resource.
 *
 * @param  \Illuminate\Http\Request $request
 * @return array
 */
public function fields(Request $request)
{
    return [
        Field::make('id')->exceptOnForms(),

        Field::make('name')
        	->rules('required', 'string', 'max:255'),
    ];
}
```

## Field column convention

As noted above, Carisma will use the first parameter of field as a name of the attribute in json representation and is use to determine the underlying database column. However, if necessary, you may pass the database column name as the second argument to the field's `make` method:

```php
Field::make('name', 'name_column')
```

## Showing/Hiding fields

Often, you will only want to display a field in certain situations. For example, there is typically no need to show a `Password` field on a resource index listing. Likewise, you may wish to not use a `Created At` field on the creation / update operations. Carisma makes it a breeze to hide / show fields on certain scenarios.

The following methods may be used to show / hide (use / don't use) fields based on the context:

- `hideFromIndex`
- `hideFromDetail`
- `hideWhenCreating`
- `hideWhenUpdating`
- `onlyOnIndex`
- `onlyOnDetail`
- `onlyOnForms`
- `exceptOnForms`

You may chain any of these methods onto your field's definition in order to instruct Carisma where the field should be used:

```php
Field::make('name')->hideFromIndex()
```

# Predefined field types

Carisma ships with a variety of field types. So, let's explore all of the available types and their options:

- Password
- DateTime
- Date

## Password

The `Password` field will automatically preserve the password that is currently stored in the database if the incoming password field is empty. Therefore, a typical password field definition might look like the following:

```php
Password::make()
    ->creationRules('required', 'string', 'min:6', 'confirmed')
    ->updateRules('nullable', 'string', 'min:6'),
```

## DateTime

The `DateTime` field may be used to store a date-time value.

```php
use Carisma\Fields\DateTime;

DateTime::make('created_at')
```

You may customize the display format of your `DateTime` fields using the `format` method.

```php
DateTime::make('created_at')->format('d-m-Y')
```

## Date

The `Date` field may be used to store a date value (without time):

```php
use Carisma\Fields\Date;

Date::make('birthday')
```

You may customize the display format of your `DateTime` fields using the `format` method.

```php
Date::make('birthday')->format('d m')
```

## Shortcut

Always keep in mind that CarismaResource extrends Laravel's JsonResource class, so you can use all its functionality to write clever json representation of your model.

There are some typical attributes that you will probably add to any resources. Resource class provide you some useful method to attach most common attributes sets to the json quickly and easily.

```php
/**
 * Get the fields displayed by the resource.
 *
 * @param  \Illuminate\Http\Request $request
 * @return array
 */
public function fields(Request $request)
{
    return [
        Field::make('id')->exceptOnForms(),

        Field::make('name')
        	->rules('required', 'string', 'max:255'),
        
        // Add "created_at" and "updated_at" as DateTime fields
        $this->timestamps(),
        
        // Add "deleted_at" as DateTime field
        $this->softDelete(),
    ];
}
```

# Field Resolution / Formatting

The `resolveUsing` method allows you to customize how a field is formatted after it is retrieved from your database but before it is sent to the response. This method accepts a callback which receives the raw value of the underlying database column:

```php
Field::make('name')->resolveUsing(function ($value) {
	return ucfirst($value);
}),
```

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

