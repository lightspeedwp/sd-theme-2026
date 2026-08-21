<?php
/**
 * Title: Header
 * Slug: sd-theme-2026/header
 * Description: Site header — a slim utility bar carrying the Trustpilot mark, the Call Us numbers and the enquiry action, above a main row with the overhanging logo, mega-menu navigation and a fold-out search. Sticks on scroll, with the utility bar scrolling away.
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
 * Local carries 65876 but not 65877, so the desktop row renders from the real
 * menu there and the mobile nav falls back to core's page list — which is fine,
 * because local is a fixture environment: 3 top-level items instead of dev's 5.
 * Prove the menu *content* on dev; local proves the header's geometry, styling
 * and interactions, which is what it was used for here.
 *
 * Menu *items* link by entity (`kind: post-type` with an `id`) wherever a post
 * or page exists behind them, rather than by typed URL. The `/search/...`
 * filters have no post behind them and stay custom links.
 */

/*
 * Sticky is a block setting, not a stylesheet.
 *
 * `core/group` supports `position: sticky` natively, so it travels in the block's
 * own `style.position` — which also means it is editable in the Site Editor
 * instead of being locked in a CSS file. Live sticks the whole header, both bands
 * together: at scrollY 900 on an inner page `.sticky-menu-wrap` is
 * `position: fixed; top: 0` and the utility bar stays put, so `top: 0px` here and
 * no offset.
 *
 * Live also compresses the stuck header by 22px (`.scrolled #masthead
 * { margin-top: -22px }`). That needs a scroll listener to toggle a class, which
 * is behaviour and does not belong in the theme — the header sticks at its full
 * height instead.
 */

/*
 * `tagName: div`, not `header`.
 *
 * WordPress already wraps a template part whose area is `header` in a `<header>`
 * element, so a `<header>` here too renders one banner landmark inside another —
 * confirmed on the rendered page 2026-08-20:
 *
 *     header.wp-block-template-part > header.wp-block-group.sd-header
 *
 * Two banner landmarks is a real defect: a screen-reader user navigating by
 * landmark hits "banner" twice for one header and has no way to tell which is
 * the one they want. The outer wrapper is the landmark; this is its content.
 * `.sd-header` still carries the sticky-context rules in
 * assets/styles/core-group.css, so nothing else changes.
 */
