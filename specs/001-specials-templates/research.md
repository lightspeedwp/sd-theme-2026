# Phase 0 Research: Specials Landing Page

**Feature**: 001-specials-templates | **Date**: 2026-09-17 | **Spec**: [spec.md](./spec.md)

All findings are measured against the code as installed in this workspace. Citations are
`path:line`. Where a finding contradicts the spec, the spec is annotated and the
contradiction is carried forward as an open item — the spec is not silently amended.

---

## R-01 — Post type, archive slug and template routing

**Decision**: Build the landing page as `templates/archive-special.html`, a thin shell
delegating to a new `patterns/template-archive-special.php`, matching the convention every
other archive in this theme already uses.

**Rationale**: The post type key is `special` (not `lsx-to-special`), registered at
`wp-content/plugins/to-specials/classes/class-to-specials-admin.php:60-63` with
`public: true`, `has_archive: 'specials'`, and `rewrite: ['slug' => 'special',
'with_front' => false]` (`includes/post-types/config-special.php:34-46`). So `/specials/`
resolves to the post-type archive and `archive-special.html` is the correct template —
Assumption 11 holds and the existing filename is right. `templates/archive-tour.html` is
four lines delegating to `patterns/template-archive-tour.php`, which carries the `<main>`
landmark and the whole composition; `archive-special.html` is currently an unconverted
scaffolding stub (3-column grid, 12 per page, `post-excerpt`) and is replaced wholesale.

**Alternatives considered**: Composing directly in the `.html` template — rejected, it
breaks the theme's one established convention and puts display copy outside the PHP files
where `esc_html_e()` can reach it.

---

## R-02 — The plugin's `special-card` pattern is a reference, not a base

**Decision**: Do **not** adopt `to-specials`' `special-card` pattern. Author the band as a
new theme pattern, taking only its **binding keys** from the plugin pattern.

**Rationale**: Spec Assumption 6 expects the plugin pattern to be "the starting point …
adopted or deliberately overridden". Measured, it is the wrong artifact in every dimension
this feature cares about (`wp-content/plugins/to-specials/patterns/special-card.php`):

| Plugin pattern does | This feature requires |
|---|---|
| Boxed grid card, `viewportWidth: 400`, `is-style-shadow-sm` | Full-width band, list layout (FR-006) |
| `core/post-excerpt` | Complete body copy (FR-012) |
| Icon rows rendering `price`, `duration`, `booking_validity_end` | None of these rendered (R-1, ruled) |
| `post-featured-image` with `linkTarget: "_blank"` | Banner image as the band ground (FR-009) |
| "View Special" button bound to `core/post-data` → `link` | Enquire button, no single page (FR-018/021) |
| `fontSize: "large"`/`"medium"`, `var:preset|color|primary-700` | Numeric slugs only; `primary-*` is not in this palette (Constitution II) |

Adopting it would violate the ruling in Risk R-1 and the token rules in one step. What it
*does* supply, verified, is the exact meta keys and the binding source name — carried into
R-04.

**Alternatives considered**: Overriding the plugin pattern in place — rejected, it is
vendor code (AGENTS.md: "don't patch in place").

---

## R-03 — The band's section style already exists and is unused

**Decision**: The band consumes the existing `styles/sections/cards/special-card.json`. This
feature is the first consumer; extend that file rather than adding a new style or a CSS file.

**Rationale**: `styles/sections/cards/special-card.json` is written, registered and
documented against the measured live band (`.post-type-archive-lsx-to-special #primary
.lsx-to-archive-item`), and `patterns/template-single-accommodation.php:57-68` records that
"the tile it needs does not exist … Building that card is the specials archive's work". Its
`css` field already defines `.special-card__media`, `.special-card__body` (absolutely
positioned over the image) and `.special-card__badge`. The markup this feature authors must
use those exact class names or the style is inert.

Two consequences:

