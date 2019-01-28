# Custom Filters

You may want to list of all your application's users sorted by their total lifetime revenue. Creating such a list may require you to join to additional tables and perform aggregate functions within the query. It is impossible to do using normal constraints.

With Carisma your backend can store a specific filtering strategy onto a resource that you can invoke with a direct url:

```javascript
axios.get('users/filters?filter=by-revenue')
```

The backend developer need to provide you the name of all custom filters available for each resource. By default your filter result can be searched using standard `search` query parameter:

```javascript
axios.get('users/filters?filter=by-revenue&search=ca')
```

