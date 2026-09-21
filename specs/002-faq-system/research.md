# Phase 0 Research: FAQ System (theme scope)

## R-01 — Does an FAQ section with zero entries actually render nothing? ✅ measured 2026-09-21

**Question**: FR-002 requires the FAQ pattern to render no heading, container, or empty
disclosure control when it holds no question/answer content. Does WordPress core's block
rendering already do this for an empty `core/accordion` wrapped in a heading + group, or
does the pattern need an explicit conditional?

**Measured** (site: `beta.local` / Local by Flywheel, once the site's MySQL socket was
located and `mysqli.default_socket` pointed at it — `wp eval`'s default connection attempt
otherwise fails with "No such file or directory", since `wp-config.php`'s `DB_HOST` is
`localhost` and PHP's compiled-in default socket path doesn't match Local's per-site
socket). `do_blocks()` on the pattern's actual markup, filled and then with its one
`core/accordion-item` removed:

- **Filled**: renders the full section — heading, accordion, item, interactivity
  directives (`data-wp-context`, `aria-controls`, `data-wp-bind--aria-expanded`,
  `data-wp-bind--hidden`, `data-wp-on--beforematch`, etc.) — all correctly wired by core's
  `render_block_core_accordion`/`block_core_accordion_item_render`, confirming the
  hand-authored markup in R-02's implementation note was structurally correct.
- **Emptied** (accordion present, zero `core/accordion-item`s): **does NOT render
  nothing.** Output is `<section>...<h2>Frequently asked questions</h2>...<div
  class="wp-block-accordion ... is-style-faq ..."></div></section>` — the heading and an
  empty (but real, box-generating) accordion `<div>` both still render. Core does not elide
  this for free. The naive "editorial discipline only" fallback from the original decision
  below would have left FR-002 unmet whenever an editor empties an accordion without
  removing the pattern instance (exactly User Story 2, Scenario 3).

**Decision, revised from the original (pre-measurement) plan**: rather than accept the
editorial-discipline-only answer, implemented a CSS-level fix and confirmed it compiles
correctly. The wrapping `<section>` in `patterns/faq-section.php` carries a new block-style
variation, `styles/sections/faq-section.json` (`is-style-faq-section` on `core/group`),
whose `css` field reads:

```
&:has(.is-style-faq:empty){display:none !important;}
```

Verified by replicating WordPress core's own per-instance variation-CSS compiler
(`wp-includes/block-supports/block-style-variations.php`) directly against this variation's
data — not just visually inspecting the JSON. The compiled output for a rendered instance
is exactly:

```css
:root :where(.wp-block-group.is-style-faq-section--1:has(.is-style-faq:empty)){display:none !important;}
```

This is one of the four documented exceptions in this theme's "styling lives in JSON" rule
(AGENTS.md) for reaching into the `css` field: an ancestor-watches-descendant-emptiness
rule that no structured `styles` prop can express. `core/group` has no attribute for "hide
when a descendant is empty." `!important` for the same reason `call-us-dropdown.json` and
`main-navigation.json` use it — the `css` field compiles at specificity (0,1,0) via
`:where()`, so a tie on source order is unsafe to leave unmarked, even though nothing else
currently sets `display` on this element.

**Not yet verified**: actual browser rendering (this measurement confirms the CSS *compiles
correctly*, not that a real browser applies `:has()` as expected — though `:has()` has been
baseline-supported in evergreen browsers since 2023 and is not a novel risk). Confirm
visually once quickstart.md step 1 is run in an actual browser against a saved page.

**Alternatives considered**: A PHP `render_block` filter that hides the section
server-side regardless of editorial discipline. Rejected — the CSS `:has()` fix is simpler,
doesn't require `functions.php` logic for a design-layer concern, and is now confirmed to
work rather than hypothetical.

---

## R-02 — What's the right block mechanism for the FAQ disclosure widget?

**Decision**: WordPress core's native accordion block family —
`core/accordion` → `core/accordion-item` → `core/accordion-heading` + `core/accordion-panel`
(WP 7.1+, ships with the Interactivity API).