1. The band is an **image + absolutely-positioned body**, not a `core/cover`. The style was
   written for that structure. This also sidesteps the fact that a bound `core/cover` would
   need `lsx/post-meta` on `url`, and keeps the scrim under the theme's control.
2. `.special-card__badge` is defined but **must not be used**. Spec correction 2 records that
   no promotion badge exists on live and none is built here. The rule stays in the style file
   for the accommodation single's badge work; this pattern emits no badge element.

**Alternatives considered**: `core/cover` with `useFeaturedImage` — rejected; the offer's
band image is the `banner_image_id` meta, not the featured image, and the existing style
does not target a cover's generated wrappers.

---

## R-04 — How each rendered field is bound

**Decision**: Use Tour Operator's `lsx/*` binding sources throughout. No new binding source
is needed for the fields this page renders.

**Rationale**: measured in
`wp-content/plugins/tour-operator/includes/classes/blocks/class-bindings.php`.

| Element | Mechanism | Evidence |
|---|---|---|
| Offer name | `core/post-title` — **not** a binding | Assumption 5; `lsx/post-meta` supports only `core/image`, `core/cover`, `core/paragraph` (`class-bindings.php:245,251`) |
| Band image | `core/image` bound `lsx/post-meta` → `banner_image_id` | Precedent: `patterns/destination-banner.php` |
| Description | `core/post-content` | FR-012 requires complete body copy, formatting preserved |
| Accommodation | `core/paragraph` bound `lsx/post-connection` → `accommodation_to_special` | connection keys generated at `to-specials/includes/metaboxes/config-special.php:203-228` |
| Destinations | `core/paragraph` bound `lsx/post-connection` → `destination_to_special` | same |
| Travel Style | `core/post-terms {"term":"travel-style"}` | taxonomy applies to `special` (`tour-operator/includes/taxonomies/config-travel-style.php:17-24`) |
| Labels (`Accommodation:` etc.) | `prefix` / `prefixBold` block attributes | Tour Operator extension; precedent `patterns/card-tour-compact.php` |

Connections are **post meta holding related post IDs**, not a taxonomy — so FR-016 (omit an
empty label) and FR-017 (omit an unpublished item silently) are the binding source's
behaviour, not the template's. Both must be verified against real data rather than assumed;
they are the first two checks in [quickstart.md](./quickstart.md).

**Bindings do not preview in the editor** (`sd-enhancements-2026/docs/blocks-and-bindings.md`
§1, rule 2). The authored fallback content is what an editor sees. Fallback text is therefore
chosen so SC-011 (no invalid- or missing-content warnings in the editor) still passes.

**Alternatives considered**: Reading offer fields over the REST API — impossible and already
ruled out by Assumption 4; confirmed here: `register_meta()` in the Tour Operator stack
registers only `featured` and `lsx_to_hide_from_listings`
(`tour-operator/includes/classes/admin/class-setup.php:130-150`). `booking_validity_start`
and `booking_validity_end` are plain CMB2 post meta
(`to-specials/includes/metaboxes/config-special.php:97-107`).

---

## R-05 — Four per page is already implemented; ordering and validity filtering are not

**Decision**: The template's `core/query` runs with `inherit: true`. Per-page needs nothing.
**Ordering (FR-007) and validity exclusion (FR-008) are new `sd-enhancements` work** and are
added to the existing `limit_specials_archive()` filter, not to the theme.

**Rationale**: `sd-enhancements-2026/modules/queries.php:177-187` already sets
`posts_per_page` to `SPECIALS_PER_PAGE = 4` (`:32`) on the specials post-type archive main
query. It sets neither `orderby` nor any meta filter. By the deactivation test (AGENTS.md)
query shaping is behaviour, so both belong in that module beside the existing rule. The
theme contributes only `inherit: true` so the main query is the one that renders.

**FR-007 is a meta-value sort on a CMB2 `text_date_timestamp` field**, so `meta_key` +
`orderby: meta_value_num` on `booking_validity_start`, with a defined position for offers
that have no start date (edge case: "no validity window means open-ended and shown").

