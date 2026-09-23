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
 * run consecutively from 52466 — verified against the dev template 2026-08-26.
 * The pool is written out in full in the `sdRotatingImages` attribute below
 * rather than generated in a loop: a pattern is block markup, and it is the
 * shape the editor itself writes when the client re-picks the pool.
 *
 * ⚠️ Those IDs are dev's, and unlike a URL they cannot be fixed with a
 * search-replace. If the media is ever re-imported rather than migrated, re-pick
 * the pool in the editor; the plugin reads the attribute, not this markup. The
 * URLs are dev's too — the same deliberate exception patterns/footer.php
 * documents at length, and the go-live find-and-replace covers them.
 */
?>
<!-- wp:cover {"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider1.jpg","id":52466,"dimRatio":0,"overlayColor":"contrast","isUserOverlayColor":true,"minHeight":720,"minHeightUnit":"px","contentPosition":"center center","tagName":"section","metadata":{"name":"Homepage - Hero"},"align":"full","style":{"spacing":{"margin":{"top":"var:preset|spacing|0","bottom":"var:preset|spacing|0"}}},"layout":{"type":"constrained"},"sdRotatingImages":[{"id":52466,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider1.jpg"},{"id":52467,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider2.jpg"},{"id":52468,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider3.jpg"},{"id":52469,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider4.jpg"},{"id":52470,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider5.jpg"},{"id":52471,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider6.jpg"},{"id":52472,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider7.jpg"},{"id":52473,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider8.jpg"},{"id":52474,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider9.jpg"},{"id":52475,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider10.jpg"},{"id":52476,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider11.jpg"}],"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"small":true,"medium":true}}}}]}} -->
<section class="wp-block-cover alignfull" style="margin-top:var(--wp--preset--spacing--0);margin-bottom:var(--wp--preset--spacing--0);min-height:720px"><img class="wp-block-cover__image-background wp-image-52466" alt="" src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider1.jpg" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-contrast-background-color has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container">
	<!-- wp:group {"className":"is-style-default","style":{"spacing":{"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group is-style-default">
		<!-- wp:heading {"level":1,"align":"wide","className":"is-style-shadow-text","style":{"typography":{"textAlign":"center","fontStyle":"normal","fontWeight":"var:custom|font-weight|regular"}},"fontFamily":"accent","anchor":"h-28-years-of-crafting-extraordinary-safari-experiences"} -->
		<h1 class="wp-block-heading has-text-align-center alignwide is-style-shadow-text has-accent-font-family" id="h-28-years-of-crafting-extraordinary-safari-experiences" style="font-style:normal;font-weight:var(--wp--custom--font-weight--regular)"><?php esc_html_e( '28 years of crafting extraordinary safari experiences', 'sd-theme-2026' ); ?></h1>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"is-style-shadow-text","style":{"typography":{"textAlign":"center"}},"fontSize":"400"} -->
		<p class="has-text-align-center is-style-shadow-text has-400-font-size"><?php esc_html_e( '“The entire trip was amazing and we didn’t have to worry about a thing!”', 'sd-theme-2026' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div></section>
<!-- /wp:cover -->

<!-- wp:cover {"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider1.jpg","id":52466,"dimRatio":0,"overlayColor":"contrast","isUserOverlayColor":true,"minHeight":544,"minHeightUnit":"px","contentPosition":"center center","tagName":"section","metadata":{"name":"Homepage - Hero (tablet)"},"align":"full","style":{"spacing":{"margin":{"top":"var:preset|spacing|0","bottom":"var:preset|spacing|0"}}},"layout":{"type":"constrained"},"sdRotatingImages":[{"id":52466,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider1.jpg"},{"id":52467,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider2.jpg"},{"id":52468,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider3.jpg"},{"id":52469,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider4.jpg"},{"id":52470,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider5.jpg"},{"id":52471,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider6.jpg"},{"id":52472,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider7.jpg"},{"id":52473,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider8.jpg"},{"id":52474,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider9.jpg"},{"id":52475,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider10.jpg"},{"id":52476,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider11.jpg"}],"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"small":true,"large":true}}}}]}} -->
<section class="wp-block-cover alignfull" style="margin-top:var(--wp--preset--spacing--0);margin-bottom:var(--wp--preset--spacing--0);min-height:544px"><img class="wp-block-cover__image-background wp-image-52466" alt="" src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider1.jpg" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-contrast-background-color has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container">
	<!-- wp:group {"className":"is-style-default","style":{"spacing":{"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group is-style-default">
		<!-- wp:heading {"level":1,"align":"wide","className":"is-style-shadow-text","style":{"typography":{"textAlign":"center","fontStyle":"normal","fontWeight":"700"}},"fontFamily":"accent","fontSize":"600"} -->
		<h1 class="wp-block-heading has-text-align-center alignwide is-style-shadow-text has-accent-font-family has-600-font-size" style="font-style:normal;font-weight:700"><?php esc_html_e( '28 years of crafting extraordinary safari experiences', 'sd-theme-2026' ); ?></h1>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"className":"is-style-shadow-text","style":{"typography":{"textAlign":"center"}},"fontSize":"400"} -->
		<p class="has-text-align-center is-style-shadow-text has-400-font-size"><?php esc_html_e( '“The entire trip was amazing and we didn’t have to worry about a thing!”', 'sd-theme-2026' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div></section>
<!-- /wp:cover -->

<!-- wp:cover {"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider1-768x316.jpg","id":52466,"sizeSlug":"medium_large","dimRatio":0,"overlayColor":"contrast","isUserOverlayColor":true,"minHeight":153,"minHeightUnit":"px","contentPosition":"center center","tagName":"section","metadata":{"name":"Homepage - Hero (mobile)"},"align":"full","style":{"spacing":{"margin":{"top":"var:preset|spacing|0","bottom":"var:preset|spacing|0"}}},"layout":{"type":"constrained"},"sdRotatingImages":[{"id":52466,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider1.jpg"},{"id":52467,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider2.jpg"},{"id":52468,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider3.jpg"},{"id":52469,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider4.jpg"},{"id":52470,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider5.jpg"},{"id":52471,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider6.jpg"},{"id":52472,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider7.jpg"},{"id":52473,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider8.jpg"},{"id":52474,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider9.jpg"},{"id":52475,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider10.jpg"},{"id":52476,"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider11.jpg"}],"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"large":true,"medium":true}}}}]}} -->
<section class="wp-block-cover alignfull" style="margin-top:var(--wp--preset--spacing--0);margin-bottom:var(--wp--preset--spacing--0);min-height:153px"><img class="wp-block-cover__image-background wp-image-52466 size-medium_large" alt="" src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/10/home-slider1-768x316.jpg" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-contrast-background-color has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container">
	<!-- wp:group {"className":"is-style-default","style":{"spacing":{"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group is-style-default">
		<!-- wp:heading {"level":1,"align":"wide","className":"is-style-shadow-text","style":{"typography":{"textAlign":"center","fontStyle":"normal","fontWeight":"700"}},"fontFamily":"accent","fontSize":"500"} -->
		<h1 class="wp-block-heading has-text-align-center alignwide is-style-shadow-text has-accent-font-family has-500-font-size" style="font-style:normal;font-weight:700"><?php esc_html_e( '28 years of crafting extraordinary safari experiences', 'sd-theme-2026' ); ?></h1>
		<!-- /wp:heading -->
	</div>
	<!-- /wp:group -->
</div></section>
<!-- /wp:cover -->
