<?php
/**
 * Title: Header
 * Slug: sd-theme-2026/header
 * Description: Site header — the logo beside a right-hand cluster carrying the Trustpilot mark, the Call Us numbers and the enquiry action, above the mega-menu navigation and a fold-out search. Sticks on scroll.
 * Categories: header, sd-theme-2026/menu
 * Keywords: header, nav, navigation, logo, mega menu, enquiry, sticky
 * Viewport Width: 1500
 * Block Types: core/template-part/header
 * Post Types: wp_template
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * Ported from dev and local, 2026-08-21, which had diverged from this file.
 *
 * The header used to be two stacked full-width bands — a utility bar above a
 * header row, each with its own section style and its own height token. It is
 * one band now, holding a two-column row: the logo on the left, and on the right
 * the utility cluster stacked above the navigation. That change came out of the
 * Site Editor, and this file is the reconciliation of it.
 *
 * What went with it:
 *
 *   - `is-style-utility-bar` and `is-style-header-row` — replaced by the single
 *     `is-style-header` section style on the outer group.
 *   - `sd-header__utility` and `sd-header__row` — the two bands' hook classes.
 *   - `custom.header.utility-height` and `custom.header.row-height` — the
 *     columns are vertically centred and the bar takes its height from the
 *     enquiry button's own padding.
 *   - `custom.header.logo-width` and `logo-lift` — the logo's width is set on
 *     the block, below, and the overhang is gone with the two-band seam it used
 *     to cross.
 *   - `sd-nav-desktop` and `sd-nav-mobile` — Block Visibility handles the nav
 *     swap now, so neither hook is styled by anything.
 *   - `sd-header` — the group's only rule was a `z-index`, which is in the
 *     `css` field of the `is-style-header` section style now. Every remaining
 *     `sd-*` class below is one a stylesheet still uses.
 */

/*
 * The navigation blocks below reference `wp_navigation` menus by `ref`.
 *
 * These IDs are dev's, and that is deliberate: **dev is deployed wholesale to
 * live**, database included, so the IDs travel with the content they point at.
 * AGENTS.md's rule against hardcoded refs is about themes that are installed
 * into a database they were not built against — that is not this migration.
 *
 *   65876  SD Main Navigation         4 mega menus + Specials
 *   65877  SD Mobile Navigation       the tiered mobile tree
 *   65909  SD Call Us Dropdown        one `ollie/mega-menu`, the numbers panel
 *
 * Local carries 65876 but neither 65877 nor 65909, so the desktop row renders
 * from the real menu there and those two fall back to core's page list — fine,
 * because local is a fixture environment. Prove menu *content* on dev.
 *
 * 65909 could not be given local's matching ID: the two databases' ID spaces
 * have diverged past 65879, and local's 65909 is the Zimbabwe destination. A
 * `ref` and inline inner blocks cannot be combined as a belt-and-braces either
 * — `WP_Block_Type_Navigation::get_inner_blocks()` *overwrites* the authored
 * inner blocks with the referenced post's, then falls back to the page list if
 * that comes back empty, so the inline copy would never render.
 */

/*
 * Sticky is a block setting, not a stylesheet.
 *
 * `core/group` supports `position: sticky` natively, so it travels in the
 * block's own `style.position` and stays editable in the Site Editor. Live
 * sticks the whole header at `top: 0`, so no offset.
 *
 * Live also compresses the stuck header by 22px (`.scrolled #masthead
 * { margin-top: -22px }`). That needs a scroll listener to toggle a class, which
 * is behaviour and does not belong in the theme.
 */

/*
 * `tagName: div`, not `header`.
 *
 * WordPress already wraps a template part whose area is `header` in a `<header>`
 * element, so a `<header>` here too renders one banner landmark inside another.
 * Two banner landmarks is a real defect: a screen-reader user navigating by
 * landmark hits "banner" twice for one header and cannot tell which is which.
 * The outer wrapper is the landmark; this is its content.
 */
