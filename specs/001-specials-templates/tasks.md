---

description: "Task list template for feature implementation"
---

# Tasks: Specials Landing Page

**Input**: Design documents from `specs/001-specials-templates/` (spec.md, plan.md, research.md,
data-model.md, contracts/, quickstart.md)

**Prerequisites**: plan.md, spec.md, research.md, data-model.md, contracts/, quickstart.md — all present

**Tests**: Not explicitly requested and there is no PHPUnit suite in this theme (plan.md,
Technical Context). Verification is manual/automated against the development site per
quickstart.md's twelve checks; those checks are included below as tasks so nothing is skipped.

**Organization**: Tasks are grouped by user story (spec.md) to enable independent implementation
and testing of each story. **This feature spans two repositories** — every task is tagged
`[THEME]` (`wp-content/themes/sd-theme-2026/`, branch `feature/ls-2021-specials`) or `[PLUGIN]`
(`wp-content/plugins/sd-enhancements-2026/`, same branch name, separate git history). A task with
neither tag is a defect in this list. Per the working agreements, **nothing is committed** —
work is left staged/edited in each repo's working tree for review.

## Format: `[ID] [P?] [Story] [THEME|PLUGIN] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (US1–US5), omitted for Setup, Foundational
  and Polish tasks
- Include exact file paths in descriptions

## Path Conventions

- `[THEME]` paths are relative to `wp-content/themes/sd-theme-2026/`
- `[PLUGIN]` paths are relative to `wp-content/plugins/sd-enhancements-2026/`
- `cd` into the repo you are editing before running any git command — a command run from the
  wrong root silently no-ops or errors rather than crossing repos

---

## Phase 1: Setup

**Purpose**: Confirm both working trees are in the expected state before any file changes

- [ ] T001 Confirm both repos are checked out on `feature/ls-2021-specials` from `develop`: theme
  root `wp-content/themes/sd-theme-2026/` and plugin root
  `wp-content/plugins/sd-enhancements-2026/` — `git status` in each, no unexpected changes
- [ ] T002 [P] [THEME] Clear the pattern and style transient cache so new/changed pattern and
  style files register: `php -d memory_limit=1024M $(which wp) transient delete --all --network`
  from `wp-content/themes/sd-theme-2026/`
- [ ] T003 [P] [THEME] Run Gate 0 baseline per quickstart.md: `phpcs --standard=.phpcs.xml.dist .`,
  `find patterns templates parts -name '*.php' -exec php -l {} \;`,
  `php -d memory_limit=1024M $(which wp) theme list --status=active` — confirm clean output and
  no fatals in `wp-content/debug.log` before any edit, as a baseline to compare against later

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Decisions and plugin-side data-layer work that every user story's band and page
furniture depend on

**⚠️ CRITICAL**: No band or template markup should be authored until this phase is complete —
plan.md's implementation sequence (steps 1–5) puts all of this before "author the band" (step 6)

- [ ] T004 [P] [THEME] Enumerate every `#special-` anchor referenced in the theme —
  `grep -rn '#special-' patterns/ parts/ templates/` in `wp-content/themes/sd-theme-2026/` —
  plus the migrated navigation on dev, and record the resulting slug list as the SC-008 test set
  (research R-10, `contracts/anchor-contract.md`)
- [ ] T005 [P] Settle the enquiry-trigger identifier with the line 15/16 owner: whether the
  per-band attribute emitted on the enquire button carries the offer's **name**, **slug**, or
  **post ID** (`contracts/enquiry-trigger-contract.md`). Record the decision — it changes both
  the `[THEME]` band markup (User Story 3) and, if the identifier needs server-side reading, the
  `[PLUGIN]` module `modules/enquiry.php`. Must be settled before either is touched.
- [ ] T006 Verify `lsx/post-connection`'s omission behaviour against real data on dev per
  quickstart Checks 3–4: an offer with no connections of a kind omits that label and its
  separator entirely, and a connection pointing at an unpublished/deleted item is omitted
  silently. If it does **not** omit cleanly, add the omission logic to
  `wp-content/plugins/sd-enhancements-2026/modules/queries.php` **before** the band pattern
  (User Story 1/2) is authored — this changes the shape of the band if it fails
