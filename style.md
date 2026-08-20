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
| `#4C5250` | 21 | **The body-copy colour of the whole site** — set on the bare `p` selector | ✅ **`neutral-700`** (8.04:1 vs live 7.98). Previously excluded as Trustpilot chrome; that was wrong — see below |
| `#BF5C17` | 17 | Hover / active orange | ⛔️ **Dropped** — hover reworked; uses `brand-600` |
| `#B4A48C` | 13 | Warm taupe — pagination borders, slider arrows, FacetWP sort control | ✅ `neutral-400` (ΔE 8.1) — **mapped 2026-08-20**; load-bearing, not incidental |
| `#41382E` | 11 | Dark warm grey | ✅ `neutral-800` (ΔE 4.5) |
| `#3E3530` | 10 | Dark warm grey | ✅ `neutral-800` (ΔE 4.3) |
| `#ECE9E3` | 10 | Warm light grey | ✅ `neutral-200` (ΔE 4.5) |
| `#938673` `#534A40` `#847C73` `#C1BDB4` `#DCD6C9` `#F6F3F0` `#F0EBE5` | 6→3 | Warm neutral family | ✅ absorbed into the warm `neutral-*` ramp |

✅ **Resolved for the warm family.** The live neutral family is warm and Figma's `neutral-*` ramp was pure grey — mapping one onto the other would have visibly cooled the site. Instead `neutral-*` was **re-derived as a warm ramp from these live values** (§2.6), so the ramp now *is* the live palette.

🔴 **Correction, 2026-08-20 — `#4C5250` is not Trustpilot chrome.** The 2026-08-12 pass excluded it on the strength of its hue (cool green at 173°, ΔE 11.1 against every warm step) and a reading of its selectors. Re-measured against the rendered page, that is wrong. `sd-lsx-child/assets/css/custom.css` contains:

```css
p { font-family: "Open Sans", sans-serif; font-weight: 400; font-size: 15px; color: #4c5250; line-height: 22px; }
```

A bare `p` selector — so **`#4C5250` is the body-copy colour of every page on the site**, and it also draws the breadcrumb bar, the pagination labels, the FacetWP sort control, the mega-menu excerpt text and the footer links. Of its 21 uses only **3** are Trustpilot-scoped; the excluded 18 are SD design. Computed colour on `p` was confirmed as `rgb(76, 82, 80)` on the homepage, the tours archive, the accommodation archive, the blog and an accommodation single.

✅ **Resolved 2026-08-20 — body copy is `neutral-700`.** `styles.color.text` moved from `contrast` to `neutral-700` (`#5B4E41`). It measures **8.04:1** on `base`, against live's **7.98:1** — the two are within 0.06 of each other, so the page reads at the weight it does today while staying AAA. `neutral-800` was the alternative and was rejected: at 13.24:1 it is materially heavier than the site has ever been. The hue still differs (warm brown against live's cool green-grey), but at this lightness the difference is not legible in running text, and no new variable was added — which is the standing rule.

`neutral-700` now carries both body copy and headings, which is how live behaves: `#4C5250` and `#60483B` are close enough in weight that the site reads as one colour.

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
- The webfont-generator family name `optimademi_bold` is gone. It would have been simply **weight 600–700 of the `Optima` family**, which is the correct way to express it — but no Optima face is bundled at all now, the licence covering desktop use only. See §3.4.
- `accent` gained Joe Hand ahead of La Belle Aurore, matching live.
- An `alternate` preset was briefly added for regular Optima, then **removed**: with the Optima family then registered across four weights, `alternate` resolved to the identical stack as `heading`. Shipping two presets with the same value would repeat exactly the `accent`/`brand` duplication criticised in Figma.

### 3.3 Line height, weight, letter spacing

`settings.custom` — no Figma equivalent except the two line heights, which match.

