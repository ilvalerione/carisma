# Filters

Each filter are composed of three components:
- The property or field name
- The operator such as eq, lte, gte
- The filter value

following the structure below:

```json
{
	"price": {
		"gte": 10,
		"lte": 100,
	}
}
```

The ability to combine multiple filters allow you to implement complex query 
on every column in the DB's table.

**Attention:** using multiple operators for the same property result in an implicit AND. 
What if the API user wanted to OR the filters instead. i.e. find all items where price 
is less than 10 OR greater than 100?

Every resource andpoint has `/filters` endpoint that allow you to develop custom filters 
coding directly your logic.

### Creating new filter

```
php artisan carisma:filter MyCustomFilter
```

Carisma will generate a new filter in `\App\Carisma\Filters` namespace. 
You can put your filtering logic using fresh 
Eloquent model instance passed to `apply` methods.