- [ ] T007 [PLUGIN] Extend `SD\Enhancements\Queries::limit_specials_archive()` in
  `wp-content/plugins/sd-enhancements-2026/modules/queries.php` (currently only sets
  `posts_per_page` to `SPECIALS_PER_PAGE` at line ~186): add ordering by `booking_validity_start`
  ascending (`meta_key` + `orderby: meta_value_num`, with a defined position for offers that have
  no start date) and exclude offers whose `booking_validity_end` has passed or whose
  `booking_validity_start` is in the future. Both dates are CMB2 `text_date_timestamp` meta, so
  comparisons are numeric; pair every meta comparison with a `NOT EXISTS` branch so an offer with
  **no** meta row for a bound is never excluded — an absent bound means open-ended (FR-007,
  FR-008, spec edge cases; `contracts/query-contract.md`)
- [ ] T008 [PLUGIN] Verify T007 on dev per quickstart Check 1, counted against the data rather
  than by eye (`wp eval-file`, remembering a top-level variable in an eval-file script is not a
  global — use `$GLOBALS`): exactly 4 offers on page 1, pagination reaches every valid offer,
  **zero** expired offers anywhere in the set, offers ordered by `booking_validity_start`, an
  offer with no end date still listed, an offer with a future start date absent (SC-001)

**Checkpoint**: The query is provably correct independent of any markup or of retirement
(FR-026a does not exist yet) — every user story phase below can now proceed

---

## Phase 3: User Story 1 - A traveller browses and reads current offers (Priority: P1) 🎯 MVP

**Goal**: Rebuild `/specials/` as a paginated list of four full-width offer bands, each showing
the offer's banner image as its ground, its centred name, its complete description and an
enquire button — closed by the existing CTAs — matching live's structure exactly.

**Independent Test**: Load the Specials page on dev. Confirm four offers per page, each
rendering banner image, name, full description and enquire button; confirm pagination reaches
the last offer; confirm expired offers are absent; confirm an empty-state message when no valid
offers exist.

### Implementation for User Story 1

- [ ] T009 [US1] [THEME] Rewrite `wp-content/themes/sd-theme-2026/templates/archive-special.html`
  into a four-line shell — header template part, delegate to the new
  `patterns/template-archive-special.php`, footer template part — matching the convention already
  used in `templates/archive-tour.html`. Remove the `<main>` landmark and the whole 3-column
  scaffolding grid from this file; the landmark moves into the pattern (Constitution: exactly one
  `<main>`; research R-01)
- [ ] T010 [US1] [THEME] Create `wp-content/themes/sd-theme-2026/patterns/template-archive-special.php`
  with the single `<main>` landmark and the page banner: image, title and introduction (~40–50
  words) all sourced through the `sd/to-setting` binding against Tour Operator's settings
  registry, not hardcoded (FR-001, FR-002, FR-003; research R-09). Follow core's pattern form:
  inline `esc_html_e()`/`esc_html_x()` with the `sd-theme-2026` text domain, no top-level
  variables holding literals, no loops, no `phpcs:ignore`, `@package sd-theme-2026` in the header
  docblock
- [ ] T011 [US1] [THEME] In `patterns/template-archive-special.php`, add a `core/query` block
  with exactly `"query":{"inherit":true,"postType":"special"}` — no `perPage`, `order`, `orderBy`
  or meta query attributes — so `limit_specials_archive()` (T007) is the only thing shaping the
  list (`contracts/query-contract.md`)
- [ ] T012 [US1] [THEME] In `patterns/template-archive-special.php`, build the offer band inside
  `post-template` as an image + absolutely-positioned body — not `core/cover` — using the exact
  class names `.special-card__media` and `.special-card__body` from
  `styles/sections/cards/special-card.json` so the existing style applies (research R-03). Use
  `core/image` bound to `lsx/post-meta` → `banner_image_id` for the band ground, filling the
  band's height (FR-009), with a legible fallback treatment for an offer with no banner image
  (spec edge case) — not a transparent or collapsed band
