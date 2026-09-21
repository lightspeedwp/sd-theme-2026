# Feature Specification: FAQ System, Schema and AI Workflows

**Feature Branch**: `feature/ls-4215-feat-southern-destinations-implement-faq-system-schema-and`

**Created**: 2026-09-18

**Status**: Draft — clarifications resolved (2026-09-18)

**Linear**: [LS-4215](https://linear.app/lightspeedwp/issue/LS-4215/feat-southern-destinations-implement-faq-system-schema-and-ai) — Estimate 3191, R65,981.25 inc VAT, PRD v0.6 ("ASC & SD FAQs - Spec Doc.md")

**Input**: User description: "LS-4215 — Southern Destinations FAQ system, schema and AI workflows. Deliver one reusable FAQ block pattern placed across Homepage, Archives, Destination templates, Why Book With Us, Connect With Us, Team, Contact, Specials, and Social Responsibility. Build a simplified general FAQ page. Update Tour Operator Core, Specials extension and Team extension schema-tag handling. Configure the approved WordPress AI plugin and WordPress MCP for draft-only, human-reviewed content assistance. QA responsive/keyboard/empty-state/schema/CTA-proximity/regression. Deploy within the SD rebuild; one training session."

---

## Estimate ceiling and scope note

**Estimate 3191 is a ceiling, not a starting point** (per `agency-scope-change-control` /
AGENTS.md working agreement 1). This PRD is a *separate* estimate from the theme rebuild's
Estimate 3164 that governs `specs/001-specials-templates` — it funds its own 10 line items
(§12 of the PRD) and nothing outside them. Anything this spec's authors discover that isn't
one of those line items goes to the Change-Control Register, not into the build.

**Theme/plugin boundary — resolved 2026-09-18.** This repository (`sd-theme-2026`) is the
*theme*: design, `theme.json`, `styles/**`, `patterns/`, `parts/`, `templates/`, and the
minimum `functions.php` needed to register those. By the deactivation test in `AGENTS.md`,
schema-tag *output logic* (§4.5 of the PRD), WordPress AI plugin configuration and MCP
permission wiring (§4.6) are behaviour, not design. **Confirmed: this functionality is built
in the companion plugin at `../../plugins/ls-plugin`** (the `lightspeedwp` LS Plugin), not in
this theme repository. This spec therefore scopes **this repo's** share of LS-4215 as: the
shared FAQ pattern (built as non-synced patterns plus reusable template parts — see Q3
below), its placement across templates/patterns, and the general FAQ page (§4.2–4.4 of the
PRD) — all themeable, design-layer work. Schema-tag handling and WordPress AI Engine/MCP
configuration are recorded below as **dependencies this feature consumes** (FAQ markup must
expose the data `ls-plugin`'s schema layer needs; its AI/MCP workflow must be able to save
draft FAQ content into this pattern) and are tracked as a **separate feature in
`ls-plugin`**, not as functional requirements built here.

---

## User Scenarios & Testing *(mandatory)*

### User Story 1 - A traveller finds an answer without submitting an enquiry (Priority: P1)

A prospective traveller is reading a page — the homepage, a destination, Why Book With Us,
Specials, or any other page carrying FAQ content — and has a question the page's main copy
doesn't answer directly. Below the main content, a "Frequently asked questions" section
lists relevant questions as collapsed rows. They open the one that matches their question,
read the answer, and either continue browsing or proceed to the page's existing enquiry
call to action with more confidence than before.

**Why this priority**: This is the entire point of the feature — the PRD's Goal 1 ("Help
travellers find concise answers before making an enquiry") and Goal 2 ("Improve enquiry
confidence and quality"). Every other story exists to make this one safe, consistent and
maintainable.

**Independent Test**: On a page carrying FAQ content, load it as a visitor, confirm the FAQ
section renders below the main content, confirm each question is collapsed by default,
confirm activating a question reveals its answer, and confirm the page's existing enquiry
CTA still renders in its established position relative to the FAQ section.

**Acceptance Scenarios**:

1. **Given** a page with one or more FAQ entries authored, **When** a visitor loads the
   page, **Then** the FAQ section renders using the shared pattern, below the page's main
   content and above or beside the existing enquiry CTA as already established for that
   template.
2. **Given** a FAQ section with several questions, **When** the page loads, **Then** every
   question is visually collapsed and only becomes visible in full when activated.
3. **Given** a collapsed question, **When** a visitor activates it (mouse or touch), **Then**
   its answer becomes visible and the question's expanded/collapsed state is reflected in
   the control itself.
4. **Given** an already-expanded question, **When** a visitor activates it again, **Then** it
   collapses.
5. **Given** a page or template from the approved placement list (§4.3 of the PRD) that has
   **no** FAQ content authored, **When** the page loads, **Then** no FAQ section, heading, or
   empty container renders at all.

---

### User Story 2 - An editor adds or updates FAQ content on any approved page (Priority: P1)

A content editor opens a page in the block editor — one of the pages on the approved
placement list, or the general FAQ page — and wants to add, edit, reorder or remove FAQ
questions and answers. They insert the shared FAQ pattern (or open the one already present),
type or edit a question and its answer using ordinary rich-text controls, and save. No
custom UI, settings screen, or taxonomy is involved.

**Why this priority**: The PRD's Goal 3 ("Provide editors with one consistent FAQ workflow")
and Scope Principle "Keep content editor-managed where practical." Without this, the FAQ
system is a one-off, not something the client can maintain after handover.

**Independent Test**: In the block editor, insert the shared FAQ pattern on a page that has
none, add two question/answer pairs, save, and confirm the front end matches. Then remove
one pair and confirm the section still renders correctly with one entry, and remove the
last entry and confirm the section disappears from the front end.

**Acceptance Scenarios**:

1. **Given** an editor on any approved page, **When** they insert the shared FAQ pattern,
   **Then** the same pattern (not a page-specific variant) is inserted, matching the pattern
   already used elsewhere in the theme.
2. **Given** an editor with the FAQ pattern on a page, **When** they add, edit or reorder
   question/answer pairs using standard block controls, **Then** the change is reflected on
   the front end after saving, with no template-file edit required.
3. **Given** an editor removes every question/answer pair from a page's FAQ section,
   **When** they save, **Then** the section renders nothing on the front end (per User Story
   1, Scenario 5), and the editor sees a clear empty state while still editing, rather than a
   broken or confusing block tree.
4. **Given** the general FAQ page, **When** an editor opens it, **Then** they find the
   existing site header/page-title treatment, a short introduction area, one FAQ section
   using the shared pattern, and the existing enquiry CTA pattern — with no grouped
   categories, custom navigation, or bespoke hub layout to configure.

---

### User Story 3 - Every FAQ section is accessible by keyboard and at all viewport widths (Priority: P1)

A traveller using only a keyboard, or a screen reader, or a phone, needs the same access to
FAQ content as a mouse user on desktop. They tab to a question, see a visible focus
indicator, activate it with the keyboard, and hear or see its expanded/collapsed state
change. On a phone, the section reflows to a single column with no horizontal scrolling and
every control remains reachable by touch.

**Why this priority**: Acceptance Criteria and §8 of the PRD make this non-negotiable, and
it is also a repository-wide non-negotiable per `AGENTS.md` working agreement 5
("keyboard support and focus traps on every modal and overlay" — the FAQ section is not a
modal, but the same bar applies to any interactive disclosure widget).

**Independent Test**: Navigate a FAQ section using only the Tab and Enter/Space keys and
confirm every question is reachable and operable with a visible focus state; resize to a
phone viewport and confirm no horizontal scroll and full touch operability; run an automated
accessibility check against the section and confirm no critical or serious violations.

**Acceptance Scenarios**:

1. **Given** a FAQ section, **When** a user tabs through it, **Then** each question receives
   a visible focus indicator in the order it appears on the page.
2. **Given** a focused question, **When** the user presses Enter or Space, **Then** it
   toggles exactly as a mouse click would.
3. **Given** an expanded or collapsed question, **When** assistive technology inspects it,
   **Then** its expanded/collapsed state is exposed programmatically, not only visually.
4. **Given** a FAQ section on a phone-width viewport, **When** it renders, **Then** it is a
   single column, causes no horizontal scroll of the page, and every question remains a
   comfortably tappable target.
5. **Given** the FAQ section, **When** interaction is attempted via hover alone (no click,
   tap or keyboard activation), **Then** nothing toggles — hover-only interaction is not
   supported, per §8 of the PRD.

---

### User Story 4 - FAQ content is authored as a draft and reviewed by a human before publishing (Priority: P2)

An approved editor uses AI-assisted drafting to produce candidate FAQ questions and answers
for a page. The drafted content is saved in a draft state. A human editor reviews it against
source material — especially for sensitive topics (pricing, visas, safety, insurance, legal,
responsible-travel claims) — edits or rejects it as needed, and only then publishes it using
the ordinary editorial workflow from User Story 2.

**Why this priority**: PRD §4.6, §7 and Acceptance Criteria all require AI-assisted content
to remain draft-only until human review; this is a governance requirement, not a visitor-
facing feature, so it is P2 relative to the P1 stories above. **The AI drafting and MCP
configuration are built in `ls-plugin` (WordPress AI Engine), not in this theme repository —
resolved 2026-09-18.** This story describes the outcome the wider LS-4215 effort must
produce; the theme's obligation is that whatever produces draft FAQ content writes it into
the same pattern and content model editors use manually in User Story 2, so no separate
review surface is needed.

**Independent Test**: Trigger the approved AI-assisted drafting workflow for a FAQ entry,
confirm the result is saved as a draft (not publicly visible), confirm a human reviewer can
edit or discard it before publishing, and confirm published content is indistinguishable in
structure from content an editor typed by hand.

**Acceptance Scenarios**:

1. **Given** AI-assisted FAQ content has been generated, **When** it is saved, **Then** it is
   held in a draft state that is not visible to site visitors.
2. **Given** draft AI-generated FAQ content, **When** a human editor reviews it, **Then**
   they can edit or discard it using the same editorial controls as hand-authored content,
   with no separate approval tool required.
3. **Given** a sensitive topic (pricing, visas, safety, insurance, legal, responsible-travel
   claims), **When** AI-assisted content addressing it is drafted, **Then** it remains draft
   and unpublished until a human with source material confirms accuracy.
4. **Given** AI-assisted content has been published, **When** the front end renders it,
   **Then** it is presented exactly as any other FAQ entry — same pattern, same markup, same
   accessibility behaviour — with no visible distinction from hand-authored content.

---

### User Story 5 - Structured data matches what visitors actually see, with no duplication (Priority: P2)

A search engine or other structured-data consumer reads a page carrying FAQ content, a
Specials offer, or a Team member profile. The structured data it receives describes exactly
what a visitor sees on that page — the same FAQ questions and answers, the same offer
details, the same team member details — once each, with no conflicting or duplicate
declarations from more than one source on the same page.

**Why this priority**: PRD §4.5, §4.7 and the Risk "Structured data does not match visible
content" (§10). P2 because it is a validation and correctness requirement layered on top of
the P1 visitor-facing behaviour, not a new visitor journey. **The schema-tag output logic is
built in `ls-plugin`, not in this theme — resolved 2026-09-18 (see Q1).** This story's
acceptance criteria describe the outcome; this theme's obligation is only to expose the
data cleanly (FR-014, FR-015) and not to duplicate it.

**Independent Test**: Extract structured data from a representative FAQ page, a Specials
page, and a Team page; confirm the FAQ questions/answers in the structured data match the
visible content exactly; confirm no more than one structured-data block of a given type
describes the same entity on the same page.

**Acceptance Scenarios**:

1. **Given** a page with a FAQ section, **When** its structured data is inspected, **Then**
   every question and answer in the structured data has a matching visible question and
   answer on the page, and vice versa.
2. **Given** a Specials page or a Team member page, **When** its structured data is
   inspected, **Then** exactly one structured-data declaration exists per entity — never
   zero when content exists, never more than one.
3. **Given** a page carrying both a FAQ section and another schema-tagged entity (an offer,
   a team member), **When** its structured data is inspected, **Then** the two do not
   conflict, overlap, or duplicate fields.
4. **Given** a FAQ entry is edited or removed, **When** the page is next rendered, **Then**
   the structured data reflects the change — it is never stale relative to the visible
   content.

---

### Edge Cases

- **A page on the approved placement list with zero FAQ entries.** The section, its heading
  and any surrounding container must render nothing — no empty accordion shell, no "no FAQs
  yet" placeholder visible to visitors (User Story 1, Scenario 5).
- **A very long answer.** The expanded panel must grow to fit the content without clipping,
  internal scrolling, or pushing following page content out of a usable position.
- **A question or answer containing rich text (links, lists, emphasis).** Formatting must be
  preserved and must not break the disclosure control's expand/collapse behaviour or the
  page layout.
- **The same underlying question relevant to two different pages (e.g. Contact and Why Book
  With Us).** Per §6 of the PRD, generic answers should not be duplicated unnecessarily
  across pages — an editor must be able to tell, from the workflow alone, whether a question
  belongs on the general FAQ page or as page-specific context, without needing a dedup tool.
- **Rapid repeated activation of the same question.** The control must not enter a broken
  intermediate state, mismatched ARIA state, or double-fire its toggle.
- **A page carrying a FAQ section and a schema-tagged entity together (e.g. a Specials page
  with both offer schema and FAQ schema).** Structured data for the two must not collide.
- **AI-assisted content abandoned mid-review.** A draft that is never approved must not
  appear on the front end and must not silently auto-publish after a timeout.
- **Editor previewing a page with unsaved FAQ changes.** The preview must show the
  unsaved state accurately without requiring a publish.

---

## Requirements *(mandatory)*

### Functional Requirements

**Shared FAQ pattern (this repository)**

- **FR-001**: The theme MUST provide exactly one reusable FAQ block pattern — a non-synced
  pattern, optionally composed from one or more reusable template parts (e.g. a shared FAQ
  section wrapper or single-question row) — built from the SD rebuild's existing design
  system and tokens, with no bespoke variant for any individual page or template. Each
  placement is an independent copy authored per page; editing one placement's content MUST
  NOT change any other placement's content (resolved 2026-09-18 — see Q3).
- **FR-002**: The FAQ pattern MUST render nothing — no heading, no container, no empty
  disclosure controls — when it holds no question/answer content.
- **FR-003**: The FAQ pattern MUST expose each question as a control that toggles its answer's
  visibility, defaulting to collapsed, using semantic markup and exposing expanded/collapsed
  state programmatically (not via CSS/visual-only cues).
- **FR-004**: The FAQ pattern MUST be operable by keyboard alone, MUST show a visible focus
  indicator on every interactive element, and MUST NOT rely on hover-only interaction for any
  of its behaviour.
- **FR-005**: The FAQ pattern MUST reflow to a single column at phone viewport widths with no
  horizontal scrolling introduced, and remain legible at increased text-zoom levels.
- **FR-006**: The FAQ pattern MUST be insertable, without modification, on the Homepage,
  Archives, Destination templates, Why Book With Us, Connect With Us, Team, Contact,
  Specials, and Social Responsibility templates/pages, and MUST NOT require a page- or
  template-specific pattern variant to do so.
- **FR-007**: Every value used by the FAQ pattern (colour, spacing, typography, radius) MUST
  be expressed through the theme's existing numeric design tokens; the feature MUST introduce
  no new visual decisions beyond what the rebuild's design system already defines.

**General FAQ page (this repository)**

- **FR-008**: The theme MUST provide a general FAQ page template/pattern composed of: the
  existing site header and page-title treatment, a short introduction area, exactly one
  instance of the shared FAQ pattern (FR-001), and the theme's existing enquiry CTA pattern —
  and MUST NOT include grouped FAQ categories, custom in-page navigation, or a bespoke hub
  layout.
- **FR-009**: The general FAQ page MUST carry exactly one `<main>` landmark and a heading
  hierarchy that descends without skipping levels, per this theme's template convention.

**Editorial workflow**

- **FR-010**: Editors MUST be able to add, edit, reorder and remove FAQ question/answer pairs
  on any approved page using standard block-editor controls, with changes reflected on the
  front end on save and with no template-file edit required.
- **FR-011**: The editing experience MUST make an empty FAQ section (User Story 2, Scenario
  3) clearly editable in the editor even though it renders nothing on the front end — it MUST
  NOT appear broken or invite a confusing empty state to editors.

**Content governance (built in `ls-plugin`, consumed here)**

- **FR-012**: Published FAQ content visible to visitors MUST be human-reviewed; AI-assisted
  drafts MUST remain in a draft state until a human approves them, using the same content
  model and editorial surface as FR-010 so no separate review tool is required. *(Resolved
  2026-09-18 — the AI drafting mechanism (WordPress AI Engine) and its MCP permission
  boundaries are built in `ls-plugin`, not in this theme repository. This theme's obligation
  is that FR-010's content model accepts draft content written by that workflow with no
  structural difference from hand-authored content.)*
- **FR-013**: Sensitive-topic FAQ content (pricing and availability, supplier terms, visa and
  entry information, safety and health, insurance, legal information, responsible-travel
  claims) MUST NOT be published without confirmation that it rests on reliable, reviewed
  source material.

**Schema (built in `ls-plugin`, consumed here)**

- **FR-014**: Wherever the FAQ pattern (FR-001) renders content, its markup MUST expose the
  question/answer data in a form `ls-plugin`'s structured-data layer can consume without
  requiring a parallel, separately-maintained copy of the same content.
- **FR-015**: The theme MUST NOT introduce a second, competing source of FAQ, Specials, or
  Team structured data on any page — schema-tag output for those entities is generated in
  `ls-plugin` (Tour Operator Core, the Specials extension, the Team extension), and the
  theme's markup MUST NOT duplicate or conflict with it. *(Resolved 2026-09-18 — see Q1. The
  schema-tag generation logic itself is out of scope for this theme repository.)*

