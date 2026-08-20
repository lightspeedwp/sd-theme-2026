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
 *   65879  SD Utility Navigation      the Call Us dropdown
 *   65876  SD Main Navigation         4 mega menus + Specials
 *   65877  SD Mobile Navigation       the tiered mobile tree
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
			 * The static Trustpilot badge — attachment 50269 on dev,
			 * 2019/07/trust-pilot-badge.png, 423×31. Shown at 160px, which puts
			 * it at roughly 12px tall and level with the utility bar's type.
			 *
			 * The media library holds several near-identical marks; this is the
			 * one confirmed for the header. 55331 (trust-pilot-top-menu.svg) is
			 * an SVG named for this slot and would scale more cleanly if the
			 * badge is ever shown larger — safe-svg is active, so it is usable.
			 *
			 * Live links this badge to "#". It is linked to the reviews page
			 * instead: an image that is the only content of a link needs the
			 * link to go somewhere, and "#" gives a keyboard user a focus stop
			 * that does nothing.
			 *
			 * This is the *static* mark. The API-backed Trustpilot widget is a
			 * different component — it lives in the footer and on Team and is
			 * plugin work. No credential is involved here.
			 */
			?>
			<!-- wp:image {"id":50269,"width":"160px","sizeSlug":"full","linkDestination":"custom","className":"sd-header__trustpilot"} -->
			<figure class="wp-block-image size-full is-resized sd-header__trustpilot"><a href="<?php echo esc_url( home_url( '/reviews/' ) ); ?>"><img src="<?php echo esc_url( home_url( '/wp-content/uploads/2019/07/trust-pilot-badge.png' ) ); ?>" alt="<?php esc_attr_e( 'Southern Destinations is rated Excellent on Trustpilot', 'sd-theme-2026' ); ?>" class="wp-image-50269" style="width:160px"/></a></figure>
			<!-- /wp:image -->

			<?php /* The Call Us dropdown is an ollie/mega-menu block inside this menu, not a core
			   navigation submenu, so its panel is the authored parts/dropdown-call-us.html and
			   its disclosure is the plugin's (a real button with aria-expanded). */ ?>
			<!-- wp:navigation {"ref":65879,"overlayMenu":"never","className":"sd-header__call-us","ariaLabel":"<?php esc_attr_e( 'Contact numbers', 'sd-theme-2026' ); ?>","fontSize":"100","layout":{"type":"flex","justifyContent":"right"}} /-->

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
