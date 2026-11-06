<?php
/**
 * Title: Why Choose Southern Destinations
 * Slug: sd-theme-2026/why-choose-sd
 * Description: The closing band — a section title over three value columns (Trusted, Passionate, Value) on a darkened photograph.
 * Categories: sd-theme-2026/call-to-action, sd-theme-2026/features
 * Keywords: why choose, trusted, passionate, value, cta
 * Viewport Width: 1400
 * Template Types: front-page, page
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * One band, not two.
 *
 * This file briefly had a twin — `homepage-why-choose-sd.php` — on the theory
 * that live runs two variants: a flat dark closer beneath inner pages, and a
 * photographic one on the homepage carrying the Trustpilot score and the We Are
 * Africa badge. Decision 2026-08-26: there is one band, and it is this one. The
 * flat version was the unfinished one, not a second design — its own notes said
 * the watermark and the badge were "not here yet" because the assets had not
 * been ported. They have been. The twin is deleted.
 *
 * ## What it replaces — live's `.footer-cta-section`
 *
 * On live this band is not page content. It is emitted by the child theme
 * beneath every inner page, which is why it appears verbatim on
 * /about-us/social-responsibility/ and /about-us/connect-with-us/ while being
 * absent from both pages' stored content. That distinction still matters for
 * where it goes next: it is a *template* concern, not a page-body one.
 *
 * So this file exists so the band has one definition, and it is deliberately
 * NOT pasted into the About Us page bodies. Dropping it into each page would
 * duplicate it the moment parts/footer.html or the page templates pick it up —
 * which is the correct eventual home, and the follow-up this pattern is waiting
 * on. → LS-2033
 *
 * ## Ground and colour
 *
 * Live puts the photograph in its own wrapper as an `<img
 * class="lsx-container-image has-background-dim-100 has-background-dim">` — an
 * LSX Container device, with the dim as a class on the image itself. There is
 * no block equivalent, so this uses what `core/group` supports: the photograph
 * as a background image with primary-500 as the group's background colour
 * beneath it.
 *
 * The section title takes base explicitly. It has to override the Section Title
 * heading style's neutral-700 — that style is written for the light bands and
 * its colour arrives through a zero-specificity `:where()` selector, so the
 * explicit `has-base-color` class wins cleanly without touching the style file.
 * The gold rule beneath the title is the Section Title style's own `:after`,
 * drawn in assets/styles/core-heading.css; it is accent-500 and reads correctly
 * on the dark ground, so it is inherited rather than restated.
 *
 * ## The Trustpilot score and the We Are Africa badge are in the footer now
 *
 * This band carried both marks side by side beneath the value columns, as live
 * does. At the client's request (BugHerd #21, email 2026-11-05) they moved to
 * the footer, patterns/footer.php, so they show on every page and not only on
 * the templates that carry this band. The white-wordmark Trustpilot stack went
 * with them, along with its measurements against live. Pending client approval.
 * If the move is reverted, restore the "Trustpilot and Membership" columns
 * from git history rather than rebuilding them.
 *
 * The uploads URLs are dev's, the same deliberate exception patterns/footer.php
 * documents at length, and they are written literally as core writes asset URLs.
 * The go-live deployment runs a find-and-replace over the dev host by
 * convention, so they need no code change and no indirection here.
 *
 * The three value columns are written out in full rather than looped: a pattern
 * is block markup, and core's patterns hold no loops.
 */

?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Why choose Southern Destinations"},"align":"full","className":"is-style-light-page-section","style":{"background":{"backgroundImage":{"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/07/home-why-choose-sd-bg-img.jpg","source":"file"},"backgroundSize":"cover"},"spacing":{"padding":{"top":"var:preset|spacing|100","bottom":"var:preset|spacing|100"},"blockGap":"var:preset|spacing|60"}},"backgroundColor":"primary-500","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-light-page-section has-primary-500-background-color has-background" style="padding-top:var(--wp--preset--spacing--100);padding-bottom:var(--wp--preset--spacing--100)">

	<!-- wp:heading {"textAlign":"center","className":"is-style-section-title","style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base","anchor":"h-why-choose-southern-destinations"} -->
	<h2 class="wp-block-heading has-text-align-center is-style-section-title has-base-color has-text-color has-link-color" id="h-why-choose-southern-destinations"><?php esc_html_e( 'Why choose Southern Destinations', 'sd-theme-2026' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:columns {"metadata":{"name":"Value Columns"},"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|60"}}}} -->
	<div class="wp-block-columns alignwide">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"textAlign":"center","level":3,"className":"is-style-script-accent","style":{"elements":{"link":{"color":{"text":"var:preset|color|accent-400"}}}},"textColor":"accent-400","anchor":"h-we-are-trusted"} -->
			<h3 class="wp-block-heading has-text-align-center is-style-script-accent has-accent-400-color has-text-color has-link-color" id="h-we-are-trusted"><?php esc_html_e( 'We are Trusted', 'sd-theme-2026' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base"} -->
			<p class="has-text-align-center has-base-color has-text-color has-link-color"><?php esc_html_e( 'We are an established DMC with a healthy balance of agent and referral business. Our offices in New York and Cape Town offer clients banking solutions and a point of contact in the traveller’s timezone.', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"textAlign":"center","level":3,"className":"is-style-script-accent","style":{"elements":{"link":{"color":{"text":"var:preset|color|accent-400"}}}},"textColor":"accent-400","anchor":"h-we-are-passionate"} -->
			<h3 class="wp-block-heading has-text-align-center is-style-script-accent has-accent-400-color has-text-color has-link-color" id="h-we-are-passionate"><?php esc_html_e( 'We are Passionate', 'sd-theme-2026' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base"} -->
			<p class="has-text-align-center has-base-color has-text-color has-link-color"><?php esc_html_e( 'We love Africa and are passionate promoters of sustainable tourism that conserves animal kingdoms and uplifts communities.', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"textAlign":"center","level":3,"className":"is-style-script-accent","style":{"elements":{"link":{"color":{"text":"var:preset|color|accent-400"}}}},"textColor":"accent-400","anchor":"h-we-offer-value"} -->
			<h3 class="wp-block-heading has-text-align-center is-style-script-accent has-accent-400-color has-text-color has-link-color" id="h-we-offer-value"><?php esc_html_e( 'We offer Value', 'sd-theme-2026' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base"} -->
			<p class="has-text-align-center has-base-color has-text-color has-link-color"><?php esc_html_e( 'Booking with us won’t cost you more. On the contrary, our first-hand expertise will add tremendous value to your trip and the partnerships we’ve developed over many years provide you with the best price guarantees.', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</section>
<!-- /wp:group -->
