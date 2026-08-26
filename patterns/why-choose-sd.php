<?php
/**
 * Title: Why Choose Southern Destinations
 * Slug: sd-theme-2026/why-choose-sd
 * Description: The dark closing band the live site runs beneath its inner pages — a section title over three value columns (Trusted, Passionate, Value) with the Trustpilot score beneath. Inherits the Dark Page Section ground.
 * Categories: sd-theme-2026/call-to-action, sd-theme-2026/features
 * Keywords: why choose, trusted, passionate, value, cta, footer, trustpilot
 * Viewport Width: 1400
 * Template Types: page
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * What this replaces — live's `.footer-cta-section`.
 *
 * On live this band is not page content at all. It is emitted by the child
 * theme beneath every inner page, which is why it appears verbatim on
 * /about-us/social-responsibility/ and /about-us/connect-with-us/ while being
 * absent from both pages' stored content. That distinction matters for where it
 * goes next: it is a *template* concern, not a page-body one.
 *
 * This file therefore exists so the band has one definition, but it is
 * deliberately NOT pasted into the About Us page bodies. Dropping it into each
 * page would duplicate it the moment parts/footer.html or the page templates
 * pick it up — which is the correct eventual home, and the follow-up this
 * pattern is waiting on. → LS-2033
 *
 * ## Ground and colour
 *
 * `is-style-dark-page-section` owns the neutral-800 ground, the base body copy
 * and the accent-500 link colour — see styles/sections/dark-page-section.json.
 * Nothing here sets a colour except the section title, which has to override
 * the Section Title heading style's neutral-700: that style is written for the
 * light bands, and its colour arrives through a zero-specificity `:where()`
 * selector, so the explicit `has-base-color` class on the heading wins cleanly
 * without touching the style file.
 *
 * The gold rule beneath the title is the Section Title style's own `:after`,
 * drawn in assets/styles/core-heading.css — it reads correctly on the dark
 * ground because it is accent-500, so it is inherited rather than restated.
 *
 * ## Trustpilot
 *
 * Embedded with `require`, not a nested `<!-- wp:pattern /-->`. A pattern
 * reference nested inside another pattern is silently dropped on front-end
 * render while still resolving under a WP-CLI `do_blocks()` test, so the CLI
 * would have reported this working. `require` inlines the markup at
 * registration and leaves trustpilot-score.php independently registered.
 * → wp-pattern-runtime-pitfalls
 *
 * The badge takes `currentColor` throughout, so it picks up the band's base
 * text without a colour variant — which is the whole reason live's three
 * `[tp_show_score color="…"]` variants collapse into one file.
 *
 * ## Not here yet
 *
 * Two pieces of the live band are deliberately absent because their assets have
 * not been ported into the theme:
 *
 *  - the repeating `home-why-choose-sd-bg-img.jpg` watermark over the brown
 *    ground, already on style.md's port list as "Port → WebP";
 *  - the "We Are Africa Tribe Member 2024" badge, which currently exists only
 *    as an upload (`WAA-Tribe-Member-Badge-2024-34-white-300x300.png`).
 *
 * Both are theme assets rather than content, so they belong in assets/images/
 * before they are referenced here.
 */

?>
<!-- wp:group {"metadata":{"name":"Why Choose Southern Destinations"},"align":"full","className":"is-style-dark-page-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-dark-page-section">

	<!-- wp:group {"metadata":{"name":"Section Header"},"className":"is-style-section-header","layout":{"type":"constrained"}} -->
	<div class="wp-block-group is-style-section-header">
		<!-- wp:heading {"textAlign":"center","level":2,"className":"is-style-section-title","textColor":"base"} -->
		<h2 class="wp-block-heading has-text-align-center is-style-section-title has-base-color has-text-color"><?php esc_html_e( 'Why choose Southern Destinations', 'sd-theme-2026' ); ?></h2>
		<!-- /wp:heading -->
	</div>
	<!-- /wp:group -->

	<!-- wp:columns {"metadata":{"name":"Value Columns"},"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|50"}}}} -->
	<div class="wp-block-columns">

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"textAlign":"center","level":3,"fontFamily":"heading","fontSize":"400","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}}} -->
			<h3 class="wp-block-heading has-text-align-center has-heading-font-family has-400-font-size" style="margin-bottom:var(--wp--preset--spacing--20);font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading)"><?php esc_html_e( 'We are Trusted', 'sd-theme-2026' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"align":"center"} -->
			<p class="has-text-align-center"><?php esc_html_e( 'We are an established DMC with a healthy balance of agent and referral business. Our offices in New York and Cape Town offer clients banking solutions and a point of contact in the traveller\'s timezone.', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"textAlign":"center","level":3,"fontFamily":"heading","fontSize":"400","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}}} -->
			<h3 class="wp-block-heading has-text-align-center has-heading-font-family has-400-font-size" style="margin-bottom:var(--wp--preset--spacing--20);font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading)"><?php esc_html_e( 'We are Passionate', 'sd-theme-2026' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"align":"center"} -->
			<p class="has-text-align-center"><?php esc_html_e( 'We love Africa and are passionate promoters of sustainable tourism that conserves animal kingdoms and uplifts communities.', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"textAlign":"center","level":3,"fontFamily":"heading","fontSize":"400","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading"},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}}}} -->
			<h3 class="wp-block-heading has-text-align-center has-heading-font-family has-400-font-size" style="margin-bottom:var(--wp--preset--spacing--20);font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading)"><?php esc_html_e( 'We offer Value', 'sd-theme-2026' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"align":"center"} -->
			<p class="has-text-align-center"><?php esc_html_e( 'Booking with us won\'t cost you more. On the contrary, our first-hand expertise will add tremendous value to your trip and the partnerships we\'ve developed over many years provide you with the best price guarantees.', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

	<!-- wp:group {"metadata":{"name":"Trustpilot"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--50)">
		<?php require __DIR__ . '/trustpilot-score.php'; ?>
	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
