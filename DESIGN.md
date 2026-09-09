# DESIGN.md — sd-theme-2026

> Design sources and the token → `theme.json` pipeline for the Southern Destinations 2026
> block theme. Read [AGENTS.md](AGENTS.md) first.
>
> Portable copy of the workspace `../../../DESIGN.md`. If you have the full workspace checked
> out, that original is authoritative.

---

## 1. This is a rebuild, not a redesign

The estimate funds *page conversion* — convert agreed pages and key landing pages to blocks
**while preserving current design**. Redesign is not bought, and the nine-week window has no
room for it.

**Consequence.** Where Figma and the live site disagree, the **live site wins**. Where Figma
is silent — which will be often, given how thin it is — the live site decides and no new
visual decision gets invented. Anything that would change the design goes to the
Change-Control Register.

---

## 2. Reading Figma

The [Southern Destinations Design System](https://www.figma.com/design/z4Nr33MGjtdqPBqxitobSD/Southern-Destinations-Design-System)
is **rudimentary** — a light token layer, not a full design system. It covers colour,
typography and spacing variables and little else. Treat it as the token source and the live
site as the composition source.

The `figma-desktop` MCP server reads the file open in the Figma desktop app. It has no write
tool.

### Importing Figma assets

1. `get_design_context` on the image node
2. Take the `localhost:3845/assets/<hash>` URL
3. `curl` it — then **check the real bytes with `file -b`**; a node named `.png` is often JPEG
4. `wp media import --porcelain` and use the returned ID/URL

---

## 3. Current token state — ⚠️ PROVISIONAL

**The tokens in `theme.json` are not final.** The scaffold carries the KWV token
*architecture* with Southern Destinations values substituted provisionally, so the theme
renders coherently while the real token map is produced.

| Group | State |
|---|---|
| `color.palette` → `brand-*` | **Provisional.** A lightness ramp anchored on `#CC7F16` — the dominant brand colour in the live site's `sd-lsx-child/assets/css/custom.css` (55 uses). |
| `color.palette` → `neutral-*`, `base`, `contrast`, status colours | Generic greys and semantic status colours. Reusable; likely to survive as-is. |
| `typography.fontFamilies` | **6 presets, 5 bundled WOFF2 faces, 216 KB** (`heading`, `belleza`, `body`, `accent`, `la-belle-aurore`, `monospace`). Bundled: **Open Sans variable ×2** (`wght` 300–700, normal + italic, OFL 1.1), Belleza, La Belle Aurore and Joe Hand — all committed since 2026-09-09. **No Optima face yet** — the 12-style desktop order is SD's Canva licence; the **web** licence covers *Optima DemiBold* alone, is annually renewable, and is awaited (LS-2641). So `heading` keeps a name-only stack, `"Optima LT Pro", Optima, Belleza, sans-serif`, and resolves to Belleza where neither Optima is installed locally. Each typeface needs its *own* preset: WordPress overwrites a `fontFace`'s `fontFamily` with the first name of its preset's stack. → `style.md` §3.4, §3.7 · `assets/fonts/LICENCES.md` |
| `spacing`, `border`, `shadow`, `custom` | Inherited scales from the base theme. Structure is right; values need confirming against the token map. |

### Live-site values observed (input for the token map)

Extracted from `sd-lsx-child/assets/css/custom.css`, ordered by frequency:

| Hex | Uses | Reads as |
|---|---|---|
| `#CC7F16` | 55 | Primary brand — amber/orange |
| `#F7F5F2` | 34 | Paper / off-white |
| `#60483B` | 30 | Warm brown |
| `#E6AD10` | 22 | Gold |
| `#4C5250` | 21 | Slate grey-green |
| `#BF5C17` | 17 | Deep orange |
| `#B4A48C` | 13 | Warm tan |
| `#41382E` | 11 | Dark brown |
| `#ECE9E3` | 10 | Light warm grey |
| `#3E3530` | 10 | Near-black brown |

Fonts observed: `optimademi_bold` / `Optima` / `Belleza` (headings), `Open Sans` (body),
`Joe Hand` / `La Belle Aurore` (script accent).

**Corroborating evidence — the logo's own palette.** The site logo
(`uploads/2019/07/sd-logo.svg`, also the source for this theme's `screenshot.png`) is drawn
in `#554234` (wordmark and elephants) with the sun element in `#BE5C16`, `#D78B17`, `#E5AC10`
and `#E9791E`. That amber→orange run brackets `#CC7F16` almost exactly and matches the
stylesheet's `#BF5C17` / `#E6AD10`, which is why the provisional ramp is anchored there.

> This is **evidence, not a decision.** Turning it into a ramp is the design-audit and
> token-mapping task; do that with the extractor pipeline, not by hand.

---

## 4. The token pipeline

Extract tokens with the pipeline — **never hand-author values.**

```
Figma + live site  ──extractor skills──▶  theme.json presets + styles/*.json
   variables tables                       numeric token scales
```

Run the **`themejson-extractor-orchestrator`** skill, which sequences the extractors in
dependency order:

```
palette → spacing → typography → radius → shadow
        → custom-color-tokens → style-variations → orphaned-refs
```

Individual skills live in `.claude/skills/figma-themejson-*`.

### Token discipline

- Authored files reference presets by **numeric slug** — `var:preset|color|…`,
  `var:preset|spacing|30`, `var:preset|font-size|200`.
- **Never** paste raw hex or font-family literals into templates, patterns, parts or style
  variations.
- Run **`theme-orphaned-refs`** after any token change; target **0 orphans**.
- `theme-color-token-enforcer` adds an optional semantic-token + dark-mode layer. Treat as
  opt-in — **not funded here.**

---

## 5. Where the theme came from

Derived from [kwv-theme-2026](https://github.com/lightspeedwp/kwv-theme-2026), inheriting its
**architecture and token conventions** — numeric preset scales, block-style handle naming,
`styles/**` JSON-first styling, per-core-block lazy CSS enqueue, mtime asset cache-busting —
and **none of its brand values**.

> **De-branding was a real task, not a rename.** KWV is a wine-and-spirits WooCommerce site.
> Its namespace, text domain, gold brand ramp, commerce patterns, demo pattern library,
> bundled fonts, brand assets and behaviour modules were **removed, not adapted**.

[ati-theme-2026](https://github.com/lightspeedwp/ati-theme-2026) is the useful sibling
reference — another LightSpeed Tour Operator block theme, with the post-type template set,
Tour Operator card patterns and LSX binding conventions worth copying from.

---

## 6. The pattern library

Patterns to build, from the spec:

| Pattern | Notes |
|---|---|
| **"Chat to a Safari Expert"** | CTA section, reused site-wide |
| **"Not sure where to go"** | CTA section |
| Card patterns | Shared across archives and related content — accommodation, destination, tour, review, team |
| **Homepage sections** | Dream Trip · Why Choose SD · Safari Gurus · Finest Brands · Reviews |

Template parts: **header** (LSX mega menu, two-tier mobile menu, sticky, Trustpilot image) and
**footer** (four widgets — Logo, Contact, Follow, Instagram — plus a conditional CTA). Markup
and styling live in the theme; the sticky / mobile-menu / banner **filters** live in the block
plugin.

Document the pattern library for editors — the site publishes actively, so editors need to
know what's available.

---

## 7. Verification

| Check | Command / skill |
|---|---|
| Presets match the token map | `themejson-extractor-orchestrator` report |
| No broken preset references | `theme-orphaned-refs` → 0 orphans |
| Global Styles coverage complete | `themejson-completion` |
| Theme structure / a11y / escaping | `block-theme-audit` |
| Styling architecture review | `wordpress-theme-styling-auditor` persona |
| Conversion fidelity | Compare against the live page — **it is the spec** |
