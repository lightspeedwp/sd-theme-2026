# Changelog

All notable changes to the Southern Destinations 2026 theme are documented here.
Format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/);
this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- ✅ **`tests/e2e/templates/tours.spec.js`, and mega-menu chevron checks in
  the header spec.** LS-2019 (line 9, Tour). `tests/e2e/parts/header.spec.js`.

  Banners on the tours archive, the tour single and the travel-style archive:
  the hero banner, one `<h1>`, between the 400px floor and the old 454, no
  inline padding, the photograph filling the band on desktop, and the phone
  stack (plate, brand-500 title, neutral-700 strapline, 3:1 strip) at 375px.
  The landing grid: two columns, 3:2 tiles, the tiles in term-ID order and,
  where REST exposes the meta, exactly the featured terms. The itinerary: no
  visible `Card Link`, no comma after a lodge with no destination, and only
  top-level destinations on the country line. The highlights: the list fills
  the wide band, and its checks are brand-500.

  Header: no chevron beside a top-level label in either Ollie markup, the
  chevron back on a keyboard-focused toggle, and the underline held while a
  panel is open. `destinations.spec.js` now expects the 400px floor.

- ✅ **`tests/e2e/templates/destinations.spec.js`, and country and region
  routes.** LS-2016 (line 6, Destinations). `tests/e2e/fixtures/routes.js`,
  `tests/e2e/global-setup.js`.

  The suite used to resolve one destination, the newest, which could be a
  country or a region. It now also resolves a country (`parent=0`) and a
  region (`parent_exclude=0`) through a new optional `query` on each route, so
  the render contract in `singles.spec.js` covers all three.

  The new spec checks two things on each of the three. **The banner** is the
  hero banner: one `<h1>`, no strapline, at least 454px on desktop with the
  photograph filling it. On phones it sits on the neutral-200 plate with the
  title in brand-500, a 3:1 strip above the title and no padding.
  **The listing cards** (and the modal cards in the page): every paragraph,
  taxonomy row and excerpt is font size 200, read-out links are brand-600,
  linked titles are neutral-700, and a link hovers to brand-700. Colours and
  sizes are compared against the resolved preset, not a literal.

  Run against dev before this branch deployed, the banner and card checks fail
  on the old markup, and the card failures list the break row by row: price
  rating, destination and rating paragraphs at 19.01px (size 300) instead of
  15.88px. On local, where only a country exists, the banner checks pass
  against the new markup and fail against the old one (a 360px floor, 26.6px
  of inline padding on phones).
- ✨ **`Header Search Dropdown`, the mobile header's search.** LS-2014 (line 2,
  Header). `styles/blocks/search/header-search-dropdown.json`,
  `assets/styles/core-search.css`.

  Live's `#mobile-searchform` drops down as a full-width band under the dark
  menu bar; ours unrolled sideways, which on a bar holding only the trigger and
  the menu toggle has nowhere to go. Measured on live at 390px: field inset 10px
  from both edges, 50px tall, square-cornered, 1px light border on white. The
  new style reproduces that. The field is positioned against the bar (found with
  `:has()` off the style class, so no hook class in the markup) because core's
  closed state puts `overflow: hidden` on both the form and its wrapper. The band
  is a spread `box-shadow` on the field, and the reveal is a `clip-path` wipe.
  Core still owns the disclosure: `aria-expanded`, Escape, focus-out, and the
  trigger becoming the submit button once open.

  ⚠️ **Not `visibility: hidden` for the closed state.** Core calls
  `input.focus()` in the same tick it opens, before the class re-renders, and a
  hidden field refuses focus. Measured on local: focus stayed on the trigger.
  `pointer-events: none` does the same job without that problem.

- ✅ **Header and footer specs.** LS-2014. `tests/e2e/parts/header.spec.js`,
  `tests/e2e/parts/footer.spec.js`, `tests/e2e/utils/contrast.js`.

  Header, at 1280px: exactly one band rendered, and it sticks; the logo sits
  wholly inside the band; the Call Us pop-out has a zero radius and no rule
  between rows (skips where menu 65909 is absent, i.e. local). At 390px: exactly
  one band rendered, and it scrolls away; the logo fits; the search opens under
  the bar, inside the viewport, with no horizontal scroll, takes focus, and
  returns it on Escape. In the mobile menu panel: no search field, four flags
  that load beside four `tel:` links, and the panel and its logo start on
  screen.

  Footer: every text link in the widget area hovers to `brand-700`, the "Follow
  Us" labels too, and every colophon link hovers to `brand-400` at ≥ 4.5:1
  against the colophon's computed ground. axe never hovers, which is how a
  failing hover shipped. The new `contrast.js` resolves palette slugs through the
  browser, so the specs never hardcode a hex value.

  Run against local, where these changes live: 23 passed, 1 skipped (the Call Us
  check; menu 65909 does not exist locally). On dev they will fail until this
  branch deploys and the overrides below are reset. That is expected.

- ✅ **`tests/e2e/templates/front-page.spec.js`.** LS-2031 (line 21, QA).
  Five checks on the homepage's per-screen-size swaps, run at phone, tablet
  and desktop width: exactly one hero, at live's height for that screen, with
  the guest quote only where live shows it; each main panel exactly once, with
  its photograph above the copy and no white-on-white text on phones; the
  intro monogram and "Start here" hidden on phones only; every shown arrow's
  SVG mask resolving to a rendered definition; and the carousel arrows kept
  inside the viewport across 1200–1440px, the band the fixed breakpoints miss.
  Run against dev before this branch deployed, they fail in exactly the five
  places the branch changes and pass everywhere else.

- ✨ **The travel-style term archive.** LS-4204 (line 12, Travel Styles).
  `templates/taxonomy-travel-style.html`, `patterns/template-taxonomy-travel-style.php`,
  `patterns/card-tour-list.php`.

  `/travel-style/<slug>/` is now a real term archive. Live has no equivalent —
  its nearest page is a hacked site search over WooCommerce `product` posts —
  so this is the search results page's structure applied to one post type: the
  "Tours & Safaris" banner and standfirst verbatim from live's tour search, the
  breadcrumb strip (the only place the term is named), then the tour rows behind
  a keyword facet, a Destinations filter and a Travel Styles filter, with a
  result count, the shared sort control and a FacetWP pager. It closes on
  "Why choose Southern Destinations", as the tours archive does.

  **The keyword box is a FacetWP search facet, not `core/search`** — here the
  keyword narrows what the term returned rather than being the query itself. It
  is `search_tours`, plural, over the **SearchWP - Tours** engine; the facet was
  registered on dev 2026-09-17 and the `facetName` has to match it exactly or
  the block renders nothing, silently.

  **No selected-filter chips on this page.** Two filters over a single post type
  in an always-visible rail already show what is applied; the chips stay on the
  search and accommodation pages, where the rails are longer.

  Live's "Price Per Person" slider is deliberately not carried — `price` is an
  accommodation field in Tour Operator 2.2, so the facet would index empty and
  hide on every request. **Noted for LS-2033**: a tour price filter needs a
  price on the tour, which is a data-model question rather than theme work.

  ⚠️ **Plugin dependency.** `travel-style` is registered against six post types
  and they all carry terms, so the tours-only constraint is a `pre_get_posts` in
  `sd-enhancements-2026` — `Queries::limit_travel_style_archive()`. With the
  plugin deactivated this page lists accommodation and reviews through the tour
  card.

  **The tour row lost its "View more" link.** The excerpt now runs to 40 words
  and ends on `/..`; the card title is already a link and the whole row reads as
  one target, so the separate read-more was a redundant tab stop. Its Location
  row now binds with `parents: "true"`, so a tour attached to a region prints
  the country alongside it rather than the region alone.
- ✨ **The 404 page.** LS-4205. `patterns/template-page-404.php`,
  `patterns/cta-feeling-lost.php`, `styles/blocks/list/list-plain.json`.

  `patterns/template-page-404.php` was a placeholder — a centred "404 / Page not
  found" stack with authored copy and a *Back to homepage* button, none of which
  is on live. It is replaced with live's own page, measured 2026-09-17 against
  `sd-lsx-child/404.php`: the `404!` banner over *Can't find what you're looking
  for?*, the warm-grey **Nothing Found** band carrying the standfirst and the
  search form, the three spelling suggestions on white beneath it, then the
  enquiry band and the value panel.

  `templates/404.html` is unchanged — it already referenced this pattern.

  **The banner keeps the only `h1`.** Live prints two, one in the banner and one
  on *Nothing Found*; the second becomes the `h2` that opens the first body
  section. No copy moves. There is no breadcrumb bar, because live's 404 has
  none — a trail ending at a page that does not exist points at nothing.

  `patterns/cta-feeling-lost.php` is the fourth and last of live's four enquiry-band
  headings, and the one `patterns/cta-not-sure-where-to-go.php` predicted when it
  recorded that a block theme turns the child theme's body-class switch into
  *which pattern each template includes*. One copy string and one anchor separate
  the two files; a change to one is a change to both.

  `styles/blocks/list/list-plain.json` is live's `.list-404` — a list with its
  marker and its hanging indent removed, so the items sit flush on the text rail.
  The indent is cleared as `padding-inline-start`, the property the UA stylesheet
  actually sets, so the rule holds in RTL as well as LTR.

  ⚠️ **`padding-left: 150px` on live's `.copy-wrapper` is deliberately not
  ported** — a raw pixel indent with no token behind it and no responsive floor.
  If the indent is wanted it is a spacing preset on the inner group, not 150px.

- ✨ **The search results page.** LS-4175 (line 14, Search).
  `patterns/template-page-search.php`, `patterns/card-search-result.php`,
  `styles/sections/cards/listing-card-list.json`,
  `assets/styles/facetwp-facets.css`.

  `/?s=…` is now the same results page the accommodation-type search is, rather
  than the three-column blog-card grid that stood in as scaffolding: the search
  banner over live's own photograph (attachment 51741, already in the media
  library), the breadcrumb strip, then mixed-post-type result rows behind a
  Content Type filter rail and a result count.

  **The rows are `patterns/card-accommodation-list.php`'s row, carrying every
  post type.** A post-type badge sits on the leading corner of the thumbnail;
  the tinted meta panel shows the accommodation fields on an accommodation and
  the tour fields on a tour, and is gone entirely on a page, a destination or an
  article, where the copy column grows into the space and the excerpt is the
  whole card. Every gate is Block Visibility's Location control on `postType`,
  which reads the *looped* post inside a `core/post-template` — so one authored
  card answers for all of them. ⚠️ That makes Block Visibility a render-time
  dependency of this card; deactivating it shows every gated block.

  The title keeps the card style's 400; the excerpt and the whole meta panel run
  at 200 — "Base" — which is the one size the row's body text runs at.

  **One filter only.** A result set spanning seven post types has no field in
  common to refine on, so the rail carries the existing `post_type` facet
  ("Content Type") and the selected-filter chips, and nothing else. Its
  behaviour was set to **OR** on dev — a `post_type` facet on AND returns
  nothing the moment two types are ticked. The keyword box is `core/search`,
  not a FacetWP search facet: here the keyword *is* the query. There is no sort
  control; relevance is the only ordering a mixed result set has.

- ✨ **The Specials landing page.** LS-2021 (line 11, Specials).
  `templates/archive-special.html`, `patterns/template-archive-special.php`,
  `patterns/cta-like-what-you-see.php`, `styles/sections/cards/special-card.json`.
  Needs `sd-enhancements-2026` on the same branch — four of this feature's
  requirements are behaviour and live there.

  `/specials/` is now live's own page rather than the three-column card grid that
  stood in as scaffolding: the photographic banner and an introduction, then one
  full-width band per offer carrying the property photograph as its ground, the
  offer name, a meta row of connections and the offer's **complete** body copy,
  four to a page, closing on the enquiry band and the value panel.

  `templates/archive-special.html` is now the four-line shell every other archive
  in this theme already uses, and the `<main>` landmark moved into the pattern
  with the composition rather than being left behind in both.

  `styles/sections/cards/special-card.json` was written in August and had no
  consumer until now. The band is live's, measured: 540px tall, the photograph
  absolutely positioned as its ground, a 460px panel of copy held against one
  edge of it and alternating leading edge / trailing edge down the page, the
  bands butting straight together with no gap. The panel sits in normal flow, so
  a long offer name or description grows the band rather than overflowing it —
  at every width, with no media query, which the `css` field silently unwraps
  anyway. The band also paints a neutral-900 ground, because
  `core/post-featured-image` renders nothing at all for an offer without one and
  the band would otherwise collapse.

  **The band addresses its two children as blocks, not by class.** The
  hand-rolled `special-card__media` / `special-card__body` classes are gone, and
  with them the unused `special-card__badge` rules; `& > .wp-block-post-featured-image`
  and `& > .wp-block-group` are as precise, since the band holds exactly one of
  each, and an editor who rebuilds the band by hand now gets the layout without
  knowing two invented class names.

  Four things the `css` field cannot express live in `assets/styles/core-group.css`
  instead, each with a comment saying which limit forced it: the panel's scrim
  (it has to sit *over* the photograph, not behind it), the edge alternation (it
  keys off the `core/post-template` `<li>` above the band), the sub-900px stack
  (`@media`), and the list reset.

  One deliberate deviation from live, for consistency across the five Tour
  Operator archives: the introduction takes `is-style-archive-intro`. Offer copy
  renders exactly as it was authored, bullets included, as live renders it.

  The introduction and the offer list share one `is-style-light-page-section`
  band — the list's top padding is off so the two read as a single section —
  and the bands run to the **wide** measure, not full. Set in the Site Editor
  and reconciled back into this file 2026-09-17; the DB override is now
  redundant and should be cleared before deploy.

  ⚠️ **Every offer's copy is one size regardless of how it was authored.**
  `theme.json` styles `core/paragraph`, and core compiles that block against the
  bare element selector — `:root :where(p){font-size:…300}`. A direct rule beats
  inheritance, so raw `<p>` in migrated content rendered a size larger than the
  `<li>`s beside it, which no rule matches and which inherited preset 200 from
  `core/post-content`. Offers written as paragraphs (Thornybush) came out
  visibly larger than offers written as bullets (RockFig, Dulini). Normalised in
  `assets/styles/core-group.css` with `font-size: inherit` at (0,2,0), so the
  copy follows the block's own font size and the editor and front end cannot
  disagree.

  ⚠️ **Which offer an enquiry is about is still not carried.** Form 10 already
  has the field — id 12, "Name of Offer", hidden, `allowsPrepopulate`, default
  `{embed_post:post_title}` (measured on dev 2026-09-17) — but `{embed_post:…}`
  resolves against the embedding post, and one dialog registered once and
  printed in an archive's footer has no embedding post to resolve against. A
  shared dialog cannot name the offer whose button opened it. Closing it means
  a per-band dialog or a script that writes field 12 on open — behaviour either
  way, so plugin work. SC-007 is verified when that lands, not waived.

  The page title, tagline and introduction are ordinary `core/heading` and
  `core/paragraph` blocks. They were bound to Tour Operator's settings registry
  through `sd/to-setting`; no setting was populated on any environment, so every
  render fell through to the authored copy, and the other four landing pages all
  author theirs directly.

### Changed

- 🖼️ **The hero banner is 400px, not 454.** LS-2019 (line 9, Tour), found on
  the tours pages. `styles/sections/hero-banner.json`,
  `patterns/hero-page-banner.php` and every banner pattern pinned at 454.

  Too tall against live, which computes 380px (38rem on a 10px root). 454 was
  the size live *serves the image at* (1920x454). 400 sits closer to live
  without matching it, because this rebuild's type runs larger. The section
  style and all thirteen banner patterns change together so the banners stay
  one height. The template-home-blog and category banners (360px) are unchanged.

- 🗺️ **The tour templates use the hero banner, phone stack included.** LS-2019.
  `patterns/template-archive-tour.php`, `patterns/template-single-tour.php`,
  `patterns/template-taxonomy-travel-style.php`.

  The same device as the destinations pages. The inline spacing-40 padding is
  gone from all three, and so is the tour single's own 360px, `dimRatio: 0`
  cover. The phone stack in `style.css` now reaches them: a 3:1 strip of
  photograph with the title and strapline on the neutral-200 plate. The tours
  archive strapline is medium rather than semi-bold, as on every other banner.
  The travel-style archive changes only its banner.

- 🧭 **The tours landing runs two columns of 3:2 tiles, in live's order.**
  LS-2019. `patterns/template-archive-tour.php`.

  Two across, as live and the accommodation archive run, through the term
  card's existing `$sd_card_aspect_ratio` (`3/2`, the destinations landing's
  crop). Live orders the ten featured travel styles by term ID: its
  `get_terms()` call (sd-lsx-child/includes/template-tags.php:258) passes no
  `orderby`, which `WP_Term_Query` resolves to `t.term_id ASC`. So Safari
  Honeymoons comes first. The terms query is now `orderBy: "id"` instead of
  `name`. The selection is unchanged: the `featured` filter in
  `sd-enhancements-2026` already matched live's ten, name for name.

- ✅ **Tour highlights run the wide measure, with brand-500 checks.** LS-2019.
  `patterns/template-single-tour.php`, `assets/styles/core-group.css`,
  `styles/sections/highlights-list.json`.

  The list group was `alignwide` but *constrained*, which clamped the `<ul>`
  back to the content measure. It is flow layout now. The check-circle is
  brand-500 rather than live's gold accent-500.

- 📱 **Mobile menu review: parent rows link to their pages, and the panel is
  tidied.** LS-2014 (line 2, Header), found while on LS-2016.
  `parts/mobile-menu.html`, `assets/styles/core-navigation.css`,
  `assets/styles/core-columns.css`.

  - **Parent rows link to their pages.** The panel's navigation goes from
    `submenuVisibility: "click"` to `"hover"`. In click mode, core renders each
    parent as a single `<button>`, so Destinations, Tours & Safaris,
    Accommodation and About Us could open their lists but never reach their
    own pages. In hover mode core renders the label as an `<a>` and a separate
    toggle button for the chevron. The row is now a flex line: the link shrinks
    to its label and the toggle fills the rest of the row. Tap the words to go
    to the page; tap anywhere else on the row to open the dropdown. Core's
    hover handlers ignore touch pointers, so a tap never opens a list by hover.
    The pressed tint still spans the whole row.
  - **No dividers in the closed menu.** The `is-style-separator-thin` between
    the navigation and "Get in touch" is gone. So is the hairline under
    Specials: it is the only top-level row that is a link rather than a
    submenu, so it was the only row that picked up the variation's border.
    Rows inside an open dropdown keep theirs.
  - **Call us today.** The heading goes up two steps on the scale (font size
    `100` → `300`). The heading and the numbers now sit in a vertical flex
    group with a `spacing|20` gap. They were flush at 0px, because theme.json
    gives every template part `margin-top: 0 !important`. The four numbers are
    now `spacing|20` apart (were 0px), each label sits on its number with no
    gap (was `spacing|5`), and the flags are 36px (were 28px). The part is
    shared with the footer, the header dropdown and the safari-expert panel,
    so these three are CSS scoped to `.sd-mobile-menu`, not changes to the part.
  - **White logo.** Attachment 36261 (`2017/12/footer-logo.png`) replaces the
    colour `footer-logo.svg` on the dark panel.

- 📱 **Destination pages take the hero banner and its phone layout.** LS-2016
  (line 6, Destinations). `patterns/destination-banner.php`.

  The banner on every destination, country and region page is now
  `patterns/hero-page-banner.php`'s markup, configured for destinations. It
  keeps Tour Operator's `banner_image_id` binding, with the featured image as
  the fallback, and drops the strapline, because live shows none on a
  destination at any width (measured on /destination/botswana/ and
  /destination/botswana/moremi-game-reserve/). Below 768px the photograph
  shrinks to a strip and the title sits beneath it in brand-500 on the
  warm-grey plate, as on live. The floor goes from 360px to the hero's 454px.
  The inline spacing-40 padding is gone, because the phone layout could not
  override it. Tour Operator's replacement image keeps the class the phone
  layout targets, so this works on a bound banner. All three templates
  `require` this one file.

- 🐛 **Listing cards are one type size again, with brand-600 links.** LS-2016
  (line 6, Destinations). `styles/sections/cards/listing-card-compact.json`,
  `styles/sections/cards/listing-card-list.json`, `patterns/card-tour-compact.php`.

  theme.json's `core/paragraph` default of font size 300 beat the size 200 a
  card sets on its body. Every bound meta paragraph (price rating,
  destination, rating, a destination's country or regions) rendered at 300,
  while the taxonomy rows beside it inherited 200. Both card styles now pin
  body copy to 200 on the card, on `core/paragraph` and on `core/post-excerpt`.
  This covers the accommodation, tour and destination compact cards, the
  accommodation units, the three modal cards and the list and search-result
  rows. Headings keep their own size, and a paragraph that sets its own
  size keeps it.

  Links in both styles go from neutral-800 (compact) and neutral-700 (list) to
  **brand-600, brand-700 on hover**. The linked title stays **neutral-700**,
  as live's `#60483b` title does, and hovers to brand-700. The tour compact
  card's own brand-500 link colours on its travel-style and destination rows
  are removed, so they follow the style like every other card.

- 💄 **The destinations landing page: finalisation pass.** LS-2016 (line 6,
  Destinations). `patterns/template-archive-destination.php`,
  `patterns/trustpilot-score.php`.

  **The banner is now the hero banner pattern, so it gets the phone layout.**
  The archive banner uses `patterns/hero-page-banner.php`'s markup, with the
  two changes that pattern allows for an archive: the landing's own photograph
  instead of a featured image, and a typed-in `<h1>` instead of the post title.
  Below 768px the photograph now shrinks to a strip and the title and
  strapline sit on the warm-grey plate beneath it, as on live and on every
  single. The page's old inline top and bottom padding is gone. The phone
  layout could not override it, so it left a band of plate above the
  photograph. On desktop the banner now takes the section style's padding.

  **The destination tiles are 3:2 landscape, as live crops them**, not square.
  The change applies to this page only. The shared tile,
  `patterns/card-media-overlay.php`, also appears in the regions grid on
  country pages and in the destinations list on team pages, and both stay
  square. So the landing writes the tile out in full with the new crop, next
  to a note to keep the two copies in step.

  **The intro band's columns are 50/50**, not 55% and auto, so the safari
  expert card uses its wide layout on desktop: the portrait beside the name
  and the actions. That switch happens once the card has 576px, which a half
  column gives it from a viewport of about 1240px up. Between 782px and about
  1240px the card keeps its narrow layout.

  **The Trustpilot badge in the expert card is easier to read.** "Excellent"
  goes from font size 100 to 200. The TrustScore and the review count go from
  the inherited neutral-700 to neutral-900, which matches the black badge live
  uses in this spot. The badge only appears inside the expert card, so this
  applies to every page that shows the card: the destination, tour and
  accommodation singles and the tour archive, as well as this page.
- 🔄 **The header, captured from dev's Site Editor copy.** LS-2014 (line 2,
  Header). `patterns/header.php`, `style.css`.

  Dev's `wp_template_part` override `header` (post 65914, edited 2026-09-04)
  replaced this file's single responsive band with two groups that Block
  Visibility swaps: **Header - Desktop** (above `large`), and **Header - Mobile**
  (Trustpilot and logo stacked on `neutral-200`, then a `primary-600` bar with
  the search and the menu toggle). The mobile navigation's no-op visibility
  wrapper was dropped. The logo column is 22%, the phone icon is Phosphor's fill
  weight at 24px (⚠️ now unlike the outline weight in the three homepage/CTA
  patterns), and every copy string and alt text is wrapped for translation.
  `fontWeight` on the Call Us navigation is `var(--wp--custom--font-weight--bold)`
  rather than the editor's raw `700`: `var:custom|…` is dropped on a dynamic
  block, and the previous file's copy of it had been silently doing nothing.

  **The mobile header no longer sticks,** matching live, whose masthead is
  `position: relative` at 390px. That took two changes. The mobile group carries
  no `style.position`, and `style.css`'s wrapper rule
  (`header:has(> .is-position-sticky)`) now sits inside `@media (min-width:
  992px)`: the hidden desktop band still matches `:has()`, so the wrapper stuck
  at every width. 992px is Block Visibility's `large` on dev. Local's is 1200px,
  so local disagrees between 992px and 1199px.

- 💄 **The Call Us pop-out is square-cornered, with no rules between the
  numbers.** LS-2014 (line 2, Header).
  `styles/blocks/navigation/call-us-navigation.json`,
  `assets/styles/ollie-mega-menu.css`. The radius goes from
  `border-radius|200` to `0`, and the `neutral-200` hairline between rows is
  removed; the row hover tint still separates them. The safari-expert panel's
  accordion copy of the numbers (`is-style-call-us-dropdown`, in
  `assets/styles/core-accordion.css`) keeps its hairlines. It is not the header.

- ♿ **Footer links hover orange, and pass AA doing it.** LS-2014.
  `styles/sections/site-footer.json`, `styles/sections/footer-colophon.json`,
  `styles/blocks/navigation/footer-navigation.json`,
  `assets/styles/core-navigation.css`, `assets/styles/core-group.css`.

  The widget area's `neutral-500` hover measured **2.93:1** on the photograph's
  pale band and failed. No single orange passes on both footer grounds, so there
  are two:

  | Ground | Hover | Ratio | `brand-500` there |
  |---|---|---|---|
  | Widget band (≈ `neutral-200`) | `brand-700` | 5.81:1 | 2.92:1 ✗ |
  | Colophon (`primary-600`) | `brand-400` | 5.75:1 | 3.96:1 ✗ |

  The colophon's old `neutral-300` hover already passed (8.8:1). It changes so
  that every footer link hovers orange. The "Follow Us" labels are pinned to
  `neutral-700 !important` by the section's `css` field, which also strips
  `:hover`, so their hover is a separate `!important` rule in `core-group.css`.

