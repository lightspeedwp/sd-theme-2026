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
<!-- wp:group {"metadata":{"name":"Header"},"align":"full","className":"is-style-header","style":{"spacing":{"blockGap":"0"},"position":{"type":"sticky","top":"0px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-header">

	<!-- wp:columns {"verticalAlignment":"center","align":"wide"} -->
	<div class="wp-block-columns alignwide are-vertically-aligned-center">

		<?php
		/*
		 * The logo.
		 *
		 * 300px, set on the block — not a token. It is one number used in one
		 * place, and having it here means it is adjustable in the editor with the
		 * mark in front of you, which is how a logo width actually gets chosen.
		 */
		?>
		<!-- wp:column {"verticalAlignment":"center","width":"30%"} -->
		<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:30%">
			<!-- wp:site-logo {"width":300,"className":"sd-header__logo"} /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"blockGap":"var:preset|spacing|10"}}} -->
		<div class="wp-block-column is-vertically-aligned-center">

			<?php
			/*
			 * The utility cluster.
			 *
			 * Live right-aligns the whole cluster rather than spreading it:
			 * measured at 1440px the Trustpilot mark ends at x=873 and Call Us
			 * begins at x=896, so the three items sit shoulder to shoulder at the
			 * right-hand end with the CTA flush against the content edge.
			 * `justifyContent: right` with `blockGap: 0` reproduces that — live
			 * has no gap either, the items' own horizontal padding does the
			 * spacing.
			 */
			?>
			<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
			<div class="wp-block-group alignwide">

				<?php
				/*
				 * The Trustpilot badge — `patterns/trustpilot-score.php`, reading
				 * the live score through the `sd/trustpilot` binding source.
				 *
				 * `require`, not `<!-- wp:pattern -->`: a nested pattern reference
				 * is silently dropped on front-end render (and resolves fine under
				 * WP-CLI, so a CLI test would not catch it). `require` inlines the
				 * markup at registration while leaving trustpilot-score.php an
				 * independently registered, separately insertable pattern.
				 * → .claude/skills/wp-pattern-runtime-pitfalls
				 *
				 * Note: live hides the band word, the score and the review count
				 * in its header (`#tb-horizon-review .tp-wording{display:none}`
				 * and `.sd-top-menu-wrapper … .tb-score{display:none}`), showing
				 * only the mark and the star tile. The full badge renders here.
				 * If that should match live, it is one rule in
				 * assets/styles/core-group.css — see the note there.
				 */
				require __DIR__ . '/trustpilot-score.php';
				?>

				<?php
				/*
				 * Call Us — `sd/call-us`, a real `<button>` whose `aria-expanded`
				 * tracks the panel, with Escape, click-outside and focus-out
				 * dismissal, all in the plugin.
				 *
				 * Live builds this as a Bootstrap dropdown on an `<a href="#">`,
				 * and builds it *again*, differently, on the safari expert panel —
				 * which is how the header ended up with two numbers and the expert
				 * panel with four. One block now, and one list of numbers:
				 * parts/dropdown-call-us.html, a template part precisely so both
				 * placements read the same file and the numbers stay editable.
				 *
				 * The weight is *not* set here. `sd/call-us` supports
				 * `typography.__experimentalFontWeight`, but its render callback
				 * does not emit the style-engine output for it, so the attribute
				 * serialises to nothing and the button renders at 400. Live's 700
				 * is applied in assets/styles/sd-call-us.css instead. Setting it
				 * here would look correct in the editor and be wrong on the front
				 * end.
				 */
				?>
				<!-- wp:sd/call-us {"label":"<?php esc_attr_e( 'Call Us Today', 'sd-theme-2026' ); ?>","placement":"end","className":"sd-header__call-us","textColor":"brand-500","fontSize":"300"} -->
				<!-- wp:template-part {"slug":"dropdown-call-us","area":"menu"} /-->
				<!-- /wp:sd/call-us -->

				<?php
				/*
				 * The enquiry action. `is-style-fill` carries the whole treatment
				 * from theme.json — brand-500 ground, base label, zero radius,
				 * heading face, uppercase, semi-bold, hovering to brand-600 (live
				 * hovers to #BF5C17, which misses WCAG AA at 4.41:1; brand-600 is
				 * the settled replacement — see assets/styles/core-button.css).
				 *
				 * Its vertical padding is what gives the utility cluster its
				 * height, now that `custom.header.utility-height` is gone.
				 */
				?>
				<!-- wp:buttons {"className":"sd-header__cta","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"stretch"}} -->
				<div class="wp-block-buttons sd-header__cta">
					<!-- wp:button {"className":"is-style-fill","style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}}} -->
					<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" style="padding-top:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20)"><?php esc_html_e( 'Get in touch', 'sd-theme-2026' ); ?></a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->

			</div>
			<!-- /wp:group -->

			<?php
			/*
			 * Navigation, search, mobile trigger.
			 *
			 * `verticalAlignment: stretch` so the nav can fill the row — its links
			 * are bottom-aligned, which is what lands the hover underline flush on
			 * the row's bottom edge as live's `border-bottom` does.
			 *
			 * blockGap 30: live measures ~25px between the last nav label and the
			 * search icon.
			 */
			?>
			<!-- wp:group {"metadata":{"name":"Header Utilities"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right","verticalAlignment":"stretch"}} -->
			<div class="wp-block-group">

				<?php
				/*
				 * Desktop navigation.
				 *
				 * ⚠️ `overlayMenu: "never"` is required, not cosmetic. Leave it at
				 * the default and core wraps the list in
				 * `.wp-block-navigation__responsive-container` and three more
				 * elements, the `is-style-main-navigation` selectors miss, and the
				 * nav silently renders at core's defaults. This nav is
				 * desktop-only, so an overlay here is dead structure anyway.
				 *
				 * The show/hide is a Block Visibility control set on the wrapper
				 * group, not a media query — an editable per-block setting rather
				 * than a selector buried in a stylesheet. The plugin is active on
				 * dev; on local it is not installed, so both navs render there
				 * until it is.
				 */
				?>
				<!-- wp:group {"layout":{"type":"constrained"},"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"medium":true,"small":true}}}}]}} -->
				<div class="wp-block-group">
					<!-- wp:navigation {"ref":65876,"overlayMenu":"never","className":"is-style-main-navigation","style":{"spacing":{"blockGap":"0px"}},"fontSize":"200"} /-->
				</div>
				<!-- /wp:group -->

				<?php
				/*
				 * The search.
				 *
				 * `buttonPosition: button-only` is not just a look — on WordPress
				 * 7.0 it is what makes `core/search` a *disclosure*: core enqueues
				 * its Interactivity view module, binds `aria-expanded` and
				 * `aria-label` on the button and toggles the field's hidden class,
				 * with Escape and focus-out closing it. The behaviour and its whole
				 * accessibility contract are core's; `is-style-header-search` only
				 * draws it.
				 *
				 * No `isSearchFieldHidden` attribute: it was dropped from core's
				 * block.json with the Interactivity rewrite and now does nothing.
				 * The sibling ATI theme's header still sets it — don't copy it.
				 */
				?>
				<!-- wp:search {"label":"<?php esc_attr_e( 'Search', 'sd-theme-2026' ); ?>","showLabel":false,"placeholder":"<?php esc_attr_e( 'Search tours, lodges, destinations…', 'sd-theme-2026' ); ?>","buttonText":"<?php esc_attr_e( 'Search', 'sd-theme-2026' ); ?>","buttonPosition":"button-only","buttonUseIcon":true,"className":"is-style-header-search"} /-->

				<?php
				/*
				 * Mobile navigation. The overlay's contents come from
				 * parts/mobile-menu.html via Ollie Menu Designer's
				 * `mobileMenuSlug`, which is why this is a second navigation block
				 * rather than a responsive mode on the one above.
				 */
				?>
				<!-- wp:group {"layout":{"type":"constrained"},"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"large":true}}}}]}} -->
				<div class="wp-block-group">
					<!-- wp:navigation {"ref":65877,"overlayMenu":"always","icon":"menu","mobileMenuSlug":"mobile-menu","className":"is-style-mobile-navigation","ariaLabel":"<?php esc_attr_e( 'Main navigation', 'sd-theme-2026' ); ?>","layout":{"type":"flex","justifyContent":"right"}} /-->
				</div>
				<!-- /wp:group -->

			</div>
			<!-- /wp:group -->

		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
