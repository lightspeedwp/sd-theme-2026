# Changelog

All notable changes to the Southern Destinations 2026 theme are documented here.
Format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/);
this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- **The country and region singles are built, and the destination sections are now shared
  partials.** `templates/single-country.html` and `templates/single-region.html` were Tour
  Operator's own defaults, copied in wholesale by commit de58c9a — a Yoast breadcrumb strip
  on `primary`, a gradient cover and the plugin's section set, none of it live's design.
  Both are now four lines referencing `patterns/template-single-country.php` and
  `patterns/template-single-region.php`, and they state live's country/region branch
  explicitly instead of leaving it to the shelves to hide themselves. *(LS-2033)*

  **The branch, as live's PHP has it.** `sd_lsx_to_destination_single_content_bottom()`
  asks `lsx_to_item_has_children()` and renders one of two section sets — country → gallery
  → regions → tours, region → gallery → accommodation → specials → tours → reviews
  (`sd-lsx-child/includes/layout.php:166-215`). The two new templates carry one arm each:
  Single Country keeps the regions shelf and drops accommodation; Single Region keeps
  accommodation and drops regions.

  **Both are assignable, not routes.** Tour Operator registers `single-country` and
  `single-region` with `post_types: [ destination ]`
  (`includes/classes/blocks/class-templates.php:89-99`), so they appear in the Template
  panel on a destination and an author picks them per post. Every destination on live
  carries `destination_attribute: default`, so `templates/single-destination.html` remains
  what WordPress resolves, and it still serves a destination of either kind on its own.
  A theme file of the same slug replaces the registered one — `get_block_templates()`
  drops any registered template that has a theme file
  (`wp-includes/block-template-utils.php:1231`) — so the plugin's versions no longer
  appear. Both are declared in `theme.json` `customTemplates` scoped to `destination`;
  without that they were offered on every post type.

  **Single Country answers the accommodation deviation.** The route template's
  accommodation shelf resolves through `accommodation_to_destination`, and a country
  carries that meta too — Botswana lists 93 — so it renders there where live hides it.
  That was flagged on LS-2033; assigning Single Country removes it, because the section is
  not in the file.

  **Seven shared section partials, `Inserter: false`** —
  `patterns/destination-{banner,summary,gallery,regions,accommodation,tours,reviews}.php`.
  All three templates `require` them, so there is one copy of every section and no third
  place to keep in step. `patterns/template-single-destination.php` was rewired to the same
  partials and its markup is unchanged: the pre- and post-refactor renders are identical
  once whitespace is normalised (33,649 → 33,527 bytes, indentation only). `require`, not
  a nested `<!-- wp:pattern /-->`, which is dropped on front-end render while still
  resolving under a WP-CLI `do_blocks()` test.

  **Verified** on local 2026-09-03, on this branch's base: all ten patterns register;
  composition is destination = 7 sections, country = 6 without accommodation, region = 6
  without regions, each with exactly one `<main>` and one `<h1>`;
  `phpcs --standard=WordPress patterns/` silent; both templates resolve as `source: theme`
  with `post_types: [destination]` and the plugin's are filtered out. Seeding a child
  destination under Botswana renders the regions band on Single Country and not on Single
  Region. **The accommodation and tour shelves and the gallery could not be exercised** —
  local has no accommodation fixtures and the six destinations carry no gallery meta or
  real connections, so every shelf self-hides. Their markup is byte-identical to the
  destination single's, which does render them on dev.

  **Still missing: the specials shelf.** Live's region branch renders `#special` between
  the accommodation and tour shelves. `styles/sections/cards/special-card.json` is written
  and registered and no pattern uses it yet; when that card lands it becomes a
  `destination-specials` partial required between `destination-accommodation` and
  `destination-tours`. **Connected reviews are kept on Single Country**, where live's
  country branch shows none — the two differences asked for were the regions and
  accommodation shelves, and the band is correct content either way. Both on LS-2033.

- **The "Why choose Southern Destinations" band now closes every static page.**
  `patterns/template-page-full.php` — the body of the "Page (Full Width, No Title)"
  template — `require`s `patterns/why-choose-sd.php` at the foot of its `<main>`, which is
  the follow-up that pattern's own notes were waiting on. On live the band is not page
  content: the child theme emits `.footer-cta-section` beneath every inner page, which is
  why it appears verbatim on `/about-us/social-responsibility/` and
  `/about-us/connect-with-us/` while being absent from both pages' stored content. It is a
  template concern, so it is declared once rather than pasted into five page bodies where
  the sixth would be forgotten. *(LS-2033)*

  **This template and not `templates/page.html`.** The full-width no-title template is the
  static-page template and nothing else — About Us and its three children, plus Contact Us,
  are its only consumers. The default page template still serves Privacy Policy, Terms,
  Sitemap, Thank You and the two enquiry pages, none of which close on a marketing CTA.
  Decision 2026-09-03.

  `require`, not a nested `<!-- wp:pattern /-->`: a pattern reference inside a pattern is
  dropped on front-end render while still resolving under a WP-CLI `do_blocks()` test.
  `templates/front-page.html` can use the reference because it is a template file, not a
  pattern. Verified by rendering the registered pattern — one `<main>`, one `<section>`, the
  band present. `<main>` carries no bottom padding, so the band's own preset 70 is the only
  gap between the last content section and the footer.

- **The four Tour Operator modals are theme files now** — `parts/modal-tour.html`,
  `parts/modal-accommodation.html`, `parts/modal-destination.html` and
  `parts/modal-enquiry.html`, registered in `theme.json` against Tour Operator's
  `modals` template-part area. *(LS-2530, estimate line 17)*

  **The three post-type modals are the compact cards.** Each mirrors the pattern the
  carousels on the singles already use — `card-tour-compact`, `card-accommodation-compact`,
  `card-destination-compact` — so a preview modal and the card that links to it are the
  same object. `is-style-listing-card-compact` already carries live's modal typography
  exactly: centred, `neutral-700` heading, `brand-600` link hover, measured from
  `.modal .modal-content .lsx-to-modal-content-area` in `sd-lsx-child/assets/css/custom.css`
  on 2026-09-01. Nothing new had to be styled for the panel interior.

  Two departures from the card, both deliberate:

  - **The title is an `<h2>`, not an `<h4>`.** `sd-enhancements`' ModalA11y names each
    dialog from its first heading, and AGENTS.md requires the first heading in a modal be
    an `h2`. Live is an `h4` inside a modal whose real `.modal-title` is
    `display: none` — an accessibility defect, not a design decision. The visual size is
    unchanged: `styles/sections/cards/listing-card-compact.json` styles `elements.heading`,
    which covers every level.
  - **No `sdLinkTo: "post"`.** A whole-tile link is a carousel affordance; in a dialog the
    title link is the way out, as on live.

  Live's modals also carry an Envira gallery slider that is **empty on every modal on
  every page sampled** — 17 on `/tour/best-of-southern-africa/`, 1 on
  `/accommodation/singita-lebombo-lodge/`, and `lsx-to-modal-thumb` never renders at all.
  So live's modals show no image. The compact card does, which is an improvement on live
  rather than a port of it.

  **`parts/modal-enquiry.html` carries Gravity Form 1.** Live prints two modals per CPT
  single, both with `id="lsx-enquire-modal"` — GF 1 first, GF 13 second — so Bootstrap
  only ever opens GF 1 and GF 13 has never been reachable. Consolidating on GF 1 is
  Zared's decision, 2026-09-01; the GF 13 and GF 14 Salesforce feeds are being reconciled
  with the client separately. The heading reuses `is-style-section-title`, which is
  already live's `#lsx-enquire-modal .modal-title` device — uppercase, `neutral-700`, with
  the 80×2px `accent-500` rule.

