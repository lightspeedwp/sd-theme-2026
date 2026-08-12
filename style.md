# style.md — Southern Destinations 2026

Token map and asset inventory for `sd-theme-2026`.

**Generated:** 2026-08-12 · **Linear:** [LS-2012](https://linear.app/lightspeedwp/issue/LS-2012/2-design-audit-tokens-and-asset-preparation) (M0, R8,100)
**Companion:** the full audit, divergence register and accessibility findings live in
[`.github/reports/sd-design-audit-2026-08-12.md`](../../../.github/reports/sd-design-audit-2026-08-12.md).

> **This document records measured state, not decisions.** Where the three sources disagree, the divergence is marked and the decision is still open. Nothing here authorises a visual change — the mandate is a rebuild with a light refresh, not a redesign.

---

## 1. Sources

| Source | Supplies | Status |
|---|---|---|
| [SD Design System Figma](https://www.figma.com/design/z4Nr33MGjtdqPBqxitobSD/Southern-Destinations-Design-System) | **Variables** — 115 across 7 collections | Variable layer generated 08/12/2026. No components, no states, no layouts. |
| [Live site](https://www.southerndestinations.com/) | **Design intent** — layout, composition, interaction | Authoritative wherever Figma is silent, which is most places. |
| `theme.json` (this theme) | Current scaffold — v3, 1,006 lines | KWV-inherited, part-branded. |

Rule, from [AGENTS.md](../../../AGENTS.md): **Figma supplies the variables; the live site supplies the design. Where Figma is silent, the live site decides.**

---

## 2. Colour tokens

### 2.1 Confirmed — all sources agree

| Token | Value | Role |
|---|---|---|
| `brand-500` | `#CC7F16` | 🎯 **The brand anchor.** Figma, live and `theme.json` all agree. The one fixed point in the palette. |
| `neutral-100` … `neutral-900` | `#FFFFFF` `#F9FAFB` `#D8D8D8` `#B8B8B8` `#909090` `#707070` `#505050` `#303030` `#181818` | Figma = `theme.json`, byte-identical |
| `error-foreground` | `#F10E0E` | System |
| `error-background` | `#F10E0E1A` | System (Figma `@10%`) |
| `warning-foreground` | `#F59E0B` | System · ⚠️ fails AA |
| `warning-background` | `#F59E0B1A` | System |
| `information-foreground` | `#3B82F6` | System |
| `information-background` | `#3B82F61A` | System |
| `success-foreground` | `#019733` | System |
| `success-background` | `#0197331A` | System |

### 2.2 Live-site colours in active use

Measured from `sd-lsx-child/assets/css/custom.css` — 273 hex references, **35 unique colours**, of which only 8 were ever declared as tokens.

| Colour | Uses | Role on the live site | Token status |
|---|---|---|---|
| `#CC7F16` | 55 | Brand orange — links, buttons, accents | ✅ `brand-500` |
| `#F7F5F2` | 34 | **Dominant warm off-white section background** | ✅ **`neutral-200`** — exact |
| `#60483B` | 30 | Brown — body and heading text | ✅ `neutral-700` (ΔE 5.6) |
| `#E6AD10` | 22 | Gold accent | ✅ **`accent-500`** — the yellow ramp's anchor |
| ~~`#4C5250`~~ | 21 | ~~Cool-green grey~~ | ⛔️ **Trustpilot chrome, not SD design** — excluded |
| `#BF5C17` | 17 | Hover / active orange | ⛔️ **Dropped** — hover reworked; uses `brand-600` |
| `#B4A48C` | 13 | Warm taupe | 🟠 `neutral-400` (ΔE 8.1) — near-fit, map during page conversion |
| `#41382E` | 11 | Dark warm grey | ✅ `neutral-800` (ΔE 4.5) |
| `#3E3530` | 10 | Dark warm grey | ✅ `neutral-800` (ΔE 4.3) |
| `#ECE9E3` | 10 | Warm light grey | ✅ `neutral-200` (ΔE 4.5) |
| `#938673` `#534A40` `#847C73` `#C1BDB4` `#DCD6C9` `#F6F3F0` `#F0EBE5` | 6→3 | Warm neutral family | ✅ absorbed into the warm `neutral-*` ramp |

✅ **Resolved.** The live neutral family is warm and Figma's `neutral-*` ramp was pure grey — mapping one onto the other would have visibly cooled the site. Instead `neutral-*` was **re-derived as a warm ramp from these live values** (§2.6), so the ramp now *is* the live palette. The one colour that could not sit on it, the cool-green `#4C5250` at hue 173°, turned out to be Trustpilot widget chrome and leaves the token map entirely.

### 2.3 In Figma, not yet in `theme.json`

**`primary-*`** — a warm brown ramp, the closest thing Figma has to the live warm neutrals. Safe to add; no conflict.

| Token | Value | | Token | Value |
|---|---|---|---|---|
| `primary-100` | `#F0E5D9` | | `primary-600` | `#403028` |
| `primary-200` | `#D1B69C` | | `primary-700` | `#2B1F1C` |
| `primary-300` | `#B99472` | | `primary-800` | `#17100F` |
| `primary-400` | `#89664B` | | `primary-900` | `#030202` |
| `primary-500` | `#554234` | | | |

**`accent-100` … `accent-900`** — the **yellow** ramp, anchored on **`#E6AD10` at step 500**. In Figma these rows still hold the `brand` values they were seeded with; the ramp was confirmed as intended-but-unfinished on 2026-08-12 and derived here. `#E6AD10` is used in several places on the live site (22 references) and previously had no token at all.

| Token | Value | | Token | Value |
|---|---|---|---|---|
| `accent-100` | `#FFF0DA` | | `accent-600` | `#A97F15` |
| `accent-200` | `#FFDEA8` | | `accent-700` | `#705413` |
| `accent-300` | `#FFCC66` | | `accent-800` | `#3B2D10` |
| `accent-400` | `#F7BB1F` | | `accent-900` | `#050301` |
| **`accent-500`** | **`#E6AD10`** ← anchor | | | |

Built by mirroring `brand`'s construction — same relative chroma profile, hue held at the anchor's 83° — with one necessary departure: **the lightness skeleton is rebuilt around the anchor.** `#E6AD10` measures L\* 74.0, which is where `brand-400` sits (71.6), not `brand-500` (60.0), because yellow is intrinsically light at full chroma. Reusing `brand`'s skeleton would have produced a **non-monotonic** ramp with step 400 darker than 500. The skeleton is instead two even segments meeting at the fixed anchor (5.4 L\* per step above, 18.2 below).

⚠️ **Yellow is a dark-background colour.** `accent-100`–`accent-500` are all AAA on `neutral-900` and all fail on white. For gold text on a light background use **`accent-700`** (7.08 AAA). `accent-500` is the brand gold — fills, dark surfaces and large display type, not body text on white.

### 2.4 Open divergences

| Token | Figma | `theme.json` | Live |
|---|---|---|---|
| `base` | `#F9F9F9` | `#FFFFFF` | `#FFFFFF` |
| `contrast` | `#010101` | `#000000` | `#000000` |
| `brand-100` | `#FCECCC` | `#F9F5F1` | — |
| `brand-200` | `#F8D597` | `#EEE0CE` | — |
| `brand-300` | `#F2BC63` | `#E3C396` | — |
| `brand-400` | `#EBA030` | `#EBA647` | — |
| `brand-600` | `#966215` | `#9B6111` | — |
| `brand-700` | `#624411` | `#6A420B` | — |
| `brand-800` | `#32240B` | `#3D2607` | — |
| `brand-900` | `#040301` | `#180F03` | — |

The brand ramp agrees only at `500`. Recommendation: adopt Figma's ramp — it agrees at the anchor and no other step appears on the live site, so nothing visible changes.

### 2.5 🔴 Accessibility constraint on the brand orange

**`brand-500 #CC7F16` is 3.17:1 against white — it fails WCAG AA for normal-size text, in both directions.**

> ✅ **Accepted 2026-08-12.** The theme's own styling is being updated, so the failing pairing is not carried forward as-is. **Not a blocker.** Recorded here so the constraint is known when components are styled. Hover independently landed on AA.

Where the ramp steps sit, for reference when styling:

| Use | Ratio | Reach for |
|---|---|---|
| Brand text on a light background | 3.17 ❌ | **`brand-600 #966215`** (5.18, AA) — adjacent step, reads the same |
| White on a brand fill | 3.17 🟠 | Fine at **large text** (≥18.66px bold / ≥24px), which live buttons broadly already are |
| Link hover | 5.18 ✅ | **`brand-600`** — already what the theme uses |
| Brand as a large-display or decorative fill | ✅ | `brand-500` as-is |
| Gold text on a light background | 2.03 ❌ | **`accent-700 #705413`** (7.08 AAA) |
| Gold on a dark surface | 8.75 ✅ | **`accent-500 #E6AD10`** — its natural home |
| System `warning-foreground #F59E0B` | 2.15 ❌ | Inherited from the KWV base, not an SD colour — needs a darker value if system messaging is built |

These are **role-mapping** rules — which ramp step a role points at. Changing `brand-500` itself would be a Change-Control Register entry. Full contrast table: audit report §5.

---

## 3. Typography tokens

### 3.1 Font sizes — fluid, 8 of 9 confirmed against Figma

| Slug | Name | Min | Max | Preset |
|---|---|---|---|---|
| `100` | Tiny | 11px | 12px | `var:preset|font-size|100` |
| `200` | Base | 14px | 16px | `var:preset|font-size|200` |
| `300` | Small | 16px | ⚠️ 19.2px *(Figma: 20px)* | `var:preset|font-size|300` |
| `400` | Medium | 20px | 24px | `var:preset|font-size|400` |
| `500` | Large | 26px | 32px | `var:preset|font-size|500` |
| `600` | X-Large | 34px | 40px | `var:preset|font-size|600` |
| `700` | Huge | 38px | 48px | `var:preset|font-size|700` |
| `800` | Gigantic | 42px | 64px | `var:preset|font-size|800` |
| `900` | Colossal | 48px | 80px | `var:preset|font-size|900` |

Slug `300`'s max is `1.20rem` against Figma's 20px — likely a typo for `1.25rem` (register item **T1**).

### 3.2 🔴 Font families — three-way disagreement, do not extract yet

**Live site (authoritative), by declaration frequency:**

| Role | Live stack | Uses |
|---|---|---|
| Heading | `"optimademi_bold", "Belleza", sans-serif` | **31** |
| Body | `"Open Sans", sans-serif` | 16 |
| Accent / script | `"Joe Hand", "La Belle Aurore", sans-serif` | 8 |
| Alternate | `"Optima", "Belleza", sans-serif` | 4 |

| Role | Live | Figma | `theme.json` (applied) |
|---|---|---|---|
| heading | `optimademi_bold, Belleza, sans-serif` | 🔴 `joeHand 3` | ✅ `Optima, Belleza, sans-serif` |
| body | `Open Sans, sans-serif` | `Open Sans` | ✅ `"Open Sans", …system…, sans-serif` |
| accent | `Joe Hand, La Belle Aurore, sans-serif` | *(absent)* | ✅ `"Joe Hand", "La Belle Aurore", cursive` |
| monospace | *(plugin only)* | *(absent)* | `monospace` |

⚠️ **Figma's typography table has two copy-paste errors.** Its `heading` row names Joe Hand, which the live site uses only as a *script accent*, never as a heading. Its `alternate` row holds `Optima` but points its Code Syntax at `--wp--preset--font-family--body`. **Correct Figma; the theme now uses the live-measured values instead.**

**Resolved in the theme, 2026-08-12** (decision: use live-measured values now):

- `heading` no longer carries the wrong `Palatino, Georgia, serif` fallback — Optima is a humanist **sans**.
- The webfont-generator family name `optimademi_bold` is gone. It is now simply **weight 600–700 of the `Optima` family**, which is the correct way to express it — see §3.7.
- `accent` gained Joe Hand ahead of La Belle Aurore, matching live.
- An `alternate` preset was briefly added for regular Optima, then **removed**: with the Optima family registered across four weights, `alternate` resolved to the identical stack as `heading`. Shipping two presets with the same value would repeat exactly the `accent`/`brand` duplication criticised in Figma.

### 3.3 Line height, weight, letter spacing

`settings.custom` — no Figma equivalent except the two line heights, which match.

| Family | Tokens |
|---|---|
| `lineHeight` | `snug 1.125` · `heading 1.25` ✅ · `button 1.35` · `body 1.5` ✅ |
| `fontWeight` | `thin 100` → `black 900`, all nine steps |
| `letterSpacing` | `none 0em` · `narrow 0.02em` · `heading 0.04em` · `wide 0.075em` |

Figma declares only `lineHeight/heading 125` and `lineHeight/body 150`; both match. The theme is a superset — keep the extras. Figma silence is not deletion.

### 3.4 🔴 Font licensing — unresolved, blocks the font layer

| Face | Licence | Status |
|---|---|---|
| **Optima / Optima Demi Bold** | 🔴 **Commercial (Linotype)** | **Web-embedding rights must be confirmed in writing.** It is the heading face of the entire site. If unlicensed, substitution is a design decision → Change-Control Register. |
| **Joe Hand** | 🔴 Unconfirmed | Same check needed. |
| Open Sans | ✅ OFL (Google Fonts) | Clear |
| Belleza | ✅ OFL (Google Fonts) | Clear |
| La Belle Aurore | ✅ OFL (Google Fonts) | Clear |

**Status 2026-08-12: written confirmation is being sought from the client. The faces are approved for development use as the primary fonts** and are present in `assets/fonts/` (§3.7), so theme setup is not blocked.

**Two mitigations are in place** because this repo was **public** when the faces were first pushed:

1. **The repo is being switched to private.** Exposure was small — created 2026-08-12, **0 forks, 0 stars, 0 watchers** — so no third party is known to hold a copy.
2. **`assets/fonts/optima-*.woff2` and `joe-hand-*.woff2` are `.gitignore`d** and delivered by the build/deploy pipeline rather than committed. The OFL faces (Open Sans, Belleza, La Belle Aurore) stay committed.

🟠 **Still a release gate.** Optima and Joe Hand must not ship to production until the rights are confirmed in writing. If Optima does not clear, substitution is a Change-Control Register item — Belleza is already registered as its resolvable fallback, so the theme degrades rather than breaks.

⚠️ **History is not cleaned by either mitigation.** The faces remain in the commits already pushed. That is acceptable while the repo is private; if it is ever made public again, history must be rewritten first.

### 3.5 Defects in the live `@font-face` blocks — all fixed in the rebuild

| Defect on live | Status in this theme |
|---|---|
| **Swapped format hints** — `optimademi_bold` declares `.woff` as `format("woff2")` and vice versa. *(The files themselves are fine — verified by magic bytes; only the CSS hints were crossed.)* | ✅ Fixed — WordPress emits `format('woff2')` correctly |
| **Invalid descriptors** — `font-family: 'Optima', sans-serif;` *inside* `@font-face`; the descriptor takes one name | ✅ Fixed — one family name per face |
| **Eleven files, one weight** — all `OpenSans-*.ttf` stacked in a single `src` with no weight/style split, so only the first ever resolved and italics were synthesised | ✅ Fixed — 10 discrete faces, each with its own weight and style |
| **Broken path** — `'Open Sans Italic'` uses `../../fonts/` where siblings use `../fonts/`, plus a missing comma | ✅ Gone — that pseudo-family no longer exists |
| **TTF, not WOFF2** | ✅ Fixed — all 13 faces are WOFF2; the bundle is **474 KB** against 1,340 KB of TTF sources |

### 3.6 Two corrupt / incorrect source fonts on live

Found by parsing the `OS/2`, `head` and `name` tables of every downloaded face:

- 🔴 **`Optima_Italic.ttf` is malformed** — its `glyf` table range (324…68264) **overlaps `cmap`** (65840…67248). `woff2_compress` rejects it outright. It was unusable on live too: it sat in the stacked `@font-face` where only the first `src` resolved, so it never loaded. **Not ported.** Italic Optima headings will synthesise an oblique, which is what live effectively does today. A clean source file is needed if real italics are wanted.
- ⚠️ **Non-standard weight metadata** — `Optima_Italic.ttf` reports `usWeightClass 5` (valid range is 1–1000; conventionally 100–900) and `Optima_Medium.ttf` reports `550`. Registered at 400 and **500** respectively.
- ⚠️ `Optima_b.TTF` and the demi-bold webfont **both report weight 700**. Only the demi-bold is ported — it is the face the live site actually renders headings with (31 uses); `Optima_b.TTF` was a duplicate at the same weight inside the never-resolving stack.
- ⚠️ Live declares Joe Hand and La Belle Aurore at `font-weight: 200`. Both fonts report **400** internally. Registered at 400.

### 3.7 Bundled font layer — `assets/fonts/`

**13 WOFF2 faces, 474 KB total.** Converted from the live sources with `woff2_compress`; every file verified as genuine WOFF2 by magic bytes.

| Preset | `fontFamily` | Faces |
|---|---|---|
| `heading` | `Optima, Belleza, sans-serif` | `Optima` 400 · 500 · **600 700** · `Belleza` 400 |
| `body` | `"Open Sans", …system…, sans-serif` | `Open Sans` 300 · 400 · 600 in normal + italic, plus **700 normal** (7) |
| `accent` | `"Joe Hand", "La Belle Aurore", cursive` | `Joe Hand` 400 · `La Belle Aurore` 400 |
| `monospace` | `monospace` | none — system |

```
assets/fonts/
  optima-400-normal.woff2            18.7 KB   ⚠️ not committed — see below
  optima-500-normal.woff2            27.9 KB   ⚠️ not committed
  optima-700-normal.woff2            26.4 KB   ⚠️ not committed · registered as font-weight: 600 700
  joe-hand-400-normal.woff2          51.4 KB   ⚠️ not committed
  belleza-400-normal.woff2           11.6 KB
  la-belle-aurore-400-normal.woff2   23.7 KB
  open-sans-{300,400,600}-{normal,italic}.woff2   ~42–58 KB each
  open-sans-700-normal.woff2         45.2 KB
```

### Weight coverage

**Open Sans ships 300/400/600 + 700 normal.** Weights 800/900 and the 700 italic were dropped as unneeded (2026-08-12); 700 normal was reinstated after review, since a cutoff at 600 was too aggressive for body copy. Payload went 602 KB → 429 KB → **474 KB**.

`patterns/template-single-post.php` requests `font-weight|bold` (700) on a **paragraph**, which is the body family — that now resolves to the real **Open Sans 700** face rather than falling back to 600.

Still unbundled and resolving to the nearest available weight, by design:

| Requested | Family | Resolves to |
|---|---|---|
| `bold` 700 *italic* on body text | Open Sans | 600 italic |
| `extra-bold` 800 / `black` 900 on body text | Open Sans | 700 normal |
| `black` 900 on headings *(404, search, archive titles)* | Optima | 700 — the demi-bold cut |

None of these synthesise; CSS font matching picks the nearest real face. The `fontWeight` custom tokens still declare the full 100–900 scale, so a value above what is bundled is legal and simply resolves down.

⏳ **Unused weights get pruned at the end of the rebuild.** The set is deliberately a little wider than today's templates need, since page conversion may call for more of it.

### ⚠️ Four faces are not committed to this repo

`assets/fonts/optima-*.woff2` and `assets/fonts/joe-hand-*.woff2` are **`.gitignore`d** — commercially licensed, web-embedding rights unconfirmed, and this repo was public when they were first pushed. They are delivered by the **build/deploy pipeline** instead. → §3.4

**Consequence:** a fresh clone carries **9 of 13 faces (353 KB)**; the other **4 (122 KB)** arrive from the pipeline. `theme.json` registers all 13, so until the pipeline runs, four `@font-face` rules are unresolved — headings fall back to **Belleza** (bundled, deliberately) and then `sans-serif`. Degraded, not broken.

**`.gitignore` does not remove them from git history.** If this repo is ever public again, history needs rewriting.

Three deliberate choices:

1. **The demi-bold face is registered as `font-weight: 600 700`,** a range, so it serves both. This is what makes the heading scale resolve without synthesis: `h1`/`h2` ask for 700, `h3` asks for 600, and both land on the real cut.
2. **Belleza is registered as its own face inside the `heading` family.** A fallback name in a stack does nothing without an `@font-face`, so without this the `Belleza` in the stack would be dead text and the browser would drop straight to `sans-serif`. This also makes Belleza a working standby if the Optima licence does not clear.
3. **`fontDisplay: swap`** on every face, matching live.

**Weight coverage verified against the heading scale** — every level resolves to a real file, none synthesised:

| | Weight | Resolves to |
|---|---|---|
| `h1` `h2` | 700 | `optima-700-normal.woff2` |
| `h3` | 600 | `optima-700-normal.woff2` *(range)* |
| `h4` `h5` | 500 | `optima-500-normal.woff2` |
| `h6` | 400 *(inherits root)* | `optima-400-normal.woff2` |

Verified via `WP_Theme_JSON` + `WP_Font_Face`: all 13 `fontFace` entries survive WordPress sanitisation, all 13 `src` paths resolve on disk, and 13 `@font-face` rules generate with correct `format('woff2')`.

### 3.6 Fonts to drop

The LSX parent loads **Lora** (4 faces), **Noto Sans** (4 faces) and **FontAwesome 4.7**, all overridden by the child. Live downloads three unused families. Drop all three; icons come from the installed `icon-block`.

---

## 4. Spacing — ✅ 11 of 11 confirmed

Figma and `theme.json` agree at every step. **No change required.**

| Slug | Name | Min | Max | Preset |
|---|---|---|---|---|
| `5` | XXS | 4px | 5px | `var:preset|spacing|5` |
| `10` | XS | 8px | 10px | `var:preset|spacing|10` |
| `20` | S | 14px | 20px | `var:preset|spacing|20` |
| `30` | M | 20px | 30px | `var:preset|spacing|30` |
| `40` | L | 26px | 40px | `var:preset|spacing|40` |
| `50` | XL | 33px | 50px | `var:preset|spacing|50` |
| `60` | XXL | 37px | 60px | `var:preset|spacing|60` |
| `70` | XXXL | 42px | 70px | `var:preset|spacing|70` |
| `80` | XXXXL | 48px | 80px | `var:preset|spacing|80` |
| `90` | Gigantic | 56px | 90px | `var:preset|spacing|90` |
| `100` | Colossal | 64px | 100px | `var:preset|spacing|100` |

Buttons carry their own spacing customs: `spacing.button.padding-horizontal` `clamp(32px, 14vw, 48px)` · `padding-vertical` `clamp(14px, 10vw, 18px)`.

---

## 5. Radius — ✅ 6 of 6 confirmed

| Slug | Name | Value | Preset |
|---|---|---|---|
| `0` | none | `0` | `var:preset|border-radius|0` |
| `100` | small | `4px` | `var:preset|border-radius|100` |
| `200` | medium | `8px` | `var:preset|border-radius|200` |
| `300` | large | `16px` | `var:preset|border-radius|300` |
| `400` | x-large | `24px` | `var:preset|border-radius|400` |
| `500` | round | `9999px` | `var:preset|border-radius|500` |

---

## 6. Border width — in Figma, absent from `theme.json`

Safe to add to `settings.custom`; no conflict.

| Token | Value | Custom property |
|---|---|---|
| `borderWidth.0` | `0` | `--wp--custom--border-width-0` |
| `borderWidth.100` | `1px` | `--wp--custom--border-width-100` |
| `borderWidth.200` | `2px` | `--wp--custom--border-width-200` |
| `borderWidth.300` | `4px` | `--wp--custom--border-width-300` |
| `borderWidth.400` | `8px` | `--wp--custom--border-width-400` |

---

## 7. Shadow — ⚠️ 0 of 6 match

| Slug | Name | Figma (reassembled) | `theme.json` (current) |
|---|---|---|---|
| `100` | Tiny | `0.5px 2px 3px 0.5px rgba(17,17,17,.20)` | `0 1px 2px 0 rgba(17,17,17,.2)` |
| `200` | Base | `0.5px 2px 6px 1px rgba(17,17,17,.20)` | `0 2px 4px 0 rgba(17,17,17,.22)` |
| `300` | Small | `1px 4px 12px 4px rgba(17,17,17,.20)` | `0 4px 8px 0 rgba(17,17,17,.24)` |
| `400` | Medium | `1px 4px 12px 4px rgba(17,17,17,.30)` | `0 6px 12px 0 rgba(17,17,17,.26)` |
| `500` | Large | `1px 4px 12px 4px rgba(17,17,17,.30)` | `0 10px 20px 0 rgba(17,17,17,.28)` |
| `600` | X-Large | `2px 6px 12px 6px rgba(17,17,17,.30)` | `0 16px 32px 0 rgba(17,17,17,.3)` |

Both agree the shadow colour is `#111111` and both scale monotonically, but no preset matches. ⚠️ **Figma's `Medium` and `Large` are byte-identical** — a 6-step scale with 5 distinct values. Confirm before extracting (register item **S1**).

---

## 8. Layout

| Token | Figma | `theme.json` | |
|---|---|---|---|
| `contentSize` | 800 | `900px` | ⚠️ divergent (**L1**) |
| `wideSize` | 1520 | `1520px` | ✅ |
| `fullWidth` | 1920 | *(absent)* | Add to `settings.custom` — no core token exists (**L2**) |

Figma's reference screenshots are captured at **1920 / 1440 / 1024 / 768 / 390**, corroborating 1920 as the full-width ceiling. These widths are *not* declared as Figma variables.

---

## 9. Asset inventory

**21 assets verified on live 2026-08-12 — all return HTTP 200. No dead references.**
Total **≈2.80MB**: 2.72MB raster, 85KB SVG.

### 9.1 Theme assets to port — `sd-lsx-child/images/`

Referenced as `background-image` from `custom.css`.

| Asset | Size | Role | Action |
|---|---|---|---|
| `banner-brands-1920x454.png` | 🔴 **1.6MB** | Brands banner | **Convert to WebP** — photographic content in PNG. Single largest asset on the site. |
| `bg-hoops.jpg` | 243KB | Decorative background | Port → WebP |
| `tour-search-banner.jpg` | 187KB | Tour search banner | Port → WebP |
| `banner-search-tc-faq-1920x454.jpg` | 184KB | Search / T&C / FAQ banner | Port → WebP |
| `current-accommodation-bg.jpg` | 152KB | Accommodation background | Port → WebP |
| `footer-bg.jpg` | 124KB | Footer background | Port → WebP |
| `mobile-footer-bg-img.jpg` | 60KB | Mobile footer background | Port → WebP |
| `sd-modal-newsletter-sign-up.jpg` | 52KB | Newsletter modal | Port → WebP |
| `privacy-bg.jpg` | 47KB | Privacy page background | Port → WebP |
| `why-choose-sd-bg-img.jpg` | 18KB | "Why Choose SD" section | Port → WebP |
| `guarantee-bg.jpg` | 10KB | Guarantee section | Port → WebP |
| `special-archive-badge.svg` | ⚠️ 39KB | Specials archive badge | Port — **run through SVGO**, 39KB is unoptimised for an SVG |
| `za.svg` | 7.4KB | 🇿🇦 currency/locale flag | Port |
| `us.svg` | 4.4KB | 🇺🇸 flag | Port |
| `aus.svg` | 4.1KB | 🇦🇺 flag | Port |
| `uk.svg` | 3.9KB | 🇬🇧 flag | Port |
| `hp-arrow-hover.svg` | 3.4KB | Homepage arrow hover state | Port |
| `quotes.svg` | 2.0KB | Testimonial quote mark | Port |

### 9.2 Theme assets — `sd-lsx-child/assets/imgs/`

| Asset | Size | Role | Action |
|---|---|---|---|
| `tp-logo-white-green.svg` | 13KB | Trustpilot logo, header | Port |
| `tp-logo.svg` | 5.1KB | Trustpilot logo | Port |
| `stars/5star.svg` | 2.5KB | Trustpilot 5-star rating | Port |

🔴 **The Trustpilot *integration* carries a committed API key** (see [AGENTS.md](../../../AGENTS.md)) — it needs rotating and must never be echoed. The **assets** above are unaffected and safe to port.

### 9.3 Media Library — migrates as content, not theme assets

| Asset | Location | Role |
|---|---|---|
| `sd-logo.svg` | `/uploads/2019/07/` | Site logo (`custom-logo`) |
| `home-intro-logo.svg` | `/uploads/2019/07/` | Homepage intro logo |
| `footer-logo.svg` | `/uploads/2019/07/` | Footer logo |
| 17 × `brands-logo-*.svg` + `asilia-logo.svg` | `/uploads/2019/08–09/` | "Finest Brands" homepage section |

Partners represented: Natural Selection · MORE · Bush Company · Desert & Delta · MalaMala · Wild Horizons · Belmond · Africa Bush Camps · Sanctuary · Ilios · One&Only · Time + Tide · Londolozi · Royal Portfolio · Asilia.

### 9.4 This theme's `assets/` today

**18 CSS files + 13 WOFF2 font faces (474 KB, 9 of them committed). No images yet.**

```
assets/
├── fonts/                # ✅ 13 WOFF2 faces (4 gitignored) — see §3.7
└── styles/               # core-{button,categories,columns,cover,group,image,list,
                          #   navigation,post-author,post-excerpt,post-navigation-link,
                          #   post-template,post-terms,post-title,
                          #   query-pagination-numbers,read-more,search,separator}.css
```

**Fonts: done** (§3.7). **Images: outstanding** — the 18 backgrounds and 3 Trustpilot SVGs in §9.1–9.2 are still to be brought in, with the WebP conversions noted there.

---

## 10. Token discipline

From [AGENTS.md](../../../AGENTS.md) working agreement 4 — non-negotiable:

- Reference presets by **numeric slug**: `var:preset|spacing|30`, `var:preset|font-size|500`, `var:preset|border-radius|200`.
- **Never** paste a raw hex or font-family literal into a template, part, pattern or style variation.
- Run **`theme-orphaned-refs`** after any token change. Target **0 orphans**.
- `theme-color-token-enforcer`'s semantic + dark-mode layer is **opt-in and not funded** on this project.

---

## 11. Status

| Family | Tokens | State |
|---|---|---|
| Spacing | 11 | ✅ Confirmed against Figma — no change needed |
| Radius | 6 | ✅ Confirmed against Figma — no change needed |
| System colours | 8 | ✅ Confirmed (⚠️ `warning-foreground` fails AA) |
| `brand-500` | 1 | ✅ Confirmed — the anchor, agreed by all three sources |
| **Neutral ramp** | 9 | ✅ **Applied** — re-derived as a warm ramp from live; `neutral-200` = `#F7F5F2` |
| **Brand ramp** | 8 | ✅ **Applied** — Figma's values adopted |
| **`accent-*`** | 9 | ✅ **Added** — the yellow ramp, anchored on `#E6AD10` |
| `primary-*` | 9 | ✅ **Added** |
| Border width | 5 | ✅ **Added** as `settings.custom.borderWidth` |
| `layout.fullWidth` | 1 | ✅ **Added** as `settings.custom.layout.fullWidth` |
| **Font families** | 4 | ✅ **Applied** — live-measured; `alternate` removed as a duplicate |
| **Font faces** | 13 | ✅ **Bundled** — WOFF2 in `assets/fonts/`, 474 KB. Open Sans 300/400/600 + 700 normal. 4 non-OFL faces `.gitignore`d, pipeline-delivered. Unused weights pruned at the end of the rebuild |
| Hover / state colours | — | ✅ **Resolved** — `#BF5C17` dropped; hover uses `brand-600` (AA) |
| Brand orange contrast | — | ✅ **Accepted** — theme styling is being updated; not a blocker |
| `base` / `contrast` | 2 | ⏳ Deferred — Figma `#F9F9F9`/`#010101` vs live white/black |
| Shadow | 6 | ⏳ Awaiting design — 0 of 6 match; Figma has a duplicate pair |
| `contentSize` | 1 | ⏳ Deferred — 800 vs `900px` |
| Font size `300` | 1 | ⏳ Deferred — `1.20rem` vs 20px |
| `#B4A48C` near-fit | 1 | ⏳ Map to nearest step during the relevant page-conversion issue |
| **Figma corrections** | 4 | 🟠 **In hand** — typography rows, border Code Syntax, `accent` + `neutral` values, shadow duplicate |
| **Font licences** | — | 🟠 Confirmation in progress · **dev use approved** · **release gate** |
| **Customizer CSS in DB** | ~112 KB | 🔴 **Largest remaining item** — export from live `wp_options` before page conversion |

**Verification run 2026-08-12:** `theme.json` valid · tab round-trip byte-identical · **0 orphaned preset references** across 557 · **46 palette entries** (matching Figma's 46 colour variables exactly), 4 font families and 13 `@font-face` rules confirmed emitted by WordPress · no contrast regression on the 71 `neutral-*` references (max Δ 0.05) · `php -l` clean on 18 files.

Update this document whenever the Figma variable layer changes — it is under active development, and a partial extraction silently produces a partial `theme.json`. Full detail and the divergence register: [`.github/reports/sd-design-audit-2026-08-12.md`](../../../.github/reports/sd-design-audit-2026-08-12.md).
