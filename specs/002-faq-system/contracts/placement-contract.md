# Contract: FAQ pattern placement, per approved location

Confirmed against the actual files in this repository on 2026-09-18. This table is the
source of truth for `/speckit-tasks` — one row is roughly one task.

| # | PRD placement (§4.3) | This repo's file(s) | Mechanism | Insertion point |
|---|---|---|---|---|
| 1 | Homepage | `templates/front-page.html` | Direct `wp:pattern` reference in the thin template (this template has no separate composition pattern — it's already a flat stack of `wp:pattern` references) | Immediately before `sd-theme-2026/homepage-lets-make-it-happen` (the existing closing CTA), or after it — confirm exact order against the live site during implementation; do not guess ahead of a live-page check per `AGENTS.md`'s "live site decides" rule |
| 2 | Archives | `patterns/template-archive-accommodation.php`, `patterns/template-archive-destination.php`, `patterns/template-archive-tour.php`, `patterns/template-archive-team.php` (Team's own placement is row 6 below — do not double-insert), `patterns/template-archive-special.php` (Specials' own placement is row 5 below — do not double-insert) | `wp:pattern` reference inside the composition pattern | Before the archive's existing closing CTA, matching each archive's established pattern |
| 3 | Destination templates | `patterns/template-single-destination.php` (and `template-single-country.php`/`template-single-region.php` if "Destination templates" is read to include those — confirm scope at task-breakdown time; the spec's Assumptions do not resolve this sub-question and it is low-risk enough not to need a fourth spec clarification) | `wp:pattern` reference inside the composition pattern | Before the existing closing CTA/summary band |
| 4 | Why Book With Us | No dedicated template — ordinary Page on `templates/page.html` | Editor inserts `patterns/faq-section.php` as **page content**, not a template edit | Editor's choice, guided by handover notes (FR-017); no code task beyond making the pattern available |
| 5 | Connect With Us | Same as row 4 | Same as row 4 | Same as row 4 |
| 6 | Team | `patterns/template-single-team.php` (individual team member) and/or `patterns/template-archive-team.php` (team archive) — confirm which the PRD means; PRD §4.3 says "Team" singular without distinguishing archive/single, and the Linear issue's Acceptance Criteria mention "Team extension" schema-tag handling separately, suggesting the FAQ placement is about the page(s) visitors browse, i.e. likely both | `wp:pattern` reference inside the composition pattern(s) | Before the existing closing CTA/summary band |
| 7 | Contact | No dedicated template — ordinary Page | Same as row 4 | Same as row 4 |
| 8 | Specials | `patterns/template-archive-special.php` | `wp:pattern` reference inside the composition pattern | Before the existing `cta-like-what-you-see` CTA and the "Why choose Southern Destinations" panel, per `specs/001-specials-templates/spec.md`'s documented page furniture for this exact template |
| 9 | Social Responsibility | No dedicated template — ordinary Page (unless this repo later gains one; not found as of 2026-09-18) | Same as row 4 | Same as row 4 |
| — | General FAQ page (FR-008, not a placement but a new page) | `templates/page-faq.html` (new) → `patterns/template-page-faq.php` (new) | New thin template + composition pattern, following the exact convention of `templates/archive-special.html` → `patterns/template-archive-special.php` | N/A — this is the page itself |

## Notes for `/speckit-tasks`

- Rows 4, 5, 7, 9 are **not** template-file tasks. They are: (a) confirm the pattern is
  registered and available in the inserter, (b) editorial action on the actual Pages (likely
  outside this repo's PR — Pages are content, not code), (c) documented in handover notes.
  Do not create a "build Why Book With Us template" task; none is needed.
- Rows 1, 2, 3, 6, 8 are template/pattern edits — one task per file, each adding one
  `wp:pattern` reference and verifying it against the live page's actual layout (per
  `AGENTS.md`'s source-of-truth precedence: the live site decides where things go, this
  contract records the *mechanism*, not the final pixel position).
- The three open sub-questions flagged inline above (exact CTA ordering on Homepage;
  whether "Destination templates" includes country/region; whether "Team" means archive,
  single, or both) are small enough to resolve during task breakdown or a quick live-site
  check, not large enough to justify reopening `/speckit-clarify` on the spec.
