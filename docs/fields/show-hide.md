# Showing/Hiding fields

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
