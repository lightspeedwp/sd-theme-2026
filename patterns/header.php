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
 *
 * Local carries 65876 but not 65877, so the desktop row renders from the real
 * menu there and the mobile nav falls back to core's page list — fine, because
 * local is a fixture environment. Prove menu *content* on dev.
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

<!-- wp:accordion {"showIcon":false,"className":"is-style-call-us-dropdown","style":{"typography":{"fontWeight":"var:custom|font-weight|bold"},"spacing":{"padding":{"right":"var:preset|spacing|20","left":"var:preset|spacing|20"}}},"textColor":"brand-500","fontSize":"300"} -->
<div role="group" class="wp-block-accordion is-style-call-us-dropdown has-brand-500-color has-text-color has-300-font-size" style="padding-right:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20);font-weight:var(--wp--custom--font-weight--bold)"><!-- wp:accordion-item -->
<div class="wp-block-accordion-item"><!-- wp:accordion-heading {"level":3,"showIcon":false} -->
<h3 class="wp-block-accordion-heading"><button type="button" class="wp-block-accordion-heading__toggle"><span class="wp-block-accordion-heading__toggle-title">Call Us Today</span></button></h3>
<!-- /wp:accordion-heading -->

<!-- wp:accordion-panel {"style":{"spacing":{"blockGap":"0"}}} -->
<div role="region" class="wp-block-accordion-panel"><!-- wp:template-part {"slug":"dropdown-call-us","theme":"sd-theme-2026","area":"menu"} /--></div>
<!-- /wp:accordion-panel --></div>
<!-- /wp:accordion-item --></div>
<!-- /wp:accordion -->

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