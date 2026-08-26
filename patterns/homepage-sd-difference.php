<?php
/**
 * Title: Homepage — The Southern Destinations Difference
 * Slug: sd-theme-2026/homepage-sd-difference
 * Description: The "The Southern Destinations difference" band — a centred section title over Trustpilot's own TrustBox review carousel, as the live homepage runs it.
 * Categories: sd-theme-2026/testimonial, sd-theme-2026/features
 * Keywords: trustpilot, reviews, trustbox, carousel, testimonial, difference
 * Viewport Width: 1400
 * Template Types: front-page
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * Why this band is a third-party embed and patterns/trustpilot-score.php is not.
 *
 * The theme has a Trustpilot component already — patterns/trustpilot-score.php,
 * which reads the score server-side through the `sd/trustpilot` binding and
 * draws the badge with the theme's own tokens, no third-party JavaScript. That
 * remains the right component wherever the *score* is what's wanted, which is
 * the header utility bar, the safari expert panel, and the "Why choose Southern
 * Destinations" band.
 *
 * This band is the one place live shows something else: Trustpilot's **TrustBox
 * carousel** of real, attributed review cards — template
 * `53aa8912dec7e10d38f59f36`, measured on the live homepage 2026-08-26. There is
 * no server-side equivalent. The API returns review text; it does not return
 * Trustpilot's rendered and attributed card, and redrawing that ourselves would
 * be both a redesign and a misrepresentation of their attribution. So the widget
 * is the widget.
 *
 * ## `core/html`, and the script that finds it
 *
 * TrustBox is addressed entirely through `data-*` attributes and no core block
 * can emit those, so the embed is a `core/html` block. The bootstrap that
 * renders into it is registered — but deliberately *not* enqueued site-wide — by
 * `SD\Enhancements\Trustpilot::register_trustbox_script()`; a companion filter
 * on `render_block_core/html` enqueues it only on a render that actually
 * contains `trustpilot-widget`, so the third-party request is scoped to this
 * page rather than added to the whole site. Loading it is behaviour, which is
 * why it lives in the plugin and not in this theme.
 *
 * With the plugin deactivated, or with JavaScript off, the widget degrades to
 * the plain Trustpilot link inside it — which is why that link is written out
 * rather than left to the script.
 *
 * ## The business-unit id
 *
 * Read through `sd_enh_trustpilot_business_unit` so a staging unit set for the
 * plugin applies here too, with the live id as the fallback for when the plugin
 * is absent. It is not a credential — it identifies the company in public
 * Trustpilot embeds, and the plugin's class docblock says so explicitly. The
 * module's *API key* is a credential and never reaches the browser; nothing on
 * this page touches it.
 */

$sd_tp_business_unit = (string) apply_filters( 'sd_enh_trustpilot_business_unit', '564399480000ff0005856b81' );
$sd_tp_template_id   = '53aa8912dec7e10d38f59f36';
$sd_tp_review_url    = 'https://uk.trustpilot.com/review/southerndestinations.com';
?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Homepage - The SD difference"},"align":"full","style":{"spacing":{"blockGap":"var:preset|spacing|40","padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)">
	<!-- wp:heading {"textAlign":"center","className":"is-style-section-title","anchor":"h-the-southern-destinations-difference"} -->
	<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-the-southern-destinations-difference"><?php echo esc_html__( 'The Southern Destinations difference', 'sd-theme-2026' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:html -->
	<div class="trustpilot-widget" data-locale="en-GB" data-template-id="<?php echo esc_attr( $sd_tp_template_id ); ?>" data-businessunit-id="<?php echo esc_attr( $sd_tp_business_unit ); ?>" data-style-height="140px" data-style-width="100%" data-theme="light" data-stars="1,2,3,4,5" data-review-languages="en">
		<a href="<?php echo esc_url( $sd_tp_review_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html__( 'Read our reviews on Trustpilot', 'sd-theme-2026' ); ?></a>
	</div>
	<!-- /wp:html -->
</section>
<!-- /wp:group -->