- 📱 **The homepage takes live's phone and tablet layouts.** LS-2030
  (line 20, responsive QA). `patterns/homepage-hero.php`,
  `patterns/homepage-main-content.php`, `patterns/homepage-dream-trip.php`,
  `assets/styles/core-cover.css`.

  Measured on live at 320–991px, 2026-09-23. The hero is now three covers
  sharing one rotating pool, one per Block Visibility screen size: 720px on
  desktop as before; **544px on tablet**, with the title at font-size 600 and
  the guest quote kept, which is what live holds across 768–991px; and a
  **153px phone banner** carrying the title alone at font-size 500, rendered
  from the `medium_large` crop rather than the 1690px original. The inherited
  `max-width: 781px` cover floor of 430px now skips any cover that carries a
  Block Visibility screen-size class, or it would have flattened the tablet
  hero and inflated the phone one. The intro monogram and "Start here" hide on
  phones, as live's `hidden-xs` does. Each of the four photograph panels loses its scrim on
  phones: the photograph becomes an image above the copy, a short rule sits
  over a centred heading, and the body text turns from white to the body
  colour. Each panel therefore has a phone-only copy after it, built from the
  same strings and links. The swap is Block Visibility's `screenSize` control —
  desktop copies hide on *small*, phone copies on *medium* and *large* — which
  is the same split as live's Bootstrap `hidden-xs`. The phone arrows declare
  their own `sd-arrow-mobile-*` mask ids, because the desktop copies that own
  `sd-arrow-*` are `display: none` on a phone, and a mask referenced from a
  hidden subtree does not paint.

  ⚠️ The panel copy now exists twice. Editing a panel's text means editing its
  phone copy as well.

- 💄 **The homepage's Site Editor edits are back in the theme.** LS-2030 (line 20,
  responsive QA). `patterns/homepage-main-content.php`,
  `patterns/homepage-sd-difference.php`.

  Dev carried a `front-page` override (`wp_template` 65959, saved 2026-09-17)
  with every homepage pattern expanded inline. Diffed pattern by pattern against
  the rendered theme files, only two changes were design edits, and both are
  width fixes: the four photograph panels' wrapper is now `alignwide` with zero
  inline padding, and the Trustpilot TrustBox sits in an `alignwide` group so it
  is no longer held to the 900px content measure. The rest was editor noise and
  was not imported — pattern-header `description`/`categories` copied into block
  metadata on insert, core's `align` → `typography.textAlign` migration,
  `queryId`/`excludeCurrent` defaults, and `home_url()` rendered as the dev host.
  `templates/front-page.html` stays a list of pattern references, unchanged.

- 💄 **The page hero banner is now live's banner, and it unstacks on phones.**
  `patterns/hero-page-banner.php`, `styles/sections/hero-banner.json`,
  `style.css`.

  The pattern used to port the About Us tree's `lsx-blocks/lsx-banner-box` —
  centred, an uppercase eyebrow over an uppercase title. That device is not what
  live puts at the top of a page. Measured at 1440px on `/about-us/`,
  `/tour/luxury-adventure-cape-town-vic-falls-botswana/` and
  `/destination/botswana/moremi-game-reserve/`, all three render the same thing:
  LSX Banners' `#lsx-banner .page-banner`, left-aligned on the container, the
  title in Joe Hand at 60px/200 with `.tagline` under it in the heading face at
  30px/600. The banner-box only ever appeared inside the content of the About
  children. So the pattern is now that device — `core/post-title` under
  `is-style-script-accent` at font-size 800 over an `is-style-subheading-large`
  strapline, in the flow-layout `alignwide` group the archive banners already
  use — which makes it the same composition as
  `patterns/template-archive-destination.php` rather than a second species.

  **Live's banner is two layouts, and only the desktop one was built.** Below
  768px live gives `.page-banner-image` a `bottom` offset so the photograph
  shrinks to a strip (122px at a 390px viewport) and the title and tagline drop
  onto an opaque `#ece9e3` plate underneath it in `#cc7f16` and `#60483b`
  (`sd-lsx-child/assets/css/custom.css`:324-360). The palette resolves those to
  neutral-200, brand-500 and neutral-700. That second layout is now a
  `@media (max-width: 767px)` block in `style.css`, **not** in the section
  style's `css` field — `@media` compiles to a dead selector there, as
  `styles/blocks/search/header-search.json` records. Every rule in it is written
  to outrank `:root :where(.wp-block-cover.is-style-hero-banner)`, which is what
  the field actually compiles to; `min-height` is the one `!important`, because
  the 454px floor arrives as an inline attribute on the block.

  The strapline is authored, not bound. Live's subtitle comes from the
  `banner_subtitle` post meta with per-post-type fallbacks
  (`sd-lsx-child/classes/class-sd-banner-integration.php`:52-71) — a binding
  source, which is `sd-enhancements` work, and that filter is not firing on live
  today in any case.

  Slug, filename and the 454px floor are unchanged, and no template references
  the pattern yet, so nothing else moves. Rolling the per-post-type templates
  onto it, and the Playwright harness with them, is the next step.
- 🔄 **The mobile menu's phone numbers carry their flags.**
  LS-2014 (line 2, Header). `parts/mobile-menu.html`,
  `assets/styles/core-columns.css`.

  The panel listed the four numbers as a second `wp:navigation` (`ref` 65865),
  which rendered them as four plain text rows. Live shows a flag beside each
  one. `parts/dropdown-call-us` already holds exactly that — flag, label and
  `tel:` link per country — and is already the one file the footer, the header
  dropdown and the safari-expert panel read, so the panel reads it too rather
  than keeping a fifth copy of the numbers that can drift.

  That part is written for a desktop pop-out: a `width: 20%` first column holding
  a flag drawn at `100px`, `isStackedOnMobile: false`. In a ~300px panel the 20%
  resolves to about 60px and the image overflowed it, so the flag column is sized
  to 28px inside `.sd-mobile-menu` — live's own mark at this width. `!important`
  is forced on both declarations because `core/column` serialises its width as
  `style="flex-basis:20%"` and `core/image` serialises its own as
  `style="width:100px"`, and an inline declaration outranks any selector.

  Two more differences against dev's Site Editor copy are folded in at the same
  time, so this file can be the one that ships and the override can be reset:

  - **The panel logo is the footer mark, not `core/site-logo`.** The site logo is
    the dark elephant wordmark, which is the right mark on the header's
    `neutral-200` band and close to invisible on this panel's `primary-600`. Dev's
    copy had already swapped it for the light footer logo; this file does the same
    and uses the same `footer-logo.svg` the colophon does — written out as a
    literal uploads URL, no attachment ID, exactly as `patterns/footer.php` writes
    it and for the reason AGENTS.md gives.
  - **`submenuVisibility: "click"`** replaces the legacy `openSubmenusOnClick`.

  ⚠️ The `margin-top: -36px` on dev's copy is deliberately **not** carried. It was
  compensating for the overlay inset removed above; with that gone it would drag
  the panel up under the header band. Reset the override
  (`wp post delete 65922 --force`, then reload) so this file renders.
  → `wp-db-override-reconciliation`

- 💄 **The mega-menu panels, finished.** `styles/sections/mega-panel.json`,
  `styles/blocks/navigation/mega-menu-nav.json`,
  `styles/blocks/query/mega-menu-list.json`, `assets/styles/ollie-mega-menu.css`,
  `parts/mega-menu-{tours,accommodation,destinations}.html`.

  Five changes to the four fold-out panels, all on Zared's instruction. The
  sixth — the Featured columns querying the wrong posts — is under **Fixed**.

  **The row hairlines are gone.** A neutral-300 `border-bottom` sat on every
  `.wp-block-navigation-item` and on every query row's `<li>`. Both are removed;
  the row padding alone separates the labels now. The two variations describe
  the same row on different markup and were changed together, as their
  descriptions require. The **column** hairlines stay — a different rule,
  between columns rather than between rows, and still in the stylesheet.

  **The panel takes the header's shadow.** `shadow|300`, the same preset
  `styles/sections/header.json` carries, so the panel reads as the header band
  continuing downward rather than as a second object with its own edge
  treatment. It is a `0 4px 8px` drop, so it falls on the panel's bottom edge —
  the only edge the header is not already sitting on.

  **The side padding is back.** The panel is not a root-level block: it renders
  inside Ollie's `.wp-block-ollie-mega-menu__menu-container`, which
  `assets/styles/ollie-mega-menu.css` pins to `inset-inline: 0`. So
  `useRootPaddingAwareAlignments` never reached it, and below `wideSize` the
  columns ran flush to the window edge. The variation now sets `spacing|20` on
  both inline sides — theme.json's own `styles.spacing.padding`, so a panel's
  columns line up with the header above them. Change one and change the other.

  **More air above and below**: block padding goes `spacing|20` → `spacing|40`.

  **The featured card is styled, and styled in the variation.** Its resting
  rules moved out of `ollie-mega-menu.css`: heading face at semi-bold,
  `line-height|heading`, no letter-spacing, a contrast title over a neutral-700
  excerpt at `line-height|body`. The card is the synced pattern `wp_block`
  65890 — ordinary core blocks, so a variation reaches it, and a variation is
  the only one of the two that renders in the Site Editor. It was the last
  thing in a panel still styled front-end only. `font-size` is deliberately
  absent from the title: the pattern sets `fontSize: "300"` and core emits
  `.has-300-font-size` with `!important`, so a declaration would silently lose.
  The title now hovers **brand-600 with no underline**, the same token the query
  rows hover to — the rule it replaces *added* an underline. That one stays in
  the stylesheet because a `css` field strips `:hover`, and it needs no
  `!important`: (0,3,0) against the variation's unmarked (0,1,0) and
  theme.json's (0,2,0).

- 💄 **"Results" is no longer set in capitals, and the count beside it matches
  it.** LS-4175. `patterns/template-page-search.php`,
  `patterns/template-taxonomy-accommodation-type.php`,
  `assets/styles/facetwp-facets.css`.

  The heading carries `textTransform: none` against `theme.json`'s uppercase
  `h2` — right for a section heading, wrong for a label beside a number — and
  the count is now font-size 400 in neutral-700, the size and colour the heading
  renders at inside `is-style-light-page-section`, rather than 300 in
  neutral-500. The brackets around the number are the `results_count` facet's
  own count text, set to `([total])` / `(1)` / `(0)` on dev 2026-09-17, because
  they are content rather than presentation. All three land on the
  accommodation-type results page too, which shares the facet and the sheet.
- 🧪 **A Playwright end-to-end harness for the theme.** `playwright.config.js`,
  `package.json`, `tests/e2e/`, `.github/workflows/e2e.yml`.

  Four projects — `desktop`, `mobile`, `a11y` and `visual` — against a target chosen by
  `WP_BASE_URL`, defaulting to dev and refusing to run against production. Follows the org
  standards in `lightspeedwp/.github` (`docs/TESTING.md`, Playwright Testing Principles):
  accessible locators first, no XPath, no brittle CSS.

  Routes are resolved from the REST API at start-up rather than hardcoded, so the suite
  follows content instead of breaking when a post is unpublished; a type with no content in
  the target environment skips with a stated reason. A shared fixture fails any test whose
  page emits a PHP notice into the markup or an unexpected console error, so those are
  caught everywhere rather than needing a spec each.

  Result counts are scoped to `main`. That is not incidental: the header's mega menus
  contain their own query loops, so a page-wide `.wp-block-post` count returns eighteen
  items on a search that matched nothing — the scoping is what makes the archive
  assertions capable of failing at all, and it is what surfaced the empty tour and
  accommodation archives.

  Current state on dev: 54 passed, 4 failed across `desktop`; 29 passed, 2 failed across
  `mobile`. Every failure is a real defect, written up in
  `.github/reports/playwright-harness-2026-09-17.md` and listed in `tests/e2e/README.md`.
  No visual baselines are committed yet — generating them before those defects are fixed
  would bake in the broken state.
- 🧪 **The Playwright harness extended to tablet, responsive, editor and parity checks.**
  `playwright.config.js`, `package.json`, `.env.example`, `.gitignore`,
  `tests/e2e/{auth.setup.js,editor,parity,responsive,forms,templates/page-variants.spec.js}`,
  `tests/e2e/utils/{env.js,axe.js,parity.js}`, `.github/workflows/e2e.yml`.

  The project matrix now follows the org breakpoints: `desktop` 1280, `tablet` 768×1024,
  `mobile` at the Pixel 7 profile, 375×667, and an opt-in `wide` at 1920 (`SD_RUN_WIDE`).
  `firefox` and `webkit` run the `@smoke` subset. `responsive` checks horizontal overflow at
  all four widths, 44×44 header touch targets and 120%/150% text scaling. `editor` signs in
  through a `setup` project and lists every theme template and part that a Site Editor
  database override is shadowing — the documented trap, now measured instead of suspected.
  `parity` (opt-in, `SD_RUN_PARITY`, one worker, read-only) checks that live's navigation
  routes still resolve and that sampled pages keep their `h1` and substance.
  `forms/enquiry.spec.js` stops at the submit boundary and never sends valid data, because
  a real submission reaches Salesforce. `templates/page-variants.spec.js` covers the four
  custom page templates and archive pagination. Credentials come from a gitignored `.env`
  and the saved session never enters the repository.

  The axe gate is now a baseline comparison, as the org rule requires, and it **reports
  without failing until a baseline has been recorded** — the same rule as
  `sd-enhancements-2026`. CI is `workflow_dispatch` only until the full test pass, and it
  gains `tablet`, `responsive` and a secrets-gated `editor` job.

  The 2026-09-23 shakedown against dev fixed eight test bugs before recording anything as a
  defect. Among them: a "tour offers an enquiry route" check that passed on the header's
  "Contact Us" link and could not fail, FacetWP's no-results `<li>` counted as a search
  result, a Gravity Forms validation check read before the AJAX response arrived, and a
  `serial` parity suite that hid the navigation check behind any content difference.
  Current state on dev and the defect list, grouped by template:
  `.github/reports/sd-theme-e2e-shakedown-2026-09-23.md`.

### Fixed

- 🐛 **The itinerary no longer prints "Card Link" for a missing destination.**
  LS-2019 (line 9, Tour). `patterns/itinerary-stay.php`,
  `assets/styles/core-group.css`.

  Tour Operator leaves an empty field's `Card Link` placeholder in place and
  hides it by rewriting the `itin-<field>-wrapper` class around it. The
  `itinerary-location` paragraph had no wrapper of its own, and
  `itin-location-wrapper` sat on the second line instead. So a stay with no
  published destination hid the wrong line and printed the placeholder: five
  of nine rows on dev's /tour/namibia-wonderland/. The destination now has its
  own wrapper and renders blank, as live does. The comma after the lodge is
  drawn only when a visible destination follows it; live leaves it dangling.

- 🐛 **The itinerary's second line names only the stay's country.** LS-2019.
  `patterns/itinerary-stay.php`, with `sd-enhancements-2026`
  (`modules/itinerary.php`).

  It was the tour's whole `destination_to_tour` connection on every row. With
  `parents: true` that should have been countries only. But `parents` only
  drops destinations that *have* a parent, so unparented draft regions from
  WETU ("Okonjima Nature Reserve", "Etosha South") came through, linked to
  draft permalinks. Each row now carries an `itinerary-country` slot, filled
  per stay by the plugin, as live's `lsx_to_itinerary_country()` did. The slot
  holds the stay's destination's published top-level ancestor. It is hidden
  when there is none.

- 🐛 **The header nav's mega-menu chevrons are hidden again.** LS-2014 (line
  2, Header), found on LS-2019. `styles/blocks/navigation/main-navigation.json`,
  `assets/styles/core-navigation.css`.

  Ollie Menu Designer 0.3.x (on dev, and on local from 2026-09-23) renders a
  mega menu with a URL as an `<a>` plus a sibling toggle `<button>` holding the
  chevron. The block style hid the chevron only inside the link, so it came
  back beside each label, outside the underline. It is now hidden in the
  button too. It shows again only while the button has keyboard focus, so the
  Tab stop is never invisible. The open-panel underline keyed off
  `aria-expanded` on the link; 0.3.x moved that to the button, and the rule now
  follows it through `:has()`.

- 🐛 **Mobile menu parent rows are back to label-links-to-page, rest of the
  row opens the dropdown.** LS-2014 (line 2, Header). `parts/mobile-menu.html`,
  `assets/styles/core-navigation.css`.

  Merge `49bdcb6` (develop into `feature/ls-2016-6-destinations`) resolved a
  conflict on the panel's `wp:navigation` line by taking develop's copy. That
  correctly dropped the search block, which develop had moved to the header
  dropdown. It also put `submenuVisibility` back to `"click"`, while keeping
  the parent-row CSS from `b94995f`, which was written for hover-mode markup.
  In click mode the whole row is a single `<button>` carrying both
  `.wp-block-navigation-item__content` and `.wp-block-navigation-submenu__toggle`,
  so it picked up both rule sets. The label sat against the right edge with no
  padding, and no parent row could reach its own page. Dev has been serving
  this combination.

  The part is `"hover"` again. The parent-row rules are now keyed to
  `.open-on-hover-click`, which core adds only to hover-mode rows, so a future
  change back to click falls back to the shared layout and can't reproduce
  this. Hover-to-open under a mouse is fixed in `sd-enhancements-2026`
  (`modules/mobile-menu.php`), because it is an Interactivity directive, not a
  style.

- 🐛 **The hero banner's phone stack no longer gaps above the photograph or
  under the strapline.** LS-2016 (line 6, Destinations). `style.css`,
  `assets/styles/core-cover.css`, `patterns/destination-banner.php`.

  Measured on dev's `/destinations/` at 390px. The band of plate above the
  image was the template's inline spacing-40 padding, which beat the phone
  stack's `padding: 0`. Fifteen templates still write their banner padding
  inline, so the phone stack now sets it with `!important`, the same way it
  already sets `min-height`. The ~130px of empty plate under the strapline
  came from the theme's own 430px cover floor below 782px. That rule
  outranked the phone stack's `min-height: 0` on class count, so it now skips
  `is-style-hero-banner`.
- 🐛 **The mobile menu: an extra search field, no flags, a clipped logo.**
  LS-2014 (line 2, Header). `parts/mobile-menu.html`.

  The panel's own `core/search` is removed; the header bar's search is the only
  one now. The missing flags and the clipped logo were **not** theme-file bugs.
  Dev renders its Site Editor override `mobile-menu` (post 65922, 2026-08-28),
  which predates the flags and carries the `margin-top: -36px` recorded below.
  Measured on dev at 390px: the panel starts at y = −36px, its logo at
  y = −15px, and it has 0 flags. This file has had the flags and no negative
  margin since the change recorded further down, so resetting the override fixes
  both.

- ♿ **The footer's terms navigation has an accessible name.** LS-2014.
  `patterns/footer.php`. `ariaLabel: "Legal"`. It was the one unnamed
  navigation landmark on every page, and the a11y structure spec flagged it.

- 🐛 **The safari gurus' card links turn yellow on hover.** LS-2030 (line 20).
  `assets/styles/core-button.css`, `patterns/homepage-safari-gurus.php`.

  The Plain Link variation's `:hover`/`:focus` colour was never emitted — the
  block-style-variation compiler keeps only a variation's base declarations —
  so the three links on each gurus card stayed white. The flip now lives in
  `core-button.css` beside the other variations' flips, at accent-500, which is
  live's `#e6ad10` exactly. It applies to the category template's Plain Link
  too, which had the same gap. On the team member card the links also go one
  step up the scale, from font size 300 to 400.

  The row itself now shows the four gurus live shows — Liesl, Lise, Camille
  and Ilze, in live's order — because they are tagged `safari-guru` on dev;
  the plugin filter that curates it was already in place.

- 🐛 **Carousel arrows no longer push the page sideways on laptops.** LS-2030
  (line 20, responsive QA). `assets/styles/core-group.css`.

  The arrows sit 38px outside a slider frame by design, and an `alignwide`
  shelf runs to ~18px from the viewport edge below ~1434px — so the next
  arrow overhung the viewport and the whole document scrolled: 20px at 1280,
  19px at 1366, on every page with a Slider Frame shelf. The offset is now
  `max()`-clamped to the gap between the frame and the viewport edge, less a
  9px allowance for a classic scrollbar (`--sd-slider-nav-edge`). Measured on
  dev: the overflow goes to 0 at 1280 and 1366, and 1440 and 1920 render
  pixel-identical to before.

- 🐛 **The mobile menu: a black hamburger, and a white frame around the
  panel.** LS-2014 (line 2, Header). `assets/styles/core-navigation.css`.

  Two defects, one cause each, both measured on dev at 390px on 2026-09-18.

  **The hamburger was black on the dark band.** The trigger sits on the mobile
  header's `primary-600` row beside the search icon, and the search icon was
  white while the hamburger was not. An unscoped pair of rules in this file gave
  `.wp-block-navigation__responsive-container-open` and `…-close` a hardcoded
  `color: contrast` and a `neutral-200` ground with a 3px radius — so the glyph,
  which takes `fill: currentColor` from core, was painted black inside a pale
  box, ignoring the navigation block's own `base` text colour. Both buttons now
  take `color: inherit` on no ground, under the `is-style-mobile-navigation`
  scope. Live draws the same mark: white bars on `#41382e`.

  The rules could be moved under that scope rather than duplicated because
  `is-style-mobile-navigation` is the **only** navigation in this theme that ever
  emits an overlay — the other twelve `wp:navigation` blocks in `parts/`,
  `patterns/` and `templates/` all carry `overlayMenu: "never"`.

  **The panel floated in a white frame.** The open overlay had a
  `spacing.30` gutter from this file, core's white ground, core's 56px top inset
  on the content wrapper and a `spacing.30` flex gap, which together put ~20px of
  white on three sides of the dark panel and 56px above it. Live has no frame:
  the menu is one full-bleed band starting at the header's bottom edge. The
  overlay now paints `primary-600` at zero padding and zero inset, and the close
  button moves from the gutter that no longer exists to the panel's top-right
  corner.

  ⚠️ **This is what the `margin-top: -36px` on the Site Editor's copy of
  `mobile-menu` was compensating for.** Reconcile that override away when the
  header and mobile-menu parts are pulled back into theme files, or the negative
  margin will drag the panel up under the header band now that the inset is gone.

- 🐛 **The mega menu's "Featured" columns showed the newest item, not the
  featured one.** `parts/mega-menu-{tours,accommodation,destinations}.html`.

  Each Featured column ran a plain `orderBy: date, order: desc` query, so it
  printed whatever was published last. Tour Operator already has the mechanism:
  `Query_Loop::query_args_filter()` matches `/(lsx|facts)-(.*?)-query/` against
  the **post-template's** `className` and, for a `featured-*` key, swaps in a
  `featured = true` meta query and pre-resolves the set through
  `posts_pre_query`. The columns now carry `lsx-featured-tours-query`,
  `lsx-featured-accommodation-query`, `lsx-featured-destinations-query` and —
  on the "Featured Specials" list, which had the same contradiction between its
  heading and its query — `lsx-featured-special-query`.

  ⚠️ **This is why those four queries no longer carry `queryId: 0`.** That
  filter caches its result in `$saved_queries[$queryId]` and *reads* the cache
  before it looks at the className, so one panel's featured args would have been
  handed to every other `queryId: 0` query on the page — and 23 of the theme's
  24 query blocks carry `queryId: 0`. Measured on local 2026-09-18: a featured
  tour query followed by a plain `destination` query, both at `queryId: 0`,
  rendered the tour twice. The four are now 6502–6505, and re-measured the same
  day the destination query returns a destination again.

  **"From the Blog" on the About panel is unchanged.** It queries `post`, and
  Tour Operator has no `featured-post` key — there is no featured flag on a core
  post for it to read.

- 🐛 **Every Gravity Forms submit button hovered to black, and carried a
  radius.** `style.css`.

  The block's `buttonPrimaryBackgroundColor` attribute sets the resting fill and
  is the right place for it. It does not reach the hover, which Gravity Forms
  draws from `--gf-ctrl-btn-bg-color-hover-primary: var(--gf-color-primary-darker)`
  — a colour it derives in PHP from the fill it was handed. This theme hands it
  `var(--wp--preset--color--brand-500)`, a custom property rather than a hex, so
  there is nothing to darken and the derived value collapses to black.

  Both the derived colour and the button token are now set to brand-600, because
  which of the two the block's inline `<style>` occupies is a Gravity Forms
  implementation detail and that style sits at (1,2,0) where no class selector
  can reach it — whichever it writes, the other lands. `--gf-ctrl-btn-radius`
  goes to `border-radius|0`, matching `core/button`'s `fill` variation. Scoped
  to `.gform-theme` rather than to the modal, so it holds for every form on the
  site.

  ⚠️ Gravity Forms is not installed on local, so this is reasoned from the
  plugin's own framework stylesheet (measured on dev 2026-09-17) and **not yet
  confirmed in a browser**.