| Family | Tokens |
|---|---|
| `lineHeight` | `snug 1.125` · `heading 1.25` ✅ · `button 1.35` · `body 1.5` ✅ |
| `fontWeight` | `thin 100` → `black 900`, all nine steps |
| `letterSpacing` | `none 0em` · `narrow 0.02em` · `heading 0.04em` · `wide 0.075em` |

Figma declares only `lineHeight/heading 125` and `lineHeight/body 150`; both match. The theme is a superset — keep the extras. Figma silence is not deletion.

### 3.4 Font licensing — resolved for Joe Hand, still blocking for Optima

Client licences supplied **2026-08-18** (`docs/SD Fonts & Licenses/`). Full register:
**`assets/fonts/LICENCES.md`**.

| Face | Licence | Status |
|---|---|---|
| **Joe Hand** | JOEBOB graphics **Webfont** EULA 1.0 | ✅ **Cleared for web embedding.** Capped — see below |
| **Optima** | Monotype/MyFonts order #9528082 (24 Jul 2018), *Optima Bold* | 🔴 **Still blocked.** Desktop OTF/TTF only; no self-hosting kit; Bold only |
| Open Sans | ✅ Apache 2.0 | Clear |
| Belleza | ✅ SIL OFL 1.1 | Clear |
| La Belle Aurore | ✅ SIL OFL 1.1 | Clear |

**Joe Hand cleared.** The installed file is now JOEBOB's **official webfont build**
(`joehand_2_15-webfont.woff2`, 532 glyphs) rather than the 226-glyph copy converted from
live. Three obligations ride with it: **10,000 pageviews per copy per month** (§1.3 —
SD's traffic will exceed one copy → LS-2642), **one domain plus 5 subdomains** (§1.4 — the
`.lightspeedwp.dev` dev host is *not* covered), and **no hotlinking or direct download**
(§1.5).

**Optima is still blocked, on three independent grounds:**

1. **No self-hosting kit.** The EULA's website grant covers the *Web Font Software* supplied
   "in a self-hosting kit"; §3 Restrictions then states *"You may not link to, or put
   online, Web Font Software not supplied to you in a self-hosting kit."* Only desktop
   OTF/TTF were delivered. Converting them ourselves also trips *"Modify the Software in
   any way."*
2. **Tracking Code is mandatory** on all non-development Websites, and removing it is
   prohibited. It ships only with the kit.
3. **Bold only.** The invoice's "2 font styles" are the Pro and Std cuts of the *same* Bold
   weight. **Optima 400 and 500 are not licensed at all** — and `h4`/`h5` used 500.

Where it *is* permissive: 250,000 pageviews/month, Development Websites explicitly allowed,
and SD (not the agency) is the named licence owner, satisfying the per-client clause.

**→ Action:** SD downloads the **webfont/self-hosting kit** for Optima Bold from the MyFonts
account holding #9528082. Under current Software-for-Creatives terms web rights are bundled
with the purchase, so this is likely a download, not a new purchase — but the 2018 order
predates those terms, so Monotype should confirm which EULA governs it. → LS-2641

#### 🔴 The three Optima faces previously bundled were never licensed

Removed **2026-08-18**. None is the face the client owns:

| File | Real identity | Embedding bits |
|---|---|---|
| `optima-400-normal.woff2` | `Optima` — **©1991 AG Baltia**, a 1993 clone | `fsType 1` — **embedding forbidden outright** |
| `optima-500-normal.woff2` | `Optima Medium` — Adobe Systems 1995 | `fsType 260` — preview/print, **no subsetting** |
| `optima-700-normal.woff2` | `Optima Demi Bold` — Adobe Systems 1995 | `fsType 4` — preview/print only |

The `fsType 1` on the 400 is the sharpest point: that font's own metadata refuses embedding.
The live site serves an equivalent conversion (`optima-demibold_1-webfont.woff2`) today, so
this exposure **predates the rebuild** and carries over to it.

