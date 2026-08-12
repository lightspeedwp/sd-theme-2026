# Changelog

All notable changes to the Southern Destinations 2026 theme are documented here.
Format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/);
this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- **`style.md`** — the token map and asset inventory for the theme: measured token state
  across Figma, the live site and `theme.json`, the bundled font layer, the WCAG contrast
  constraints, and the live asset inventory with per-asset porting decisions.
  Companion to the workspace audit report
  (`.github/reports/sd-design-audit-2026-08-12.md`). *(LS-2012)*
- **`assets/fonts/` — 13 WOFF2 faces (474 KB)**, converted from the live sources
  with `woff2_compress` and registered as `fontFace` entries in `theme.json`:
  - `Optima` 400, 500 and 600–700 — the 600–700 face is the live site's Optima Demi Bold,
    declared as a weight *range* so `h1`/`h2` (700) and `h3` (600) both resolve to the real
    cut instead of synthesising.
  - `Belleza` 400 — registered as a face in its own right so the fallback in the `heading`
    stack actually resolves, and so it works as a standby if the Optima licence does not
    clear.
  - `Open Sans` 300/400/600 in normal and italic plus 700 normal (7 faces), each with its
    own weight and style rather than the 11-file single-`src` stack used on live. Weights
    800/900 and the 700 italic were dropped as unneeded. Requests above what is bundled
    resolve to the nearest real face per CSS font matching rather than synthesising — body
    text at 800/900 renders Open Sans 700, and heading text at 900 renders Optima's
    demi-bold cut. Unused weights across all families will be pruned at the end of the
    rebuild.
  - `Joe Hand` 400 and `La Belle Aurore` 400 for the `accent` family.
- `settings.color.palette`: **`accent-100` … `accent-900`** — a **yellow** ramp anchored on
  `#E6AD10` at step 500, the gold used in several places on the live site (22 references)
  that previously had no token. Figma's `accent` rows still hold the `brand` values they were
  seeded with; the ramp was confirmed as intended-but-unfinished and derived here by mirroring
  `brand`'s construction (same relative chroma profile, hue held at the anchor's 83°). The
  lightness skeleton is rebuilt around the anchor rather than reused from `brand`: `#E6AD10`
  measures L\* 74, where `brand-400` sits, so reusing `brand`'s skeleton would have made the
  ramp non-monotonic with step 400 darker than 500. Yellow is a dark-background colour — for
  gold text on a light background use `accent-700` (7.08 AAA). *(LS-2012)*
- `settings.color.palette`: **`primary-100` … `primary-900`** — Figma's warm brown ramp,
  inserted after `neutral-900` to match Figma's collection order. With `accent-*` the palette
  is now **46 entries, exactly matching Figma's 46 colour variables** (2 + 9 + 9 + 9 + 9 + 8).
- `settings.custom.borderWidth`: `0 · 1px · 2px · 4px · 8px`, from Figma's Border
  collection. Emits as `--wp--custom--border-width--{0,100,200,300,400}`.
- `settings.custom.layout.fullWidth`: `1920px`, from Figma's Layout collection — no core
  token exists for it. Corroborated by Figma's 1920px reference capture.
- Initial scaffold, derived from `lightspeedwp/kwv-theme-2026`.
- Core WordPress templates: `index`, `front-page`, `page`, `single`, `archive`,
  `category`, `tag`, `search`, `404`, plus the `page-no-header`, `page-no-title`
  and `page-with-sidebar` custom templates.
- Tour Operator template stubs — `archive-*` and `single-*` for `accommodation`,
  `destination`, `tour`, `review`, `special` and `team`, and `taxonomy-*` for
  `travel-style`, `accommodation-type`, `accommodation-brand`, `facility` and
  `continent`. Every one carries a single `<main>` landmark.
- Template parts: `header`, `footer`, `sidebar`, `mega-menu`, `dropdown-menu`,
  `mobile-menu` (two-tier), `single-hero`.
- Ported agent guidance — `AGENTS.md`, `CLAUDE.md`, `DESIGN.md`,
  `CONTRIBUTING.md`, `inc/README.md` — plus 18 skills and 3 personas under
  `.claude/`, so the theme can be worked on as a standalone checkout.

### Changed

- Identity: `Theme Name: Southern Destinations 2026`, PHP namespace
  `SdTheme2026`, text domain `sd-theme-2026`, block-style handles
  `sd-theme-2026-block-*`, pattern namespace `sd-theme-2026/*`.
- `theme.json`: schema pinned to `wp/6.9`; `brand-*` ramp replaced with a
  **provisional** Southern Destinations ramp anchored on the live site's
  `#CC7F16`; font families replaced with the live site's stacks (Optima /
  Open Sans / La Belle Aurore) with no bundled faces pending licensing.
- **`neutral-200` … `neutral-900` re-derived as a warm ramp** from the live site's measured
  neutrals (hue ≈78°, chroma peaking mid-ramp), replacing the inherited pure-grey values.
  `neutral-200` is now exactly `#F7F5F2`, the live site's dominant section background.
  The outgoing ramp's L\* skeleton was preserved deliberately, so contrast is held within
  ±0.05 at every step and none of the 71 existing `neutral-*` references regress. *(LS-2012)*
- **`brand-*` ramp replaced with Figma's values** at the 8 steps that diverged. `brand-500`
  `#CC7F16` is unchanged — all three sources already agreed on the anchor. No ramp step
  other than 500 appears on the live site, so nothing visible changes. *(LS-2012)*
