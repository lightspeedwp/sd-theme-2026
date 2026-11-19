# Implementation Plan: Specials Landing Page

**Branch**: `001-specials-templates` (spec-kit branch name)
| **Work branch**: `feature/ls-2021-specials`, in **two repos** — the theme (already existed,
from `develop`) and `sd-enhancements-2026` (created 2026-09-17, from `develop`) — see
"Repositories & branches" below
| **Date**: 2026-09-17 | **Spec**: [spec.md](./spec.md)

**Input**: Feature specification from `specs/001-specials-templates/spec.md`

**Linear**: [LS-2021](https://linear.app/lightspeedwp/issue/LS-2021) — Estimate 3164 line 11,
R10,800, milestone M2

---

## Summary

Rebuild `/specials/` as a block template: a banner and introduction from Tour Operator
settings, then a paginated list of four full-width offer bands, each carrying the offer's
banner image as its ground, its name, a meta row of connections, its **complete** body copy,
and an enquire button — closed by the existing enquiry CTA and the "Why choose Southern
Destinations" panel. Individual offer pages stay suppressed; `templates/single-special.html`
is deleted.

The approach spans **two repositories, both with a `feature/ls-2021-specials` branch** —
three files in the theme, one module extended and one added in `sd-enhancements` — not a new
subsystem in either:

1. `[THEME]` `templates/archive-special.html` becomes a four-line shell (the convention every
   other archive in this theme already follows), delegating to
2. `[THEME]` a new `patterns/template-archive-special.php` carrying the `<main>`, the banner,
   the query and the band, styled by
3. `[THEME]` the **already-written and currently unused** `styles/sections/cards/special-card.json`,
   whose class contract the markup must match; with
4. `[PLUGIN]` four additions to `sd-enhancements`, because all four are behaviour, not design:
   ordering and validity filtering on the existing `limit_specials_archive()`, a
   `template_redirect` issuing the single-hop 301 the platform does not, a scheduled sweep
   retiring expired offers to draft, and the render-time band anchor.

Items 1–3 are one PR against the theme; item 4 is a separate PR against `sd-enhancements`, on
its own branch, its own hand-over. Neither is functional without the other — the theme
delegates query shaping and the redirect to code that has to exist on the other side.

Research produced two corrections that matter more than anything in the build, both **verified
by execution** on the local install rather than by reading source. Tour Operator's
"Disable Single" setting is **inert for `special`** — the filter it runs on never fires for
that post type — and on the post types it does reach it serves the **homepage at HTTP 200**,
the soft-404 SC-005 explicitly forbids. Separately, the offer-expiration mechanism registers
**no hooks at all**, has no scheduler behind it, and was only ever wired for `tour`. So the
platform supplies neither the redirect nor the retirement the spec assumes it supplies.

Both gaps are filled in `sd-enhancements`, which is where they belong under the deactivation
test and which keeps `tour-operator` unpatched. The spec has been amended accordingly —
FR-021, FR-022, FR-026, FR-026a, FR-026b, SC-005 and SC-006a, with Assumptions 7 and 12
withdrawn. See [research.md](./research.md) R-06 and R-07 for the measurements.

**Retirement to draft is now in scope** (FR-026a). It is the only part of this feature that
writes to published content, and it is deliberately built as a *second, independent*
mechanism: the page is correct from the read-time filter alone, so retirement can be disabled
or fixed without the visitor-facing behaviour regressing. See
[contracts/retirement-contract.md](./contracts/retirement-contract.md) and spec Risk R-4.

## Technical Context

**Language/Version**: PHP 8.x (WordPress 6.9+ block theme), HTML block markup, JSON
(`theme.json` schema `https://schemas.wp.org/wp/6.9/theme.json`, version 3)

**Primary Dependencies**: WordPress core block editor; `tour-operator` 2.2 (post type
`special`, `lsx/post-meta` and `lsx/post-connection` binding sources, `travel-style`
taxonomy); `to-specials` 2.2 (field configuration, connection meta);
`sd-enhancements-2026` (query shaping, the single-offer redirect, enquiry trigger, render-time
anchor)

**Storage**: WordPress post meta (CMB2-authored, not REST-exposed) and the `lsx_to_settings`
option. **This feature adds none.**

**Testing**: `phpcs --standard=.phpcs.xml.dist .`; `php -l`; manual and automated
verification against the development site per [quickstart.md](./quickstart.md); automated
accessibility scan. No PHPUnit suite in this theme; no new test tooling is introduced.

**Target Platform**: WordPress block theme, front end + Site Editor. Verified on the
development site (migrated content); linted and activation-checked locally (SQLite, no
specials fixtures).

**Project Type**: WordPress block theme feature — one template, one pattern, one section
style, plus a companion change in the sibling plugin repo.

**Performance Goals**: One paginated main query of four posts, matching current behaviour. No
regression to the archive's render path. No new HTTP requests, no new build tooling, no new
npm or Composer dependency. Baseline pass only — full performance engineering is not costed.

**Constraints**: Design is preserved, not redesigned. Tokens only, by numeric slug — no
literals. The theme may not shape queries or author redirects (deactivation test). No
per-install IDs in authored files. Exactly one `<main>` per template. Bindings do not preview
in the editor. Dev is the only environment with specials data.

**Scale/Scope**: 42 offers (5 published, 37 expired). One template, one new pattern, one
existing section style extended, one template deleted; in the plugin, one filter extended plus
a redirect, a scheduled retirement sweep and the band anchor. Estimate line 11 is the ceiling.

## Constitution Check

*GATE: passed before Phase 0; re-evaluated after Phase 1 design — both recorded below.*

| # | Principle | Assessment | Status |
|---|---|---|---|
| I | `theme.json` is generated, not hand-edited | No token changes. The feature introduces no new colour, spacing, type or radius value (FR-031). `theme.json` is not touched. | ✅ |
| II | No literals in authored files | Band and page furniture reference presets by numeric slug. Quickstart check 12 greps for hex, `rgb()` and font-family literals. Note this is the specific reason the plugin's own `special-card` pattern cannot be adopted — it carries `fontSize: "large"` and `primary-700`, neither of which exists in this palette. | ✅ |
| III | `var:custom|…` is silently dropped on dynamic blocks | **Live risk in this feature.** The band is built from `core/post-title`, `core/post-content`, `core/post-terms` — all dynamic. The offer name's `fontWeight` and `lineHeight` come from `special-card.json`'s `elements.heading` (a section style, resolved by the global-styles engine, where the shorthand is safe) and **not** from a `style` object in the block markup. Any per-block override written later must use `var(--wp--custom--…)`. Recorded in quickstart check 12. | ✅ with a named constraint |
| IV | Styling lives in JSON | All styling goes into `styles/sections/cards/special-card.json`, which already exists and already uses its `css` field for the absolutely-positioned body. No file is added to `assets/styles/`. | ✅ |
| V | Never hardcode a per-install ID | No attachment IDs, no form IDs, no navigation `ref`. The band image is a binding on `banner_image_id`; the page banner comes from a setting. | ✅ |
| — | Exactly one `<main>` landmark | The landmark **moves** from `archive-special.html` into the new pattern, matching `archive-tour.html`. Shipping both is the specific failure mode; quickstart check 9 greps for it. | ✅ with a named constraint |
| — | `patterns/*.php` follows core's form | Labels and the button text are inline `esc_html_e()` with the `sd-theme-2026` text domain. No variables holding literals, no loops, no `phpcs:ignore`. | ✅ |
| — | Theme/plugin boundary (deactivation test) | Query shaping, the redirect, the retirement sweep, the render-time anchor and the enquiry payload are all in `sd-enhancements`. The theme carries composition and style only. | ✅ |
| — | Don't patch vendor code | Neither gap is fixed in `tour-operator`. Routing `special` through the content-models runtime, or uncommenting `Post_Expiration`, would change behaviour for every site running the plugin. | ✅ |
| — | Spec Kit vs OpenSpec | This is feature-level work inside the theme repo against an existing line item. It does not change what is delivered against line 11, so it stays in Spec Kit. The two spec corrections below go to the Change-Control Register as corrections, not as new scope. | ✅ |
| — | Nothing is committed without review | Work is left in the working tree. `CHANGELOG.md` updated as part of the work, tagged LS-2021. | ✅ |

**Post-design re-evaluation**: no gate moved. The Phase 1 design added three contracts, all of
which *reinforce* the theme/plugin boundary rather than bending it — each seam that could have
been solved with a line of logic in a template is instead specified as an interface into
`sd-enhancements` or into the line 15/16 surfaces. No violation requires justification, so
Complexity Tracking below is empty.

### Two spec corrections, now settled by measurement

Both were verified by running them on the local install on 2026-09-17, after the first pass of
this plan asserted them from source alone and got the mechanism wrong in each case. Neither is
a constitution violation and neither blocks the build. Both resolve the same way — the
platform supplies nothing, so `sd-enhancements` supplies it — and both make specific sentences
of the spec wrong, which goes to LS-2033 as a correction rather than as new scope.

**1. "Disable Single" is inert for `special`, and soft-404s where it works** (research R-07).
The filter it runs on, `content_model_post_type_args`, fires only for `accommodation`,
`destination` and `tour` — `special` is registered by a direct `register_post_type()` call that
bypasses the content-models runtime. With the setting on, `special`'s `publicly_queryable`
stays `true` and the single still serves its own page at 200. Tested against `tour`, where the
filter does apply, the URL serves the **homepage at 200 with zero redirects** — success while
displaying different content, which SC-005 forbids by name.

→ **FR-021 and FR-022 are wrong as written.** Enabling the setting achieves nothing, and
"MUST NOT author a redirect rule" was written believing the platform already did. A
`template_redirect` in `sd-enhancements` issues the single-hop 301 instead. SC-005's target is
unchanged and correct; only the sentence about who produces it is wrong.

**2. Offer expiration registers no hooks and never covered specials** (research R-06).
`Post_Expiration` is instantiated but both `add_action()` calls in its constructor are
commented out — `has_action()` returns `false` on every hook it would use. Action Scheduler is
not even available. And the hooks are `save_post_tour` / `lsx_to_expire_tour`: **tour only**,
with no `special` equivalent anywhere. No cron event exists.

→ **The original FR-026's premise was false as installed**, and its instruction to "verify it
is configured and carry the setting across" could not be executed. The spec now splits it:
**FR-026** is the read-time exclusion in the query, which carries FR-008 and SC-001 and needs
no scheduler; **FR-026a** is retirement to draft, added at Zared's direction so the editor's
list matches what is live; **FR-026b** records that the platform's mechanism is not to be
revived. FR-025's visitor-facing wording still holds.

The two are independent by design. The page is correct from FR-026 alone — FR-026a is never
load-bearing for what a visitor sees, which is what makes a scheduled write to published
content an acceptable thing to add here (spec Risk R-4).

`tour-operator` is vendor code and is not patched for either — AGENTS.md, and a change there
would alter behaviour for every site running the plugin.

## Project Structure

### Documentation (this feature)

```text
specs/001-specials-templates/
├── spec.md                          # Input
├── plan.md                          # This file
├── research.md                      # Phase 0 — ten findings, two spec corrections
├── data-model.md                    # Phase 1 — fields read, fields deliberately unrendered
├── contracts/                       # Phase 1 — the five seams
│   ├── README.md
│   ├── query-contract.md            # theme ↔ sd-enhancements
│   ├── enquiry-trigger-contract.md  # this page ↔ lines 15/16
│   ├── anchor-contract.md           # this page ↔ every template linking to an offer
│   ├── redirect-contract.md         # sd-enhancements ↔ old links, and the sitemap owner
│   └── retirement-contract.md       # sd-enhancements ↔ the editors maintaining offers
├── quickstart.md                    # Phase 1 — twelve validation checks
├── checklists/requirements.md       # Pre-existing
└── tasks.md                         # Phase 2 — NOT created by /speckit-plan
```

### Repositories & branches — this feature spans two

**This is not incidental.** The theme/plugin boundary (AGENTS.md's deactivation test) put
four of this feature's requirements — the query filter, the redirect, the retirement sweep,
and the band anchor — in `sd-enhancements`, not the theme. `/speckit-implement` builds both
repos in the same run; it is not a theme-only feature with plugin notes on the side.

| Repo | Root | Branch | State as of 2026-09-17 |
|---|---|---|---|
| **Theme** | `wp-content/themes/sd-theme-2026/` | `feature/ls-2021-specials` | Already exists, checked out, from `develop` |
| **Plugin** | `wp-content/plugins/sd-enhancements-2026/` | `feature/ls-2021-specials` | Created from `develop`, checked out, clean |

Same branch name in both, same Linear issue, deliberately — this is one feature, two working
trees. Each repo is committed **separately** when the time comes (they share no git history),
and per the working agreements **nothing is committed here** — both are left staged-or-edited
in the working tree for review. `cd` into the repo you're editing before running any git
command; a command run from the wrong root silently no-ops or errors rather than crossing
repos.

### Source code

Every file below is tagged `[THEME]` or `[PLUGIN]` so a task list generated from this plan
inherits the tag rather than losing it. A task with no tag is a defect in the task list, not
an ambiguity in the plan — every file here has exactly one home.

```text
wp-content/themes/sd-theme-2026/                    [THEME] — branch feature/ls-2021-specials
├── templates/
│   ├── archive-special.html              # REWRITE → four-line shell, header/pattern/footer
│   └── single-special.html               # DELETE (FR-024)
├── patterns/
│   └── template-archive-special.php      # NEW → <main>, banner, intro, query, band, CTAs
├── styles/sections/cards/
│   └── special-card.json                 # EXTEND → written, registered, this is its first consumer
└── CHANGELOG.md                          # UPDATE → Keep a Changelog 1.1.0, tagged LS-2021

wp-content/plugins/sd-enhancements-2026/            [PLUGIN] — branch feature/ls-2021-specials
├── modules/
│   ├── queries.php                       # EXTEND → limit_specials_archive(): ordering + validity
│   └── specials.php                      # NEW → template_redirect: single offer → 301 /specials/
│                                         #       scheduled sweep: expired offer → draft
│                                         #   (+ the render-time band anchor; may fold into an
│                                         #    existing module at task time)
└── CHANGELOG.md                          # UPDATE → tagged LS-2021
```

Reused unchanged, not rebuilt — all `[THEME]`: `parts/header.html`, `parts/footer.html`,
`parts/modal-enquiry.html`, `patterns/why-choose-sd.php`, and whichever of the three `cta-*`
patterns carries this page's wording (FR-020 says reuse — no fourth CTA pattern is authored).

**Structure Decision**: `archive-special.html` → `patterns/template-archive-special.php` is
not a preference; it is the convention `archive-tour.html`, `archive-destination.html`,
`archive-accommodation.html` and `archive-team.html` already follow in this theme. It also
puts every piece of display copy inside a PHP file where `esc_html_e()` can reach it, which
the constitution's pattern rules require and a `.html` template cannot do. The band is styled
by an existing section style rather than a new one because
`patterns/template-single-accommodation.php:57-68` already records that building this card is
this feature's work, and the accommodation single's deferred specials shelf is waiting to
consume it.

## Implementation sequence

Not a task list — that is `/speckit-tasks`. This is the order the dependencies impose. Every
step names its repo; `/speckit-tasks` should carry the same tag onto whatever task it becomes.

1. `[PLUGIN]` **Write the redirect in `sd-enhancements`** (research R-07). No longer a
   question of ownership — the platform provides nothing, so this is ours. A
   `template_redirect` on a `special` single request, 301 to the archive, single hop. Small
   and independent of the template, so it can land first and be verified on its own.
   *Alongside it, still check what produces the live 301* so the rebuild does not stack two
   rules at launch and the deployment/redirects line knows what it is inheriting. That is a
   check, not a blocker.
2. `[THEME]` **Enumerate the anchors in use** (research R-10, anchor contract). Grepping the
   theme repo's `patterns/`, `parts/` and `templates/`. This is the SC-008 test set and it
   constrains the band's outer element — built in step 6.
3. **Settle the enquiry payload** with the line 15/16 owner (enquiry-trigger contract). Not
   repo work by itself — a decision — but it changes both the `[THEME]` band's markup (step 6)
   and, if a new attribute needs reading server-side, the `[PLUGIN]` enquiry module. Settle it
   before either.
4. `[PLUGIN then THEME]` **Verify the two binding behaviours against real data** — that
   `lsx/post-connection` (Tour Operator, consumed via `sd-enhancements`) omits an empty set and
   an unpublished item cleanly (quickstart check 3). If it does not, the omission moves into a
   `[PLUGIN]` fix before the `[THEME]` band can rely on it.
5. `[PLUGIN]` **Extend the query filter** — ordering and validity, with the `NOT EXISTS`
   branch that keeps open-ended offers listed. This carries SC-001 on its own and is tested on
   its own, **before** retirement exists, so it is proved independent of it.
5a. `[PLUGIN]` **Add the retirement sweep** (FR-026a) — after step 5, never before. Building
   it second is what proves the page does not depend on it. A recurring sweep rather than a
   per-offer scheduled action: simpler, no per-post state to desynchronise, and it handles a
   missed run by design. Verified by closing a real validity window and republishing the
   offer afterwards, not by inspecting the schedule.
6. `[THEME]` **Author the band and the page**, then delete `single-special.html`. Depends on
   steps 2 and 3 being settled, and on step 4's verification — this is the step that can start
   only after the plugin-side decisions above are made, even though its files never leave the
   theme repo.
7. `[THEME + PLUGIN]` **Verify** per quickstart, on dev. Runs against both repos deployed
   together — a check can fail because of either one, and quickstart notes which.

Steps 2–4 are cheap, all answer questions that change the markup, and are the kind of thing
that is expensive to discover after the band is built. Steps 1, 5 and 5a are `[PLUGIN]`-only,
independent of the markup and of each other, and 5a is deliberately last of the three — it is
the only work here that writes to published content, and it should land against a page that
is already provably correct without it. Steps 1, 5 and 5a can proceed in the plugin repo
without the theme branch being touched at all; step 6 is the only step that needs the plugin
side's decisions (3, 4) settled first.

## Complexity Tracking

No constitution gate was violated, so there is nothing to justify here.