**Consequence, and it was designed for:** `theme.json` registers no Optima face, but the
`heading` stack still *names* Optima — so a visitor with Optima installed locally (every
macOS) renders it, and everyone else resolves to **Belleza**, which is bundled as a real
face for exactly this eventuality. The design degrades rather than breaks. Substituting a
permanent heading face is a **Change-Control Register** item, per the decision already
recorded here on 2026-08-12.

⚠️ **History is not cleaned.** The faces remain in commits already pushed, and GitHub still
serves the purged blobs at the pre-rewrite SHAs. Acceptable while the repo is private; if it
is ever public again, history must be rewritten first. → `CHANGELOG.md` → Security.

### 3.5 Defects in the live `@font-face` blocks — all fixed in the rebuild

| Defect on live | Status in this theme |
|---|---|
| **Swapped format hints** — `optimademi_bold` declares `.woff` as `format("woff2")` and vice versa. *(The files themselves are fine — verified by magic bytes; only the CSS hints were crossed.)* | ✅ Fixed — WordPress emits `format('woff2')` correctly |
| **Invalid descriptors** — `font-family: 'Optima', sans-serif;` *inside* `@font-face`; the descriptor takes one name | ✅ Fixed — one family name per face |
| **Eleven files, one weight** — all `OpenSans-*.ttf` stacked in a single `src` with no weight/style split, so only the first ever resolved and italics were synthesised | ✅ Fixed — 10 discrete faces, each with its own weight and style |
| **Broken path** — `'Open Sans Italic'` uses `../../fonts/` where siblings use `../fonts/`, plus a missing comma | ✅ Gone — that pseudo-family no longer exists |
| **TTF, not WOFF2** | ✅ Fixed — all 10 faces are WOFF2; the bundle is **398 KB** against 1,340 KB of TTF sources |

### 3.6 Two corrupt / incorrect source fonts on live

Found by parsing the `OS/2`, `head` and `name` tables of every downloaded face:

- 🔴 **`Optima_Italic.ttf` is malformed** — its `glyf` table range (324…68264) **overlaps `cmap`** (65840…67248). `woff2_compress` rejects it outright. It was unusable on live too: it sat in the stacked `@font-face` where only the first `src` resolved, so it never loaded. **Not ported.** Italic Optima headings will synthesise an oblique, which is what live effectively does today. A clean source file is needed if real italics are wanted.
- ⚠️ **Non-standard weight metadata** — `Optima_Italic.ttf` reports `usWeightClass 5` (valid range is 1–1000; conventionally 100–900) and `Optima_Medium.ttf` reports `550`. Registered at 400 and **500** respectively.
- ⚠️ `Optima_b.TTF` and the demi-bold webfont **both report weight 700**. Only the demi-bold is ported — it is the face the live site actually renders headings with (31 uses); `Optima_b.TTF` was a duplicate at the same weight inside the never-resolving stack.
- ⚠️ Live declares Joe Hand and La Belle Aurore at `font-weight: 200`. Both fonts report **400** internally. Registered at 400.

### 3.7 Bundled font layer — `assets/fonts/`

**10 WOFF2 faces, 398 KB total.** Nine converted from the live sources with
`woff2_compress`; Joe Hand is the vendor's own webfont build. Every file verified as genuine
WOFF2 by magic bytes.

| Preset | `fontFamily` | Faces |
|---|---|---|
| `heading` | `Optima, Belleza, sans-serif` | **none** — Optima unlicensed (§3.4), resolves to `belleza` |
| `belleza` | `Belleza, sans-serif` | `Belleza` 400 |
| `body` | `"Open Sans", …system…, sans-serif` | `Open Sans` 300 · 400 · 600 in normal + italic, plus **700 normal** (7) |
| `accent` | `"Joe Hand", "La Belle Aurore", cursive` | `Joe Hand` 400 |
| `la-belle-aurore` | `"La Belle Aurore", cursive` | `La Belle Aurore` 400 |
| `monospace` | `monospace` | none — system |

