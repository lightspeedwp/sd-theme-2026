---
description: "Task list for FAQ System, Schema and AI Workflows (theme scope)"
---

# Tasks: FAQ System, Schema and AI Workflows (theme scope)

**Input**: Design documents from `specs/002-faq-system/`
**Prerequisites**: plan.md, spec.md, research.md, data-model.md, contracts/, quickstart.md

**Scope reminder**: This repository (`sd-theme-2026`) builds FR-001–FR-011 and
FR-016–FR-017 only. FR-012–FR-015 (AI/MCP configuration, schema-tag output) are built in
`ls-plugin` — resolved in spec.md Clarifications Q1/Q2. Tasks below touching those FRs are
verification/contract tasks, not implementation of that logic.

**Tests**: Not requested for this feature. Verification is via `quickstart.md`'s manual/
automated checks, not an automated test suite (this theme has none).

## Format: `[ID] [P?] [Story] Description`

---

## Phase 1: Setup

- [x] T001 **Done 2026-09-21.** The site is `beta.local` (Local by Flywheel, not the
      WordPress Studio install AGENTS.md's environment table describes — that reference is
      stale for this working copy) and is active once started from the Local app. `wp`
      still needed `-d mysqli.default_socket=<path>` pointed at Local's per-site socket
      (`~/Library/Application Support/Local/run/uJoM6kM55/mysql/mysqld.sock` for this
      site — `wp-config.php`'s `DB_HOST=localhost` makes PHP default to a socket path that
      doesn't match Local's). Pattern transient cache cleared successfully. The theme was
      **inactive** on this install (`twentytwentyfive` was active) — activated
      `sd-theme-2026` to test against it.

**Checkpoint**: Local environment ready and confirmed working.

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: The one shared pattern, template part and style variation every user story
depends on. **No placement or page work can start until this phase is complete.**

- [x] T002 [P] Create `styles/blocks/accordion/faq.json` — a block-style variation scoped to
      `core/accordion`/`core/accordion-item`/`core/accordion-heading`/`core/accordion-panel`,
      reusing the same numeric token references already used in
      `styles/blocks/accordion/call-us-dropdown.json` (no new visual decisions, per FR-007).
      **Do not** copy `call-us-dropdown.json`'s `.wp-block-accordion-panel[hidden]{display:
      none !important;}` override — research.md R-02 explains why that override is
      deliberately wrong for a FAQ (it breaks in-page find and `#hash`-link opening of a
      closed answer). Document this omission inline in the new file's own `description`
      field, matching this theme's convention of every accordion style file explaining its
      own deviations.
      **Done 2026-09-21.** JSON validated with `python3 -m json.tool`; not yet exercised in
      the editor (blocked on T001). `phpcs`/token-orphan checks not run — no `phpcs` binary
      available in this shell (see T031/T033).