**Alternatives considered**: `core/query` attributes alone — cannot express a meta sort or a
meta date comparison. A theme-side `pre_get_posts` — forbidden by the deactivation test.

---

## R-06 — Automatic expiration does not run, and never covered specials ⚠️ contradicts the spec

**Status: verified by execution on the local install, 2026-09-17.** Not inferred from source.

**Finding**: Spec Dependencies records the offer-expiration setting as "✅ Configured —
verify, do not rebuild", and FR-025/FR-026 rest on it. Three independent measurements say
there is nothing there to verify.

```
save_post_tour         has_action=false
save_post_special      has_action=false
lsx_to_expire_tour     has_action=false
lsx_to_expire_special  has_action=false
Post_Expiration class exists: true
instantiated in classes[]: post_expiration
Action Scheduler available: false
```

1. `lsx\admin\Post_Expiration` **is** instantiated — it is live in Tour Operator's
   `classes['post_expiration']`. But it registers **no hooks**: both `add_action()` calls in
   its constructor are commented out
   (`tour-operator/includes/classes/admin/class-post-expiration.php:26-27`), and `has_action()`
   confirms zero callbacks on every hook it would use. Nothing schedules, nothing expires.
2. **Action Scheduler is not available** — `as_schedule_single_action()` is undefined. So even
   with those two lines uncommented, `maybe_schedule_tour_expiration()` would fail at the
   scheduling call.
3. Even fully working, it would not have covered this feature. The hooks are
   `save_post_tour` and `lsx_to_expire_tour` — **tour only**. There is no `special` equivalent
   anywhere in either plugin.

`wp cron event list` carries no expiry event of any kind, and the only other matches for
`expire_post` in the plugin tree are the metabox field *declarations*
(`to-specials/includes/metaboxes/config-special.php`, `tour-operator/includes/metaboxes/config-tour.php`)
— the checkbox exists in the editor and is read by nothing.

**Decision**: Build both halves in `sd-enhancements`. Meet FR-008 and SC-001 with a **validity
filter in the query**, added to the existing `limit_specials_archive()`; and retire expired
offers to `draft` with a **scheduled sweep** (FR-026a). Do not revive Tour Operator's cron:
`tour-operator` is vendor code and AGENTS.md forbids patching it in place.

**Rationale**: the filter makes the page correct at read time regardless of post status, needs
no scheduler, and cannot drift. It is not "the templates implementing their own expiration
mechanism" in the sense FR-026 forbids — no post status is changed and nothing is written;
the list is filtered when it is read, which is ordinary query shaping and is where FR-007's
ordering has to go anyway.

**Carried forward**: FR-025's visitor-facing wording still holds — an offer leaves the page
when its window closes, with no editor action. FR-026's premise is **false as installed** and
its instruction to "verify it is configured and carry the setting across" cannot be executed.
The dependency table's "✅ Configured" needs correcting on LS-2033.

**Retirement to draft is also built** — added at Zared's direction on 2026-09-17, after this
finding. It cleans the editor's list as well as the page, and it lands in `sd-enhancements` as
a scheduled sweep covering `special`. It is deliberately a **second, independent** mechanism:
the page is correct from the read-time filter alone, so retirement is never load-bearing for
what a visitor sees, and it can be disabled or fixed without a visitor-facing regression. That
independence is what makes a scheduled write to published content acceptable to add here.
Spec FR-026a and Risk R-4; [contracts/retirement-contract.md](./contracts/retirement-contract.md).

---

## R-07 — "Disable Single" never reaches `special`, and does not redirect ⚠️ contradicts the spec

**Status: verified by execution on the local install, 2026-09-17.** An earlier reading of the
source concluded this setting produces a 404. That was wrong — it is worse than a 404, and
for `special` the setting does nothing at all. Both corrections come from running it.

