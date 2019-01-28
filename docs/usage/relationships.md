# Relationships

Relationships may be to-one or to-many. You can request to GET a list or details of a specific resource including also their relationships:

```javascript
axios.get('users?include=posts')
```