**Rationale**: This theme already made this exact choice once, on record, in
`styles/blocks/accordion/call-us-dropdown.json`. That file documents in detail why: the
project used to have a bespoke `sd/call-us` block that manually built a `<button>`, a panel,
and the ARIA wiring between them — replaced 2026-08-21 once `core/accordion` shipped
equivalent behaviour natively (`core/accordion-heading` renders a real
`.wp-block-accordion-heading__toggle` button; open/close state runs through the
Interactivity API; `hidden="until-found"` on the panel plus `.is-open` on the item, no
inline height animation to fight). Re-deriving a second bespoke solution for FAQs — a
block-theme feature that is *more* accordion-shaped than a header phone-number disclosure —
would contradict that decision for no reason. It also satisfies FR-003/FR-004 (keyboard
operation, visible focus, no hover-only interaction) and User Story 3's ARIA
expanded/collapsed exposure for free, because core's Interactivity API already implements
all of it.

**One explicit correction carried over from that file's own warning**: `call-us-dropdown.json`
overrides the panel to `display: none` when closed, because `hidden="until-found"`'s default
behaviour (`content-visibility: hidden`, box still participates in some ways, and — the part
that matters here — `beforematch`/in-page-find/hash-link opening of a closed panel) was
wrong for four phone numbers in a header. It is explicitly **right** for a FAQ: a visitor
following a link to `#faq-refund-policy` or using in-page Find should be able to land on a
closed answer and have it open. **The new `styles/blocks/accordion/faq.json` variation must
NOT copy that `display: none` override.** This is flagged in plan.md's Summary and must be
re-flagged in the style variation's own file per this theme's documentation convention (every
existing accordion style file explains its own deviations at length).

**Implementation note, added 2026-09-21**: `patterns/faq-section.php` and
`parts/faq-section.html` were hand-authored against `wp-includes/blocks/accordion*` on disk
(block.json attributes plus the `render_block_core_accordion` / accordion-item render
callbacks — read directly from source, not guessed), because the local site's database was
unreachable and the block editor couldn't be used to generate ground-truth saved markup.
Those render callbacks only *augment* existing class-matched elements with Interactivity
API directives (`data-wp-*`, `aria-controls`, bound `aria-expanded`/`hidden`) — they don't
invent the base markup — so getting the class names and static attributes right
(`wp-block-accordion`, `wp-block-accordion-item`, `wp-block-accordion-heading__toggle`,
`wp-block-accordion-heading__toggle-title`, `wp-block-accordion-panel`, literal
`hidden="until-found"` on a closed panel) should be sufficient for correct behaviour. **One
thing was deliberately left out, not verified**: `showIcon` defaults to `true` on
`core/accordion`, meaning core's real save() output likely includes a chevron icon (SVG)
inside the heading button that this hand-authored markup does not reproduce. Whether that's
cosmetic-only or something the block's own JS expects to find is unconfirmed. **First thing
to check once the site is reachable (tasks.md T006)**: open `patterns/faq-section.php` in
the block editor, let WordPress re-serialize it, and diff the result against the
hand-authored version — adopt whatever core actually produces for the icon markup
specifically, keeping everything else in this file as authored.

**Alternatives considered**:
- **`core/details`/`core/details-content` (native `<details>`/`<summary>`)**. Rejected:
  `patterns/template-single-team.php` already has a documented reason for avoiding it on
  this theme for a comparable disclosure ("would change the desktop page to fix a phone" —
  not directly applicable here, but it establishes that `core/details` was considered and
  passed over once already in this codebase). More importantly, `core/accordion` supports
  multiple grouped items with shared `autoclose` behaviour and chevron iconography that
  matches a conventional FAQ list better than independent `<details>` elements, and reuses
  styling infrastructure (`faq.json`) analogous to `call-us-dropdown.json` rather than a
  second, divergent approach.
- **A custom interactive block or a JS-driven accordion**. Rejected outright — this theme's
  whole `call-us-dropdown.json` narrative is the record of retiring exactly this kind of
  bespoke solution once core caught up; reintroducing one for FAQs would be regressive, and
  `AGENTS.md` working agreement 6 caps new build tooling/dependencies without explicit
  justification, which none exists here.

---

## R-03 — Non-synced pattern vs. synced pattern with template parts (Q3, already decided)

**Decision** (per spec Clarifications Q3, answered by the user): non-synced pattern per
placement, with shared structure carried by a **template part** (`parts/faq-section.html`)
rather than a synced pattern.