- **The accommodation archive is built.** `templates/archive-accommodation.html` was a
  generic three-up Query Loop over the `accommodation` post type; it now references
  `patterns/template-archive-accommodation.php`, which reproduces the live accommodation
  landing page section for section — banner, the two-panel Best Price Guarantee and
  specials band, the grid of accommodation-type tiles, closing on the brands shelf and Why
  Choose. Measured from `/accommodation/` on 2026-08-31 against `custom.css:1449, 3957,
  4000` and the `accommodation` block of `_lsx-to_settings` on dev. *(LS-2019, item 9)*

  **The grid lists types, not properties.** Live's tiles are the ten accommodation types —
  Safari Lodges, Luxury Tented Camps, Boutique Hotels and so on — so this is a
  `core/terms-query` over `accommodation-type` carrying `sd-featured-terms-query`, the
  exact device the tours archive runs over `travel-style`. It needed no plugin change:
  `SD\Enhancements\TermMeta` already registers the `featured` checkbox against both
  taxonomies and `Queries::arm_featured_terms()` reads the taxonomy off the query block.
  Dev already holds the flags — twelve of the twenty-six `accommodation-type` terms are
  ticked. The card is `patterns/card-media-overlay-term.php`, unchanged.

  ⚠️ **Two data items, not markup.** *Luxury Trains* is featured with a count of 0, so
  `hideEmpty` drops it — live drops it too. *Africa's Finest* is featured, has 19
  accommodations and carries **no `thumbnail` term meta**, so Tour Operator's featured-image
  filter falls through to `render_placeholder_image()` and the grid renders eleven tiles
  with one grey placeholder where live renders ten. Giving that term a Featured Image or
  unticking Featured on it fixes it; both are one field on the term edit screen.

  **The intro band is not the one the other two archives run.** This page has no safari
  expert panel (`grep -c safari-expert-box` over the rendered page returns 0). In its place
  live runs `#accommodation-cta-header`, the child theme's `partials/accommodation-search.php`
  (port inventory K-17): the Best Price Guarantee panel — the same one the accommodation
  single carries, written out again because that file is a template and not a section
  pattern — beside a specials panel linking to the specials archive. Live wraps the whole
  specials panel in one `<a>`; `SD\Enhancements\GroupLink` extends `core/group` only, so the
  link is on the heading and the badge is decorative. **`assets/images/current-accommodation-bg.jpg`**
  and **`assets/images/specials-badge.svg`** are ported from the child theme as theme
  chrome, not seeded into the media library. `specials-badge.svg` is the 169px archive
  plate and is **not** `special-badge.svg`, the 218px shadowed plate the single carries.

  **One knowing departure from live:** the brands shelf keeps its heading. Live's widget
  renders titleless on this page; the pattern included here is the homepage's, whose
  heading reads "We only work with Africa's finest" — a five-up row of unlabelled logos is
  not a section. Live's tiles also point at SearchWP facet URLs
  (`/search/accommodation/safari+lodges/`) where these point at the term archive, as the
  tours archive's tiles already do; routing them at the facet search is a decision for the
  search line.

  Verified on local against a seeded fixture of six terms and five properties: the featured
  filter admitted three featured-with-thumbnail terms and the thumbnail-less one (rendering
  TO's placeholder) and excluded both the unfeatured term and the empty one; three-up grid;
  tiles link to `accommodation-type` term archives with the whole-card overlay; both cover
  photographs and the badge resolve; the specials heading links to the specials archive;
  one `<main>`, h1 → h2 → h3 with no skips; `phpcs --standard=WordPress patterns/` silent.
  Fixture deleted. Not visually confirmed in a browser — the structural check is on the
  rendered HTML.

- **The accommodation single is built.** `templates/single-accommodation.html` was a
  verbatim copy of Tour Operator 2.2's default template; it now references
  `patterns/template-single-accommodation.php`, which reproduces the live accommodation
  page section for section — banner, the summary band pairing the property copy with the
  rating box and the Best Price Guarantee panel, the gallery, the units band, the tours and
  review shelves, closing on the "Inspired by this property?" band and Why Choose. Measured
  from `/accommodation/chitwa-chitwa-private-game-lodge/` on 2026-08-31 against
  `sd-lsx-child/includes/layout.php:117-155` and `includes/functions.php:531, 683`.
  *(LS-2019, item 9)*

  **The rating box is three wrapper classes and no theme PHP.**
  `lsx-accommodation-price-box-wrapper` removes the framed box when the property has no
  rating, no price band and no connected special; `lsx-accommodation-price-facts-wrapper`
  removes the left column on its own; `lsx-special-to-accommodation-wrapper` removes the
  badge. The first two are registered by `SD\Enhancements\Wrappers` against Tour Operator's
  `lsx_to_multi_field_wrappers` filter. The stars are `lsx/post-meta` on `rating`, which Tour
  Operator's own `Accommodation::rating()` filter answers with five star images filled down
  to the stored value; the band is `sd/post-meta` on `price_rating`, whose `price-band`
  format returns null for the stored literal `none`. `centered-rating` / `centered-special`
  are deliberately not ported — a `justifyContent: center` does that job with no PHP.

  **The units band is one heading where live has up to five.** Live loops the five unit
  types and emits a section per type; Tour Operator 2.2's `render_units_block()` returns
  every unit in one pass, so there is no per-type band to head. "Rooms" is live's heading on
  the measured page. The card is `patterns/accommodation-unit.php`, which carries the
  `lsx/accommodation-units` binding and is what repeats, and the band is a three-up grid
  rather than a carousel because Tour Operator's slider extends `core/query` and
  `core/terms-query` only.

  **`patterns/cta-inspired-by-this-property.php`** is the third sibling of the same enquiry
  band, differing from `cta-tell-us-your-trip-ideas.php` in exactly three lines: the slug,
  the anchor and the heading. **`assets/images/guarantee-bg.jpg`** and
  **`assets/images/special-badge.svg`** are ported from the child theme as theme chrome, not
  seeded into the media library.

  Deliberately **not** here, each for a reason recorded in the pattern's docblock: the
  **specials shelf** (needs the special card that `styles/sections/cards/special-card.json`
  is written for and no pattern uses yet — the specials archive's work, as on the
  destination single); the **map, facilities and videos bands** and the
  **related-accommodation shelf** (in Tour Operator's default template and in live's hidden
  spy nav, but live renders none of them); the **breadcrumb bar** and the **`.more-text`
  read-more collapse** with its gold drop cap (`sd-enhancements` work by the deactivation
  test); and **live's rotating banner image**, which LSX Banners picks at random from eleven
  site-wide photographs — the property's own banner image then its featured image is used
  instead, as on the tour and destination singles.

  The units band sits on white rather than live's `#f7f5f2`: live's card is `#ece9e3` on
  that ground and the palette resolves both to `neutral-200`, so keeping live's band colour
  would make the cards vanish into it. The contrast relationship is preserved; the two
  absolute values are not.

  Verified on local against a seeded fixture: all four gallery images render, three unit
  cards repeat with titles and descriptions substituted and the empty description hidden,
  the star row draws 4/5, the price band prints `$$$`, the tour and review shelves appear
  when connections exist and vanish when they do not, and emptying `rating` and setting
  `price_rating` to `none` removes the whole framed box. One `<main>`, no heading-level
  skips, `phpcs --standard=WordPress` silent. Fixture deleted.

- **The destination single is built.** `templates/single-destination.html` was a verbatim
  copy of Tour Operator 2.2's default template; it now references
  `patterns/template-single-destination.php`, which reproduces the live destination page
  section for section — banner, the summary band pairing the destination copy and the
  safari expert with the cluster map, the gallery, and the region, accommodation, tour and
  review shelves, closing on the "Not sure where to go?" band and Why Choose. Measured from
  `/destination/botswana/` (a country) and `/destination/botswana/chobe-national-park/`
  (a region) on 2026-08-31 against `sd-lsx-child/includes/layout.php:166-215` and
  `includes/template-tags.php:520-600`. *(LS-2019, item 9)*

  **One template, not the country/region pair live branches into.**
  `sd_lsx_to_destination_single_content_bottom()` asks `lsx_to_item_has_children()` and
  renders either gallery → regions → tours or gallery → accommodation → specials → tours →
  reviews. In blocks that branch is not a conditional: every shelf carries an
  `lsx-…-wrapper` class and Tour Operator's `Query_Loop::maybe_hide_varitaion()` removes
  the band, heading included, when the query behind it is empty — and it checks the
  `regions` key against `lsx_to_item_has_children()` by name, which is the same test live
  branches on. So both live orders come out of one template with no theme PHP. Tour
  Operator's `single-region` and `single-country` templates are *assignable* per post, not
  routes; nothing on live assigns one, and the two files commit `de58c9a` copied in are
  left untouched for now.

  Section headings that interpolate the post title — "Popular Travel Destinations in
  Botswana", "Botswana Tours to Inspire You", "Our Favourite {region} Accommodations" —
  are `sd/post-field` bindings using its `prefix`/`suffix` args, which is the case that
  source was written for. The theme owns those strings and their translation.

  The regions shelf carries `patterns/card-media-overlay.php` and the tours shelf
  `patterns/card-tour-compact.php`, so regions read as the same object as the countries on
  `/destinations/` and a tour looks the same wherever it is shelved.

  Four things are deliberately **not** here. The **specials shelf** needs a card that does
  not exist yet — `styles/sections/cards/special-card.json` is registered and no pattern
  uses it — and belongs with the specials archive. The **`.more-text` read-more collapse**
  and its gold drop cap are a JavaScript behaviour over post content, so `sd-enhancements`
  work, exactly as the tour single decided. The **breadcrumb bar** is a filter over a
  third-party plugin's trail, the same call already recorded twice. **Travel Information**
  is commented out on live (`layout.php:169`) and has not rendered in years, despite every
  field being populated.

  Only the summary band is tinted; the sections below it sit on white. That is live, and
  it is deliberately not the tinted/light stripe the tour single runs — the tour's gallery
  genuinely is tinted on live and the destination's is not.

  ⚠️ **The map column renders empty on Tour Operator 2.2, and the fault is upstream.**
  `lsx_to_map()` (`tour-operator/includes/template-tags/maps.php:66`) opens by reading the
  `{post_id}_location` transient, and when it finds one it builds `$map` and then executes
  a bare `return;` at line 233 — discarding the markup and ignoring `$echo`. The
  `return $before . $map . $after` that honours `$echo` sits below that block and is
  unreachable whenever the transient is warm, which it always is: `render_map_block()`
  calls `lsx_to_has_map()` first and that function's last act is to `set_transient()`. So
  the google branch of TO's map binding returns null for every caller. Measured on local
  2026-08-31 against a seeded Botswana: `has_map=1 enabled=1 transient=array maplen=0`.
  The blocks are correct and stay as authored — when the upstream `return;` is fixed, or
  `sd-enhancements` answers TO's own `lsx_to_map_override` filter, the map appears with no
  change here.

- **The destinations landing page closes with the Why Choose and enquiry bands.**
  `patterns/template-archive-destination.php` now `require`s `patterns/why-choose-sd.php`
  and `patterns/cta-not-sure-where-to-go.php` beneath the tile grid — the same pair, in the
  same order, that the tours archive closes with, so the two Tour Operator archives end the
  same way. Authored on dev 2026-08-28 and carried back here. `<main>` drops its bottom
  padding with them: the CTA band brings its own, and a padding on the wrapper showed as a
  strip of page ground under a full-bleed section. *(LS-2019)*

  ⚠️ **The dev override held a stale inline copy of the CTA, and the maintained pattern is
  required instead.** Inserting a pattern in the Site Editor expands it, so the override
  carried whatever was registered at insert time: `+1 646-906-8113` where the pattern carries
  the toll-free `+1-844-292-8240`, the two offices as `core/columns` rather than a centred
  flex row, and a primary-600 glyph and number rather than neutral-700. Requiring the file
  means this page shows the same CTA as every other page that uses it. Do not re-inline it.

- **The tour archive is built.** `templates/archive-tour.html` was a stub Query Loop over
  the `tour` post type; it now references `patterns/template-archive-tour.php`, which
  reproduces the live tours landing page — the photographic banner, the tinted intro band
  pairing the archive description with the safari expert panel, the tile grid, and the
  Why Choose and enquiry bands beneath it. Measured from `/tours/` on 2026-08-28.
  *(LS-2019, item 9)*

  **The grid lists travel styles, not tours.** Live's tours archive is ten tiles reading
  "Safari Honeymoons", "Family Safaris", "Gorilla Trekking" and so on, each clicking
  through to that term's archive; the tours themselves live a level down. So this is a
  `core/terms-query` over `travel-style`, not a Query Loop, and it carries
  `sd-featured-terms-query` so `sd-enhancements` restricts it to the terms flagged
  `featured` — which on dev are exactly live's ten. Without that flag the grid would also
  render "Top 10 Safari Tours", the mega-menu curation term, whose thumbnail is the same
  attachment as Luxury Big Five Safaris.

  Banner image, title, tagline and description are the `tour` block of `_lsx-to_settings`
  read on dev, so they are the values live renders rather than a transcription of the
  page. The tagline is stored upper case and is set in sentence case here, matching the
  destinations banner.

  **Three columns, where live runs two** — decided 2026-08-28, so every Tour Operator
  archive shares one grid and one tile shape. Ten tiles at three columns leave a row of
  one; that is the accepted trade.

- **`patterns/card-media-overlay-term.php`** — the term twin of
  `patterns/card-media-overlay.php`, for a `core/term-template`. Same
  `is-style-media-overlay-card` styling, so the two archives stay one design;
  `core/term-name` replaces `core/post-title` and the whole tile links to the term
  through `sdLinkTo: "term"`.

  The image is `core/post-featured-image`, which looks wrong and is not: Tour Operator
  filters that block and, given a `termId` in context, swaps in the term's `thumbnail`
  meta wrapped in `get_term_link()` with the term name as `alt`, emitting the same
  `<figure style="aspect-ratio:…">` core does. That branch is reachable only because
  `SD\Enhancements\Compat::declare_term_context_on_featured_image()` adds the context
  keys TO reads but never declared — the same shim the homepage brands shelf depends on.
  `sd/term-image` does not fit: its `metaKey` allow-list covers `sd_thumbnail` /
  `sd_thumbnail_color`, and travel styles carry TO's `thumbnail`.

- **The tour single is built.** `templates/single-tour.html` was a verbatim copy of Tour
  Operator 2.2's default template; it now references
  `patterns/template-single-tour.php`, which reproduces the live tour page section for
  section — banner, the summary band, tour highlights, the gallery, the WETU map, the
  connected review carousel, the "Tell us your trip ideas" band, "Other tours you might
  like", and Why Choose with the Trustpilot score. Measured from
  `/tour/botswana-victoria-falls-safari/` and `/tour/best-of-southern-africa/` on
  2026-08-28 against `sd-lsx-child/includes/layout.php` and
  `.github/reports/live-site-audit-2026-08-12.md` §3.3. *(LS-2019, items 9.2 and 9.3)*

  Four things the TO default carries are deliberately **not** here. The **sticky section
  menu** is markup live emits and then switches off (`.single .lsx-to-navigation {
  display: none !important }`) — porting it would be adding a component, not preserving
  one. The **breadcrumb bar** is a filter over a third-party plugin's trail and belongs
  to `sd-enhancements`, the same call `patterns/template-archive-destination.php`
  already recorded. The **price includes/excludes panel** is Tour Operator's composition,
  not SD's — live's tour single has none and no SD tour populates the fields. The
  **accommodation modals** belong with the accommodation single.

  Every optional band carries an `lsx-<field>-wrapper` class, so Tour Operator removes it
  — heading included — when the field, gallery, itinerary or connected query behind it is
  empty. No conditional logic entered the theme. Verified on this install: a tour with no
  reviews and no related tours renders neither section; adding a `review_to_tour` and a
  `tour_to_tour` connection brings both back.

- **`patterns/itinerary-stay.php`** — one row of the summary's numbered itinerary spine,
  and the unit Tour Operator's `lsx/tour-itinerary` binding repeats. The number is a CSS
  counter rather than a bound value, because `render_itinerary_block()` passes a row index
  into `build_itinerary_field()` and never uses it; a counter also renumbers correctly
  when a row is hidden. Marker, number and dashed spine are in
  `assets/styles/core-group.css` — all three need `content:`, which a block-style `css`
  field drops.

  ⚠️ It renders **"Day 1"**, not live's **"2 Nights"**. Live merges consecutive days that
  share a lodge and labels the merged row with its night count;
  `SD\Enhancements\Itinerary::collapse()` already implements that and is waiting on the
  upstream `lsx_to_itinerary_items` filter (tour-operator#1293, LS-2531). Nothing in the
  theme changes when it lands — the plugin rewrites the same field. Live's
  `.itinerary-country` line has no equivalent in TO 2.2 either and is not reproduced;
  it came from `lsx_to_itinerary_country()`, a Tour Operator tag the child theme
  redefined (port-inventory M-09). *(LS-2019, item 9.4)*

- **`patterns/card-review-quote.php`** and `styles/sections/cards/review-quote-card.json`
  — the full-bleed review slide the carousels on every Tour Operator single carry. Live
  sets white type straight onto the photograph with no overlay, which holds only while
  every connected review happens to carry a dark image; this uses the same neutral-900
  scrim as `styles/sections/hero-banner.json`. Live's two links to the same place — an
  inline `…/` and a `.moretag` whose label exists only as a CSS pseudo-element, i.e. a
  link with no accessible text — become one labelled `core/read-more`. *(LS-2019)*

- **`patterns/cta-tell-us-your-trip-ideas.php`** — the default heading of live's
  `sd_call_info_section()`, and the second of the four variants LS-2014 item 4.6 names.
  It is `patterns/cta-not-sure-where-to-go.php` with one string changed, which is what
  the old five-branch body-class conditional becomes in a block theme. ⚠️ The two must
  stay in step; only the heading may differ. *(LS-2019)*

- **`styles/sections/highlights-list.json`** — the tour highlights list. The field is a
  WYSIWYG arriving through a block binding as raw `<ul>` markup, so every rule is a
  descendant selector. Note the shape this forces: a `<ul>` inside a `<p>` is not
  parseable HTML, so the browser closes the paragraph first and the list ends up a
  *sibling* of the bound block. The wrapping group is the only stable hook, which is why
  the style is registered against `core/group`. The gold check marker and the two-column
  run are in `assets/styles/core-group.css` — a `css` field drops `content:` rules
  outright and unwraps `@media` into unconditional ones. *(LS-2019)*

- **`assets/styles/core-post-featured-image.css`** — two rules Tour Operator's term-image
  `<figure>` never gets from core. TO builds that wrapper with
  `get_block_wrapper_attributes()` from inside a `render_block` filter, so the classes it
  returns are the *enclosing* block's; in a term template it emits
  `<figure style="aspect-ratio:1" class="wp-block-term-template">` and nothing in
  `wp-includes/blocks/post-featured-image/style.css` reaches it. Restores
  `a { display: block; height: 100% }` and adds `object-fit: cover`, keyed off the leaked
  class so the rules stop matching the day TO emits the right one. Both symptoms it fixes
  are under Fixed below. *(LS-2019, item 9)*

### Changed

- **The five enquiry CTAs open the modal instead of linking to `/contact/`.**
  `patterns/safari-expert.php`, `cta-not-sure-where-to-go.php`,
  `cta-tell-us-your-trip-ideas.php`, `cta-inspired-by-this-property.php` and
  `homepage-lets-make-it-happen.php` now carry `href="#to-modal-modal-enquiry"`.
  *(LS-2530)*

  They stay plain `core/button`s. `lsx-tour-operator/modal-button` writes its `className`
  onto the outer `wp-block-buttons` wrapper, where `is-style-fill` and
  `is-style-accent-cta` cannot reach it, and those variations are registered for
  `core/button` in any case. `sd-enhancements`' Enquiry module registers the modal off the
  href instead, so the theme declares the intent and the plugin supplies the behaviour.

  The comments in `safari-expert.php` and `cta-not-sure-where-to-go.php` said this CTA
  opens Gravity Form 13. It renders GF 13 into the DOM; it opens GF 1. Corrected, with the
  measurement.

- **`style.css` carries the dialog chrome.** Tour Operator 2.2 prints its modals into
  `wp_footer` as a bare `<dialog class="wp-block-hm-popup">` — outside the block system, so
  there is no block to hang `wp_enqueue_block_style()` on. `tour-operator-style` enqueues
  at priority 1 and the theme's sheet at 10, so these rules land after it without
  `!important`. Live's measured 590px panel width (Tour Operator defaults to the theme's
  900px `contentSize`); the white gutter Tour Operator paints around the panel removed so
  the card's image meets the edge; a `neutral-400` close button with a real focus ring,
  where upstream has none; and a `prefers-reduced-motion` guard on transitions that had
  none.

- **The accommodation card is a panel with a full meta block, carried back from dev.**
  `patterns/card-accommodation-compact.php` was restructured in the Site Editor on the dev
  single-destination template and is imported here: an explicit `neutral-200` ground with an
  inline `shadow|200`, an even spacing|30 body in place of the 40/30/60/30 that left room for
  a read-more the carousel hides, a Meta group at spacing|10 holding the price band, travel
  styles, brand, type, connected destination and star rating, an excerpt closing on `/..`,
  and `sdLinkTo: "post"` so the whole tile is the target. The same device as the tour card
  and the post grid card. *(LS-2019)*

  Three departures from the editor's markup, each recorded in the file: `var:preset|spacing|0`
  became a plain `0` (there is no `0` member in `spacingSizes`, so the token resolves to
  nothing and the declaration is dropped); the Type row's one-off `primary-500` link colour
  is gone; and the rating stars are restyled in CSS rather than by an attribute, because the
  markup is Tour Operator's, not ours.

- **Every link in the three compact cards is `neutral-800`, `brand-600` on hover, no
  underline.** `styles/sections/cards/listing-card-compact.json`'s `elements.link` moves from
  `neutral-700`, and the per-block `primary-500` the editor had put on the accommodation
  card's Type row is dropped — between them they had the four link rows on one card at three
  different colours. Title, brand, type, travel style and connected destination now read as
  one thing. The linked title therefore sits a step darker than the `neutral-700` on
  `elements.heading`, which stays as the fallback for placements where the title is not a
  link. *(LS-2019)*

- **The accommodation rating renders as brand-500 Phosphor stars.** `lsx/post-meta` on
  `rating` does not return a number: Tour Operator's `lsx_to_accommodation_rating()`
  (`includes/classes/legacy/class-accommodation.php:179`) filters it into five
  `<figure class="wp-block-image">` wrappers around 20px PNGs from its own plugin directory,
  which are raster images of a particular gold and cannot be recoloured by anything in
  `theme.json`. So `assets/styles/core-group.css` hides the PNG and repaints each star as a
  **mask** over a `brand-500` ground — Phosphor's `star-fill` for a whole star, Phosphor's
  regular `star` for an empty one, whose outer contours are identical so the two sit on the
  same centres at the same size. One colour token paints both, which is what "brand-500 fill
  and border, brand-500 border" reduces to. *(LS-2019)*

  Full and empty are told apart by the PNG filename (`img[src$="rating-star-full.png"]`),
  lifted to the `<figure>` with `:has()` — Tour Operator gives the two figures identical
  classes. If it ever renames or re-formats those files the masks stop matching and the PNGs
  come back, which is the right way for this to fail. It is enqueued CSS rather than the
  section style's `css` field for one reason only: a variation's `css` is emitted in the
  document head, so a relative `url()` would resolve against the page. The masks are
  therefore inline `data:` URIs and carry no hex.

  Two empty paragraphs the row leaves behind are hidden by `& p:empty` in the section style —
  one where the HTML parser splits the bound `<p>` in front of the `<div class="rating-stars">`
  TO emits *inside* it, and the Rating Authority paragraph on a property whose `rating_type`
  is empty or "Unspecified". Both were zero-width flex items still taking a gap either side.

- **The whole compact-card family lifts on hover, not just the tour card.** The lift in
  `assets/styles/core-group.css` was scoped to `.lsx-tour-related-tour-query`, the
  post-template class the related-tours shelf carries — so the same card missed the lift on
  the destination single, whose shelf is `.lsx-tour-related-destination-query`. The class is
  per-shelf and the card is on four templates, so the selector is now the section style
  itself. 4px rise, 200 → 300 on the shadow scale, `:focus-within` matched, the rise dropped
  under `prefers-reduced-motion` while the shadow still grows — all unchanged. *(LS-2019)*

  **Qualified by `.sd-has-link`.** A rise plus a deeper shadow is an affordance, and one
  member of the family is not a link: `patterns/accommodation-unit.php`, the repeated room
  tile on the accommodation single, wears this section style so a unit reads as the same
  object as the tiles around it. `sd-has-link` is the class `SD\Enhancements\GroupLink` adds
  once it has resolved an `sdLinkTo` into an overlay anchor — the hook that module's own
  docblock nominates — so the lift lands on exactly the cards that are clickable, without a
  hand-rolled opt-out class.

- **The destination card is structured like the tour and accommodation cards, and now carries
  meta.** `patterns/card-destination-compact.php` was the odd one of the three: a bare tint
  with a title, an excerpt and a read-more, and no meta at all. It is now the same panel —
  `shadow|200`, an even spacing|30 body, a Meta group at spacing|10, `sdLinkTo: "post"`, the
  read-more dropped and the excerpt closing on `/..`. *(LS-2019)*

  **Which meta, measured over the 107 published destinations on dev 2026-09-01.** A
  destination's own fields are almost all travel-information prose — `climate`, `visa`,
  `health`, `banking`, `cuisine`, `dress`, `electricity`, `transport`, eleven or twelve
  country records each — and belong to the single's travel-information band, not a tile.
  What is short, present and useful is the hierarchy and the taxonomies: Continent
  (`continent`, 2 of 107), Country (`post_parent`, 97 of 107 — every region), Regions
  (`post_children`, 10 of 107 — every country) and Travel Styles (`travel-style`, 13 of 107).
  Country and Regions are never both filled, so one row shows per card and it is the useful
  one either way. `location` is on 107 of 107 and is the cluster map's lat/long, not a row;
  `spoken_languages`, which TO's own `parts/fast-facts-destination.html` carries, is on none.

  ⚠️ **The two `facts-*-wrapper` classes on those rows are load-bearing, not styling hooks.**
  Tour Operator's `Query_Loop::maybe_hide_variation()` (`class-query-loop.php:79`) filters
  `render_block` on `core/group` *and* `core/paragraph`, reads a `(lsx|facts)-(.*?)-wrapper`
  class off the block and returns an empty string when the field behind it is empty. Drop
  them and both rows render on every card, failing two ways that CSS cannot reach: TO's
  `render_paragraph_prefix_block()` prepends the `prefix` with no test on the bound value, so
  an empty `post_children` renders a bare "**Regions:**" that is indistinguishable from a real
  one-word value to a selector; and `post_parent` calls `prep_links()` on
  `wp_get_post_parent_id()` without checking it, so `get_permalink( 0 )` falls through to the
  global post and a country renders "Country: Botswana" linking to itself. Verified locally
  against post 65904, 2026-09-01. The `country-query` wrapper removes that row before it
  renders, which is what makes the row safe to author.

- **The Single Tour banner is shorter, bottom-aligned and unscrimmed.**
  `patterns/template-single-tour.php`'s cover drops from 608px to 360px, moves its content
  from `center center` to `bottom center`, takes spacing|40 top and bottom, and sets
  `dimRatio` to 0. Carried back from the dev DB override, edited there 2026-08-28.
  *(LS-2019)*

  ⚠️ **`dimRatio: 0` removes the scrim entirely, and the banner type is white.**
  `styles/sections/hero-banner.json` paints the scrim as
  `background-color: color-mix(in srgb, neutral-900 45%, transparent) !important` on
  `.wp-block-cover__background` — but core gives that span `opacity: 0` for
  `has-background-dim-0`, so the 45% wash is gone and `is-style-hero-banner`'s `base` text
  colour now sits directly on the photograph. The previous `dimRatio: 100` was not a black
  banner: it made the span fully opaque so the style's authored 45% showed at strength. To
  put the scrim back, that one attribute goes back to 100 — nothing else changes.

- **The Single Tour tagline is medium, not semi-bold.** 500 rather than 600, with an explicit
  `normal` font style. Set on dev 2026-08-28; authored as `var:custom|font-weight|medium`
  rather than the editor's raw `500`, which `core/paragraph` can carry because it is static.
  *(LS-2019)*

- **The related-tours shelf runs 15 deep and excludes the tour being viewed.** `perPage` 9 →
  15 and `excludeCurrent: true` on the Query Loop in
  `patterns/template-single-tour.php`. Three tiles still show at a time; the count is how
  far the carousel runs. *(LS-2019)*

- **The tour card is a panel, and it lifts on hover like the post grid card.**
  `patterns/card-tour-compact.php` was restructured in the Site Editor on dev and is carried
  back here: a `neutral-100` ground with an inline `shadow|200`, a 3/2 crop in place of
  16/9, an even spacing|30 panel in place of 40/30/60/30, `sdLinkTo: "post"` so the whole
  tile is the target, and a duration row, an excerpt and connected-destination *parents*
  added beside the title and travel styles. *(LS-2019)*

  The hover is the post grid card's, unchanged — a 4px rise and 200 → 300 on the shadow
  scale, `:focus-within` matched, the rise dropped under `prefers-reduced-motion` while the
  shadow still grows. It lives in `assets/styles/core-group.css` because the block-style
  `css` field strips `:hover` and mis-compiles `@media`, and it carries `!important` on the
  shadow for the same reason the post grid card's does: the resting value is an inline style,
  which no selector outranks.

  **Scoped to `.lsx-tour-related-tour-query`**, the post-template class the shelf carries,
  not to `.is-style-listing-card-compact` — that section style is shared with the
  accommodation and destination compact cards, which have no resting shadow, so a bare class
  selector would pop a shadow-300 in from nothing on two cards nobody asked to change. The
  tour card is referenced from that one shelf and nowhere else. Verified in the dev DOM
  2026-08-31: the `<ul>` renders `columns-3 has-native-responsive-grid
  lsx-tour-related-tour-query …` and the card `<div>` inside it renders
  `is-style-listing-card-compact … sd-has-link`, so the selector matches; the shelf's
  `is-style-slider-frame` already opens the Slick clip box vertically, so the rise is not
  sheared.

- **The post grid card's corners are square.** The `border-radius|100` on all four corners of
  the root group in `patterns/card-post-grid.php` is removed; the `shadow|200` stays.
  *(LS-2019)*

  ⚠️ **The dev front-page DB override (post 65895) still carries the radius**, as a
  four-corner `topLeft`/`topRight`/`bottomLeft`/`bottomRight` object on its inline copy of
  this card. The homepage carousel will keep rendering rounded tiles until the theme is
  deployed to dev and that override is reconciled. Same for the single-tour override (post
  65924) and every change above it. → `wp-db-override-reconciliation`


- **The safari expert panel is restructured, and the eyebrow and name are authored twice.**
  `patterns/safari-expert.php`, from Zared's `archive-destination` edit on dev 2026-08-28.
  Below 992px the eyebrow and the consultant's name sit beside the portrait in a new nowrap
  "Expert Identity" row; at 992px and up they sit at the top of "Expert Detail" above the
  actions, and the row beside the portrait collapses to the portrait alone. Block Visibility's
  screen-size control picks the copy — `hideOnScreenSize.large` on one, `.medium` + `.small`
  on the other — and the two sets are mutually exclusive and cover every width, so exactly
  one pair exists at any viewport and never zero.

  The duplicate headings are not an accessibility fault: the plugin hides with
  `display: none !important` inside a `@media` block, which removes the subtree from the
  accessibility tree, so a screen reader is offered one eyebrow and one name. Verified on the
  rendered local page 2026-08-28 — one `block-visibility-hide-large-screen` and one
  `block-visibility-hide-medium-screen block-visibility-hide-small-screen` inside the panel.

  With it: the card's corner goes from radius 200 to radius 0; the portrait goes from
  116 × 116 with `scale: cover` to 126px square via `aspectRatio`; the eyebrow becomes an
  `h2` where it had been a paragraph; Call Us and Send an Email each take half the detail
  column (`layout.selfStretch: fill` on both, `dimensions.width` at the `100` preset on the
  button); the Call Us trigger takes the heading face at semi-bold, uppercased; and the phone
  glyph becomes Phosphor's **filled** mark — the only filled copy in the theme, where
  `header.php`, `homepage-dream-trip.php`, `homepage-lets-make-it-happen.php` and
  `cta-not-sure-where-to-go.php` all keep the outline.

  This file is required by `template-archive-destination.php`,
  `template-archive-tour.php` and `template-single-tour.php`, so all three change together —
  Zared's call, taken over duplicating the pattern for one page. All three verified 200 on
  local with the panel rendering.

  ⚠️ **Two things the edit dropped are dropped here too, and both are one line to restore.**
  The consultant's name is no longer a link (`isLink` off on both `core/post-title` blocks;
  the portrait keeps its link, so the single is still reachable), and the portrait's 2px
  `base` ring is gone — the editor left a `border-width` behind with no `border-style` beside
  it, which draws nothing, so the dead declaration is not carried.

- **The Call Us label is uppercased in CSS, not in the string.** The dev edit set the
  `ollie/mega-menu` label to the literal `CALL US`; it is authored as `Call Us` with
  `textTransform: uppercase` instead, so no locale is handed a shouted string it cannot
  override. It reaches the `<button>`: Ollie's own stylesheet sets `text-transform: inherit`
  on `.wp-block-ollie-mega-menu__toggle` beside `font-family: inherit` and
  `font-weight: inherit`, and the block declares all three supports. Verified on the rendered
  local page — `font-weight:var(--wp--custom--font-weight--semi-bold);text-transform:uppercase`
  and `has-heading-font-family` on the `<li>`, label text `Call Us`.

- **The destinations banner sits its type on the floor of the photograph.**
  `contentPosition: "bottom center"` with `spacing|40` above and below, against the previous
  `center center`. `patterns/template-archive-destination.php`, from the dev edit.

- **The destinations intro band splits 55% / auto**, against `58.33%` / `41.67%`. Only the
  description is pinned and the expert column takes the remainder, which the restructured
  panel's two-up action row needs. The tile grid gains a `spacing|80` bottom padding to
  match its top, and the banner tagline drops from `semi-bold` to `medium`.

- **The Trustpilot badge is centred.** `justifyContent: center` on
  `patterns/trustpilot-score.php`'s wrapper. `patterns/safari-expert.php` is its only
  consumer — `patterns/why-choose-sd.php` writes its own stacked copy and records why — so
  this is the badge's own arrangement rather than something one placement imposes.

- **The media-overlay tile is square, not 3/4 portrait.** `aspectRatio: "1"` on both
  `patterns/card-media-overlay-term.php` and `patterns/card-media-overlay.php`, authored on
  dev 2026-08-28 and carried back here. The term thumbnails are landscape originals — the
  travel styles are 554×368 — so a portrait tile threw away most of the frame's width, and a
  square holds a two-line title without the scrim crowding it. Both archives change together,
  as the two cards share one design. *(LS-2019, item 9)*

  On the tours card only, the scrim's side padding drops to `spacing|20` against `spacing|40`
  top and bottom, so a name as long as "Beach & Safari Vacations" breaks over two lines rather
  than three, and the term name is `semi-bold` rather than the `bold` the card style sets.
  Both are the values authored on dev. The destinations twin keeps the even padding and the
  card style's weight.

- **The tours archive banner sits its title at the bottom.** `contentPosition: "bottom
  center"` with `spacing|40` top and bottom, matching what was authored on dev — the title
  and tagline were vertically centred in the 454px cover. *(LS-2019, item 9)*

