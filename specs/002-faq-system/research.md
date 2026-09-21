# Phase 0 Research: FAQ System (theme scope)

## R-01 — Does an FAQ section with zero entries actually render nothing? 🟡 unverified

**Question**: FR-002 requires the FAQ pattern to render no heading, container, or empty
disclosure control when it holds no question/answer content. Does WordPress core's block
rendering already do this for an empty `core/accordion` wrapped in a heading + group, or
does the pattern need an explicit PHP conditional?

**Attempted measurement**: `wp eval` against the local install (`Local Sites/beta`) to run
`do_blocks()` on a group containing a heading and an empty `core/accordion`. **Blocked** —
the local MySQL server was not running/reachable from this planning session
(`mysqli_real_connect(): No such file or directory`). Not measured; not assumed.

**Decision**: Design `patterns/faq-section.php` so the empty-state behaviour does **not**
depend on an unverified core default. The pattern's container (a `core/group`) is placed
inside a PHP-level check: only emit the heading + `core/accordion` markup when the
associated content has at least one `core/accordion-item`. Concretely, this is done the way
`patterns/template-archive-special.php` and similar composition patterns already handle
optional content — a guarded conditional around the block markup, not a loop and not a
runtime data fetch (patterns don't do either, per `AGENTS.md`'s "no loops, no computed
markup" rule). Because a *pattern* is inserted once per page and then hand-edited, the
practical mechanism is: the **template part** (`parts/faq-section.html`) carries the
heading + empty accordion scaffold, and editors are instructed (and the quickstart check
verifies) to delete the whole FAQ pattern insertion from a page that has no FAQ content,
rather than leaving an empty one in place. This sidesteps needing a runtime "is this empty"
check inside a pattern file at all.

**Verification required before/during implementation** (recorded in quickstart.md): confirm
on the dev/local install, once reachable, whether an *authored-then-emptied* accordion (all
`core/accordion-item`s deleted, pattern instance left in place) renders visibly. If it does,
FR-002's guarantee shifts from "the pattern is self-hiding" to "the editorial convention is
to remove the pattern instance, not empty it" — and the general FAQ page's help text /
handover notes (FR-017) must say so explicitly.

**Alternatives considered**: A PHP `render_block` filter that hides the section
server-side regardless of editorial discipline. Rejected for now — it would be the kind of
"computed markup" this theme's patterns explicitly avoid, and belongs in `functions.php` at
most if R-01's verification shows editors reliably leave empty accordions behind. Deferred
unless the verification step proves the naive approach insufficient.

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