- 🐛 **Taxonomy links underlined on hover, against every card's own
  instruction.** `assets/styles/core-post-terms.css`,
  `assets/styles/core-group.css`.

  A site-wide `.wp-block-post-terms a:hover { text-decoration: underline }`
  inverted the default. `theme.json` sets `elements.link` and `core/post-terms`'
  own `elements.link` to `textDecoration: none` in both states, but a
  block-style variation compiles to `:root :where(…)` at (0,0,0) against that
  rule's (0,2,1) — so every card carrying a taxonomy row underlined it while its
  own variation file said it should not, and cards were exempted one at a time
  (`listing-card-list`, `listing-card-compact`, twice over) with the special
  card next in line.

  The rule and both exemptions are gone. **No hover underline is the default
  now, for taxonomies and for links generally**; a component that wants one
  specifies it in its own file, as the mega menu's featured post title and the
  FacetWP "See N more" toggle do.

- 🐛 **The Specials "Book Special" buttons opened nothing.** LS-2021.
  `patterns/template-archive-special.php`, `parts/modal-special.html`,
  `theme.json`.

  Every offer band pointed at `#to-modal-enquiry`, which is not a template-part
  slug — parts are `modal-enquiry` and `modal-special`, so the href carries
  `modal-` twice. `SD\Enhancements\Enquiry::register_trigger_modal()` verifies
  the slug resolves to a real `wp_template_part` in the `modals` area before
  rendering it, by design, so the mismatch registered no dialog at all and the
  buttons were inert in-page anchors.

  They now open **`parts/modal-special.html`** — the same dialog composition as
  `parts/modal-enquiry.html`, carrying Gravity Form 10, "Specials Form", rather
  than the general form 1 the other six enquiry CTAs use. Registered in
  `theme.json` under the `modals` area alongside the other four.

  Verified on local 2026-09-17: the part resolves with `area=modals` and
  `formId: 10`, and four renders of the band button leave exactly one
  `to-modal-modal-special` entry in Tour Operator's `modal_contents` — four
  bands, one dialog — where the old href left none.

- ✨ **`patterns/cta-like-what-you-see.php`** — the specials variant of the
  enquiry band. LS-2021.

  None of the three existing `cta-*` patterns carried this page's wording.
  `cta-not-sure-where-to-go.php`'s own docblock already recorded why: live's
  `sd_call_info_section()` takes its title from the caller, `partials/footer-cta.php`
  switches on body class to pick one of four, a block theme turns that conditional
  into *which pattern each template includes*, and it names the specials variant as
  this issue's to write. Same band, one copy string apart — not a fourth design.

### Removed

- 🗑️ **`templates/single-special.html`.** LS-2021. The site does not publish a page
  per offer; `/special/{slug}/` 301s to the archive, and that redirect is issued by
  `sd-enhancements-2026` because the theme may not author one.

### Fixed

- 🐛 **The brand Read more showed on every brand, whether the story overflowed or not.**
  LS-2018 (line 8, Lodge / Brand). `assets/styles/core-term-description.css`,
  `assets/js/intro-collapse.js`.

  Two independent faults, and the first one hid the second.

  The toggle is meant to be revealed only by `.is-enhanced`, which
  `assets/js/intro-collapse.js` adds after confirming the text is actually clipped. The hide
  was written as `.sd-intro-collapse__actions { display: none }` — one class, (0,1,0) — and
  global styles print `body .is-layout-flex { display: flex }` at (0,1,1) for the
  `core/buttons` layout. So the hide never applied and the button was visible on every brand
  regardless of what the script decided. Measured on dev at /brand/african-bush-camps/,
  2026-09-16: `is-enhanced` absent from the container, computed `display: flex` on the
  actions, and `body .is-layout-flex` named as the winning rule. The hide is now scoped to
  `.sd-intro-collapse` — (0,2,0), which lands, and which the (0,3,0) reveal still beats.

  Behind that, the overflow test itself over-reported. It compared the text's unclamped
  height against the height N lines would occupy, derived from the computed `line-height`,
  with the line count duplicated in the script as `CLAMP_LINES` and kept in step with
  `-webkit-line-clamp` by hand. Any margin inside the description — the paragraph rhythm on
  a multi-paragraph term description — counts toward the height but not toward the line
  count, so the test said "overflowing" on text that was not being cut. It now measures the
  clamp instead of modelling it: read the height, add `.is-enhanced`, read it again, and keep
  the class only if the second reading is shorter. Both reads are synchronous within one
  task, so nothing paints in between. `CLAMP_LINES` and its `FALLBACK_LINE_HEIGHT` are gone —
  the CSS is now the only place the line count is written.

- 🐛 **The review cards collapsed to a date and a star tile the moment Slick initialised.**
  LS-2020 (line 10). `assets/styles/core-group.css`.

  The equal-height rule was written as `.sd-review-slider .slick-slide > div` on the
  assumption that Slick wraps each slide in a bare `<div>`. It only does that when
  `rows`/`slidesPerRow` ask it to; at this row's settings it puts `.slick-slide` on the
  review card itself, so the card is a direct child of `.slick-track` and the selector
  matched **the card's own first child**.

  That was inert for as long as the card's children were a heading and three paragraphs.
  Adding the stars-and-date group gave it a `<div>` to match, and it stretched that group
  to the card's full height — measured on dev: meta row 220px in a 220px card, reviewer
  name at y=2158 against a `.slick-list` clipping at y=2024. Hence a row that rendered
  correctly and then lost everything below the date.

  Both selectors now carry `:not([class])`. Slick's wrapper has no attributes at all and
  every authored block carries at least one class, so it matches the wrapper and can never
  match block markup. The unwrapped shape needs no rule: `.slick-track` is already
  `display:flex` with `align-items:stretch`.

- 🐛 **The team single's Trustpilot reviews rendered as three empty cards.** LS-2020
  (line 10, Team / Safari Expert). `patterns/template-single-team.php`.

  The review card was pulled in with `<!-- wp:pattern {"slug":"…/card-trustpilot-review"} /-->`
  inside `sd/trustpilot-reviews`. `render_block_core_pattern()`
  (`wp-includes/blocks/pattern.php`) takes **no `$block` argument** and ends in
  `do_blocks( $content )`, which builds a fresh block tree with an empty available
  context. So the repeater handed each of its three passes an `sdTrustpilotIndex`,
  `core/pattern` discarded it, and every `sd/trustpilot-review` binding inside resolved
  to null — which is the card's authored fallback, and the card is authored empty on
  purpose. Right classes, right count, no date, no headline, no text, no name: dev's
  `/team/liesl-mathews/` beside live's three Liesl reviews.

  The card is now `require`d, so its blocks sit in the template's own parsed tree and the
  repeater's context reaches them the way `core/post-template`'s reaches its inner blocks.
  Nothing was wrong with the plugin: the cache on dev held Liesl's three reviews under
  `_transient_sd_enh_tp_reviews_<md5('Liesl')>` throughout.

  **References inside a `core/query` loop stay as they are** — `core/post-template` sets
  `$GLOBALS['post']` per row and the core blocks in those cards read the global post, so
  losing the block context costs them nothing. A review is not a post, which is what makes
  this one different.

### Changed

- 📐 **The brand Read more is italic, ellipsised, closer to the copy, and cuts at ten lines.**
  LS-2018 (line 8, Lodge / Brand). Zared's call, 2026-09-16.
  `patterns/template-taxonomy-accommodation-brand.php`,
  `assets/styles/core-term-description.css`.

  Four adjustments so the control reads as the `core/read-more` on a Tour Operator single —
  `patterns/destination-summary.php` — which is what the 2026-09-16 restyle set out to match
  and stopped one step short of.

  The label is now `Read more...`, carrying the ellipsis that pattern uses, and the toggle is
  set in italics by name. On the destination single the italic is inherited from the wrapping
  group's inline `font-style`; here the toggle is a sibling of the description rather than a
  descendant, so inheritance cannot reach it. `inc/intro-collapse.php`'s localised "Read
  less" stays plain — the ellipsis says the text continues past the cut, which is true of the
  collapsed state only.

  The story group's `blockGap` drops from `spacing|30` to `spacing|20`, and the group holds
  exactly the description and the toggle, so that is the gap between those two and nothing
  else.

  The clamp goes from six lines to ten, because six cut most brand stories mid-thought. With
  the measurement fix above, /brand/african-bush-camps/ — six lines at the 900px column —
  now gets no Read more at all, which is the intended behaviour and also live's.

- 📐 **The Brands landing standfirst runs to 1100px, and the measure is set on two blocks
  because one does nothing.** LS-2018 (line 8, Lodge / Brand).
  `patterns/template-page-brands.php`.

  Wider than the root `contentSize` (900px), narrower than the 1440px the `alignwide`
  group around it gets. Zared's call.

  ⚠️ **Setting it on the parent group alone has no visible effect**, which is what it
  looked like when it was tried in the Site Editor. A constrained layout constrains its
  *children*, never itself — so a `contentSize` on the group widens the
  `core/post-content` wrapper and stops. `core/post-content` is itself a constrained
  container, and with no `contentSize` of its own it falls back to the global 900px and
  re-caps every paragraph inside the wrapper it was just given. Verified against
  `wp_get_layout_style()`: a constrained layout emits
  `… > :where(:not(.alignleft):not(.alignright):not(.alignfull)){max-width:1100px}`, which
  reaches the wrapper and not the text. Both layouts carry the value now.

- 🏷️ **The single-brand archive loses its keyword box, result count and sort control, and
  the region strip moves into the results column.** LS-2018 (line 8, Lodge / Brand).
  `patterns/template-taxonomy-accommodation-brand.php`,
  `assets/styles/sd-brand-regions.css`. Zared's call, measured against
  `/brand/wilderness-safaris/`.

  A brand archive is already a narrow set — Wilderness Safaris, the largest, is a few
  dozen properties over six countries — and three chrome controls above a list that short
  read as search furniture rather than as a brand's page. The regions strip and the three
  checkbox facets are now the whole navigation.

  ⚠️ **The two taxonomy templates are deliberately no longer the same.**
  `template-taxonomy-accommodation-type.php` keeps all three controls. Do not restore them
  here for consistency — the divergence is the decision. The FacetWP facets themselves are
  untouched in `wp_options`, so putting any back is a markup change and nothing else.

  ⚠️ **The `Results` `h2` stays, as `screen-reader-text`.** The cards are `h3`. With no
  `h2` between them and the rail's own "Refine by", every card title would be announced as
  a child of the filter rail. The theme adds no CSS for it — checked rather than assumed:
  `wp_should_load_separate_core_block_assets()` is true on this install, so the monolithic
  `block-library/style.css` never loads and the class arrives from
  `block-library/common.css:222`, which is what the enqueued `wp-block-library` handle
  resolves to. Both files define it, so flipping that setting cannot break it.
  `#h-results` is kept as a real anchor.

  **The region strip** ran full width above the two columns and now heads the results
  column, directly over the first card — which is live's own relationship, where the strip
  sits immediately above `.lsx-to-archive-items`. Its `alignwide` is gone with the move: a
  block inside a `core/column` has no constrained layout to align against.

- 🎨 **The region strip is live's tinted bar, not an underlined tab rail.** LS-2018 (line
  8, Lodge / Brand). `assets/styles/sd-brand-regions.css`.

  **The separator is gone** — the `neutral-300` bottom border and the 3px active marker
  that overlapped it. With the strip inside the results column a full-width line under it
  separated the strip from the cards it belongs to, and live draws no such line.

  Measured from `sd-lsx-child/assets/css/partials/_single.scss` (compiled at
  `assets/css/custom.css:2846`) and mapped to tokens: bar `#f0ebe5` → `neutral-200` (the
  mapping decision 5 already made for the card meta strip), item `#60483b` →
  `neutral-700`, `border-right: 1px white` → `base`, hover/active bar `#3E3530` →
  `primary-600`, hover/active type `#cc7f16` → `brand-500` (exact). 14px uppercase 600 →
  font-size `200`, semi-bold.

  ⚠️ **`gap` is zero and must stay zero.** The separation between segments is the
  `border-inline-end` hairline, as on live; a gap would put the bar's own tint between
  segments and leave the hairlines reading as stray ticks. ⚠️ **The ground is on the
  `<li>`, the type on the `<a>`** — live's anchor carries `margin: 5px 0`, so only the
  list item paints the bar's full height. `:has()` carries hover and focus up to the `li`
  for that reason, and degrades correctly: without it the type still changes colour, so
  the state is never invisible. Focus takes an `accent-400` ring because `brand-500` on
  `primary-600` is already the current-segment pair.

- 🎨 **The brand story's Read more reads as a link, like every Tour Operator read-more.**
  LS-2018 (line 8, Lodge / Brand). `patterns/template-taxonomy-accommodation-brand.php`,
  `assets/styles/core-term-description.css`. Zared's call.

  `is-style-outline` is gone and no button style replaces it; the flat look is scoped CSS
  on `.sd-intro-collapse__toggle`, because one control does not warrant a theme-wide
  `is-style-*`. Font size `200` → `300`, matching `patterns/destination-summary.php`.

  ⚠️ **It is still a `core/button` and must stay one.** `assets/js/intro-collapse.js` binds
  to `.sd-intro-collapse__toggle a` and puts `role="button"`, `tabindex`, `aria-expanded`
  and `aria-controls` on it; `core/read-more` renders a link to a *post* permalink and
  there is no post on a taxonomy archive. Only the paint changed. A focus ring is added
  explicitly — the outline variation's border was carrying it, and a bare `<a>` with no
  `href` has nothing.

  ⚠️ **The reset answers each property by name** because with the variation gone the anchor
  falls back to theme.json's `elements.button` (brand-500 fill, heading face, uppercase) —
  including `font-size: inherit`, since `elements.button` writes the size onto the anchor
  while `core/button` puts its font-size class on the wrapping `<div>`. No underline in
  either state: theme.json's `elements.link` sets `textDecoration: none` at rest *and* on
  hover, so the read-more this imitates is not underlined anywhere on the site.

- 📐 **The accommodation list card's meta rows sit at XS.** LS-2018 (line 8, Lodge /
  Brand). `patterns/card-accommodation-list.php`. Shared with the accommodation-type
  archive.

  `blockGap` `spacing|20` (S) → `spacing|10` (XS) on the meta panel. Three short prefixed
  read-outs at S read as three separate statements rather than one block of facts, and the
  gap was wider than the leading inside each row. The panel's own inset padding stays at
  `20` — the two are deliberately no longer the same token, because they are doing
  different jobs.

- 🎨 **The facet chevrons are a quarter-rem smaller.** LS-2018 (line 8, Lodge / Brand).
  `assets/styles/facetwp-facets.css`. `2rem` → `1.75rem`, on the collapsible facet
  headings and the sort select alike — the file's own ⚠️ requires the two to match, since
  they sit in different type contexts and an `em` value would draw two different chevrons.
  At `2rem` the box filled the 32.4px heading row exactly and competed with the heading.
  Still well over the 24px minimum target: the hit area is the whole heading row, not this
  pseudo-element.

- **The Trustpilot review card carries stars and a linked headline, and clamps the
  extract.** LS-2020 (line 10). `patterns/card-trustpilot-review.php`,
  `assets/styles/core-paragraph.css`. Needs `sd-enhancements` at the matching revision —
  it adds the two source keys below.

  The star tile is back on the card, beside the date in a flex row, at 110px — live's
  `.tb-review-box img` is 40% of a ~300px box. It binds `sd/trustpilot-review`'s new
  `stars_image`, which is **that review's** rating rather than the company's, so a 4½-star
  review draws the 4½-star tile. Decorative: the date beside it is the labelled content.

  The headline is live's link again — an anchor to the company review page, arriving inside
  the bound value via the source's new `link` arg, because a binding replaces a block's
  whole `content` and `core/heading` has no bindable `href`. Core `wp_kses_post()`s
  rich-text replacements, so this is a supported route rather than a way round escaping.
  Hover is `brand-600` against live's `#cc7f16`, set as `elements.link` on the heading so
  it travels with the block.

  The extract is clamped to **three lines** in CSS. Live throws the rest of the review away
  in PHP at ten words; ten words is not a number of lines, so three reviews of different
  lengths gave three cards of different heights. The clamp cuts at the rendered measure and
  leaves the whole review in the markup. Not in a block-style `css` field: it needs
  `display:-webkit-box`, and the sanitiser drops `-webkit-box-orient`.

- **The team single's Trustpilot badge is stacked, matching live.** LS-2020 (line 10).
  New `patterns/trustpilot-score-stacked.php`; `patterns/template-single-team.php` requires
  it in place of `patterns/trustpilot-score.php`.

  Live ships one component and two arrangements, separated in CSS rather than PHP:
  `#tb-horizon-review` is a centred row with the band word hidden, and
  `#tb-list-review-container #tb-horizon-review` (`sd-lsx-child/assets/css/custom.css:4348`)
  is a column — band word (600, order 1), stars (2), count (3), mark (4) — with the
  `TrustScore 5 |` span set to `display:none` and a `Based on` prefix injected before the
  count. Three of the four children change, which is past what a `flex`/`orientation`
  switch can express, so the badge is authored in reading order in its own file rather
  than re-ordered with CSS `order`.

  Sizes are dev's, not live's: font-size 100, a 160px star tile and a 132px mark, against
  live's 12px text and `max-height:25px` on everything. Zared's call, 2026-09-16. Live's
  underline on the count is **not** carried over — it is not a link, and nothing else in
  the badge but the mark is.

  `patterns/trustpilot-score.php` is unchanged and still serves
  `patterns/safari-expert.php`, where live draws the row arrangement.

- ⭐ **The team single's Trustpilot review row is a carousel.** LS-2020 (line 10, Team /
  Safari Expert). `patterns/template-single-team.php`, `inc/review-slider.php`,
  `assets/js/review-slider.js`, `assets/styles/core-group.css`, `functions.php`.

  ⚠️ **The script is enqueued on `wp_enqueue_scripts` at priority 20 and depends on
  `tour-operator-script` — never on `slick`, and never from a `render_block` filter.**
  The first pass did both and took every slider on the team single down with it on dev
  (2026-09-16). `wp_script_is( $handle, 'queue' )` falls through to `recurse_deps()`, so it
  answers true for any handle that is merely a *dependency* of something queued — and Tour
  Operator guards its own vendor enqueue with exactly that question at priority 1. A
  render-time enqueue naming `slick` can land first, because an SEO plugin renders block
  content during `wp_head` to build its description; TO then registered neither `slick` nor
  `slick-lightbox`, and `tour-operator-script` — which depends on both — was dropped
  silently at print time, taking TO's `custom.js`, `sd-enhancements`' `to-slider.js` and
  this script with it. `sd-enhancements/modules/to-slider.php` already had the right shape;
  this now matches it.

  `sd/trustpilot-reviews` is now wrapped in a `core/group` carrying
  `sd-review-slider is-style-slider-frame` — the frame, because Slick appends its dot row to
  the *parent* of the element it initialises and `styles/sections/slider-frame.json` positions
  that row against the parent's edges. Same shape as the three shelves below it on the same
  page, where `core/query` is the frame and `core/post-template` the track.

  **It is not Tour Operator's initialiser.** TO's selector is
  `.lsx-to-slider .wp-block-post-template` / `.wp-block-term-template`, and the reviews block
  is neither — it is a repeater over a cached API response. Giving it a core class to be
  picked up would drag core's post-template CSS onto it and claim a query loop that is not
  there, so it keeps its own class and the theme initialises it with TO's settings: 3 up →
  2 at ≤1028 → 1 at ≤782, dots and swipe.

  **No arrows, at any width** — the one place the settings depart from the shelves.
  `Trustpilot::REVIEW_COUNT` caps the cache at three and the desktop row shows three, so an
  arrow could never move anything; and the frame's arrows sit outside its own edges, which on
  a 75% column would put the left one on top of the score badge beside it.

  ⚠️ **Live slides this row only below 767px** (`sd-lsx-child/assets/js/custom.js:358`) and
  leaves it a static flex row above. The shelves' responsive curve is used instead, at Zared's
  direction 2026-09-16, so the reviews are not the one row on the page with their own
  behaviour. Desktop is unchanged either way — three reviews in three slots is what live draws.

  Progressive enhancement throughout: the pattern authors the block as a three-column grid,
  which is the finished desktop layout on its own, and `is-layout-grid` comes off only at the
  point Slick takes over. No JavaScript, no jQuery or no Tour Operator leaves the row correct.

- 📏 **Tour and blog card meta now render at one size — base.**
  `patterns/card-tour-compact.php`, `patterns/card-post-grid.php`.

  The tour card's meta rows were rendering at three different sizes. The cause is
  `theme.json`'s `styles.blocks.core/paragraph.fontSize` of `300`: a block-level global style
  is not inheritance, so it lands on every `core/paragraph` and beats the Body group's
  `has-200-font-size` outright. The three paragraph rows came out at 300 while
  `core/post-terms` — a `<div>`, with no block style of its own — inherited 200 and the
  excerpt carried an explicit 200. Every row now carries an explicit `200`.

  ⚠️ **Those explicit sizes are load-bearing, not redundant.** Remove one and that row goes
  straight back to 300.

  The blog card's date and category rows moved `100` → `200` for the same reason of one
  size per card, and every meta row on both cards takes a 2px `padding-block` — Zared's
  measurement — which settles "days" against the number beside it and puts the whole meta
  block on one rhythm.

  Both cards' Body group gap came down `S` → `XS` with it (Zared, 2026-09-16): at base
  the rows are taller than they were at 100, and the old step left the meta reading as
  separate blocks rather than one stack.

  The travel-style row also lost its `medium` font weight, so the taxonomy and destination
  values render at the same weight. Only the prefixes are bold now, which is what the card
  style already says (`& strong`, `& .wp-block-post-terms__prefix`).

- 📐 **The team single's bio sets its own paragraph gap, and stacks in flow rather than
  constrained.** LS-2020 (line 10). `patterns/template-single-team.php`.

  `core/post-content` carries `blockGap: var:preset|spacing|30` (M) rather than falling to
  the root gap, which read as a stack of separate statements instead of one passage. `M` is
  the same step the meta rows above it use. On the markup, never in a variation JSON — see
  AGENTS.md.

  Its layout also moved `constrained` → `default`. With `useRootPaddingAwareAlignments` on
  — theme.json sets it — core adds `has-global-padding` to **every** constrained-layout
  block and not just the ones at the root
  (`wp-includes/block-supports/layout.php:1111-1117`), so a constrained `post-content`
  picked up the root left padding and the bio sat one `spacing|20` in from the Meet heading
  and the role above it. The column already constrains the measure; this block only needs to
  stack its children, which flow does, `blockGap` and all.

- 🎨 **The team single's role line is `brand-600` at font-size 400.** LS-2020 (line 10).
  `patterns/template-single-team.php`.

  Up from body colour at 300, so the consultant's job title reads as a standfirst under the
  name rather than as another meta row. Zared's call, 2026-09-16. The heading face, the
  semi-bold weight and `lsx-role-wrapper` are unchanged — that class is Tour Operator's
  empty-meta hook, not styling, so the whole line still disappears on a member with no role
  set.

- 🖼️ **The team member portrait is round.** LS-2020 (line 10).
  `patterns/template-single-team.php`.

  `core/post-featured-image` takes `border-radius` from preset `500` — `9999px`, whose
  **name** is `round`.

  ⚠️ **Reference radius presets by slug, not by name.** The first pass wrote the value as a
  raw `var(--wp--preset--border-radius--round)` and the portrait stayed square, because core
  keys the generated custom properties on the preset's `slug`: the variables block emits
  `--wp--preset--border-radius--0` through `--wp--preset--border-radius--500` and **no
  `--round`** (measured against `wp_get_global_stylesheet( [ 'variables' ] )`). So the
  declaration named a property that does not exist and was dropped at computed-value time.
  `var:preset|border-radius|500` is both the working form and the authored form the rest of
  the theme uses — which is the point of the rule: a slug reference is one
  `theme-orphaned-refs` can check, and a name reference is one it cannot.

  ⚠️ **The crop stays `aspectRatio: 1` and must.** The radius is what makes it a circle, but
  only the square crop keeps it from being an ellipse. Image crops are `aspectRatio`, never
  CSS — see AGENTS.md.

### Fixed

- ⏳ **A tour with no duration no longer renders "Duration: days".**
  `patterns/card-tour-compact.php`. The Duration group carries `lsx-duration-wrapper`, which
  is Tour Operator's hook rather than a styling class: `Query_Loop::maybe_hide_varitaion()`
  (`class-query-loop.php:96`) filters `render_block`, matches `(lsx|facts)-<key>-wrapper` on a
  `core/group` or `core/paragraph`, and returns an empty string when that key's post meta is
  empty.

  It is on the **group**, deliberately. Tour Operator prepends the prefix with no test on the
  value, so an empty duration rendered `<p><strong>Duration:</strong> </p>` next to a live
  "days". The paragraph is not `:empty`, so the card style's `p:empty` rule cannot reach it —
  only hiding the group takes the value and the "days" together.

  Measured against Luxury Honeymoon Adventure (dev, 57936), whose `duration` meta is `""`,
  and verified locally by blanking and restoring a tour's duration: the group and its "days"
  both disappear, and both return.
### Added