- **`brand-600` is `#BC5B18`, not `#966215`.** Live leans on this orange hard — it is the
  hover colour across the site — and the token exists so the theme has the same colour to
  reach for. The old value was a desaturated brown already stepping toward `brand-700
  #624411`, so every hover, link and panel authored against `brand-600` drifted browner
  than the page it was reproducing. Same hue family as `brand-500 #CC7F16` now, one step
  deeper. *(LS-2019)*

  **The AA anchor is kept, and that is why it is not the sampled value.** `brand-600` was
  chosen in the first place as the adjacent step that clears AA where `brand-500` does not
  — `style.md` §7, the note at `assets/styles/core-button.css:32` and
  `.github/reports/sd-design-audit-2026-08-12.md` all record it as the settled colour for
  brand text on a light ground. Live's orange as sampled, `#BF5C18`, measures **4.41**
  against white: AA Large only, and it would have quietly broken that contract for every
  body-size use. `#BC5B18` is the same hue and saturation 0.6% darker in lightness —
  visually the same colour — and measures **4.52**, so the anchor holds. For the record:
  old `#966215` 5.18, `brand-500 #CC7F16` 3.17.

  **This revises the audit's divergence D2, which had dropped live's orange rather than
  adopting it** — as sampled it is `#BF5C17`/`#BF5C18`, 4.41, missing AA by 0.09. Adopting
  it at `#BC5B18` keeps both the colour and the anchor. `style.md` §2.4 and §2.5 and the
  note at `assets/styles/core-button.css:32` carry the revision and the reasoning; the
  audit report's D2 row, divergence register, §5 contrast table and role-mapping list are
  annotated in place, with the original 2026-08-12 measurements left intact.

  The uses this touches, all now still AA: `elements.link` in
  `styles/sections/light-page-section.json` and `tinted-page-section.json`, the byline and
  tag rows in `patterns/card-post-list.php`, the lodge and destination link hovers in
  `patterns/itinerary-stay.php`, the back-link hover in `patterns/template-single-post.php`,
  white type on the `brand-600` ground of the `patterns/safari-expert.php` panel, and the
  `Archive` headings in `patterns/template-index-news.php` and `patterns/template-category.php`.

- **`is-style-script-accent` now applies to `core/post-title` as well as `core/heading`.**
  Live gives every non-home banner title the Joe Hand script face
  (`body:not(.home) #lsx-banner .container .page-title`), and on a single that title has
  to stay dynamic — the destinations archive can author it as a heading, the tour single
  cannot. Core only emits a variation's numbered class for the block types it is
  registered against, so before this the class rode along on the markup and resolved to
  nothing: the banner title computed as `Optima` at weight 700 instead of `Joe Hand` at
  200. Confirmed in the browser before and after. *(LS-2019)*

- **The post grid card lifts on hover.** `.is-style-post-grid-card` now rises 4px and steps
  one place up the shadow scale — `shadow|200` → `shadow|300` (`0 2px 4px` → `0 4px 8px`) —
  over 250ms, with `:focus-within` matching the hover so the tile answers the keyboard. The
  whole card is a link (`sdLinkTo`), and on a three-up shelf of identical tiles it was the
  only click target on the homepage giving no pointer feedback. Kept deliberately small: the
  same card is reused on the blog grid.

  In `assets/styles/core-group.css`, not the section style, for the reasons AGENTS.md
  requires be named — the `css` field strips `:hover` outright, and an `@media
  (prefers-reduced-motion)` block does not merely fail there, it compiles its query text into
  the selector and emits the inner rules unconditionally. `!important` on the hover shadow is
  load-bearing and cannot be traded for specificity: the resting value is an inline style
  written by the pattern's `style.shadow` attribute, and only an important author declaration
  outranks one. Recorded in `style.md` §12.2 and §12.3.

- **The slider shelf no longer clips its cards.** `.is-style-slider-frame .slick-list` takes
  `padding-block: spacing|20` with a matching negative `margin-block`. Slick builds that
  wrapper itself and vendor `slick.css` gives it `overflow: hidden` — needed horizontally,
  since that clip is what makes the carousel a carousel, but it sheared the bottom off every
  card shadow and would have cut the new hover lift in half. The negative margin gives the
  height back, so the shelf occupies exactly the space it did before and neither the arrows
  (absolutely centred on the frame) nor the dot row move.

- **Mega-menu rows are tighter, and their height is one token.**
  `settings.custom.mega-menu.row-padding` in `theme.json` now holds the row's vertical
  padding, and both `styles/blocks/navigation/mega-menu-nav.json` and
  `styles/blocks/query/mega-menu-list.json` read it. The two lists sit side by side in the
  same panel and have to share a rhythm, so the value is one place instead of three
  declarations across two files that had to agree.

  Set to `spacing|10` (8–10px), down from `spacing|20` (14–20px): a row goes from ~46–58px
  to ~34–38px, and the ten-item "Top 10 Safaris" column from ~460–580px to ~340–380px,
  which brings it back inside a sensible panel height. 38px stays a comfortable pointer
  target. Follows the existing `settings.custom.header` convention for component tokens.

- **The mega panels have a section style.** `styles/sections/mega-panel.json`
  (`is-style-mega-panel`, `core/group`) carries the panel frame that was authored as
  markup on all four parts: the `spacing|20` vertical padding, the column headings'
  `font-size|300`, and `text-transform: none` on panel paragraphs. Set once instead of
  thirteen times, and — unlike `assets/styles/ollie-mega-menu.css` — it renders in the
  Site Editor.

  The heading size is marked `!important` against a named opponent: the same headings
  carry `is-style-section-title-left`, which sets `font-size|500`. Measured on local, the
  two compile to (0,1,0) and (0,1,0) at offsets 86814 and 86942 — the section style is
  emitted *first*, so unmarked it would lose.

  ⚠️ **Every `blockGap` deliberately stays on the block markup** — the column gaps of
  `spacing|10`/`20`/`30` and the columns' own `spacing|70`. Per AGENTS.md a variation's own
  `blockGap` is emitted nowhere, and a nested one is emitted on the front end only, so
  moving them here would make the editor and the front end disagree. They are markup on
  purpose, not an oversight.

- **The mega panels' query rows are a block style variation, so the Site Editor shows them.**
  `styles/blocks/query/mega-menu-list.json` (`is-style-mega-menu-list`, `core/query`) now
  carries the resting row contract for the tour and special lists — flush rows, the
  hairline between them, and body type at the navigation row's weight and rhythm. It
  replaces the `sd-mega-list` utility class and the block of rules that sat in
  `assets/styles/ollie-mega-menu.css`.

  ⚠️ **The reason is editor parity, not tidiness.** `wp_enqueue_block_style()` hooks
  `render_block` and `wp_enqueue_scripts` (`wp-includes/script-loader.php`), and the theme
  calls `add_editor_style()` on `style.css` alone — so *every* `assets/styles/*.css` sheet
  is front-end only and the editor canvas never sees one. The six-way nav-row/query-row
  mismatch fixed on 2026-08-28 was therefore still live in the canvas: `elements.h3` in
  `theme.json` gives a post title `semi-bold`, `letter-spacing|heading` and
  `line-height|button`, and `elements.heading` gives it the heading face. The navigation
  columns never had the problem, because they were always a variation.

  Four declarations are marked `!important`, each against a named opponent rather than
  defensively: `font-family`, `font-weight`, `letter-spacing` and `line-height` against
  `theme.json`'s `elements.h3` / `elements.heading`, and `color`, `display` and
  `padding-block` on the link against `elements.link` and the block library's
  `.wp-block-post-title :where(a)`. `font-size` is set nowhere — both blocks carry
  `fontSize: "200"` and core emits `.has-200-font-size` with `!important`.

  Only `:hover` / `:focus-visible` stay in `assets/styles/ollie-mega-menu.css`, because a
  `css` field strips them. That half and the navigation half in
  `assets/styles/core-navigation.css` still describe the same row on different markup and
  still have to be changed together; each file now names the other.

- **The mega panels' column gap is the block's again.** All four parts authored
  `blockGap: spacing|70` and rendered at 50: core emits the authored gap as
  `.wp-container-core-columns-is-layout-bd159393{gap:spacing-60 spacing-70}` at (0,1,0)
  and `.sd-mega-panel .sd-mega-panel__columns` sat at (0,2,0), so the stylesheet won on
  both axes and the Site Editor's gap control did nothing. The `gap` declaration is gone
  and the panels widen to the authored 70; the hairline's `padding-inline-start` moves
  from 50 to 70 with it, since a border on the column cannot read its parent's gap.

- **The safari expert panel is live's widget again — a brand card, not a neutral one.**
  `patterns/safari-expert.php` had drifted to a `neutral-100` card that live never shipped.
  Rebuilt against live's own measurements (`sd-lsx-child/assets/css/custom.css:3791`,
  `#safari-expert-box`): a brand-coloured card holding the portrait beside the eyebrow,
  the name and the two actions, with the Trustpilot badge moved out of the card and onto
  the page ground beneath it, which is where live's `.trust-pilot-box` sits.

  The facelift over live is four things: an 8px corner instead of 1px, a 2px `base` ring
  on the portrait, and the two actions taking the theme's own button clothing —
  `is-style-outline-light`'s for the Call Us box and `is-style-accent-cta` for "Send an
  Email" — rather than live's hand-rolled `.lsx-to-meta-data` and `.cta-btn`.

  ⚠️ **The ground is `brand-600`, not live's `brand-500`.** Live's `#cc7f16` *is*
  `brand-500` exactly, and white on it measures **3.18:1** — under the 4.5:1 AA floor for
  the eyebrow, the Call Us label and the numbers — while `accent-400` on it measures
  **1.83:1**, under the 3:1 SC 1.4.11 floor for the CTA's own boundary. `brand-600` is the
  next step down the same ramp; measured off the rendered page it gives 5.18:1 for the
  eyebrow, the name, the Call Us label and its outline, and 7.23:1 for the CTA's own
  label. The CTA *fill* lands at 2.98:1 — a hundredth under the non-text floor, accepted
  rather than tuned, because the control is identified by that 19.2px uppercase label and
  no palette token sits between `accent-400` and the card. Nothing between `brand-500`
  and `brand-600` exists in the palette either. Reverting to live's exact orange is one word on the
  panel group and ships live's contrast failure with it.

- **The panel's Call Us is the header's nav item, not a `core/accordion`.** Same
  construction as `patterns/header.php` — an `ollie/mega-menu` inside a `core/navigation`
  carrying `is-style-call-us-navigation`, hover-open, its panel the one shared
  `parts/dropdown-call-us.html` reached through `menuSlug`. One mechanism for the widget
  across the site.

  Authored **inline, with no `ref`**, so unlike the header's copy it needs no
  `wp_navigation` row: `WP_Block_Type_Navigation::get_inner_blocks()`
  (`wp-includes/blocks/navigation.php:518`) only replaces a block's own inner blocks when
  `ref` is present. That answers the objection
  `styles/blocks/accordion/call-us-dropdown.json` had recorded against the swap.

  The trigger is a box, as live draws it: a `core/group` carrying the border, radius and
  padding as block attributes, with the Phosphor phone glyph beside the navigation.
  Because `ollie/mega-menu` escapes its `label`, the icon cannot go inside the `<button>`
  — so the toggle is stretched over the whole box with an `::after` and the nav, its
  container and its item go `position: static`, which also puts the pop-out under the box
  rather than under the label. Hover and `:focus-within` fill the box white and take the
  label, the icon and Ollie's chevron to `primary-500`, matching the button beside it;
  live's `#D59844` hover is not carried, because it leaves white text at 2.4:1. The new
  rules are the last section of `assets/styles/ollie-mega-menu.css`.

  The landmark is labelled **"Office numbers"** — the header's "Call us" and the mobile
  menu's "Contact numbers" are both already in the DOM, and WordPress disambiguates
  duplicate landmark labels by appending a number.

  Measured on local at 1440/1024/768/390: no horizontal overflow at any width, the panel
  opens 8px below the box and aligned to its inline start, `Escape` closes it, and
  focusing the toggle opens it and fills the box.

  ⚠️ `styles/blocks/accordion/call-us-dropdown.json` and the
  `.is-style-call-us-dropdown` half of `assets/styles/core-accordion.css` now have **no
  placement in the theme** — this was the last one. Left in place rather than deleted;
  `core-accordion.css` only loads where a `core/accordion` renders, so the cost is a dead
  entry in the editor's style picker.

- **Mega-menu link rows take a `brand-600` hover.** Neither list type had one that worked.
  The navigation columns had no hover rule at all — `styles/blocks/navigation/mega-menu-nav.json`
  records that a block style's `css` field has `:hover` stripped out of it, and the rule it
  points at in `assets/styles/core-navigation.css` was never written. It is there now, at
  `.wp-block-navigation.is-style-mega-menu-nav .wp-block-navigation-item__content:hover`,
  which is (0,4,0) and so clears core's always-on `color: inherit` reset at (0,3,0) without
  `!important`. `brand-600` rather than the header row's `brand-500`, because the panels sit
  on a `base` ground.

- **Fixed: the query-driven mega-menu columns were unstyled.** `parts/mega-menu-tours.html`
  ("Top 10 Safaris") and `parts/mega-menu-accommodation.html` ("Featured Specials") put
  `is-style-mega-menu-nav` on their `core/query` blocks. That variation declares
  `blockTypes: ["core/navigation"]`, so WordPress compiles its selector as
  `.wp-block-navigation.is-style-mega-menu-nav` and the class matched nothing — while the
  whole "Link lists" section of `assets/styles/ollie-mega-menu.css`, written against
  `sd-mega-list`, matched nothing either. Both blocks carry `sd-mega-list` now.

- **The query columns now match the navigation columns.** With `sd-mega-list` live the rows
  were styled but still did not read as the same list — measured side by side on dev, they
  differed on six counts, because a query row is `li > h3.wp-block-post-title > a` and picks
  up theme.json's `elements.heading` and the bare `h3` rule, where a navigation row is
  `li > a.wp-block-navigation-item__content` and picks up the variation:

  | | Navigation row | Query row, before |
  |---|---|---|
  | font-family | `body` | `heading` |
  | font-weight | `regular` | `semi-bold` |
  | letter-spacing | `0` | `heading` |
  | line-height | `snug` | `button` |
  | row divider | 1px `neutral-300` | none |
  | vertical padding | spacing 20 | spacing 10, plus a row gap |

  The "Link lists" section of `assets/styles/ollie-mega-menu.css` now re-states the
  variation's row on a post title. Colour is `inherit` rather than a named token, so both
  column types take it from the panel: the navigation rows already do — core's always-on
  `color: inherit` reset at (0,3,0) beats the `:root :where()` the variation compiles to, so
  the `contrast` it asks for has never rendered — while the query rows were taking `contrast`
  (`#000000`) from `elements.link` and reading noticeably blacker. The dead `contrast` in the
  variation is left as it is; forcing it would turn both columns pure black. Hover is
  `brand-600` with no underline on both, dropping the underline theme.json's
  `core/post-title` link `:hover` was adding. `font-size` is left to the blocks' own
  `fontSize: "200"`, since core emits `.has-200-font-size` with `!important`.

  Both post-templates take `blockGap: "0"` so the editor preview matches; the CSS keeps a
  `margin-block: 0` as the guarantee, because with the attribute absent the gap falls back to
  the global `blockGap`, not to zero.

- **The Why Choose band's Trustpilot badge is its own component, no longer shared with
  `patterns/trustpilot-score.php`.** `patterns/why-choose-sd.php` used to `require` the shared
  badge on the premise — written into that file at length — that the mark takes `currentColor`,
  so live's three `[tp_show_score color="…"]` variants collapse into one pattern. Measured
  against live on 2026-08-27, they do not:

  | Shared badge (`trustpilot-score.php`) | This band, on live |
  |---|---|
  | `trustpilot-logo.svg`, `#191919` | `tp-logo-white-green.svg` — a **different file**: white wordmark, green star |
  | One row: word, mark, stars, score | Mark over stars over the score line |
  | Rating word rendered | `.tp-wording` is `display:none` sitewide (`sd-lsx-child/assets/css/partials/_cta.scss:731`) |

  Neither SVG is `currentColor`, so no inherited colour could ever have fixed the first row —
  the dark mark read as a hole in the photograph. The badge is now written out inside
  why-choose-sd.php as a vertical `core/group`: the white-green mark at 90px, the bound star
  tile at 100px, and the `TrustScore | reviews` pair in a centred flex row beneath. All four
  `sd/trustpilot` bindings and their null-fallback behaviour are carried over unchanged.
  `trustpilot-score.php` is untouched and still serves `patterns/safari-expert.php`.

- **New asset: `assets/images/trustpilot/trustpilot-logo-white-green.svg`**, copied from
  `sd-lsx-child/assets/imgs/`, byte-identical to the file live and dev serve. The theme's
  existing `trustpilot-logo-white.svg` is the *all*-white mark, star included, and is not what
  this band uses; both are kept.

- **The We Are Africa badge is 185px, was 131px** — live's and dev's measured width.

- **The header's Call Us disclosure is an Ollie dropdown, not a `core/accordion`.** Zared's
  call, taken from ATI Holidays where the same widget is built this way: it opens on hover,
  and the pop-out is the plugin's own construction rather than an in-flow accordion panel
  argued out of the flow with `!important`. `patterns/header.php` now carries a
  `core/navigation` (`ref` 65909, `overlayMenu: "never"`, `ariaLabel: "Call us"`) holding one
  `ollie/mega-menu` whose `menuSlug` is the `dropdown-call-us` template part — the same one
  file the footer and the safari expert panel read, so the four numbers still cannot drift.

  Four shipped bugs went with the swap, each written up in
  `styles/blocks/navigation/call-us-navigation.json`:

  | Was | Why it happened | Now |
  |---|---|---|
  | Panel hung ~50px low | theme.json's global block gap reached the panel as `:root :where(.is-layout-flow) > *{margin-block-start:…}`, and a margin on an absolutely positioned box is added to its inset | The panel is the plugin's container, not a flow child |
  | White bar under the closed trigger | Core closes with `hidden="until-found"`, which is `content-visibility`, not `display` | Ollie closes with `opacity`/`visibility` in its own stylesheet |
  | Dropdown opened by itself on hard refresh | `accordion-item.php` registers only `isOpen`; `isHidden` is derived in JS, so the server leaves the attribute off and the panel ships **open** until hydration (~1s on dev) | The resting state is CSS — shut on first paint, no JavaScript involved |
  | Caret had to be kept in step with `aria-expanded` by hand | It was a rotated square drawn in `core-accordion.css` | Ollie's toggle ships a chevron that rotates off `aria-expanded` itself |

  A phone icon was added beside the label — Phosphor's glyph, the same path already used in
  three patterns, as an `outermost/icon-block` sibling of the navigation exactly as ATI does
  it. It is not part of the button's hover or click target; drawing it as a `::before` on the
  toggle would fix that and lose the editor-visible block, and the block was preferred.

- **New block style: `styles/blocks/navigation/call-us-navigation.json`.** Scopes the Ollie
  class names so nothing reaches `is-style-main-navigation`, which sits in the same header and
  uses the same markup. Positioning is CSS, not the plugin's JavaScript, for the reason
  `assets/styles/ollie-mega-menu.css` already documents for the mega-menu panels:
  `adjustMegaMenu()` does not run until `window.load`, measured at 7.8s on dev. `width` is
  `"custom"` rather than `"content"` because `menu-width-content` carries a plugin rule forcing
  the full content column — `menu-width-custom` has no stylesheet rule at all, so the CSS is
  uncontested.

- **`ariaLabel` is `"Call us"`, not `"Contact numbers"`.** `parts/mobile-menu.html` already
  labels its own copy of the numbers that way and both are in the DOM at once; where two
  landmarks share a label WordPress appends a number, so the pair rendered as "Contact numbers"
  and "Contact numbers 2". Measured on local, 2026-08-27.

### Removed

- **The destination grid's pagination and no-results fallback.**
  `patterns/template-archive-destination.php` — `core/query-pagination` and
  `core/query-no-results`, both deleted in Zared's dev edit and the deletion carried here on
  his instruction. The grid is a bare loop now. With ten destinations and Tour Operator's
  per-page setting inherited nothing paginated anyway, but an archive returning zero rows
  renders an empty band rather than a message. Restoring the fallback is four lines and
  changes nothing at any non-empty count. *(LS-2019)*

- **Four header-only rules from `assets/styles/core-accordion.css`** — the label's
  `white-space: nowrap`, the `is-style-header` hover colour, the `is-style-header` panel
  alignment flip, and that same selector in the narrow-viewport block. The file now serves
  exactly one placement, the safari expert panel.

  That panel keeps the accordion deliberately: `ollie/mega-menu` declares
  `"parent": ["core/navigation"]`, so using it there would put a navigation landmark inside a
  per-post content panel and make a `wp_navigation` post a dependency of a template that
  renders per post.

  `nowrap` is gone rather than moved — Zared's call, leave it out until it is an issue. It
  existed because "Call Us Today" wrapped inside the header's `flexWrap: nowrap` cluster at
  1024px and below; the expert panel's row is `flexWrap: wrap` and its label is the shorter
  "Call Us".

### Fixed

