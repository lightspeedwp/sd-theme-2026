# theme.json shadow presets (WP 6.9)

Location: settings.shadow

Structure:

- defaultPresets: boolean
- presets: array of objects
	- name: string (display name)
	- slug: string (used in var(--wp--preset--shadow--{slug}))
	- shadow: string (CSS box-shadow value)

Example preset:

{
	"name": "Tiny",
	"slug": "100",
	"shadow": "0.5px 2px 3px 0.5px rgba(17, 17, 17, 0.2)"
}

Conversion rules:

1. Slug comes from Figma “Code Syntax” column: var(--wp--preset--shadow--100) → "100".
2. Shadow value format: "x y blur spread color".
3. If Figma values are numeric without units, append "px".
4. Convert “#RRGGBB @NN%” → rgba(r, g, b, NN/100).
5. If multiple shadow layers are present, join with comma and a space.
