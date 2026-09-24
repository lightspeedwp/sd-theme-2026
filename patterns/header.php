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
 * sticks the desktop header at `top: 0`, so no offset — and only the desktop
 * header: at phone width live's masthead scrolls away, so the mobile group
 * below carries no `style.position`.
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
<!-- wp:group {"metadata":{"name":"Header - Desktop","patternName":"sd-theme-2026/header","description":"Site header — the logo beside a right-hand cluster carrying the Trustpilot mark, the Call Us numbers and the enquiry action, above the mega-menu navigation and a fold-out search. Sticks on scroll.","categories":["header","sd-theme-2026/menu"]},"align":"full","className":"is-style-header","style":{"spacing":{"blockGap":"0"},"position":{"type":"sticky","top":"0px"}},"layout":{"type":"constrained"},"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"medium":true,"small":true}}}}]}} -->
<div class="wp-block-group alignfull is-style-header"><!-- wp:columns {"verticalAlignment":"center","align":"wide"} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"22%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:22%"><!-- wp:site-logo {"width":300,"align":"left","className":"sd-header__logo"} /--></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"blockGap":"var:preset|spacing|10"}}} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
<div class="wp-block-group alignwide"><!-- wp:group {"metadata":{"name":"Trustpilot"},"className":"sd-trustpilot","style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"right":"var:preset|spacing|20"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group sd-trustpilot" style="padding-right:var(--wp--preset--spacing--20)"><!-- wp:image {"width":"100px","sizeSlug":"full","linkDestination":"custom","className":"sd-trustpilot__logo"} -->
<figure class="wp-block-image size-full is-resized sd-trustpilot__logo"><a href="https://www.trustpilot.com/review/southerndestinations.com" target="_blank" rel="noreferrer noopener"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/trustpilot/trustpilot-logo.svg' ) ); ?>" alt="<?php esc_attr_e( 'Trustpilot', 'sd-theme-2026' ); ?>" style="width:100px;height:auto"/></a></figure>
<!-- /wp:image -->

<!-- wp:image {"width":"143px","sizeSlug":"full","linkDestination":"custom","className":"sd-trustpilot__stars"} -->
<figure class="wp-block-image size-full is-resized sd-trustpilot__stars"><a href="https://www.trustpilot.com/review/southerndestinations.com" target="_blank" rel="noreferrer noopener"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/trustpilot/stars/stars-5.svg' ) ); ?>" alt="<?php esc_attr_e( 'Rated 5 out of 5 on Trustpilot', 'sd-theme-2026' ); ?>" style="width:143px;height:auto"/></a></figure>
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
 * Phosphor's phone glyph in its **fill** weight at 24px, as dev's Site Editor
 * copy had it (captured 2026-09-23). ⚠️ That makes it diverge from the
 * outline weight still used in homepage-dream-trip.php,
 * homepage-lets-make-it-happen.php and cta-not-sure-where-to-go.php — the
 * header change was an editor decision, and those three were not touched.
 */
?>
<!-- wp:group {"metadata":{"name":"Call Us"},"style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"right":"var:preset|spacing|20","left":"var:preset|spacing|20"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)"><!-- wp:outermost/icon-block {"iconName":"","iconColor":"brand-700","width":"24px"} -->
<div class="wp-block-outermost-icon-block"><div class="icon-container has-brand-700-color" style="width:24px;transform:rotate(0deg) scaleX(1) scaleY(1)"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 256 256"><path d="M231.88,175.08A56.26,56.26,0,0,1,176,224C96.6,224,32,159.4,32,80A56.26,56.26,0,0,1,80.92,24.12a16,16,0,0,1,16.62,9.52l21.12,47.15,0,.12A16,16,0,0,1,117.39,96c-.18.27-.37.52-.57.77L96,121.45c7.49,15.22,23.41,31,38.83,38.51l24.34-20.71a8.12,8.12,0,0,1,.75-.56,16,16,0,0,1,15.17-1.4l.13.06,47.11,21.11A16,16,0,0,1,231.88,175.08Z"></path></svg></div></div>
<!-- /wp:outermost/icon-block -->

<!-- wp:navigation {"ref":65909,"textColor":"brand-600","overlayMenu":"never","className":"is-style-call-us-navigation","style":{"typography":{"fontWeight":"var(--wp--custom--font-weight--bold)","fontStyle":"normal"},"spacing":{"blockGap":"0"}},"fontSize":"300","ariaLabel":"<?php esc_attr_e( 'Call us', 'sd-theme-2026' ); ?>"} /--></div>
<!-- /wp:group -->

<!-- wp:buttons {"className":"sd-header__cta","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"stretch"}} -->
<div class="wp-block-buttons sd-header__cta"><!-- wp:button {"className":"is-style-fill","style":{"spacing":{"padding":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|10"}}}} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" style="padding-top:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--10)"><?php esc_html_e( 'Get in touch', 'sd-theme-2026' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Header Utilities"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right","verticalAlignment":"stretch"}} -->
<div class="wp-block-group"><!-- wp:group {"layout":{"type":"constrained"},"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"medium":true,"small":true}}}}]}} -->
<div class="wp-block-group"><!-- wp:navigation {"ref":65876,"overlayMenu":"never","className":"is-style-main-navigation","style":{"spacing":{"blockGap":"0px"}},"fontSize":"200"} /--></div>
<!-- /wp:group -->

<!-- wp:search {"label":"<?php esc_attr_e( 'Search', 'sd-theme-2026' ); ?>","showLabel":false,"placeholder":"<?php esc_attr_e( 'Search tours, lodges, destinations…', 'sd-theme-2026' ); ?>","buttonText":"<?php esc_attr_e( 'Search', 'sd-theme-2026' ); ?>","buttonPosition":"button-only","buttonUseIcon":true,"className":"is-style-header-search"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<?php
/*
 * The mobile header — its own group, not the desktop one reflowed.
 *
 * Captured from dev's Site Editor copy, 2026-09-23. Below Block Visibility's
 * `large` breakpoint the desktop group above is hidden and this one takes over:
 * Trustpilot and the logo stacked on the `neutral-200` band, then a full-width
 * `primary-600` bar holding the search trigger and the menu toggle — live's
 * arrangement at phone width.
 *
 * **Not sticky.** Live's mobile header scrolls away with the page
 * (`#masthead` is `position: relative` at 390px, measured 2026-09-23), so this
 * group carries no `style.position`. Only the desktop group sticks.
 *
 * The search is `is-style-header-search-dropdown`, not `is-style-header-search`:
 * the field drops down as a full-width band under the bar, as live's does,
 * instead of unrolling leftwards over the toggle. The mechanism is in
 * assets/styles/core-search.css.
 */
?>
<!-- wp:group {"metadata":{"name":"Header - Mobile"},"align":"full","style":{"spacing":{"blockGap":"0"}},"backgroundColor":"neutral-200","layout":{"type":"constrained"},"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"large":true}}}}]}} -->
<div class="wp-block-group alignfull has-neutral-200-background-color has-background"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
<div class="wp-block-group alignwide" style="padding-top:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20)"><!-- wp:group {"metadata":{"name":"Trustpilot"},"className":"sd-trustpilot","style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"right":"var:preset|spacing|20"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group sd-trustpilot" style="padding-right:var(--wp--preset--spacing--20)"><!-- wp:image {"width":"100px","sizeSlug":"full","linkDestination":"custom","className":"sd-trustpilot__logo","style":{"layout":{"selfStretch":"fixedNoShrink","flexSize":"90px"}}} -->
<figure class="wp-block-image size-full is-resized sd-trustpilot__logo"><a href="https://www.trustpilot.com/review/southerndestinations.com" target="_blank" rel="noreferrer noopener"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/trustpilot/trustpilot-logo.svg' ) ); ?>" alt="<?php esc_attr_e( 'Trustpilot', 'sd-theme-2026' ); ?>" style="width:100px;height:auto"/></a></figure>
<!-- /wp:image -->

<!-- wp:image {"width":"143px","sizeSlug":"full","linkDestination":"custom","className":"sd-trustpilot__stars","style":{"layout":{"selfStretch":"fixedNoShrink","flexSize":"120px"}}} -->
<figure class="wp-block-image size-full is-resized sd-trustpilot__stars"><a href="https://www.trustpilot.com/review/southerndestinations.com" target="_blank" rel="noreferrer noopener"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/trustpilot/stars/stars-5.svg' ) ); ?>" alt="<?php esc_attr_e( 'Rated 5 out of 5 on Trustpilot', 'sd-theme-2026' ); ?>" style="width:143px;height:auto"/></a></figure>
<!-- /wp:image --></div>
<!-- /wp:group -->

<!-- wp:site-logo {"width":240,"align":"left"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Header Utilities"},"align":"full","style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"top":"var:preset|spacing|5","bottom":"var:preset|spacing|5"}},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"backgroundColor":"primary-600","textColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-base-color has-primary-600-background-color has-text-color has-background has-link-color" style="padding-top:var(--wp--preset--spacing--5);padding-bottom:var(--wp--preset--spacing--5)"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
<div class="wp-block-group alignwide"><!-- wp:search {"label":"<?php esc_attr_e( 'Search', 'sd-theme-2026' ); ?>","showLabel":false,"placeholder":"<?php esc_attr_e( 'Search tours, lodges, destinations…', 'sd-theme-2026' ); ?>","buttonText":"<?php esc_attr_e( 'Search', 'sd-theme-2026' ); ?>","buttonPosition":"button-only","buttonUseIcon":true,"className":"is-style-header-search-dropdown","style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base"} /-->

<!-- wp:navigation {"ref":65877,"textColor":"base","overlayMenu":"always","icon":"menu","className":"is-style-mobile-navigation","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","justifyContent":"right"},"ariaLabel":"<?php esc_attr_e( 'Main navigation', 'sd-theme-2026' ); ?>","mobileMenuSlug":"mobile-menu"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
