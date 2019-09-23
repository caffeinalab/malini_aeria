# Malini Aeria

Malini Accessors and Decorators for Aeria.

Depends on both:
* [Malini](https://github.com/caffeinalab/malini);
* [Aeria](https://github.com/caffeinalab/aeria).

## Decorators:

Adds the **Aeria** decorator:

```
malini_post()->decorate('aeria');
```

Accepted options:
- `filter`: the attributes we want to retrieve; [see filter option syntax](other/filter-option-syntax);
- `automerge_fields`: if `false` specifies that the attributes retrieved will be nested inside an `aeria_fields` key; otherwise they will be added at the root level (defaults to `false`).

## Accessors:

`@aeria:{metabox_id},{field_id},{default}`

- `metabox_id`: the id of the wanted metabox; if no `metabox_id` is provided, all **Aeria** meta fields will be retrieved for this post;
- `field_id`: the id of the wanted field in this metabox; if no `field_id` is provided, all **Aeria** meta fields of this metabox will be retrieved;
- `default`: default value if the field is not set for this post; defaults to `null`.