```
assets/fonts/
  joe-hand-400-normal.woff2          44.5 KB   ⚠️ not committed — licensed, non-redistributable
  belleza-400-normal.woff2           11.4 KB
  la-belle-aurore-400-normal.woff2   23.1 KB
  open-sans-{300,400,600}-{normal,italic}.woff2   ~41–57 KB each
  open-sans-700-normal.woff2         45.2 KB
  LICENCES.md                                  ← the licence register
```

#### 🔴 Fixed 2026-08-18 — WordPress rewrites every `fontFace.fontFamily`

`WP_Font_Face_Resolver::convert_font_face_properties()` sets
`$font_face['font-family'] = $font_family_property` unconditionally
(`wp-includes/fonts/class-wp-font-face-resolver.php:142`), where `$font_family_property` is
the **first name of the preset's `fontFamily` stack**, taken by
`maybe_parse_name_from_comma_separated_list()` (`:120–125`). **A `fontFace` entry's own
`fontFamily` is ignored entirely.**

So registering a fallback face *inside* another family's preset — Belleza inside `heading`,
La Belle Aurore inside `accent` — silently mislabels it. The emitted CSS was:

```css
@font-face{font-family:Optima;      …src:…/belleza-400-normal.woff2}          /* wrong */
@font-face{font-family:"Joe Hand";  …src:…/joe-hand-400-normal.woff2}
@font-face{font-family:"Joe Hand";  …src:…/la-belle-aurore-400-normal.woff2}  /* wrong */
```

Two live consequences: the second `"Joe Hand"` rule had **identical descriptors** to the
first, so the later declaration won and the `accent` family rendered **La Belle Aurore
instead of Joe Hand**; and an `@font-face` claiming the name `Optima` **outranked
locally-installed Optima**, so even macOS visitors got Belleza's glyphs under Optima's name.

**Fix:** every typeface gets its own preset, whose `fontFamily` *leads* with that
typeface's name — hence the new `belleza` and `la-belle-aurore` presets. `heading` and
`accent` keep their slugs and stacks unchanged, so all 62 `var:preset|font-family|heading`
references are untouched. Verified against the rendered front page: 10 rules, each with the
correct descriptor.

> The earlier verification missed this because it counted `fontFace` entries surviving
> sanitisation and checked `src` paths and `format()` hints — never the `font-family`
> descriptor the resolver had overwritten. **Assert on emitted CSS, not on parsed input.**

#### Weight coverage

**Open Sans ships 300/400/600 + 700 normal.** Weights 800/900 and the 700 italic were
dropped as unneeded (2026-08-12); 700 normal was reinstated after review, since a cutoff at
600 was too aggressive for body copy.

`patterns/template-single-post.php` requests `font-weight|bold` (700) on a **paragraph**,
which is the body family — that resolves to the real **Open Sans 700** face.

Still unbundled and resolving to the nearest available weight, by design:

| Requested | Family | Resolves to |
|---|---|---|
| `bold` 700 *italic* on body text | Open Sans | 600 italic |
| `extra-bold` 800 / `black` 900 on body text | Open Sans | 700 normal |

**Headings no longer have a bundled cut at any weight.** `h1`/`h2` (700), `h3` (600),
`h4`/`h5` (500) and `h6` (400) all resolve to locally-installed Optima where present, else
to **Belleza 400** — which means the heading weight hierarchy is currently expressed by
size and synthesised bolding, not by real cuts. This is the visible cost of §3.4 and it
reverses the moment the Optima self-hosting kit lands. → LS-2641

None of the body weights synthesise; CSS font matching picks the nearest real face. The
`fontWeight` custom tokens still declare the full 100–900 scale, so a value above what is
bundled is legal and simply resolves down.

⏳ **Unused weights get pruned at the end of the rebuild.** The set is deliberately a little
wider than today's templates need, since page conversion may call for more of it.

