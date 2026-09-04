<?php
/**
 * Title: Destination — Banner
 * Slug: sd-theme-2026/destination-banner
 * Description: The destination single's opening cover: the banner image from Tour Operator's banner_image_id meta behind the scrim, with the destination title alone over it and no tagline.
 * Categories: sd-theme-2026/tour-operator
 * Keywords: destination, banner, hero, cover, single
 * Viewport Width: 1400
 * Template Types: single
 * Post Types: wp_template
 * Inserter: false
 *
 * @package sd-theme-2026
 */

/*
 * The banner.
 *
 * The same device as the tour single, at the same 360px floor, so the two
 * Tour Operator singles open identically: `is-style-hero-banner` owns the
 * scrim and the type colours, and only the composition is here.
 *
 * The image is the destination's banner image, not its featured image. Tour
 * Operator's `Bindings::render_banner_block()` (class-bindings.php:1144)
 * swaps a cover's background for the `banner_image_id` meta whenever the
 * cover carries an `lsx/post-meta` binding on `content` — the args are only
 * a marker; the key it reads is fixed. Measured on dev: Botswana's
 * `banner_image_id` is 58727, `header-botswana.jpg`, which is the image live
 * paints into `.page-banner-image`. `useFeaturedImage` stays on underneath
 * as the fallback, so a destination with no banner image set keeps its
 * thumbnail rather than rendering an empty scrim.
 *
 * **No tagline.** The tour single carries one; this deliberately does not.
 * Live gates it on post type — `.single #lsx-banner .banner-content .tagline
 * { display: none }` with only `.single-lsx-to-tour` putting it back
 * (custom.css:1787) — which in a block theme is simply: it is in the tour
 * template and not in this one. Both destination banners measured carry the
 * `<h1>` alone. `sd/banner`'s `subtitle` key would resolve here (for a region
 * it returns the parent country's title), and that is exactly why the
 * absence is written down rather than left to look like an oversight.
 *
 * A flow layout, not constrained, so `alignwide` reaches the children
 * instead of being re-clamped to the content measure. Same reasoning as the
 * destinations archive banner, which has the note in full.
 */
?>
<!-- wp:cover {"useFeaturedImage":true,"dimRatio":0,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":360,"minHeightUnit":"px","contentPosition":"bottom center","isDark":false,"align":"full","tagName":"section","metadata":{"name":"Banner","bindings":{"content":{"source":"lsx/post-meta","args":{"key":"banner_image_id"}}}},"className":"is-style-hero-banner","style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-cover alignfull is-light has-custom-content-position is-position-bottom-center is-style-hero-banner" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);min-height:360px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container">

	<!-- wp:group {"metadata":{"name":"Banner Content"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group alignwide">

		<!-- wp:post-title {"level":1,"metadata":{"name":"Destination Title"},"className":"is-style-script-accent","fontSize":"800"} /-->

	</div>
	<!-- /wp:group -->

</div></section>
<!-- /wp:cover -->