?>
<!-- wp:group {"metadata":{"name":"Header"},"align":"full","className":"sd-header","style":{"spacing":{"blockGap":"0"},"position":{"type":"sticky","top":"0px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull sd-header">

	<?php
	/*
	 * The utility bar.
	 *
	 * Live right-aligns the whole cluster rather than spreading it: measured at
	 * 1440px the Trustpilot mark ends at x=873 and Call Us begins at x=896, so
	 * the three items sit shoulder to shoulder at the right-hand end of the row
	 * with the CTA flush against the content edge. `justifyContent: right` with
	 * `blockGap: 0` reproduces that — live has no gap either, the items' own
	 * horizontal padding does the spacing.
	 */
	?>
	<!-- wp:group {"metadata":{"name":"Utility Bar"},"align":"full","className":"is-style-utility-bar sd-header__utility","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull is-style-utility-bar sd-header__utility">
		<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"0"},"dimensions":{"minHeight":"var:custom|header|utility-height"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right","verticalAlignment":"stretch"}} -->
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
			 *
			 * Type and colour are live's, measured 2026-08-20:
			 * `#menu-top-menu-right .menu-item a { color:#cc7f16; font-size:18px;
			 * font-weight:700 }` — brand-500 is that hex exactly, and font-size
			 * 300 is the nearest token. The block already draws live's phone
			 * glyph and caret itself (assets/styles/sd-call-us.css).
			 *
			 * The weight is *not* set here. `sd/call-us` supports
			 * `typography.__experimentalFontWeight`, but its render callback does
			 * not emit the style-engine output for it — a `style.typography.
			 * fontWeight` attribute on this block serialises to nothing and the
			 * button renders at 400. Verified on the rendered page 2026-08-20.
			 * Live's 700 is applied in assets/styles/sd-call-us.css instead, where
			 * the rest of the block's design already lives. Setting it here would
			 * look correct in the editor and be wrong on the front end.
			 */
			?>
			<!-- wp:sd/call-us {"label":"<?php esc_attr_e( 'Call Us Today', 'sd-theme-2026' ); ?>","placement":"end","className":"sd-header__call-us","textColor":"brand-500","fontSize":"300"} -->
			<!-- wp:template-part {"slug":"dropdown-call-us","area":"menu"} /-->
			<!-- /wp:sd/call-us -->

			<?php
			/*
			 * The enquiry action.
			 *
			 * This lives in the utility bar, not in the header row — live puts it
			 * there (`#menu-top-menu-right .cta-btn`, a 55px-tall square plate
			 * flush against the container's right edge) and an earlier pass of
			 * this pattern had it down beside the search instead, which put two
			 * competing actions on the nav row and left the top bar half empty.
			 *
			 * `is-style-fill` already carries the whole treatment from
			 * theme.json — brand-500 ground, base label, zero radius, heading
			 * face, uppercase, semi-bold, hovering to brand-600 (live hovers to
			 * #BF5C17, which misses WCAG AA at 4.41:1; brand-600 is the settled
			 * replacement — see assets/styles/core-button.css). The only thing
			 * left is making it full-bleed to the bar's height, which is
			 * `.sd-header__cta` in that same stylesheet.
			 */
			?>
			<!-- wp:buttons {"className":"sd-header__cta","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"stretch"}} -->
			<div class="wp-block-buttons sd-header__cta">
				<!-- wp:button {"className":"is-style-fill"} -->
				<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Get in touch', 'sd-theme-2026' ); ?></a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->

		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"Header Row"},"align":"full","className":"is-style-header-row sd-header__row","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull is-style-header-row sd-header__row">
		<?php
		/*
		 * `verticalAlignment: stretch` rather than `center`: the navigation has
		 * to reach the row's bottom edge for the hover underline to land there,
		 * and it can only do that if its ancestors are full-height. The logo is
		 * put back to centred with `align-self` in
		 * assets/styles/core-site-logo.css, since it is the one item here that
		 * should not stretch.
		 */
		?>
		<!-- wp:group {"align":"wide","style":{"dimensions":{"minHeight":"var:custom|header|row-height"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"stretch"}} -->
		<div class="wp-block-group alignwide">

			<?php
			/*
			 * The logo.
			 *
			 * 310px is live's rendered width (310x84, measured 2026-08-20), and
			 * the mark *overhangs* — live lifts it 45px so the elephants rise out
			 * of this row and into the utility bar, which is the single most
			 * recognisable thing about the SD header. That lift is
			 * assets/styles/core-site-logo.css, and it is why both bands share one
			 * neutral-200 ground: the logo crosses the seam between them.
			 */
			?>
			<!-- wp:site-logo {"width":310,"className":"sd-header__logo"} /-->

			<?php
			/*
			 * The right-hand cluster: navigation, then the search disclosure,
			 * then the mobile trigger. Live puts all three at the right-hand end
			 * with the logo alone on the left, which is what the parent row's
			 * `space-between` produces.
			 *
			 * `verticalAlignment: stretch` so the nav can fill the row's height —
			 * its links are 50px tall and bottom-aligned, which is what lands the
			 * hover underline flush on the row's bottom edge as live's
			 * `border-bottom` does.
			 *
			 * blockGap 30: live measures ~25px between the last nav label and the
			 * search icon.
			 */
			?>
			<!-- wp:group {"metadata":{"name":"Header Utilities"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right","verticalAlignment":"stretch"}} -->
			<div class="wp-block-group">

				<?php
				/*
				 * Desktop navigation. Hidden below the header breakpoint in
				 * assets/styles/core-navigation.css — this install has no Block
				 * Visibility plugin, so the swap is a media query rather than a
				 * per-block control.
				 *
				 * `justifyContent: right` because live right-aligns the nav
				 * (`#masthead .primary-navbar { justify-content: flex-end }`); an
				 * earlier pass centred it, which pushed it into the logo. The
				 * blockGap is 0 because live spaces the items with the links'
				 * own 10px inline padding, set in the block style — a gap on top
				 * of that would widen the underline's dead space between items.
				 */
				?>
				<!-- wp:navigation {"ref":65876,"overlayMenu":"never","showSubmenuIcon":false,"className":"is-style-main-navigation sd-nav-desktop","ariaLabel":"<?php esc_attr_e( 'Main navigation', 'sd-theme-2026' ); ?>","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","justifyContent":"right"}} /-->

				<?php
				/*
				 * The search.
				 *
				 * `buttonPosition: button-only` is not just a look — on WordPress
				 * 7.0 it is what makes `core/search` a *disclosure*: core enqueues
				 * its Interactivity view module, binds `aria-expanded` and
				 * `aria-label` on the button and toggles the field's hidden class,
				 * with Escape and focus-out closing it. So the icon-that-folds-out
				 * behaviour, and its whole accessibility contract, is core's; the
				 * theme only draws it (assets/styles/core-search.css), which
				 * overrides core's in-flow growth for live's overlay geometry so
				 * the header does not reflow as the field opens.
				 *
				 * No `isSearchFieldHidden` attribute: it was dropped from core's
				 * block.json with the Interactivity rewrite and now does nothing.
				 * The sibling ATI theme's header still sets it — don't copy it.
				 */
				?>
				<!-- wp:search {"label":"<?php esc_attr_e( 'Search', 'sd-theme-2026' ); ?>","showLabel":false,"placeholder":"<?php esc_attr_e( 'Search tours, lodges, destinations…', 'sd-theme-2026' ); ?>","buttonText":"<?php esc_attr_e( 'Search', 'sd-theme-2026' ); ?>","buttonPosition":"button-only","buttonUseIcon":true,"className":"is-style-header-search"} /-->

				<?php /* Mobile navigation. The overlay's contents come from parts/mobile-menu.html via Ollie Menu Designer's `mobileMenuSlug`, which is why this is a second navigation block rather than a responsive mode on the one above. */ ?>
				<!-- wp:navigation {"ref":65877,"overlayMenu":"always","icon":"menu","mobileMenuSlug":"mobile-menu","className":"is-style-mobile-navigation sd-nav-mobile","ariaLabel":"<?php esc_attr_e( 'Main navigation', 'sd-theme-2026' ); ?>","layout":{"type":"flex","justifyContent":"right"}} /-->

			</div>
			<!-- /wp:group -->

		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