- [x] T003 [P] Create `parts/faq-section.html` — **redesigned during implementation**: holds
      only the shared "Frequently asked questions" heading (`is-style-section-title`, `h2`).
      The accordion scaffold moved into the *pattern* instead (T004) — a template part is a
      single shared entity site-wide, so putting per-page-editable accordion content inside
      it would have silently reintroduced synced (shared) content, contradicting the Q3
      decision (non-synced pattern, template part for structure only). Registered in
      `theme.json`'s `templateParts` array (`"name": "faq-section"`, `"area":
      "uncategorized"`, matching `single-hero`'s precedent) so it's a real, addressable part
      rather than an orphaned file. **Done 2026-09-21.**
- [x] T004 Create `patterns/faq-section.php` — the one reusable FAQ pattern (FR-001),
      referencing `parts/faq-section.html` via `wp:template-part` for the heading, with its
      own `core/accordion` (one placeholder `core/accordion-item`, `is-style-faq`) as a
      sibling block inside the same wrapping `<section>` — this is the per-placement,
      independently-editable content Q3 calls for. Follows this theme's pattern conventions:
      inline `esc_html_e()` with the `sd-theme-2026` text domain, no top-level variables
      holding literals, no loops, no `phpcs:ignore`, `@package sd-theme-2026` docblock.
      Filed under the existing `sd-theme-2026/features` category (no new category added, per
      `functions.php`'s "keep this list tight" comment). **Done 2026-09-21.** `php -l`
      passed. **Caveat, recorded in research.md R-02**: the accordion markup was
      hand-authored against `wp-includes/blocks/accordion*` source (block.json + render
      callbacks) since the editor wasn't reachable to generate ground-truth serialized
      markup — everything except the heading-button's icon SVG (which `showIcon: true`
      likely adds and which core's render callbacks don't inject themselves) should be
      correct; the icon is the one thing to diff against the real editor output first.
      Depends on T002, T003. ✅
- [x] T005 **Done 2026-09-21.** Confirmed via
      `WP_Block_Patterns_Registry::get_instance()->get_registered('sd-theme-2026/faq-section')`
      — registered, title "FAQ Section", category `sd-theme-2026/features`. Also confirmed
      both new style variations register correctly via
      `WP_Theme_JSON_Resolver::get_style_variations('block')`: `faq` scoped to
      `core/accordion` and `faq-section` scoped to `core/group`.

**Checkpoint**: The shared FAQ pattern exists, registers, and carries the approved styling
— confirmed against a running site, not just the working tree.

---

## Phase 3: User Story 1 - A traveller finds an answer without submitting an enquiry (Priority: P1) 🎯 MVP

**Goal**: The shared FAQ pattern renders correctly — including rendering nothing when
empty — on every one of the nine approved placements.

**Independent Test**: quickstart.md steps 1 and 7 — load each placement as a visitor,
confirm collapsed-by-default questions, confirm empty pages show no FAQ section at all.

- [x] T006 [US1] **Done 2026-09-21.** Measured via `do_blocks()`: an emptied accordion
      (item removed, pattern instance left in place) does **not** render nothing — the
      heading and an empty accordion `<div>` both still render. Full detail and exact
      measured markup in research.md R-01.
- [x] T007 [US1] **Done 2026-09-21.** T006 confirmed the guard is needed. Implemented as a
      CSS-level fix, not a PHP conditional (patterns have no runtime logic once inserted —
      see research.md R-01 for why): added `className: "is-style-faq-section"` to the
      pattern's wrapping group (`patterns/faq-section.php`) and created
      `styles/sections/faq-section.json` with `"css": "&:has(.is-style-faq:empty){display:
      none !important;}"`. Verified by replicating WordPress core's own per-instance
      variation-CSS compiler directly — confirmed compiled output is
      `:root :where(.wp-block-group.is-style-faq-section--N:has(.is-style-faq:empty)){
      display:none !important;}`. Not yet visually confirmed in an actual browser
      (quickstart.md step 1 still needed for that final check, though `:has()` is
      baseline-supported in evergreen browsers).
- [ ] T008 [P] [US1] Insert the FAQ pattern reference in `templates/front-page.html`
      (Homepage — placement-contract.md row 1), positioned relative to the existing closing
      CTA per a live-site check (`https://www.southerndestinations.com/`), not a guess.
- [ ] T009 [P] [US1] Insert the FAQ pattern reference in
      `patterns/template-archive-accommodation.php` (Archives — placement-contract.md
      row 2), before the existing closing CTA.
- [ ] T010 [P] [US1] Insert the FAQ pattern reference in
      `patterns/template-archive-destination.php` (Archives — placement-contract.md row 2),
      before the existing closing CTA.
- [ ] T011 [P] [US1] Insert the FAQ pattern reference in
      `patterns/template-archive-tour.php` (Archives — placement-contract.md row 2), before
      the existing closing CTA.
- [ ] T012 [P] [US1] Insert the FAQ pattern reference in
      `patterns/template-single-destination.php` (Destination templates —
      placement-contract.md row 3), before the existing closing CTA/summary band. Confirm
      during this task whether "Destination templates" also covers
      `patterns/template-single-country.php` / `patterns/template-single-region.php`
      (open note in placement-contract.md row 3) and insert there too if so.
- [ ] T013 [P] [US1] Insert the FAQ pattern reference in
      `patterns/template-archive-team.php` **and** `patterns/template-single-team.php`
      (Team — placement-contract.md row 6, both archive and single per the resolved reading
      of PRD §4.3), before each template's existing closing CTA/summary band.