**Deployment and handover**

- **FR-016**: This feature's rollout MUST be deployable as part of the existing SD rebuild
  process, with no separate deployment pipeline.
- **FR-017**: Handover MUST include documentation sufficient for an editor to place the FAQ
  pattern on a new page, author content, and understand the draft/publish distinction for
  AI-assisted content, without needing developer support.

### Key Entities

- **FAQ pattern instance** — One placement of the shared FAQ block pattern (FR-001) on a
  page or template. Holds an ordered list of question/answer pairs. Has no existence or
  state independent of the page it is placed on — there is no central FAQ content store,
  category taxonomy, or relationship-management system (explicitly excluded by the PRD).
- **Question/answer pair** — A single FAQ entry: a question (plain text or short rich text)
  and an answer (rich text, may include links/lists/emphasis). Authored directly inside a
  FAQ pattern instance by an editor or by an approved AI-assisted drafting workflow.
- **General FAQ page** — One page composed per FR-008: header/title treatment, introduction,
  one FAQ pattern instance, and the enquiry CTA. Distinct from the *contextual* FAQ pattern
  instances that appear on other approved pages/templates (§6 of the PRD draws this
  distinction: general vs. contextual FAQ content).
- **Structured-data declaration** — The schema.org-equivalent structured data describing a
  FAQ pattern instance's content, and separately, existing Tour Operator/Specials/Team
  structured data. Must correspond 1:1 with visible content and must not collide across
  sources on the same page (FR-014, FR-015).