- [ ] T013 [US1] [THEME] In the band from T012, add centred `core/post-title` for the offer name
  (FR-010) — **not** a binding; `lsx/post-meta` supports only `core/image`, `core/cover` and
  `core/paragraph` (Assumption 5, research R-04) — an empty meta-row placeholder group (populated
  in User Story 2), and `core/post-content` for the offer's **complete** body copy with authored
  formatting (lists, links, emphasis) preserved — no excerpt, no truncation (FR-012)
- [ ] T014 [US1] [THEME] In the band from T012, add the enquire `core/button` reading "Enquire
  about this special" with `url="#to-modal-enquiry"` (FR-018), inline `esc_html_e()` with the
  `sd-theme-2026` text domain — the per-band identifier attribute is added in User Story 3, not
  here
- [ ] T015 [US1] [THEME] In `patterns/template-archive-special.php`, add `core/query-pagination`
  and a `core/query-no-results` block carrying a clear empty-state message, verifying the banner,
  title and introduction from T010 still render above it when the message shows (FR-013)
- [ ] T016 [P] [US1] [THEME] Extend `wp-content/themes/sd-theme-2026/styles/sections/cards/special-card.json`
  only if the no-banner-image fallback (T012) or the phone-width reflow (T018) genuinely need a
  rule the existing `.special-card__media`/`.special-card__body` `css` field cannot express —
  reuse first. Do **not** add any use of `.special-card__badge`; no promotion badge exists on
  live and none is built here (spec correction 2, research R-03)
- [ ] T017 [US1] [THEME] Below the query in `patterns/template-archive-special.php`, confirm which
  of `patterns/cta-inspired-by-this-property.php`, `patterns/cta-not-sure-where-to-go.php` or
  `patterns/cta-tell-us-your-trip-ideas.php` carries this page's live "Like what you see? Let's
  start planning!" wording, insert that one, then `patterns/why-choose-sd.php` (FR-020) — no
  fourth CTA pattern is authored
- [ ] T018 [US1] [THEME] Confirm in `patterns/template-archive-special.php` that the band reflows
  to a single column at phone widths with no horizontal scrolling of the page body and every
  control reachable by touch (FR-028), a very long offer name wraps within the band rather than
  overflowing it, and a very long description grows the band rather than clipping, scrolling
  internally, or pushing the enquire button out of view (spec edge cases)
- [ ] T019 [US1] Verify quickstart Check 2 on dev for **every** migrated offer, not a sample:
  banner image fills the band, name is centred, description matches `post_content` exactly (no
  ellipsis, no "read more"), enquire button present, band is styled (unstyled markup means the
  class names in T012 do not match `special-card.json`) (SC-003)

**Checkpoint**: The page is live and correct for a valid dataset, an empty dataset, and phone
widths — User Story 1 is independently demoable

---

## Phase 4: User Story 2 - A traveller follows an offer to the product behind it (Priority: P2)

**Goal**: The meta row names connected accommodation, destinations and travel style, each
labelled and linking to the right place, with empty labels and dead links never shown.

**Independent Test**: On an offer with connections, activate each meta link and confirm the
correct destination. On an offer with no connections, confirm no meta row renders at all. Force
an unpublished connection and confirm it is omitted silently.

### Implementation for User Story 2

- [ ] T020 [US2] [THEME] In the meta-row placeholder from T013, add `core/paragraph` bound to
  `lsx/post-connection` → `accommodation_to_special`, labelled `Accommodation:` via the
  `prefix`/`prefixBold` block attributes (precedent: `patterns/card-tour-compact.php`) (FR-014)
- [ ] T021 [US2] [THEME] In the same meta row, add `core/paragraph` bound to
  `lsx/post-connection` → `destination_to_special`, labelled `Destinations:` the same way
  (FR-014)
- [ ] T022 [US2] [THEME] In the same meta row, add `core/post-terms {"term":"travel-style"}`
  labelled `Travel Style:`, linking to that taxonomy term's archive (FR-014, FR-015)