- [ ] T014 [P] [US1] Insert the FAQ pattern reference in
      `patterns/template-archive-special.php` (Specials — placement-contract.md row 8),
      before the existing `cta-like-what-you-see` CTA and the "Why choose Southern
      Destinations" panel, matching the page furniture documented in
      `specs/001-specials-templates/spec.md`.
- [ ] T015 [US1] Confirm `patterns/faq-section.php` is available in the block inserter for
      manual insertion on the four Page-based placements with no dedicated template — Why
      Book With Us, Connect With Us, Contact, Social Responsibility (placement-contract.md
      rows 4, 5, 7, 9). No template file is edited for these; this task is a verification
      + note for handover (FR-017), not a code change.
- [ ] T016 [US1] Run quickstart.md step 7 (placement coverage) across all nine placements
      plus the general FAQ page (once T018–T019 in Phase 4 exist) and record any deviation
      from the live site's established CTA ordering.

**Checkpoint**: User Story 1 is independently functional and testable — every placement
either shows the FAQ pattern correctly or shows nothing at all.

---

## Phase 4: User Story 2 - An editor adds or updates FAQ content on any approved page (Priority: P1)

**Goal**: Editors can manage FAQ content with standard block-editor controls on any
approved page, and the general FAQ page exists per FR-008/FR-009.

**Independent Test**: quickstart.md step 2 (add/edit/reorder/remove) and step 8 (general
FAQ page structure).

- [ ] T017 [US2] Create `templates/page-faq.html` — a thin shell following the exact
      convention of `templates/archive-special.html` (header template part → one
      `wp:pattern` reference → footer template part), delegating to a new composition
      pattern (T018).
- [ ] T018 [US2] Create `patterns/template-page-faq.php` — the general FAQ page
      composition (FR-008): existing site header/page-title treatment, a short
      editor-authored introduction region, exactly one instance of
      `patterns/faq-section.php`, and a reference to the theme's existing enquiry CTA
      pattern (`patterns/cta-*.php` — select the one already used for comparable
      standalone pages, not a new CTA variant). Exactly one `<main>` landmark, correct
      heading hierarchy (FR-009). Follows the same PHP conventions as T004. Depends on T004.
- [ ] T019 [US2] Run quickstart.md step 2 on a placement from Phase 3: insert
      `patterns/faq-section.php` fresh, add two question/answer pairs, save and confirm the
      front end; reorder them and confirm; remove one and confirm one remains; remove the
      last and confirm the section disappears (ties back to T006/T007).
- [ ] T020 [US2] Confirm the empty-FAQ editing experience (FR-011): with the last
      question/answer pair removed in the editor, verify the block tree still reads as
      clearly editable (not broken or confusing) even though nothing renders on the front
      end. Adjust `parts/faq-section.html`'s editor-only affordances if needed (e.g. a
      placeholder visible only in the editor context, if core doesn't already provide one).
- [ ] T021 [US2] Run quickstart.md step 8 against `templates/page-faq.html`: confirm one
      `<main>` landmark, confirm the composition order (header → intro → FAQ → CTA), confirm
      no grouped categories/custom navigation/hub design are present.

**Checkpoint**: User Stories 1 and 2 both work independently. The general FAQ page exists
and the editorial workflow is confirmed on at least one other placement.

---

## Phase 5: User Story 3 - Every FAQ section is accessible by keyboard and at all viewport widths (Priority: P1)

**Goal**: Keyboard, focus, screen-reader and responsive behaviour meet FR-004/FR-005 and
the PRD's accessibility non-negotiables, on the actual built pattern (not assumed from
core's defaults).

**Independent Test**: quickstart.md steps 3, 4 and 5.

- [ ] T022 [US3] Run quickstart.md step 3 (keyboard and focus) against at least two
      placements from Phase 3 (e.g. Homepage and the general FAQ page): tab through, confirm
      visible focus order, confirm Enter/Space toggles exactly as click does, confirm no
      hover-only interaction is possible.
