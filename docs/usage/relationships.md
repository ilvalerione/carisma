# Relationships

Relationships may be **to-one** or **to-many**. You can send a request to list or get a specific resource including their relationships in the result:

```javascript
axios.get('users?include=posts')
```

In alternative you can pointing directly to a resource's relationship using `relationships` sub-path:

```javascript
// Posts owned by a user
axios.get('users/1/relationships/posts')

// Author of the post
axios.get('posts/1/relationships/user')
```

When you pointing directly to a **to-many** relationship you can search and filter the collection. Imagine that you want retrieve a list of post related to a user based on search query and their status:

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

> To read more about Carisma search and filter capability go to [Search](search.md) section.

