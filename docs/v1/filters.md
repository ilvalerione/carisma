# Filters

Every resource has `/filters?filter=...` endpoint that allow you to attach custom filters 
to a resource collection to develop complex custom filtering logic.

### Creating new filter

```
php artisan carisma:filter MyCustomFilter
```

Carisma will generate a new filter in `\App\Carisma\Filters` namespace. 
You can put your filtering logic using fresh 
Query Builder instance passed to `apply` methods.