- [ ] T023 [US3] Run quickstart.md step 4 (responsive reflow) at 320–375px viewport width on
      the same two placements: confirm single-column reflow, zero horizontal scroll, every
      question comfortably tappable.
- [ ] T024 [US3] Run quickstart.md step 5 (automated accessibility scan, e.g. axe/Lighthouse)
      against a page carrying the FAQ section. Fix any critical/serious violation
      attributable to `styles/blocks/accordion/faq.json` or `parts/faq-section.html`
      directly (do not touch unrelated page regions the scan also flags).
- [ ] T025 [US3] Spot-check with a screen reader (or the browser's accessibility tree
      inspector) that the accordion panel's expanded/collapsed state is exposed
      programmatically, not only visually, confirming core's native `aria-expanded`/`hidden`
      wiring survived the `faq.json` styling unchanged.

**Checkpoint**: All three P1 user stories are independently functional. This is the MVP —
see Implementation Strategy below.

---

## Phase 6: User Story 4 - FAQ content is authored as a draft and reviewed by a human before publishing (Priority: P2)

**Goal**: Confirm this theme's content model imposes no obstacle to the `ls-plugin`-side
AI-drafting workflow, without building any part of that workflow here (FR-012 is a
dependency on `ls-plugin`, not built in this repo — spec Clarifications Q1).

**Independent Test**: A draft FAQ entry produced by WordPress AI Engine (in `ls-plugin`)
saves into a `core/accordion-item` inside `patterns/faq-section.php` with no structural
difference from a hand-authored one, and is not visible on the front end until published.

- [ ] T026 [US4] Cross-repo check (no theme code change expected): confirm with the
      `ls-plugin` side of LS-4215 that draft AI-generated content is written as ordinary
      `core/accordion-item` block markup into an existing FAQ pattern instance — the same
      shape `contracts/faq-markup-contract.md` describes for *published* content — so no
      separate review surface or theme-side accommodation is needed. Record the outcome in
      `contracts/faq-markup-contract.md`'s "Draft/AI-generated FAQ content" note if anything
      changes.
- [ ] T027 [US4] Confirm (via the ordinary WordPress draft/publish post-status mechanism —
      no new code) that a draft `core/accordion-item` inside a published page's FAQ pattern
      is not rendered to visitors until the containing revision is published. This is
      expected to be inherent to how WordPress handles unpublished block content, not a
      theme-built behaviour — verify rather than assume.

---

## Phase 7: User Story 5 - Structured data matches what visitors actually see, with no duplication (Priority: P2)

**Goal**: Confirm this theme introduces no competing or duplicate structured-data source,
and hand off a verified markup contract to `ls-plugin` (FR-014/FR-015 are built in
`ls-plugin`, not here — spec Clarifications Q1).

**Independent Test**: `contracts/faq-markup-contract.md` accurately describes the actual
rendered DOM, and no theme file emits JSON-LD, microdata, or any other structured-data
markup for FAQ content.

- [ ] T028 [US5] Inspect the actual rendered markup of `patterns/faq-section.php` (once
      built in Phase 2) against `contracts/faq-markup-contract.md`'s documented shape;
      correct the contract file if core's real output differs from what was drafted during
      planning (class names, nesting, `hidden` attribute value).
- [ ] T029 [US5] Grep `patterns/faq-section.php` and `parts/faq-section.html` to confirm
      neither echoes JSON-LD, a `<script type="application/ld+json">` block, or any other
      structured-data markup — this theme must not introduce a second FAQ schema source
      alongside whatever `ls-plugin` builds (FR-015).
- [ ] T030 [US5] Hand off `contracts/faq-markup-contract.md` (as corrected in T028) to
      whoever plans the `ls-plugin` side of LS-4215 — no further action in this repository.

---

## Phase 8: Polish & Cross-Cutting Concerns

- [ ] T031 [P] Run `phpcs --standard=.phpcs.xml.dist patterns/ parts/ templates/` and fix
      any violation in the new/changed files.
- [ ] T032 [P] Run `find patterns parts -name '*.php' -exec php -l {} \;` and fix any syntax
      error.
- [ ] T033 Run the `theme-orphaned-refs` skill after all style/pattern changes; target zero
      orphaned token references.