**Finding**: FR-021, FR-022, Assumption 12 and SC-005 all rest on Tour Operator's "Disable
Single" setting producing a single-hop 301 to `/specials/` with nothing authored on our side.
It does neither half of that.

### The setting never applies to `special`

The setting is `lsx_to_settings['special_disable_single']`, consumed by
`Admin::disable_archives_singles()` (`tour-operator/includes/classes/admin/class-admin.php:75-90`)
on the filter `content_model_post_type_args`. That filter is applied in exactly one place —
the content-models runtime
(`tour-operator/plugins/content-models/includes/runtime/class-content-model.php:223`), which
registers post types from JSON content models.

`special` is not registered that way. It is registered by a direct `register_post_type()` call
in `to-specials/classes/class-to-specials-admin.php:60-63`, bypassing the runtime entirely.
Instrumenting the filter on a real request shows which post types it actually reaches:

```
FILTER FIRED slug=accommodation
FILTER FIRED slug=destination
FILTER FIRED slug=tour
```

`special` is absent. Measured end to end: with `special_disable_single = 1` set and rewrite
rules flushed, `get_post_type_object('special')->publicly_queryable` is still **`true`**, and
`/special/{slug}/` still returns **200 rendering the offer's own page**. The setting is inert
for this post type. Enabling it — which FR-021 instructs — changes nothing.

### And where it does apply, it serves the homepage at 200

Tested against `tour`, which the filter does reach. With `tour_disable_single = 1`:

| | Before | After |
|---|---|---|
| `publicly_queryable` | `true` | `false` |
| `/tour/{slug}/` status | `200` | **`200`** |
| Redirects followed | 0 | **0** |
| `<title>` | the tour | `Southern Destinations` |
| `<body class>` | single tour | `home blog` |

It is a **soft-404**: the URL returns success while displaying entirely different content.
That is the exact condition SC-005 forbids in as many words — "zero URLs returning success
while displaying different content". The setting's own description in the admin
(`tour-operator/includes/constants/settings-fields.php:122`) says "you will be redirected to
the homepage", which confirms the homepage is the intent; there is simply no redirect, just a
resolved-away query.

Neither plugin registers any `template_redirect`, `template_include` or `wp_redirect` for a
disabled single. The per-post `disable_single` field
(`to-specials/includes/metaboxes/config-special.php:29-33`) is read by nothing.

**Conclusion**: the 301 measured on live on 2026-09-17 is real, but it is produced by
something outside these two plugins — `sd-lsx-child`, a Yoast redirect, or a server rule. No
combination of Tour Operator settings reproduces it, so it will not survive the rebuild.

### Decision

**Author the redirect in `sd-enhancements`.** Under the deactivation test a redirect is
unambiguously plugin behaviour, and there is no platform mechanism to lean on. Concretely: a
`template_redirect` that catches a `special` single request and issues a single-hop 301 to the
specials archive.

This makes **FR-022 wrong as written** — "this feature MUST NOT author a redirect rule … for
these URLs" was written on the belief that the platform already did it, and the platform does
not. FR-021's instruction to enable the setting is also moot: it is inert here, and enabling
it on a post type the filter *does* reach would produce the soft-404 SC-005 forbids. Both need
amending on LS-2033. SC-005's own wording — a single permanent redirect, zero soft-404s, one
hop — is the correct target and is unchanged; only the sentence about who produces it is wrong.

**Still worth locating the live redirect's source** before shipping ours, so the rebuild does
not end up with two rules stacked at launch, and so the deployment/redirects line knows
whether it is inheriting one. That is a check, not a blocker.

**Alternatives considered**: enabling the setting and accepting the homepage-at-200 — rejected,
it fails SC-005 explicitly and is a real SEO regression against 5 indexed URLs. Patching
`tour-operator` so `special` flows through the content-models runtime — rejected, it is vendor
code, it would change the behaviour of a shared plugin for every site running it, and it would
still only deliver the soft-404.

---

## R-08 — The enquire button's seam

