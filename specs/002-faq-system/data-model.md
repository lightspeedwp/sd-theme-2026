# Phase 1 Data Model: FAQ System (theme scope)

This feature introduces **no database schema, post type, taxonomy, meta field, or option**.
Everything below describes block-markup entities, not persisted data structures — consistent
with the spec's Key Entities section and the Assumption that FAQ content is ordinary block
content, not a managed content library.

## FAQ pattern instance

One placement of `patterns/faq-section.php` on a page or template.

| Attribute | Representation | Notes |
|---|---|---|
| Placement | Implicit — which page/template the pattern was inserted on | Not stored; determined by where the pattern markup lives |
| Presence | Existence of the pattern insertion in the page's block markup | Absence = no FAQ section for that page (FR-002) |

No identity, no relationships, no lifecycle beyond ordinary post-content editing. There is
no central registry of "which pages have FAQs" — an editor finds out by opening the page.

## Question/answer pair

One `core/accordion-item` inside a FAQ pattern instance's `core/accordion`.

| Field | Block representation | Validation / constraints |
|---|---|---|
| Question | `core/accordion-heading`'s inner text (plain text or minimal inline formatting, via the heading toggle button) | Non-empty when the item exists; no length limit imposed by this feature (FR-005 requires long-content reflow to work, not a length cap) |
| Answer | `core/accordion-panel`'s inner blocks — one or more `core/paragraph`/`core/list`/etc. carrying rich text | Formatting (links, lists, emphasis) preserved per Edge Cases; authored markup must not break the panel's layout |
| Order | Position of the `core/accordion-item` among its siblings | Editor-controlled via ordinary block reordering (FR-010) |
| Expanded/collapsed state | Runtime only — `core/accordion`'s Interactivity API state (`isOpen`, `hidden="until-found"`) | Not authored or persisted; always starts collapsed on page load (User Story 1, Scenario 2) |

**State transitions**: collapsed → expanded → collapsed, client-side only, on
click/tap/keyboard activation (Enter/Space) of the accordion-heading toggle. No transition
is persisted server-side; every page load starts fully collapsed.

## General FAQ page

One page composed via `templates/page-faq.html` → `patterns/template-page-faq.php`.

| Region | Source |
|---|---|
| Header / page-title treatment | Existing site header template part + existing page-title pattern already used by other pages in this theme — reused unmodified (FR-008) |
| Introduction | Short editor-authored rich text region (FR-008) |
| FAQ section | Exactly one FAQ pattern instance (see above) |
| Enquiry CTA | Existing `patterns/cta-*.php` reference, reused unmodified (FR-008) |

No grouping/category field exists on this page — it is a single flat list, per FR-008's
explicit exclusion of grouped categories, custom navigation, and a hub design.

## Structured-data declaration (external — `ls-plugin`, referenced only)

Not modeled here. `ls-plugin` owns the shape of any FAQ/Specials/Team structured-data
object it emits; this theme's only obligation is the markup contract in
[contracts/faq-markup-contract.md](./contracts/faq-markup-contract.md).
