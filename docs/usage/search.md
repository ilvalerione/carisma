# Search

Each filter are composed of three components:

- The property or field name
- The operator such as eq, lte, gte
- The filter value

All filters declarations need to be sent under `filters` urel query parameter:

```json
{
    filters: {
	    "price": {
		    "gte": 10,
		    "lte": 100,
	    }
    }
}
```

The ability to combine multiple filters allow you to implement complex query 
on every column in the DB's table.

Many HTTP client easily encode nested JSON objects into url query structure:

```javascript
import axios from 'axios'

axios.get('users', {
    params: {
		filters: {
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

You can use [custom filters](filters/) to develop more complex data extraction.