<?php
/**
 * Title: Why Choose Southern Destinations
 * Slug: sd-theme-2026/why-choose-sd
 * Description: The closing band — a section title over three value columns (Trusted, Passionate, Value) on a darkened photograph, with the Trustpilot score and the We Are Africa membership badge side by side beneath.
 * Categories: sd-theme-2026/call-to-action, sd-theme-2026/features, sd-theme-2026/testimonial
 * Keywords: why choose, trusted, passionate, value, cta, trustpilot, badge, we are africa
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
 * ## Trustpilot is written out here, not shared with trustpilot-score.php
 *
 * This band used to `require` patterns/trustpilot-score.php — the theme-wide
 * convention for embedding one pattern in another, because a `<!-- wp:pattern
 * /-->` nested inside a pattern is dropped on front-end render while still
 * resolving under a WP-CLI `do_blocks()` test. → wp-pattern-runtime-pitfalls
 *
 * It no longer does, and the reason is not colour. trustpilot-score.php assumed
 * the mark inherits `currentColor` and that live's three `[tp_show_score
 * color="…"]` variants therefore collapse into one file. Measured on live
 * 2026-08-27, they do not: this placement loads a different SVG — white
 * wordmark, green star — stacks its three parts instead of running them in a
 * row, and drops the rating word. The full comparison sits on the badge below.
 *
 * The shared pattern is untouched and still serves safari-expert.php.
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
<!-- wp:group {"tagName":"section","metadata":{"name":"Why choose Southern Destinations"},"align":"full","className":"is-style-light-page-section","style":{"background":{"backgroundImage":{"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/07/home-why-choose-sd-bg-img.jpg","source":"file"},"backgroundSize":"cover"},"spacing":{"blockGap":"var:preset|spacing|60"}},"backgroundColor":"primary-500","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-light-page-section has-primary-500-background-color has-background">

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

	<!-- wp:columns {"metadata":{"name":"Trustpilot and Membership"},"verticalAlignment":"center","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|60"}}}} -->
	<div class="wp-block-columns alignwide are-vertically-aligned-center">

		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<?php
			/*
			 * The badge is written out here rather than `require`-ing
			 * patterns/trustpilot-score.php, which every other placement still uses.
			 *
			 * Measured on live 2026-08-27 — `#tb-horizon-review.tb-color-white_green`
			 * in `.footer-cta-section`, against `.tb-color-black` in the expert panel.
			 * This placement is a different component, not a colour variant of one:
			 *
			 *   - the mark is `tp-logo-white-green.svg`, a *different file* — white
			 *     wordmark, green star. The shared badge's `trustpilot-logo.svg` is
			 *     #191919 and reads as a hole in this photograph. Neither is
			 *     `currentColor`, so no amount of inherited colour fixes it, which is
			 *     what trustpilot-score.php's "no colour variants" note assumed.
			 *   - it stacks: mark over stars over the score line. The shared badge is
			 *     a single row.
			 *   - `.tp-wording` is `display:none` sitewide in the child theme
			 *     (assets/css/partials/_cta.scss:731), so "Excellent" is authored
			 *     nowhere here — live never shows it, at any width or colour.
			 *
			 * Live's own desktop rendering runs the three parts in one row and lets
			 * the score wrap; the stack is what it collapses to, and is the intended
			 * arrangement for this column. Decision 2026-08-27.
			 *
			 * Everything variable still arrives through the `sd/trustpilot` binding
			 * source, and the fallbacks behave exactly as trustpilot-score.php
			 * documents: the two number paragraphs are authored empty so a null
			 * binding prints nothing, and the star tile falls back to the grey
			 * `stars-0.svg` rather than a five-star one.
			 */
			?>
			<!-- wp:group {"metadata":{"name":"Trustpilot Score"},"className":"sd-trustpilot","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
			<div class="wp-block-group sd-trustpilot">

				<?php /* The mark. Static — theme asset, constant URL — and the one link in the badge. */ ?>
				<!-- wp:image {"width":"160px","sizeSlug":"full","linkDestination":"custom","className":"sd-trustpilot__logo"} -->
				<figure class="wp-block-image size-full is-resized sd-trustpilot__logo"><a href="https://www.trustpilot.com/review/southerndestinations.com" target="_blank" rel="noreferrer noopener"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/trustpilot/trustpilot-logo-white-green.svg' ) ); ?>" alt="<?php esc_attr_e( 'Trustpilot', 'sd-theme-2026' ); ?>" style="width:160px;height:auto"/></a></figure>
				<!-- /wp:image -->

				<?php /* The star tile. Bound on `url`; decorative, because the rating is in the line below it. */ ?>
				<!-- wp:image {"width":"200px","sizeSlug":"full","className":"sd-trustpilot__stars","metadata":{"name":"Star rating","bindings":{"url":{"source":"sd/trustpilot","args":{"key":"stars_image"}}}}} -->
				<figure class="wp-block-image size-full is-resized sd-trustpilot__stars"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/trustpilot/stars/stars-0.svg' ) ); ?>" alt="" style="width:200px;height:auto"/></figure>
				<!-- /wp:image -->

				<?php
				/*
				 * The TrustScore line — one row, as live sets it. Two paragraphs
				 * rather than one with a bound span, because a binding replaces a
				 * block's whole `content`, so the static words travel as the
				 * source's `prefix` and `suffix` args. The spaces sit here, outside
				 * the translation calls, so a translator cannot drop the one thing
				 * holding the words apart.
				 *
				 * Both take base explicitly, for the same reason the section title
				 * does: inherited, they pick up the Light Page Section's neutral-700
				 * and are barely legible on the photograph.
				 */
				?>
				<!-- wp:group {"metadata":{"name":"TrustScore line"},"className":"sd-trustpilot__line","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
				<div class="wp-block-group sd-trustpilot__line">

					<!-- wp:paragraph {"metadata":{"name":"TrustScore","bindings":{"content":{"source":"sd/trustpilot","args":{"key":"score","prefix":"<?php echo esc_attr_x( 'TrustScore', 'precedes the Trustpilot score figure', 'sd-theme-2026' ); ?> ","suffix":" |"}}}},"className":"sd-trustpilot__score","textColor":"base","fontSize":"100"} -->
					<p class="sd-trustpilot__score has-base-color has-text-color has-100-font-size"></p>
					<!-- /wp:paragraph -->

					<!-- wp:paragraph {"metadata":{"name":"Review count","bindings":{"content":{"source":"sd/trustpilot","args":{"key":"count","suffix":" <?php echo esc_attr_x( 'reviews', 'follows the Trustpilot review count', 'sd-theme-2026' ); ?>"}}}},"className":"sd-trustpilot__count","textColor":"base","fontSize":"100"} -->
					<p class="sd-trustpilot__count has-base-color has-text-color has-100-font-size"></p>
					<!-- /wp:paragraph -->

				</div>
				<!-- /wp:group -->

			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:image {"width":"185px","height":"auto","sizeSlug":"medium","linkDestination":"custom","align":"center"} -->
			<figure class="wp-block-image aligncenter size-medium is-resized"><a href="https://www.weareafricatravel.com/" target="_blank" rel="noreferrer noopener"><img src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2024/02/WAA-Tribe-Member-Badge-2024-34-white-300x300.png" alt="<?php esc_attr_e( 'We Are Africa — 2024 Tribe Member', 'sd-theme-2026' ); ?>" style="width:185px;height:auto"/></a></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</section>
<!-- /wp:group -->
