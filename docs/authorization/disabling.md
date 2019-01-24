# Disabling Authorization (not implemented)

If one of your Carisma resources' models has a corresponding policy, but you want to disable Carisma authorization for that resource, you may override the `authorizable` method on the Carisma resource:

```php
/**
 * Determine if the given resource is authorizable.
 *
 * @return bool
 */
public static function authorizable()
{
    return false;
}
```