- ❮ **A left chevron on the blog category archive's *Back To Blog* link.** LS-2022 (line 12,
  Blog Templates and Related Posts). `styles/blocks/paragraph/back-link.json`,
  `assets/styles/core-paragraph.css`, `patterns/template-category.php`.

  Zared, 2026-09-16. Live's anchor is plain body-scale link text and stays that way; the chevron
  is the one thing added, so the link reads as a way back rather than as another link in the
  page. It is the same Phosphor `CaretLeft` the slider frame's previous control uses, as a
  `::before` mask painted with `currentColor` — so it follows the link's colour and its hover
  with no second rule, and being a pseudo-element it never enters the accessible name.

  The anchor's `inline-flex` layout is in the variation's `css` field, where the editor lays it
  out too; only the `::before` is in `assets/styles/core-paragraph.css`, because a `css` field
  mangles `content: ""` and drops the rule containing it. Verified locally: the variation
  registers against `core/paragraph`, the pattern renders `is-style-back-link--2`, and core
  emits `:root :where(p.is-style-back-link--2 a){display: inline-flex; …}` on
  `block-style-variation-styles`.

- ❯ **The big chevron on the single post's previous/next pager.** LS-2022 (line 12).
  `assets/styles/core-post-navigation-link.css`.

  Live draws a 55px FontAwesome angle inside each pager link, absolutely positioned 15px from
  the edge and vertically centred, taking its colour from the anchor — measured in the browser
  on the Camille post, 2026-09-16 (`lsx/assets/css/gutenberg.css`). The pager reproduces it as
  a Phosphor caret mask painted with `currentColor`: the same glyph pair the carousel arrows
  already use, so the theme does not load FontAwesome for one character, and the colour still
  resolves through a token.

  It is a `::before` on the anchor rather than `core/post-navigation-link`'s own `arrow`
  attribute, which renders a `«`/`»` span *outside* the link and so would sit outside the
  padded box and miss the link's hover. Size and clearance are `--sd-post-nav-chevron-size` /
  `--sd-post-nav-chevron-gap`. (The 4px hover slide this shipped with is gone — see *The
  single post's pager sits tighter…* under **Changed**.)

- 📂 **The blog category archive, rebuilt from the live site.** LS-2022 (line 12, Blog
  Templates and Related Posts). `patterns/template-category.php`, used by
  `templates/category.html`.

  `/category/rwanda/` and its siblings now render as live renders them: a photographic banner
  carrying the category name, the breadcrumb strip, a *Back To Blog* link, the list of post
  rows with pagination, and the *Why choose Southern Destinations* value band. It is the blog
  landing with the intro band taken out and the back-link put in, and it shares the blog
  landing's post row (`card-post-list.php`) so the two pages agree.

  **The heading is the category itself**, rendered from the queried term rather than authored,
  so every category gets its own title with no per-term template. Live prints the same string
  twice — once visibly in the banner as the bare term name, once hidden as "Category: Rwanda";
  the visible one is reproduced and the hidden Tour Operator artefact is dropped, as it was on
  the team and destinations archives.

  **The loop inherits the main query**, so the category's own pagination and Settings →
  Reading govern it and nothing about publishing or categorising changes — task 12.5.

  ⚠️ **The banner photograph is the same on every category.** Live sets a banner image per
  category — Rwanda draws a gorilla-trekking shot — and a `core/cover` takes a fixed URL, so
  following the term needs a block binding over the term's banner meta. That is
  `sd-enhancements` work, not theme work. Until it exists the banner carries the same
  photograph the blog landing uses, and switching it to a binding later is a change to one
  block.

- 📰 **The blog landing page, built from the live site.** LS-2022 (line 12, Blog Templates
  and Related Posts). `templates/home.html`, `patterns/template-home-blog.php` and
  `patterns/blog-categories.php`.

  The posts index now has a template of its own. What a reader sees, top to bottom: the
  breadcrumb strip, a warm band carrying the *Tales from our trails* heading and the italic
  standfirst with its drop cap, the Browse By Category shelf, the list of post rows with
  pagination, and the *Why choose Southern Destinations* value band. That is live's blog
  landing, section for section.

  **The loop inherits the main query**, so Settings → Reading still decides how many posts a
  page holds and publishing, scheduling and back-dating behave exactly as they do today.
  Nothing an editor does changes because of this template — task 12.5.

  **The category shelf follows the taxonomy.** It is a `core/terms-query` over the post
  categories with empty ones hidden, so adding or renaming a category updates the shelf with
  no template edit. Live hard-codes nine tiles; this does not.

  Two pieces that were already in the theme get their first use here: `card-post-list.php`,
  the post row built for this page back in August, and `card-category.php`, the tile whose
  style variation has described itself as belonging to "the live blog landing" since it was
  written.

- 🏞️ **A photographic banner on the blog landing.** LS-2022 (line 12).
  `patterns/template-home-blog.php`.

  The page now opens on the same 454px banner as every other landing page in the theme,
  above the breadcrumb strip, carrying the page title over the old About Us photograph
  (`uploads/2019/07/about-us-banner.jpg`).

  This reverses a call recorded when the template was written. LSX Banners *is* configured on
  live's blog and points at `blog_header.jpg`, but the child theme collapses the banner to a
  50px sliver, so the photograph is never seen there — which is why the first build opened
  on the breadcrumb strip and logged the banner as a design decision rather than a
  translation. Zared ruled it in on 2026-09-16.

  **The `h1` moved with it.** Live authors `<h1 class="archive-title">Blog</h1>` and then
  hides it; with a banner to sit in, that heading is now the visible page title, and
  *Tales from our trails* steps down to the `h2` that follows it. The page still has exactly
  one `h1`.

### Fixed

- 🖼️ **The Browse By Category tiles show their images.** LS-2022 (line 12).
  `patterns/card-category.php`.

  The tile bound its image `url` and `alt` to `sd/term-meta` with `key: sd_thumbnail`.
  `sd_thumbnail` is the brand logo field on `accommodation-brand`, ported from the child
  theme — **no post category has ever held that key.** Post categories store their tile image
  under plain `thumbnail`, which is what LSX Banners wrote on live and what 10 of the 11
  category terms on dev already carry. So every tile in the band rendered imageless, and the
  binding failed the way bindings do: silently, with an empty `<figure>`.

  The key is now `thumbnail`, with the reasoning written into the pattern so it is not
  "corrected" back. The editing UI for the field and the `show_in_rest` registration that
  makes it readable here are plugin work — `SD_Enhancements\TermMeta`, same issue.

### Removed

- 🗑️ **`patterns/template-index-news.php` — the KWV-era blog landing.** LS-2022 (line 12).

  It ran a dark "News" cover hero and a sticky categories sidebar down the right-hand side.
  The live blog landing has neither: it is a single full-width column, and its categories are
  the shelf in the intro band. The pattern came across from the `kwv-theme-2026` base and was
  never measured against this site, so it is removed rather than left standing as a second,
  contradictory blog landing. `templates/index.html` — the generic fallback — now points at
  **Template: Blog Landing** alongside the new `templates/home.html`.

### Changed

- ✍️ **The single post byline is two blocks, and the article breathes.** LS-2022 (line 12, Blog
  Templates and Related Posts). `patterns/template-single-post.php`.

  Zared, 2026-09-16, edited in the Site Editor on dev and imported back to the pattern. The
  byline word is now its own `core/paragraph` beside `core/post-author-name` in a nowrap flex
  row, where it had been `core/post-author`'s `byline` attribute. `core/post-author` renders the
  word inside the author block's own wrapper, so the word could not carry its own font size and
  sat on the author's baseline rather than the date's; splitting them lets the row align on one
  line and keeps "by" translatable in its own string. `core/post-date` picks up the
  `core/post-data` `datetime` binding the editor now writes for it.

  The spacing and tint move with it: the article's block gap opens from `spacing|20` to
  `spacing|60` so the header, body and closing band read as three sections rather than one
  column; `core/post-content` takes `spacing|30` between its own paragraphs; the byline row
  opens from `spacing|5` to `spacing|10`; and the byline and category tint move from
  `brand-500` to `brand-600`, the darker of the two against the light page section. The related
  heading's anchor is `h-related-posts`, which is what the editor assigned it — nothing links
  to the old `h-related`.

  Imported selectively, not verbatim. The editor's flattened copy also carried its own
  normalisation — the injected pattern metadata, an inert `placeholder` on the byline
  paragraph, `textAlign` relocated into `style.typography`, and reordered attribute keys — none
  of which changes a rendered class, so none of it was brought across. `breadcrumbs.php` and
  `card-post-grid.php` came back identical; `why-choose-sd.php` differed only by that
  normalisation and is untouched.

- 📏 **The two blog banners open at 360px, not 454px.** LS-2022 (line 12, Blog Templates and
  Related Posts). `patterns/template-category.php`, `patterns/template-home-blog.php`.

  Zared, 2026-09-16 — the banner was crowding the first post row off the fold on both pages.
  360px is the floor the two Tour Operator singles already carry
  (`patterns/destination-banner.php`, `patterns/template-single-tour.php`), so the list pages
  now open at the same height as the pages they lead to. The two move together deliberately: a
  step in banner height between the blog landing and a category would read as a mistake. The
  page landings keep 454px.

- 🖼️ **The category banner is the category's photograph, not one image for all of them.**
  LS-2022 (line 12). `patterns/template-category.php`.

  The cover now declares a `sd/term-meta` binding on `url` against the term's `banner` meta —
  the same association live has, and one that was already in the database. `sd-enhancements`'
  new `TermBanner` module paints it; `core/cover` is a static block whose `url` declares no
  `source` in block.json, so the binding resolves the value and cannot reach the HTML on its
  own. The reasoning is written out on the pattern and on `modules/term-banner.php`.

  **The `url` in the markup is now the fallback, not the banner** — what the two categories with
  no `banner` row (Safari Tips, Botswana) wear, and what the page wears with the plugin
  deactivated. It is still the blog landing's image, so an unset category reads as the same
  section of the site. The note claiming the banner could only be static here is corrected
  rather than left to contradict the markup.

- 📐 **The category archive's top band is tighter.** LS-2022 (line 12).
  `patterns/template-category.php`.

  Zared, 2026-09-16. `is-style-light-page-section` opens on spacing|70, which is right for a
  content band and too much for a one-line back-link: it put ~70px between the breadcrumb strip
  and the link, and another ~70px between the link and the first post row. The back-link band now
  opens on spacing|40 and the post list on spacing|30, so the breadcrumbs, the link and the first
  row read as one group instead of three separated bands.

- 🎠 **The single post's related shelf is a carousel of fifteen, not a row of three.** LS-2022
  (line 12, Blog Templates and Related Posts). `patterns/template-single-post.php`.

  Zared, 2026-09-16. Live draws three tiles and stops; this shows fifteen on a Slick carousel,
  three at a time. It is now the same object as the homepage's *Tales from our trails* band —
  `hasCustomClass` + `lsx-to-slider` is Tour Operator's "Enable Slider" checkbox on
  `core/query`, `is-style-slider-frame` supplies the arrows and dots, and `slidesToShow` comes
  off the `columns-3` class `core/post-template` emits from its own grid `columnCount`, which
  doubles as the no-JavaScript fallback.

  ⚠️ **The count lives in two places and they have to agree.** The block's `perPage` never
  reaches `WP_Query` on this shelf: `Queries::relate_posts_by_category()` replaces Tour
  Operator's arguments wholesale, so `RELATED_POSTS_PER_PAGE` in **sd-enhancements-2026**
  moves to 15 alongside this. Changing one alone changes nothing.

  The grid's `minimumColumnWidth: 16px` is gone with it — it made core add
  `has-native-responsive-grid` and switch the fallback to an auto-fill track rather than the
  three-up row Slick is about to be told it has. The homepage carousel has never set it.

- ❮ **The single post's pager sits tighter, and the chevron holds still.** LS-2022 (line 12).
  `assets/styles/core-post-navigation-link.css`.

  Three changes, all Zared's, 2026-09-16.

  `--sd-post-nav-chevron-gap` drops from spacing 40 (26–40px) to spacing 10 (8–10px). The gap
  was never the whole of what a reader saw: Phosphor's caret path spans about 38% of its
  square viewBox, so a 44px chevron box already leaves roughly 13px of empty mask on the inner
  side before the gap starts, and spacing 40 on top of that read as a gulf.

  **The 4px hover slide is removed.** The caret is a signpost here, not a control the reader
  aims at, and the movement pulled the eye off the title that is the actual link. Live's glyph
  is static too, so this is back to the port. The `prefers-reduced-motion` block keeps only the
  transition damping it still has something to damp.

  **"Previous Post" / "Next Post" is brand-600 by default**, and the chevron flips to brand-600
  on `:hover` / `:focus-visible` rather than following the anchor to brand-500. Set on the
  label rather than the anchor because the adjacent post's *title* stays `contrast` — the title
  is the link's content, the label its signpost — and the caret's hover is stated explicitly
  because `background-color: currentColor` would otherwise land it a step short of the label's
  tone. Live paints its label #cc7f16 (brand-500); brand-600 is the tone the slider arrows'
  active state already uses, so the closing band's two brand accents now agree.

- ↔ **The category slider's arrows stand clear of the band.** LS-2022 (line 12).
  `styles/sections/slider-frame.json`, `assets/styles/core-group.css`.

  Zared, 2026-09-16 — the chevrons sat almost against the category artwork. The clearance a
  reader sees is three things added together: the arrow's own offset from the frame edge, the
  slide's 15px inset, and the empty margin inside the caret's square mask box.
  `.sd-slider-tight` sets `--sd-slider-slide-gutter` to 0 by design — the band is one
  continuous bar, not a row of cards — which gives the middle one away and leaves the chevron
  nearly touching the tile.

  The arrow offset is now a token, `--sd-slider-nav-offset`, declared on the frame at the 10px
  every shelf already used, so nothing else moves. `.sd-slider-tight` sets it to 28px: the
  10px + 15px the card shelves read at, plus a little. The gutter *between* tiles stays 0.

- 🎠 **The category shelf on the blog landing is one continuous band again.** LS-2022
  (line 12). `patterns/blog-categories.php`, `styles/sections/slider-frame.json`,
  `assets/styles/core-group.css`.

  The shelf's term template has always carried `blockGap: 0`, because live's five 228px tiles
  fill a 1140px rail exactly and butt together into a single grey bar. That was only half the
  story: Tour Operator insets every slide by 15px on all four sides once Slick takes over
  (`.wp-block-terms-query.lsx-to-slider .slick-slide{padding:15px!important}`,
  tour-operator/build/style.css), so the carousel put a 30px gutter back between tiles the
  block markup had already set to zero.

  The vendor constant is now restated as `--sd-slider-slide-gutter`, defaulting to the
  vendor's own 15px so every existing card shelf renders unchanged, and the new
  `sd-slider-tight` modifier sets it to 0 for the category band. It is deliberately not
  `sd-slider-flush`, which also strips the shelf's focus-ring padding, drops its block margins
  and forces a flex track — none of which an in-flow shelf wants.

- 🔗 **The single post's related shelf now runs on a Tour Operator related query.** LS-2022
  (line 12, Blog Templates and Related Posts). `patterns/template-single-post.php`; the rule
  itself is `SD\Enhancements\Queries::relate_posts_by_category()` in `sd-enhancements`.

  The `core/post-template` was classed `sd-related-posts-query` against a filter that had
  never been written, so the shelf rendered the three most recent posts sitewide and the post
  being read could appear in its own related list. It is now `lsx-post-related-post-query`,
  which is a name Tour Operator acts on: `Query_Loop::query_args_filter()` matches
  `/(lsx|facts)-(.*?)-query/` on the className, derives the key `post-related-post` and ends
  by applying `lsx_to_query_loop_query_args_post-related-post` — the hook the plugin now
  takes. Same rails as `lsx-tour-related-tour-query`, not a parallel set of our own.

  ⚠️ **TO 2.2 ships no post↔post variation** — verified against the installed plugin locally
  and on dev, both 2.2. Its `default:` branch reads a `post_to_post` connection meta key that
  SD's posts have never had, and sets `post__in` to the post being read; the plugin filter
  clears that first. TO's "hide the wrapper when the query is disabled" affordance is not
  usable here for the same reason — it is decided before the filter runs — so the plugin
  guarantees a non-empty shelf instead of an empty one.

- 📏 **Tightened the gap between the elements of the single post's content section.** LS-2022
  (line 12). `patterns/template-single-post.php`.

  The `article` group's `blockGap` goes from L (`spacing|40`) to S (`spacing|20`), so the
  byline/title/categories header sits closer to the body copy it introduces. At Zared's
  direction, 2026-09-16.

- 🧹 **Removed the inherited "News" archive layout from the category template.** LS-2022
  (line 12). `patterns/template-category.php`.

  The category template had been running the KWV base theme's news archive — a dark cover
  hero over a sticky `core/categories` sidebar in a 90/20 column pair. Southern Destinations'
  category pages have none of that: no sidebar, no dark hero, no categories list. It was
  carried across with the theme architecture and never measured against this site, the same
  way `template-index-news` was on the blog landing, and it is replaced rather than left
  standing as a second, contradictory blog archive.

- ✍️ **The blog post row and landing page, as authored in the Site Editor.** LS-2022
  (line 12). `patterns/card-post-list.php` and `patterns/template-home-blog.php`, imported
  from the Blog Home template override on dev (2026-09-16).

  These are the adjustments made against the real migrated posts rather than the six local
  fixtures. The row: the image column narrows to 30%, the body column gains a little top
  padding so the title sits level with the photograph, and the byline opens out — date,
  author and category now breathe at `spacing|20` while *by* stays tight against the author
  name. The landing page: pagination switches to chevrons without their labels, and the
  empty-loop message reads *No stories yet, check back in regularly as we post often.*

  **The byline is composed rather than `core/post-author`.** That block renders its byline
  word and the name into one container it owns, so the two could not be spaced independently
  of the gap between the byline's three parts. It is now a literal *by* beside
  `core/post-author-name`, in their own group.

  ⚠️ **The tag rule's border width is a literal `1px`, deliberately.** `core/post-terms` is
  a dynamic block, so its styles are resolved by the server-side style engine — which expands
  `var:preset|…` only, and only for properties that declare `css_vars`. `border.width`
  declares none. The `var:custom|border-width|200` this row previously carried had therefore
  never drawn a rule at all. Measured on local 2026-09-16; the reasoning is in the pattern so
  it is not "corrected" back to a token.

- 📄 **The single blog post page, rebuilt from the live site.** LS-2022 (line 12, Blog
  Templates and Related Posts). `patterns/template-single-post.php` and
  `assets/styles/core-post-navigation-link.css`.

  What a reader sees, top to bottom: the breadcrumb strip, the article — an italic
  date-and-author byline, the title, the categories, the post — then a full-bleed tinted
  band carrying three **Related Posts** and the previous/next pager, and the *Why choose
  Southern Destinations* value band. That is live, section for section, measured on
  2026-09-16 against two posts so nothing in it is a property of one article.

  **The pattern that stood here was the `kwv-theme-2026` base's and had never been measured
  against this site.** It ran a "← Back to News" link, an author avatar, a 1:1 featured
  image beside the title in a 60/40 pair, and a bare prev/next row. Live has none of the
  first three; the pager is kept and moved into the closing band where live puts it.

  ⚠️ **Live does not render the featured image on a single post** — measured on both posts.
  The photograph at the top of the Namibia article is a `core/image` inside the post
  content, placed by the author; the second post carries a featured image and shows no image
  at all. The featured image is for the cards. Putting one back on the page is a new design
  decision rather than a translation, and it is one block away.

  **The related tile is `card-post-grid.php`, used whole.** Live's related card is the same
  centred title, date and excerpt, with the author, categories, "Read More" and tags all
  switched off in CSS. Those last two are the only difference, and a post tile looking like
  the same object wherever it appears was worth more than a second card — the same call
  `template-single-team.php` made for its blog shelf. Two attributes come out if you'd
  rather match live exactly.

  🔵 **The related shelf needs one filter in `sd-enhancements` before it is correct.**
  "Posts sharing a category with this one, minus this one" cannot be written in block
  markup — `taxQuery` holds fixed term IDs and `exclude` holds fixed post IDs — and it is
  behaviour, not design, so it falls on the plugin side of the deactivation test. The
  `core/post-template` carries `sd-related-posts-query`, which is the convention Tour
  Operator 2.2 and `sd-enhancements`' own `Queries` module already use. **Until that filter
  lands the shelf shows the three most recent posts sitewide, and the post being read can
  appear in its own related list.** An unfiltered shelf is visibly wrong and gets fixed; a
  silently empty one looks like a template bug and can survive a release.

- 🖼️ **All four gallery bands now use `sd/gallery` instead of the `lsx/gallery` placeholder.**
  LS-2023 (line 13, Gallery Implementation).

  `patterns/template-single-team.php`, `patterns/template-single-accommodation.php`,
  `patterns/template-single-tour.php` and `patterns/destination-gallery.php`. Each was a
  `core/gallery` carrying Tour Operator's `lsx/gallery` binding plus three empty `core/image`
  blocks that existed only to give the editor something to show; each is now one self-closing
  `wp:sd/gallery`. The ⚠️ "this is the placeholder pass, not the gallery build" note that
  stood in all four is gone — this is the gallery build.

  What changes on the page: live's actual layout. Two tiles across the top row, three across
  the second, a `+N more` overlay on the fifth, and a lightbox over the whole set — where the
  binding rendered every image in a flat grid with no `srcset` and no `alt`. The block is
  `sd-enhancements`' (`blocks/gallery/`), and it carries its own grid, tile box and overlay
  CSS because those three are load-bearing rather than decorative.

  **No theme CSS was added.** The block ships the rules that hold the layout up and leaves
  colour, type, radius and the lightbox chrome to the theme; nothing in `styles/**` or
  `assets/styles/` needed to change for this. If the gallery should pick up theme spacing, the
  hook is `--sd-gallery-gap` (4px, live's gutter) and the tile shape is `--sd-gallery-ratio`.

  **`lsx-gallery-wrapper` still collapses the band.** `maybe_hide_varitaion()` reads the
  `gallery` meta directly and does not care which block sits inside, so a post with no gallery
  still loses the section and its heading. Verified on all four patterns.

  ⚠️ **One visual decision to confirm:** the tiles default to **3:2**, matching Tour
  Operator's `lsx-to-gallery` 900×600 crop so a tile does not crop an already-cropped file
  twice. Live's Envira config asks for 4:3 but has never actually rendered it — its gallery is
  broken in production (the tile box collapses to 4px; the diagnosis is in the block's
  `render.php`). If 4:3 is wanted, it is one select in the block's Settings panel.

### Added

- 🧭 **Breadcrumbs on both team templates, and the dev Site Editor changes imported.**
  LS-2020 (line 10, Team / Safari Expert). `patterns/template-archive-team.php` and
  `patterns/template-single-team.php`.

  **Breadcrumbs.** `require __DIR__ . '/breadcrumbs.php'` directly under the banner on
  both, the same placement every other archive and single in this theme uses. Both files
  used to record the opposite — the archive under "What is deliberately not here", the
  single under "What this template does not carry". Those notes predated the 2026-09-03
  decision recorded in `patterns/breadcrumbs.php` — placing the block is design, filtering
  what Yoast puts in the trail stays plugin work — so they have been removed rather than
  left to contradict it. The team post type's parent-link handling, which decides what the
  trail actually *says* on a member, remains LS-2020 item 10.7 in `sd-enhancements`; the
  band renders either way.

  **Imported from dev** (`wp_template` 65947, modified 2026-09-11 13:11). One real edit:
  the standfirst is now centred at font-size 300 inside an `Intro` group constrained to
  1100px, where it was a plain left-aligned `alignwide` paragraph at 200. That is a
  departure from live, which runs it left-aligned and roman at 15px — Zared's call, and
  noted as such on the block so the next reader does not "correct" it back.

  **What was deliberately not imported.** The editor's copy hard-codes the `role` term
  IDs (1810 / 1699 / 1811), which are dev's. This file resolves them from their slugs at
  runtime with a `-1` fallback precisely because local, dev and live do not share term
  IDs, so importing them verbatim would have broken two environments out of three. The
  runtime lookup stands. Also dropped: the editor's `patternName` / `description` /
  `categories` expansion metadata, and its loss of the banner cover's `alt=""` and
  `dimRatio`.

  **Reserialised, no-op:** `taxQuery` moved to WP 7.1's `{"include":{"role":[id]}}` shape.
  Core keeps a back-compat branch for the old form (`blocks.php:2907`), so this changes
  nothing at render time — it stops the editor rewriting the file on every open.

  `patterns/card-team.php`, `why-choose-sd.php` and `cta-not-sure-where-to-go.php` were
  byte-identical to their inline expansions on dev, so all three stay `require`d.

  Verified on local 2026-09-11: the pattern parses, sections land in order (Banner →
  Breadcrumbs → Team → Intro → the three role sections), and
  `build_query_vars_from_query_block()` still reads the new shape into a real `tax_query`.
  ⚠️ **The dev DB override is still in place** — it must not be cleared until this theme
  file is deployed there, or dev falls back to a stale template.

- 🗺️ **The team member map on the single team member template.** LS-2020 (line 10, Team /
  Safari Expert). `patterns/template-single-team.php` now carries live's `#map` — "Places
  {name} has visited" — between the gallery and the tours shelf, which is exactly where the
  file's own docblock said it would go when the block landed. Nothing else on the template
  changed.

  **The block is `sd/team-map` from `sd-enhancements`, not Tour Operator's
  `lsx-tour-operator/google-map`.** The destination summary composes that variation by hand
  in forty lines; this section is three, because the block emits the plate, the `.lsx-map`
  data carrier and the marker data itself. It has to: on TO 2.2 the `lsx/map` binding cannot
  answer for a team member — `lsx_to_has_map()` has no `team` case, its `default` branch
  wants the post's own coordinates, which a person does not have, and `lsx_to_map()`
  discards its own output. All three are answered in the plugin.

  **The heading is the theme's, like every other heading here** — `sd/post-field` with
  `format: first-name`, `prefix` `"Places "` and `suffix` `" has visited"`, so this file
  owns the standing halves and their translation. `lsx-location-wrapper` on the section
  drops the band, heading included, when `lsx_to_has_map()` is false.

  **Hidden on phones, because live hides it on phones.** `custom.css:2642-2647` puts `#map`
  in a `max-width: 767px` display-none beside the tour, destination and accommodation maps.
  That is a Block Visibility `small` control, not a CSS hide — and `small` is that
  breakpoint exactly, `@media (max-width: 767.98px)` off the 768px `medium` setting.

  Verified on local 2026-09-11 against a seeded team member: band, plate, `.lsx-map` and two
  `map-data` marker nodes render, plate and `.lsx-map` are siblings inside one
  `.lsx-location-wrapper`, the hide class lands server-side, and the heading composes to
  "Places Liesl has visited". With the connection meta removed the whole band disappears.
  The fixture was reverted. ⚠️ **Not yet seen in a browser**: local has no Google Maps API
  key, so the Google tiles themselves are unproven — that needs dev, which has the key and
  the real connections.

- 🔗 **The team map's plate label styled as a link.** `assets/styles/sd-team-map.css`,
  attached to `sd/team-map` by `inc/team-map.php` — the same arrangement
  `inc/brand-regions.php` uses for the other sd-enhancements block the theme dresses.

  The plugin ships a neutral dark pill as a standing default and says in its own
  stylesheet header that "colour and type are the theme's". This unwinds the pill —
  background, padding and radius all go — and leaves a link: all caps in the heading
  face at font-size 400, semi-bold, letter-spacing wide, `neutral-800` turning
  `brand-600` on hover and focus. Zared's call, 2026-09-11; live leaves the LSX plate
  label unstyled, so there is no measurement behind this one.

  **Two classes deep on purpose.** The plugin's rules are single-class, and two block
  stylesheets on the same block have no guaranteed order, so
  `.sd-team-map .sd-team-map__plate-action` wins on specificity rather than on luck. No
  `!important`. (Measured order happens to favour the theme anyway — the plugin's sheet
  enqueues first.)

  **Not theme.json.** `styles.blocks` reaches the block wrapper, and `elements.link`
  would catch every `<a>` inside it — including the ones Google writes into the marker
  info windows once the map has drawn. The plate label needs a selector.

  The hover colour is the whole affordance now the pill is gone, so `:focus-visible`
  carries it too, the UA outline is left in place, and the transition moved from
  `background-color` to `color`. Contrast is comfortable because of what the plate
  photograph is — Tour Operator's placeholder is a washed-out world map, pale sea and
  cream land; a darker plate image would need a scrim, not a different colour.

  Verified on local 2026-09-11: all eight tokens resolve against the generated global
  stylesheet with no orphaned refs, and the sheet is absent before the block renders and
  enqueued after. ⚠️ **Not yet seen in a browser** — the local server was not running.

- 🏷️ **The Brands landing and the single-brand archive.** LS-2018 (line 8, Lodge / Brand).
  Two templates, both bound to theme files rather than to Site Editor layouts:
  `templates/page-brands.html` (core resolves it by the page slug `brands`, dev 52337) and
  `templates/taxonomy-accommodation-brand.html`, which replaces the generic stub that was
  standing in for it. Their markup is `patterns/template-page-brands.php` and
  `patterns/template-taxonomy-accommodation-brand.php`.

  **The plugin side already existed and is used, not rebuilt.** LS-2528 shipped
  `SD\Enhancements\BrandEndpoints` (the `brand/{brand}/{region}/` rewrite and the region
  resolver), the `sd/brand-regions` tab strip and `Queries::bound_brand_archive()`;
  `sd/term-image`'s own render.php says the brands grid "stays a theme pattern". This is
  that grid, and the templates around it. Nothing in sd-enhancements was touched.

  **The landing** is a `core/terms-query` over `accommodation-brand`, four across, with
  `sd/term-image` as the logo — so all twenty-one brands come from the taxonomy and there is
  no list in the theme to keep in sync. ⚠️ It reads `sd_thumbnail`, where
  `patterns/homepage-brands.php` reads Tour Operator's `thumbnail` through
  `core/post-featured-image`. Two pages, two meta keys; if the logos ever disagree, that is
  why. → flagged on LS-2033, not resolved here.

  **The single brand departs from live in four asked-for ways**: the filters come out of the
  intro and into the results rail, the intro splits into the brand's story beside its logo
  (8/4, top-aligned), the story collapses behind a Read more, and the results are
  `patterns/card-accommodation-list.php` — the horizontal row the accommodation-type archive
  already uses. The facet set is live's four (keyword, Destinations, Specials, Types), and
  **Types belongs here** where the type archive deliberately dropped it: this archive is
  scoped by brand, so the facet still has choices to offer.

  **The Read more needed a script, and it is strict progressive enhancement.**
  `core/read-more` was not available: it renders a link to a *post* permalink and there is no
  post on a taxonomy archive, and `core/term-description` emits the whole description in one
  dynamic block, so there is no first block to collapse to. `assets/js/intro-collapse.js`
  measures the text **unclamped** — a clamped `-webkit-box` reports the same `scrollHeight`
  and `clientHeight` and would always say "no overflow" — and adds `.is-enhanced` only once
  it has confirmed the text overflows. JavaScript off, or a short description, gets the full
  story and **no button at all**, which is also live's behaviour. ⚠️ `CLAMP_LINES` in the
  script must stay in step with `-webkit-line-clamp` in the stylesheet; the value cannot be
  read back reliably across browsers.

  **`is-style-archive-intro` could not be reused on this block**, and the reason is the one
  `patterns/destination-summary.php` and `patterns/template-taxonomy-accommodation-type.php`
  both hit: the variation is registered for `core/paragraph`, whose `selectors.root` is a
  bare `p`, so it compiles to `p.is-style-archive-intro` and can never match
  `core/term-description`'s `<div>`. New `assets/styles/core-term-description.css` carries
  the italic, size, leading and drop cap instead — picked up by
  `enqueue_custom_block_styles()`' `core-*` scan, so the filename is the wiring. Adding the
  block to that JSON's `blockTypes` was the other route and was not taken: one variation
  compiling against two `selectors.root` values is how a shared style quietly stops matching
  one of them.

  **Two new `inc/` modules**, both following `inc/facetwp.php`: `inc/brand-regions.php`
  enqueues `assets/styles/sd-brand-regions.css` against `sd/brand-regions` (a non-core
  block, so outside the `core-*` scan), and `inc/intro-collapse.php` enqueues the collapse
  script against `core/term-description` and localises its "Read less" label —
  `core/button` saves a fixed `<a>`, so a `data-label-expanded` written into the pattern
  would fail block validation the moment the template was opened.

  ⚠️ **The region tabs are links, not `core/tabs`, and one thing about them remains open.**
  WordPress 7.1 does ship `core/tabs` and it was the asked-for block; it is not used because
  a brand's regions are derived per brand (so the panels cannot be authored into one
  template) and because `brand/{brand}/{region}/` is, in the plugin's own words,
  "launch-critical: it feeds the redirect map" — `core/tabs` switches panels on one URL.
  `SD\Enhancements\Queries::scope_brand_archive_to_region()` now narrows the main query via
  `post__in`, using the active region's connected accommodation; the template's `core/query`
  inherits that scope.

- 🔤 **Optima ships. The `heading` preset finally has a real face.** Vanessa Ratcliffe (SD)
  sent the Monotype self-hosting kit on **2026-09-10** — `docs/DS Optima DemiBold/`, MyFonts
  build 3867246 — closing the gap that has stood since 2026-08-18, when the three previously
  bundled Optima faces were removed as unlicensed. Applied per the prepared drop-in,
  `.github/tasks/optima-webfont-kit-dropin-2026-09-09.md`, taking **option A**.

  **The face.** `assets/fonts/optima-600-normal.woff2`, 32 KB, the kit's `font.woff2`
  renamed to the theme's `<family>-<weight>-<style>` convention. `fc-scan` reads it as
  family `Optima LT Pro`, subfamily `SemiBold` / `Demi Bold`, PostScript
  `OptimaLTPro-DemiBold`, `usWeightClass 600`, foundry `MONO` — the same cut as the licensed
  desktop OTF, so `600` is the right number in the filename. The WOFF sibling is not
  bundled; every other face here is WOFF2-only and has been for years.

  **It is a different binary from live, and a better one.** Live serves
  `optima-demibold_1-webfont.woff2` under the CSS family `optimademi_bold`; `fc-scan` reads
  *that* as `Optima Demi Bold` — the Adobe Systems 1995 cut, one of the three faces removed
  here in August for `fsType 4`. Same design, properly licensed source. Verified by
  downloading both and comparing metadata and SHA-256, 2026-09-10.

  **`theme.json` — the stack reorders, and that is not cosmetic.** `heading` becomes
  `Optima, "Optima LT Pro", Belleza, sans-serif`, previously `"Optima LT Pro"` first.
  `WP_Font_Face_Resolver::convert_font_face_properties()` overwrites a `fontFace`'s own
  `fontFamily` with the **first name in its preset's stack** — the bug already documented in
  `style.md` §3.7 — so leaving `"Optima LT Pro"` in front would have emitted
  `@font-face{font-family:"Optima LT Pro"}` and, on a design machine with the twelve desktop
  OTFs installed, been shadowed by a locally-installed family of that exact name carrying
  only 400 and 700. The served family has to be named first. `"Optima LT Pro"` stays as the
  second entry, where it still earns its place on those same machines.

  **Heading weights levelled to 600 — this is a visible change on every template.** One
  licensed weight cannot honestly serve four. `h1` and `h2` asked for 700, `h4` and `h5` for
  500; 700 against a lone 600 face means **synthetic emboldening**, which on Optima's
  modulated humanist strokes reads as smeared. All four are now
  `var:custom|font-weight|semi-bold`. `h3` and `core/button` were already 600 and are
  untouched, and **body text does not move — that is Open Sans.** Live has only ever served
  one Optima cut, so uniform DemiBold headings are *faithful to the design being preserved*,
  not a compromise; hierarchy is carried by size, letter-spacing and case, as it is there.
  Option B — declaring the single file across `"fontWeight": "400 700"` — would have reached
  the same pixels with no `theme.json` edits, and was rejected: it hides a licence-shaped
  constraint inside a font descriptor where nobody will find it, and this constraint is
  renewed annually. Chose the version that stays visible.

  `h6` sets no weight and inherits 400. With only a 600 face registered it renders the 600
  outline anyway — harmless, and left alone because the drop-in scoped four elements, but it
  is now the one heading token that does not describe what is drawn.

  **The drop-in scoped four elements; a sweep found ten more overrides that would have
  reintroduced the fault.** `theme.json`'s element styles are only the floor — blocks and
  style variations override them, and a `theme.json`-only fix would have left synthetic bold
  on most of the site's most visible headings. Every heading-family declaration above 600 is
  now 600:

  | File | Was |
  |---|---|
  | `styles/blocks/heading/section-title.json` | `bold` — the live `.lsx-title`, the most repeated device in the design |
  | `styles/blocks/heading/section-title-left.json` | `bold` |
  | `styles/sections/cards/team-archive-card.json` → `elements/heading` | `bold` |
  | `styles/sections/cards/team-member-card.json` → `elements/heading` | `bold` |
  | `patterns/hero-page-banner.php` `core/post-title` | **`black`** (900) |
  | `patterns/template-page-archive.php` `core/query-title` | **`black`** |
  | `patterns/template-page-centered.php` `core/post-title` | **`black`** |
  | `patterns/template-page-right-sidebar.php` `core/post-title` | **`black`** |
  | `patterns/template-page-search.php` `core/query-title` | **`black`** |
  | `patterns/template-page-404.php` `core/heading` ×2 + `core/button` | `black`, `bold`, `bold` |
  | `patterns/template-single-post.php` `core/post-title`, `core/post-author-name` | `bold` |

  The `black` cases were the worst of them: 900 against a 600 outline is the heaviest
  synthetic smear a browser will draw, and five of the six page-level banner titles were
  set that way. `core/button` inherits the heading family from `styles.elements.button`
  (already 600), so the 404's local `bold` override was in scope too.

  **Nothing else moved.** No font-family reference changed anywhere — all 62 go through
  `var:preset|font-family|heading`, which is why the preset was built that way — and no
  body-family weight was touched: Open Sans is a variable face registered `300 700`, so its
  700s are real. `phpcs --standard=WordPress` clean on all seven patterns.

  **The kit's licence notice is served on `wp_head`, and there is no Tracking Code to
  install.** Reading the kit's `StartHere.html` in full corrected an assumption the drop-in
  doc carried: this is a MyFonts **self-hosting** kit, and its instructions are three steps —
  upload the kit, link `MyWebfontsKit.css` from the `<head>` of every page, assign the family
  in CSS. **No tracking script, counter or beacon appears anywhere in it.** Those belong to
  Monotype's *hosted* web-font service, which this is not. So §3 of the drop-in is void,
  nothing is owed on that front, and nothing goes into `sd-enhancements`.

  The real obligation is the `@license` block, and the instruction that it travel in the
  `<head>`. `font_licence_notice()` in `functions.php` prints it on `wp_head` at **priority
  1**, ahead of the stylesheet link, reproducing the kit's text **byte for byte** — verified
  by diffing the emitted markup against `MyWebfontsKit.css`. A second copy sits beside the
  font it covers as `assets/fonts/optima-600-normal.LICENSE.txt`, so the licence cannot be
  separated from the file by a careless copy.

  **`MyWebfontsKit.css` is deliberately neither shipped nor enqueued**, which is the one
  place this departs from the kit's literal instructions and does so on purpose. That
  stylesheet exists to put an `@font-face` *and* the notice on a page together;
  `theme.json` already supplies the `@font-face`. Linking it as well would fetch the same
  32 KB a second time under a second family name — `OptimaProDemiBold`, which nothing in this
  theme references — and its relative `webFonts/OptimaProDemiBold/…` paths do not exist here,
  so it would 404 on top of the duplication. Same obligation discharged, no dead stylesheet
  and no wasted request. The pristine kit stays in `docs/DS Optima DemiBold/`.

  **It stays in the theme, not the plugin.** The notice is owed *because this theme serves
  that font file*; deactivate the theme and both the file and the obligation go with it. That
  is the deactivation test answering "theme", and it is the same reason the face itself lives
  in `assets/fonts/`. `php -l` and `phpcs --standard=WordPress` clean.

  🟡 **Left alone, and worth a separate look:** `patterns/homepage-hero.php` sets
  `core/heading` to **700 on the `accent` family**, where only a Joe Hand 400 face is
  registered — so that heading is synthetically emboldened too. Different typeface,
  different licence, not an Optima question. Flagged, not fixed.

- **The accommodation single carries the breadcrumb strip.** `patterns/breadcrumbs.php`
  is required beneath the banner in `patterns/template-single-accommodation.php`, which
  is where the destination, region, country and tour singles already put it. It was the
  only Tour Operator single without one.

- **`templates/taxonomy-accommodation-type.html` is a real template.** It was Tour
  Operator's stub — a three-up grid of featured images and titles. It now references
  `patterns/template-taxonomy-accommodation-type.php`, ported from
  https://www.southerndestinations.com/search/accommodation/safari+lodges/ measured in
  Chrome at 1440 / 992 / 390 on 2026-09-04: the banner carrying the term name, the
  breadcrumb band, a **25% FacetWP filter rail** and a 75% results column holding a
  result count, a sort control, the horizontal accommodation rows and a pager. This is
  the open decision `patterns/template-archive-accommodation.php` recorded — "routing
  the tiles at the facet search instead is a decision for whoever builds the search
  line" — taken the other way: the archive's tiles keep pointing at `get_term_link()`
  and the term archive becomes the results page.

  **The design is ported; the mechanism is rebuilt.** Live's `/search/accommodation/*`
  is a FacetWP *legacy template* whose stored PHP queries `post_type => 'product'`
  against the `product_cat` taxonomy and sorts on a `search_price` meta key it writes
  per request — live's accommodation search runs on **WooCommerce products**, and there
  is no WooCommerce here. So the page is a Query Loop over `accommodation` with
  `inherit: true`, filtered by FacetWP through `enableFacetWP: true` on `core/query` —
  a registered attribute from FacetWP Blocks (Beta) that puts a `facetwp-template` class
  on the inner `core/post-template`. Verified on local against a two-post fixture: the
  class lands, and the loop returns only the term's accommodation and none of the six
  tours.

  **No `core/query-pagination`, and it is not a choice.** `add_facetwp_query_args()`
  reads FacetWP's own `fwp_paged` argument and writes `page`/`paged` straight onto
  `$GLOBALS['wp_the_query']`, so core's `/page/N/` links are overridden after they are
  built. The count, sort and pager are `facetwp/facet` blocks — a **Pager** facet in
  *Result counts* mode, a **Sort** facet, and a Pager in *Page numbers* mode — rather
  than the `[facetwp …]` shortcode extras, so every control on the page is a block the
  Site Editor can see. ⚠️ Those three facets (`results_count`, `sort`, `pager`) must be
  created in FacetWP → Settings; until they are, `facetwp_display()` returns `''` for an
  unknown name and each block renders its wrapper and nothing else. That is site data in
  `wp_options`, not theme code. *(LS-2033)*

  **`core/query-no-results` is live here and nowhere else.** LS-2529 records FacetWP
  Blocks Beta returning `''` unconditionally from `render_block_core/query-no-results`,
  which kills the fallback on this theme's non-inheriting loops. Its stated reason — that
  core prints the content inside `core/post-template` when the query inherits — is true,
  and this loop inherits, so the message renders.

  ⚠️ **`accommodation_type` is deliberately not one of the facets.** Live can offer it
  because its search page is not actually scoped: `total_rows` and
  `total_rows_unfiltered` both return 200 on `/safari+lodges/` *and* on bare
  `/accommodation/`, and the facet lists 16 types including "Safari Lodges (162)" — the
  path segment narrows nothing. A term archive narrows the query before FacetWP sees it,
  so the facet's only possible choice is the term already applied. Offering the type
  facet alongside results means this cannot be a taxonomy template. *(LS-2033)*

- **FacetWP facets render as dropdowns.** `assets/styles/facetwp-facets.css` and
  `assets/js/search-filters.js`, wired to the `facetwp/facet` block by the new
  `inc/facetwp.php` — the stylesheet through `wp_enqueue_block_style()` and the script
  through `render_block_facetwp/facet`, since there is no block-script counterpart, so
  neither loads on a page with no facet. Adapted from `kwv-theme-2026`'s
  `assets/js/shop-filters.js`, with three differences: no MutationObserver is needed
  (FacetWP refreshes only the *inside* of `.facetwp-facet`, so the block wrapper's
  attributes survive), the panel opens **over** the results instead of folding the rail
  open, and outside-click and Escape close it. FacetWP's own `fselect` facet type was
  not used: facet *type* is site configuration, and `travel_style`, `price` and the
  `destination_to_*` connections are shared with the tours search, so retyping them
  would change a page this line does not cover.

  Live diverges twice and is not followed: its facets are Bootstrap accordions with the
  first force-opened by `lsx-search.js`, and below 768px the rail becomes an off-canvas
  drawer with Apply/Close controls and `auto_refresh` switched off. That second
  interaction model is a bespoke mobile design and out of scope; the dropdowns stack.

  FacetWP's PNG checkbox and close icons are replaced with masked glyphs in theme
  colours — the checked box is a single `fill-rule="evenodd"` mask that knocks the tick
  *out* of a brand-500 tile, degrading to a solid tile if masks are unsupported.
  ⚠️ Nothing in the sheet may set `display` on `.facet-wrap`: the blocks plugin adds
  `.facetwp-hidden` there for a facet with no remaining choices, and that is a single
  class at (0,1,0) which an author `display` would outrank, resurfacing an empty control.

- **The accommodation archive runs a breadcrumb band under its banner.**
  `patterns/template-archive-accommodation.php` requires `patterns/breadcrumbs.php`
  directly beneath the banner cover, the same placement the tour single and the three
  destination templates use. The file's header had the bar down as `sd-enhancements`
  work; that reasoning is corrected here for the same reason it was corrected on those
  templates — filtering what Yoast puts in the trail is plugin work, placing the band is
  design. `patterns/template-archive-destination.php` still carries the old note and is
  owed the same fix. *(LS-2033)*

- **The whole specials panel is a link, not just its heading.**
  `patterns/template-archive-accommodation.php` wraps the Accommodation Specials cover in
  a flow-layout `core/group` carrying `sdLinkTo: "custom"` and `sdLinkUrl`, which is what
  live does (`<a>` around the entire plate). `SD\Enhancements\GroupLink` finds the
  heading's existing link to the same URL, marks it `sd-link-echo` and leaves the overlay
  presentational — verified on local: one overlay, one echo, so no second tab stop. No
  `sdLinkLabel`: with none set the module falls back to the first heading inside the
  group, which is both the right name and already translated. The wrapper is deliberately
  **flow** rather than the constrained layout the editor saved — constrained picks up
  `has-global-padding` and inset the cover by `spacing|20` a side, leaving this panel
  visibly narrower than the guarantee panel beside it.

- **`patterns/card-media-overlay-term.php` takes one parameter: the crop.**
  `$sd_card_aspect_ratio`, read once with a `'1'` default, so an includer can ask for a
  different ratio immediately before the `require` and the standalone pattern still
  registers square. Verified on local: the accommodation archive resolves to `16/9`, the
  tour and destination archives and the pattern's own registration all resolve to `1`.

- **The brands shelf logos zoom on hover.** `patterns/homepage-brands.php` carries
  `is-style-image-hover-zoom` on `core/term-template`, and
  `styles/blocks/media/image-hover-zoom.json` declares `core/term-template` to match. The
  class does **not** work on `core/post-featured-image` inside a terms query, which is
  where an author would put it: Tour Operator builds that `<figure>` with
  `get_block_wrapper_attributes()` from a `render_block` filter, so it comes back as
  `<figure class="columns-5 wp-block-term-template">` with neither
  `wp-block-post-featured-image` nor any `is-style-*` on it, and the generated
  `.wp-block-post-featured-image.is-style-image-hover-zoom--N` selector matches nothing.
  Measured on dev 2026-09-04: the per-image class emitted twenty-two copies of the CSS and
  changed no pixel. On the term template it lands on that figure and emits one copy. The
  same leak `assets/styles/core-post-featured-image.css` and
  `SD\Enhancements\Queries::FEATURED_TERMS_CLASS` are written around.

- **The breadcrumb band now runs on the tour single too.** `patterns/template-single-tour.php`
  requires `patterns/breadcrumbs.php` directly beneath the banner cover, outside it — the same
  placement as the three destination templates. This corrects a call recorded the other way:
  the file's own header had the bar down as `sd-enhancements` work on the grounds that
  breadcrumb output is a filter over a third-party plugin's trail. That still holds for the
  trail's *contents*, but placing the block and choosing the band's ground is design, so the
  block is a theme block — the same correction `template-single-destination.php` made on
  2026-09-03. `patterns/template-archive-destination.php` still carries the old reasoning
  verbatim and is owed the same fix. *(LS-2033)*

- **The destination copy collapses to a Read More, and the gap between its paragraphs is
  tighter.** `patterns/destination-summary.php` — imported from the Site Editor on dev
  2026-09-03 (wp_template 65929), verified byte-for-byte (`d858e95d…`, 500 chars) — wraps
  `core/post-content` in a `core/group` carrying `font-style:italic;font-weight:400`, adds
  `core/read-more`, and drops `post-content`'s own `blockGap` to `spacing|20` against the
  `spacing|60` root default. Because the section is a shared partial, all three destination
  templates carry it identically. This is the block-native answer to the read-more
  truncation `template-single-destination.php` had recorded as `sd-enhancements` work
  (sd-lsx-child/assets/js/custom.js:224-275) — `core/read-more` collapses and expands
  `core/post-content` with no script of ours needed. *(LS-2033)*

  ⚠️ **`is-style-archive-intro` is authored on `post-content` and currently styles
  nothing.** `core/paragraph`'s root selector is bare `p`
  (wp-includes/blocks/paragraph/block.json:80), so the variation compiles to
  `p.is-style-archive-intro` and can only match a paragraph carrying the class itself —
  never an ancestor — and the style is registered `blockTypes: ["core/paragraph"]` only in
  any case. The italic and the 400 weight the page shows come from CSS inheritance off the
  wrapping group's own inline style, exactly as dev saved it. The class is kept because
  that is what dev authored and because it correctly marks the block's role; flagged so it
  is not mistaken for load-bearing if the archive-intro variation is ever touched alone.

  **Verified** on local 2026-09-03: `phpcs --standard=WordPress .` silent across the whole
  theme; all three templates carry the wrap, `is-style-archive-intro` once and
  `core/read-more` once; rendering against a real destination produces one `<main>`, one
  `<h1>` and one `.wp-block-read-more` button on all three.

- **The dev Site Editor state of the destination single is imported, and the breadcrumb
  strip and Tour Operator's Google Map land on all three destination templates.** Three
  changes were authored on dev in the Site Editor (`wp_template` 65929, modified
  2026-09-03 14:44) and are now theme files. Because the sections are shared partials, all
  three templates picked them up by construction. *(LS-2033)*

  **Breadcrumbs** — `patterns/destination-breadcrumbs.php`, required directly beneath the
  banner cover: a full-width `primary-100` band at `spacing|10` top and bottom holding
  `yoast-seo/breadcrumbs` at `alignwide`. Live draws the trail in a 58px `#ece9e3` strip
  under the banner and `primary-100` is the nearest token.

  **This corrects a call recorded the other way.** `template-single-destination.php` had
  the breadcrumb bar down as `sd-enhancements` work, on the grounds that breadcrumb output
  is a filter over a third-party plugin's trail. That still holds for the trail's
  *contents* — what Yoast puts in it, and any `wpseo_breadcrumb_links` filtering — but
  placing the block and choosing the band's ground is design, so the block is a theme
  block. The stale note has been rewritten rather than left to contradict the file.
  `patterns/template-single-tour.php` and `patterns/template-archive-destination.php`
  carry the same old reasoning verbatim and were **left alone** as outside this task;
  they are owed the same correction.

  **The map is Tour Operator's own `lsx-tour-operator/google-map` block variation**, not
  the two-group composition it replaces. The plugin registers a fixed inner shape in
  `src/blocks/google-map/index.js` and its JavaScript keys off it:
  `group.lsx-location-wrapper` → `group "Map Container"` → `cover.lsx-map-preview` +
  `group.hidden "Map Details"` carrying the `lsx/map` binding. `lsx-location-wrapper`
  replaces `lsx-google-map-wrapper`; both resolve through the same branch of
  `Query_Loop::maybe_hide_varitaion()` (class-query-loop.php:213 checks `location`,
  `google_map`, `wetu_map`, `wetu-map` and `google-map` against `lsx_to_has_map()`), so the
  band still removes itself on a destination with no `location` meta. The preview plate is
  a **lazy-load and it is the plugin's**: maps.js binds `.lsx-map-preview a` click →
  `preventDefault()` → `getScript(google_url)` → `initThis()`, so Google Maps is not
  requested until somebody asks for the map and the `href="#"` is wired upstream.
  Tour Operator's "Title" group — separator, a centred "Location" heading, separator — is
  deliberately not reproduced: live's `#destination-map` has no heading.

  **The tours shelf is now tinted**, `is-style-light-page-section` →
  `is-style-tinted-page-section`. Live paints every section below the summary on white
  (custom.css:1802 sets padding on `#gallery` only), so this is a design decision taken in
  the editor and preserved as authored, not a translation. The destination single now
  alternates once. The "live does not alternate here" paragraph in
  `template-single-destination.php` has been corrected.

  **Verified byte-for-byte against dev, not by eye.** Each imported block was normalised
  for newlines and hashed on both sides: breadcrumbs `bb13f7d2…` (594 chars),
  the map block `09435070…` (1,631 chars), the tours section head `16d9405e…` — all three
  match `wp_template` 65929 exactly. Section-level class counts now match dev too
  (tinted 6, light 10, `primary-100` 2). The **one deliberate divergence** is the map
  placeholder image: the editor wrote an absolute `southerndestinations.lightspeedwp.dev`
  URL and the theme uses the root-relative plugin path, which is how Tour Operator's own
  templates write it and the one URL here that is not environment-specific.
  Two editor artefacts were corrected to core's actual save shape so the blocks do not
  read as invalid: `core/cover` does not serialise `dimensions.aspectRatio` as an inline
  style, and the preview paragraph centres via `style.typography.textAlign`, not `align`.

  **Also verified** on local 2026-09-03: `phpcs --standard=WordPress patterns/` silent;
  all eleven patterns register; section order is banner → breadcrumbs → summary → map →
  gallery → (regions | accommodation) → tours → reviews with one `<main>` and one `<h1>`
  each. Seeding `location` meta on Botswana renders the wrapper and the plate on all three
  templates; removing it removes the whole band.

  🔴 **The map plate is a dead click on Tour Operator 2.2, and the fault is upstream.**
  `lsx_to_map()` builds the map and then executes a bare `return;`
  (tour-operator/includes/template-tags/maps.php:233), discarding the markup and ignoring
  `$echo`; the `return $before . $map . $after` below it is unreachable. So "Map Details"
  emits nothing — measured with `location` seeded: `mapwrap=1 preview=1 maplink=1
  mapdetails=0 lsxmap=0`. And maps.js's entire ready handler is gated on `.lsx-map`
  existing (`jQuery(".lsx-map").length > 0 && …`), so with no `.lsx-map` in the DOM no
  click handler is ever bound and "Click here to display the map" does nothing. The markup
  here is the plugin's own and is correct. This needs either a Tour Operator patch or
  `sd-enhancements` answering TO's own `lsx_to_map_override` filter **before launch** — as
  it stands the page offers the reader a control that cannot work. → LS-2033

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

- **The team member single is built.** `templates/single-team.html` was header, the generic
  `single-hero` part and a bare `core/post-content`; it now references
  `patterns/template-single-team.php`, which reproduces the live consultant profile section
  for section — banner, the tinted summary band pairing the bio with the portrait, the
  Trustpilot feedback row, the gallery, and the tour, destination and blog shelves, closing
  on Why Choose. Measured from `/team/camille-rowe/` on 2026-09-02 against
  `custom.css:2113-2230` and `4335-4416`, and verified against the same member on dev
  (post 41452). *(LS-2020, item 10.2)*

  **Every heading is composed from the member's first name.** “Meet Camille”, “Camille’s
  client feedback”, “Camille’s Favourite Tours”. Live builds all six through
  `sd_first_name_team()` (`sd-lsx-child/includes/functions.php:424`), which is
  `current( explode( ' ', $name ) )`; here they are `sd/post-field` with
  `format: first-name` plus a `prefix`/`suffix` — the binding source sd-enhancements wrote
  for this template specifically. A binding replaces a block's whole `content`, so the
  standing half cannot be static text beside a bound span; the template owns those strings
  and their translation, and the authored text inside each heading is the fallback.

  The apostrophes are typographic (’) and that is load-bearing, not house style: a straight
  `'` inside an `esc_attr_e()` in a block-comment attribute is escaped to `&#039;`, and the
  block parser reads that comment as JSON without decoding entities, so it would render
  literally. Verified by parsing the registered pattern back out — all six round-trip.

  **The three shelves are Tour Operator connection queries**, keyed
  `lsx-{tour,destination,post}-related-team-query` on the `core/post-template` with the
  matching `…-wrapper` on the section group. `to-team` 2.2.0 registers a query variation
  for each (`build/blocks/*-related-team`), so these are the plugin's own keys; they
  resolve through `tour_to_team`, `destination_to_team` and `post_to_team` — 4, 6 and 9 on
  Camille, which is live's three carousels exactly. The tiles are the existing
  `card-tour-compact`, `card-media-overlay` and `card-post-grid`. The overlay tile on
  destinations is a change from live's panelled card and is Zared's call (2026-09-02): it
  makes a member's destinations read as the same object as the countries on
  `/destinations/`.

  **The feedback row is gated on `lsx-truspilot-id-wrapper`** — the misspelling is the
  stored meta key. Tour Operator finds no matching query, taxonomy or special case, falls
  through to its post-meta branch and reads `truspilot_id`, so a member with no Trustpilot
  tag loses the whole band. That is deliberate: `Trustpilot::context_tag()` returns `''`
  without a tag, and an empty tag is the *company's* review list, which would show three
  unrelated reviews under “{their name}’s client feedback”.

  **The role paragraph carries `lsx-role-wrapper`, and it hides on the taxonomy, not the
  meta.** `maybe_hide_varitaion()` tests `taxonomy_exists()` before falling through to post
  meta, and `role` is both a `to-team` taxonomy and a `to-team` meta key — so the paragraph
  is removed when the member carries no role *term* while the value printed comes from the
  *meta*. The two agree on all fifteen published members on dev; they are still two fields.
  Recorded in the pattern rather than worked around.

  **Not carried, and why:** the mobile "Summary" collapse (a phone accordion over a section
  with no second desktop state), Tour Operator's sticky section menu (live sets
  `.single .lsx-to-navigation { display: none !important }`, custom.css:1795), and the
  boxed contact panel `to-team`'s own `single-team.html` puts beside the bio — live renders
  none of it, only the role. The breadcrumb bar is `sd-enhancements` work as it is on every
  other single, and item 10.7 carries the team post type's parent-link case.

  **Verified on local** against a seeded fixture, both directions. Bare member: five of the
  six sections hide themselves, the role paragraph goes, the enquiry button falls back to
  `/contact/`. Populated: all six render, the banner takes `banner_image_id`, the gallery
  binding replaces the three authored placeholders with the seeded images, each shelf
  returns exactly its connected posts, and the enquiry button resolves to `mailto:`.
  `phpcs --standard=WordPress` silent; every token reference checked against `theme.json`.

  > ⚠️ **The map is not here, and it is not a gap in this template.** Live's `#map` is
  > *"Places {name} has visited"* — a Google **cluster** map built from marker data for the
  > member's 60 connected accommodations, behind a click-to-load placeholder. It is not
  > reachable from the theme: Tour Operator's `lsx/map` binding answers for `wetu` and
  > `google` only (`class-bindings.php:940-969`), and its `google` branch calls
  > `lsx_to_map()`, which reads the post's own single `location` point — which a team member
  > does not have. This is the **Team Member Map block**, named separately as items 10.4 and
  > 17.7 and built in the plugin. When it lands it goes between the gallery and the tours
  > shelf and nothing else changes.

  > ⚠️ **The feedback row renders a heading and the company badge but no reviews until the
  > Trustpilot API key is rotated and set.** `sd/trustpilot-reviews` renders nothing on a
  > cold cache, and there is no key — the one committed to `sd-lsx-child` must not be
  > reused; the replacement belongs in `wp-config.php` as `SD_TRUSTPILOT_API_KEY` per
  > environment. Intended cold-start behaviour, recorded so it is not read as a fault.

