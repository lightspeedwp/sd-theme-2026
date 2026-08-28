# Changelog

All notable changes to the Southern Destinations 2026 theme are documented here.
Format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/);
this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Changed

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
  `sd-mega-list`, matched nothing either. Both blocks carry `sd-mega-list` now: the shared
  list type, the flush rows and the hover are live, and the two columns read as one list with
  the navigation columns beside them. Their hover moves `brand-500` → `brand-600` to match.

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