- [ ] T023 [US2] Verify quickstart Check 3 on dev across the full migrated set: labels present
  only where connections exist, **zero** empty labels or orphaned separators, an offer with no
  connections at all renders no meta row (FR-016), and — after deliberately unpublishing one
  connected accommodation — that connection is omitted silently with no dead link and no empty
  list item (FR-017, SC-004)
- [ ] T024 [US2] Verify quickstart Check 4 on dev: activate each meta link on an offer with
  connections — accommodation and destination names open that item's overlay (or degrade to its
  permalink if the line-16 overlay is not yet built; record as deferred verification, not a
  pass), and the travel-style link opens that term's archive (FR-015)

**Checkpoint**: User Stories 1 and 2 both work independently — every rendered element and every
meta-row rule holds against real data

---

## Phase 5: User Story 3 - A traveller enquires about a specific offer (Priority: P2)

**Goal**: Each band's enquire button opens the shared enquiry surface already knowing which
offer it is about, correctly, on every band on the page.

**Independent Test**: Activate the enquire button on two different offers on the same page and
confirm the enquiry surface opens each time carrying that offer's own name — not the first one,
not the last rendered. Confirm the button is reachable and operable by keyboard alone.

### Implementation for User Story 3

- [ ] T025 [US3] [THEME] Using the identifier decided in T005, add the per-band attribute to the
  enquire button from T014 in `patterns/template-archive-special.php`, rendered from the queried
  post inside `post-template` so pagination and multiple bands on one page each carry their own
  value — a single page-level value fails SC-007 by construction
  (`contracts/enquiry-trigger-contract.md`)
- [ ] T026 [US3] [PLUGIN] Only if T005's identifier needs server-side reading: extend
  `SD\Enhancements\Enquiry::register_trigger_modal()` in
  `wp-content/plugins/sd-enhancements-2026/modules/enquiry.php` (currently matches
  `#to-modal-{slug}` and returns the button unchanged — lines ~96–137) to read the per-band
  identifier from the matched trigger and carry it to the form. Skip this task if T005 settles on
  a client-side-only identifier.
- [ ] T027 [US3] Verify quickstart Check 5 on dev, not a spot check: on a page showing four
  offers, activate **each** band's button in turn and confirm the enquiry surface opens each time
  identifying that offer by name, and that the button is reachable and operable by keyboard alone
  with a visible focus indicator (SC-007, FR-018, FR-029)

**Checkpoint**: Every enquire button on the page is independently correct

---

## Phase 6: User Story 4 - Someone follows an old link to an individual offer (Priority: P2)

**Goal**: `/special/{slug}/` returns a single-hop permanent redirect to `/specials/` for
published and retired offers alike, with no redirect rule authored in the theme, and every
`/specials/#special-{slug}` anchor from elsewhere in the site still resolves to the right band.

**Independent Test**: Request each individual offer URL on the rebuilt site and confirm a
single-hop permanent redirect. Follow every enumerated anchor link and confirm it lands on the
correct band on the page it is actually on.

### Implementation for User Story 4

- [ ] T028 [US4] [PLUGIN] Create `wp-content/plugins/sd-enhancements-2026/modules/specials.php`
  with a `template_redirect` that catches a front-end request resolving to a single `special`
  (published **and** draft/expired) and issues a single-hop `301` to the specials archive URL
  derived from the post type's own archive link — never hardcoded — leaving wp-admin, previews,
  REST and feeds unaffected (FR-022, `contracts/redirect-contract.md`, research R-07)
- [ ] T029 [US4] [PLUGIN] Leave `lsx_to_settings['special_disable_single']` off. Do **not** enable
  it: it is inert for `special` (the filter it is read on never fires for this post type) and
  where it does apply it soft-404s to the homepage at 200, which SC-005 forbids (FR-021, research
  R-07)
- [ ] T030 [P] [US4] [THEME] Delete `wp-content/themes/sd-theme-2026/templates/single-special.html`
  (FR-024)