#### ⚠️ One face is not committed to this repo

`assets/fonts/joe-hand-*.woff2` is **`.gitignore`d** and delivered by the **build/deploy
pipeline**. This is about redistribution, not doubt — its rights are confirmed, but EULA
§1.5/§1.6 forbid direct download and transfer, and a repository is a distribution channel.
The `optima-*` ignore pattern is kept so a stray conversion cannot be committed by accident.
→ §3.4

**Consequence:** a fresh clone carries **9 of 10 faces (353 KB)**; Joe Hand (44.5 KB) arrives
from the pipeline. Until it does, `accent` resolves to La Belle Aurore (bundled) and then
`cursive`. Degraded, not broken.

Two remaining deliberate choices:

1. **Belleza and La Belle Aurore are registered as families in their own right** — a
   fallback name in a stack does nothing without an `@font-face`, and as shown above it
   cannot be declared from inside another family's preset. The cost is two extra entries in
   the editor's font picker; the benefit is that both stacks actually resolve.
2. **`fontDisplay: swap`** on every face, matching live.

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

---

## 12. Block and section styles — the live port

**LS-2013 tasks 3.6 and 3.7 · measured 2026-08-20 at 1440px, Chrome 151.**

Method matters here. The 2026-08-12 pass read the two theme repos; this one read the
**rendered page**, because roughly half the live styling is generated at render time from
`wp_options` and is in neither repo. Every value below is a computed style off a real page,
cross-checked against the authored rule that produces it.

Two things that changed the picture:

- **The 112 KB Customizer CSS is no longer an unknown.** It was extracted from the rendered
  homepage (`lsx-customizer-inline-css`, 490 rule blocks) and read. It turns out to carry
  **LSX's unconfigured defaults for buttons** — `#991703` fill with a `#751203` plate, a dark
  red that appears nowhere on the site — because the child theme's `custom.css` overrides all
  of it. So the Customizer blob is *not* the record of SD's interaction palette that the audit
  assumed; `custom.css` is. That closes divergence **DB** for styling purposes.
- **`sd-lsx-child/assets/css/custom.css` in the repo is byte-identical to the copy live
  serves.** Diffed 2026-08-20. The "repo does not match production" caution still holds for
  the PHP templates, but not for this stylesheet — it can be trusted as the authored source.

### 12.1 What was built

| Style | Slug | Block | Ported from |
|---|---|---|---|
| **Fill** *(core variation, `theme.json`)* | `fill` | `core/button` | `.btn` — `#CC7F16` plate, uppercase Optima Demi 18px/600, square |
| **Outline** *(core variation, `theme.json`)* | `outline` | `core/button` | `.btn.ssm-apply-btn` — 2px brand border, fills on hover |
| Outline Light | `outline-light` | `core/button` | `.btn.white-border-btn` — base border, for dark and brand grounds |
| Accent CTA | `accent-cta` | `core/button` | The expert panel's "Send an Email" — `accent-400` plate, black label |
| Section Title | `section-title` | `core/heading` | `.lsx-title` + `:after` — uppercase, 80×2px `#E6AD10` rule |
| Section Title (Left) | `section-title-left` | `core/heading` | `.lsx-title.lsx-title-left:after` |
| Script Accent | `script-accent` | `core/heading` | `.sd-title` — Joe Hand 37px/200 |
| Light Page Section | `light-page-section` | `core/group` | Plain `.lsx-block-container` |
| Tinted Page Section | `tinted-page-section` | `core/group` | `.lsx-block-container` at `#F7F5F2` |
| Dark Page Section | `dark-page-section` | `core/group` | `.footer-cta-section` — 70px padding |
| Brand Page Section | `brand-page-section` | `core/group`, `core/column` | The expert / enquiry panel |
| Hero Banner | `hero-banner` | `core/cover`, `core/group` | `#lsx-banner .page-banner` — 680px home / 454px inner |
| Section Header | `section-header` | `core/group` | The centred title cluster |
| Media Overlay Card | `media-overlay-card` | `core/group` | `.lsx-to-archive-wrapper` — scrim **lifts** on hover |
| Team Member Card | `team-member-card` | `core/group` | Team archive — `rgba(26,18,5,.7)` 70px strip |
| Slider Frame | `slider-frame` | `core/group` | `.slick-arrow` / `.slick-dots` |
| — (CSS only) | — | `core/query-pagination-numbers` | `.lsx-pagination` — 40×40 plates |

