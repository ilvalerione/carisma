# Relationships

Relationships may be **to-one** or **to-many**. You can send a request to list or get a specific resource including also their relationships:

```javascript
axios.get('users?include=posts')
```

In alternative you can pointing directly to a relationship of a resource using `relationships` sub-path:

```javascript
// Posts owned by a user
axios.get('users/1/relationships/posts')

// Author of the post
axios.get('posts/1/relationships/user')
```

When you pointing directly to a relationships you can search and filter the collection. Imagine that you want retrieve a list of post of a user with some searching query that are published.

```javascript
axios.get('users/1/relationships/posts?search=pr', {
    params: {
        filters: {
            status: {
                eq: 'public'
            }
        }
    }
})
```