- **The itinerary row broke rather than wrapped, and the marker took the wrong orange.**
  Both flex rows in `patterns/itinerary-stay.php` were authored `flexWrap: nowrap`, which
  held every row on one line no matter how long the lodge name was. On
  `/tour/botswana-victoria-falls-safari/`, "Stanley & Livingstone Boutique Hotel" squeezed
  the night count until `2 Nights` split mid-word into `2 / Night / s` and pushed the lodge
  and its destination apart across an over-long line. The stay heading and
  `itin-accommodation-wrapper` are now `wrap`, so the lodge drops below the night count and
  the destination below the lodge, each still in reading order. Verified on dev at 360px,
  390px and 1440px. *(LS-2019)*

  Two rules back the attributes up, both in `assets/styles/core-group.css` because neither
  has a block attribute behind it. `.sd-itinerary__stay .itin-title-wrapper { flex: 0 0 auto }`
  pins the night count at its content width — a wrapping row still shrinks a constrained
  flex child before it wraps it. And the comma between the lodge and its destination is now
  `.sd-itinerary__stay .itinerary-accommodation::after { content: "," }` rather than the
  paragraph block it was authored as: as a block it was a flex item of its own, so once the
  lodge name wrapped the comma stayed pinned to the right edge of the lodge's shrunken box,
  a line away from the word it punctuates. As a pseudo-element it can neither detach nor
  begin a line, and it is hidden along with the lodge when Tour Operator marks the wrapper
  hidden. `content:` is why it is enqueued CSS and not a block-style `css` field.

  **The numbered marker is `brand-600` (#BC5B18), not `brand-500`.** Live paints the circle
  `#BF5C17`, which the CSS comment recorded as dropped from the palette — it is not:
  `brand-600` is that colour to within a shade. The marker and the lodge links are now
  deliberately different weights of the accent, which is how live reads.

  ⚠️ **The pattern file was rebuilt to match dev.** The itinerary was reauthored in the Site
  Editor on dev — `itinerary-location` moved inside `itin-accommodation-wrapper` so the
  night count, lodge and the stay's own destination run together as one sentence, with the
  `destination_to_tour` connection on a quieter line beneath. That structure existed only in
  the `wp_template` DB override; it is now in the pattern file, so a deploy no longer
  reverts it. The same edits were applied to the dev override (post 65930) so the two agree.

  ⚠️ **Unfixed and separate: `Card Link` leaks on stays with no destination.** Rows 1, 3 and
  4 of that tour render the literal placeholder. `build_itinerary_field()` only rewrites a
  field's class to `hidden itin-<field>-wrapper` on the group carrying that class, and
  `itinerary-location` no longer sits in one — `itin-location-wrapper` now holds the tour's
  parent destinations instead. An empty stay destination therefore has nothing to hide it,
  and the `::after` comma dangles after the lodge. Needs a decision on where the two
  destination lines should live before it can be fixed.

- **The media-overlay card's chevron could wrap onto a line of its own.** The `›` the card
  appends after its title is a `::after` on the heading, and it was `display: inline-block`.
  That makes it an atomic inline, and the line breaker takes a break opportunity on either
  side of one — so any title that filled its line left the chevron stranded alone on the
  next. It is now a plain `inline` whose `content` opens with a no-break space
  (`"\00A0\203A"`) instead of carrying a `margin-inline-start`, which glues it to the last
  word of the title so the two wrap together. The 0.3em gap is unchanged in appearance: a
  no-break space at the chevron's 1.15em is the same width. The optical nudge moved from
  `transform: translateY()` to `position: relative; top:`, because `transform` has no effect
  on a non-replaced inline box. `assets/styles/core-group.css:44-63`; applies to both
  `patterns/card-media-overlay.php` and `patterns/card-media-overlay-term.php`. *(LS-2019)*

- **`patterns/itinerary-stay.php` was authored against a placeholder that no longer applies.**
  The repeated row's heading held `Day 1` and the file documented the night count as blocked
  on an upstream Tour Operator filter. `sd-enhancements` now collapses the itinerary to one
  row per stay and labels each row with its night count, so the placeholder is `2 Nights` and
  the docblock records the mechanism instead of the blocker. *(LS-2019)*

  Nothing in the block markup changed: `itinerary-title` is filled from
  `lsx_to_itinerary_title()` either way, and the numbered spine is a CSS counter on
  `.sd-itinerary__stay` (`assets/styles/core-group.css:729-771`) that renumbers itself from
  the rows it is given. The authored text is an editor placeholder only, and is now written
  as a night count so the editor preview matches the front end. The country line stays
  flagged as missing — Tour Operator 2.2 exposes no itinerary field for it.

- **The tours archive tiles were 6.78px taller than their own crop, and the scrim covered the
  gap.** A tile 359.16px wide rendered **365.94px tall** on dev, measured 2026-08-28 — a strip
  of card below the photograph that the media-overlay scrim's `inset: 0` painted, which read
  as the overlay hanging past the bottom of the tile. Tour Operator's term-image `<figure>`
  carries the enclosing block's classes rather than the featured image's
  (`class-taxonomy-images.php:176-266`, via `get_block_wrapper_attributes()` called from a
  `render_block` filter), so core's `.wp-block-post-featured-image a { display: block;
  height: 100% }` never reached the link. Left inline, the image sat in a line box and the
  strut's descender added height the `aspect-ratio` box does not account for. *(LS-2019,
  item 9)*

- **The tile images were stretched to the crop instead of cropped to it.** Same missing class,
  second consequence: `object-fit` is not in core's featured-image stylesheet — it comes from
  the block's `scale` attribute, serialised inline. `scale` defaults to `"cover"`, so it is
  absent from saved block markup, and Tour Operator reads the parsed attributes rather than
  the defaults-merged set: `if ( ! empty( $attributes['scale'] ) )` is false and no
  `object-fit` is emitted. The image kept `width:100%;height:100%` at the initial
  `object-fit: fill`, so a 554×368 photograph was squashed into a 359×359 tile. *(LS-2019,
  item 9)*

  **Authoring `"scale":"cover"` is not the fix.** It renders, but the editor drops attributes
  equal to their default on save — `patterns/safari-expert.php` carries `"scale":"cover"` and
  dev's `single-tour` template, saved from the same pattern, has lost it. The fix is a
  stylesheet rule, which cannot be edited away. Upstream it is two lines in
  `render_term_featured_image()`: default `$scale` to `'cover'`, and pass the block's own
  class into the wrapper.

  Both fixes verified on local 2026-08-28: tile **359.16 × 359.16**, `object-fit: cover`,
  and `sd-theme-2026-block-core-post-featured-image-css` enqueued on the archive.

- **`var:custom|…` is silently dropped on a dynamic block.** Found while setting the tile
  title's weight: `core/term-name` given `"fontWeight":"var:custom|font-weight|semi-bold"`
  rendered with no inline style at all and inherited the card style's `bold`. A static block's
  `style` object is resolved by the editor at save time; a dynamic block has no saved markup,
  so the server-side style engine resolves it — and that only expands `var:preset|…`, and only
  for properties declaring `css_vars`. `fontWeight`, `lineHeight`, `fontStyle`, `textTransform`
  and `letterSpacing` declare none. Measured on local 2026-08-28, `wp_style_engine_get_styles()`
  given all three of `fontWeight: var:custom|font-weight|semi-bold`,
  `lineHeight: var:custom|line-height|heading` and `fontSize: var:preset|font-size|500`
  returns only `font-size:var(--wp--preset--font-size--500);`. *(LS-2019)*

  Fixed on this card by writing `var(--wp--custom--font-weight--semi-bold)`, which
  `patterns/safari-expert.php` had already arrived at for `letterSpacing`, and written up as a
  convention in AGENTS.md so it stops being rediscovered. **Roughly twenty other authored
  `var:custom|…` declarations on dynamic blocks across `patterns/` are inert for the same
  reason** — `post-title`, `query-title`, `post-excerpt`, `post-terms`, `post-author-name` and
  `navigation` in the page, category, search, single-post, blog-card, review-card and safari-guru
  patterns. Not swept here; each one needs its intended value checked against what the block
  currently inherits rather than a blind find-and-replace.

- **The header's search panel overhung the left edge of the screen below 781px.** The
  out-of-flow panel sized itself `min(600px, 100vw - spacing-80)` — a guess at the room to
  the *left of the icon*, written as a fraction of the whole viewport, and the icon is not
  at the viewport's edge. The trigger, the block gap, the mobile menu toggle and the root
  gutter are roughly 110px the formula never subtracts, so the panel overhung by about that
  much at every width where the 600px cap was not already clamping it. Measured on local
  2026-08-28: the open panel's left edge sat at **-61px** at 390px and **-59px** at 600px.
  Nothing scrolled — `overflow: hidden` on the closed wrapper meant the placeholder and the
  first characters typed were simply cut off screen rather than visibly broken. *(LS-2019)*

  **The fix is not a better formula.** Any formula has to hard-code the width of the
  furniture to the icon's right and goes wrong again the next time that furniture changes.
  Below 781px the form is allowed to grow across the row (`flex: 1 1 auto`) and the panel
  returns to normal flow inside it, with the trigger held at the end by `justify-content:
  flex-end`. The panel then *cannot* overhang: it is a flex item, so it stops where its flex
  line does — the row's content edge, which is the root gutter, exactly. No magic number, and
  it survives the toggle, the gap or the gutter changing.

  In flow is what the out-of-flow model exists to avoid above 781px, where a growing panel
  would shove the trigger leftwards and reflow the row. It doesn't here because the form has
  the whole row's free space to grow into and the trigger is pinned to the far end of it —
  the panel eats slack, not the trigger's position. 781px is core's own column-stacking
  breakpoint, not a new one: at that width `wp-block-columns` drops the logo onto its own
  line and the utility column goes full-width, which is what leaves the row that slack. The
  two changes are the same event.

  **The animation moves from `width` to `flex-basis`.** In flow the open width is `auto` —
  resolved by the flex line, unknowable up front, and not transitionable from `0`.
  `flex-basis` is: 0% closed, 100% open, with `flex-shrink: 1` pulling the overshoot back to
  what the row actually leaves. That is also the axis core animates natively, so this agrees
  with core rather than fighting it; `max-width` keeps the 600px token as a ceiling for the
  wide end of the range. One style, not two — the treatment, its ARIA and its tokens are
  unchanged, and the reduced-motion opt-out still applies.

  In `assets/styles/core-search.css` with the full reasoning, not the block style's `css`
  field: `@media` cannot compile there at all — it emits a dead
  `:root :where(.is-style-header-search--N@media (max-width: 960px)){}` plus the inner rules
  unconditionally at every width, which is silently wrong rather than silently absent.
  Summarised in the `styles/blocks/search/header-search.json` description.

- **Mega-panel query rows stopped hovering.** A regression from moving the row contract
  into a variation: the resting rule needs `color: inherit !important` to beat theme.json's
  `elements.link` at the same (0,1,0), and an important author declaration outranks a
  *normal* one whatever its specificity — so the hover rule in
  `assets/styles/ollie-mega-menu.css`, at (0,4,0) but unmarked, was inert. Marked, so
  important-vs-important falls back to specificity and the hover wins. The navigation rows
  were never affected; their resting colour is unmarked.

- **The query columns' rows didn't span their column.** The query block carries a vertical
  flex layout, which core compiles to
  `.wp-container-core-query-is-layout-bfed8e9f{flex-direction:column;align-items:flex-start}`
  — so the post-template, and therefore every row and its hairline, was only as wide as the
  longest title. `width: 100%` on the post-template in the variation. Both query columns
  were affected; Accommodation's Featured Specials showed it because the special titles are
  short.

- **`.sd-mega-panel__heading` was six dead declarations.** Measured on local 2026-08-28.
  Its colour, font-family, font-weight, letter-spacing and text-transform all lost to
  `is-style-section-title-left`, which the same headings also carry: both compile to
  (0,1,0), and the variation is emitted with the global styles at offset 86607 while
  `assets/styles/ollie-mega-menu.css` is linked at 25792. Its `margin-block-end` lost to
  core's layout contract — each column sets a `blockGap`, which emits
  `.wp-container-core-column-is-layout-‹hash› > * { margin-block-end: 0 }` late in the
  document — and it never reached the editor at all, because no stylesheet in that
  directory does. Rule and class both removed.

- **`.sd-mega-panel { padding-block: spacing|40 }` was already overridden.** The parts had
  begun setting `spacing|20` as an inline style, which outranks any stylesheet rule. The
  value moves to `is-style-mega-panel` and the dead rule is gone.

- **Two dead classes in the mega-menu parts.** `parts/mega-menu-about.html` put
  `is-style-mega-menu-nav` on a `core/paragraph`; the variation declares
  `blockTypes: ["core/navigation"]`, so WordPress compiles it as
  `.wp-block-navigation.is-style-mega-menu-nav` and it matched nothing — the same bug
  class as the `sd-mega-list` one fixed on 2026-08-28. `parts/mega-menu-tours.html`
  carried `sd-mega-menu-tours-query` on its post-template, which nothing in the theme
  styles. Both removed.

- **`mega-menu-nav.json` transitioned `all`.** Core stamps a variation's class on both the
  `<nav>` and its `<ul>` (measured on dev), so `transition: all 0.25s ease` was applied
  twice over a flex container that animates nothing but colour on hover. Narrowed to
  `transition: color`.

- **`blockGap` set in the editor disagreed with the front end, and the gap control under a
  section title did nothing at all.** Two separate causes, both now removed. Measured on
  WP 7.1, canvas 905px against viewport 1200px, 2026-08-27.

  **1. Child margins inverted between the two environments.** `script-accent`, `section-title`
  and `section-title-left` each declared `spacing.margin.bottom: var:preset|spacing|20`, and
  `archive-intro` declared `margin.top: 0`. An explicit `blockGap` compiles to
  `.wp-container-‹hash› > * { margin-block: 0 }` in `core-block-supports-inline-css`; a
  variation margin compiles to `:root :where(.wp-block-heading.is-style-X--N)`. Both are
  **(0,1,0)** — `:root` contributes (0,1,0), `:where()` contributes nothing — so source order
  alone picks the winner, and WordPress reverses it:

  | | Container rule | Variation rule | Winner | Child `margin-bottom` |
  |---|---|---|---|---|
  | Front end | **55** | 50 | container | `0px` |
  | Editor | 125 | **142** | variation | `16.814px` |

  In the editor the surviving margin **collapses** with the next sibling's
  `margin-block-start`, so the rendered gap is `max(gap, childMargin)` rather than `gap`. Every
  gap at or below the child's margin rendered identically and the control looked dead: with a
  `spacing|20` margin, `None`/`XXS`/`XS`/`S` all rendered at 16.8px and only `M` upwards moved.
  Where the parent's gap was *smaller* than the margin — `spacing|10` in
  `parts/mega-menu-tours.html` — it was swallowed whole.

  **2. The gold rule's bottom margin outweighed every gap.** The `::after` accent rule under
  `section-title` / `section-title-left` carried `margin-bottom: var(--wp--preset--spacing--70)`
  (55–61px) in `assets/styles/core-heading.css`, reproducing live's `margin: 8px auto 4.25rem`.
  Because it sits inside the heading box it collapsed through the heading's bottom edge and
  dominated any parent gap below `spacing|70` — in **both** environments, so it was not a
  divergence, but it made the section's own gap control inert up to `XXXL`. A gap of
  `spacing|10` and a gap of `spacing|40` rendered identically at 61.25px. Only the 8px **top**
  margin is kept, which is the space between the title text and the rule and belongs to the
  device; the gap to the section body is the parent section's `blockGap` now.

  Verified across eight parent shapes, front end and editor, before and after:

  | Shape | FE before | FE after | Editor before | Editor after |
  |---|---|---|---|---|
  | `section-title`, gap `spacing\|10` | 61.25 | **9.37** | 55.13 | **8.93** |
  | `section-title`, gap `spacing\|40` | 61.25 | **35.63** | 55.13 | **32.56** |
  | `section-title`, no parent gap | 61.25 | **52.81** | 55.13 | **47.78** |
  | `section-title-left`, column gap `spacing\|10` | 61.25 | **9.37** | 55.13 | **8.93** |
  | flex parent, gap `spacing\|10` | 9.37 | 9.37 | 8.93 | 8.93 |
  | `script-accent`, no parent gap | 52.81 | 52.81 | 47.78 | 47.78 |
  | **`script-accent`, gap `spacing\|10`** | 9.37 | 9.37 | **16.81** | **8.93** |
  | plain heading, gap `spacing\|10` (control) | 9.37 | 9.37 | 8.93 | 8.93 |

  The editor now tracks the front end in every shape, and the gap control is live under a
  section title for the first time. Cause 1 was a **front-end no-op** to remove — the margin
  was always dominated, by `core-block-supports` where the parent set a gap and by the root
  `spacing|60` flow gap where it did not. `archive-intro` was inert in both shapes it occurs
  in: it is a first child, which `> :first-child` already zeroes. **Cause 2 does move the front
  end** — the four `section-title` shapes above lose the 61.25px and fall back to their
  parent's gap, so the sections that hold one need their `blockGap` set to restore the
  intended rhythm.

  The rule this establishes is in [AGENTS.md](AGENTS.md): `spacing.margin` belongs to
  page-section styles only, top and bottom, only where genuinely necessary, and **never** on a
  block sitting inside a parent that carries a `blockGap`.

  Still latent, recorded rather than changed: **six section styles declare `margin.top: 0`**
  (`dark-`/`light-`/`tinted-page-section`, `site-footer`, `footer-colophon`, `section-header`).
  `margin: 0` is the same hazard pointing the other way — it *kills* a parent's gap in the
  editor rather than flooring it — but every parent that currently holds one sets
  `blockGap: 0`, so both environments agree today.

- **The mega menus took as long as the slowest asset on the page to become usable, and were
  squashed until then.** Ollie Menu Designer positions its panels from JavaScript, and
  `callbacks.initMenuLayout` in `build/blocks/mega-menu/view.js` defers that work to
  `window.load`:

  ```js
  "complete" === document.readyState
      ? adjustMegaMenu()
      : window.addEventListener("load", () => adjustMegaMenu(), {once:true})
  ```

  `adjustMegaMenu()` is what writes the panel's `top`, `left` and `width`. Until it runs the
  panel keeps the plugin stylesheet's resting values — `top: 0; left: 0` at content width — so
  it opened *over* the nav row at 433px instead of 1440px, with its three columns crammed into
  it. Measured on dev at 1440×900, 2026-08-27:

  | | |
  |---|---|
  | `window.load` fired at | 7 802 ms (18 441 ms on a second run) |
  | panel first got its inline `top` | 7 870 ms — **+68 ms, every run** |

  `window.load` waits for every image, iframe and third-party script, and dev carries GTM,
  Facebook, LinkedIn Insight, Salesforce, DoubleClick, Trustpilot's widget and Popup Maker.
  Blocking every third-party host still left `window.load` at 5 738 ms, so this was not one
  vendor's fault and could not be tuned away.

  The panels are placed in CSS now — `assets/styles/ollie-mega-menu.css` — and the JavaScript
  is left to lose. Three of the four computed values are static once the containing block is
  right: `inset-block-start: 100%` for `top`, and `0`/`0` for the inline edges. Core's
  navigation stylesheet puts `position: relative` on the nav, its container and every item
  (confirmed via `CSS.getMatchedStylesForNode`); taking those three to `static` hands the
  containing block up to the sticky header group. Safe for this nav specifically — it carries
  `overlayMenu: "never"` and has no core submenus, only four `ollie/mega-menu` dropdowns and one
  plain link.

  Verified on local: the panel opened **before `window.load` had fired**, at exactly
  `width = clientWidth, left = 0, top = header bottom`, with the plugin's inline styles still
  empty. Placement is correct at 1200/1280/1366/1440/1600 with a panel open, and the author's
  `menu-width` setting is still honoured — `full` spans the header, `wide`/`content`/`custom`
  keep the plugin's width and are centred on it.

  Two things came off the maintenance list with it. The `topSpacing` attribute no longer
  matters (`top: 100%` is the header's own height), which had silently drifted — dev said 50,
  local 40, and 40 put the panel 10px *over* the nav. And `adjustMegaMenu()` set
  `width: window.innerWidth`, which includes the scrollbar: 1440 against a clientWidth of
  1427, measured as 13px of horizontal overflow at 1280, 1366 and 1440 on dev and local both.
  `inset-inline: 0` resolves against the header's padding box, so that is gone.

- **The Call Us dropdown grew the header from 110px to 355px when opened.** A specificity bug,
  not an accordion problem. WordPress compiles a block style variation's `css` field into
  `:root :where(<selector>)`, which lands at **(0,1,0)** — `:root` contributes all of it and
  `:where()` contributes nothing. That is low enough to lose a *tie on source order*, and the
  panel's `position: absolute` was losing it: `position` computed as `relative`, so the panel
  stayed in flow. The utility row went to 295px, the enquiry button stretched to match, the logo
  and Trustpilot mark centred themselves in a 295px band, and the nav was pushed down onto the
  hero. And because `inset-block-start: calc(100% + 8px)` is a *relative offset* once `position`
  is `relative`, the panel was displaced 303px below its own flow position and hung over the
  navigation.

  It only happened for logged-in users, which is what made it read as environment-specific
  rather than as a specificity bug — dev serves logged-in requests uncached and with the admin
  bar's stylesheet stack on top, and that is enough to flip which side of the tie loses.
  Anonymous requests to the same URL measured 110px at every width tested, across six
  scenarios (scrolled, pre-`window.load`, post-resize, mega menu open first, 1512px, 1680px),
  which is why it took the reported screenshots to locate.

  Confirmed by injecting one rule — `.wp-block-accordion-panel{position:relative}` — into an
  otherwise untouched dev page: header 355px, row 295px, enquiry button 295px, matching the
  reported screenshots pixel for pixel.

  The five load-bearing declarations are marked `!important` now — `position`,
  `inset-block-start`, `inset-inline-start` and `z-index` on the panel, plus `position: relative`
  on the accordion, which is the positioning context they resolve against and was equally
  exposed. Same reasoning and same remedy as `styles/blocks/navigation/main-navigation.json`,
  which already carries `!important` throughout for this exact reason. Verified against four
  override attempts that each previously flipped it, including one at (0,3,0) with a type
  selector: header stays 110px, panel stays `absolute`, accordion stays 26px.

- **The Call Us Today label wrapped at narrow widths.** Separate from the above, and about the
  *closed* trigger: the label broke onto two lines below ~1024px, taking the button from 25px to
  49px, the row from 48px to 71px and the header from 108px to 180px. `white-space: nowrap` on
  the toggle. The trigger's `fontSize: "300"` is unchanged and approved — shrinking the type
  would only move the failure width down, and any longer label or larger user font size would
  bring it back. Verified: trigger 23–26px tall from 768px to 1600px, and opening it no longer
  changes the header's height at any width.

- **The desktop navigation was shown 200px before it fits.** Block Visibility's `large`
  breakpoint was at its 992px default, so the nav/mobile-menu swap happened at 992px — but the
  five-item uppercase nav needs ~1250px, and between 992px and 1200px it wrapped to two rows
  and added ~50px to the header. This is what made dev's header 157px at 1200px against local's
  107px. `block_visibility_settings → visibility_controls.screen_size.breakpoints.large` is
  `1200px` now, which is the breakpoint `assets/styles/ollie-mega-menu.css` already assumed
  (`@media (max-width: 1199px)`). Verified: nav shown at 1200px, mobile menu at 1199px, header
  106–110px unbroken from 1024px to 1600px.

  ⚠️ **This is a database option, not a theme file** — it does not travel with a theme deploy
  and has to be set on dev as well. The only other Block Visibility usage in the theme
  (`patterns/homepage-safari-gurus.php`) is unaffected: its blocks key off `small` and off
  `large`+`medium` together, both of which resolve the same way either side of the change.

- **The Call Us dropdown opened itself on every hard refresh, then closed a second later.**
  The panel is closed by an attribute the server never writes. Core renders it as
  `<div data-wp-bind--hidden="state.isHidden" role="region" class="wp-block-accordion-panel">`
  — the directive, with no resolved value — because `accordion-item.php` registers only
  `isOpen` through `wp_interactivity_state()` while `isHidden` is derived in the JS store
  (@wordpress/block-library/accordion/view). The server-side directive processor has nothing
  to resolve `state.isHidden` against, so it leaves the attribute off and the panel ships
  **open**, closing only when the Interactivity module hydrates. Confirmed in the shipped HTML
  of local and dev both; imperceptible on local, about a second on dev, which is why it read as
  environment-specific.

  The closed state now keys off the item's `.is-open` class as well as the panel's `[hidden]`
  — `.is-style-call-us-dropdown .wp-block-accordion-item:not(.is-open) .wp-block-accordion-panel`
  — because `.is-open` is absent in exactly the two states that matter: before hydration and
  after a close. It is set by `data-wp-class--is-open` in step with `hidden` and
  `aria-expanded`, so it cannot disagree with what a screen reader announces, and the chevron
  rotation was already keyed off it.

  Verified under CDP throttling (300ms latency, 400KB/s) so the hydration window was wide:
  **284 sampled frames, 0 of them painted the panel open**, with `hidden` arriving at
  dt=5098ms. Click-to-open still lands 8px under the trigger with right edges flush, and the
  close still animates — `display: block` at opacity 0.24 mid-transition, then `none`.

  Cost: with JavaScript off the panel stays shut and the toggle is inert. It was already inert
  — `core/accordion` is a JS component — so this trades "four numbers permanently overlapping
  the nav" for "four numbers not shown", and every one of them appears elsewhere on the page.
  Fixing it at the source would mean registering the derived `isHidden` in PHP so the server
  emits `hidden`; that is behaviour, so it belongs in `sd-enhancements`, and it is really a
  core gap. → LS-2033

- **The blog card's tag footer drew a rule under posts that have no tags.**
  `core/post-terms` returns an empty string when the post has no terms in the taxonomy
  (`wp-includes/blocks/post-terms.php:57`) — the block disappears, its wrapper group does
  not, so an untagged post rendered a bare `primary-300` rule with `spacing|20` of padding
  beneath the excerpt: a divider dividing nothing. One rule added to
  `styles/sections/cards/post-grid-card.json`:
  `& .wp-block-group:not(:has(> *)) { display: none; }`.

  `:not(:has(> *))` and **not** `:empty` — the pattern indents its markup, so the group
  always holds whitespace text nodes and `:empty` never matches. Measured on the rendered
  page: the untagged group's contents are `'\n\t\t\t\n\t\t'`.

  Keyed off core's own `.wp-block-group` rather than a hand-rolled `__`-suffixed hook, per
  AGENTS.md. Two things follow from that: the card's **markup is unchanged**, so the fix
  reaches the dev front-page DB override with no edit to the database; and it generalises —
  a post with no featured image empties the Media group, and an empty box is no more wanted
  there than an empty rule.

  It is CSS rather than a visibility control because neither alternative exists: the
  condition is per-post inside a Query Loop, which Block Visibility cannot express (its
  conditions are request-level — role, date, screen size, query string), and the emptiness is
  only knowable *after* `core/post-terms` has rendered, so there is nothing to branch on at
  block level.

  `:has()` and `:not()` both survive css-field sanitisation — measured on the local homepage
  2026-08-27, compiling to
  `:root :where(.wp-block-group.is-style-post-grid-card--N .wp-block-group:not(:has(> *)))`.
  So this stayed in JSON instead of dropping to an enqueued sheet, and since nothing else
  sets `display` on those groups, (0,1,0) is enough — no `!important`. Verified in-browser
  across four fixture posts, one tagged: computed `display` `block` at 42px on the tagged
  card, `none` at 0px on the other three.

### Changed

- **The Trustpilot badge's mark and star tile are slightly larger.** In
  `patterns/trustpilot-score.php` the Trustpilot logo goes 90px → 105px and the star tile
  100px → 118px (both ~+17%, so the two keep their relative weight). Requested for the
  homepage, where the badge arrives through `patterns/why-choose-sd.php`; because this is one
  shared pattern with no colour or size variants, the same bump also applies to the safari
  expert panel on the destination archives (`patterns/safari-expert.php`,
  `patterns/template-archive-destination.php`). The header's Trustpilot badge is separate
  static markup in `patterns/header.php` (100px / 143px) and is untouched.

- **The panel renders closed in the editor now, by decision.** `.is-open` is an Interactivity
  API class and the module does not run on the canvas, so the rule above closes the panel there
  too. Zared's call: the numbers are edited in the `dropdown-call-us` template part, not
  through the header. Three rules that existed only to render it open on the canvas came out
  with it — `display: block` on the accordion, `position: static; box-shadow: none` on the
  panel, and a chevron pointed up to match, which was actively wrong once the panel was closed.

  Recorded so the next attempt does not waste the hour: an editor exemption **cannot** be
  written from `core-accordion.css`. The variation's `css` field compiles into the editor's
  variation stylesheet as
  `:root :where(:root :where(.wp-block-accordion.is-style-call-us-dropdown-<uuid>) .wp-block-accordion-panel[hidden])`
  carrying `display: none !important`. Measured in the canvas iframe: an exemption at (0,4,0)
  whose `.editor-styles-wrapper` ancestor *does* match still lost to it. Editor state after
  the change: panel `display: none`, chevron in its closed position, panel still carrying
  `data-block` so it stays reachable in List View.

- **The chevron's right-hand corner was clipped by core's `overflow: hidden`.** The toggle
  carries `overflow: hidden` from `wp-includes/blocks/accordion-heading/style.css` — core's own
  `+`/`×` indicator rotates inside a fixed box and has no reason to spill. A rotated square
  does: at 0.4em the chevron's layout box is 6.4px but its painted box is 6.4 × √2 ≈ 9.05px, so
  1.3px of each corner falls outside, and the right one was cut. Measured as
  `scrollWidth - clientWidth`: 2px at 1600 and 1440, 1px from 1280 down, at every desktop
  width. `overflow: visible` on the toggle in `assets/styles/core-accordion.css`, at (0,2,0)
  against core's (0,1,0). Verified by crop: both corners paint, closed and open.

- **The Call Us trigger underlined on hover instead of darkening.** Core's
  `.wp-block-accordion-heading__toggle:hover .wp-block-accordion-heading__toggle-title` sets
  `text-decoration: underline`; nothing of ours asked for it. It now takes the treatment the
  enquiry button beside it uses — `styles.elements.button` hovers brand-500 to **brand-600**
  over `0.25s ease-in-out`, so the trigger darkens on the same token and the same curve, and
  the chevron follows because it is `currentColor`. Measured brand-500 `rgb(204,127,22)` to
  brand-600 `rgb(150,98,21)`, underline `none`.

  Two rules, because the placements rest on different colours: the header's is brand-500 and
  darkens to brand-600, the safari expert panel's inherits neutral and darkens to neutral-900,
  which is what the variation already gives its number links. `:focus-visible` takes the same
  change, over core's focus outline. In the stylesheet rather than the variation JSON because
  the `css` field strips `:hover` and theme.json allows pseudo-state keys on elements, not on
  nested blocks — `styles.blocks.core/accordion-heading[":hover"]` does not exist.

- **The Call Us dropdown opened 50px away from its trigger.** theme.json's global block gap
  reaches the panel as
  `:root :where(.is-layout-flow) > *{margin-block-start:var(--wp--preset--spacing--60)}` — it
  is a flow child of the accordion item — and a margin on an absolutely positioned box adds to
  its inset, so `inset-block-start: calc(100% + 8px)` resolved to ~58px and the panel floated
  below the header over the page content instead of hanging off the bar. The panel now carries
  `spacing.margin: 0` in `styles/blocks/accordion/call-us-dropdown.json`. Measured open at 8px
  under the trigger with right edges flush, at 1440 and 1024, in the header and the safari
  expert panel both.

- **The Call Us trigger was sized by its heading level, not by its own attributes.** Core's
  `.wp-block-accordion-heading__toggle` is `font-size: inherit`, and what it inherits from is
  `<h3 class="wp-block-accordion-heading">` — which this theme styles through
  `styles.elements.h3` (font-size 400, semi-bold). So the header's label rendered at **24px
  semi-bold** while the block asked for 300 bold, and the expert panel's `<h4>` copy rendered
  at 19.2px medium while it asked for 200 semi-bold: both at a size nobody had chosen. Below
  about 1100px that 24px label wrapped inside the utility cluster's `flexWrap: nowrap` row and
  collided with the Trustpilot mark; at 1024 it took the bar from 128 to 146px and wrapped the
  enquiry button with it.

  The variation now sets `core/accordion-heading` to `font-size: inherit` /
  `font-weight: inherit` so the instance attributes decide, and the header's own attribute
  drops 300 to **200**. Live's label is 18px bold; this token scale reaches that at 200 (16px)
  rather than 300 (19.2px, fluid to 24px), and 200 matches the enquiry button beside it. Both
  instances measured at their authored type afterwards — header 16px/700 brand-500, expert
  panel 16px/600 neutral — and the 1024 bar is back to one line.

  **Worth generalising:** on any block whose wrapper is a heading, an `elements.h*` style
  outranks the block's own font-size attribute. `inherit` hands control back to the instance.

### Changed

- **The Call Us caret is an actual chevron.** It was a solid CSS triangle, ported from live's
  Bootstrap `<span class="caret">`. Two strokes on a rotated square now: a filled triangle
  reads as a select control, a chevron reads as a disclosure, and it is the lighter mark
  beside a 16px label. Stroke width comes from `custom.borderWidth.200`, so it matches the
  panel's hairlines, and each state carries a `translateY` for optical centring — a rotated
  square's visual mass sits low pointing down and high pointing up, so the tip would appear to
  hop ~2px on toggle without it. `assets/styles/core-accordion.css`, editor open-state rule
  updated with it.

### Added

- **The Call Us panel fades and lifts on open and close** — 0.18s, in
  `assets/styles/core-accordion.css`. CSS only: no JS, and nothing needed from
  `sd-enhancements`. Core's accordion toggles `hidden` and `.is-open` and animates nothing
  itself, and this panel is `display: none` when closed, so two modern pieces do the work —
  `transition-behavior: allow-discrete` on `display` holds the computed `none` back until the
  fade finishes, and `@starting-style` supplies the from-state that an element entering from
  `display: none` otherwise has no way to interpolate from. Both directions verified by
  sampling computed style mid-transition: closing holds `display: block` at opacity 0.36
  before flipping to `none`; opening enters at opacity 0 / `translateY(-6px)` and lands at
  1 / `translateY(0)`.

  No height animation — the panel is absolutely positioned with a content-driven height, so
  `max-height` would need a magic number and would animate a property nothing else here
  animates. Guarded by `prefers-reduced-motion`. This is the fifth entry on the list of things
  the variation JSON's `css` field cannot hold: it unwraps at-rules, so `@starting-style` and
  the reduced-motion guard have to live in the stylesheet.

### Fixed

- **The fluid spacing scale was never fluid.** Every `spacingSizes` clamp had a broken
  interpolation term, so all eleven presets pinned to their minimum at every viewport.
  `spacing|80` was authored `clamp(3rem, calc(2.257rem + 0.19vw), 5rem)` — at 1520px the
  middle term evaluates to ~39px against a 48px floor, so it always resolved to 48px and the
  80px maximum was unreachable. The `vw` coefficient was ~13x too small across the whole
  scale and the intercept was wrong with it.

  Regenerated in core's own fluid form, `clamp(MIN, MIN + ((1vw - 0.2rem) * F), MAX)` with
  `F = 100 * (max - min) / 80rem` over the 320-1600px range the `fontSizes` already use
  (`wp-includes/block-supports/typography.php:530-536`), so the two scales now interpolate
  identically. Verified in-browser at three widths: mobile is effectively unchanged
  (360px: 4.03 / 8.05 / 14.19 / 20.30 / 26.44 / 33.53 / 37.72 / 42.88 / 49.00 / 57.05 /
  65.12px, within 2% of the old pinned values), and above 1600px every preset now resolves to
  its intended round maximum — 5 / 10 / 20 / 30 / 40 / 50 / 60 / 70 / 80 / 90 / 100px, the
  slug numbers themselves. **Desktop spacing grows accordingly**: at 1520px `spacing|40` goes
  26 to 39px and `spacing|80` goes 48 to 78px. `spacing|5`'s maximum corrected 0.313rem to
  0.3125rem so it lands on exactly 5px.

### Changed

- **Card structure moved out of CSS and into block markup.** Every hand-written `__` helper
  class on the card patterns is gone, along with the CSS that keyed off it. Card `css` fields
  total 8,290 to 2,089 chars; `core-group.css` 858 to 629 lines, `core-columns.css` 39 to 14,
  `core-image.css` 32 to 28.

  - **Image crops are `aspectRatio` attributes now.** ~100 lines of `(0,3,0)` selectors in
    `core-group.css` existed only because `height`/`width` in a section style's `css` field
    compile to `:root :where(...)` at `(0,1,0)` and lose to the block library's
    `.wp-block-image img{height:auto;width:auto}` at `(0,2,0)`. `core/post-featured-image`
    and `core/image` serialise `aspectRatio`/`height`/`scale` as **inline styles on the
    `<img>`** (`wp-includes/blocks/post-featured-image.php:46-65`), which no stylesheet rule
    can lose to and which the editor exposes as a control. Crops chosen from core's stock
    ratios: `16/9` for post-grid, compact and category; `3/4` for media-overlay; `4/3` for
    the two list cards. `blog-card` (`4/5`), `blog-card-large` and `team-member-card` (`1`)
    already carried theirs.
  - **The two wide cards are `core/columns`.** `card-post-list` and `card-tour-list` /
    `card-accommodation-list` were flex rows whose column widths lived in CSS as
    `flex: 0 0 25%` / `31.5%`. `core/column`'s `width` attribute compiles to `flex-basis`, so
    the percentages are markup and the `__media` / `__wrapper` / `__body` / `__meta` classes
    and their flex CSS are gone. The list card's nested wrapper collapsed into a plain
    three-column row. Measured at 900px: **225 / 450 / 225**, matching the old
    25% / 50% / 25%, all columns stretching to equal height, meta panel and padding intact.
    `blog-card-wide` measures 284 (31.5%) / 577 with the `spacing|40` gap. Both variations
    gained `core/columns` in `blockTypes`. Core stacks columns below 782px on its own, so the
    hand-written responsive stacking blocks went with them.
  - **Padding, gap, colour and type are block attributes.** `text-align`, `padding`,
    `font-size`, `font-style`, `line-height`, `color`, `background-color`, `border-top` and
    the tag row's rule are all set on the blocks. `core/group` has no `typography.textAlign`
    support, so alignment is set per child, not on the wrapper.
  - **Kept in CSS, because blocks cannot express it:** the three absolute overlays
    (`media-overlay-card__scrim`, `category-card__label`, `team-member-card__overlay`),
    `overflow`/`height:100%` on card roots, `::after`/`::before` content (the read-more
    chevron, the tag `#`, the author comma — a `css` field drops any rule containing
    `content`), and the `!important` weight overrides on `.wp-block-post-terms__prefix`.
    Rules that survived were rekeyed off core's own classes rather than helper classes.
  - Removed 11 dead helper classes referenced by no markup: `.sd-row-stack`,
    `.sd-flex-start`, `.sd-justify-start`, `.sd-no-shrink`, `.sd-sticky-top`,
    `.sd-swap-order`, `.sd-row-reverse`, `.sd-avatar-row`, `.sd-contact-directory`,
    `.sd-footer__widgets` and the dead `.team-member-card__name` scrim strip.
  - Fixed an orphaned token reference in `homepage-safari-gurus.php`:
    `var:preset|spacing|0` is not a slug in this theme's scale (it starts at 5).

