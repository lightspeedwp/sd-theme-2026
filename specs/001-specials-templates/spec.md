# Feature Specification: Specials Landing Page

**Feature Branch**: `feature/ls-2021-specials` (from `develop`)

**Created**: 2026-09-17
**Last updated**: 2026-09-17 (Q1/Q2 answered; re-grounded against a live fetch)

**Status**: Draft — clarifications resolved

**Linear**: [LS-2021](https://linear.app/lightspeedwp/issue/LS-2021) — *Specials*, Estimate 3164 line 11, R10,800, milestone M2

**Input**: User description: "LS-2021 Specials — Specials landing/archive and single Special templates for the sd-theme-2026 block theme"

---

## Overview

Southern Destinations publishes time-limited travel offers ("specials") — a discounted rate
at a lodge, a seasonal package, a honeymoon deal — each valid between a start and an end
date and each attached to the accommodation, tours and destinations it applies to.

They are presented on **one page**. Each offer is a full-width band carrying its own banner
image, its name, the products it applies to, its complete description, and a button to
enquire about it. Each band has its own anchor, and the rest of the site links to offers by
that anchor rather than to a page of their own. There is no individual offer page: those
URLs redirect to the landing page.

This feature rebuilds that page as a block template, preserving the behaviour exactly, and
completes the surrounding cleanup the redirect implies.

**Estimate line 11 reads: "Specials archive/single, valid/expired filtering, CTAs."** The
"single" half of that line resolves — by the decisions recorded below — to *suppressing*
the single rather than building it: a redirect, a sitemap correction, and the offer detail
carried on the landing page instead.

### Decisions taken (2026-09-17)

| # | Question | Decision |
|---|---|---|
| **Q1** | Are individual offer pages public? | **No.** Keep them suppressed; individual offer URLs redirect to the landing page. *(Option B — CC-06)* |
| **Q2** | Does the landing page show past offers? | **No.** Expired offers are simply absent; no visitor-facing past-offers view. *(Option A)* |
| — | Who produces the redirect? | ~~Tour Operator does, via its "Disable Single" setting.~~ **`sd-enhancements` does.** The setting is inert for offers and soft-404s where it applies — measured, not read. *(Ruled 2026-09-17; corrected 2026-09-17 after verification)* |
| — | Who retires an expired offer? | **`sd-enhancements` does**, both at read time and by moving it to `draft`. The platform mechanism does not exist. *(Ruled 2026-09-17 after verification)* |
| — | Surface the unrendered offer fields? | **No.** Matching live. Not a register item, not revisited. *(Ruled 2026-09-17)* |
| — | Standing instruction | **Behaviour matches the live site.** Where this spec and the live site differ, the live site is right and this spec is wrong. |

### Out of scope for this feature

Funded on other line items. Named here so nobody builds them twice and nobody reads their
absence as a gap:

| Concern | Belongs to |
|---|---|
| The enquiry form itself, its fields and its Salesforce feed | Line 15 — Forms and Salesforce settings |
| The enquiry **modal**, and the accommodation/destination modals the meta row opens | Line 16 — Modals and responsive overlays |
| Faceted filtering and search indexing of specials | Line 14 — Search and filtering system |
| `schema.org/Offer` structured data | Line 19 — optional structured-data line, not yet taken |
| Migrating the 5 published and 37 expired offers and their relationships | Line 18 — Content migration |
| Specials carousels embedded on destination, accommodation and tour pages | The issue owning each of those templates |

This feature **places** the enquiry call to action and the modal-opening meta links; it does
not build the surfaces behind them.

### Two behaviours the platform was expected to supply, and does not

Recorded for the Change-Control Register (**LS-2033**) as a correction to this spec's
dependency table, **not** as a request for new scope. Both sit behind elements the line
already funds — the offer list and the suppressed single — and AGENTS.md is explicit that the
behaviour behind a funded element is funded. Flagged so the correction is on the record;
**Zared decides** if he reads it differently.

| Assumed | Measured 2026-09-17 | Now built as |
|---|---|---|
| "Disable Single" produces the 301 | Inert for offers; soft-404 where it applies | FR-021, FR-022 — a redirect in `sd-enhancements` |
| An expiration setting retires expired offers | No hooks, no scheduler, tours only | FR-026, FR-026a — read-time exclusion **and** retirement to draft, both in `sd-enhancements` |

Of the two, the read-time exclusion is load-bearing and small; retirement to draft is an
editorial convenience with a wider blast radius (Risk R-4) and is the part most worth
confirming is wanted before it is built.

---

## Live behaviour, measured

Fetched from `https://www.southerndestinations.com/specials/` on 2026-09-17. This is the
design and behaviour contract.

**Page furniture**
- A rotating page banner with a dedicated specials banner image, and the page title over it.
- Below the banner, an introduction of roughly 50 words.
- One main landmark. Breadcrumbs above the content.

**The offer list**
- **Four offers per page**, full-width, stacked one above the other — a list, not a grid.
  Five published offers currently produce two pages.
- Each offer is one band with the offer's banner image as its background, filling the band's
  full height.
- Inside each band, in order: **centred offer name** → **meta row** → **description** →
  **enquire button**.
- The meta row is a set of labelled links: `Accommodation:` (one or more, each opening that
  accommodation's modal), `Travel Style:` (a link to the travel-style term archive),
  `Destinations:` (one or more, each opening that destination's modal).
- The description is the offer's **full body copy**, not an excerpt. Rates, inclusions,
  minimum-stay rules and validity wording all live inside that copy as authored paragraphs
  and lists.
- The button reads *"Enquire about this special"* and opens the shared enquiry modal; the
  offer's name is written into the form before the modal opens.
- Each band carries `id="special-{slug}"`, and the site's navigation links to offers as
  `/specials/#special-{slug}`.

**Below the list**
- A "Like what you see? Let's start planning!" call to action.
- The standard "Why choose Southern Destinations" panel.

**Individual offer URLs**
- `/special/{slug}/` returns **301 → `/specials/`**. All five behave this way.
- All five are nonetheless still advertised in `special-sitemap.xml`.

### Three corrections to earlier reports

1. **F-03 is stale in its detail.** It recorded individual offer URLs returning HTTP 200
   rendering the archive under a canonical to `/specials/`. They now return a **301
   redirect** to `/specials/`. What remains is the sitemap.
   *Amended 2026-09-17:* the 301 exists on live but **is not produced by any Tour Operator
   setting** — verified by execution, see FR-021. It is therefore not behaviour the rebuild
   inherits for free, and the redirect half of Q1/option B is built rather than preserved.
2. **There is no promotion badge.** The 2019 functional spec lists "Banner + Promotion
   Bage" on this page. No badge is present in the live markup. It is not built here.
3. **Offer fields are not rendered as fields.** Price, price basis, duration and the
   validity dates exist in the data model but the live page renders none of them as
   discrete elements — they appear only as prose inside the description. See Risk R-1.

---

## User Scenarios & Testing *(mandatory)*

### User Story 1 - A traveller browses and reads current offers (Priority: P1)

A prospective traveller arrives on the Specials page from the main navigation or a
newsletter. Below the banner and a short introduction, they scroll through current offers.
Each offer occupies a full-width band over its own image, and each gives them everything —
the name, what it applies to, and the complete description including rates, inclusions and
conditions. They do not click through to read more, because there is nothing behind the
band; the band *is* the offer. They page through to reach the rest.

**Why this priority**: This is the whole page and the whole line item. Reading and browsing
are one journey here, not two, because the detail is not on a separate page.

**Independent Test**: Load the Specials page on a site carrying migrated specials data.
Confirm four offers per page, each rendering banner image, name, meta row, full description
and enquire button; confirm pagination reaches the last offer; confirm expired offers are
absent.

**Acceptance Scenarios**:

1. **Given** a site with more than four valid offers, **When** a traveller opens the
   Specials page, **Then** four offers are shown and pagination reaches every remaining
   offer in the set.
2. **Given** a valid offer, **When** its band renders, **Then** it shows the offer's banner
   image as the band background, its name, its meta row, its **complete** description, and
   an enquire button.
3. **Given** an offer whose booking-validity end date has passed, **When** the page is
   loaded, **Then** that offer does not appear.
4. **Given** a site with no currently-valid offers, **When** the page is loaded, **Then** the
   banner, title and introduction still render and a clear message replaces the list.
5. **Given** a traveller on a phone, **When** they open the page, **Then** each band reflows
   to a single column, the background image treatment degrades legibly, and every control
   stays reachable by touch.

---

### User Story 2 - A traveller follows an offer to the product behind it (Priority: P2)

Reading an offer, the traveller wants to know about the lodge or the destination it applies
to. The meta row names each connected accommodation and destination, and the travel style
the offer belongs to. Accommodation and destination names open that item's detail overlay
in place; the travel style is a link to everything in that style.

**Why this priority**: It is what turns an offer into an enquiry, and it is how the offer
connects to the rest of the catalogue. It is P2 because the overlays it opens are built on
another line item.

**Independent Test**: On an offer with connections, activate each meta link and confirm the
correct destination — the right item's overlay for accommodation and destinations, the
right term archive for travel style.

**Acceptance Scenarios**:

1. **Given** an offer connected to accommodation, **When** its band renders, **Then** each
   connected accommodation is named in the meta row under an `Accommodation:` label and
   opens that accommodation's overlay.
2. **Given** an offer connected to destinations, **When** its band renders, **Then** each is
   named under a `Destinations:` label and opens that destination's overlay.
3. **Given** an offer classified by travel style, **When** its band renders, **Then** the
   style is named under a `Travel Style:` label and links to that style's archive.
4. **Given** an offer with no connections of a given kind, **When** its band renders,
   **Then** that label and its row segment are omitted entirely — no empty label, no
   trailing separator.
5. **Given** a connected item that has been unpublished or deleted, **When** the band
   renders, **Then** it is omitted silently rather than rendered as a dead link.

---

### User Story 3 - A traveller enquires about a specific offer (Priority: P2)

Having read an offer the traveller presses *"Enquire about this special"* on that band. The
enquiry surface opens already knowing which offer it is about, so they do not have to
describe it. Beneath the list, the "Like what you see? Let's start planning!" call to action
catches travellers who have read everything and not yet chosen.

**Why this priority**: The line item names CTAs explicitly and the site is enquiry-led. P2
rather than P1 because the form and modal behind the button are funded elsewhere.

**Independent Test**: Activate the enquire button on two different offers and confirm the
enquiry surface opens each time carrying that offer's name; confirm the button is reachable
and operable by keyboard alone.

**Acceptance Scenarios**:

1. **Given** any offer band, **When** a traveller activates its enquire button, **Then** the
   enquiry surface opens and identifies that offer by name.
2. **Given** two different offers, **When** each enquire button is used in turn, **Then**
   each carries its own offer's name — not the first one, and not the last rendered.
3. **Given** a traveller navigating by keyboard only, **When** they reach any enquire button
   or call to action, **Then** it shows a visible focus indicator and activates from the
   keyboard.

---

### User Story 4 - Someone follows an old link to an individual offer (Priority: P2)

A traveller clicks a link to `/special/{slug}/` — from an old newsletter, a search result,
or a bookmark. They arrive at the Specials page rather than an error.

**Amended 2026-09-17: this story is construction after all.** It was written believing Tour
Operator's "Disable Single" setting produced the redirect on its own. Verified by execution,
that setting is **inert for the offer post type** — the filter it is read on never fires for
offers — and on the post types it does reach it serves the **homepage at HTTP 200** rather
than redirecting. So the redirect is authored in `sd-enhancements` (FR-022), the setting is
left off (FR-021), and what this story asserts is that our redirect behaves correctly for
published and retired offers alike and that the anchors the rest of the site links to still
resolve. The sitemap remains configuration owned elsewhere (see FR-023).

**Why this priority**: These URLs exist and are indexed. Confirming the platform still does
the right thing costs almost nothing and prevents a visible regression at launch.

**Independent Test**: Request each individual offer URL on the rebuilt site and confirm a
single-hop permanent redirect to the landing page, with no redirect rule authored in the
theme or the plugin.

**Acceptance Scenarios**:

1. **Given** the rebuilt site, **When** any individual offer URL is requested, **Then** a
   permanent redirect to the Specials landing page is returned — a single hop, with no
   intermediate error and no page that returns success while displaying different content.
2. **Given** an offer that has been retired to draft, **When** its URL is requested, **Then**
   it redirects exactly as a published offer does, rather than erroring.
2a. **Given** the rebuilt site, **When** the **theme** is inspected, **Then** no redirect rule
   for individual offer URLs exists in it — the redirect lives in `sd-enhancements`, because
   by the deactivation test a redirect is behaviour.
2b. **Given** an editor, **When** they open or preview an offer in wp-admin, **Then** the
   redirect does not interfere.
3. **Given** a link of the form `/specials/#special-{slug}`, **When** it is followed,
   **Then** the browser lands on the Specials page scrolled to that offer's band.

---

### User Story 5 - An editor publishes and retires an offer (Priority: P3)

An editor creates an offer, writes its description, sets its booking-validity window and
attaches it to the accommodation and destinations it applies to. It appears on the page for
the life of that window and leaves when the window closes, without anyone remembering to
unpublish it. The page's banner image, title and introduction are editable through the
site's own settings.

Once the window has closed the offer also leaves the editor's own list of live offers — it
becomes a draft rather than sitting published and invisible — so what an editor sees as
published matches what a visitor can find.

**Why this priority**: An editorial workflow rather than a visitor journey. **Raised in
substance on 2026-09-17**: it was written expecting to *verify* a platform setting, and
verification found no mechanism at all — no hooks, no scheduler, and what exists wired for
tours only. Both halves are now built (FR-026, FR-026a), so this story carries real work.

**Independent Test**: Create one offer with a closed validity window and one with an open
one; confirm only the open one appears on the page, and that the closed one is `draft` after
a scheduled cycle while the open one stays published. Change the banner and introduction
through the site settings and confirm the change renders.

**Acceptance Scenarios**:

1. **Given** an offer whose validity window has closed, **When** the page is next rendered,
   **Then** the offer is absent — with no editor action having been taken, and regardless of
   whether it has yet been retired to draft.
1a. **Given** an offer whose validity window has closed, **When** the next scheduled cycle
   runs, **Then** its status is `draft`, and its description, connections and validity dates
   are unchanged — so extending the window and republishing restores it exactly.
1b. **Given** an editor changes an offer's validity end date, **When** they save, **Then** the
   retirement is rescheduled to the new date, and an offer with no end date is never retired.
2. **Given** an editor changes the banner image, title or introduction in the site settings,
   **When** the page is reloaded, **Then** the change renders without a template file edit.
3. **Given** an offer is created and attached to accommodation, **When** the page is
   reloaded, **Then** the offer appears in validity-start-date order with its meta row
   populated.

---

### Edge Cases

- **No valid offers at all.** The banner, title and introduction must still render, with a
  clear message in place of the list. Realistic: 37 of 42 offers are currently expired.
- **An offer with no banner image.** The band must fall back to a defined treatment that
  keeps the name and description legible — not a transparent band, not a collapsed one.
- **An offer with no end date, or a start date in the future.** No validity window means
  open-ended and shown; a start date not yet reached means not shown.
- **A very long offer name.** It wraps within the band rather than overflowing it.
- **A very long description.** The band grows; it does not clip, scroll internally, or push
  the enquire button out of view.
- **Description copy containing lists, links and emphasis.** Formatting is preserved, and
  authored markup cannot break out of the band or the page layout.
- **An offer with no connections at all.** The meta row is omitted in full.
- **The last page of pagination holding a single offer.** It renders as a full band, not a
  quarter-width remnant.
- **A page number past the end of the set.** A defined response, not an empty list under
  working pagination controls.
- **An individual offer URL for an offer that has since expired to draft.** Still redirects
  to the landing page rather than producing an error.
- **An offer retired while an editor has it open.** Retirement must not clobber an edit in
  progress or resurrect the offer on save.
- **An offer whose end date is moved forward after it was retired.** Republishing it must put
  it back on the page and cancel the stale retirement, not retire it again immediately.
- **Retirement running on a site where the scheduler has not fired for days.** Offers that
  expired in the gap must all retire when it next runs, not just the most recent — and the
  page must have been correct throughout regardless, because the read-time exclusion does not
  depend on the schedule.

---

## Requirements *(mandatory)*

### Functional Requirements

**Page furniture**

- **FR-001**: The Specials page MUST present a banner carrying the specials banner image and
  the page title, matching the banner treatment used by the other archive templates in this
  theme.
- **FR-002**: The page MUST present an introduction below the banner, at the shortened
  length used on live (approximately 40–50 words).
- **FR-003**: The banner image, page title and introduction MUST be editable through the
  site's existing Tour Operator settings, not through template files.
- **FR-004**: The page MUST present exactly one main landmark and a heading hierarchy that
  descends without skipping levels.

**The offer list**

- **FR-005**: The page MUST list **four offers per page**, matching live, and MUST paginate
  the remainder.
- **FR-006**: Offers MUST be presented as full-width bands stacked vertically — a list
  layout, not a grid.
- **FR-007**: Offers MUST be ordered by their booking-validity start date.
- **FR-008**: The list MUST include only offers that are currently valid, and MUST exclude
  offers whose booking-validity end date has passed.
- **FR-009**: Each band MUST use the offer's banner image as its background, filling the
  band's height.
- **FR-010**: Each band MUST show, in order: the offer name (centred), the meta row, the
  offer's full description, and the enquire button.
- **FR-011**: Each band MUST carry a stable anchor derived from the offer's slug, so that
  `/specials/#special-{slug}` resolves to that band.
- **FR-012**: The description MUST be the offer's **complete body copy**, not an excerpt or
  a truncation, with its authored formatting preserved.
- **FR-013**: When no valid offers exist, the page MUST render a clear empty-state message
  in place of the list while still rendering FR-001 to FR-003.

**The meta row**

- **FR-014**: The meta row MUST present connected accommodation under an `Accommodation:`
  label, connected destinations under a `Destinations:` label, and the offer's travel style
  under a `Travel Style:` label.
- **FR-015**: Accommodation and destination names MUST open that item's overlay in place;
  the travel style MUST link to that term's archive.
- **FR-016**: Any label whose connection set is empty MUST be omitted entirely, along with
  its separator.
- **FR-017**: A connection resolving to an unpublished or deleted item MUST be omitted
  silently.

**Calls to action**

- **FR-018**: Each band MUST carry an enquire button reading *"Enquire about this special"*,
  which opens the shared enquiry surface carrying that band's offer name.
- **FR-019**: The templates MUST NOT implement the enquiry form, its fields, its validation
  or its routing, nor the overlays opened by the meta row.
- **FR-020**: The page MUST present the "Like what you see? Let's start planning!" call to
  action below the list, reusing the call to action already built in this theme.

**Individual offer URLs**

- **FR-021**: Individual offer pages MUST NOT be publicly reachable. The platform's
  **"Disable Single" setting MUST be left off** — measured 2026-09-17, it is **inert for the
  offer post type**: the filter it is read on never fires for offers, so enabling it changes
  nothing, and on the post types it does reach it serves the **homepage at HTTP 200** rather
  than redirecting. A setting that implies a mechanism nobody is running is worse than one
  left off.
- **FR-022**: Every individual offer URL MUST return a single-hop permanent redirect to the
  Specials landing page, **authored in `sd-enhancements`** as a front-end redirect on the
  single-offer request. It MUST apply to draft and expired offers as well as published ones,
  MUST NOT affect the admin, previews, REST or feeds, and MUST derive the archive URL rather
  than hardcoding it. The theme MUST NOT author a redirect rule — by the deactivation test a
  redirect is behaviour, and it belongs in the plugin.
- **FR-023**: Individual offer URLs MUST NOT appear in the published sitemap. This is a
  **search-plugin configuration change, not template work** — it is owned by the
  deployment, redirects and launch-support line and tracked against F-03. This feature
  records the requirement and verifies it at hand-over; it does not implement it.
- **FR-024**: The theme MUST NOT ship a rendering template for an individual offer. The
  existing `single-special` scaffolding stub is removed as part of this work.

**Validity behaviour**

- **FR-025**: Offers MUST leave the page automatically when their validity window closes,
  with no editor action.
- **FR-026**: The **query** MUST exclude offers whose booking-validity end date has passed and
  offers whose start date has not yet been reached, at read time, in `sd-enhancements`. An
  absent bound means open-ended and MUST NOT be a reason to exclude. This is the mechanism
  FR-025 and FR-008 rest on, and it MUST be correct independently of any post-status change.
- **FR-026a**: Expired offers MUST additionally be **retired to `draft`** by a scheduled task
  in `sd-enhancements`, so the editor's own list reflects what is live. Retirement MUST be
  idempotent, MUST reschedule when an offer's validity dates change, MUST NOT touch offers
  with no end date, and MUST leave the offer's content and connections intact so it can be
  republished by extending its window. The theme MUST NOT implement any part of this.
- **FR-026b**: The platform's own expiration mechanism MUST NOT be relied on or revived.
  Measured 2026-09-17: `Post_Expiration` registers **no hooks**, has **no scheduler** behind
  it, and was only ever wired for tours — there is no offer equivalent. `tour-operator` is
  vendor code and MUST NOT be patched.
- **FR-027**: The page MUST NOT offer a visitor-facing view of past or expired offers.

**Presentation and access**

- **FR-028**: The page MUST reflow to a single column at phone widths with no horizontal
  scrolling of the page body, and every control reachable by touch.
- **FR-029**: Every interactive element MUST be reachable and operable by keyboard, with a
  visible focus indicator.
- **FR-030**: Text over a band's background image MUST meet the project's contrast standard
  at every viewport width, including where the image is light.
- **FR-031**: Every colour, spacing, typography and radius value MUST be expressed through
  the theme's existing design tokens. This feature introduces no new visual decisions.
- **FR-032**: The band treatment MUST stay consistent with the offer cards used by the
  specials carousels on other templates, so a given offer is recognisable wherever it
  appears.

### Key Entities

- **Special (offer)** — A time-limited travel offer. Carries: name, slug, banner image,
  featured image, full description, tagline, price and price basis, duration,
  booking-validity start and end dates, terms and conditions, gallery, a featured flag, and
  a flag suppressing its individual page. Classified by travel style and offer type.
  Connected to accommodation, tours, destinations, team members and blog posts.
  *Of these, the page renders: name, banner image, description, and the accommodation,
  destination and travel-style connections. The rest are carried by the data model and
  surface only inside the authored description — see Risk R-1.*
- **Specials page settings** — Banner image, title and introduction, plus the site-wide
  switch suppressing individual offer pages. Held in the platform's settings, not the theme.
- **Connected product** — An accommodation, tour or destination an offer applies to. The
  connection is bidirectional: the offer names the product, and the product's page shows the
  offer.

---

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Every currently-valid offer in the migrated dataset is reachable within the
  page's pagination — none missing, and **zero** expired offers shown.
- **SC-002**: A traveller can read any offer's complete description, including its rates and
  conditions, **without leaving the Specials page**.
- **SC-003**: Every offer band renders banner image, name, meta row, full description and
  enquire button — verified across the full set of migrated offers, with **zero** bands
  missing an element that has data behind it.
- **SC-004**: **Zero** empty labels, orphaned separators or placeholder regions across the
  full set of migrated offers.
- **SC-005**: Every individual offer URL returns a single permanent redirect to the landing
  page — **zero** URLs returning success while displaying different content, and **zero**
  redirect chains longer than one hop. Verified across published **and** draft offers, and
  with **zero** redirect rules authored in the theme.
- **SC-006**: **Zero** individual offer URLs appear in the published sitemap, closing F-03.
  *(Verified at hand-over; implemented on the deployment/redirects line.)*
- **SC-006a**: An offer whose validity window closes is absent from the page on the next
  render **and** is `draft` within one scheduled cycle — verified by closing a window on a
  test offer rather than by inspecting the schedule.
- **SC-007**: Every enquire button opens the enquiry surface carrying its own offer's name —
  verified on **every** offer on a page, not just the first.
- **SC-008**: Every `/specials/#special-{slug}` link used elsewhere on the site resolves to
  the correct band — **zero** broken anchors.
- **SC-009**: The page passes an automated accessibility check with **no critical or serious
  violations**, is fully operable by keyboard, and every band's text meets the project's
  contrast standard over its background image.
- **SC-010**: The page does not scroll horizontally at 320px viewport width and presents a
  single-column layout at phone widths.
- **SC-011**: The page renders correctly for a content editor as well as a visitor, with no
  invalid-content or missing-content warnings in the editing environment.
- **SC-012**: Side-by-side against the live page at desktop and phone widths, the rebuilt
  page matches it in structure, order and content — differences limited to the agreed light
  refresh of token values.

---

## Assumptions

Reasonable defaults recorded because the description did not settle them. Each is cheap to
reverse at planning time.

1. **The design is preserved, not redesigned.** The live page is the reference for structure
   and composition; the design system supplies token values only.
2. **The offer data contract is the platform's existing one.** No new fields are introduced.
3. **`travel_dates` is not part of the contract.** The plugin's JSON defines it; its actual
   field configuration does not implement it. Nothing here depends on it.
4. **Offer fields are not readable over the site's public data API.** Specials carry no
   registered meta; every field is read server-side at render. Any approach assuming
   API-readable offer fields returns nothing. *(Accepted 2026-08-13 — not a defect to fix
   here.)*
5. **Headings cannot carry bound offer data.** The platform's binding mechanism handles only
   paragraph, image and cover presentation. The offer name is composed accordingly.
6. **The platform's own `special-card` pattern is the starting point** for the band. It ships
   with the plugin and already carries the data bindings — adopted or deliberately
   overridden, not re-authored.
7. ~~**Expiration is a configured platform setting** that moves an expired offer to draft.
   Verified, not rebuilt.~~ **Withdrawn 2026-09-17 — measured false.** There is no working
   platform mechanism: no hooks registered, no scheduler available, and what exists was only
   ever wired for tours. Both the read-time exclusion and the retirement to draft are built in
   `sd-enhancements` — FR-026, FR-026a, FR-026b.
8. **Offer type and travel style are classifications, not visitor-facing filters** on this
   page. Faceted filtering is funded on the search line.
9. **The migrated dataset is the acceptance environment** — the development site with real
   content, not the local fixture site, which holds no specials.
10. **The enquiry modal and the accommodation/destination overlays exist** by acceptance, or
    a defined seam stands in their place. This feature does not block on them.
11. **`/specials/` remains the landing page path**, so existing links, anchors and the search
    index stay valid.
12. ~~**The redirect is the platform's.** Tour Operator's "Disable Single" setting produces
    it, exactly as it does on live today. Nothing is authored on our side.~~ **Withdrawn
    2026-09-17 — measured false.** The setting is read on a filter that never fires for the
    offer post type, so it is inert here; and where it does apply it serves the homepage at
    HTTP 200 rather than redirecting. The 301 on live is real but comes from somewhere outside
    these plugins and has not been identified. The redirect is authored in `sd-enhancements` —
    FR-022.

---

## Dependencies

| Dependency | State |
|---|---|
| Tour Operator + Tour Operator Special Offers at 2.2 | ✅ Active on the development site |
| Offer post type and classifications registered under unprefixed keys | ✅ Done on the development site |
| Migrated specials content | ✅ Present — 5 published, 37 expired drafts |
| The `special-card` pattern and offer data bindings shipped by the plugin | ✅ Available |
| Offer-expiration setting configured to retire expired offers | 🔴 **Does not exist.** Measured 2026-09-17: no hooks registered, no scheduler, tours only. Build both halves in `sd-enhancements` — FR-026, FR-026a |
| Tour Operator "Disable Single" setting | 🔴 **Inert for offers**, and a soft-404 where it applies. Leave it off; author the redirect in `sd-enhancements` — FR-021, FR-022 |
| Enquiry form and its Salesforce feed | ⚠️ Line 15 — consumed, not built here |
| Enquiry modal and the accommodation/destination overlays | ⚠️ Line 16 — consumed, not built here |
| Banner, breadcrumb, header, footer and CTA parts | ✅ Built in this theme |
| Sitemap excluding individual offer URLs | ⚠️ Search-plugin configuration, owned by the deployment/redirects line. Live still advertises all five despite the 301 (verified 2026-09-17). Verified at hand-over, not implemented here — FR-023 |
| Whether the 37 expired offers migrate | ⬜ Open (CC-14). Affects volume, not behaviour — Q2 settles the visitor-facing question either way |

---

## Risks

### R-1 — The offer's structured fields surface nowhere ✅ ruled, 2026-09-17

`price`, `price_type`, `duration`, `booking_validity_start`, `booking_validity_end`,
`tagline`, `terms_conditions` and `gallery` all exist on every offer. Live renders none of
them as discrete elements — rates and conditions appear only as prose an editor typed into
the description, and the terms-and-conditions field is not shown at all.

**Ruled: do not surface them. We are matching live.** This is not a register item, not a
deferred improvement and not a caveat to restate at planning or review. The offer's rendered
content is its name, banner image, description and connections; the remaining fields are
carried by the data model and deliberately unrendered.

Recorded here only so that a later reader who notices the populated fields knows it was a
decision rather than an omission.

### R-2 — Anchors must survive the rebuild 🟡

The site links to offers as `/specials/#special-{slug}` from navigation and from other
templates. If the rebuilt band derives its anchor differently, every one of those links
lands at the top of the page instead of the offer. SC-008 exists to catch this; the anchors
in use should be enumerated before the band is built.

### R-4 — Retirement writes to content 🟡 *(new 2026-09-17)*

Read-time exclusion (FR-026) is inert — it changes nothing and cannot corrupt anything.
Retirement to draft (FR-026a) is a **write to published content**, on a schedule, without an
editor present. The failure modes are ordinary but the blast radius is real: retiring an offer
that should have stayed live, retiring on a wrong or timezone-shifted date, or a rescheduling
bug that retires on every save.

Mitigated by keeping the two mechanisms strictly independent — the page is correct from
FR-026 alone, so FR-026a can be disabled or fixed without the visitor-facing behaviour
regressing — and by requiring retirement to be reversible: content, connections and dates are
left intact, so extending the window and republishing fully restores the offer. SC-006a tests
it by closing a real window rather than by inspecting the schedule.

### R-3 — Text legibility over arbitrary banner images 🟡

Each band's readability depends on an editor-supplied image. Live handles this with a fixed
treatment; the rebuild must carry an equivalent that holds at every viewport width, for
light images as well as dark. FR-030 and SC-009 cover it.

---

## Resolved Questions

### Q1 — Are individual offer pages public? → **B. No.**

Individual offer pages stay suppressed and their URLs redirect to the landing page. The
landing page carries the full offer detail, as it does today. Individual offer URLs are
removed from the sitemap. Recorded against **CC-06**.

**The answer to Q1 is unchanged; who delivers it is not.** The first pass recorded that live
already returns 301 → `/specials/`, and it does — but verification on 2026-09-17 established
that no Tour Operator setting produces that 301, so it is not behaviour the rebuild inherits.
The redirect is built in `sd-enhancements` (FR-022). Locating what produces it on live remains
worth doing before launch, so the rebuild does not stack two rules.

**Effect on this spec**: the original User Story 2 ("a traveller reads one offer in full" on
its own page) is withdrawn. Its content is absorbed into User Story 1, and a new User Story
4 covers the redirect and sitemap. FR-021 to FR-024 replace the individual-page
requirements.

### Q2 — Does the landing page show past offers? → **A. No.**

Automatic exclusion only. Expired offers vanish; there is no current/past toggle and no
unlisted archive of past offers. Matches the platform's existing retirement behaviour and
the live site. FR-027 records it.