?>
<!-- wp:group {"metadata":{"name":"Header","patternName":"sd-theme-2026/header","description":"Site header — the logo beside a right-hand cluster carrying the Trustpilot mark, the Call Us numbers and the enquiry action, above the mega-menu navigation and a fold-out search. Sticks on scroll.","categories":["header","sd-theme-2026/menu"]},"align":"full","className":"is-style-header","style":{"spacing":{"blockGap":"0"},"position":{"type":"sticky","top":"0px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-header"><!-- wp:columns {"verticalAlignment":"center","align":"wide"} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"30%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:30%"><!-- wp:site-logo {"width":300,"className":"sd-header__logo"} /--></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"blockGap":"var:preset|spacing|10"}}} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
<div class="wp-block-group alignwide"><!-- wp:group {"metadata":{"name":"Trustpilot"},"className":"sd-trustpilot","style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"right":"var:preset|spacing|20"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group sd-trustpilot" style="padding-right:var(--wp--preset--spacing--20)"><!-- wp:image {"width":"100px","sizeSlug":"full","linkDestination":"custom","className":"sd-trustpilot__logo"} -->
<figure class="wp-block-image size-full is-resized sd-trustpilot__logo"><a href="https://www.trustpilot.com/review/southerndestinations.com" target="_blank" rel="noreferrer noopener"><img src="https://southerndestinations.lightspeedwp.dev/wp-content/themes/sd-theme-2026/assets/images/trustpilot/trustpilot-logo.svg" alt="Trustpilot" style="width:100px;height:auto"/></a></figure>
<!-- /wp:image -->

<!-- wp:image {"width":"143px","sizeSlug":"full","linkDestination":"custom","className":"sd-trustpilot__stars"} -->
<figure class="wp-block-image size-full is-resized sd-trustpilot__stars"><a href="https://www.trustpilot.com/review/southerndestinations.com" target="_blank" rel="noreferrer noopener"><img src="https://southerndestinations.lightspeedwp.dev/wp-content/themes/sd-theme-2026/assets/images/trustpilot/stars/stars-5.svg" alt="Rated 5 out of 5 on Trustpilot" style="width:143px;height:auto"/></a></figure>
<!-- /wp:image --></div>
<!-- /wp:group -->

<?php
/*
 * Call Us — an Ollie dropdown, not the accordion it was until 2026-08-27.
 *
 * Zared's call, taken from ATI Holidays where the same widget is built this
 * way: it opens on hover, and the pop-out is the plugin's own construction
 * rather than an in-flow accordion panel argued out of the flow with
 * `!important`. Four shipped bugs went with the swap — the block-gap-into-inset
 * displacement, the `hidden="until-found"` white bar, the pre-hydration flash,
 * and a hand-drawn caret that had to be kept in step with `aria-expanded`.
 * styles/blocks/navigation/call-us-navigation.json has the full account.
 *
 * The panel is the `dropdown-call-us` template part, reached through the
 * block's `menuSlug` — the same one file the footer and the safari expert
 * panel read, so the four numbers cannot drift the way live's two copies did.
 *
 * `ariaLabel` is not optional here: `core/navigation` renders a `<nav>`, and
 * this is the header's second one. Without it a screen-reader user gets two
 * unlabelled navigation landmarks in one banner.
 *
 * "Call us", **not** "Contact numbers" — parts/mobile-menu.html already labels
 * its own copy of the numbers (`ref` 65865) that way, and both are in the DOM
 * at once. Where two landmarks share a label WordPress disambiguates them by
 * appending a number, so the pair rendered as "Contact numbers" and "Contact
 * numbers 2" — technically distinguishable, useless to read out. Measured on
 * local, 2026-08-27.
 *
 * `overlayMenu: "never"`, as on the main nav — with the overlay on, core wraps
 * the list in four more elements and the variation's selectors miss.
 *
 * The icon is a sibling of the navigation, as it is on ATI, rather than part
 * of the trigger: `label` is a block attribute and `render.php` escapes it, so
 * no markup can go inside the button. The cost is that the icon is not part of
 * the button's hover or click target — 20px of dead zone beside a live label.
 * Drawing it as a `::before` on the toggle would fix that and lose the
 * editor-visible block; the block was preferred.
 *
 * ⚠️ Phosphor's phone glyph, the same path used in homepage-dream-trip.php,
 * homepage-lets-make-it-happen.php and cta-not-sure-where-to-go.php. Written
 * out rather than echoed from a variable, as core's patterns do; if it changes
 * it changes in all four.
 */
?>
<!-- wp:group {"metadata":{"name":"Call Us"},"style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"right":"var:preset|spacing|20","left":"var:preset|spacing|20"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)"><!-- wp:outermost/icon-block {"iconName":"","iconColor":"brand-500","width":"20px"} -->
<div class="wp-block-outermost-icon-block"><div class="icon-container has-brand-500-color" style="width:20px;transform:rotate(0deg) scaleX(1) scaleY(1)"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 256 256"><path d="M222.37,158.46l-47.11-21.11-.13-.06a16,16,0,0,0-15.17,1.4,8.12,8.12,0,0,0-.75.56L134.87,160c-15.42-7.49-31.34-23.29-38.83-38.51l20.78-24.71c.2-.25.39-.5.57-.77a16,16,0,0,0,1.32-15.06l0-.12L97.54,33.64a16,16,0,0,0-16.62-9.52A56.26,56.26,0,0,0,32,80c0,79.4,64.6,144,144,144a56.26,56.26,0,0,0,55.88-48.92A16,16,0,0,0,222.37,158.46ZM176,208A128.14,128.14,0,0,1,48,80,40.2,40.2,0,0,1,82.87,40a.61.61,0,0,0,0,.12l21,47L83.2,111.86a6.13,6.13,0,0,0-.57.77,16,16,0,0,0-1,15.7c9.06,18.53,27.73,37.06,46.46,46.11a16,16,0,0,0,15.75-1.14,8.44,8.44,0,0,0,.74-.56L168.89,152l47,21.05h0s.08,0,.11,0A40.21,40.21,0,0,1,176,208Z"></path></svg></div></div>
<!-- /wp:outermost/icon-block -->

<!-- wp:navigation {"ref":65909,"overlayMenu":"never","ariaLabel":"<?php esc_attr_e( 'Call us', 'sd-theme-2026' ); ?>","className":"is-style-call-us-navigation","style":{"typography":{"fontWeight":"var:custom|font-weight|bold"},"spacing":{"blockGap":"0"}},"textColor":"brand-500","fontSize":"300"} /--></div>
<!-- /wp:group -->

<!-- wp:buttons {"className":"sd-header__cta","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"stretch"}} -->
<div class="wp-block-buttons sd-header__cta"><!-- wp:button {"className":"is-style-fill","style":{"spacing":{"padding":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|10"}}}} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="https://southerndestinations.lightspeedwp.dev/contact/" style="padding-top:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--10)">Get in touch</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Header Utilities"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right","verticalAlignment":"stretch"}} -->
<div class="wp-block-group"><!-- wp:group {"layout":{"type":"constrained"},"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"medium":true,"small":true}}}}]}} -->
<div class="wp-block-group"><!-- wp:navigation {"ref":65876,"overlayMenu":"never","className":"is-style-main-navigation","style":{"spacing":{"blockGap":"0px"}},"fontSize":"200"} /--></div>
<!-- /wp:group -->

<!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search tours, lodges, destinations…","buttonText":"Search","buttonPosition":"button-only","buttonUseIcon":true,"className":"is-style-header-search"} /-->

<!-- wp:group {"layout":{"type":"constrained"},"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"large":true}}}}]}} -->
<div class="wp-block-group"><!-- wp:navigation {"ref":65877,"overlayMenu":"always","icon":"menu","className":"is-style-mobile-navigation","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","justifyContent":"right"},"ariaLabel":"Main navigation","mobileMenuSlug":"mobile-menu"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->