- **Spacing set in the editor now renders in the editor.** `blockGap` moved out of the block
  style variation JSONs and onto the block markup — 29 delimiters across 21 files, 20
  `blockGap` declarations removed from 19 `styles/**` partials. Measured on WP 7.1 against the
  `index` template and the homepage.

  The cause: core generates variation CSS differently in the two environments. On the front
  end each variation gets its full layout set — `…-is-layout-flex{gap}`,
  `…-is-layout-flow > *{margin-block-start}`, `> :first-child`, `> :last-child`,
  `…-is-layout-grid{gap}`. **The editor generates none of it** — 86 rules / 34.5 KB of
  variation CSS on the front end against 59 rules / 23.0 KB in the editor, with
  `grep -c 'is-layout-'` returning 0 for the editor. So the canvas fell through to
  theme.json's `:root :where(.is-layout-flex){gap:var(--wp--preset--spacing--60)}`. The four
  Call Us dropdown rows computed **8px on the front end and 37.008px in the editor**; they
  now compute 8px in both, each row carrying its own `wp-container-*` class.

  Worse underneath it: **`spacing.blockGap` on a variation's *own* wrapper is emitted
  nowhere**, front end or editor. Eighteen of the nineteen variations declared one and all
  eighteen were inert — `.is-style-site-footer`, `.is-style-footer-colophon` and
  `.is-style-section-header` carried no `wp-container-*` class at all. Those elements now
  carry one. The intended values are preserved on the markup:

  | Variation | blockGap | Now set on |
  |---|---|---|
  | `dark-page-section` | `spacing\|40` | `template-index-news.php`, `template-category.php` |
  | `light-page-section` | `spacing\|40` | `template-index-news.php`, `template-category.php`, `homepage-dream-trip.php`, `homepage-safari-gurus.php` |
  | `tinted-page-section` | `spacing\|40` | `template-archive-destination.php` |
  | `site-footer` | `spacing\|50` | `footer.php` |
  | `footer-colophon` | `spacing\|20` | `footer.php` |
  | `hero-banner` | `spacing\|10` | `hero-page-banner.php`, `template-archive-destination.php` |
  | `slider-frame` | `spacing\|30` | `homepage-brands.php`, `homepage-tales-from-our-trails.php` |
  | `call-us-dropdown` | `spacing\|10` / `0` | `parts/dropdown-call-us.html` ×4, panel in `header.php` + `safari-expert.php` |
  | seven card variations | `0` | their nine `card-*.php` patterns |
  | `mobile-navigation` | `0` | `header.php` (`parts/mobile-menu.html` already had it) |

  Sites that already declared their own `blockGap` on the markup keep it — an explicit local
  choice outranks a variation default. `brand-page-section`, `section-header` and
  `special-card` are registered but used in no pattern, so their gaps (`spacing|30`,
  `spacing|10`, `0`) have no consumer and are recorded here rather than on markup.

  Verified: front-end computed spacing is **unchanged** across all 203 block elements on the
  homepage (0 differences before vs after); all `styles/**` JSON parses; `php -l` clean across
  `patterns/`; all 723 block delimiters in `patterns/`, `parts/` and `templates/` parse as
  JSON.

### Changed