- [ ] T031 [US4] [PLUGIN] Add a render-time filter in
  `wp-content/plugins/sd-enhancements-2026/modules/specials.php` (may fold into an existing
  module at task time) that injects `id="special-{post_name}"` — unmodified, not re-slugged — onto
  each offer band's outer group as it renders inside the specials archive's `post-template`,
  because the anchor is per-post and cannot be an authored `anchor` attribute in a pattern that
  renders once (FR-011, `contracts/anchor-contract.md`, research R-10). Use the anchor list from
  T004 as the verification set.
- [ ] T032 [US4] Verify quickstart Check 6 on dev: follow every anchor from the T004 list plus the
  migrated navigation and confirm each lands on the correct band on the page it is actually on
  (an anchor for an offer on page 2 does not resolve from page 1) — **zero** broken anchors
  (SC-008)
- [ ] T033 [US4] Verify quickstart Check 7 on dev: for every offer slug
  (`wp post list --post_type=special --post_status=any --field=post_name`), confirm `/special/{slug}/`
  returns `301` to the specials archive with exactly one hop and zero URLs returning `200` with
  different content; draft/expired offers redirect too; an editor can still open and preview an
  offer in wp-admin; and `grep -rn 'template_redirect\|wp_redirect' patterns/ inc/ functions.php`
  in the theme returns nothing (SC-005)

**Checkpoint**: Old links and in-page anchors both resolve correctly; the theme carries no
redirect logic

---

## Phase 7: User Story 5 - An editor publishes and retires an offer (Priority: P3)

**Goal**: An offer leaves the page automatically when its validity window closes (already true
from Phase 2), and is additionally retired to `draft` on a schedule so the editor's own list
matches what a visitor can find — reversibly, without touching content or connections.

**Independent Test**: Create one offer with a closed validity window and one with an open one;
confirm only the open one appears, and that the closed one is `draft` after a scheduled cycle.
Change the banner and introduction through the site settings and confirm the change renders.

### Implementation for User Story 5

- [ ] T034 [US5] [PLUGIN] Add a scheduled sweep to
  `wp-content/plugins/sd-enhancements-2026/modules/specials.php` (or fold into `queries.php`,
  decided at task time) that queries offers whose `booking_validity_end` has passed and sets
  `post_status` to `draft`, leaving description, connections, taxonomies, validity dates, and
  featured/banner images unchanged. Use `wp_next_scheduled()` / `wp_schedule_event()` — a single
  recurring sweep, not one scheduled action per offer — following the precedent in
  `modules/trustpilot.php` (`CRON_HOOK`, lines ~296–297), since Action Scheduler is not available
  on this install (FR-026a, `contracts/retirement-contract.md`, research R-06)
- [ ] T035 [US5] [PLUGIN] In the sweep from T034: make retiring an already-`draft` offer a no-op
  (idempotent), reschedule cleanly whenever an offer's `booking_validity_end` changes so a stale
  scheduled retirement never fires against the new date, never touch an offer with no end date
  (never retired), and retire **every** offer that expired during a scheduler gap on the next
  run — not just the most recent (`contracts/retirement-contract.md`)
- [ ] T036 [US5] [PLUGIN] Confirm no hook is added to `tour-operator` and `Post_Expiration` is not
  revived — `git -C wp-content/plugins/tour-operator status --short` shows no changes (FR-026b)
- [ ] T037 [US5] Verify quickstart Check 8a on dev — test the write, not the schedule: close one
  real offer's validity window and confirm it disappears from `/specials/` **immediately** from
  the read-time filter (T007), before any sweep has run; run the sweep and confirm status becomes
  `draft` with `post meta list` unchanged; extend the end date, republish, and confirm the offer
  returns exactly as before. Then confirm: an offer with no end date is never retired; running the
  sweep twice is a no-op the second time; an end date moved forward after retirement republishes
  cleanly with no stale retirement firing; a multi-day scheduler gap retires every offer that
  expired in it. **Restore dev's data afterwards** (SC-006a)
- [ ] T038 [US5] Verify quickstart Check 8 on dev: temporarily close every published offer's
  validity window (or filter to an empty set) and confirm the banner, title and introduction
  still render with a clear empty-state message in place of the list, no broken pagination
  controls, and a page number past the end of the set gives a defined response rather than an
  empty list under working controls (FR-013, spec edge cases)