---

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: The same FAQ pattern renders correctly, with zero visual or markup variants,
  across every one of the nine approved placements (Homepage, Archives, Destination
  templates, Why Book With Us, Connect With Us, Team, Contact, Specials, Social
  Responsibility) plus the general FAQ page.
- **SC-002**: Zero pages on the approved placement list render an empty FAQ heading,
  container, or disclosure shell when no FAQ content has been authored for that page.
- **SC-003**: 100% of FAQ questions on a representative sample of pages are operable by
  keyboard alone, each with a visible focus indicator, and pass an automated accessibility
  check with no critical or serious violations.
- **SC-004**: The FAQ section causes no horizontal scrolling and remains fully operable at a
  320px viewport width, on every approved placement.
- **SC-005**: An editor with no developer support can add a new FAQ question/answer pair to
  an approved page and see it live within one publish action.
- **SC-006**: Zero AI-assisted FAQ content reaches the live site without a recorded human
  review step.
- **SC-007**: On a representative sample of pages carrying FAQ content, Specials offers, or
  Team profiles, structured-data output matches visible content exactly, with zero duplicate
  or conflicting declarations per entity per page.
- **SC-008**: One training session is delivered and one set of handover notes is produced,
  covering FAQ management, draft-only AI handling, sensitive topics, source boundaries and
  escalation.