- **All 39 pattern files now follow core's form exactly.** Measured against the 155 pattern
  files in Twenty Twenty-Four and Twenty Twenty-Five, then brought into line. No design or
  copy change was intended or made. Verified by re-registering all 39 patterns in WordPress:
  every one registers with non-empty content and no leftover raw PHP, block delimiters nest
  and balance in all 39, and the expansions hold their counts — 2 offices ×2 patterns, 3
  planning steps, 3 value columns, 9 Instagram tiles, 4 panels with 4 scrims and 3 arrows,
  and 11 `sdRotatingImages` entries that parse as JSON.
  - **17 `echo esc_html__()` / `echo esc_attr__()` calls → `esc_html_e()` / `esc_attr_e()`.**
    Core uses the `echo …__()` form zero times.
  - **41 variables holding literals deleted**, clearing 11 files — copy strings, uploads
    URLs, inline SVGs, Trustpilot ids, the Instagram tile map, the hero's banner pool. Core's
    patterns declare no top-level variables. Values are written where they are used.
  - **All 5 `foreach` loops and the hero's `for` loop expanded** into literal markup: two
    office blocks (×2 patterns), three planning steps, three value columns, nine Instagram
    tiles, four homepage panels, and the hero's eleven-image `sdRotatingImages` pool. Core's
    patterns contain no loops.
  - **All 4 `phpcs:ignore WordPress.Security.EscapeOutput` suppressions removed** — each one
    existed only because an SVG or a JSON blob was echoed from a variable. Written literally
    into the markup, there is nothing to escape. `homepage-dream-trip.php` already showed the
    correct form.
  - **Translator context added** where a string is short or its role is not obvious —
    `esc_html_x()` / `esc_attr_x()` on `US:`, `RSA:`, `T`, `·`, `Excellent`, `TrustScore`,
    `reviews`, `Start here` and the three team-card hover links. Core uses the `_x` forms
    heavily; the theme previously used none.
  - **`@package sd-theme-2026` added to the 23 pattern docblocks that lacked it**, with the
    blank line WPCS requires. `phpcs --standard=WordPress patterns/` is now silent — it
    reported 45 errors before.
  - **Kept, deliberately:** `$sd_team_archive` in `homepage-safari-gurus.php` and
    `$sd_news_url` in `template-single-post.php`. Both are *guarded* runtime lookups with a
    fallback, so they need a conditional and cannot be inlined — and `get_permalink( 0 )`
    would resolve to the current post, so the guard is load-bearing. Unguarded single calls
    (`get_theme_file_uri()`, `apply_filters()`, `home_url()`) are inlined at the point of use
    instead, as core inlines `get_template_directory_uri()`.
  - **Uploads URLs are now written out literally.** The `$sd_uploads` indirection bought
    nothing — it still held a hardcoded dev host — and the go-live deployment runs a
    find-and-replace over that host by convention. The one copy that replace will not reach
    is in `assets/styles/core-group.css`; it is noted in `patterns/footer.php`.
  - Rule recorded in [AGENTS.md](AGENTS.md), [CONTRIBUTING.md](CONTRIBUTING.md),
    [PATTERNS.md](PATTERNS.md) and `.claude/agents/theme-architect.md` so it does not
    regress.

### Added

- **`patterns/cta-not-sure-where-to-go.php`** — the enquiry band live runs beneath its
  archives, above the Why Choose band: a script heading over the two office numbers with
  "Send us an Email" beneath, on the warm-grey ground. LS-2014 item 4.7. Ported from
  `sd_call_info_section()` (sd-lsx-child/includes/template-tags.php:467), measured off
  /destinations/ 2026-08-26. Structurally the twin of `homepage-lets-make-it-happen.php`
  and follows that file's office loop, Phosphor phone and interim `/contact/` action.
  - **One of four headings on live**, chosen by body class in the old
    `partials/footer-cta.php`. A block theme selects per template, so that conditional
    becomes *which pattern each template includes*; this is the variant 4.7 names, and
    the specials, default and 404 headings are one copy string apart and belong with
    their own templates.
  - **Not attached to a template**, deliberately and for the same reason as
    `why-choose-sd.php`: on live neither band is page content, both are emitted by the
    child theme beneath the archive, so where they land is open on LS-2033.
  - ⚠️ **The US number differs from the rest of the theme.** Live hardcodes
    `+1-844-292-8240` in this one function; everywhere else — including six other places
    on the same live page — it is `+1 646-906-8113`. Live's string is ported rather than
    harmonised, because a toll-free number on a high-intent CTA may be deliberate.
    One line to change if it is not.
- **[`PATTERNS.md`](PATTERNS.md)** — the pattern library documented for editors. LS-2014
  item 4.10. What each of the 39 patterns is, where it belongs, which ones fill
  themselves in from site content, and a symptom table for when one renders empty. Notes
  the two bands that are defined but deliberately not yet placed, so nobody pastes them
  into page bodies and gets them twice when LS-2033 lands. Linked from README, AGENTS and
  CLAUDE.
- **The homepage** — `templates/front-page.html` reduced to a header, nine pattern
  references and a footer, with the last two inline sections lifted into files of their
  own. LS-2014 item 4.9. Measured against live 2026-08-26.
  - **`patterns/homepage-dream-trip.php`** — the opening invitation: the monogram, the
    script heading, the italic standfirst and the "Start here" cue with the SD bird.
    Moved out of the template unchanged; copy wrapped for translation and the uploads
    host lifted into `$sd_uploads`. No design change.
  - **`patterns/homepage-safari-gurus.php`** — the consultant row, rebuilt as a Query
    Loop over the `team` post type. It replaces four hardcoded cards that named Liesl,
    Lise, Camille and Ilze in the markup, with their attachment ids, `/team/…` links and
    email addresses written out four times — correct on the day it was authored and
    stale the first time somebody joins or leaves. Live runs the same row as an LSX Team
    widget, so the loop is what live does, not a new idea.
    - **Which four is declared in the plugin**, as the `role` term `safari-guru`, matched
      off the `sd-safari-gurus-query` class on the `core/post-template`. Not a `taxQuery`
      here, because a Query Loop stores a taxonomy filter as a **term ID** and local, dev
      and live do not share them — the same reasoning as the mega menu's tours column.
      ⚠️ **The term is empty on dev.** Until the four people are tagged the row renders
      the four most recent team members; ordering (`date`/`asc`) reproduces live's once
      they are.
    - **The card is the existing Team Member Card style, used the way live's homepage
      uses it** — the styled group wraps the media and the hover panel only, and the name
      sits below it in neutral-700, the palette's nearest to live's `$brown` #60483b.
      The inline version had put a white `base` block with `contrast` text there, which
      was neither the archive card nor live. The 70px name strip in
      `team-member-card.json` is the *archive* treatment and is untouched.
    - The tagline is not rendered: live stores it in `role` post meta and then hides it
      on this row (`_cta.scss:453`). It belongs to the team archive, LS-2017.
    - **Phones get a button through to `/team/`, not the grid**, as live does — split
      with Block Visibility's screen-size control rather than a CSS media query, per
      `patterns/header.php`. Live's heading sits inside the desktop container, so the
      phone view has no section title; that is reproduced.
- **`styles/blocks/button/link-plain.json`** — a button with no button about it: label
  only, no fill, border or padding. The homepage team card's hover links are plain text
  on live, but their URLs are per-post and only `core/button` exposes a bindable `url`,
  so they have to be buttons; this takes the chrome back off.

### Changed

- **The two Why Choose Southern Destinations patterns are now one.**
  `patterns/homepage-why-choose-sd.php` is deleted and `patterns/why-choose-sd.php`
  carries the full band — the darkened photograph, the three value columns, and the
  Trustpilot score beside the We Are Africa badge. The flat dark version was never a
  second design, only an unfinished one: its own notes recorded the watermark and the
  badge as "not here yet" because the assets had not been ported. `Template Types` now
  covers `front-page` and `page`, and `front-page.html` points at the surviving slug.
  - Trustpilot is embedded with `require`, not a nested `<!-- wp:pattern /-->`. A nested
    pattern reference is dropped on front-end render while still resolving under a
    WP-CLI `do_blocks()` test, so the CLI reports it working. The deleted twin was the
    only file in the theme that used a reference here.

- **The destinations landing page** — `templates/archive-destination.html` plus
  `patterns/template-archive-destination.php`, the destination post-type archive rebuilt
  against live (measured 2026-08-26). Three regions: the LSX Banners `page-banner` as a
  `core/cover` on `is-style-hero-banner`, with the Joe Hand script title left on the wide rail
  and the strapline under it; the `#f7f5f2` intro band as `is-style-tinted-page-section`,
  pairing the archive description with `patterns/safari-expert.php` on live's 7/5 split; and
  the tile grid running the shared `card-media-overlay` tile, unmodified. The scaffold this
  replaces was Ollie's — a `query-title` over a 3-up of image-and-excerpt cards, with no
  banner, no intro band and no expert panel.
  - Live renders the archive `<h1>` and description **twice** and hides the first copy with
    `.lsx-to-archive-header-tour + .lsx-to-archive-header { display: none }`. Only the visible
    copy is ported; reproducing the duplicate would put a second `<h1>` on the page.
  - The archive description is authored in the pattern because there is no block for it —
    on live it is a Tour Operator *setting*, and TO 2.2 ships no archive-description block. It
    is carried verbatim (content is migrated, not rewritten) and is the first thing to become a
    binding when `sd-enhancements` exposes that setting.
  - **Live's per-archive tile crop is not carried.** Live crops this archive flatter than the
    others — `min-height: 240px; max-height: 240px` at `custom.css:1461`, which against its
    ~360px column is 3:2 where tours and accommodation run near-square. Decision 2026-08-26:
    one tile shape across every archive, which is what `media-overlay-card.json` already
    describes. The Media Overlay Card is used exactly as-is, with no archive-scoped override.
  - **The breadcrumb bar is not built.** Live draws Yoast's trail in a 58px strip under the
    banner; breadcrumb output is a filter over a third-party plugin, so by the deactivation
    test it is `sd-enhancements` work.
- **`styles/blocks/paragraph/archive-intro.json`** — the italic standfirst that opens every
  Tour Operator archive, live's `.lsx-to-archive-description`. Size, italic and leading only;
  the brand-500 drop cap it opens with is in `assets/styles/core-paragraph.css`, because live
  gates the cap at 900px and up and an `@media` block inside a `css` field is unwrapped rather
  than honoured.
- **`assets/styles/core-paragraph.css`** — that drop cap, and nothing else. Auto-attached to
  `core/paragraph` by `enqueue_custom_block_styles()`' `core-*` scan.

- **Nine bound card patterns** — `patterns/card-{media-overlay,tour-list,accommodation-list,tour-compact,accommodation-compact,destination-compact,post-grid,post-list,category}.php`.
  The card *styles* landed earlier against static markup; these are the same shapes wired to
  real data, so each one drops into a Query Loop (or, for the category tile, a Terms Query) and
  renders the post in front of it. Deliberately **not** ported: the "Media Overlay Card, with a
  call to action" variant, and the Specials card, which comes with Specials.
  - **Where each field comes from.** Titles, images, excerpts, dates and read-mores are core
    blocks. Taxonomy rows are `core/post-terms`, which hides itself when the post carries no
    terms. Connections and custom fields are bound paragraphs: `lsx/post-connection` for
    `destination_to_tour` / `destination_to_accommodation`, `core/post-meta` for the tour
    tagline, and `sd/post-meta` with the `price-band` format for `price_rating` — that format
    exists precisely because the stored value offers a literal `none`, which every other source
    would dutifully print.
  - **Rows hide themselves.** Each bound paragraph carries Tour Operator 2.2's
    `lsx-{key}-wrapper` class, so `Query_Loop::maybe_hide_varitaion()` removes the whole row when
    the field is empty — including `none` on a price band and connections whose posts have been
    deleted. Without it a missing field renders a bare bold label. No theme PHP is involved; the
    class is the wiring.
  - **Bold labels** are Tour Operator's `prefix` / `prefixBold` paragraph attributes, matching
    `parts/fast-facts-*.html` upstream, rather than markup the binding would have to carry.

### Fixed

- **The blog list card had its composition inverted** — `styles/sections/cards/blog-card-wide.json`,
  re-measured 2026-08-26 and renamed *Blog Card — List*. Both errors came from reading the live
  DOM order literally. lsx-blog-customizer sets `flex-direction: row-reverse` on `.entry-layout`
  above 768px, so the image markup that comes *last* renders *first* — the image leads and the
  body trails, not the other way round. And every `text-align: center` on the title, byline,
  categories and excerpt sits inside `@media (max-width: 767px)` in the child theme, so live is
  **left-aligned on desktop** and centred only on a phone. The first port carried body-leading
  and centred-everywhere, which is neither state. The byline is now one line — date, author,
  categories — as live renders it, and the tag row with its rule is ported for the first time.
- **The listing card's mobile stack never worked**, and the same defect would have hit the blog
  card. A section style's `css` field compiles to `:root :where(…)`, which is **(0,1,0)** —
  `:root` contributes, `:where()` does not — so a single-class rule in `core-group.css` *ties*
  with it and loses on source order, global styles being printed after the block sheet. The
  card-level `flex-wrap: wrap` was therefore discarded while the descendant rules (already
  (0,2,0)) applied, so the media went full-width inside a row that stayed `nowrap` and squeezed
  the body into a ~90px sliver. The two card-level selectors are doubled to (0,2,0), and the
  stretch `height: 100%` is released to `auto` once the card wraps.
- **`core/read-more` was centred in the listing row.** The block library ships it as
  `display: block; width: fit-content`, and a constrained-layout parent's auto inline margins
  then centre it. Pinned back to the leading edge on the list card only; the compact card wants
  the centred default.
- **`.wp-block-post-terms__prefix` was bold in both bylines.** `core-post-terms.css` bolds every
  prefix at (0,1,0) and the css field ties with it; live's bylines carry no bold at all.

### Changed

- **`styles/sections/cards/category-card.json`** — the tile now carries a `neutral-700` ground
  and a 100px min-height so it stands up when the term has no image, which is **every category
  today**: `sd_thumbnail` is registered for `accommodation-brand` only, so `sd/term-meta` returns
  null for `category` and `core/image` renders nothing at all, collapsing the absolutely-positioned
  label to zero height. Live has the same absence and papers over it with a grey placeholder
  JPEG; this is that placeholder without the asset. The label's link is also stretched over the
  whole scrim, so the tile is the target as live's is. The image *binding* is verified working end
  to end — registering `sd_thumbnail` for `category` in `sd-enhancements` is all that is missing.

### Added

- **Six card styles** — `styles/sections/cards/{listing-card-list,listing-card-compact,post-grid-card,blog-card-wide,category-card,special-card}.json`, completing the card set. The list
  card is the variant §12.6 recorded as "measured and ready, blocked on open decision 5": that
  decision resolved the `#F0EBE5` meta strip onto `neutral-200`, and the container stays `base`,
  so the step between body and strip survives. Tours and accommodation share one style — they
  share one CSS rule on live and differ only in what the meta strip holds. `blog-card-wide` is
  the live blog landing row, ported for the first time; it does not replace `blog-card-large`,
  it competes with it, and the decision is Zared's.
- **Card Style Reference page** (local, page 65899) — all ten card shapes on one page with
  static fields, the companion to the Block & Section Style Reference. Every field is hardcoded:
  the point is to settle proportion, colour and interaction before any binding is wired. Closes
  with six open decisions, the first being which of the three blog cards is the landing row.
- **Featured images on the three fixture posts** (local only) — they carried the
  `lsx-placeholder` meta, so any query loop rendered empty frames. Needed for the `blog-card-large`
  comparison and useful for every loop review after it.

- **Trustpilot score badge** — `patterns/trustpilot-score.php`, the band word, the Trustpilot
  mark, the star tile and the `TrustScore 4.8 | 349 reviews` line, reading the live score
  through the plugin's `sd/trustpilot` binding source. Replaces `[tp_show_score]`, which live
  calls three times with three `color=` variants; nothing here sets a colour, so the badge
  inherits whatever ground it is placed on and the three variants collapse into one file.
- **`inc/trustpilot.php`** — answers the plugin's `sd_enh_trustpilot_stars_image` filter with
  the theme's own rating tile. The plugin exposes the rating as a *number* and says explicitly
  that picking the graphic is the theme's job; this is the theme doing that, and the only place
  that knows where the tiles live. Ships the ten **official** Trustpilot RGB tiles as
  `assets/images/trustpilot/stars/`. Their colour is data — the scale runs red at one star to
  green at four and a half — so they are the one asset set deliberately exempt from the token
  rule, and the child theme's `5star-brown.svg` / `5star-white.svg` recolours are **not**
  ported: they only ever rendered because `tp_overall_score()` hardcoded `$stars = 5`.
- **`patterns/safari-expert.php`** — the "Chat to your safari expert" panel (K-01/K-02),
  replacing `sd_lsx_to_contact()` / `sd_lsx_to_enquiry_contact()`. Portrait, eyebrow, name, the
  Call Us disclosure, an email action and the Trustpilot badge. Live's `<h5>` eyebrow above an
  `<h3>` name is a skipped level in the wrong direction; the eyebrow is a paragraph here (it
  labels the panel, it does not head a section) and the name is an `<h2>`.
- **Flag assets** — `assets/images/flags/{us,uk,za,aus}.svg`, ported unchanged from
  `sd-lsx-child/images/`. Live sets them with `content: url(…)` on a `:before`, which cannot be
  sized; they are `core/image` blocks in the `dropdown-call-us` template part now, so each one
  is a real block an editor can swap and the row height is the theme's.
- **`styles/blocks/accordion/call-us-dropdown.json`** — the design for the Call Us disclosure,
  now that it is a `core/accordion`. Panel ground, border, radius, shadow and padding; the
  rows' padding and gap; the label colour; and the number link's weight, colour and hover
  underline — all structured JSON, with four declarations in the `css` field lifting the
  in-flow accordion panel into a pop-out.
- **`assets/styles/core-accordion.css`** — the four things that JSON genuinely cannot hold:
  the caret and the row-wide hit area (both need `content: ""`, which the `css` field mangles),
  the row tint (`:hover` and `:focus-within` are stripped), and the hairlines between rows
  (no structured equivalent for an adjacent-sibling selector). Auto-attached to
  `core/accordion` by `enqueue_custom_block_styles()`' `core-*` scan — no module, no explicit
  registration; the filename is the wiring.
