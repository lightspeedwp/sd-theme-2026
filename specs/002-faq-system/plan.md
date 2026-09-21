# Implementation Plan: FAQ System, Schema and AI Workflows (theme scope)

**Branch**: `002-faq-system` (spec-kit branch name) | **Work branch**:
`feature/ls-4215-feat-southern-destinations-implement-faq-system-schema-and`, **this
repository only** (`sd-theme-2026`) | **Date**: 2026-09-18 | **Spec**: [spec.md](./spec.md)

**Input**: Feature specification from `specs/002-faq-system/spec.md`

**Linear**: [LS-4215](https://linear.app/lightspeedwp/issue/LS-4215/feat-southern-destinations-implement-faq-system-schema-and-ai) — Estimate 3191, R65,981.25 inc VAT, PRD v0.6

---

## Summary

Build one non-synced FAQ block pattern using WordPress core's native `core/accordion` family
(`core/accordion` → `core/accordion-item` → `core/accordion-heading` + `core/accordion-panel`)
— the same native-block approach this theme already adopted for `styles/blocks/accordion/call-us-dropdown.json`,
replacing what used to be a bespoke `sd/call-us` block. No new block, no JavaScript, no
custom accessibility wiring: the Interactivity API core ships already provides keyboard
operation, visible focus and programmatic expanded/collapsed state.

The pattern is composed of:
1. A new block-style variation, `styles/blocks/accordion/faq.json`, scoped to
   `core/accordion`/`core/accordion-item`/`core/accordion-heading`/`core/accordion-panel` —
   **not** copying `call-us-dropdown`'s `hidden="until-found" → display:none` override
   (that file's own warning: right for a header phone-number disclosure, wrong for a FAQ,
   where in-page find and `#hash` links opening a closed answer is exactly the behaviour to
   keep).
2. A new template part, `parts/faq-section.html`, carrying the "Frequently asked questions"
   heading and an empty `core/accordion` scaffold that editors fill with
   `core/accordion-item`s — the vehicle for FR-001's "shared structure across placements,
   independent content per placement" (Q3).
3. A new pattern, `patterns/faq-section.php`, that references the template part and is what
   gets inserted on each of the nine approved placements plus the general FAQ page. Because
   it is a plain (non-synced) pattern reference, each insertion is copied at insert time and
   edited independently — exactly the Q3 answer.
4. A new page-composition pattern, `patterns/template-page-faq.php`, and template
   `templates/page-faq.html`, for the general FAQ page (FR-008), following the thin-template
   → composition-pattern convention already used by every other template in this theme
   (`templates/archive-special.html` → `patterns/template-archive-special.php`, etc.).

Empty rendering (FR-002) is expected to fall out of the markup itself — an `core/accordion`
with zero `core/accordion-item` children plus a parent wrapper conditioned on inner-block
presence — but **this has not yet been measured against a running install** (the local
MySQL server was not reachable from this planning session). It is recorded as an open
verification item in [research.md](./research.md) R-01 and a required check in
[quickstart.md](./quickstart.md), not asserted as fact. If core does not elide it for free,
FR-002 may need an explicit conditional wrapper in `patterns/faq-section.php` (checking for
inner block count before rendering the heading/container) — a small, contained fallback,
not a scope change.

**Everything else in LS-4215 — Tour Operator/Specials/Team schema-tag output (FR-014,
FR-015), and WordPress AI Engine + MCP configuration (FR-012, FR-013) — is out of scope for
this repository**, confirmed 2026-09-18 (spec Clarifications Q1/Q2): it is built in
`ls-plugin` (`/Users/warwick/Local Sites/beta/app/public/wp-content/plugins/ls-plugin`).
This plan covers FR-001–FR-011 and FR-016–FR-017 only. The markup contract this theme owes
`ls-plugin`'s schema layer is recorded in
[contracts/faq-markup-contract.md](./contracts/faq-markup-contract.md) so that work can
proceed independently without either side guessing at the other's shape.

## Technical Context

**Language/Version**: PHP 8.3 (WordPress 7.0.x block theme), HTML block markup, JSON
(`theme.json` schema `https://schemas.wp.org/wp/6.9/theme.json`, version 3)

**Primary Dependencies**: WordPress core `core/accordion`, `core/accordion-item`,
`core/accordion-heading`, `core/accordion-panel` (native, WP 7.1+, Interactivity API);
existing theme CTA patterns (`patterns/cta-*.php`); existing Tour Operator settings-backed
banner/title/intro pattern already used by other archive templates, reused unmodified for
the general FAQ page's header/intro per FR-008.

**Storage**: FAQ question/answer content is ordinary post content (block markup) inside
each page/template's own content — no new post type, meta field, or option. **This feature
adds no storage.**

**Testing**: `phpcs --standard=.phpcs.xml.dist patterns/ parts/ templates/`; `php -l` on
every new/changed PHP file; `wp transient delete --all --network` after pattern/style
changes; manual and automated verification against the local/dev install per
[quickstart.md](./quickstart.md) (keyboard operation, focus visibility, empty-state
rendering, phone-width reflow, automated accessibility scan). No PHPUnit suite in this
theme; no new test tooling introduced.

**Target Platform**: WordPress 7.0.x (local: WordPress Studio/SQLite; dev/live: standard
MySQL stack), PHP 8.3, this theme active with the LSX Tour Operator plugin family (for the
Archives/Destination/Specials/Team placements) — templates for an inactive plugin's post
type are inert, not broken, per `AGENTS.md`.

**Project Type**: WordPress block theme (design layer only — see Summary for the
theme/plugin split).

**Performance Goals**: No measurable regression to page load or Core Web Vitals from adding
an FAQ section — the accordion's panels are native block markup with `hidden="until-found"`,
not JS-rendered, so there is no added script cost beyond core's own Interactivity API
(already loaded wherever `core/accordion` appears elsewhere in this theme).

**Constraints**: Numeric-token-only styling (no raw hex/font values); one `<main>` per
template; no hardcoded `wp:navigation` `ref` or Gravity Forms `formId`; `blockGap`-not-margin
spacing discipline; styling lives in JSON block/section styles, not `assets/styles/*.css`,
except where the `css` field provably cannot express it (per this theme's `AGENTS.md` §
"Styling lives in JSON (block & section styles)").

**Scale/Scope**: One shared pattern + one template part + one block-style variation + one
new page template/composition pattern, touching up to 9 existing
templates/patterns for placement, no data migration, no new dependencies.

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

| Constitution principle | Applies? | Compliant? |
|---|---|---|
| I — `theme.json` generated, not hand-edited | No new tokens needed; no `theme.json` edit anticipated | ✅ N/A (no changes planned) |
| II — No literals in authored files | New pattern/part/style-variation must reference numeric token slugs only | ✅ Planned — `faq.json` reuses `call-us-dropdown.json`'s existing token references (`neutral-*`, `border-radius--200`, `shadow--300`, spacing/typography customs) rather than inventing new values, per PRD's "no new visual decisions" |
| III — `var:custom\|…` dropped on dynamic blocks | `core/accordion-heading`'s button text and `core/post-title`/similar dynamic blocks are not used inside the FAQ pattern (questions/answers are static `core/paragraph`/heading content, editor-authored) | ✅ N/A — no dynamic block carries a `var:custom` typography/line-height/weight value in this pattern |
| IV — Styling lives in JSON | Accordion styling belongs in `styles/blocks/accordion/faq.json`, not `assets/styles/*.css` | ✅ Planned — see Summary point 1; any exception (if the `css` field truly cannot express something) will be documented inline per the four-case rule |
| V — Never hardcode a per-install ID | New template/part/pattern must carry no `wp:navigation` `ref`, no Gravity Forms `formId`, no attachment `id` | ✅ Planned — the FAQ pattern has no navigation block, no form, and any illustrative image will be a placeholder, not a hardcoded attachment ID |
| One `<main>` per template | `templates/page-faq.html` (new) | ✅ Planned — `patterns/template-page-faq.php` will carry exactly one `<main>`, matching `templates/archive-special.html`'s pattern-delegation convention |
| `patterns/*.php` follows core's form | `patterns/faq-section.php`, `patterns/template-page-faq.php` (new) | ✅ Planned — inline `esc_html_e()`/`esc_html_x()`, no top-level variables holding literals, no loops, no `phpcs:ignore`, `sd-theme-2026` text domain, `@package sd-theme-2026` docblock |
| `inc/` is design-only | No `inc/` change anticipated | ✅ N/A |
| Semantic tagNames, heading hierarchy, no inline styles | All new markup | ✅ Planned — verified in quickstart.md |
| Theme = design / plugin = behaviour (AGENTS.md's governing rule) | Schema-tag output and AI/MCP config | ✅ Resolved 2026-09-18 (spec Q1) — those live in `ls-plugin`; this plan does not build them |

No violations requiring justification. Complexity Tracking table is omitted (empty).

## Project Structure

### Documentation (this feature)

```text
specs/002-faq-system/
├── plan.md                          # This file
├── research.md                      # Phase 0 output
├── data-model.md                    # Phase 1 output
├── quickstart.md                    # Phase 1 output
├── contracts/
│   ├── faq-markup-contract.md       # Theme → ls-plugin schema data contract
│   └── placement-contract.md        # Where the FAQ pattern is inserted, per template
├── checklists/
│   └── requirements.md
└── tasks.md                         # Phase 2 output (/speckit-tasks — not this command)
```

### Source Code (repository root — `sd-theme-2026`)

```text
patterns/
├── faq-section.php               # NEW — the one reusable FAQ pattern (FR-001, FR-006)
└── template-page-faq.php         # NEW — general FAQ page composition (FR-008, FR-009)

parts/
└── faq-section.html              # NEW — shared FAQ structure (heading + accordion scaffold)

templates/
└── page-faq.html                 # NEW — thin shell delegating to template-page-faq.php

styles/
└── blocks/
    └── accordion/
        └── faq.json               # NEW — block-style variation for the FAQ accordion

# Existing files gaining one new `wp:pattern` reference each (placement, FR-006):
templates/front-page.html          # Homepage
templates/archive-*.html           # Archives (as applicable — see placement-contract.md)
templates/single-destination.html  # Destination templates
templates/archive-special.html     # Specials
templates/single-team.html         # Team
# + composition patterns behind page.html-based pages (Why Book With Us, Connect With Us,
#   Contact, Social Responsibility) — these are ordinary Pages, not custom templates, so the
#   FAQ pattern is inserted as page content, not a template edit. See placement-contract.md.
```

**Structure Decision**: Follows this theme's existing thin-template → composition-pattern
convention exactly (`templates/*.html` stay near-empty shells; real markup lives in
`patterns/template-*.php`). No new directories, no build tooling, no new dependency — one
pattern, one template part, one style variation, one new template + its composition pattern,
and reference insertions into existing files.

## Complexity Tracking

*No violations — table omitted.*
