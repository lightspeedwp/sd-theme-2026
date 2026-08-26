<?php
/**
 * Title: Homepage — Why Choose Southern Destinations
 * Slug: sd-theme-2026/homepage-why-choose-sd
 * Description: The homepage's darkened photograph band — a centred section title over three value columns (Trusted, Passionate, Value), with the Trustpilot score and the We Are Africa membership badge beneath.
 * Categories: sd-theme-2026/features, sd-theme-2026/testimonial
 * Keywords: why choose, trusted, passionate, value, trustpilot, badge, homepage
 * Viewport Width: 1400
 * Template Types: front-page
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * Not the same band as patterns/why-choose-sd.php.
 *
 * That one is the *inner page* closer — the child theme emits it beneath every
 * inner page, so it is a template concern and it inherits the Dark Page Section
 * ground. This one is homepage content: the same three values, but over a
 * photograph (`uploads/2019/07/home-why-choose-sd-bg-img.jpg`) with a full dim,
 * and it carries two extra things the inner-page version does not — the
 * Trustpilot score and the We Are Africa badge, side by side, beneath the
 * columns. Two patterns rather than one because they sit in different places for
 * different reasons and only one of them is page content.
 *
 * ## How live darkens the photograph, and what is done instead
 *
 * Live puts the image in its own wrapper as an `<img class="lsx-container-image
 * has-background-dim-100 has-background-dim">` — an LSX Container device, with
 * the dim as a class on the image itself. There is no block equivalent, so this
 * uses what `core/group` actually supports: the photograph as a background image
 * with `primary-500` as the group's background colour beneath it. That is the
 * shape the dev build already used and it reads the same.
 *
 * ## The Trustpilot score is the existing pattern, referenced
 *
 * Live's `#tb-horizon-review` — the band word, the Trustpilot mark, the star
 * tile and the TrustScore line — is already built as
 * patterns/trustpilot-score.php, reading the live figures through the
 * `sd/trustpilot` binding. It is referenced rather than restated. (The dev build
 * had an `sd/trustpilot-reviews` block here instead, which renders review cards
 * rather than the score; live shows the score, so this follows live.)
 *
 * Nested `wp:pattern` renders server-side through
 * `render_block_core_pattern()` (wp-includes/blocks/pattern.php:33), verified
 * locally 2026-08-26.
 *
 * The uploads URLs are dev's — the same deliberate exception documented above
 * `$sd_uploads` in patterns/footer.php. ⚠️ Rewrite at go-live.
 */

$sd_uploads = 'https://southerndestinations.lightspeedwp.dev/wp-content/uploads/';

$sd_bg_src    = $sd_uploads . '2019/07/home-why-choose-sd-bg-img.jpg';
$sd_badge_src = $sd_uploads . '2024/02/WAA-Tribe-Member-Badge-2024-34-white-300x300.png';

$sd_values = array(
	array(
		'heading' => __( 'We are Trusted', 'sd-theme-2026' ),
		'anchor'  => 'h-we-are-trusted',
		'body'    => __( 'We are an established DMC with a healthy balance of agent and referral business. Our offices in New York and Cape Town offer clients banking solutions and a point of contact in the traveller’s timezone.', 'sd-theme-2026' ),
	),
	array(
		'heading' => __( 'We are Passionate', 'sd-theme-2026' ),
		'anchor'  => 'h-we-are-passionate',
		'body'    => __( 'We love Africa and are passionate promoters of sustainable tourism that conserves animal kingdoms and uplifts communities.', 'sd-theme-2026' ),
	),
	array(
		'heading' => __( 'We offer Value', 'sd-theme-2026' ),
		'anchor'  => 'h-we-offer-value',
		'body'    => __( 'Booking with us won’t cost you more. On the contrary, our first-hand expertise will add tremendous value to your trip and the partnerships we’ve developed over many years provide you with the best price guarantees', 'sd-theme-2026' ),
	),
);
?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Homepage - Why choose Southern Destinations"},"align":"full","className":"is-style-light-page-section","style":{"background":{"backgroundImage":{"url":"<?php echo esc_url( $sd_bg_src ); ?>","source":"file"},"backgroundSize":"cover"},"spacing":{"blockGap":"var:preset|spacing|60"}},"backgroundColor":"primary-500","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-light-page-section has-primary-500-background-color has-background">
	<!-- wp:heading {"textAlign":"center","className":"is-style-section-title","style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base","anchor":"h-why-choose-southern-destinations"} -->
	<h2 class="wp-block-heading has-text-align-center is-style-section-title has-base-color has-text-color has-link-color" id="h-why-choose-southern-destinations"><?php echo esc_html__( 'Why choose Southern Destinations', 'sd-theme-2026' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|60"}}}} -->
	<div class="wp-block-columns alignwide">
		<?php foreach ( $sd_values as $sd_value ) : ?>
			<!-- wp:column -->
			<div class="wp-block-column">
				<!-- wp:heading {"textAlign":"center","level":3,"className":"is-style-script-accent","style":{"elements":{"link":{"color":{"text":"var:preset|color|accent-400"}}}},"textColor":"accent-400","anchor":"<?php echo esc_attr( $sd_value['anchor'] ); ?>"} -->
				<h3 class="wp-block-heading has-text-align-center is-style-script-accent has-accent-400-color has-text-color has-link-color" id="<?php echo esc_attr( $sd_value['anchor'] ); ?>"><?php echo esc_html( $sd_value['heading'] ); ?></h3>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base"} -->
				<p class="has-text-align-center has-base-color has-text-color has-link-color"><?php echo esc_html( $sd_value['body'] ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:column -->
		<?php endforeach; ?>
	</div>
	<!-- /wp:columns -->

	<!-- wp:columns {"verticalAlignment":"center","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|60"}}}} -->
	<div class="wp-block-columns alignwide are-vertically-aligned-center">
		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:pattern {"slug":"sd-theme-2026/trustpilot-score"} /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:image {"width":"131px","sizeSlug":"medium","linkDestination":"custom","align":"center"} -->
			<figure class="wp-block-image aligncenter size-medium is-resized"><a href="https://www.weareafricatravel.com/" target="_blank" rel="noreferrer noopener"><img src="<?php echo esc_url( $sd_badge_src ); ?>" alt="<?php esc_attr_e( 'We Are Africa — 2024 Tribe Member', 'sd-theme-2026' ); ?>" style="width:131px"/></a></figure>
			<!-- /wp:image -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</section>
<!-- /wp:group -->
