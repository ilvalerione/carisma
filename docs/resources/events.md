# Resource Events

All Carisma operations use Eloquent model of the resource so are available all native events you are familiar with. Therefore, it is easy to listen for CRUD events triggered by Carisma and react to them. You have dedicated hooks in your resource for each CRUD event.

```php
// Fired when update and create resource 
public static function onSaving(CarismaRequest $request, Model $model)
public static function onSaved(CarismaRequest $request, Model $model)
    
public static function onCreating(CarismaRequest $request, Model $model)
public static function onCreated(CarismaRequest $request, Model $model)

public static function onUpdating(CarismaRequest $request, Model $model)
public static function onUpdated(CarismaRequest $request, Model $model)

public static function onDeleting(CarismaRequest $request, Model $model)
public static function onDeleted(CarismaRequest $request, Model $model)
```

You are free to override this methods to attach fire actions to the CRUD operations.

