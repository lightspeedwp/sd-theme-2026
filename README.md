# sd-theme-2026

The **Southern Destinations 2026** WordPress block theme — a Full Site Editing theme for
[southerndestinations.com](https://www.southerndestinations.com/), a South African inbound
tour operator.

Built by [LightSpeed](https://lightspeedwp.agency/) as part of the *Website Rebuild 2026*
project. Derived from [kwv-theme-2026](https://github.com/lightspeedwp/kwv-theme-2026) for its
architecture and token conventions, fully de-branded.

> **Status: scaffold.** Structure, identity and conventions are in place. Design tokens are
> **provisional** and the pattern library is not built yet — see
> [DESIGN.md](DESIGN.md) §3 and §6.

---

## The theme carries design only

> **If deactivating the theme would break it, it belongs in the companion block plugin.**

This theme contains `theme.json`, styles, patterns, template parts and templates. Post types,
expiration rules, WETU import, form handling, search integration, CRM routing, sticky-header
and mobile-menu behaviour all live in the companion Southern Destinations block plugin — even
though they surface visually.

There is **no WooCommerce** on this site.

---

## Requirements

| | |
|---|---|
| WordPress | 6.9+ |
| PHP | 7.4+ |
| Post types | `accommodation`, `destination`, `tour` — from the **LSX Tour Operator** plugin |
| Optional | TO Reviews (`review`), TO Specials (`special`), SearchWP Pro, FacetWP, Gravity Forms, Yoast SEO Premium |

Templates for post types whose plugin is inactive are inert, not broken.

---

## Layout

```
theme.json      Global Styles + settings. Numeric token scales. Schema wp/6.9.
style.css       Theme header + CSS reset/base.
functions.php   Setup, block styles, pattern categories. Namespace SdTheme2026.
inc/            Design-only modules. Usually empty — see inc/README.md.
templates/      Core WP templates + Tour Operator archives, singles and taxonomies.
parts/          header · footer · sidebar · mega-menu · dropdown-menu · mobile-menu · single-hero
patterns/       Block patterns (.php), slugs namespaced sd-theme-2026/*
styles/         Block & section style variations (scanned recursively)
  blocks/<block>/   Variations scoped to one block type
  sections/         core/group "section" compositions
assets/styles/  Per-block CSS, lazy-enqueued by filename (core-*.css → core/*)
.claude/        Ported agent skills + personas, so this repo works standalone
```

---

## Conventions in one screen

- **Tokens by numeric slug.** `var:preset|color|brand-500`, `var:preset|spacing|30`,
  `var:preset|font-size|200`. No raw hex or font-family literals in authored files, ever.
- **Styling lives in JSON.** Block and section styles under `styles/**`, including their
  `css` field. `assets/styles/*.css` is a last resort, with a comment saying which limitation
  forced it.
- **Per-block CSS is convention-driven.** `assets/styles/core-<block>.css` auto-enqueues for
  `core/<block>` only when that block renders. Don't break the filename mapping.
- **Cache-busting is automatic.** Enqueue with `SdTheme2026\asset_version( $path )` — it uses
  the file mtime. Never hardcode a version.
- **One `<main>` landmark per template.** Escape all PHP output with the `sd-theme-2026` text
  domain.
- **No hardcoded per-install values** — navigation `ref` IDs, uploads URLs, form IDs.

Full detail in [AGENTS.md](AGENTS.md).

---

## Development

```bash
# Lint
phpcs --standard=WordPress .
phpcbf --standard=WordPress .

# Syntax check
find . -name '*.php' -not -path './.claude/*' -exec php -l {} \;

# WP-CLI on this install needs a raised memory limit (and has no MySQL — SQLite)
php -d memory_limit=1024M $(which wp) theme list

# New patterns and styles don't register until the pattern transient is cleared
php -d memory_limit=1024M $(which wp) transient delete --all --network
```

`.claude/skills/` ships the theme's working skills — token extractors,
`theme-orphaned-refs`, `block-theme-audit`, `pattern-extractor` and others. They are
discovered at startup, so start a fresh session after pulling changes to them.

---

## Documentation

| File | What's in it |
|---|---|
| [AGENTS.md](AGENTS.md) | Orchestration guide — boundary, scope, conventions, environment |
| [DESIGN.md](DESIGN.md) | Design sources, token state, the extractor pipeline, pattern library |
| [CONTRIBUTING.md](CONTRIBUTING.md) | Workflow, git, quality bar |
| [CHANGELOG.md](CHANGELOG.md) | Release history |
| [inc/README.md](inc/README.md) | What may and may not live in `inc/` |

---

## Licence

GNU General Public License v3 or later. See [LICENSE](LICENSE).

Southern Destinations 2026, © 2026 Southern Destinations / LightSpeed.
