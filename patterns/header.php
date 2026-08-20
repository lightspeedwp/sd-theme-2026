<?php
/**
 * Title: Header
 * Slug: sd-theme-2026/header
 * Description: Site header — a slim utility bar carrying the Trustpilot mark and the Call Us numbers, above a main row with the logo, mega-menu navigation, search and the enquiry call to action. Sticks on scroll, with the utility bar scrolling away.
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
 * The navigation blocks below reference `wp_navigation` menus by `ref`.
 *
 * These IDs are dev's, and that is deliberate: **dev is deployed wholesale to
 * live**, database included, so the IDs travel with the content they point at.
 * AGENTS.md's rule against hardcoded refs is about themes that are installed
 * into a database they were not built against — that is not this migration.
 *
 * The menus are content, built and edited on dev under Appearance → Navigation:
 *
 *   65876  SD Main Navigation         4 mega menus + Specials
 *   65877  SD Mobile Navigation       the tiered mobile tree
 *
 * 65879 ("SD Utility Navigation") used to be here for the Call Us dropdown and
 * is no longer referenced: that widget is `sd/call-us` now, and its numbers are
 * a template part. The menu can be deleted on dev once this ships.
 *
 * The columns inside each mega-menu panel reference their own menus; those refs
 * live in parts/mega-menu-*.html.
 *
 * The one place this does not resolve is **local**, which is a fixture
 * environment with no menus, so the header renders core's fallback there. That
 * is expected — local is for lint and activation, not for proving the header.
 *
 * Menu *items* link by entity (`kind: post-type` with an `id`) wherever a post
 * or page exists behind them, rather than by typed URL. The `/search/...`
 * filters have no post behind them and stay custom links.
 */
?>
<!-- wp:group {"metadata":{"name":"Header"},"tagName":"header","align":"full","className":"sd-header","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"constrained"}} -->
<header class="wp-block-group alignfull sd-header">

	<!-- wp:group {"metadata":{"name":"Utility Bar"},"align":"full","className":"is-style-utility-bar sd-header__utility","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull is-style-utility-bar sd-header__utility">
		<!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
		<div class="wp-block-group alignwide">

			<?php
			/*
			 * The Trustpilot badge.
			 *
			 * An earlier pass at this file had a static image here — attachment
			 * 50269, `uploads/2019/07/trust-pilot-badge.png`, linked to the
			 * reviews page. That was the wrong one of live's *two* Trustpilot
			 * marks. Live's header carries both:
			 *
			 *   1. `#tb-horizon-review.tb-color-brown` — the real badge, from
			 *      `[tp_show_score color="brown"]`: the band word, the mark, a
			 *      star tile and "TrustScore 5 | 349 reviews".
			 *   2. A `trust-menu` nav item holding
			 *      `uploads/2019/07/trustpilot.png`, linked to `#`.
			 *
			 * The second is what got reproduced. It is dropped — it duplicates
			 * the badge beside it and its link goes nowhere — and the first is
			 * built properly, as patterns/trustpilot-score.php, reading the live
			 * score through the `sd/trustpilot` binding source.
			 *
			 * `require`, not `<!-- wp:pattern -->`: a nested pattern reference is
			 * silently dropped on front-end render (and resolves fine under
			 * WP-CLI, so a CLI test would not catch it). `require` inlines the
			 * markup at registration while leaving trustpilot-score.php an
			 * independently registered, separately insertable pattern.
			 * → .claude/skills/wp-pattern-runtime-pitfalls
			 */
			require __DIR__ . '/trustpilot-score.php';
			?>

			<?php
			/*
			 * Call Us.
			 *
			 * `sd/call-us` — a real `<button>` whose `aria-expanded` tracks the
			 * panel, with Escape, click-outside and focus-out dismissal, all in
			 * the plugin. This replaces the `wp:navigation` block that used to be
			 * here, and with it menu 65879 ("SD Utility Navigation"), which
			 * existed only to hold two phone numbers.
			 *
			 * Live builds this as a Bootstrap dropdown on an `<a href="#">` in a
			 * nav menu, and builds it *again*, differently, on the safari expert
			 * panel — which is how the header ended up with two numbers and the
			 * expert panel with four. One block now, and one list of numbers:
			 * parts/dropdown-call-us.html, which is a template part rather than
			 * inline markup precisely so both placements read the same file and
			 * so the numbers stay editable in the Site Editor.
			 *
			 * `placement: end` because this sits at the right-hand end of the
			 * utility bar; a start-aligned panel would hang off the viewport.
			 */
			?>
			<!-- wp:sd/call-us {"label":"<?php esc_attr_e( 'Call Us Today', 'sd-theme-2026' ); ?>","placement":"end","className":"sd-header__call-us","fontSize":"100"} -->
			<!-- wp:template-part {"slug":"dropdown-call-us","area":"menu"} /-->
			<!-- /wp:sd/call-us -->

		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"Header Row"},"align":"full","className":"is-style-header-row sd-header__row","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull is-style-header-row sd-header__row">
		<!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:site-logo {"width":150,"className":"sd-header__logo"} /-->

			<?php /* Desktop navigation. Hidden below the header breakpoint in assets/styles/core-navigation.css — this install has no Block Visibility plugin, so the swap is a media query rather than a per-block control. */ ?>
			<!-- wp:navigation {"ref":65876,"overlayMenu":"never","className":"is-style-main-navigation sd-nav-desktop","ariaLabel":"<?php esc_attr_e( 'Main navigation', 'sd-theme-2026' ); ?>","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","justifyContent":"center"}} /-->

			<!-- wp:group {"metadata":{"name":"Header Utilities"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
			<div class="wp-block-group">

				<!-- wp:search {"label":"<?php esc_attr_e( 'Search', 'sd-theme-2026' ); ?>","showLabel":false,"placeholder":"<?php esc_attr_e( 'Search tours, lodges, destinations…', 'sd-theme-2026' ); ?>","widthUnit":"%","buttonText":"<?php esc_attr_e( 'Search', 'sd-theme-2026' ); ?>","buttonPosition":"button-only","buttonUseIcon":true,"className":"sd-header__search"} /-->

				<!-- wp:buttons {"className":"sd-header__cta","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
				<div class="wp-block-buttons sd-header__cta">
					<!-- wp:button {"className":"is-style-fill"} -->
					<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Get in touch', 'sd-theme-2026' ); ?></a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->

				<?php /* Mobile navigation. The overlay's contents come from parts/mobile-menu.html via Ollie Menu Designer's `mobileMenuSlug`, which is why this is a second navigation block rather than a responsive mode on the one above. */ ?>
				<!-- wp:navigation {"ref":65877,"overlayMenu":"always","icon":"menu","mobileMenuSlug":"mobile-menu","className":"is-style-mobile-navigation sd-nav-mobile","ariaLabel":"<?php esc_attr_e( 'Main navigation', 'sd-theme-2026' ); ?>","layout":{"type":"flex","justifyContent":"right"}} /-->

			</div>
			<!-- /wp:group -->

		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

</header>
<!-- /wp:group -->