- [ ] T034 Run quickstart.md step 9 (regression check) against every modified template/
      pattern from Phase 3–4, comparing against each file's pre-feature `git diff` to confirm
      no unrelated layout, spacing, or CTA-position change was introduced.
- [ ] T035 Draft handover/training notes covering FAQ management, the draft/publish
      distinction for AI-assisted content, sensitive-topic handling, and escalation
      (FR-017, PRD §4.8) — coordinate with the `ls-plugin`-side owner for the AI/MCP
      portions this repo does not build.
- [ ] T036 Add a `CHANGELOG.md` entry tagged LS-4215, per this theme's `AGENTS.md` working
      agreement and Keep a Changelog 1.1.0 format.

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies.
- **Foundational (Phase 2)**: Depends on Setup. **Blocks every user story** — the pattern,
  part and style variation in T002–T005 are shared by all of them.
- **User Story 1 (Phase 3)**: Depends on Phase 2. No dependency on US2/US3.
- **User Story 2 (Phase 4)**: Depends on Phase 2. T016 (in Phase 3) references the general
  FAQ page built in T017–T018, so run Phase 4's T017–T018 before Phase 3's T016 specifically
  — everything else in Phase 3 is independent of Phase 4.
- **User Story 3 (Phase 5)**: Depends on Phase 2 and at least one placement existing from
  Phase 3 (T022–T025 need a live placement to test against).
- **User Story 4 (Phase 6)** and **User Story 5 (Phase 7)**: Depend on Phase 2 (need the
  built pattern to inspect/verify against). Independent of each other and of Phases 3–5,
  though most useful once at least one placement exists.
- **Polish (Phase 8)**: Depends on all preceding phases whose work it audits.

### Parallel Opportunities

- T002 and T003 (Phase 2) — different files, no shared dependency.
- T008–T014 (Phase 3 placements) — each touches a different template/pattern file; all
  parallelizable once T004/T005 (the pattern itself) exist.
- T031 and T032 (Phase 8) — independent checks.

---

## Parallel Example: Phase 3 placements

```bash
Task: "Insert FAQ pattern reference in templates/front-page.html"
Task: "Insert FAQ pattern reference in patterns/template-archive-accommodation.php"
Task: "Insert FAQ pattern reference in patterns/template-archive-destination.php"
Task: "Insert FAQ pattern reference in patterns/template-archive-tour.php"
Task: "Insert FAQ pattern reference in patterns/template-single-destination.php"
Task: "Insert FAQ pattern reference in patterns/template-archive-team.php + template-single-team.php"
Task: "Insert FAQ pattern reference in patterns/template-archive-special.php"
```

---

## Implementation Strategy

### MVP First (User Stories 1–3, all P1)

1. Phase 1: Setup (T001)
2. Phase 2: Foundational (T002–T005) — **critical, blocks everything**
3. Phase 3: User Story 1 (T006–T016) — placements + empty-state
4. Phase 4: User Story 2 (T017–T021) — editorial workflow + general FAQ page
5. Phase 5: User Story 3 (T022–T025) — accessibility/responsive verification
6. **STOP and VALIDATE**: quickstart.md steps 1–5, 7, 8 all pass. This is the deliverable
   PRD §4.2–§4.4 describes and is independently demoable.

### Then (P2, dependency verification only — no new build)

7. Phase 6: User Story 4 (T026–T027) — confirm no theme-side obstacle to `ls-plugin`'s
   AI-drafting workflow.
8. Phase 7: User Story 5 (T028–T030) — finalize and hand off the markup contract.

### Always last

9. Phase 8: Polish (T031–T036) — lint, orphan-ref check, regression pass, handover notes,
   changelog.

---

## Notes

- [P] tasks touch different files with no dependency on an incomplete task.
- [US#] maps each task to its spec.md user story for traceability.
- No automated test suite exists in this theme; "tests" above are the manual/scripted
  checks in `quickstart.md`.
- T006/T007 are the one task pair whose outcome was genuinely unknown at planning time
  (research.md R-01) — do these early in Phase 3, not last, since T007's necessity depends
  on T006's result.
