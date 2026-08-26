<?php
/**
 * Title: Homepage — Hero
 * Slug: sd-theme-2026/homepage-hero
 * Description: The homepage hero — a full-bleed 720px photograph drawn at random from the eleven-image banner pool, carrying the headline and a guest quote in shadowed type.
 * Categories: sd-theme-2026/hero, sd-theme-2026/pages
 * Keywords: hero, banner, homepage, cover, rotating, headline
 * Viewport Width: 1400
 * Block Types: core/cover
 * Template Types: front-page
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * The rotation is the plugin's, the composition is the theme's.
 *
 * Live picks one of eleven images per request in PHP — LSX Banners reading a
 * repeatable CMB2 field and calling `rand()`. It is not a slider and there is no
 * front-end JavaScript. `SD\Enhancements\RotatingBanner` ports that behaviour as
 * a single extra `core/cover` attribute, `sdRotatingImages`, so everything else
 * stays plain Cover: overlay, dim ratio, minimum height, content position,
 * alignment and inner blocks are all controls the client already knows.
 *
 * Cover's own `url`/`id` are set to the first image of the pool. That is
 * deliberate on the plugin's side — the editor previews through Cover's normal
 * rendering, the saved markup carries a real `<img>` for the filter to swap
 * rather than build, and with the plugin deactivated the hero degrades to a
 * static photograph instead of an empty overlay.
 *
 * `dimRatio: 0` with an explicit `overlayColor` looks odd but is correct: it
 * keeps the overlay element in the markup at zero opacity so the contrast can be
 * dialled up in the editor without re-adding a colour. The legibility of the
 * type comes from `is-style-shadow-text` instead, which is how live does it —
 * the headline sits directly on the photograph.
 *
 * ## The pool
 *
 * Eleven files, `uploads/2019/10/home-slider{1..11}.jpg`, whose attachment IDs
 * run consecutively from 52466 — verified against the dev template 2026-08-26 —
 * so the pool is generated rather than written out eleven times.
 *
 * ⚠️ Those IDs are dev's, and unlike a URL they cannot be fixed with a
 * search-replace. If the media is ever re-imported rather than migrated, re-pick
 * the pool in the editor; the plugin reads the attribute, not these constants.
 * The URLs are dev's too — the same deliberate exception patterns/footer.php
 * documents at length above its own `$sd_uploads`.
 */

$sd_uploads = 'https://southerndestinations.lightspeedwp.dev/wp-content/uploads/';

$sd_banner_first_id = 52466;
$sd_banner_count    = 11;
$sd_banner_pool     = array();

for ( $sd_i = 1; $sd_i <= $sd_banner_count; $sd_i++ ) {
	$sd_banner_pool[] = array(
		'id'  => $sd_banner_first_id + $sd_i - 1,
		'url' => $sd_uploads . '2019/10/home-slider' . $sd_i . '.jpg',
	);
}

$sd_banner_json  = wp_json_encode( $sd_banner_pool );
$sd_banner_lead  = $sd_banner_pool[0];
$sd_hero_heading = __( '28 years of crafting extraordinary safari experiences', 'sd-theme-2026' );
$sd_hero_quote   = __( '“The entire trip was amazing and we didn’t have to worry about a thing!”', 'sd-theme-2026' );
?>
<!-- wp:cover {"url":"<?php echo esc_url( $sd_banner_lead['url'] ); ?>","id":<?php echo (int) $sd_banner_lead['id']; ?>,"dimRatio":0,"overlayColor":"contrast","isUserOverlayColor":true,"minHeight":720,"minHeightUnit":"px","contentPosition":"center center","tagName":"section","metadata":{"name":"Homepage - Hero"},"align":"full","style":{"spacing":{"margin":{"top":"var:preset|spacing|0","bottom":"var:preset|spacing|0"}}},"layout":{"type":"constrained"},"sdRotatingImages":<?php echo $sd_banner_json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_json_encode() of locally-built IDs and URLs, inside a block-comment attribute. ?>} -->
<section class="wp-block-cover alignfull" style="margin-top:var(--wp--preset--spacing--0);margin-bottom:var(--wp--preset--spacing--0);min-height:720px"><img class="wp-block-cover__image-background wp-image-<?php echo (int) $sd_banner_lead['id']; ?>" alt="" src="<?php echo esc_url( $sd_banner_lead['url'] ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-contrast-background-color has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container">
	<!-- wp:group {"className":"is-style-default","style":{"spacing":{"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group is-style-default">
		<!-- wp:heading {"level":1,"align":"wide","className":"is-style-shadow-text","style":{"typography":{"textAlign":"center","fontStyle":"normal","fontWeight":"700"}},"fontFamily":"accent","anchor":"h-28-years-of-crafting-extraordinary-safari-experiences"} -->
		<h1 class="wp-block-heading has-text-align-center alignwide is-style-shadow-text has-accent-font-family" id="h-28-years-of-crafting-extraordinary-safari-experiences" style="font-style:normal;font-weight:700"><?php echo esc_html( $sd_hero_heading ); ?></h1>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"is-style-shadow-text","style":{"typography":{"textAlign":"center"}},"fontSize":"400"} -->
		<p class="has-text-align-center is-style-shadow-text has-400-font-size"><?php echo esc_html( $sd_hero_quote ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div></section>
<!-- /wp:cover -->
