# Quickstart: validating the FAQ system (theme scope)

Prerequisites: local WordPress Studio install running (`http://localhost:8903`), this theme
active, `wp transient delete --all --network` run after any pattern/style change (patterns
don't re-register otherwise — `AGENTS.md`).

## 1. Empty-state check (FR-002, research.md R-01)

1. Open a page from the approved placement list that has never had the FAQ pattern
   inserted. Confirm no FAQ heading, container, or accordion shell appears on the front end.
2. Insert `patterns/faq-section.php`, add one question/answer pair, save, confirm it
   renders.
3. Delete that one question/answer pair (empty the accordion but leave the pattern
   instance in place), save, and check the front end again.
   - **If nothing renders**: R-01's naive approach works; document this as confirmed.
   - **If an empty heading/container is visible**: FR-002 is not yet met by markup alone.
     Either add a guarded conditional in `patterns/faq-section.php`, or update the
     editorial handover notes (FR-017) to say "remove the pattern instance entirely, don't
     empty it" — and update research.md R-01 with the measured result either way.

## 2. Editorial workflow (FR-010, User Story 2)

1. In the block editor, insert `patterns/faq-section.php` on a page that has none.
2. Add two question/answer pairs, save, confirm the front end matches.
3. Reorder them using standard block-editor controls, save, confirm the front end order
   changed.
4. Remove one, save, confirm the section still renders correctly with one entry.

## 3. Keyboard and focus (FR-004, User Story 3)

1. On a page with an FAQ section, tab through it using only the keyboard.
2. Confirm each question receives a visible focus indicator, in document order.
3. Press Enter or Space on a focused question; confirm it expands. Press again; confirm it
   collapses.
4. Confirm no interaction is possible via hover alone (move the mouse over a question
   without clicking; confirm nothing toggles).

## 4. Responsive check (FR-005)

1. Resize the browser (or use device emulation) to a 320–375px phone width.
2. Confirm the FAQ section reflows to a single column, causes no horizontal scroll of the
   page, and every question remains comfortably tappable.

## 5. Accessibility scan (User Story 3, SC-003)

1. Run an automated accessibility check (e.g. axe, Lighthouse) against a page carrying the
   FAQ section.
2. Confirm no critical or serious violations attributable to the FAQ section.
3. Confirm the accordion panel's `aria-expanded`/`hidden` state changes are exposed to
   assistive technology, not only visually (spot-check with a screen reader if available).

## 6. Token discipline (FR-007, Constitution II)

1. Run `theme-orphaned-refs` (skill) after adding `styles/blocks/accordion/faq.json`.
2. Confirm zero orphaned references and that every colour/spacing/typography/radius value
   in the new style variation matches an existing numeric token slug already used elsewhere
   in this theme (no new visual decisions).

## 7. Placement coverage (FR-006, contracts/placement-contract.md)

1. Load each of the nine approved placements plus the general FAQ page.
2. Confirm the same pattern (same markup, same style variation `is-style-faq`) renders on
   each — no bespoke per-page variant.
3. Confirm, on each, the FAQ section sits relative to the existing enquiry CTA as recorded
   in `contracts/placement-contract.md`, and cross-check against the live site
   (`https://www.southerndestinations.com/`) per `AGENTS.md`'s "live site decides" rule for
   any placement not already covered by an existing spec (e.g. Specials, whose furniture is
   already documented in `specs/001-specials-templates/spec.md`).

## 8. General FAQ page (FR-008, FR-009)

1. Open `templates/page-faq.html` on the front end.
2. Confirm exactly one `<main>` landmark (view source or an accessibility tree tool).
3. Confirm the composition: header/title treatment → introduction → one FAQ section → CTA,
   with no grouped categories, custom navigation, or hub design present.

## 9. Regression check (SC-009)

1. On each modified template/pattern, compare against its pre-feature state (git diff or a
   staged preview) to confirm no unrelated layout, spacing, or CTA-position change was
   introduced.

## Quality gate before hand-over

```bash
phpcs --standard=.phpcs.xml.dist patterns/ parts/ templates/
find patterns parts -name '*.php' -exec php -l {} \;
php -d memory_limit=1024M $(which wp) transient delete --all --network
```