**Decision**: The enquire button is a `core/button` whose `url` is `#to-modal-enquiry`,
matching the existing `parts/modal-enquiry.html` part. Carrying the offer's name into the
form is a **defined seam**, specified in [contracts/](./contracts/) and consumed by lines
15/16 — this feature emits the identifier and does not build the form.

**Rationale**: `sd-enhancements-2026/modules/enquiry.php:96-137` filters
`render_block_core/button`, matches an href of the form `#to-modal-{slug}`, checks the slug
against a template part, and asks Tour Operator's `Modals` class to print that modal. So the
trigger contract already exists and this feature simply uses it. What the module does **not**
do is carry any per-offer payload — it inspects the rendered href and returns the button
unchanged. FR-018 requires the offer's name to reach the form, and SC-007 requires it to be
*that band's* offer on every band, not the first or the last.

The identifier therefore has to be emitted per band in the markup, which the pattern can do,
and read by the modal, which it cannot. The contract fixes the attribute so both halves can
be built independently and FR-019's boundary holds.

**Alternatives considered**: One modal per offer — rejected; it multiplies a shared surface
funded on another line and breaks with pagination.

---

## R-09 — Page furniture: banner, intro, CTA, and the `<main>` landmark

**Decision**: Compose the banner inside `patterns/template-archive-special.php` (not via
`hero-page-banner`), source the banner image, title and intro from Tour Operator settings,
and close the page with the existing `sd-theme-2026/why-choose-sd` pattern.

**Rationale**:

- `patterns/hero-page-banner.php` binds `useFeaturedImage` and is scoped to `page`. An
  archive has no featured image, and `template-archive-tour.php` already records why the
  archive banner is composed in the template pattern instead. Follow that precedent.
- FR-003 requires the banner image, title and intro to be editable through Tour Operator
  settings rather than template files. `sd/to-setting` (`key`) exists for exactly this
  (`sd-enhancements-2026/docs/blocks-and-bindings.md` §1), and `sd/banner` composes banner
  strings. The specific setting keys are read at implementation time from the same settings
  registry — they are not invented here.
- FR-020's "Like what you see? Let's start planning!" band is the archive variant of the
  enquiry CTA. `patterns/cta-not-sure-where-to-go.php` is described as "the enquiry band live
  runs beneath its archives". Its heading copy is measured per page, so the implementation
  confirms which of the three `cta-*` patterns carries this page's wording before reusing one
  — FR-020 says reuse, so no fourth CTA pattern is authored.
- FR-004 / Constitution "exactly one `<main>`": `archive-tour.html` carries no `<main>`; the
  pattern does. `archive-special.html` currently carries one **in the template**. When the
  composition moves into the pattern the landmark moves with it, or the page ships two.

---

## R-10 — Anchors (Risk R-2)

**Decision**: Emit `id="special-{slug}"` on each band's outer group, and **enumerate the
anchors actually linked from elsewhere in the theme before the band is built**.

**Rationale**: FR-011 and SC-008 require `/specials/#special-{slug}` to keep resolving. The
slug is per-offer, so the anchor cannot be an authored `anchor` attribute in a pattern that
renders once per post — it has to be derived at render time from the queried post. That is a
render-time concern on a `core/group` inside a `post-template`: behaviour, so
`sd-enhancements` again, and it needs a mechanism decision at implementation time rather than
an assumption now. The enumeration is cheap (`grep` for `#special-` across `patterns/`,
`parts/` and the migrated menu) and turns SC-008 from a hope into a list.

---

## Resolved: no NEEDS CLARIFICATION remains in Technical Context

Both items that were open at the first pass — the redirect and the expiration — are now
**settled by measurement rather than by reading**, and both resolve the same way: the platform
provides nothing, and `sd-enhancements` provides it instead. Neither blocks the construction
of the page. Both make the spec wrong in specific, named sentences (FR-021, FR-022, FR-026 and
the dependency table), which is a correction for LS-2033 rather than new scope — the behaviour
behind a funded element is funded.
