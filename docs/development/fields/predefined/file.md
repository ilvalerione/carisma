# File

To illustrate the behavior of Carisma file upload fields, let's assume our application's users can upload "profile photos" to their account. So, our `users` database table will have a `profile_photo` column. This column will contain the path to the profile on disk, or, when using a cloud storage provider such as Amazon S3, the profile photo's path within its "bucket".

# Define the field

Next, let's attach the file field to our `User` resource. In this example, we will create the field and instruct it to store the underlying file on the `public` disk. This disk name should correspond to a disk name in your `config/filesystems.php` configuration file:

```php
use Carisma\Fields\File;

File::make('profile_photo')->disk('public'),
```

# How Files are stored

When a file is uploaded using this field, Carisma will use Laravel's [Flysystem integration](https://laravel.com/docs/filesystem) to store the file on the disk of your choosing with a randomly generated filename. Once the file is stored, Carisma will store the relative path to the file in the file field's underlying database column.

To illustrate the default behavior of the `File` field, let's take a look at an equivalent route that would store the file in the same way:

```php
use Illuminate\Http\Request;

Route::post('/photo', function (Request $request) {
    $path = $request->profile_photo->store('/', 'public');

    $request->user()->update([
        'profile_photo' => $path,
    ]);
});
```

Of course, once the file has been stored, you may retrieve it within your application using the Laravel `Storage` facade:

```php
use Illuminate\Support\Facades\Storage;

Storage::get($user->profile_photo);

Storage::url($user->profile_photo);
```

> To learn more about file storage in Laravel, check out the [Laravel file storage documentation](https://laravel.com/docs/filesystem).

# Customizing File Storage

Previously we learned that, by default, Carisma stores the file using the `store` method of the `Illuminate\Http\UploadedFile` class. However, you may fully customize this behavior based on your application's needs.

## Customizing The Name / Path

If you only need to customize the name or path of the stored file on disk, you may use the `path` and `storeAs` methods of the `File` field:

```php
use Illuminate\Http\Request;
use Carisma\Fields\File;

/**
 * Get the fields displayed by the resource.
 *
 * @param  \Illuminate\Http\Request $request
 * @return array
 */
public function fields(Request $request)
{
    return [
        File::make('attachment')
            ->disk('s3')
            ->path($request->user()->id.'-attachments')
            ->storeAs(function (Request $request) {
                return sha1($request->attachment->getClientOriginalName());
            }),
    ];
}
```

## Customizing The Entire Storage Process

However, if you would like to take **total** control over the file storage logic of a field, you may use the `store` method. The `store` method accepts a callable which receives the incoming HTTP request and the model instance associated with the request:

```php
use Illuminate\Http\Request;

File::make('attachment')
    ->store(function (Request $request, $model) {
        return request->attachment->store('/', 's3');
    })
```

As you can see in the example above, the `store` callback is returning an array of keys and values. These key / value pairs are mapped onto your model instance before it is saved to the database, allowing you to update one or many of the model's database columns after your file is stored.

Of course, performing all of your file storage logic within a Closure can cause your resource to become bloated. For that reason, Carisma allows you to pass an "invokable" object to the `store` method:

```php
File::make('attachment')->store(new StoreAttachment)
```

The invokable object should be a simple PHP class with a single `__invoke` method:

```php
<?php

namespace App\Carisma;

use Illuminate\Http\Request;

class StoreAttachment
{
    /**
     * Store the incoming file upload.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return array
     */
    public function __invoke(Request $request, $model)
    {
        return $request->attachment->store('/', 's3');
    }
}
```

