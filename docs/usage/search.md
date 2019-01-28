# Search

The `search` query parameter is reserved to apply generic search to a resource collection.

```javascript
axios.get('users?search=ca')
```

# Filter

The `filter` query parameter is reserved for filtering data. Each constraint is composed of three components:

- The property name
- The operators such as eq, lte, gte
- The filter value

All constraint declarations need to be sent under `filter` query parameter:

```json
{
    filter: {
	    "price": {
		    "gte": 10,
		    "lte": 100,
	    }
    }
}
```

The ability to combine multiple constraints allow you to implement complex query. Many HTTP client easily encode nested JSON objects into query parameters:

```javascript
import axios from 'axios'

axios.get('users', {
    params: {
		filter: {
            price: {
				gte: 10,
				lte: 100
			}
        }
	}
});
```

## Supported operator
```php
[
    'eq' => '=',
    'neq' => '!=',
    'gt' => '>',
    'gte' => '>=',
    'lt' => '<',
    'lte' => '<=',
]
```

?> **Note:** using multiple operators for the same property result in an implicit AND. 

What if the API user wanted to OR the filters instead. i.e. find all items where price 
is less than 10 OR greater than 100?

You can use [custom filters](filters.md) to develop more complex data extraction.