**Checkpoint**: All five user stories are independently functional; the page is correct with or
without the retirement sweep running

---

## Phase 8: Polish & Cross-Cutting Concerns

**Purpose**: Checks that span every user story, plus hand-over housekeeping

- [ ] T039 [P] [THEME] Re-run Gate 0 — `phpcs --standard=.phpcs.xml.dist .`,
  `find patterns templates parts -name '*.php' -exec php -l {} \;` — clean, against the finished
  feature (quickstart Gate 0)
- [ ] T040 [P] [THEME] Run quickstart Check 12: `grep -rnE '#[0-9a-fA-F]{3,8}|rgb\(|font-family:\s*["A-Za-z]' patterns/template-archive-special.php styles/sections/cards/special-card.json`
  — expect nothing; run the `theme-orphaned-refs` skill; confirm no `var:custom|…` shorthand
  appears in a `style` object on `core/post-title`, `core/post-terms`, `core/post-content` or any
  other dynamic block used in the pattern (FR-031, Constitution I–IV)
- [ ] T041 [P] Run quickstart Check 9 on dev: automated accessibility scan with no critical or
  serious violations; full keyboard operability with visible focus on every interactive element;
  band text meeting the project's contrast standard over its background image at every viewport
  width including light images (Risk R-3); no horizontal scrolling at 320px; and exactly one
  `<main>` via `grep -c '<main\|"tagName":"main"' templates/archive-special.html patterns/template-archive-special.php`
  (SC-009, SC-010, FR-030)
- [ ] T042 [P] [THEME] Run quickstart Check 10: open the Specials archive template in the Site
  Editor, confirm no invalid- or missing-content warnings, bindings show their authored fallback
  text (expected, not a defect), and no DB override is shadowing `templates/archive-special.html`
  (SC-011)
- [ ] T043 Run quickstart Check 11: side-by-side comparison of live `/specials/` against the
  rebuild at desktop and phone widths — Zared's review per AGENTS.md, not an automated
  fix-verify loop (SC-012)
- [ ] T044 Verify SC-006 at hand-over: fetch `special-sitemap.xml` and confirm zero individual
  offer URLs. This is search-plugin configuration owned by the deployment/redirects line — record
  the result, do not implement a fix here (FR-023)
- [ ] T045 [P] [THEME] Update `wp-content/themes/sd-theme-2026/CHANGELOG.md` per Keep a Changelog
  1.1.0, tagged LS-2021
- [ ] T046 [P] [PLUGIN] Update `wp-content/plugins/sd-enhancements-2026/CHANGELOG.md` per Keep a
  Changelog 1.1.0, tagged LS-2021
- [ ] T047 Locate what produces the live `301` on southerndestinations.com today (outside
  `tour-operator` and `to-specials` — research R-07 leaves this unidentified), so the rebuild does
  not stack two redirect rules at launch, and hand the finding to the deployment/redirects line —
  a check, not a blocker

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies — start immediately in both repos
- **Foundational (Phase 2)**: Depends on Setup — **blocks every user story**. T007/T008 must land
  before any band markup exists (plan.md implementation sequence, steps 1–5 before step 6)
- **User Stories (Phase 3–7)**: All depend on Foundational completion
  - **User Story 1 (P1)** is the only one that can be built in true isolation — it is the MVP
  - **User Story 2, 3** extend the same `patterns/template-archive-special.php` file User Story 1
    creates, so while independently *testable*, they are not independently *file-parallel* with
    it — sequence US1 → US2/US3 in the same working tree
  - **User Story 4** (`[PLUGIN]` redirect + anchor, `[THEME]` deletion) can proceed in the plugin
    repo as soon as Foundational is done, in parallel with the theme-side stories
  - **User Story 5** (`[PLUGIN]` retirement) explicitly depends on Foundational's T007 landing
    and being verified **first** — plan.md step 5a is "never before" step 5, because building it
    second is what proves the page does not depend on it
- **Polish (Phase 8)**: Depends on all five user stories being complete

### Within Each User Story

