# Changelog

All notable changes to the Southern Destinations 2026 theme are documented here.
Format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/);
this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

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