**The two most-used treatments are core's own variations.** Fill and Outline are defined in
`theme.json` under `styles.blocks.core/button.variations`, not as partials in
`styles/blocks/button/`. That means a button carries the SD design with **no style picked in
the editor**, and the Fill/Outline pair an author already reaches for is the right one. Only
the two ground-specific treatments — Outline Light and Accent CTA — remain partials.

Dropped along the way: `cta` (became `fill`), `outline-dark` (became `outline`) and `raised`
(the live `2px 2px 0 0` offset plate, retired — Fill replaces its instances). The six
`is-style-cta` references in `patterns/header.php`, `patterns/footer.php` and
`templates/front-page.html` were repointed to `is-style-fill`.

**27 block-level style variations** register, plus the two core button variations defined
directly in `theme.json`. Verified via `WP_Theme_JSON_Resolver::get_style_variations( 'block' )`
and the merged `core/button.variations` keys.

### 12.2 Where each rule lives, and why

The rule is JSON-first, per `wp-blockstyle-css-field`. Three things forced CSS:

| What | Where | Which limit |
|---|---|---|
| The gold `::after` rule | `assets/styles/core-heading.css` | A `css`-field rule containing `content: ""` is dropped **whole** |
| Every hover/focus flip | `assets/styles/core-button.css` | The `css` field **strips `:hover`** entirely; and a variation's JSON `:hover` only beats the button element rule on source order |
| The card scrim lift + chevron | `assets/styles/core-group.css` | Both of the above |
| The `:active` press-in on Raised | `assets/styles/core-button.css` | `:active` is stripped like `:hover` |
| Pagination plates | `assets/styles/core-query-pagination-numbers.css` | The numbers are bare `<a>`/`<span>` with no block support to hang a variation off, and `current` needs a class selector core does not expose |
| Slider arrows and dots | `assets/styles/core-group.css` | Slick and Swiper markup belongs to plugins — per `wp-thirdparty-markup-styling`, scope to the `is-style-*` class |
| Editor-only overrides for both cards | `assets/styles/core-group.css` | Gutenberg out-specifies the `css` field — see the second trap below |

⚠️ **Three specificity traps, recorded so they are not reintroduced.**

**1 — the absolutely-positioned card children get a block-gap margin.** WordPress gives
flow-layout siblings a `margin-block-start` from `--wp--style--block-gap`. Because the scrim
and the team card's name band are `position: absolute` with `inset: 0`, that margin *still*
shifts them — it does not get ignored — so a 37px strip of bare image showed along the top of
every card. The variation's own `blockGap: 0` does not win. Both cards now carry
`margin: 0 !important` on their absolute children, and a `& > *` reset for good measure.

**2 — Gutenberg forces `position: relative` on block wrappers in the editor.** `content.css`
sets `.block-editor-block-list__layout .block-editor-block-list__block { position: relative }`
at **(0,2,0)**. A block-style `css` field compiles to `:root :where(…)` at **(0,1,0)**, so
inside the editor the scrim lost `position: absolute` and dropped *below* the image — the card
read as a stacked image-then-caption rather than an overlay. The fix is a
`.editor-styles-wrapper …` rule at (0,3,0) in `core-group.css`. The same rule keeps the team
card's bio panel open in the editor so its text can be selected and edited without a hover.

