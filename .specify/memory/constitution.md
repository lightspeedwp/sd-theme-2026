# sd-theme-2026 Constitution

Rules that are **specific to this repository** and not stated elsewhere.

`AGENTS.md` (this repo) and the workspace `AGENTS.md` remain authoritative for everything
shared: the theme/plugin deactivation test, the Estimate 3164 scope ceiling, the
Change-Control Register, source-of-truth precedence, security and accessibility
non-negotiables, and the WP-CLI memory flag. **This file does not restate them.** Where this
file and `AGENTS.md` appear to conflict, `AGENTS.md` wins and this file is wrong — amend it.

## Core Principles

### I. theme.json is generated, not hand-edited

Token values come from the token map via the extractor skills (`figma-themejson-*`), not from
manual edits — see `DESIGN.md`. Schema is `https://schemas.wp.org/wp/6.9/theme.json`,
`version: 3`. Tokens use **numeric slugs** (`100`/`200`/…), never named sizes.

Colour is referenced **directly by palette slug** (`var:preset|color|brand-500`). The semantic
custom-colour layer (`settings.custom.color` + a `styles/dark.json` mirror) is **not adopted**
in this theme and is not funded. Introduce it only if dark mode is approved, and only via
`theme-color-token-enforcer`.

Run `theme-orphaned-refs` after any token change.

### II. No literals in authored files

Patterns, templates, parts and styles reference preset tokens by numeric slug —
`var:preset|color|…`, `var:preset|spacing|…`, `var:preset|font-size|…`,
`var:preset|font-family|…`, `var:custom|font-weight|…`, `var:custom|line-height|…`.

No raw `#hex`, `rgb()`, font-family or font-weight literals. In raw `css` strings use
`var(--wp--preset--…)` / `var(--wp--custom--…)`.

### III. `var:custom|…` is silently dropped on dynamic blocks (NON-NEGOTIABLE)

On a **dynamic** block — `core/post-title`, `core/term-name`, `core/query-title`,
`core/post-excerpt`, `core/post-terms`, `core/post-author-name`, `core/navigation` — write
`var(--wp--custom--…)`, never the `var:custom|…` shorthand.

A static block's `style` object is resolved by the editor at save time. A dynamic block has no
saved markup: the server-side style engine resolves it, and that only expands `var:preset|…`,
and only for properties declaring `css_vars`. `fontWeight`, `lineHeight`, `fontStyle`,
`textTransform` and `letterSpacing` declare none
(`wp-includes/style-engine/class-wp-style-engine.php:292-370`). There is no notice and no
fallback — the declaration is simply absent and the block inherits.

`var:preset|color|…` and `var:preset|font-size|…` are safe on dynamic blocks.
Worked example: `patterns/safari-expert.php`.

### IV. Styling lives in JSON

Every style belongs in a block-style or section-style JSON partial under `styles/**` —
including its `css` field for selector-level rules. One source of truth, renders in the editor,
keeps the cascade clean.

- **Structured props** (color, border, radius, typography, spacing) and **native pseudo-states**
  (`:hover`/`:focus` as `styles` keys) → the JSON `styles` object.
- **Selector-level rules** that aren't structured props (descendant selectors, layout on
  generated wrappers, resting overrides that must out-specify a plugin) → the JSON style's
  **`css` field** (`&` = variation root).
- **`assets/styles/*.css`** → last resort, only for what the `css` field provably cannot hold.

The `css` field is `:where()`-zero-specificity (use `!important` to win), **strips**
`:hover`/`:first-child`, **mangles** `content:""`, and **drops** comma `&` selectors. Those
four cases are the legitimate reasons to reach for an authored stylesheet — nothing else is.
See the `wp-blockstyle-css-field` skill.

### V. Never hardcode a per-install ID

No `ref` ID on a `wp:navigation` block. No Gravity Forms `formId`. No attachment IDs
(`"id":52466`, `wp-image-52466`). These are per-install values that no deploy step can fix;
they were the single largest source of breakage inherited from the KWV base.

**Uploads URLs are the exception** and are written out literally, as core writes asset URLs.
The go-live deployment runs a find-and-replace over the dev host by convention, so a
`$sd_uploads`-style variable buys nothing.

## Additional Constraints

**Every template has exactly one `<main>` landmark.** The sibling ATI theme shipped without one
on its Tour Operator templates and had to retrofit it.

**`patterns/*.php` follows core's form**, measured: inline `esc_html_e()` / `esc_html_x()`, no
variables holding literals, no loops, no `phpcs:ignore`. Text domain on every escape.

**`inc/` is design-only** — styling third-party plugin markup. It is not a back door for logic.
See `inc/README.md`.

**Semantic `tagName`s, correct heading hierarchy, no inline styles in templates or parts.**

## Development Workflow

Spec Kit (`/speckit-*`) governs **feature-level work inside this repo**. OpenSpec (`/opsx:*`,
at the workspace root) governs **project-level scope** — the estimate line items and the
`website-rebuild-2026` change. A feature that changes what is delivered against a line item
belongs in OpenSpec first; how that feature is built belongs here.

Every feature branch is `feature/ls-NNNN-*` against `develop`. `CHANGELOG.md` is updated as
part of the work (Keep a Changelog 1.1.0), tagged with the Linear issue.

Quality gate before hand-over: `phpcs --standard=.phpcs.xml.dist .` clean, every PHP file
syntax-checked, and `wp transient delete --all --network` run if patterns or styles changed.

**Nothing is committed without review.** Work is left in the working tree.

## Governance

This constitution covers only rules specific to `sd-theme-2026`. It is subordinate to
`AGENTS.md` and to the source-of-truth precedence defined there — Estimate 3164 first, then the
Block Theme and Plugin Specification, then the live site.

Amendments: edit this file (or run `/speckit-constitution`), state what changed and why in
`CHANGELOG.md`, and bump the version below. A principle that turns out to duplicate `AGENTS.md`
should be deleted here, not reconciled.

Scope questions are not settled by this file. Out-of-scope work goes to the Change-Control
Register (LS-2033) with a rough value; **Zared decides**.

**Version**: 1.0.0 | **Ratified**: 2026-09-17 | **Last Amended**: 2026-09-17
