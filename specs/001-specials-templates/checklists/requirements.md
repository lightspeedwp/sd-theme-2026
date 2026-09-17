# Specification Quality Checklist: Specials Landing Page

**Purpose**: Validate specification completeness and quality before proceeding to planning
**Created**: 2026-09-17
**Feature**: [spec.md](../spec.md)

## Content Quality

- [x] No implementation details (languages, frameworks, APIs)
- [x] Focused on user value and business needs
- [x] Written for non-technical stakeholders
- [x] All mandatory sections completed

## Requirement Completeness

- [x] No [NEEDS CLARIFICATION] markers remain
- [x] Requirements are testable and unambiguous
- [x] Success criteria are measurable
- [x] Success criteria are technology-agnostic (no implementation details)
- [x] All acceptance scenarios are defined
- [x] Edge cases are identified
- [x] Scope is clearly bounded
- [x] Dependencies and assumptions identified

## Feature Readiness

- [x] All functional requirements have clear acceptance criteria
- [x] User scenarios cover primary flows
- [x] Feature meets measurable outcomes defined in Success Criteria
- [x] No implementation details leak into specification

## Notes

**Iteration 2 (2026-09-17) — all items pass. Ready for `/speckit-plan`.**

### What changed since iteration 1

Both clarifications were answered — **Q1: B** (individual offer pages stay suppressed and
redirect to the landing page) and **Q2: A** (expired offers are simply absent) — together
with a standing instruction that **behaviour matches the live site**. The spec was
re-grounded against a live fetch of `/specials/` rather than patched, because the answers
changed its shape:

- **User Story 2 was withdrawn.** There is no individual offer page to read, so "browse"
  and "read in full" are one journey. Its content moved into User Story 1, and the offer
  detail moved onto the landing page where live puts it.
- **Two user stories were added.** US-2 now covers the meta row and the route to the
  connected product; US-4 covers the redirect and the sitemap — the form in which the line
  item's "single" half is delivered.
- **Requirements grew from 24 to 32** and are now written against measured live markup
  rather than inferred structure.

### Three corrections the live fetch forced

1. **F-03 is stale.** It recorded individual offer URLs returning HTTP 200 rendering the
   archive. They now return **301 → `/specials/`**. Option B's redirect is therefore
   existing behaviour to preserve, not new behaviour to build. Only the sitemap remains.
2. **Four offers per page is retained.** Iteration 1 argued against live's hard limit
   (FR-002, SC-009 then). "Match live" overrides that — FR-005 now specifies four, and the
   objection is withdrawn.
3. **The layout is a stacked full-width list, not a card grid,** and the description is the
   offer's **complete body copy**, not an excerpt. The scaffolding stub in
   `templates/archive-special.html` is a 3-up grid of excerpt cards — wrong on both counts,
   and it will be replaced rather than extended.

### Standing exceptions

- **No implementation details** — passes with the same deliberate exception as iteration 1.
  Platform and plugin names appear in **Dependencies**, **Assumptions** and **Live
  behaviour, measured** only, where they identify what already exists and what the feature
  may not assume (offer fields are not readable over the public data API; headings cannot
  carry bound offer data). Requirements and Success Criteria stay technology-agnostic.

- **Scope boundary** — the Overview carries an out-of-scope table mapping the enquiry form,
  the enquiry and product modals, faceted filtering, structured data and content migration
  to the line items that fund them. Verified against Estimate 3164 line 11 ("Specials
  archive/single, valid/expired filtering, CTAs", R10,800).

### Iteration 3 (2026-09-17) — two rulings, nothing outstanding

Both open threads from iteration 2 are closed. Nothing now goes to the Change-Control
Register from this feature.

1. **The redirect is Tour Operator's, not ours.** Its "Disable Single" setting produces the
   301 on its own. FR-021/FR-022 now require the setting to be *enabled and verified* and
   explicitly forbid authoring a redirect rule, a redirect-map entry or template-level
   handling. User Story 4 is reframed as verification rather than construction, and SC-005
   now asserts **zero** redirect rules authored anywhere.

2. **The unrendered offer fields stay unrendered.** `price`, `price_type`, `duration`,
   `booking_validity_start`, `booking_validity_end`, `tagline`, `terms_conditions` and
   `gallery` are populated on every offer and surface nowhere on live. Ruled: match live.
   **Risk R-1 is closed as a decision, not carried as a risk** — it is not a register item,
   not a deferred improvement, and not a caveat to restate at planning or review. It stays
   in the document only so a later reader knows it was chosen rather than missed.

The one item genuinely outside this feature is the **sitemap** (FR-023). Live still
advertises all five individual offer URLs despite the 301 — verified 2026-09-17. That is a
search-plugin configuration change owned by the deployment, redirects and launch-support
line. This feature records the requirement and verifies it at hand-over; it does not
implement it.

**Branch**: re-cut from `develop` once the Spec Kit scaffolding chore was merged (PR #24),
so the feature branch carries only this feature's work.