**3 — the pagination link rule.** `theme.json` sets
`styles.blocks.core/query-pagination.elements.link`, which compiles to
`:root :where(.wp-block-query-pagination a:where(:not(.wp-element-button)))`. Everything
inside `:where()` counts as zero, so that rule lands at **(0,1,0)** — exactly tying a bare
`.wp-block-query-pagination-next` and winning on source order. It silently stripped the
border off the prev/next arrows. Every selector in that sheet is now descendant-scoped to
(0,2,1) or higher. **Do not flatten them back to single classes.**

### 12.3 Deliberate departures from live

Everything else is a faithful port. These are not, and each is recorded as an improvement
rather than drift. Four of the five buy contrast:

1. **Pagination is `primary-500`, not the taupe.** Live draws it in `#B4A48C` → `neutral-400`,
   which is **1.9:1** on white — the borders barely register and the current page's white
   label on that fill is weak. `primary-500` is **9.48:1** in both directions. The rule also
   thins from 2px to 1px, because that weight of colour needs less of it.
2. **Pagination has a hover state.** Live has none at all. A click target with no hover
   feedback is a defect, not a design decision.
3. **The brand-panel CTA is gold with a black label.** Live uses a brighter orange
   (`#FF9900`) with white text, roughly 2.4:1. `accent-400` with `contrast` is **12.08:1** —
   the most legible button in the theme rather than the least.
4. **The archive card scrim rests at 45%, not 30%.** At live's 30% the title loses the fight
   against a bright photograph; the beach card on the reference page is the test case.
5. **The active slider dot takes the brand fill.** Live leaves resting and active on the
   same `#938673`, which gives the reader no position cue.

Two shape changes that are not about contrast:

6. **No radius anywhere.** Live's `.btn.white-border-btn` carries a 2px radius; nothing else
   in the design does, so it is squared off.
7. **One button box.** See §12.8.

### 12.4 A defect fixed on the way through

`style.css` gave inline `<code>` a fixed `neutral-200` plate but let it inherit its text
colour. Inside any dark or brand-filled section it inherited `base` and rendered white on
near-white — invisible. The rule now sets `color` explicitly. KWV-inherited; it would have
surfaced the first time a dark section carried inline code.

### 12.5 Ten decisions, all taken 2026-08-20

Rendered as a table on the **Block & Section Style Reference** page (local,
`/block-section-style-reference/`, page ID 239, `page-no-title` template).

| # | Item | Live | Now | Decision |
|---|---|---|---|---|
| 1 | **Body copy** | `#4C5250` | `neutral-700` | 8.04:1 AAA, within 0.06 of live's 7.98. `neutral-800` rejected at 13.24 as materially heavier than the site has ever been |
| 2 | Button hover | `#BF5C17` | `brand-600` | Confirmed. Keeps the AA step |
| 3 | Type scale | 28px title, 15px body | preset `500` / `300` | Align to the new scale; no new steps. The larger type is the intended modernisation |
| 4 | Brand-panel button | `#FF9900` + white | `accent-400` + `contrast` | Became **Accent CTA**. 12.08:1 against roughly 2.4 |
| 5 | Card meta strip | `#F0EBE5` | `neutral-200` | Nearest existing step; no new variable. The subtle step between card body and strip is lost |
| 6 | Heading case | only h2 uppercase | only h2 uppercase | `elements.heading` no longer uppercases globally |
| 7 | Dark section ground | `#3E3530` + watermark | `neutral-800`, flat | Watermark goes on during template/page dev, as WebP or SVG. The live PNG is 1.6 MB |
| 8 | Pagination | 2px `#B4A48C`, no hover | 1px `primary-500`, hover fills | `neutral-400` is 1.9:1 on white; `primary-500` is 9.48:1 both ways |
| 9 | Heading brown | `#60483B` | `neutral-700` | Accepted. ΔE 5.6, and now the same token as body copy — which is how live reads |
| 10 | Button geometry | 15px pad, one 2px radius, 55px | 14/32px pad, square, one box | See §12.8 |