- **`styles/sections/footer-colophon.json`** — the dark bar under the footer photograph,
  live's `footer#colophon`: `primary-600` ground (live's `#41382E`), spacing-20 vertical
  padding (live's 15px), `neutral-400` text brightening to `neutral-300`.
- **The "Follow Us" list's geometry**, in the `css` field of
  `styles/sections/site-footer.json`. Core treats a labelled social link as an icon with a
  caption; live's is an icon beside body copy, so the label's size, colour and margins all
  have to be prised off the icon's font-size.

### Fixed

- **Card images never took their intended size.** Core ships `.wp-block-image>figure>a
  { display: inline-block }` at (0,1,1) and `.wp-block-image img { height: auto }` at (0,1,1),
  while a block-style `css` field compiles to `:root :where(…)` at (0,1,0). Every `width`,
  `height` and the block-level anchor set on a card image was therefore discarded — silently,
  since the rules still appear in the compiled sheet. The shrink-to-fit anchor was the worse
  half: a row of cards looked correct as long as every photograph was wider than its card, and
  broke the moment a square one appeared. Card image sizing now lives in
  `assets/styles/core-group.css` at (0,2,1); structure, spacing and colour stay in the JSON.
- **`@media` inside a `css` field is unwrapped, not honoured.** The wide blog card's
  small-breakpoint rule compiled without its query, so the trailing image was hidden at every
  width instead of below 781px. Moved to `core-group.css`. No `@media` belongs in a `css` field.
- **Linked card images sat on a text baseline.** The inline `<a>` generated a line box, leaving
  a strip of card ground below every photograph. Fixed with `line-height: 0` on the figure and a
  block-level anchor, applied across all eight cards including the two that shipped earlier.

### Changed

- **The Call Us disclosure is a `core/accordion`, not a plugin block.** WordPress 7.1 ships
  everything `sd/call-us` was written to provide: `core/accordion-heading` renders a real
  `<button>` that resets the UA styling and inherits type, with `aria-expanded` and
  `aria-controls`, and the panel state runs through the Interactivity API. All four defects the
  block was built to fix — a `<a href="#">` trigger, no `aria-expanded`, a hover-only copy on
  the expert panel with no keyboard path, and two widgets with two number lists — stay fixed,
  upstream. Two things core does *not* do are Escape and click-outside dismissal; both are
  behaviour, so they belong in `sd-enhancements` if they come back. → LS-2033

  The reason to move is that a non-core block has no JSON styling surface. `sd-call-us.css`
  opened by saying so: `styles.blocks` reaches only a block's own wrapper, and the toggle, the
  panel and the rows were all descendants of it. Each of them is a block in its own right now.
  **300 lines of CSS became 130 of JSON and ~40 of CSS**, and six class hooks were retired
  outright — `.sd-call-us__toggle`, `.sd-call-us__panel`, `.sd-call-us__number`, the
  `.sd-call-us--start` / `--end` placement pair, and the `.sd-header__call-us` and
  `.sd-expert__call` instance hooks. Nothing authored carries an `sd-*` class for this
  component now.

  It also fixes what prompted the change: with `sd/call-us` unregistered, the editor collapsed
  the block and left only the bare `wp:template-part` inside it — no trigger, and no way to
  edit the numbers. Every part of it is a core block, so every part is editable.

  `parts/dropdown-call-us.html` still holds the four numbers and nothing else, so the header
  and the safari expert panel go on reading one list. Each row is a `core/group` — a 28px
  `core/image` flag beside a font-size-200 paragraph carrying the label and the `tel:` link —
  and the whole row is the hit area, as it is on live. The number does not underline on row
  hover; the tint is the affordance, and both at once makes the list flicker under the pointer.

  Two fixes on top of the first pass, both worth knowing about beyond this component:

  - **A closed accordion panel needs `display: none`.** The Interactivity API closes it with
    `hidden="until-found"`, which the HTML rendering spec maps to `content-visibility: hidden`,
    *not* `display: none`. The panel's box went on rendering — ground, border, radius, shadow —
    and since it is absolutely positioned 8px below the trigger it read as a white bar under
    the closed accordion. The trade is `beforematch`: in-page find and hash links can no
    longer open the panel. Right for four phone numbers in a header, wrong for a FAQ.
  - **`neutral-100` is `#FFFFFF`** in this palette — the same value as `base`. The row hover
    was written against it and did nothing at all. The first tinted step above white is
    `neutral-200`. Worth remembering before reaching for `neutral-100` as a hover or stripe
    colour anywhere else in the theme: it is not a tint, it is white.

  The trigger's weight now travels on the block. `sd/call-us` supported
  `typography.__experimentalFontWeight` but dropped the style-engine output for it, so the
  attribute silently rendered at 400 and live's 700 had to come from a class in a stylesheet.
  `core/accordion` serialises it properly.

  One cost, recorded plainly: core's accordion always wraps its toggle in a heading — the
  block's save is `"h" + headingLevel` and there is no opt-out — so the header gains an `<h3>`
  inside the banner landmark that live does not have. Level 3 in the header and level 4 on the
  expert panel, so neither competes with a page's own `<h2>`s.

- **The footer addresses its media by URL and carries no bespoke CSS classes.** Two changes
  to one pattern, both simplifications.

  Its thirteen assets were resolved per environment through
  `SdTheme2026\attachment_id_by_path()`, against paths seeded identically on local, dev and
  live. That cost a lookup per asset, a filter-invalidated object-cache map, a `sizeSlug`-aware
  URL resolver and a PHP module of its own for the one rule it could not express — and bought
  nothing the project needs: dev holds the real media, dev is deployed to live wholesale, and
  local only needs the images to render. `patterns/footer.php` now writes dev's URLs plainly,
  built from a single `$sd_uploads` string, and local pulls them from dev. `mobile-footer-bg-img.jpg`
  was missing from dev's library and has been uploaded to the path the rule expects.
  ⚠️ **These are dev URLs and must be rewritten at go-live** — one string in
  `patterns/footer.php` and one in `assets/styles/core-group.css`, in the same pass as the
  database domain search-replace.

  The six `.sd-footer__*` hook classes are gone, and with them every footer rule in
  `assets/styles/core-image.css` and `core-social-links.css`. What core can express as a block
  attribute now is one — the brand mark's and badge's `width`, and the Instagram tiles'
  `width`/`height`/`scale`, which core serialises as `object-fit: cover` — so those sizes are
  visible in the editor instead of hidden in a stylesheet behind a class. The "Follow Us"
  list's geometry moved into `styles/sections/site-footer.json`' `css` field, where plain
  specificity beats core's selectors without `!important`. Only the two `@media` blocks remain
  in `assets/styles/core-group.css`, scoped to `.is-style-site-footer`, because `@media` does
  not compile in a `css` field.

- **The header's Trustpilot badge is two static linked images, not the bound badge.** It used to
  `require patterns/trustpilot-score.php`, which was a misreading of live. Measured on live
  2026-08-21 — computed styles, not source — the header badge renders **two of its four children**:

  | Child | Live header |
  |---|---|
  | `h3.tp-wording` "Excellent" | `display:none` |
  | `a.tp-review-logo` → `tp-logo.svg` | visible, 100×24 |
  | `a.tp-review-stars` → `5star.svg` | visible, 143×25 |
  | `div.tb-score` "TrustScore 5 \| 349 reviews" | `display:none` |

  by `#tb-horizon-review .tp-wording{display:none}` and
  `.sd-top-menu-wrapper #tb-horizon-review .tb-score{display:none}` — verified against all 18
  stylesheets and every inline `<style>` on the page, so nothing later in the cascade puts them
  back. The two hidden children are the *only* things the Trustpilot API supplies, so live's
  header makes an API call whose entire visible product is `display:none`, and the badge there is
  two static SVGs. Binding it and then hiding three of five children with CSS would have paid for
  a live lookup to render nothing.

  Both images link to the review page, as live's do; unlike live's they have accessible names —
  live's two `<img>`s carry no `alt`, so both its links are nameless (WCAG 2.4.4). ⚠️ The
  `stars-5.svg` tile and the "5 out of 5" in its `alt` are **authored, not measured**: accurate
  today (live reports TrustScore 5 from 349 reviews) and silently wrong the day the rating moves.
  They must change together.

  `patterns/trustpilot-score.php` is unchanged and still used by `patterns/safari-expert.php`,
  where the opposite is true: `.tb-score` *is* visible on live (142×13, measured on
  `/accommodation/table-bay-hotel/`), so the score and review count are real output there.
  ([LS-2014](https://linear.app/lightspeedwp/issue/LS-2014))
- **The `.sd-header__utility` Trustpilot note in `assets/styles/core-group.css` is corrected.** It
  had recommended re-adding rules to hide the badge's band word, score and count in the header.
  Don't: the header no longer renders them, so there is nothing to hide. Badge text styling should
  be scoped to the expert panel, never to the header.
- **The header is a faithful port of live's, measured rather than approximated.** Every value
  below was read off the rendered live site at 1440px on 2026-08-20, not inferred from its
  stylesheets — several of live's declarations don't mean what they look like. The layout is
  unchanged from live; what is modernised is the underline weight, the search's motion and the
  removal of a duplicated landmark.
  - **The enquiry CTA moves into the utility bar**, where live has it — a square brand-500
    plate filling the bar's full 56px and flush to the content edge. It had been down beside
    the search, which put two competing actions on the nav row and left the top bar half empty.
  - **The logo overhangs**, as on live: 310×84, lifted 24px so the elephants rise out of the
    80px row into the bar above it. Live's own `bottom: 45px` is *not* the lift — it is measured
    from a static position its own `margin-bottom: -30px` has already moved, so the net offset
    is about half of it. 24px is what the rendered page measures, and it is why both bands now
    share one `neutral-200` ground: the mark crosses the seam, so a second colour or a border
    there would cut through it.
  - **The header row is `neutral-200` with a shadow**, not white with a hairline border. Live
    paints the ground on the shared sticky wrapper and leaves the row transparent; one ground on
    both bands is the same result with one layer less.
  - **The search is an icon that folds out.** `buttonPosition: button-only` is not a look — on
    WordPress 7.0 it is what makes `core/search` a disclosure, with core's Interactivity module
    binding `aria-expanded`/`aria-label`, toggling the field's hidden class and closing on
    Escape and focus-out. The theme only draws it, overriding core's *in-flow* growth so the
    field unrolls leftwards over the header instead of reflowing the row as it opens — which is
    what live does and what the ATI theme's header does. No `isSearchFieldHidden` attribute:
    core dropped it with the Interactivity rewrite and it now does nothing, so ATI's copy of it
    is not carried across.
  - **No chevrons on the top-level row.** Live renders a Bootstrap `<span class="caret">` and
    then hides it. Ollie Menu Designer hardcodes its own chevron into the mega-menu toggle with
    no attribute to disable it, so it is hidden from the `is-style-main-navigation` variation —
    which leaves the mobile nav's disclosure indicators, on `is-style-mobile-navigation`, alone.
  - **Below 992px the utility bar carries the Trustpilot mark alone, centred**, exactly as live
    does. Nothing is lost: `parts/mobile-menu.html` already holds both the enquiry button and
    the numbers list. Without it the bar broke — at 390px "Call Us Today" wrapped onto three
    lines and the plate read "GET IN TOU / CH". Note that live uses **992px** for the utility
    bar and **1200px** for the nav swap; they are two different numbers, not one rounded twice.
  - **The header's Trustpilot badge shows the mark and the star tile only.** Live hides the band
    word globally and the score line in the header. Scoped to `.sd-header__utility`, so the
    badge keeps its full form everywhere else it is placed — delete that one rule to show it all.
- **The two responsive `display: none` rules are marked as placeholders.** The
  desktop/mobile nav swap at 1200px and the utility bar's 992px rule both hide blocks from
  a stylesheet, which is the wrong home for it: hiding belongs on the block as a visibility
  control, the way kwv-theme-2026 does it. The Block Visibility plugin is planned for this
  site but not installed yet, so both media queries stand in until it is and then come out.
  Flagged in place in `core-navigation.css` and `core-group.css` so neither is mistaken for
  a settled decision.
- **Sticky is a block setting now, not a stylesheet.** It travels in the group's own
  `style.position`, so it is editable in the Site Editor. It also *works*: the old
  `position: sticky` on `.sd-header__row` could never fire, because a sticky box cannot leave
  its containing block and the row's was the 136px header itself — at scrollY 1096 the row's
  viewport top measured -1008, having travelled the ~56px its parent allowed. Live sticks the
  whole header with both bands together, so that is what this does. Live's further 22px
  compression on scroll is deliberately not ported: it needs a scroll listener to toggle a
  class, which is behaviour, not design.
- **The Call Us disclosure takes live's type** — brand-500, font-size 300, bold. The weight is
  applied in `assets/styles/sd-call-us.css` rather than on the block: `sd/call-us` declares
  `typography.__experimentalFontWeight` but its render callback never emits the style-engine
  output for it, so the attribute serialises to nothing and the button renders at 400. Setting
  it on the block would look right in the editor and be wrong on the front end.


- **Navigation is `wp_navigation` content, referenced by `ref`** — the header's menus are
  built and edited on dev under Appearance → Navigation, and the theme references them by ID.
  Dev is deployed to live wholesale, database included, so the IDs travel with the content
  they point at. Menu *items* link by entity (`kind: post-type` with an `id`) wherever a post
  or page exists behind them; only the `/search/...` filters, which have no post, stay custom
  links. That is not cosmetic — three About Us pages are children of the About Us page, so the
  flat URLs they were first authored with (`/why-book-with-us/`) were simply wrong; the
  correct path is `/about-us/why-book-with-us/`.
  Local is a fixture environment with no menus, so the header renders core's fallback there.
- **The Call Us dropdown's menu was flattened** out of the classic-menu structure it was
  migrated in, dropping the Font Awesome `<i>` markup and `<br>` from the labels.
  (This entry originally said the dropdown was an `ollie/mega-menu` block with a
  plugin-supplied disclosure. Neither half was true: it shipped as a `core/navigation` submenu,
  and no such disclosure existed in the plugin. It is `sd/call-us` now, and the disclosure is
  real — see further up this release.)
- **`parts/mobile-menu.html` rewritten** — logo, search, the tiered mobile navigation, a
  full-width "Get in touch" and the Call Us numbers, on the dark panel live uses. Rendered
  into the overlay by Ollie Menu Designer's `mobileMenuSlug`. Desktop and mobile navigation
  are two blocks swapped by a media query at 1200px, live's own header breakpoint —
  kwv-theme-2026 does this with the Block Visibility plugin, which is not installed here.
- **Trustpilot badge** — originally the static mark in the utility bar, attachment 50269
  (`2019/07/trust-pilot-badge.png`), linked to the reviews page rather than live's `href="#"`.
  **Superseded further up this release:** that was the wrong one of live's two Trustpilot
  marks — the static `trust-menu` PNG rather than the API-backed `#tb-horizon-review` badge —
  and the utility bar now carries `patterns/trustpilot-score.php` instead.

### Fixed

- **The main navigation had no styling at all on the front end.** `main-navigation.json`'s
  selector hopped through `.wp-block-navigation__responsive-container`, but the header's desktop
  nav sets `overlayMenu: "never"` and core then emits `nav > ul.wp-block-navigation__container`
  with no responsive wrapper — so the selector matched nothing and the nav rendered at core's
  defaults: sentence case, 19px, weight 500, black. It now matches live: uppercase, font-size
  200, regular, `neutral-800`, 10px inline padding, on the row's bottom edge.
- **The nav's hover underline was invisible and the wrong colour.** It rode the same dead
  selector, and was `brand-300` at 1px. It is `brand-500` — live's `#cc7f16` exactly — at 2px,
  flush on the header row's bottom edge where live's 5px `border-bottom` sits, sliding in from
  the centre. The label takes the same colour, which needs `!important` for a reason worth
  knowing: the resting colour is `!important` itself (a variation's `css` is emitted inside
  `:root :where()` at (0,1,0) and loses to core's (0,3,0) `color: inherit` reset), and once the
  resting value is important no amount of specificity beats it.
- **The mobile hamburger showed at every width.** `.sd-nav-mobile { display: none }` sat at
  (0,1,0), and core prints `.wp-block-navigation-is-layout-flex { display: flex }` *inline in
  the body*, after this stylesheet and at the same specificity — so it won on source order. Both
  halves of the desktop/mobile swap now carry `.wp-block-navigation` as well.
- **The header rendered two nested `banner` landmarks.** WordPress already wraps a template part
  whose area is `header` in a `<header>`, so the pattern's own `tagName: header` produced
  `header.wp-block-template-part > header.sd-header`. A screen-reader user navigating by landmark
  hit "banner" twice for one header with no way to tell them apart. The outer wrapper is the
  landmark; the pattern's group is a `div`.

- **The header's Trustpilot mark is the real badge, not a static image.** The previous pass put
  `uploads/2019/07/trust-pilot-badge.png` in the utility bar — that reproduced the wrong one of
  live's *two* Trustpilot marks. Live carries both: `#tb-horizon-review.tb-color-brown`, the
  API-backed badge, and a `trust-menu` nav item holding a PNG linked to `#`. The second is
  dropped (it duplicates the badge beside it and its link goes nowhere) and the first is now
  built properly. The badge is inlined with `require`, **not** a nested `<!-- wp:pattern -->`
  reference, which is silently dropped on front-end render while resolving fine under WP-CLI —
  so `trustpilot-score.php` stays an independently insertable pattern.
- **The Call Us dropdown is `sd/call-us`**, not a `core/navigation` submenu and not an
  `ollie/mega-menu` block. A real disclosure button with `aria-expanded`, supplied by the
  plugin. This drops the `wp:navigation` block from the utility bar and with it menu **65879**
  ("SD Utility Navigation"), which existed only to hold two phone numbers — it can be deleted
  on dev once this ships.
- **`parts/dropdown-call-us.html` now holds the four office numbers** — US, UK (toll free),
  South Africa and Australia (toll free), each with its flag class. It stays a template part
  rather than becoming inline pattern markup precisely so the header utility bar and the safari
  expert panel read the *same* file: live builds the widget twice and the two drifted to two
  numbers and four. It also keeps the numbers editable in the Site Editor, which is right —
  they are content.
- **The Call Us panel is softened from live.** Live draws a hard-edged white rectangle with a
  1px grey border butted flush against the trigger. This has a `200` radius, a `300` shadow, a
  caret notch pointing at its button, hairline row separators and a hover tint. The 8px of
  clearance between button and panel is affordable only because this opens on *click* — live's
  expert-panel copy opens on hover, where a gap would drop the panel mid-travel.
- **`assets/styles/core-navigation.css`** — the `.sd-header__call-us
  .wp-block-navigation__submenu-container` rules are removed. They styled a submenu container
  that no longer exists now that Call Us is not a navigation block.

- **Site header rebuilt as blocks** — LS-2014 task 4, the header half. A slim utility bar
  (Trustpilot link, Call Us dropdown) above a main row (logo, mega-menu navigation, search,
  "Get in touch"), replacing the scaffold. Four mega-menu panels ship as `menu`-area template
  parts — `parts/mega-menu-{destinations,tours,accommodation,about}.html` — each a three
  column layout of curated links, a contextual column and a featured card, which fills the
  dead space live leaves in columns two and three without inventing content.
  ([LS-2014](https://linear.app/lightspeedwp/issue/LS-2014))
- **Sticky header, in CSS only** — `assets/styles/core-group.css` sticks the *main row*, so
  the utility bar scrolls away and the row pins. Sticking the header and offsetting it by the
  utility bar's height would need that height as a number, and it is sized by clamp()-based
  spacing and font-size presets. Live does the same job with jQuery `scrollToFixed` via
  `lsx_sticky_menu_selector`; that filter and its script are replaced, not ported. No
  JavaScript, so nothing here is the "sticky-header behaviour" `inc/README.md` bans.
- **Mega-menu panel styling** — `assets/styles/ollie-mega-menu.css`, attached to
  `ollie/mega-menu` by the new `inc/mega-menu.php`. A non-core block is outside
  `enqueue_custom_block_styles()`' `core-*` scan, and the panel container is generated by the
  plugin's own `render.php`, so no block-style variation can reach it.
- **`parts/dropdown-call-us.html`** — the Call Us dropdown panel. (Superseded further up this
  release: it is the panel of the plugin's `sd/call-us` block now, and it holds four numbers
  rather than two.)
- **`styles/sections/utility-bar.json`** — the utility bar's section style.

- **The footer is a faithful port of live's, measured rather than approximated**, on the same
  basis as the header above: every value read off the rendered live site at 1440px and 390px on
  2026-08-20. It replaces a scaffold that shared almost nothing with live — a "Plan your
  journey" CTA, a `core/site-logo`, an empty `core/navigation` for contact details, an empty
  `core/social-links`, and a black `contrast` ground where live has a photograph.
  - **The sunset photograph is the footer's ground.** `#footer-widgets` over
    `footer-bg.jpg` at `cover` / `center bottom` with a 615px floor
    (`custom.footer.widgets-min-height`), and `mobile-footer-bg-img.jpg` below 600px.
  - **Four columns**: the brand mark with the We Are Africa 2024 Tribe Member badge; Contact Us;
    Follow Us as six icon-and-label social links; and Instagram as a 3×3 grid of nine tiles.
  - **All thirteen footer assets now come out of the database**, each seeded at the same
    uploads-relative path it holds on live — `2019/07/footer-logo.svg`,
    `2019/07/instagram-1…9.jpg`, `2024/02/WAA-Tribe-Member-Badge-2024-34-white.png` — and
    referenced by that path rather than by ID or URL. This moves `footer-bg.jpg` and
    `mobile-footer-bg-img.jpg` out of the "theme assets to port" list in style.md §9.1: they
    were child-theme background images with no uploads path of their own, and are now media at
    `2026/08/`. Their WebP conversion, which §9.1 also calls for, is still outstanding.
  - **The Instagram column is nine static images, not a feed.** `inc/README.md` reserves "the
    Instagram feed" for the companion plugin and that reservation stands — but live has no feed
    to reserve: it is a hand-written 3×3 table of JPEGs uploaded in July 2019. Wiring it to the
    real API would be new scope.
  - **`#footer-cta` is not reproduced.** Live's third footer region is a widget area that
    renders empty on every page type measured (`/`, `/blog/`, `/contact/`, `/about-us/`,
    `/accommodation/`, a tour single). The scaffold's conditional CTA stood in for it; a
    scaffold for a region that does not exist reads as a missing feature.
  - **One `contentinfo` landmark, not two.** The pattern's outer group was `tagName: footer`
    inside the template part's own `<footer>`; it is a `div` now, the same correction
    `patterns/header.php` carries for `banner`.
  - **Nine described tiles, not nine identical ones.** Live gives every Instagram thumbnail
    `alt="instagram"` and wraps the grid in a single link. Nine links cannot share one
    accessible name, so each tile is described — accurate to the photograph, but unverified
    against the original posts and worth a client pass.
  - **The colophon's text is deliberately not live's colour.** Live sets the credit and the
    terms links to `#847C73` on `#41382E`, which measures **2.54:1** — a WCAG AA failure on
    15px type. `neutral-400` measures **6.33:1** and passes AA and AAA; `neutral-500` was
    rejected at 3.95:1. Live's hover, `#DCD6C9`, is kept exactly as `neutral-300`, so the
    gesture is live's and only the resting value moves.
  - **Content sits on `alignwide` (1520px), not live's 1170px.** Live's 1170 is Bootstrap's
    `.container`, shared by every region of the old site; this theme's shared rail is
    `wideSize`, which `patterns/header.php` already uses.
  - **Mobile drops live's `min-height: 1400px`**, which leaves ~400px of empty photograph
    between the Instagram grid and the copyright bar. Trimming it moves the background's anchor
    to `50% 0%` at the same time: bottom-anchoring a 1077px band over a 1431px-tall crop slid
    the dark water up behind the "Follow Us" and "Instagram" labels, measured at roughly 1.5:1.
    Top-anchoring keeps live's relationship between the text and the sky at the shorter height.
  - **The mobile stack is centred at 767px**, as live does — and at live's 767px, not core's
    781px, because the stack and the centring are two separate decisions on live and the
    fourteen pixels between them are where the columns are stacked but still left-aligned.
  - **Instagram tiles are square and cropped.** The nine source files are 83×82, 81×83 and
    83×81 — nominally square, actually three shapes. Live's paired `max-width`/`max-height`
    squashes whichever axis overshoots; a free height left the grid's rows ragged at 81–85px. A
    fixed square with `object-fit: cover` is the only option that is both undistorted and
    aligned.
  - **`styles/sections/site-footer.json` is rewritten** for the photograph band: it had been a
    `contrast`/`base` dark block, which is neither live's ground nor its type colour.
  - **`styles/blocks/navigation/footer-navigation.json` now describes the colophon's terms
    nav**, where live's `#footer-navigation` actually is, rather than the contact-details list
    the scaffold applied it to. Its `spacing.blockGap` is dropped: a variation's `blockGap` is
    not emitted as the flex layout gap, so it silently did nothing and the nav inherited
    spacing-60 (37px measured). The gap is on the block now, and the new 1px×14px dividing rule
    between the two items halves the same token.

### Removed

- **`parts/mega-menu.html` and `parts/dropdown-menu.html`** — unused kwv-derived scaffolds,
  referenced by nothing, superseded by the four named panels and the Call Us menu.

- **Block and section styles ported from the live site** — 18 styles covering LS-2013 tasks
  3.6 and 3.7, measured off the rendered page on 2026-08-20 rather than the theme repos,
  because roughly half the live styling is generated at render time from `wp_options`.
  Provenance, the JSON-vs-CSS routing table and ten open decisions are recorded in
  `style.md` §12. *(LS-2013)*
  - **Buttons** — the two most-used treatments are now **core's own variations**, defined in
    `theme.json` under `styles.blocks.core/button.variations`: `fill` carries the live `.btn`
    rule (`brand-500` plate, uppercase heading face at semi-bold, square) and `outline` carries
    the live brand-bordered secondary. Putting them there rather than in `styles/blocks/button/`
    means a button is correct with **no style picked in the editor**. Two ground-specific
    treatments remain partials: `outline-light` for dark and brand grounds, and `accent-cta`,
    an `accent-400` plate with a black label for use inside a brand-filled panel where a Fill
    button would be brand-on-brand and disappear — at 12.08:1 it is the most legible button in
    the theme, against roughly 2.4:1 for the brighter orange with white text that live uses there.
    Every button shares one box: a single padding pair in `settings.custom.spacing.button`
    (14px / 32px), zero radius, and a 2px border that is simply transparent on the filled
    variants — which is what keeps a filled and an outlined button exactly the same height.
  - **Headings** (`core/heading`) — `section-title` and `section-title-left`, carrying the
    site's most repeated device: an 80×2px `accent-500` rule under the title. Plus
    `script-accent`, the Joe Hand line live uses for warmth.
  - **Sections** (`core/group`) — `light-page-section`, `tinted-page-section` and
    `dark-page-section` rewritten from KWV's black-and-white generics to the measured SD
    grounds; `section-header` stripped of the brand bottom-border KWV gave it, which appears
    nowhere on live; and `brand-page-section`, `hero-banner` and `slider-frame` added.
  - **Cards** — `media-overlay-card` added, reproducing the archive card whose scrim
    *lifts* to transparent on hover rather than deepening; `team-member-card` rewritten to
    the live 70px name band. Both are keyboard-reachable via `:focus-within` and honour
    `prefers-reduced-motion`.
  - **Pagination** — `assets/styles/core-query-pagination-numbers.css` rewritten to the live
    40×40 plates with an inverted current page, drawn in `primary-500` at 1px rather than
    live's 2px `#B4A48C`. `neutral-400` measures 1.9:1 against white, so the taupe borders
    barely register and the white label on the filled current page is weak; `primary-500` is
    9.48:1 in both directions.
- **`assets/styles/core-heading.css`** — new per-block sheet holding the section-title gold
  rule. It cannot live in the style JSON: a `css`-field rule containing `content: ""` is
  dropped whole, so a `::after` pseudo-element is unreachable from there. *(LS-2013)*

- **`assets/fonts/LICENCES.md`** — the font licence register: per-face rights, evidence,
  the obligations that ride with each commercial licence, and why Optima is still blocked.
  Client licences were supplied 2026-08-18 and are held in `docs/SD Fonts & Licenses/`.
  *(LS-2012, LS-2641, LS-2642)*
- **`belleza` and `la-belle-aurore` font-family presets** — Belleza and La Belle Aurore are
  now families in their own right rather than faces registered inside `heading` and
  `accent`. This is not cosmetic: WordPress ignores a `fontFace`'s own `fontFamily` (see
  **Fixed**), so a fallback face can only be declared correctly from its own preset.
  `heading` and `accent` keep their slugs and stacks, so all 62 existing
  `var:preset|font-family|heading` references are untouched. *(LS-2013)*

- **`style.md`** — the token map and asset inventory for the theme: measured token state
  across Figma, the live site and `theme.json`, the bundled font layer, the WCAG contrast
  constraints, and the live asset inventory with per-asset porting decisions.
  Companion to the workspace audit report
  (`.github/reports/sd-design-audit-2026-08-12.md`). *(LS-2012)*
- **`assets/fonts/` — 13 WOFF2 faces (474 KB)**, converted from the live sources
  with `woff2_compress` and registered as `fontFace` entries in `theme.json`:
  - `Optima` 400, 500 and 600–700 — the 600–700 face is the live site's Optima Demi Bold,
    declared as a weight *range* so `h1`/`h2` (700) and `h3` (600) both resolve to the real
    cut instead of synthesising.
  - `Belleza` 400 — registered as a face in its own right so the fallback in the `heading`
    stack actually resolves, and so it works as a standby if the Optima licence does not
    clear.
  - `Open Sans` 300/400/600 in normal and italic plus 700 normal (7 faces), each with its
    own weight and style rather than the 11-file single-`src` stack used on live. Weights
    800/900 and the 700 italic were dropped as unneeded. Requests above what is bundled
    resolve to the nearest real face per CSS font matching rather than synthesising — body
    text at 800/900 renders Open Sans 700, and heading text at 900 renders Optima's
    demi-bold cut. Unused weights across all families will be pruned at the end of the
    rebuild.
  - `Joe Hand` 400 and `La Belle Aurore` 400 for the `accent` family.
- `settings.color.palette`: **`accent-100` … `accent-900`** — a **yellow** ramp anchored on
  `#E6AD10` at step 500, the gold used in several places on the live site (22 references)
  that previously had no token. Figma's `accent` rows still hold the `brand` values they were
  seeded with; the ramp was confirmed as intended-but-unfinished and derived here by mirroring
  `brand`'s construction (same relative chroma profile, hue held at the anchor's 83°). The
  lightness skeleton is rebuilt around the anchor rather than reused from `brand`: `#E6AD10`
  measures L\* 74, where `brand-400` sits, so reusing `brand`'s skeleton would have made the
  ramp non-monotonic with step 400 darker than 500. Yellow is a dark-background colour — for
  gold text on a light background use `accent-700` (7.08 AAA). *(LS-2012)*
- `settings.color.palette`: **`primary-100` … `primary-900`** — Figma's warm brown ramp,
  inserted after `neutral-900` to match Figma's collection order. With `accent-*` the palette
  is now **46 entries, exactly matching Figma's 46 colour variables** (2 + 9 + 9 + 9 + 9 + 8).
- `settings.custom.borderWidth`: `0 · 1px · 2px · 4px · 8px`, from Figma's Border
  collection. Emits as `--wp--custom--border-width--{0,100,200,300,400}`.
- `settings.custom.layout.fullWidth`: `1920px`, from Figma's Layout collection — no core
  token exists for it. Corroborated by Figma's 1920px reference capture.
- Initial scaffold, derived from `lightspeedwp/kwv-theme-2026`.
- Core WordPress templates: `index`, `front-page`, `page`, `single`, `archive`,
  `category`, `tag`, `search`, `404`, plus the `page-no-header`, `page-no-title`
  and `page-with-sidebar` custom templates.
- Tour Operator template stubs — `archive-*` and `single-*` for `accommodation`,
  `destination`, `tour`, `review`, `special` and `team`, and `taxonomy-*` for
  `travel-style`, `accommodation-type`, `accommodation-brand`, `facility` and
  `continent`. Every one carries a single `<main>` landmark.
- Template parts: `header`, `footer`, `sidebar`, `mega-menu`, `dropdown-menu`,
  `mobile-menu` (two-tier), `single-hero`.
- Ported agent guidance — `AGENTS.md`, `CLAUDE.md`, `DESIGN.md`,
  `CONTRIBUTING.md`, `inc/README.md` — plus 18 skills and 3 personas under
  `.claude/`, so the theme can be worked on as a standalone checkout.

### Changed

- **Body copy is `neutral-700`, not `contrast`.** `styles.color.text` moved off black. It
  measures 8.04:1 on `base` against live's 7.98:1 — within 0.06, so the page reads at the
  weight it does today while staying AAA. `neutral-800` was rejected at 13.24:1 as materially
  heavier than the site has ever been. *(LS-2013)*
- **Only `h2` is uppercase.** `textTransform` moved off `elements.heading` and onto
  `elements.h2`, so h1, h3 and h4 return to sentence case as live has them. `h6` lost its
  uppercase with the global rule — that was a base-theme label treatment rather than part of
  the hierarchy, and live uses no h6, but it is a one-line restore if the eyebrow style is
  wanted. Section headings are unaffected at any level, because `section-title` sets its own
  uppercase. *(LS-2013)*
- **Media Overlay Card** — resting scrim raised from live's 30% to 45% so the title holds
  against a bright photograph, title up one step to preset `500`, and the chevron enlarged to
  1.15em with an optical nudge onto the cap-height centre. *(LS-2013)*
- **Slider navigation** — arrows are now a 44px round target with a 32px glyph, flex-centred,
  with both vendors' pseudo-element glyph styles reset so the centring governs. The Slick dot
  row is normalised so the dots sit on the arrows' centre line. *(LS-2013)*

- **`joe-hand-400-normal.woff2` replaced with JOEBOB's official webfont build** — the
  licensed file (`joehand_2_15-webfont.woff2`, **532 glyphs**, 44.5 KB) rather than the
  226-glyph copy converted from the live site. Better coverage *and* the file the licence
  actually covers. *(LS-2012)*

- Identity: `Theme Name: Southern Destinations 2026`, PHP namespace
  `SdTheme2026`, text domain `sd-theme-2026`, block-style handles
  `sd-theme-2026-block-*`, pattern namespace `sd-theme-2026/*`.
- `theme.json`: schema pinned to `wp/6.9`; `brand-*` ramp replaced with a
  **provisional** Southern Destinations ramp anchored on the live site's
  `#CC7F16`; font families replaced with the live site's stacks (Optima /
  Open Sans / La Belle Aurore) with no bundled faces pending licensing.
- **`neutral-200` … `neutral-900` re-derived as a warm ramp** from the live site's measured
  neutrals (hue ≈78°, chroma peaking mid-ramp), replacing the inherited pure-grey values.
  `neutral-200` is now exactly `#F7F5F2`, the live site's dominant section background.
  The outgoing ramp's L\* skeleton was preserved deliberately, so contrast is held within
  ±0.05 at every step and none of the 71 existing `neutral-*` references regress. *(LS-2012)*
- **`brand-*` ramp replaced with Figma's values** at the 8 steps that diverged. `brand-500`
  `#CC7F16` is unchanged — all three sources already agreed on the anchor. No ramp step
  other than 500 appears on the live site, so nothing visible changes. *(LS-2012)*
- **Font families replaced with the live-measured values**, Figma's typography table being
  demonstrably wrong (it names Joe Hand as the heading face; the live heading face is Optima
  Demi Bold at 31 uses against Joe Hand's 8 as a script accent):
  - `heading`: `Optima, Belleza, sans-serif` — was `Optima, Belleza, Palatino, Georgia,
    serif`. The serif fallback chain was wrong; Optima is a humanist sans.
  - `accent`: `"Joe Hand", "La Belle Aurore", cursive` — Joe Hand added ahead of
    La Belle Aurore, matching live.
  - `body` unchanged — already correct, with richer system fallbacks than live.
- Header, footer and front-page rebuilt as clean scaffolds — the inherited ones
  carried hardcoded navigation `ref` IDs, uploads URLs and a Gravity Forms
  embed with inline hex.

### Removed

- **The `cta`, `outline-dark` and `raised` button styles.** `cta` became core's `fill` and
  `outline-dark` became core's `outline`, both now in `theme.json`; the six `is-style-cta`
  references in `patterns/header.php`, `patterns/footer.php` and `templates/front-page.html`
  were repointed to `is-style-fill`. `raised` — the live `2px 2px 0 0` offset plate — is
  retired outright; Fill replaces its instances. *(LS-2013)*

- **All WooCommerce**: `inc/woocommerce.php`, 11 `woo-*` patterns, 9 commerce
  templates, 5 commerce parts, 7 commerce stylesheets, cart/product block styles.
- **All KWV-specific artifacts**: 8 `inc/` behaviour modules, ~30 brand page
  patterns, brand logos and imagery, 6 behaviour scripts, bundled Poppins and
  Bodoni Moda font families, and the transparent/dark header variants.
- Orphaned CSS and dead references: the transparent-header flow rule, the
  KWV mega-menu block rule, the unregistered mega-menu search style, the
  `author-role` block bindings (that meta belongs in the block plugin), and the
  dangling `blog-post-card` pattern reference.
- The **`alternate` font-family preset**, added briefly for regular-weight Optima and then
  withdrawn: with the Optima family registered across four weights it resolved to the same
  stack as `heading`, making it a duplicate token. Regular Optima is now simply weight 400
  of `heading`. *(LS-2012)*
- 🔴 **The three Optima faces have been removed entirely — none was ever licensed.**
  Deregistered from `theme.json` and deleted from `assets/fonts/`, 2026-08-18. The client's
  licences arrived that day and revealed that none of the three is the face Southern
  Destinations owns:

  | File | Real identity | Embedding bits |
  |---|---|---|
  | `optima-400-normal.woff2` | `Optima` — **©1991 AG Baltia**, a 1993 clone | `fsType 1` — **embedding forbidden outright** |
  | `optima-500-normal.woff2` | `Optima Medium` — Adobe Systems 1995 | `fsType 260` — preview/print, **no subsetting** |
  | `optima-700-normal.woff2` | `Optima Demi Bold` — Adobe Systems 1995 | `fsType 4` — preview/print only |

  What SD actually owns is MyFonts order #9528082 (2018) for **Monotype Optima Bold, desktop
  OTF/TTF only** — a different foundry's cut, one weight, and no self-hosting kit. The
  `fsType 1` on the 400 is the sharpest point: that font's own metadata refuses embedding.
  The live site serves an equivalent conversion today, so the exposure **predates the
  rebuild**. The files were moved aside rather than shredded, and are recoverable from live.
  *(LS-2012, LS-2641)*

  The `heading` preset keeps its `Optima, Belleza, sans-serif` stack but now registers **no
  face**, so locally-installed Optima resolves where present and **Belleza** everywhere else
  — the standby it was bundled to be. A permanent substitute heading face is a
  Change-Control Register decision, per the position recorded here on 2026-08-12.

- **`joe-hand-400-normal.woff2` is still untracked**, now on redistribution grounds rather
  than doubt: its web-embedding rights are confirmed, but EULA §1.5/§1.6 forbid direct
  download and transfer, and a repository is a distribution channel. Supplied by the
  build/deploy pipeline. *(LS-2012)*

### Fixed

- **A 37px strip of bare image showed along the top of both cards.** WordPress gives
  flow-layout siblings a `margin-block-start` from `--wp--style--block-gap`, and because the
  scrim and the name band are `position: absolute` with `inset: 0`, that margin still shifts
  them rather than being ignored. The variation's own `blockGap: 0` does not win. *(LS-2013)*
- **Both cards rendered stacked instead of overlaid inside the block editor.** Gutenberg's
  `content.css` sets `position: relative` on block wrappers at (0,2,0), which beats a block
  style's `css` field at (0,1,0), so the scrim dropped below the image. Fixed with an
  `.editor-styles-wrapper` rule at (0,3,0). The same rule keeps the Team Member Card's bio
  panel open in the editor so its text can be selected and edited without a hover.
  *(LS-2013)*
- **`styles/sections/cards/blog-card-large.json` referenced `var:preset|line-height|heading`.**
  Line height is a `custom` family, not a preset, so the reference resolved to nothing and the
  rule silently did not apply. KWV-inherited; the theme is back to 0 orphaned references.
  *(LS-2013)*

- **Inline `<code>` was invisible on dark and brand-filled sections.** `style.css` gave it a
  fixed `neutral-200` plate but let it inherit its text colour, so inside any section setting
  `base` text it rendered white on near-white. The rule now sets `color` explicitly.
  KWV-inherited. *(LS-2013)*
- **Pagination prev/next lost their border.** `theme.json` sets
  `styles.blocks.core/query-pagination.elements.link`, which compiles to
  `:root :where(.wp-block-query-pagination a:where(:not(.wp-element-button)))`. Everything
  inside `:where()` counts as zero, so that rule lands at (0,1,0) — exactly tying a bare
  `.wp-block-query-pagination-next` and winning on source order. Every selector in that
  sheet is now descendant-scoped to (0,2,1) or higher. *(LS-2013)*
- **`style.md` §2.2 — `#4C5250` was wrongly excluded from the token map** as "Trustpilot
  chrome". The child theme sets it on a bare `p` selector, so it is the body-copy colour of
  the entire site; only 3 of its 21 uses are Trustpilot-scoped. It still has no token, and
  the theme currently renders body copy as `contrast` — a visible change on every page.
  Now the largest open item in the colour map. *(LS-2012, LS-2013)*

- 🔴 **Every bundled fallback face was emitted under the wrong `font-family` name.**
  `WP_Font_Face_Resolver::convert_font_face_properties()` sets
  `$font_face['font-family']` unconditionally from the **first name of the preset's
  `fontFamily` stack** (`wp-includes/fonts/class-wp-font-face-resolver.php:142`, with
  `:120–125` doing the `explode(',')`), so a `fontFace` entry's own `fontFamily` is
  **ignored entirely**. Registering Belleza inside `heading` and La Belle Aurore inside
  `accent` therefore mislabelled both. Two live consequences:

  - `accent` rendered **La Belle Aurore instead of Joe Hand** — both faces were emitted as
    `font-family:"Joe Hand"` at the same weight and style, so the later declaration won.
  - An `@font-face` claiming the name `Optima` **outranked locally-installed Optima**, so
    even macOS visitors got Belleza's glyphs under Optima's name.

  Fixed by giving each typeface its own preset. Verified against the rendered front page:
  10 rules, each with the correct descriptor. The earlier verification missed it because it
  asserted on `fontFace` entries surviving sanitisation and on `src`/`format()`, never on
  the descriptor the resolver had overwritten — **assert on emitted CSS, not parsed input.**
  *(LS-2013)*

- Two dead preset references in `patterns/template-index-news.php` and
  `patterns/template-category.php`, which called `var:preset|spacing|0` where no `0` slug
  exists (the spacing scale starts at `5`), silently dropping the intended zero padding.
  Replaced with a literal `0` in both the block comment and the rendered markup so the
  editor does not flag the blocks as invalid. Pre-existing; found by the orphan scan.
  `theme-orphaned-refs` now reports **0 orphans** across 557 references. *(LS-2012)*
- All five `@font-face` defects carried by the live child theme are corrected in the bundled
  font layer: swapped `format()` hints, `font-family` descriptors holding a fallback list,
  eleven Open Sans files stacked under a single weight, a broken `../../fonts/` path with a
  missing comma, and TTF delivery instead of WOFF2. *(LS-2012)*

### Security

- **`assets/fonts/optima-*.woff2` and `joe-hand-*.woff2` are `.gitignore`d and untracked**,
  delivered by the build/deploy pipeline instead of being committed. They are commercially
  licensed — Optima is a Linotype face and the heading face of the whole site — with
  web-embedding rights unconfirmed, and **this repository was public when they were first
  pushed**, making them briefly downloadable by anyone. The OFL faces (Open Sans, Belleza,
  La Belle Aurore) remain committed. *(LS-2012)*

  Three mitigations, all applied 2026-08-12:
  - **The repository was switched to `PRIVATE`.** This contains the exposure. It was small
    to begin with: created 2026-08-12 with 0 forks, 0 stars and 0 watchers.
  - **The faces were untracked** (`git rm --cached`, after the `.gitignore` rules alone
    proved inert against files git already tracked). This keeps them out of future clones
    and deploys. The files stay in the working tree, so local development is unaffected.
    A fresh clone carries 9 OFL faces (353 KB); the other 4 (122 KB) come from the pipeline.
  - **Branch history was rewritten** so no commit on this branch has ever contained them
    (`git filter-branch --index-filter` over the branch range, then a force-push). Verified:
    **0 licensed blobs across all commits**, and the resulting tree is byte-identical to
    before the rewrite. The now-empty untrack commit was pruned, so the branch carries 3
    commits rather than 4.

  Why all three, and not just the rewrite: private contains the exposure, `.gitignore` +
  untracking prevents recurrence, and the rewrite removes the blobs that were already
  pushed. The rewrite was done **before merge** deliberately — merging would have propagated
  the blobs into `develop`'s history and widened the cleanup.

  Remaining caveats:
  - 🟠 **GitHub still serves the purged blobs at the pre-rewrite commit SHAs.** Verified:
    `optima-400-normal.woff2` (18,716 b) is still fetchable at `261d97b` after the
    force-push. Unreferenced objects are not garbage-collected promptly, and the PR
    timeline records the old SHAs, so they are discoverable by anyone with repo access.
    **The rewrite makes clones, checkouts and deploys clean; it does not purge GitHub's
    object store.** Per GitHub's own guidance on removing sensitive data, that requires
    asking GitHub Support to run `gc` — or, given this repo is only days old with a
    handful of commits, deleting and recreating it. Residual risk is low: the repo is
    private, so reach is limited to collaborators who are entitled to the code anyway,
    and the public exposure window is closed.
  - ⚠️ Anyone who pulled this branch before the rewrite has divergent history and needs
    `git fetch && git reset --hard origin/<branch>`. The branch was hours old with only
    automated reviews at the time, so this is unlikely to affect anyone.
  - ⚠️ `theme.json` registers **10** faces as of 2026-08-18, of which 9 are committed, so a
    fresh clone has **1 unresolved `@font-face` rule** until the pipeline supplies Joe Hand.
    The `accent` family falls back to La Belle Aurore — bundled precisely for this — and
    then `cursive`. Degraded, not broken.
- ✅ **Joe Hand's web-embedding licence is confirmed** — JOEBOB graphics Webfont EULA 1.0,
  licence owner Southern Destinations, supplied 2026-08-18. The release gate is **cleared for
  this face**, subject to three obligations now tracked as work, not risk: a **10,000
  pageview per copy per month** cap that SD's traffic will exceed (§1.3 → LS-2642), a
  **single licensed domain plus 5 subdomains** that does **not** include the
  `.lightspeedwp.dev` dev host (§1.4), and an **anti-hotlinking/no-direct-download**
  obligation on however the file is served (§1.5). *(LS-2012, LS-2642)*
- 🔴 **Optima's release gate stands, and the predicted consequence has triggered.** The
  licence supplied covers **desktop use of Optima Bold only**. Its EULA §3 states *"You may
  not link to, or put online, Web Font Software not supplied to you in a self-hosting kit"*
  and forbids modifying the software — so the delivered OTF/TTF cannot lawfully be converted
  and served, mandatory Tracking Code ships only with the kit, and weights 400 and 500 are
  not licensed at any format. No Optima face is bundled or registered as of 2026-08-18.
  SD needs to download the **webfont/self-hosting kit** from the MyFonts account holding
  order #9528082; under current Software-for-Creatives terms web rights are bundled with the
  purchase, so this is likely a download rather than a new purchase, but Monotype should
  confirm in writing which EULA governs a 2018 order. → **LS-2641**, and the substitution
  question is on the Change-Control Register. *(LS-2012, LS-2641)*
- `Optima_Italic.ttf` was **not** ported — the live source file is corrupt (its `glyf` table
  range overlaps `cmap`) and `woff2_compress` rejects it. It never loaded on live either.
  Italic Optima will synthesise an oblique until a clean source file is supplied.