- **Font families replaced with the live-measured values**, Figma's typography table being
  demonstrably wrong (it names Joe Hand as the heading face; the live heading face is Optima
  Demi Bold at 31 uses against Joe Hand's 8 as a script accent):
  - `heading`: `Optima, Belleza, sans-serif` — was `Optima, Belleza, Palatino, Georgia,
    serif`. The serif fallback chain was wrong; Optima is a humanist sans.
  - `accent`: `"Joe Hand", "La Belle Aurore", cursive` — Joe Hand added ahead of
    La Belle Aurore, matching live.
  - `body` unchanged — already correct, with richer system fallbacks than live.
- Header, footer and front-page rebuilt as clean scaffolds — the inherited ones
  carried hardcoded navigation `ref` IDs, uploads URLs and a Gravity Forms
  embed with inline hex.

### Removed

- **All WooCommerce**: `inc/woocommerce.php`, 11 `woo-*` patterns, 9 commerce
  templates, 5 commerce parts, 7 commerce stylesheets, cart/product block styles.
- **All KWV-specific artifacts**: 8 `inc/` behaviour modules, ~30 brand page
  patterns, brand logos and imagery, 6 behaviour scripts, bundled Poppins and
  Bodoni Moda font families, and the transparent/dark header variants.
- Orphaned CSS and dead references: the transparent-header flow rule, the
  KWV mega-menu block rule, the unregistered mega-menu search style, the
  `author-role` block bindings (that meta belongs in the block plugin), and the
  dangling `blog-post-card` pattern reference.
- The **`alternate` font-family preset**, added briefly for regular-weight Optima and then
  withdrawn: with the Optima family registered across four weights it resolved to the same
  stack as `heading`, making it a duplicate token. Regular Optima is now simply weight 400
  of `heading`. *(LS-2012)*
- **The four commercially-licensed font faces are no longer tracked in this repository** —
  `optima-400-normal`, `optima-500-normal`, `optima-700-normal` and `joe-hand-400-normal`.
  They remain in the working tree and are supplied by the build/deploy pipeline. See
  **Security** below for why, and for the history caveat. *(LS-2012)*

### Fixed

- Two dead preset references in `patterns/template-index-news.php` and
  `patterns/template-category.php`, which called `var:preset|spacing|0` where no `0` slug
  exists (the spacing scale starts at `5`), silently dropping the intended zero padding.
  Replaced with a literal `0` in both the block comment and the rendered markup so the
  editor does not flag the blocks as invalid. Pre-existing; found by the orphan scan.
  `theme-orphaned-refs` now reports **0 orphans** across 557 references. *(LS-2012)*
- All five `@font-face` defects carried by the live child theme are corrected in the bundled
  font layer: swapped `format()` hints, `font-family` descriptors holding a fallback list,
  eleven Open Sans files stacked under a single weight, a broken `../../fonts/` path with a
  missing comma, and TTF delivery instead of WOFF2. *(LS-2012)*

### Security

- **`assets/fonts/optima-*.woff2` and `joe-hand-*.woff2` are `.gitignore`d and untracked**,
  delivered by the build/deploy pipeline instead of being committed. They are commercially
  licensed — Optima is a Linotype face and the heading face of the whole site — with
  web-embedding rights unconfirmed, and **this repository was public when they were first
  pushed**, making them briefly downloadable by anyone. The OFL faces (Open Sans, Belleza,
  La Belle Aurore) remain committed. *(LS-2012)*

  Two mitigations, both applied 2026-08-12 and complementary rather than redundant:
  - **The repository was switched to `PRIVATE`.** This is what contains the exposure.
    It was small to begin with: created 2026-08-12 with 0 forks, 0 stars and 0 watchers.
  - **The faces were untracked** (`git rm --cached`, after the `.gitignore` rules alone
    proved inert against files git already tracked). This is what keeps them out of future
    clones and deploys. The files stay in the working tree, so local development is
    unaffected. A fresh clone now carries 9 OFL faces (353 KB); the other 4 (122 KB) come
    from the pipeline.

  Two caveats that remain:
  - ⚠️ **History is not rewritten.** The faces are still retrievable from the earlier
    commits by anyone with repo access — verified. Acceptable while the repo is private.
    **If it is ever made public again, history must be rewritten first**
    (`git filter-repo` over `assets/fonts/optima-*` and `joe-hand-*`, then a force-push,
    which invalidates existing clones). Cheap now, unpleasant later.
  - ⚠️ `theme.json` still registers all 13 faces, so a fresh clone has **4 unresolved
    `@font-face` rules** until the pipeline supplies the files. Headings fall back to
    Belleza — which is bundled precisely for this — and then `sans-serif`. Degraded, not
    broken.
- 🟠 **Optima and Joe Hand web-embedding licences are unconfirmed — written confirmation is
  being sought from the client.** The faces are **approved for development use as the primary
  fonts**, but this remains a **release gate: they must not ship to production until the
  rights are confirmed in writing.** If Optima does not clear, substitution is a
  Change-Control Register item. *(LS-2012)*
- `Optima_Italic.ttf` was **not** ported — the live source file is corrupt (its `glyf` table
  range overlaps `cmap`) and `woff2_compress` rejects it. It never loaded on live either.
  Italic Optima will synthesise an oblique until a clean source file is supplied.