- **SC-009**: The rebuilt pages carrying the FAQ pattern show no regression, side-by-side
  against their pre-feature state, to any surrounding template content or to the existing
  enquiry CTA's position and behaviour.

---

## Assumptions

- **The FAQ pattern is a standard (non-synced) block pattern**, inserted per page and edited
  in place — not a synced/global pattern where editing one instance would change every other
  placement's content. **Confirmed 2026-09-18 (Q3)** — each placement is independently
  authored. The FAQ section's shared structure additionally uses one or more reusable
  **template parts** (e.g. a common FAQ wrapper/row part referenced from `parts/`), so a
  structural change updates every placement while each placement's question/answer content
  stays independent.
- **No new custom post type, taxonomy, or relationship field is introduced for FAQ content.**
  The PRD explicitly excludes "a custom FAQ library or relationship-management system" (§5);
  question/answer pairs live as ordinary block content inside each page.
- **"Archives" in the placement list (§4.3) refers to the existing Tour Operator archive
  templates already built elsewhere in this theme** (destination/accommodation/tour
  archives), not a new archive type introduced by this feature.
- **The general FAQ page is a single, ungrouped list of questions** (§4.4 explicitly excludes
  grouped categories and a hub design), so no category taxonomy or filtering UI is designed
  for it.
