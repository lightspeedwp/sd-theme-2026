# Changelog

All notable changes to the Southern Destinations 2026 theme are documented here.
Format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/);
this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

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
- **`assets/styles/sd-call-us.css` + `inc/call-us.php`** — the design for the plugin's new
  `sd/call-us` disclosure, attached to the block so it loads only where a Call Us button
  renders (the `inc/mega-menu.php` arrangement — a non-core block is outside
  `enqueue_custom_block_styles()`' `core-*` scan). Enqueued CSS rather than a JSON style
  because three of the `css` field's documented limits apply at once: `content: ""` is mangled
  and every glyph here is a pseudo-element, `:hover` is stripped, and a non-core block's *base*
  styles have no JSON home at all — `theme.json`'s `styles.blocks` reaches only the block
  wrapper, never the `__toggle` / `__panel` / `__number` descendants that need styling.
- **`patterns/safari-expert.php`** — the "Chat to your safari expert" panel (K-01/K-02),
  replacing `sd_lsx_to_contact()` / `sd_lsx_to_enquiry_contact()`. Portrait, eyebrow, name, the
  Call Us disclosure, an email action and the Trustpilot badge. Live's `<h5>` eyebrow above an
  `<h3>` name is a skipped level in the wrong direction; the eyebrow is a paragraph here (it
  labels the panel, it does not head a section) and the name is an `<h2>`.
- **Flag assets** — `assets/images/flags/{us,uk,za,aus}.svg`, ported unchanged from
  `sd-lsx-child/images/`. Live sets them with `content: url(…)` on a `:before`, which cannot be
  sized; they are background images on a sized box here so the row height is the theme's.
- **`styles/sections/footer-colophon.json`** — the dark bar under the footer photograph,
  live's `footer#colophon`: `primary-600` ground (live's `#41382E`), spacing-20 vertical
  padding (live's 15px), `neutral-400` text brightening to `neutral-300`.
- **The "Follow Us" list's geometry**, in the `css` field of
  `styles/sections/site-footer.json`. Core treats a labelled social link as an icon with a
  caption; live's is an icon beside body copy, so the label's size, colour and margins all
  have to be prised off the icon's font-size.

### Changed

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
