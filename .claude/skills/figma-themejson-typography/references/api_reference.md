# Reference Documentation for Figma Themejson Typography

## WordPress theme.json (6.9) typography essentials

Required schema:

```
"$schema": "https://schemas.wp.org/wp/6.9/theme.json"
```

Typography preset location:

```
settings.typography.fontSizes[]
settings.typography.fontFamilies[]
```

Custom properties for line-height:

```
settings.custom.lineHeight
```

Preset references:

- `var:preset|font-size|<slug>`
- `var:preset|font-family|<slug>`

## Figma variables table parsing checklist

- Read all rows in the three sections: fontSize, fontFamilies, lineHeight.
- Use “Total variables” count as a completeness check.
- Slugs come from Code Syntax:
  - `var(--wp--preset--font-size--100)` → slug `100`
  - `var(--wp--preset--font-family--heading)` → slug `heading`
- Names come from the token name (after the `/`) and must preserve case.
- Min/Max map to `fluid.min` and `fluid.max` (px → rem).

## Font file guidance

- Prefer variable WOFF2 when available.
- If only static fonts exist, include all weights/styles in `fontFace`.
- Avoid `fonts.google.com/download` (returns HTML); use the CSS2 endpoint.
- If Python installs are blocked (PEP 668), use a venv:

```
python3 -m venv /tmp/woff2-venv
source /tmp/woff2-venv/bin/activate
python -m pip install fonttools brotli
```

## Example fontFace list (static)

```
"fontFace": [
	{
		"fontFamily": "Ubuntu",
		"fontStyle": "normal",
		"fontWeight": "400",
		"src": ["file:./assets/fonts/ubuntu/Ubuntu-400-normal.woff2"]
	},
	{
		"fontFamily": "Ubuntu",
		"fontStyle": "italic",
		"fontWeight": "400",
		"src": ["file:./assets/fonts/ubuntu/Ubuntu-400-italic.woff2"]
	}
]
```