- **Deduplication between the general FAQ page and contextual FAQ placements (§6) is an
  editorial discipline, not a system-enforced constraint** — nothing in the PRD asks for
  automatic detection of duplicate questions across pages, so this feature does not build
  one.
- **Schema-tag output logic and WordPress AI Engine/MCP configuration are implemented in
  `ls-plugin`** (`/Users/warwick/Local Sites/beta/app/public/wp-content/plugins/ls-plugin`),
  **not in this theme repository — confirmed 2026-09-18 (Q1)**, per the theme/plugin
  deactivation test in `AGENTS.md`. This spec records this repo's dependency on that work
  (FR-012, FR-014, FR-015) rather than building it; the `ls-plugin` side of LS-4215 needs its
  own spec/plan in that repository, out of scope for this document.

---

## Clarifications

### Resolved 2026-09-18

- **Q1 — Where does schema-tag output logic and AI/MCP configuration get built?**
  **Answer**: In `ls-plugin` (`/Users/warwick/Local Sites/beta/app/public/wp-content/plugins/ls-plugin`).
  Any functionality for Tour Operator/Specials/Team schema-tag handling and WordPress
  AI/MCP configuration belongs there, matching Option A. This theme repository's scope is
  limited to FR-001–FR-011 and FR-016–FR-017; FR-012, FR-014 and FR-015 are dependencies on
  `ls-plugin`, tracked here but not planned or built in this repo. A separate spec/plan for
  the `ls-plugin` side of LS-4215 is needed in that repository and is out of scope for this
  document.
- **Q2 — Which WordPress AI plugin and MCP permission set is approved?**
  **Answer**: WordPress AI Engine. This names the tool referenced by FR-012's dependency;
  its specific MCP permission/operation list is configured and owned within `ls-plugin`,
  not this theme.
- **Q3 — Non-synced or synced pattern?**
  **Answer**: Non-synced pattern, per placement, with shared structure additionally carried
  by reusable **template parts** (not just a pattern). FR-001 and the Assumptions above have
  been updated accordingly.
