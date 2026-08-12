# Theme.json 6.9 Schema Notes — Border Radius Presets

Key schema area (settings.border.radiusSizes):

```
"border": {
	"radius": true,
	"radiusSizes": [
		{
			"name": "Small",
			"slug": "100",
			"size": "4px"
		}
	]
}
```

Notes:

- `radiusSizes` is an array of objects with `name`, `slug`, and `size`.
- Slugs generate `--wp--preset--border-radius--{slug}`.
- `size` must be a CSS border-radius value, including units (use "0" for zero).
- In WordPress 6.9 schema, the property lives under `settings.border`.
