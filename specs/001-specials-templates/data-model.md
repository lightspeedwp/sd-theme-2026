# Phase 1 Data Model: Specials Landing Page

**Feature**: 001-specials-templates | **Date**: 2026-09-17

This feature introduces **no new data**. Every field below already exists in the Tour
Operator stack; this document records which of them the page reads, how each is reached from
a block, and which are deliberately carried but never rendered.

Field sources are `wp-content/plugins/to-specials/includes/metaboxes/config-special.php` and
the content-model manifest `wp-content/plugins/to-specials/post-types/special.json`.

---

## Entity: Special (offer)

Post type key `special`. Archive `/specials/`. Single rewrite base `special`.
Registered at `to-specials/classes/class-to-specials-admin.php:60-63`.

### Rendered on this page

| Field | Storage | Reached by | Requirement |
|---|---|---|---|
| Name | `post_title` | `core/post-title` (dynamic block) | FR-010 |
| Slug | `post_name` | render-time, for the band anchor | FR-011 |
| Banner image | meta `banner_image_id` (attachment ID) | `core/image` ← `lsx/post-meta` | FR-009 |
| Description | `post_content` | `core/post-content` | FR-012 |
| Accommodation | meta `accommodation_to_special` (post IDs) | `core/paragraph` ← `lsx/post-connection` | FR-014 |
| Destinations | meta `destination_to_special` (post IDs) | `core/paragraph` ← `lsx/post-connection` | FR-014 |
| Travel style | taxonomy `travel-style` | `core/post-terms` | FR-014 |

### Read but not rendered — used to shape the query only

| Field | Storage | Used for |
|---|---|---|
| Booking validity start | meta `booking_validity_start` (CMB2 `text_date_timestamp`) | Sort order (FR-007); "not yet started" exclusion |
| Booking validity end | meta `booking_validity_end` (same) | Expired exclusion (FR-008, SC-001) **and** the retirement trigger (FR-026a) |

Both consumers of `booking_validity_end` must compare it the same way, in the site's timezone.
If the filter and the sweep disagree on the boundary, an offer can be filtered off the page on
one date and retired on another — visible to an editor as an offer that is published but
missing, which is exactly the state retirement exists to prevent.

Neither is registered with `register_meta()` and neither is in REST
(`tour-operator/includes/classes/admin/class-setup.php:130-150` registers only `featured` and
`lsx_to_hide_from_listings`). They are readable **server-side only** — Assumption 4, confirmed.

### Carried by the data model, deliberately unrendered

`price`, `price_type`, `duration`, `tagline`, `terms_conditions`, `gallery`, `featured`,
`expire_post`, `travel_dates`, `team_to_special`, `tour_to_special`, `post_to_special`, and
the taxonomy `special-type`.

**Ruled 2026-09-17 (spec Risk R-1): do not surface these.** Rates, inclusions and conditions
reach the visitor as prose inside the description, exactly as on live. This table exists so a
later reader who notices the populated fields knows it was a decision. Note that the
plugin's own `special-card` pattern *does* render `price`, `duration` and
`booking_validity_end` as icon rows — which is precisely why that pattern is not adopted
(research R-02).

`travel_dates` is declared in the JSON manifest but has no field configuration behind it
(Assumption 3). Nothing here depends on it.

### Validity states

Derived, not stored. `now` is the render time; an absent bound is open.

| State | Condition | On the page | Post status |
|---|---|---|---|
| Valid | `start <= now` (or absent) **and** (`end >= now` or absent) | Listed, ordered by `start` | `publish` |
| Not yet started | `start > now` | Absent | `publish` — retirement never touches it |
| Expired | `end < now` | Absent (FR-008) | `publish` until the sweep runs, then `draft` (FR-026a) |
| Open-ended | `end` absent | Listed | `publish`, never retired |

The "expired but not yet swept" row is the one that matters: an offer is absent from the page
**before** its status changes, because the read-time filter does not depend on the schedule.
That ordering is deliberate and is what makes retirement safe to add — see
[contracts/retirement-contract.md](./contracts/retirement-contract.md).

The spec originally expected the platform to move an expired offer to `draft`. **It does
not**: `Post_Expiration` registers no hooks, has no scheduler behind it, and was only ever
wired for `tour` (research R-06, verified by execution). So retirement is built in
`sd-enhancements` (FR-026a) — but as a **second, independent** mechanism. The query filter
alone keeps SC-001 true and is tested on its own, before retirement exists.

---

## Entity: Connected product

An accommodation, tour or destination an offer applies to. Stored on the **offer** as a
multiselect of post IDs (`{post_type}_to_special`, generated in a loop at
`to-specials/includes/metaboxes/config-special.php:203-228`). Not a taxonomy, and not a
first-class relationship object — the reverse direction is resolved by query.

| Rule | Behaviour |
|---|---|
| Empty set | Label and its row segment omitted entirely, with no separator (FR-016) |
| Item unpublished or deleted | That item omitted silently; the rest of the set still renders (FR-017) |
| Rendering | Each item is a link; accommodation and destination links open that item's overlay (FR-015) |

Both omission rules are the **binding source's** behaviour, not the template's. They are
asserted, not assumed — see [quickstart.md](./quickstart.md) checks 1 and 2.

The overlay behind an accommodation or destination link is funded on line 16 and is not built
here (FR-019). This feature emits the links; if the overlay is not yet present, the link
degrades to the item's permalink, which is a working fallback rather than a defect.

---

## Entity: Specials page settings

Site-wide, held in Tour Operator's settings option `lsx_to_settings` — not in the theme, and
not per-post (FR-003).

| Setting | Key | Used for |
|---|---|---|
| Page banner image | Tour Operator settings registry | FR-001 |
| Page title | same | FR-001 |
| Page introduction | same | FR-002 |
| Disable single | `special_disable_single` (`tour-operator/includes/constants/settings-fields.php:118-123`) | FR-021 |

`special_disable_single` is **inert for this post type**. It is read on the filter
`content_model_post_type_args`, which fires only for `accommodation`, `destination` and `tour`
— `special` is registered by a direct `register_post_type()` call that bypasses the
content-models runtime. With it set, `special`'s `publicly_queryable` stays `true` and the
single still serves at 200. On a post type the filter does reach it sets
`publicly_queryable = false`, which serves the homepage at 200 rather than redirecting. Leave
it off; the redirect is authored in `sd-enhancements` instead — research R-07 and
[contracts/redirect-contract.md](./contracts/redirect-contract.md). The exact keys for banner, title and intro are read from the
settings registry at implementation time and reached with the `sd/to-setting` binding source;
they are not guessed here.

---

## What this feature adds to the data layer

No post type, no taxonomy, no meta key, no option, no `register_meta()` call, no REST field.
Every addition is behaviour, and all of it lands in `sd-enhancements`: query shaping, one
render-time anchor, the single-offer redirect, and the retirement sweep — see
[contracts/](./contracts/).

Retirement is the only thing in this feature that **changes stored data**, and it changes
exactly one column: `post_status`, `publish` → `draft`. No meta is written, no content is
rewritten, and nothing is deleted — so republishing an offer restores it whole.