Two consequences worth noting. **Heading case (6)** removes `textTransform: uppercase` from
`elements.heading` and puts it on `elements.h2` alone, so h1, h3 and h4 return to sentence
case as live has them. `h6`'s uppercase went with it — that was a base-theme label treatment
rather than part of the hierarchy, and live uses no h6, but restoring it is a one-line change
if the eyebrow style is wanted. Section headings are unaffected at any level, because
`section-title` sets its own uppercase.

**Type scale (3)** is why the section title renders at 32px against live's 28px, h3 at 24px
against 22px, and body copy at 19.2px against 15px. That is the sanctioned modernisation, and
it should be applied consistently rather than corrected per-component.

### 12.6 Not ported, and why

- **The blog card.** Its live treatment is a horizontal list row — image left, Optima title,
  italic meta in orange, an `…../` read-more. That is composition, not a token decision, so
  it belongs to the blog page-conversion issue. `blog-card.json` and `blog-card-large.json`
  are untouched and still carry their KWV structure.
- **The list-card variant** of the archive card (container `#F6F3F0`, meta strip `#F0EBE5`,
  read-more `#3E3530` → `#4A4A4A`). Measured and ready, blocked on open decision 5.
- **The breadcrumb bar** (`#ECE9E3` ground, italic `#4C5250` links). Breadcrumbs are a Yoast
  filter — plugin behaviour, so the bar lands with `sd-enhancements`, not here.

### 12.7 Verification

- **27 block style variations** register, plus `fill` and `outline` in `theme.json`; every one
  resolves its `var:preset|*` reference to a real custom property.
- **0 orphaned preset references** across 101 files.
- **Task 3.8 clean.** No raw hex and no font literal in any declaration across `styles/`,
  `parts/`, `patterns/`, `templates/` and `assets/styles/`. Every hex that appears is inside a
  comment or a `description`, documenting the measured live value it came from.
- Computed styles confirmed on the reference page: all four buttons at **58px** with identical
  14/32px padding, zero radius and a 2px border; the card scrim at 45% with a **0px** top gap;
  the overlay title at preset `500` with a 36px chevron; pagination at 40×40 with a 1px
  `primary-500` border and an inverted current plate; slider arrows at 44×44 with a 32px glyph
  centred on the dot row to **0px** offset; body copy at `rgb(91, 78, 65)`.
- Heading case confirmed in the compiled global stylesheet: `h2` is the **only** heading
  carrying `text-transform: uppercase`.
- **Editor checked, not just the front end.** Both cards render as overlays in the block
  editor, and the team card's bio panel is open and editable there.
- Local only. Nothing was written to dev — its theme is deployed by pull, and the MCP endpoint
  there is content-only (`editable: false`).

### 12.8 One button box

Every button in the theme is the same size. That is worth spelling out because it is not how
live behaves, and because the mechanism is not obvious.

`settings.custom.spacing.button` holds the only padding values any button uses:

| Token | Value | Live |
|---|---|---|
| `--wp--custom--spacing--button--padding-vertical` | `0.875rem` (14px) | 15px on the CTA, 10px on the outline |
| `--wp--custom--spacing--button--padding-horizontal` | `2rem` (32px) | 15px |

The horizontal padding roughly doubles live's, which is the single most visible piece of the
modernisation — live's buttons are tight around their label.

The button **element** in `theme.json` also sets a `2px solid transparent` border, and each
variation only ever recolours it. Without that, an outlined button would compute 4px taller
than a filled one and the two would never line up in a row. Every variation resolves to
**58px** at the default font size, which replaced the per-variation `min-height` hacks the
first pass used (55px on most, 45px on Outline Light).

Radius is `0` everywhere. Live carries a 2px radius on `.btn.white-border-btn` alone; nothing
else in the design has one, so it was squared off rather than propagated.