- **`patterns/card-trustpilot-review.php` — Card — Trustpilot Review.** One cached review as
  live's `.tb-review-box` draws it: the date, the headline, the extract and the reviewer's
  name. Repeated by `sd/trustpilot-reviews`, which takes its subject from the
  `sdTrustpilotIndex` context — so the card is authored once and reads no post.

  **The star row is left out and it is a plugin gap, not a design decision.**
  `sd/trustpilot` exposes `stars_image`, which the theme itself answers through
  `inc/trustpilot.php`; `sd/trustpilot-review` does not — its keys are `stars`, `title`,
  `text`, `author`, `date` and `url`, and `stars` is a bare float. Binding an image to the
  *business* rating would paint the company's score onto an individual review. It needs one
  key on the sd-enhancements source, calling the private `stars_image()` the score source
  already uses; nothing in the card changes when it lands. → LS-2033

  **The headline is not a link.** Live wraps all three in an `<a>` to the same company
  review page the Trustpilot mark beside them already links to — four links to one
  destination in one section — and `core/heading` has no bindable `href` in any case.
  `sd/trustpilot-review`'s `url` key stays available for per-review permalinks.

- **The team landing page is built.** `templates/archive-team.html` was a generic three-up
  Query Loop over the `team` post type; it now references
  `patterns/template-archive-team.php`, which reproduces the live *Meet the Team* page
  section for section — the About Us banner, the company standfirst, and three role
  sections (Management Team, Consultants, Support Team). Measured from `/team/` in the
  browser on 2026-09-02. *(LS-2020, item 10.1)*

  **Three Query Loops, not one.** Live groups the archive by role through Tour Operator's
  `posts_orderby` filter (`to-team/classes/class-to-team-frontend.php:55`), which is gated
  on `$query->is_main_query()`, and the section headings come from the archive partial
  walking the results. Neither half is reachable from a block template — a Query Loop is
  never the main query, and no core block prints a heading when a queried post's term
  changes. Three loops, one per role, is the block equivalent, and it makes each section
  independently editable.

  **The role term IDs are resolved from their slugs at render.** `core/query`'s `taxQuery`
  holds term IDs — core runs the values through `intval()`
  (`wp-includes/blocks.php:2912`), so a slug is silently dropped and the section renders
  the whole roster. Local, dev and live do not share term IDs, so a literal would be wrong
  in two environments out of three. The fallback when a term is missing is **-1, not 0**:
  core's `array_filter( array_map( 'intval', … ) )` strips a `0`, leaving an empty `terms`
  clause that `WP_Tax_Query` drops — which would print every published member under every
  heading. `-1` matches no term, so a renamed role renders an empty section instead.
  Verified on local, which has no `role` terms: three headings, no cards.

  **Ordering is `menu_order`** — Tour Operator's own team ordering
  (`to-team/classes/class-to-team-admin.php:154`), so who comes first in a section is a
  content decision. Live's within-section order does not match dev's migrated `menu_order`
  values on two of the three sections; that is a content fix, not a template one.

  **One `h1`, and it says "About Us".** Live puts two on the page and hides one:
  `.archive-header-wrapper` holds `<h1 class="archive-title">Team</h1>` at
  `display: none`, and the visible title is the banner's "About Us" over the strapline
  "Meet the Team". The hidden copy is a Tour Operator template artefact — the destinations
  archive carries the same one — so it is not reproduced. That is also why the banner
  title is authored rather than `core/query-title`: "About Us" is not a string
  `query-title` can produce. The section separators move from live's `h3` to `h2`, which
  is the correct level under that `h1`.

  **No tinted intro band.** The destinations and tours archives put their standfirst on
  `neutral-200` beside the safari expert panel because live's `.lsx-to-archive-header-tour`
  does. The team archive's description is in `.lsx-to-archive-header` *without* the `-tour`
  suffix — white ground, left-aligned, roman, 15px, no expert panel, no drop cap — so it is
  a plain paragraph and not `is-style-archive-intro`.

  The page closes on **Why Choose Southern Destinations** and **CTA — Not Sure Where To
  Go**, `require`d from their own patterns. Live's team page carries only the first; the
  pair is how every other archive in this theme ends (2026-08-28), so it ends the way its
  siblings do.

  > ⚠️ **`core/query-no-results` renders nothing anywhere on this install, and that
  > is not this template's doing.** FacetWP Blocks Beta hooks
  > `render_block_core/query-no-results` and returns `''` unconditionally
  > (`facetwp-blocks-beta/includes/class-blocks-integration.php:724`). Its stated reason is
  > that core already prints no-results content inside `core/post-template` when the query
  > inherits from the template — true for `inherit: true`, and **not** true for the
  > non-inheriting loops this page uses. So the three fallback messages here are dead while
  > that plugin is active, and so are the ones in `templates/archive-review.html`. The
  > markup is correct and is kept; the suppression is a third-party defect to raise with
  > FacetWP or work around in `sd-enhancements`. Found 2026-09-02. → LS-2529

