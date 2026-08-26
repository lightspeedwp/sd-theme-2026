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
				 * The Trustpilot badge — two linked images, and deliberately
				 * nothing more.
				 *
				 * This used to `require patterns/trustpilot-score.php`, the
				 * data-bound badge, and that was a misreading of live. Measured
				 * on live 2026-08-21 (computed styles, not source), the header
				 * badge renders **two visible children out of four**:
				 *
				 *     h3.tp-wording  "Excellent"                → display:none
				 *     a.tp-review-logo  → tp-logo.svg  100×24    → visible
				 *     a.tp-review-stars → 5star.svg    143×25    → visible
				 *     div.tb-score  "TrustScore 5 | 349 reviews" → display:none
				 *
				 * by `#tb-horizon-review .tp-wording{display:none}` and
				 * `.sd-top-menu-wrapper #tb-horizon-review .tb-score{display:none}`
				 * — checked against all 18 stylesheets and every inline
				 * `<style>` on the page, so nothing later in the cascade puts
				 * them back.
				 *
				 * The two hidden children are the *only* things the Trustpilot
				 * API supplies. So live's header makes an API call whose entire
				 * visible product is `display:none`, and the header badge is two
				 * static SVGs. Binding it, then hiding three of five children
				 * with CSS, would have paid for a live lookup to render nothing.
				 *
				 * The expert panel is the opposite case and keeps the bound
				 * pattern: there `.tb-score` *is* visible (142×13, measured on
				 * /accommodation/table-bay-hotel/), so the score and the review
				 * count are real output. → patterns/safari-expert.php
				 *
				 * ## Two links to one destination
				 *
				 * Both anchors point at the review page, which is what live
				 * does. Unlike live they have accessible names — live's two
				 * `<img>`s carry no `alt`, so both links are nameless (WCAG
				 * 2.4.4). Named, adjacent links to one target are a *should*
				 * (technique H2), not a failure, and the structure is what was
				 * asked for. To collapse them, drop the stars' `linkDestination`
				 * and give it `alt=""` — the logo keeps the link and the name.
				 *
				 * ## The star tile is static, and that is a maintenance cost
				 *
				 * `stars-5.svg` and the "5 out of 5" in its `alt` are authored,
				 * not measured — accurate today (live's API reports TrustScore 5
				 * from 349 reviews) and silently wrong the day the rating moves.
				 * Both would have to change together. That is the argument for
				 * binding this too once the Trustpilot module lands; it is not
				 * an argument for binding it now, when the only bindable values
				 * in this placement are the two live hides.
				 */
				?>
				<?php
				/*
				 * `padding-right` reproduces live's measured 23px between the
				 * mark and Call Us: the enclosing cluster runs at `blockGap: 0`
				 * because live has no gap either — the items' own horizontal
				 * padding does the spacing.
				 */
				?>
				<!-- wp:group {"metadata":{"name":"Trustpilot"},"className":"sd-trustpilot","style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"right":"var:preset|spacing|20"}}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
				<div class="wp-block-group sd-trustpilot" style="padding-right:var(--wp--preset--spacing--20)">

					<!-- wp:image {"width":"100px","sizeSlug":"full","linkDestination":"custom","className":"sd-trustpilot__logo"} -->
					<figure class="wp-block-image size-full is-resized sd-trustpilot__logo"><a href="https://www.trustpilot.com/review/southerndestinations.com" target="_blank" rel="noreferrer noopener"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/trustpilot/trustpilot-logo.svg' ) ); ?>" alt="<?php esc_attr_e( 'Trustpilot', 'sd-theme-2026' ); ?>" style="width:100px"/></a></figure>
					<!-- /wp:image -->

					<!-- wp:image {"width":"143px","sizeSlug":"full","linkDestination":"custom","className":"sd-trustpilot__stars"} -->
					<figure class="wp-block-image size-full is-resized sd-trustpilot__stars"><a href="https://www.trustpilot.com/review/southerndestinations.com" target="_blank" rel="noreferrer noopener"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/trustpilot/stars/stars-5.svg' ) ); ?>" alt="<?php esc_attr_e( 'Rated 5 out of 5 on Trustpilot', 'sd-theme-2026' ); ?>" style="width:143px"/></a></figure>
					<!-- /wp:image -->

				</div>
				<!-- /wp:group -->

				<?php
				/*
				 * Call Us — a `core/accordion` carrying `is-style-call-us-dropdown`.
				 *
				 * This was the `sd/call-us` plugin block until 2026-08-21. WP 7.1
				 * ships everything that block existed to provide:
				 * `core/accordion-heading` renders a real `<button>` with
				 * `aria-expanded` and `aria-controls`, and the open/close state runs
				 * through the Interactivity API. Live's version is a Bootstrap
				 * `data-toggle` on an `<a href="#">` — a link that goes nowhere,
				 * with no `aria-expanded` at all — so all four of the defects the
				 * old block was written to fix are still fixed, upstream.
				 *
				 * Two things the custom block did that core does not: dismiss on
				 * Escape, and dismiss on click-outside. Both are behaviour, so if
				 * they come back they come back in `sd-enhancements`, not here.
				 * → LS-2033
				 *
				 * Live builds this widget twice — as this dropdown, and again,
				 * differently, on the safari expert panel — which is how the header
				 * ended up with two numbers and the expert panel with four. One
				 * list now: parts/dropdown-call-us.html, a template part precisely
				 * so both placements read the same file and the numbers stay
				 * editable in the Site Editor.
				 *
				 * The weight *is* set here, unlike under the old block. `sd/call-us`
				 * supported `typography.__experimentalFontWeight` but its render
				 * callback dropped the style-engine output, so the attribute
				 * serialised to nothing and the button rendered at 400 — which is
				 * why live's 700 used to be applied from a class in a stylesheet.
				 * `core/accordion` serialises it properly.
				 *
				 * `showIcon: false` because core's indicator is a `+` that rotates
				 * into an `×`. Live's caret is drawn in
				 * assets/styles/core-accordion.css instead.
				 *
				 * `headingLevel: 3` — core's accordion always wraps its toggle in a
				 * heading, which is the ARIA pattern for an accordion and is not
				 * optional (the block's save is `"h" + headingLevel`). It puts one
				 * heading inside the banner landmark that live does not have. Level
				 * 3 rather than 2 so it does not compete with a page's own h2s.
				 */
				?>
				<!-- wp:accordion {"showIcon":false,"headingLevel":3,"className":"is-style-call-us-dropdown","style":{"typography":{"fontWeight":"var:custom|font-weight|bold"},"spacing":{"padding":{"right":"var:preset|spacing|20","left":"var:preset|spacing|20"}}},"textColor":"brand-500","fontSize":"300"} -->
				<div role="group" class="wp-block-accordion is-style-call-us-dropdown has-brand-500-color has-text-color has-300-font-size" style="padding-right:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20);font-weight:var(--wp--custom--font-weight--bold)"><!-- wp:accordion-item -->
				<div class="wp-block-accordion-item"><!-- wp:accordion-heading {"title":"<?php esc_attr_e( 'Call Us Today', 'sd-theme-2026' ); ?>","level":3,"showIcon":false} -->
				<h3 class="wp-block-accordion-heading"><button type="button" class="wp-block-accordion-heading__toggle"><span class="wp-block-accordion-heading__toggle-title"><?php esc_html_e( 'Call Us Today', 'sd-theme-2026' ); ?></span></button></h3>
				<!-- /wp:accordion-heading -->

				<!-- wp:accordion-panel -->
				<div role="region" class="wp-block-accordion-panel"><!-- wp:template-part {"slug":"dropdown-call-us","area":"menu"} /--></div>
				<!-- /wp:accordion-panel --></div>
				<!-- /wp:accordion-item --></div>
				<!-- /wp:accordion -->

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