- User Story 1: T009 (shell) → T010 (banner/furniture) → T011 (query) → T012 (band ground) → T013
  (name/meta placeholder/description) → T014 (button) → T015 (pagination/empty state) → T016
  (style, if needed) → T017 (CTAs) → T018 (responsive) → T019 (verify)
- User Story 2: T020 → T021 → T022 (same meta-row block, sequential) → T023 → T024 (verify)
- User Story 3: T025 → T026 (conditional) → T027 (verify)
- User Story 4: T028, T029, T031 are `[PLUGIN]`; T030 is `[THEME]` and independent of them →
  T032, T033 (verify)
- User Story 5: T034 → T035 → T036 → T037, T038 (verify)

### Parallel Opportunities

- T002 and T003 (Setup) can run together once T001 confirms both repos are ready
- T004 and T005 (Foundational) are independent decisions/enumerations and can run together
- T030 (delete `single-special.html`) can run in parallel with any `[PLUGIN]` User Story 4 task
- Once Foundational (Phase 2) is done, `[PLUGIN]`-only User Story 4 (T028, T029, T031) and User
  Story 5 (T034–T036, but only after T007/T008) can proceed in the plugin repo without the theme
  branch being touched — matching plan.md's note that steps 1, 5 and 5a need no theme work
- T039–T042, T045, T046 (Polish) touch different files/environments and can run together once
  every user story is complete

---

## Parallel Example: Foundational phase

```bash
# Launch T004 and T005 together — independent, no file conflicts:
Task: "Enumerate every #special- anchor in patterns/, parts/, templates/ (T004)"
Task: "Settle the enquiry-trigger identifier with the line 15/16 owner (T005)"
```

---

## Implementation Strategy

### MVP First (User Story 1 Only)

1. Complete Phase 1 (Setup) and Phase 2 (Foundational) — the query must be correct before any
   markup is authored
2. Complete Phase 3 (User Story 1) — the page renders, paginates, and hides expired offers
3. **STOP and VALIDATE**: run quickstart Checks 1, 2, 8, 9 (responsive) independently
4. This is already the bulk of Estimate line 11 — everything after is P2/P3 refinement of the
   same page, not new pages

### Incremental Delivery

1. Setup + Foundational → the query is provably correct (T008)
2. User Story 1 → the page is demoable end to end (MVP)
3. User Story 2 → meta-row connections work, verified against real omission cases
4. User Story 3 → every enquire button carries the right offer
5. User Story 4 → old links and in-page anchors resolve correctly; the plugin PR can ship on its
   own schedule relative to the theme PR since neither is functional without the other but both
   are reviewed as separate PRs (plan.md)
6. User Story 5 → retirement lands last, deliberately, proving the page never depended on it
7. Polish → phpcs, accessibility, token discipline, both CHANGELOGs, hand-over sign-off per
   quickstart.md

### Two-Repo Team Strategy

Because four requirements (query shaping, the redirect, the retirement sweep, the render-time
anchor) live in `sd-enhancements` and everything else lives in the theme:

1. One person/session can drive Foundational + User Story 1–3 in the theme repo
2. Another can drive User Story 4 and 5 in the plugin repo as soon as Foundational's T007/T008
   land — no theme branch access needed until User Story 1's band exists for the anchor task
   (T031) to target
3. Both PRs are reviewed and merged separately; neither is functional alone (plan.md, "Repositories
   & branches")

---

## Notes

- `[P]` tasks touch different files with no dependency on an incomplete task
- `[Story]` labels map tasks to spec.md's five user stories for traceability; Setup, Foundational
  and Polish carry none
- `[THEME]` / `[PLUGIN]` labels are mandatory on every task that touches a file — this feature's
  central risk is losing track of which repo a change belongs in
- This feature adds **no new data** (data-model.md) — every field read here already exists;
  retirement (User Story 5) is the only mechanism that writes to stored data, and it changes
  exactly one column (`post_status`)
- Per working agreements: do not commit in either repo. Leave both working trees staged/edited
  for review, and hand over commit messages as text rather than running `git commit`
- Report any slip the week it happens (AGENTS.md)