- **`patterns/card-team.php` — Card — Team Member.** The consultant tile: a 4/3 portrait
  with a warm-dark band pinned along its foot carrying the name in uppercase and the role
  beneath it. `styles/sections/cards/team-archive-card.json` is the new section style that
  owns the positioning context, the band's ground and the caption type, measured from
  `.post-type-archive-lsx-to-team … .lsx-to-archive-content` — a 70px absolute strip at
  `rgba(26, 18, 5, 0.7)`, which is `neutral-900` at 70%.

  **This is not the Team Member Card style.** `is-style-team-member-card` is the homepage
  row, whose bio panel covers the whole photograph and is revealed on hover.
  `is-style-team-archive-card` is always visible, covers only the foot of the image, and
  has no hover state, because live's team archive has none — checked against every
  `:hover` rule in the rendered stylesheet.

  **Four things live renders and this does not:** the phone, the email, the socials and the
  "More about {name} ›" link. All four are in live's markup and all four are
  `display: none` on this template. So is the `Role:` label
  (`.lsx-to-meta-data-key { display: none }`), which is why the binding carries no
  `prefix` — the band shows the bare value, "Queen Bee". The member's own page carries
  the rest, and both the portrait and the name link to it.

  **The band sizes itself** rather than being pinned at live's 70px, so a name that wraps
  to two lines grows the band instead of being clipped as it is on live. The crop is 4/3
  against live's 9/7 (360 × 280) — the nearest standard ratio, and the one the review
  archive already uses, so the archives stay one system.

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

- 🎨 **The brand ramp turns red below 600 instead of going back to brown.** LS-2018.
  `theme.json`: `brand-700` `#624411` → **`#9E451E`**, `brand-800` `#32240B` →
  **`#531608`**, `brand-900` `#040301` → **`#0F0000`**.

  `brand-600` was changed to `#BC5B18` earlier in the build — a redder, lighter step than
  the `#9B6111` it replaced. The three below it were still on the old trajectory, and in
  OKLCH the ramp's hue was **reversing**: 100→600 rotates cleanly 84.6° → 81.4° → 78.2° →
  71.8° → 66.5° → 49.2°, then 700–900 turned back up to 76.1° → 79.5° → 90.9° while chroma
  collapsed from 0.145 to 0.012. A hue heading back toward yellow with no chroma left is the
  definition of brown, which is what the dark end read as.

  The new values continue the rotation toward red and hold near pure red's hue rather than
  overshooting into magenta — 42° → 34° → 29° (sRGB red is 29.2° in OKLCH) — at 85% of the
  in-gamut chroma for each lightness. Lightness is respaced to the theme's **own hover
  step**: `brand-500` → `brand-600` is 8.3 OKLCH points, and `brand-600` → `brand-700` is now
  8.0, where it was 17.0. That matters because `brand-700` is the search button's hover
  against a `brand-600` rest, and a 17-point drop read as a different button rather than the
  same one hovered.

  **Contrast checked, not assumed.** `brand-700` is 6.32:1 on `base` and 5.81:1 on
  `neutral-200` (AA at both), against 8.92:1 before — it is used as link-hover text on
  `styles/sections/cards/blog-card.json` and as the facet toggle's hover in the rail, and
  both still pass. `brand-800` and `brand-900` are referenced nowhere but `theme.json`.

  ⚠️ **Figma is now behind on three values.** DESIGN.md makes the Design System file
  authoritative for `theme.json` variables, and this is a hand-edit against that — as the
  `brand-600` change before it was. The ramp needs pushing back into Figma, and style.md §2.2
  reads `brand-700` as a brown; neither is updated here.

- 🔧 **Tour Operator 2.2 ships FacetWP styling of its own, and it was winning.** LS-2018.
  Found while styling the sort control: `tour-operator/build/style.css` carries
  `.facetwp-facet select`, `.facetwp-icon`, `.facetwp-facet input.facetwp-search`,
  `.facetwp-selections` and `button.facetwp-reset`. Measured on dev 2026-09-10 — the sheet
  loads **after** the theme's `facetwp-facets.css`, so at equal specificity source order
  handed TO the control.

  The sort select was the visible casualty: black text in the body font at 19.2px, a grey
  4px-radius box, `cursor: default`, and a hard-coded `fill='black'` chevron, none of it
  token-derived. (TO's own `font-family` and `font-size` there point at
  `--wp--preset--font-family--primary` and `--wp--preset--font-size--x-small`, which this
  theme does not define, so those two resolved to nothing and the select inherited.) Every
  rule in the theme's sort and search sections is now scoped through `.facetwp-facet`, which
  wins at (0,2,1) on specificity rather than on load order. **`border` is the exception** —
  TO sets it `!important`, and `!important` beats any specificity, so the resting border and
  its hover partner answer with `!important` of their own. Those two are the only
  `!important` declarations in the file. TO's `.facetwp-selections{max-width:27%}` is also
  overridden: sized for a full-width toolbar, it left each chip ~70px in a 25% rail.
  → `.claude/skills/wp-thirdparty-markup-styling`

- 🔍 **Second pass on the facet rail: the fold no longer bounces, and the search and sort are
  styled.** LS-2018. `assets/styles/facetwp-facets.css`,
  `styles/blocks/heading/script-accent.json`, and the rail markup in both taxonomy patterns.

  **The fold bounced the heading, and the grid row-collapse was why.** Sampling
  `getBoundingClientRect()` every frame across a real click on dev at 1440: the heading's own
  box went 32.39 → 42.44 → **44.06** → 37.27 → 32.39px, an **11.9px swing**, with the two
  grid tracks converging (37.20 / 37.75) before snapping back. Chrome cannot interpolate
  `auto`, so for the duration of the transition the heading's track is treated as flexible
  and takes a share of a container height that is itself mid-flight; with the title
  vertically centred in that box it rides up and down. The panel now slides on
  `max-block-size` with the heading in normal flow, outside anything animated — the same
  measurement returns a **0.00px swing** over 32 frames. ⚠️ It only reproduces through a real
  click, not a programmatic `classList.add`; drive it from a click if this is ever revisited.
  The `:not(.facetwp-hidden)` guard the grid version needed is gone with the `display` that
  required it.

  **The chevron is bigger and darker** — `0.7em` → `2rem`, `neutral-400` → `neutral-700`,
  the heading's own colour, and hover moves to `brand-600`. At live's weight it was too faint
  to read as a control. ⚠️ `rem`, not `em`, and the sort control matches it: the two sit in
  different type contexts (`400` here, the inherited body `300` there), so the same `em`
  value drew 33.6px in the rail and 26.9px in the toolbar.

  **The keyword box moved above "Refine by"** and its submit affordance is now a `brand-600`
  button with a `brand-700` hover. Searching re-queries the set where everything under the
  heading narrows it, so the heading now labels only what it actually governs. FacetWP's
  `<i class="facetwp-icon">` is drawn as a square the height of the field, with its three
  glyph states (magnifier, `.f-reset` cross, `.f-loading` spinner) all redrawn as masks —
  leaving any one unmasked would show the plugin's grey PNG on a brand ground. The button's
  width and the input's trailing clearance both come from one
  `--sd-search-button-size` calc off the field's own metrics: `spacing|50` was the input's
  `padding-right` and it was the wrong tool, because the spacing scale is a viewport clamp
  (33px → 50px) while the button tracks the field's height, so at 390px the text ran under
  it. Verified square with clearance at both 1706px and 390px. ⚠️ FacetWP renders an `<i>`
  with a click handler, not a `<button>`, so it is not focusable — acceptable only because
  the facet filters on Enter in the field itself.

- 🏷️ **`is-style-script-accent` was inert on the results-page `h1`.** LS-2018.
  `styles/blocks/heading/script-accent.json` gains `core/query-title`.

  The class was on the block and doing nothing: the accommodation-type and -brand templates
  render their title from the queried term, and core only emits a variation's numbered class
  for the block types it is **registered** against. The tell is in the delivered HTML — every
  working script-accent heading carries `is-style-script-accent--49`, `--50` and so on beside
  the base class, and this one carried the base class alone. The variation's own description
  already spelled out the trap for `core/post-title`; `core/query-title` is the same trap, one
  block later.

- 🔍 **The facet filters fold in flow instead of opening over the results, and the rail is
  restyled off live.** LS-2018 (line 8, Lodge / Brand). Four files:
  `assets/styles/facetwp-facets.css`, `assets/js/search-filters.js` and the rail markup in
  `patterns/template-taxonomy-accommodation-type.php` and
  `patterns/template-taxonomy-accommodation-brand.php`.

  **The dropdown is gone.** Opening a facet used to reveal an absolutely-positioned panel
  over the result cards at `z-index: 30`, with one facet open at a time, an outside-click
  close and an Escape handler. Live's facets are Bootstrap `.collapse` accordions: opening
  one **pushes the facets below it down the rail**, several may be open at once, and there is
  no outside-click or Escape because nothing is ever covered. All three of those followed
  from the overlay, and all three are gone with it. The fold is the grid row-collapse
  `kwv-theme-2026` uses — `grid-template-rows: auto minmax(0, 1fr)` → `minmax(0, 0fr)`, not
  `display: none`, because noUiSlider lands every handle at zero if it initialises in a box
  with no width. The first facet now opens on load, as live's `lsx-search.js` force-opens its
  first `.collapse`.

  **The styling is measured off the search page, not off a term archive.** Live has no
  term-archive results page — `/accommodation-type/lodge/` renders through LSX's generic
  archive, and every real accommodation listing on the site is one FacetWP search template
  with a different query string. Giving the taxonomy its own results page is a change this
  rebuild makes, so the reference for *how the rail looks* is
  `/search/accommodation/africa's+finest/`, measured in Chrome at 1440 on 2026-09-10. The
  full table of measurements and their tokens is at the head of the stylesheet. The visible
  changes: each facet with a heading is now a **`neutral-200` plate** 3px from its
  neighbours (rail `blockGap` `spacing|20` → `spacing|5`), "Refine by" is a plate of the same
  kind, and the heading is live's plain brown title with a chevron ranged right —
  `neutral-400`, hover `accent-500` — instead of the bordered white select box it was
  imitating. Facet headings go `200` → `400`, live's 22px against the 16px they were.

  `#60483B` and `#4C5250` both map to `neutral-700`, and `#ECE9E3` and `#F7F5F2` both to
  `neutral-200`, per style.md §2.2 — so live's four-percent step between the "Refine by"
  plate and the facet plates is not reproduced; the `h2`'s own uppercase separates them
  instead.

  **Two smaller corrections to match live.** The result count beside each choice sits inline
  after the term name (`Botswana (42)`) rather than ranged right, and a checked choice is no
  longer recoloured to `brand-600` semi-bold — live marks it by the tick alone, and the
  second emphasis read as a different kind of state. Choices now take live's 5px sibling
  rhythm rather than per-row padding, which also keeps each row at live's 24px.

  ⚠️ **`display: grid` on `.facet-wrap` is gated on `:not(.facetwp-hidden)`** and must stay
  that way. When a facet runs out of choices, `facetwp-blocks-beta`'s front.js adds
  `.facetwp-hidden` to the block wrapper and the plugin hides it at (0,1,0); an unguarded
  author `display` outranks that and resurfaces the empty facet as a live-looking control
  over nothing. Same trap as kwv-theme-2026's shop-filter sheet.

  ⚠️ **Both templates have Site Editor overrides on dev** (`wp_template` 65946 and 65945), so
  the two *markup* changes — the rail `blockGap` and the heading sizes — do not reach the dev
  front end until those rows are reconciled. The stylesheet and the script are theme files
  and take effect immediately. → `wp-db-override-reconciliation`