**Rationale**: A synced (`wp:pattern` with `syncStatus: full`) FAQ pattern would force every
placement's *content* to be identical, which directly contradicts the PRD's own distinction
between general and contextual FAQ content (§6) — different questions on different pages.
A template part gives the *structural* consistency (heading treatment, accordion wrapper,
any shared "Still have questions?" framing) that "one shared visual pattern" (PRD §3, §4.2)
actually asks for, while each page's `patterns/faq-section.php` insertion holds independent,
editable question/answer content. This mirrors how `parts/single-hero.html` already
supplies shared structure reused across several single templates in this theme, rather than
inventing a new mechanism.

**Alternatives considered**: Block bindings to a single external content source (rejected —
no such source exists or is being introduced; FAQ content is explicitly *not* a managed
library, PRD §5); a fully synced pattern with per-instance attribute overrides (rejected —
WordPress's override mechanism is designed for a handful of scalar attributes, not an
open-ended, reorderable list of question/answer pairs, and using it here would be a novel,
unbudgeted mechanism for this theme).

---

## R-04 — Where does the FAQ pattern get inserted on each approved placement?

**Decision**: Recorded in [contracts/placement-contract.md](./contracts/placement-contract.md).
Summary of the mechanism, confirmed by reading the actual template files:

- Templates in this theme are thin shells (`wp:template-part` for header/footer plus one or
  more `wp:pattern` references) — e.g. `templates/archive-special.html` is three lines
  delegating to `patterns/template-archive-special.php`; `templates/front-page.html` is a
  flat stack of nine `wp:pattern` references directly in the template.
- **Homepage, Specials, Team, Destination templates** (and any other placement backed by a
  dedicated `templates/*.html` + `patterns/template-*.php` pair): the FAQ pattern reference
  is added either directly in the thin template (homepage's style) or inside the
  composition pattern (every other archive/single's style), placed immediately before the
  page's existing closing CTA pattern (`patterns/cta-*.php`) — matching PRD §4.3's implicit
  ordering ("FAQ then existing enquiry CTA," mirrored in the general FAQ page's own
  composition, FR-008).
- **Why Book With Us, Connect With Us, Contact, Social Responsibility**: these are ordinary
  editor-managed Pages using the generic `page.html` template, not dedicated templates —
  confirmed by there being no `templates/why-book-with-us.html` etc. in this repository. The
  FAQ pattern is therefore inserted as **page content** (an editor action, FR-010), not a
  template file edit, for these four placements. This is not a gap; it's what "editor-
  managed where practical" (PRD Scope Principle) already implies for ordinary Pages.
- **Archives** placement (PRD §4.3) is interpreted, per the spec's Assumptions, as the
  existing Tour Operator archive templates (`archive-accommodation.html`,
  `archive-destination.html`, `archive-tour.html`, `archive-team.html`, plus
  `archive-special.html` already covered above) — each gets the same treatment as Specials/
  Team.

**Alternatives considered**: A single global insertion via a `template_include` or
`the_content` filter (rejected — that is exactly the "welded" behaviour-in-theme pattern
`AGENTS.md` warns against, and it would remove editors' ability to omit the FAQ section on a
page-by-page basis, contradicting FR-002/User Story 1 Scenario 5).

---

## R-05 — What does `ls-plugin`'s schema layer need from this theme's markup? (informational only — not built here)

**Question**: FR-014/FR-015 require the FAQ pattern's markup to expose question/answer data
in a form `ls-plugin`'s (out-of-scope, separately planned) structured-data layer can consume
without a parallel content copy.

**Decision**: Recorded as a **contract**, not a build task, in
[contracts/faq-markup-contract.md](./contracts/faq-markup-contract.md) — a stable, minimal
DOM shape (heading text = question, panel content = answer, one `core/accordion-item` per
FAQ entry, no data duplicated into block attributes or post meta) that `ls-plugin` can select
against, whatever mechanism it ultimately uses (DOM parsing at render time, a shared
attribute convention, or something else its own plan decides). This theme repository commits
to the shape; it does not commit to how the plugin reads it.

**Alternatives considered**: Duplicating FAQ content into post meta or a custom field
specifically for schema consumption. Rejected — this is exactly the parallel,
separately-maintained copy FR-014 explicitly forbids, and it would make the theme own data
storage, which contradicts "Storage: N/A" in the Technical Context and the theme/plugin
split generally.