- 🌅 **The banner scrim is 0% — every `is-style-hero-banner` photograph now runs at full
  brightness.** `styles/sections/hero-banner.json`: the `color-mix()` alpha on
  `.wp-block-cover__background` goes from `45%` to `0%`. One number, one file, twelve
  banners.

  **Live does this, and it is explicit about it.** `sd-lsx-child/assets/css/custom.css`
  carries `body:not(.home) #lsx-banner .page-banner-wrap .page-banner .page-banner-image:after
  { background-color: transparent; }` — the overlay is cleared on every inner page and kept
  only on the homepage. Measured on `/accommodation/` 2026-09-10.

  The alpha stays inside the `color-mix()` rather than being deleted, so putting a scrim
  back is that one number again — which is also why every banner pattern keeps
  `dimRatio: 100`. Core's dim classes are an `opacity` on the overlay span; 100 makes the
  span fully opaque so the style is the single source of the scrim's alpha. Nothing in the
  patterns changed, and the four Tour Operator singles that were already unscrimmed
  (`dimRatio: 0`, 2026-08-28) render identically either way.

  ⚠️ **`base` type now sits on an undimmed photograph on all twelve.** That trade-off was
  already accepted for the singles in August, so this is consistent rather than new — but
  live does not run white-on-photo on inner pages at all: it puts the title and tagline on
  an opaque `#ece9e3` plate in `#cc7f16` and `#60483b`, which is why it can afford a bright
  image. If a banner title fails contrast, that plate is the fix live already ships. →
  flagged, not adopted.

  `patterns/card-review-quote.php` keeps its own neutral-900 scrim. Its header used to
  justify it by pointing at the banner's — "the same one at the same weight, so the two read
  as one family" — and that pairing no longer holds; the note now says why the card keeps a
  floor where the banner drops one. `patterns/template-archive-destination.php`, the
  canonical banner comment the other six point at, records the change and the live
  measurement.

- 🏨 **The horizontal accommodation row was reworked, and the accommodation-type archive
  caught up with it.** Both reconciled from the Site Editor on dev 2026-09-10
  (`wp_template` 65946, `/accommodation-type/africas-finest/`) rather than authored here.

  **`patterns/card-accommodation-list.php`** — five deltas, and the file is now
  byte-identical to the row in that template: the card ground is `neutral-200` with the meta
  panel on `neutral-100`, so the strip reads as a plate *on* the card rather than the only
  tinted thing in the row (live measures #f6f3f0 with the strip on #f0ebe5 — this pair, the
  right way round); the thumbnail is **30%** and **square**, not 25% at 4/3, which stops a
  portrait lodge photograph being cropped to a letterbox; the meta panel moved *inside* the
  content column as a nested 65/35 split, which is what lets it stretch to the copy's height
  and inset itself from the card edge — a third top-level column could only ever run the full
  height of the row, thumbnail included; the excerpt is 45 words in its own group; and
  `core/read-more` is gone, because the whole title is already a link to the same URL.
  The card's `margin-bottom` went with it — the row gap is the enclosing
  `core/post-template`'s `blockGap`, and a margin here doubled it.

  ⚠️ **The card is shared with `patterns/template-taxonomy-accommodation-brand.php`**, so
  the brand term archive picks the new row up too. That template's own `wp_template` row
  (65945) still carries the *old* card inline and will keep rendering it on dev until the
  override is reset or re-saved.

  **`patterns/template-taxonomy-accommodation-type.php`** — the Best Price Guarantee /
  specials band is now carried above the results, reversing the "not repeated here" note in
  this file's header: live has the pair on this page as well as on the archive, and the type
  page is a child of the archive that opens with it. `core/term-description` came out (a
  second standfirst under the new band pushed the list a long way down, and twenty-four of
  the twenty-six terms had nothing to show there). The results region is
  `is-style-light-page-section`, which owns the ground and the rhythm the hand-set
  `spacing|70`/`spacing|80` padding used to; the loop pages at 12; and the Destinations and
  Specials facet headings carry anchors.

  ⚠️ **The sort facet is `sort_`, with the trailing underscore.** `sort` is reserved —
  FacetWP will not save a facet under it — so a block pointing at `sort` renders nothing at
  all. This file pointed at `sort` since it was written.

  **Two differences from the DB are deliberately not imported.** The banner's `dimRatio` is
  kept at **100**: the editor has it at 0, and `has-background-dim-0` compiles to
  `opacity: 0` on `.wp-block-cover__background`
  (`wp-includes/blocks/cover/style.min.css`), which removes the scrim outright — the
  `!important` background-colour in `styles/sections/hero-banner.json` cannot bring it back,
  and the banner's `base`-coloured title would sit on an unmuted photograph. And the empty
  `core/paragraph` between the "Results" heading and the count facet is dropped as an
  editing artefact. Both → flagged for a ruling, not silently resolved.

- **`assets/fonts/optima-*.woff2` is no longer `.gitignore`d — the licensed face is
  committed.** The rule existed for one reason: no web-licensed file existed, so the pattern
  guarded against someone converting the twelve desktop OTFs. The kit's arrival on
  **2026-09-10** retired that reason. Committing it follows the Joe Hand precedent set on
  2026-09-09 for the same reasons — Monotype's webfont transfer restrictions read much like
  JOEBOB's, this repository is private, access is agency plus client, and the client is the
  licence owner — with the added evidence that leaving a face ignored is exactly what made
  Joe Hand **404 on dev** while every committed face returned 200. There is no
  `.github/workflows/` here and no other written step that places an ignored file on a
  server.

  One thing does **not** carry over from Joe Hand: Monotype's webfont terms explicitly exempt
  Development Websites, where JOEBOB's have no such carve-out. Optima on
  `southerndestinations.lightspeedwp.dev` is unambiguously fine.

  ⚠️ **Two standing conditions**, both recorded in `.gitignore` next to the rule: this depends
  on the repository staying private — if it is ever made public both commercial faces come
  out and history is rewritten first — and if the annual licence lapses the file must be
  **removed**, not merely left in place. The twelve desktop OTFs in
  `docs/myfonts_order_7491875209386/` stay unusable here; converting them is a §4 breach.

- **The accommodation single is imported from the Site Editor, and the safari expert panel
  is part of it.** The `wp_template` override edited on dev (post 65942, modified
  2026-09-09 08:26) is folded back into the theme, with each change landing in the pattern
  that owns it rather than as one flattened template. `patterns/safari-expert.php` is now
  required beneath the property copy in the summary's left column, which is live's
  placement; the Trustpilot score rides inside it because live emits `.trust-pilot-box`
  inside the panel itself, not beside it. The rating box's two inner wrappers become flex
  — `lsx-accommodation-price-facts-wrapper` vertical and left-aligned,
  `lsx-rating-wrapper` wrapping — and the bound `Rating Stars` paragraph goes leading-
  aligned, since the stars are laid out by the group and centring an empty paragraph did
  nothing. Smaller with them: the rating box gains `has-border-color`, the specials badge
  gains `height:auto`, `core/gallery` drops its `sizeSlug`, and the root group is renamed
  "Template: Single Accommodation".

  Composition is deliberately preserved: `require` for the sub-patterns and
  `<!-- wp:pattern -->` only inside the query loops, per the rule in the template's own
  header — a nested pattern reference inside a pattern is dropped on front-end render.
  The editor's copy had every sub-pattern expanded inline, so the import compared the two
  *semantically* (parsing block-attribute JSON) rather than textually; that is what
  separated eleven real edits from the editor's re-serialisation — attribute reordering,
  `\u002d\u002d` escaping, auto-generated heading anchors and the WP 6.9
  `align` → `style.typography.textAlign` migration. `patterns/safari-expert.php` and
  `patterns/trustpilot-score.php` needed no change at all.

  **The breadcrumb strip is kept.** The editor's copy had dropped it; the theme keeps
  `patterns/breadcrumbs.php` on this template, as every other Tour Operator single does.

- **The unit card's geometry follows the editor.** `patterns/accommodation-unit.php` — the
  photograph column narrows from `33.33%` to `30%`, its crop is `aspectRatio: 1` rather
  than `1/1`, and the Unit Body column's padding drops from `spacing|40` to `spacing|30`.

### Fixed

- 🔗 **Three absolute dev-host URLs in `patterns/header.php` made portable.** LS-2020.
  The Trustpilot wordmark, the five-star tile and the "Get in touch" button pointed at
  `https://southerndestinations.lightspeedwp.dev/…` literally. The two images are **theme
  assets**, so they now resolve through `get_theme_file_uri()`, and the link through
  `home_url()` — the form the other eighteen asset references and eleven links in
  `patterns/` already use. They resolved on dev only because dev is the host they named.

  **Uploads URLs are untouched, and deliberately so.** This file's "Uploads URLs are the
  exception" rule stands: they are written literally because go-live runs a find-and-replace
  over the dev host, and the attachment IDs beside them tie the markup to dev regardless.
  The forty-six `wp-content/uploads/…` URLs across twelve other patterns are that convention
  working as intended, not a defect — they were counted as one only because the first sweep
  matched on the host and not on what followed it.

  The header badge stays a **static five-star image**, which is what live has and Zared's
  call. It is not bound to `sd/trustpilot`, so it reads five stars whatever the real score
  is — and its `alt` says so too. Wiring it is deferred, not forgotten.

- **The claim that live's accommodation single has no safari expert panel was wrong.**
  `patterns/template-single-accommodation.php` recorded the absence as a design decision,
  written down so it would not read as an oversight. It was measured from one page.
  `/accommodation/chitwa-chitwa-private-game-lodge/` really does render no
  `#safari-expert-box` — but `/accommodation/andbeyond-mnemba-island-lodge/` renders the
  panel in full, directly after the property copy in the left column.

  The panel is **conditional per accommodation**, not absent from the template:
  `sd-lsx-child/content-accommodation.php:35-41` gates it on
  `lsx_to_has_enquiry_contact()`, then branches to `sd_lsx_to_team_member_panel()`
  (`includes/functions.php:96-200`, resolving the `team_to_<post_type>` connection and
  falling back to a random `expert-*` from Tour Operator's team options, transient-cached
  per post) or to `sd_expert_box()` (`:385`) off the `enquiry_contact_*` fields. Chitwa
  Chitwa satisfies neither. The note is corrected in place and carries the reasoning so
  the next reader does not re-derive it from a single page. The theme renders the panel
  unconditionally; data-gating it is an `sd-enhancements` wrapper of the same kind as the
  rating box's three, not a template concern. *(LS-2033)*

  The equivalent claim on `patterns/template-archive-accommodation.php` was re-measured
  and **holds** — the accommodation *archive* renders no expert panel and does run
  `#accommodation-cta-header`. Left as written.

### Removed

- **The accommodation units read-more collapse, before it ever shipped.** The unit
  description renders whole. `patterns/accommodation-unit.php` loses its
  `core/read-more`, and the module written to make that block work at all goes with it:
  `inc/accommodation-units.php`, `assets/js/accommodation-units-read-more.js` and the
  `require_once` in `functions.php`. The Unreleased entry that introduced them is removed
  rather than contradicted, since none of it was released.

  Worth keeping for anyone tempted to reintroduce the block: Tour Operator wires two
  read-more collapses and neither reaches a unit — `set_read_more()` needs a
  `.wp-block-post-content` in the parent group and `set_read_more_itinerary()` is scoped
  to `.lsx-itinerary-wrapper` — while TO binds a `preventDefault()` handler to *every*
  read-more on a Tour Operator single. A bare `core/read-more` here is worse than none.
  `assets/styles/core-read-more.css` and the read-more rules in
  `assets/styles/core-group.css` are card-scoped and untouched.

- **Optima: the `heading` stack names the licensed family; the web licence is DemiBold
  alone.** `theme.json`'s `heading` preset becomes
  `"Optima LT Pro", Optima, Belleza, sans-serif`. SD confirmed the shape of this by email
  on **2026-09-07**: the 12-style *Optima LT Pro* order (#7491875209386, 7 Sep 2026) is a
  **Desktop licence for one user, bought for Canva and other client design work** — a
  deliberate purchase, correct for its purpose — while the **website** gets a separate
  **Web licence for *Optima DemiBold* only, annually renewable**, still in progress.

  The desktop order cannot be repurposed for the theme. Its EULA (`2275`, Monotype *Font
  Software For Desktop* v250903) blocks web use three times over: §2 grants only
  distribution of materials that **do not contain the Font Software embedded**; §4 bars
  Derivative Works, which §9 defines to include "binary data in any format into which Font
  Software may be converted" — i.e. OTF → WOFF2; and §4 bars "install the Font Software on
  **any server**". All 12 faces are `fsType 4`. It does retire the old "Bold only" gap for
  **desktop** work: SD now owns 400/500/600/700/750/950 plus italics in Figma, Canva and
  print.

  Nothing about the rendered site changes for visitors. What changes is that a machine
  holding the family SD paid for renders **that** cut rather than Apple's system Optima.
  Weight behaviour is identical either way: Linotype splits LT Pro across four CSS families,
  leaving `Optima LT Pro` with only 400 and 700 (Medium 500 and Black 750 under `Optima LT
  Pro Medium`, DemiBold 600 under `Optima LT Pro DemiBold`, ExtraBlack 950 under `Optima LT
  Pro XBlack`), so `h3` at 600 and `h4`/`h5` at 500 resolve exactly as before. Adding the
  sub-families to the stack would not help — CSS takes the first family with any matching
  face, then the nearest weight inside it.

  🔴 **Two obligations are new and neither is a code change.** The web licence is
  **annually renewable**, so a lapse drops headings back to Belleza silently — it needs a
  diary entry on SD's side and a line in the handover pack. And because only **DemiBold**
  is licensed, `h1`/`h2` (700) and `h4`/`h5` (500) will have to come down to 600 when the
  kit lands, or browsers will synthesise bold over the 600 outline. Live has always served
  `optimademi_bold` alone, so uniform DemiBold headings are faithful to the design being
  preserved — but it is a visible change and wants review before it ships.

  The drop-in is written and verified against the current theme —
  `.github/tasks/optima-webfont-kit-dropin-2026-09-09.md` in the workspace: what to check
  on arrival, the one filename, the one `theme.json` patch (with the reason `Optima` must
  lead the stack once a `fontFace` exists), the four heading weights, where the mandatory
  Tracking Code belongs, and how to verify. `assets/fonts/optima-*.woff2` stays
  `.gitignore`d. *(LS-2641)*

  > **Superseded 2026-09-10** — the kit arrived and was applied. The stack now leads with
  > `Optima`, the face is registered and committed, the four heading weights came down to
  > 600, and the ignore rule is gone. See the Optima entry under **Added**. The two
  > obligations above survive: the licence is still annually renewable, and the Tracking
  > Code is still owed by SD.

- **Joe Hand is cleared to ship: the pageview cap is accepted, not blocking.** SD reports
  current traffic of roughly **5,000 pageviews a month** against the JOEBOB Webfont EULA's
  §1.3 allowance of **10,000 per copy per month**, and has accepted the cap, licensing
  further copies when traffic approaches it. LS-2642 becomes a monitor rather than a
  blocker. No code changes — the face was already registered on the `accent` preset and the
  official 532-glyph webfont build was already installed; this records the decision in
  `assets/fonts/LICENCES.md`, `.gitignore` and `style.md` §3.4 so the next reader does not
  re-litigate it. §1.4 (one domain — the `.lightspeedwp.dev` dev host is **not** covered)
  and §1.5 (no hotlinking or direct download) are unaffected by traffic and stay live. The
  face is now **committed** rather than pipeline-delivered — see the entry below. *(LS-2642)*

- **Open Sans becomes a variable font: 7 static faces → 2, and a real 500 finally exists.**
  `styles.blocks.core/query-pagination.elements.link` asks for `var:custom|font-weight|medium`
  against the body family, and no 500 face was registered — so CSS matching, which searches
  below the requested weight before above it, rendered those links at **400**. They had been
  reading regular where they were specified medium.

  There was no 500 to add. The bundled faces were the classic Ascender static release,
  **`Version 1.10`** (`uniqueID: 1.10;1ASC;OpenSans-Regular`, 938 glyphs), and **that release
  shipped 300/400/600/700/800 with no Medium at all** — a 500 exists only in the 2021-onward
  variable rebuild, which is a redrawn cut. So the family moved wholesale rather than gaining a
  mismatched face: `assets/fonts/open-sans-{300,400,600}-{normal,italic}.woff2` and
  `open-sans-700-normal.woff2` are replaced by `open-sans-variable-normal.woff2` and
  `open-sans-variable-italic.woff2`, built from Google's current **`Version 3.003`**
  (`google/fonts` → `ofl/opensans`) and registered with `"fontWeight": "300 700"`. WordPress
  emits that descriptor verbatim — verified on local, `font-weight:300 700` in the
  `wp-fonts-local` block — so every weight the body family asks for is now a real interpolated
  instance. The pagination link keeps `medium` and renders a true 500.

  | | Before (7 statics, v1.10) | After (2 variable, v3.003) |
  |---|---|---|
  | Weights available | 300, 400, 600 + italics; 700 normal | **any 300–700**, normal + italic |
  | Typical page (400 + 600 normal) | 89 KB | **61 KB** |
  | With italics (300/400/600 + 400i) | 145 KB | **125 KB** |
  | Whole family on disk | 319 KB | **125 KB** |
  | Requests, two-weight page | 2 | 1 |

  **The unmodified variable font would have been a 3× per-page regression** — 141 KB normal and
  154 KB italic — so two reductions were necessary and both are recorded with a reproducible
  recipe in `assets/fonts/LICENCES.md`: the **`wdth` axis is pinned to 100** (unused by this
  theme, ~32 KB per file on its own), and **`wght` is clamped to 300–700 with Greek and
  Cyrillic dropped** (883 → 532 codepoints; latin, latin-ext and Vietnamese kept). 300–700 is
  exactly the range the body family is asked for — the theme's 200 and 900 references are on
  the `accent` and `heading` families.

  The script drop was measured, not assumed: on dev, **0 of 1,431** content rows contain
  Cyrillic, and the only 3 Greek-range hits are a single mojibake `ϋ` (U+03CB) in three 2014
  blog posts. Uncovered characters fall back per-glyph to the system font. A full-parity
  rebuild costs +47 KB per file and is one flag change.

  ⚠️ **The licence changed with the font, and it is a real change rather than a corrected
  error.** Open Sans was **Apache 2.0** under Ascender; Google relicensed the 2021 rebuild to
  **SIL OFL 1.1** (in-font name ID 13). Both are permissive and bundleable, but OFL adds the
  reserved-font-name rule — do not rename the internal family name if these are ever re-subset.
  `LICENCES.md`, `style.md` §3.4/§3.7 and `DESIGN.md` all updated. *(LS-2019)*

- **Joe Hand is committed; the "build/deploy pipeline" that was supposed to deliver it never
  existed.** Measured on dev, 2026-09-09: `assets/fonts/joe-hand-400-normal.woff2` returned
  **404** while `open-sans-400-normal`, `belleza-400-normal` and `la-belle-aurore-400-normal`
  all returned **200**. The `@font-face` rule was emitted correctly in dev's `wp-fonts-local`
  block the whole time, so nothing was wrong with the theme — the file simply was not there.
  The three that worked arrived with the repo; the ignored one had nothing to bring it, because
  there is no `.github/workflows/` in this repository and no other written step that places an
  ignored file on a server. **Production would have failed identically at launch on
  2026-09-30.**

  `assets/fonts/joe-hand-*.woff2` is removed from `.gitignore` and the face is committed. The
  ignore rule was a redistribution reading of JOEBOB §1.5 (no direct download) and §1.6 (no
  transfer) — but those clauses are about not offering the font for public download and not
  transferring the licence, and a private repository whose access is agency plus client, for a
  client who *is* the licence owner, is neither. §1.4's one-domain reading does leave
  `southerndestinations.lightspeedwp.dev` outside strict scope; that is **accepted and
  recorded** rather than resolved, dev being a private review host with no public audience. If
  it ever needs resolving, JOEBOB adds the host in writing.

  ⚠️ **This depends on the repository staying private.** Public means removing the face and
  rewriting history first — and history is already not clean; see Security.

  **Optima does not inherit the fix.** `assets/fonts/optima-*.woff2` stays ignored because no
  web-licensed file exists yet, and there the pattern guards against a stray conversion of the
  twelve desktop-licensed OTFs sitting in the workspace. Revisit when the kit arrives.
  *(LS-2642)*

- **The accommodation units band is imported from the Site Editor.** Authored on dev
  (`wp_template` 65942) on 2026-09-04 and brought into
  `patterns/template-single-accommodation.php` and `patterns/accommodation-unit.php`: the
  band's `contentSize` goes to **1100px** with the list and each card `alignwide`; the
  copy column takes `verticalAlignment: center` and holds a vertical flex group, so a
  short unit's title and copy centre against the square photograph instead of sitting at
  the top of a tall row; the unit name is centred while the description keeps
  `justifyContent: left`, so the copy stays leading-aligned as live is above 767px.

  Two things in the DB version were **not** imported. The `id="h-unit-name"` anchor on
  the unit heading: the card is repeated once per unit by `render_units_block()`, so the
  id would be emitted two, three or five times on one page — duplicate ids break in-page
  links and are a validity failure. And the editor's serialisation noise — attribute
  reordering, `align` migrating into `style.typography.textAlign`, `queryId`,
  `excludeCurrent`, dev-host URLs, detached-pattern `patternName` metadata — which the
  authored files express more portably.

- **The accommodation single's summary copy, guarantee panel, rooms band and tours
  heading all match the pages they were meant to match.** Four corrections to
  `patterns/template-single-accommodation.php`, none of them changing the section order.

  **The copy block is now the sibling singles' composition.** It was a bare
  `core/post-content`; it is now the italic wrapper carrying
  `is-style-archive-intro` at `blockGap: spacing|20` with a `core/read-more` under it —
  byte-for-byte what `patterns/destination-summary.php` and
  `patterns/template-single-tour.php` carry, so all three Tour Operator singles open
  their copy identically. This supersedes the note routing live's `.more-text`
  truncation (custom.js:224-275) to the block plugin on this template, on the same
  grounds it was superseded on the other two: `core/read-more` collapses
  `core/post-content` to its first block and expands it in place, so it is core's
  behaviour and neither plugin work nor a script.

  **The Best Price Guarantee panel is `patterns/template-archive-accommodation.php`'s,
  byte-for-byte.** The archive re-authored it on dev 2026-09-04 — `is-style-script-accent`
  at font-size 700, `minHeight: 300`, `align: center`, block padding tightened to
  `spacing|30` top and bottom, and the paragraph dropping its explicit 200 for the body
  size — and this copy was left on the earlier composition, so the same panel read as two
  different objects depending on which page you reached it from. ⚠️ The two copies must
  stay in step; with only two, a `require` is impossible in either direction, because
  both files are whole templates rather than sections.

  **The rooms band is stacked horizontal rows, which is what live renders.** Measured
  from `.sd-rooms-wrapper` on 2026-09-04: each unit sits in a `col-md-12`, so one to a
  row with 30px between them (custom.css:1822), and `.rooms-contents` is
  `display: flex; flex-flow: row nowrap` at `max-width: 945px` centred, the photograph
  taking the leading third (`tour-operator/assets/css/style.css:1377`) and the name and
  copy beside it. The band was a three-across grid on the reading of live's `data-slick`
  `slidesToShow: 3`; those options are on the container, the
  `.lsx-to-slider .rooms-contents` rule that would turn the card vertical never takes
  effect on the page, and the stacked row is what live actually draws. So the "Units
  List" group becomes a `constrained` stack at `blockGap: spacing|30` and
  `patterns/accommodation-unit.php` becomes a `core/columns` row inside its bound group —
  a 33.33% photograph column at `aspectRatio: 1/1` and `scale: cover`, and a body column
  at `spacing|40` padding with leading-aligned copy. The outer block stays a
  `core/group` because `render_units_block()` allow-lists exactly that
  (`class-bindings.php:517`); `core/columns` gets the mobile stack from core at 782px
  with no media query of ours. Live's literal 945px cap is not carried — the theme's own
  900px `contentSize` is near enough to be invisible and keeps the band on a measure the
  theme uses elsewhere, the same call the archive intro's 1130px got.

  Verified on local 2026-09-04 against two seeded units on Xigera Safari Lodge: the
  binding repeats the group once per unit, both titles and both descriptions substitute,
  and each card renders as a two-column row. The seeded meta was removed afterwards.

  `assets/styles/core-image.css` gains the one rule the blocks cannot express —
  `.wp-block-image.unit-image` and its `img` filling the stretched column, which is
  live's `.rooms-thumbnail a { min-height: 100% }`. A height set by a *sibling* column is
  not a block attribute, and `!important` is needed because the block library's
  `.wp-block-image img{height:auto;width:auto}` sits at the same (0,2,0). `aspectRatio`
  stays on the block: it is live's square crop and it is what the editor shows.

  **The tours shelf heading names the property.** `Tours Featuring {Accommodation}`,
  through `sd/post-field`'s `title` field and a `prefix` — the same composition the team
  single and both sibling singles use for every shelf heading. Live composes the
  accommodation *type* term instead (`layout.php:126-137`, usually "Tours Featuring
  Lodge") and no source reaches that: a binding replaces a block's whole `content`, and
  the composed half needs the current post's first term in a taxonomy, which
  `sd/post-field` does not answer for and `sd/term-meta` cannot, needing a queried term.
  The property's name is more use to a reader than its category, and live's un-typed
  fallback string stays as the authored content for when the binding returns null.
  Verified on local: renders "Tours Featuring Xigera Safari Lodge".

- **The media overlay card's label is `medium`.**
  `styles/sections/cards/media-overlay-card.json` sets
  `elements.heading.typography.fontWeight` to `var:custom|font-weight|medium`, down from
  `bold`, and both cards stop overriding it — `patterns/card-media-overlay-term.php` drops
  its `semi-bold`. One weight for the post tile and the term tile, set in one place. This
  also retires that file's `var(--wp--custom--font-weight--…)` escaping; the dynamic-block
  rule it worked around is unchanged and still documented on `patterns/safari-expert.php`.

- **The accommodation archive's type grid is two columns with a 16/9 crop, and its intro
  band runs at the theme's wide measure.** Imported from the Site Editor on dev
  2026-09-04 (wp_template 65931). Live runs two columns and eleven type tiles at three-up
  left a ragged last row; a square tile at half the wide measure is a very tall
  photograph, so this grid alone asks the card for `16/9`. The intro band drops
  `contentSize: 1130px` for `alignwide` on the row, and its columns are top-aligned with
  `minHeight: 300` on each panel instead of stretched, so neither panel's height is
  decided by the other's copy length. `perPage` is raised from 12 to 33 — a ceiling above
  the *unfiltered* term count, so a thirteenth featured term is a tick on a term and
  nothing here. Both panel headings become `is-style-script-accent` at font-size 700, and
  the guarantee paragraph takes the body size.

  ⚠️ Two markers tried on that query on dev are **not** carried: `parents-only` and
  `custom-order` are Tour Operator query markers whose handler allow-lists `core/query`
  only (`class-query-loop.php:288-317`), so a `core/terms-query` never reaches them. Both
  are inert. Re-measured the same day, `sd-featured-terms-query` alone is doing the job:
  twelve of twenty-six `accommodation-type` terms are featured and the page renders
  eleven — Luxury Trains has a count of 0 and `hideEmpty` drops it, as live drops it. The
  standing ⚠️ about Africa's Finest rendering Tour Operator's grey placeholder is
  **resolved**: term 1799 now has `thumbnail` 51625, and all twelve featured terms have
  one.

- **The accommodation banner's tagline is `medium`.** `semi-bold` on
  `patterns/template-archive-accommodation.php` matched the tours archive; the
  destinations archive already ran `medium` and dev was re-authored to it.

- **The tour summary card fills its column instead of stopping at 497px.**
  `patterns/template-single-tour.php` drops the `contentSize: "497px"` from the Summary
  Card group's constrained layout. The cap reproduced live's
  `#single-tour-summary { max-width: 497px }` (sd-lsx-child custom.css:2308), but the cap
  does not translate: on live it sits on the column itself inside a
  `justify-content: space-between` flex row, so the card is flush to the container's right
  edge with nothing beside it. Here it sat on a group inside a `core/column` — half of an
  `alignwide` 1440px row, so ~696px — which stranded ~200px to its right and broke the
  itinerary rows mid-term at ~436px of text (497 less the 33px marker and its gap). The
  itinerary spine still breaks on the word rather than the term; it simply breaks later.
  The stale cross-references in `patterns/destination-summary.php` and
  `patterns/template-single-accommodation.php`, both of which described the tour's right
  column as "a 497px-capped fast-facts card", are corrected to say the cap is live's and
  not this theme's. *(LS-2033)*

- **`patterns/destination-breadcrumbs.php` is renamed to `patterns/breadcrumbs.php`**
  (slug `sd-theme-2026/destination-breadcrumbs` → `sd-theme-2026/breadcrumbs`, title
  "Destination — Breadcrumbs" → "Breadcrumbs"). The band carries nothing
  destination-specific — it is Yoast's trail on a tinted strip — and the destination-scoped
  name stopped describing the file the moment `template-single-tour.php` required it too.
  All `require __DIR__ . '/destination-breadcrumbs.php'` call sites
  (`template-single-destination.php`, `template-single-country.php`,
  `template-single-region.php`) and the slug reference in `inc/yoast-breadcrumbs.php` are
  updated to match. Entries above dated 2026-09-03 still name the old filename and slug — they
  describe the state as verified that day and are left as written.

- **The Yoast breadcrumb trail renders at font size `200` (Base) instead of inheriting the
  root `300`.** `assets/styles/yoast-breadcrumbs.css`, attached to `yoast-seo/breadcrumbs`
  by the new `inc/yoast-breadcrumbs.php`. Only the size is set — family, weight, colour,
  the separator and the link treatment all continue to inherit the `primary-100` band the
  `sd-theme-2026/destination-breadcrumbs` pattern places them on.

  The rule is authored CSS rather than JSON because Yoast's block does not call
  `get_block_wrapper_attributes()`: it renders a bare `<div class="yoast-breadcrumbs">`
  with no `wp-block-yoast-seo-breadcrumbs` class, so a `theme.json` `styles.blocks` entry
  would compile to a selector that never appears in the output, and a `styles/**` partial
  has no block wrapper to attach a variation to. `enqueue_custom_block_styles()` globs
  `core-*.css` only, so the sheet is registered by its own `inc/` module — the arrangement
  `functions.php` documents and `inc/mega-menu.php` already follows.
  `wp_enqueue_block_style()` keeps it lazy: nothing is printed on pages without a trail,
  and nothing at all while Yoast SEO is inactive, since the block is then unregistered.

- **The Related Reviews band is a full-bleed quote with no carousel nav, on every template
  that carries it.** At Zared's direction 2026-09-03, matching live.
  `patterns/destination-reviews.php`, `patterns/template-single-tour.php` and
  `patterns/template-single-accommodation.php` — the three shelves stay identical.
  - The wrapper group drops `is-style-light-page-section`, whose `spacing|70` block padding
    was what held the card off the bands above and below, and drops its `constrained`
    layout. Under `useRootPaddingAwareAlignments` core adds `has-global-padding` to *any*
    constrained block regardless of its padding
    (`wp-includes/block-supports/layout.php:1112-1118`), which both re-applies the root
    inline padding and gives an `alignfull` child a negative root-padding margin — so a
    flow layout is what actually reaches the viewport edge. The band is now flush top and
    bottom, and the card is the full width of the screen.
  - The query goes `alignwide` → `alignfull` and takes a new `sd-slider-nav-hidden` class;
    `assets/styles/core-group.css` hides Slick's arrows and dot row (and Swiper's
    equivalents) against it. Tour Operator offers no way to ask for this: `build_slider()`
    in `tour-operator/build/custom.js` hardcodes `dots: true` and leaves `arrows` at
    Slick's default on every `.lsx-to-slider .wp-block-post-template`, and its only opt-out
    (`.slider-disabled`) turns the carousel off and stacks every review. `display: none`
    rather than `visibility: hidden`, so the controls are not tab stops; `!important` for
    the same reason the rest of that block needs it — `tour-operator/build/style.css` loads
    after this sheet and reaches (0,4,1).
  - The query also takes `sd-slider-flush`, which takes off everything that was still
    holding the slide off the viewport edge. Measured on dev 2026-09-04, there were four
    separate causes:
    1. The root `spacing|60` block gap landed on the post-template via
       `:root :where(.is-layout-flow) > *`. It escaped the `:first-child` exemption
       because Slick *prepends* its prev arrow to the query (`appendArrows: o.parent()`),
       so the template is no longer the first child — the hidden arrow is. That is why
       the gap showed on top and not on the bottom.
    2. `.is-style-slider-frame .slick-list` pads by `spacing|20` and pulls back with a
       negative margin, so a focus ring on a card in a normal shelf isn't clipped. A
       flush band has nothing to clip.
    3. Tour Operator insets every slide 15px on all four sides
       (`.wp-block-query.lsx-to-slider .slick-slide{padding:15px!important}`,
       `tour-operator/build/style.css`) — (0,3,0) and `!important`, so the override has
       to reach (0,4,0). The first attempt at this rule was (0,2,0) and silently lost.
    4. Slick sizes the track to the tallest slide, leaving a strip of page background
       under the shorter reviews; a flex track with `align-items: stretch` equalises
       them, and Slick's inline widths survive it.

    That inset is right for the card shelves — tours, accommodation, regions — so none of
    it is unset globally on `.slick-slide`. The cover's own `spacing|80`/`spacing|40`
    padding stays: it keeps the quote off the screen edge while the image and its scrim
    run the full width. Verified in the browser on dev: cover at left 0 for the full
    viewport width, and 0px between it and the bands above and below.

  The shelf still advances on its own, so hiding the nav does not strand slides 2..n:
  Tour Operator writes a `data-slick` override onto the post-template —
  `{"autoplay":true,"autoplaySpeed":5000,"pauseOnHover":true,"pauseOnFocus":true}` —
  which is merged over the `autoplay: false` default in `build_slider()`. Confirmed on
  dev 2026-09-04. *(LS-2033)*

- **The Review Quote Card's "Read More" is font-size 300 and flips to brand-500.**
  `styles/sections/cards/review-quote-card.json` asked for
  `var:preset|font-size|small`, and there is no `small` slug in this theme — the scale is
  numeric, and "Small" is `300` — so the declaration was orphaned and the size came from
  the pattern's own `fontSize: 200`. Both now say `300`. The hover colour is in
  `assets/styles/core-read-more.css`, scoped to the variation: a `:hover` key under
  `styles.blocks.<block>` is never compiled (core compiles pseudo-selectors for elements
  only), and the variation's `elements.link:hover` would have taken the linked post title
  with it. The now-inert `:hover` key and the `css`-field hover rule (the field strips
  `:hover` outright) are removed rather than left looking load-bearing. *(LS-2033)*

- **The slider chevrons are bigger and thicker, and the dots are lighter, thinner and
  square-cornered.** At Zared's direction 2026-09-03.
  `styles/sections/slider-frame.json` and `assets/styles/core-group.css`. The arrows move
  from `neutral-400` to `primary-500`, hovering to `brand-600` rather than `brand-500`; the
  hit target grows from 44px to 56px; the resting dot lightens from `neutral-500` to
  `neutral-400`, thins from 8px to 6px, and its 2px radius squares off to 0.

  The chevron itself is the part that needed a mechanism. Slick and Swiper each draw the
  arrow as a character from a bundled icon font — `←`/`→` and the `prev`/`next` ligature —
  and an icon font carries its weight in the outline with only one face, so there is no
  `font-weight` to turn up. The glyph is switched off (`content: ""`) and ours is drawn in
  its place.

  That first went in as `border-top` + `border-right` on a square rotated 45°, which made
  size and stroke independent custom properties but left the *shape* unsettable: two borders
  meeting at a corner give a mitred point and square-cut ends, and at the weight asked for
  the mitre read as a spur and the ends as broken stubs. Same day, at Zared's direction, it
  was replaced with the SVG treatment from `asnz-block-theme` — a stroked polyline with
  `stroke-linecap`/`stroke-linejoin: round`, the only way to get a rounded apex and rounded
  ends. The artwork is inlined as a data URI (WordPress appends `?ver=` to `style.css` but
  never to the assets a stylesheet points at, so a revised icon under an unchanged filename
  would be served from cache) and applied as a `mask-image` with
  `background-color: currentColor`. ASNZ ships two files and swaps them on hover because its
  arrow colours are literals inside the artwork; masked, ours carries shape only, so one
  asset covers both states and the hover stays a single `color` change on a token. That is
  the same mask idiom `.rating-stars` already uses in this file.
  `--sd-slider-chevron-thickness` is gone with it — the stroke weight is now in the SVG —
  and `--sd-slider-chevron-size` is the glyph's height (`26px`), its width following the
  artwork's 14:24 aspect.

  Two details in that swap are load-bearing rather than tidy: `color: inherit` on the
  pseudo-element resets `slick-theme.css`'s `color: white`, which `currentColor` would
  otherwise paint the mask with, giving white arrows on a white shelf; and `next` mirrors
  with `scaleX(-1)` rather than `rotate(180deg)`, because the glyph box is taller than it is
  wide and rotating would need the box swapped too. Two further consequences worth naming:
  the
  arrow's `left`/`right` offset is now derived from the hit target
  (`calc(var(--sd-slider-nav-size) / -2 - 10px)`) so the centre stays 10px outside the frame
  edge as the target grows, where the old flat `-2rem` would have pulled the chevron in over
  the shelf; and `font-size: 0` is restated on the button, because we override Slick's
  `color: transparent` and its "Previous"/"Next" label would otherwise reappear.

  Every value stays a custom property resolving to a `theme.json` preset — no raw hex — and
  the whole set stays scoped to `.is-style-slider-frame`, per `wp-thirdparty-markup-styling`.
  It supersedes the 2026-08-20 live measurement (arrows `#B4A48C`, dots `#938673`); recorded
  in `style.md` §12.9, with §12.3 item 9 and the §12.7 verification line updated.

  **Verified in a browser 2026-09-03** on the dev homepage's two shelves at 1600px, and
  the look found the arrows still rendering Tour Operator's own chevron — see the Fixed
  entry below, which supplies the specificity this entry's rules were missing. The dot
  treatment landed correctly first time (measured 24x6, square, `neutral-400` `#C3B6A6`),
  because those rules already carried `!important`. *(LS-2033)*

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

- **The Icon Block instances no longer open as broken blocks in the editor.** Ten
  `outermost/icon-block` wrappers across seven patterns were authored with
  `has-icon-color` and no `transform`, which is not what The Icon Block 2.0.0 saves —
  so the editor flagged each as containing unexpected content and offered to recover it.
  Read from the plugin's `save()` (`icon-block/build/index.js`): `has-icon-color` is
  written **only when `iconColorValue` is set**, i.e. for a custom colour — with a
  palette `iconColor` the class is absent and `has-<slug>-color` carries the colour — and
  `transform: rotate(0deg) scaleX(1) scaleY(1)` is written **always**, from the rotate and
  flip controls at their defaults.

  `patterns/card-review-quote.php` was repaired in the Site Editor on dev
  (`wp_template` 65942, 2026-09-04) and the repair is imported verbatim; the same defect
  in `patterns/cta-inspired-by-this-property.php`, `patterns/cta-not-sure-where-to-go.php`,
  `patterns/cta-tell-us-your-trip-ideas.php`, `patterns/header.php`,
  `patterns/homepage-lets-make-it-happen.php` and `patterns/safari-expert.php` is
  corrected the same way. The SVGs are untouched — byte-identical before and after,
  checked by hash.

- **The enquiry modal's Gravity Form is sized against the theme's own controls.**
  `style.css` — a `Gravity Forms in a modal` block scoped to
  `.wp-block-hm-popup .gform-theme`. Gravity Forms' framework sheets size a form for a
  page, not a 590px dialog: measured on dev 2026-09-04, `--gf-form-gap-y: 40px` between
  fields, `--gf-ctrl-size-md: 38px` tall inputs, and 14px text and placeholders in them
  (`--gf-font-size-primary`). Against this theme's other text control — the header search
  input — that is small fields with a large gap between them.

  The block re-declares five tokens rather than any rule: `--gf-form-gap-y` to
  `spacing|20`, `--gf-ctrl-size-md` to the search input's own box expressed as
  `font-size|200 x line-height|body + spacing|10 x 2 + border-width|100 x 2`,
  `--gf-font-size-primary` and `--gf-ctrl-btn-font-size-md` to `font-size|200`, and
  `--gf-padding-x` / `--gf-padding-y` to `spacing|20` / `spacing|10`. No widths, no
  heights, no `!important`.

  Two things make that hold, and the file says both. Every one of these tokens is
  declared by Gravity Forms on a single class — `.gform-theme--framework` or
  `.gform-theme--foundation`, (0,1,0) — so `.wp-block-hm-popup .gform-theme` at (0,2,0)
  wins on specificity with no dependency on stylesheet order. And the overrides are of
  the `-md` **size step**, not the resolved token: the form block prints an inline
  `<style>` at `#gform_wrapper_1[data-form-index="0"].gform-theme`, (1,2,0), which no
  class selector can reach, and what it writes there are `var()` references
  (`--gf-ctrl-size: var(--gf-ctrl-size-md)`, and the same for `--gf-ctrl-btn-size` and
  `--gf-ctrl-btn-font-size`). Redefining the step resolves the inline declaration;
  redefining the token loses to it. Because `--gf-ctrl-btn-size-md` is itself
  `var(--gf-ctrl-size-md)`, the submit button and the fields are the same height by
  construction.

  Colour stays out of this block: it arrives through the form block's own
  `inputPrimaryColor` and `buttonPrimaryBackgroundColor` attributes in
  `parts/modal-enquiry.html`, already pointing at `primary-500` and `brand-500`.

  The stray paragraphs and line breaks the form used to render with are **not** papered
  over here — they were Tour Operator running `wpautop()` and `wp_kses()` over the
  rendered template part, and `SD\Enhancements\ModalMarkup` fixes that at the source.
  Without that module no gap or height set here will square the form up. *(LS-2033)*

- **The safari expert card no longer breaks between ~990px and ~1250px.**
  `patterns/safari-expert.php` and `assets/styles/core-group.css`. The card is one
  two-column grid of three siblings — portrait, identity, actions — and the arrangement
  is chosen by a `@container` query on the card's own width (36rem) rather than by Block
  Visibility's viewport breakpoints. The duplicated identity block is gone: one eyebrow
  and one `core/post-title` in the DOM instead of two of each.

  The old build authored the identity block twice and let Block Visibility's screen-size
  control pick a copy at its `large` breakpoint. That switched the wide arrangement on
  inside a card too narrow to hold it: `.sd-expert__detail` was `flex: 1 1 auto`, so
  flexbox sized it from its ~470px max-content, could not fit that beside the 126px
  portrait, and wrapped it to a second flex line — the portrait alone on row one with the
  eyebrow, the name and both actions stacked beneath it. Measured on local: **313px tall
  at the switch against 175px at 1440px.**

  Two things made a viewport breakpoint unfixable. Block Visibility's breakpoints are a
  global plugin setting and `large` is **1200px on local, 992px on dev**, so the band was
  a different width in each environment — the file's own comment asserted "large ≥ 992px"
  as a fact about the plugin when it was a dev measurement. And the pattern is required
  by three templates whose columns are all different widths, so one viewport number could
  never be right for all three. Verified across 13 widths on the tour single plus both
  archives after the change: the switch now lands at 1260px on the single, 1440px on the
  destination archive and ~1700px on the tour archive — the same 576px of card each time
  — and the card never exceeds 183px tall in the wide arrangement. The Call Us pop-out
  still escapes the card unclipped with `container-type` on `.sd-expert`. *(LS-2033)*

- **Every Tour Operator modal is square now, and its close button is the mark on its own.**
  `style.css` — radius off the panel (`.wp-block-hm-popup > *`) and off the close button,
  which loses Tour Operator's translucent-white ground and quarter-radius corner weld for a
  transparent 36px square inset `spacing|10` from the top and right. Its chrome arrives on
  hover and keyboard focus only: a 2px `brand-600` border, `base` at 75% over whatever is
  behind it, and the glyph going `brand-600` from a resting `neutral-800`. The border is
  declared transparent at rest rather than omitted, so the box does not move when it
  appears. Zared, 2026-09-04.

- **The close mark was drawn off-centre and clipped, and it is Tour Operator's `wp_kses`
  that does it.** `Modals::get_modal_allowed_html()` allows the attribute as
  `'viewBox' => true`, and `wp_kses` lowercases every attribute name before looking it up —
  so `viewbox` misses the list and **the attribute is stripped from the rendered SVG**
  (core's own allow-lists spell it `'viewbox'` for exactly this reason). Without a viewBox
  the SVG cannot scale: `width`/`height` resize the viewport while the path stays at its
  authored 32 user units, so the mark draws full-size against the top-left corner and is
  cut off on the other two. Measured on dev 2026-09-04 with `width: 1.25rem`: the 20px box
  centred to the pixel, the 16px ink inside it sitting at gaps of 16/4 left/right and
  16.5/3.5 top/bottom. The theme's `svg` sizing rule is gone and the button is sized around
  the glyph instead — 36px box, 2px border, 32px viewport, 16px mark, measured back at
  10/10 and 10.5/9.5 (the half-pixel is TO's own artwork, whose path is authored 0.5 units
  low). A ⚠️ note in `style.css` says not to put the sizing back. Reported upstream.

- **The card modals were rendering with none of their card styling.** The cause is not in
  this theme and the fix is not either — Tour Operator renders its modal parts on
  `wp_footer`, after core has read both of the stores that hold block CSS, so the whole of
  `is-style-listing-card-compact` and the `blockGap: 0` under the image were computed and
  discarded (→ `SD\Enhancements\ModalStyles`, sd-enhancements-2026). What that had been
  showing instead was the global `h2` and the default block gap, which is the oversized
  title, the loose padding and the gap between the image and the text. The theme's part of
  it is a note in the modal block of `style.css` saying so, because the temptation on
  seeing it is to hard-code smaller sizes onto the modal — which would fix the symptom and
  set the modal and its carousel card drifting.

- **The tour modal now matches the accommodation and destination modals.**
  `parts/modal-tour.html` was alone in using a `3/2` crop where the other two use `16/9`
  (and where its own `card-tour-compact` uses `16/9`), a `neutral-100` ground against their
  `neutral-200`, a zeroed top and bottom padding, and per-block `brand-500` link colours on
  the travel-style and destinations rows. That last one is the case
  `styles/sections/cards/listing-card-compact.json` explicitly warns about — every link in
  the card is one colour, set once on the section style, and a row that overrides it
  re-introduces the problem the style was written to solve. All four differences removed;
  the three card modals are now identical apart from their meta rows.

- **The slider arrows rendered Tour Operator's default chevron, not ours.** Measured on the
  dev homepage 2026-09-03 at 1600px: both shelves drew a 20px white feather-stroke caret in
  a 30px hit target — the vendor's own artwork — while the masked Phosphor caret, the
  56px target and `primary-500` were all being discarded. The dots on the same sliders were
  correct, which is what located the fault.

  The cause is a stale premise recorded in `assets/styles/core-group.css`: that the only
  vendor rules to beat are `slick-theme.css`'s, at `(0,1,1)`. **Tour Operator does not
  enqueue `slick-theme.css` at all** — it inlines its own arrow theming into
  `tour-operator/build/style.css`, which loads *after* our block stylesheet and reaches
  `(0,4,1)`:

  | Selector | Specificity | What it took |
  |---|---|---|
  | `.lsx-to-slider .slick-arrow` | `(0,2,0)` | `color:#fff`, `height:4rem`, `position`, `margin-top` |
  | `.wp-block-query.lsx-to-slider .slick-arrow` (and `::before`) | `(0,3,0)` / `(0,3,1)` | `width`/`height:30px` |
  | `.lsx-to-slider .slick-arrow:before` | `(0,2,1)` | `color:#fff`, `position:absolute`, `top:47%`, `transform` |
  | `.wp-block-query.lsx-to-slider .slick-arrow.slick-prev:before` | `(0,4,1)` | `background: url(<feather chevron>)`, `width`/`height:20px`, `left:3px` |

  Our selectors are `(0,2,0)` and `(0,2,1)`, so every one of those won — the arrow block was
  the one part of this style written without `!important`, on the assumption natural
  specificity would carry it.

  Beating `(0,4,1)` naturally would mean forking the selector per query block
  (`.wp-block-query…` / `.wp-block-terms-query…` at `(0,5,1)`) and would still leave
  `core/group` and `cb/carousel` uncovered, so the declarations the vendor reaches now carry
  `!important` instead — `width`, `height`, `color` and `background-color` on the button;
  `content`, `position`, `inset`, `transform`, the two logical sizes, `color` and
  `background-color` on the pseudo-element. Two of those are not obvious:
  `background-image: none` has to be **stated explicitly**, because Tour Operator uses the
  `background` shorthand — restoring `background-color: currentColor` alone leaves the
  vendor's artwork showing through our mask — and the hover rule needs `!important` too, or
  it loses to our own now-important base `color`.

  `inset: auto` and `transform: none` on the pseudo-element replace the vendor's
  `top: 47%` / `translateY(-50%)` absolute placement, so the caret is centred by the
  button's own flexbox as this style intends. No colour is hardcoded: every value still
  resolves through the custom properties in `styles/sections/slider-frame.json`.

  **Verified** in a browser 2026-09-03: the patched rules injected at their real cascade
  position (immediately after `core-group.css`, still ahead of
  `tour-operator/build/style.css`, so source order could not do the work) on the dev
  homepage. All four arrows on the two shelves measure a 56px target with a 26px caret,
  `background-image: none`, `position: static`, `transform: none`, and both `color` and the
  mask paint at `rgb(85,66,52)` — `primary-500`. Confirmed visually at device scale.
  *(LS-2033)*

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
