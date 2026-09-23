<?php
/**
 * Title: Destination — Banner
 * Slug: sd-theme-2026/destination-banner
 * Description: The destination single's opening banner — the hero banner configured for destinations: Tour Operator's banner_image_id photograph on a 400px floor with the destination title alone over it and no strapline. Over the photograph from 768px up; below it, on a neutral-200 plate, on phones.
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
 * The banner — `patterns/hero-page-banner.php`, configured for destinations.
 *
 * Required by all three destination templates (single-destination,
 * single-country, single-region), so this one file is the banner on every
 * destination page.
 *
 * ## On patterns/hero-page-banner.php since 2026-09-23
 *
 * This is that pattern's markup — the 400px floor, `dimRatio: 100` against the
 * section style's scrim, the flow-layout content group and the script-face
 * `<h1>` — so the phone stack in `style.css` ("Hero banner — the phone stack")
 * applies here as it does on the destinations archive: below 768px the
 * photograph shrinks to a 3:1 strip and the title drops onto a neutral-200
 * plate beneath it in brand-500. Measured on live 2026-09-23 at 390px on
 * /destination/botswana/ and /destination/botswana/moremi-game-reserve/: the
 * image is a strip (66px and 122px) and the `<h1>` is `#cc7f16` on the plate.
 *
 * Before this the destination banner was its own 360px, `dimRatio: 0` cover
 * with spacing-40 padding written inline. The padding now comes from
 * `is-style-hero-banner` (spacing-90) on desktop; on phones the phone stack
 * zeroes it with `!important`, so an inline value added later cannot bring
 * back a band of plate above the photograph.
 *
 * It is written out rather than `require`d because a destination makes the two
 * substitutions the hero pattern's own notes allow for:
 *
 * 1. **The image is the destination's banner image, not only its featured
 *    image.** Tour Operator's `Bindings::render_banner_block()`
 *    (class-bindings.php:1144) swaps a cover's background for the
 *    `banner_image_id` meta whenever the cover carries an `lsx/post-meta`
 *    binding on `content` — the args are only a marker; the key it reads is
 *    fixed. Its replacement `<img>` keeps the `wp-block-cover__image-background`
 *    class (`rebuild_cover_block_image()`), which is the element the phone
 *    stack targets, so the strip works on a bound banner too. Measured on dev:
 *    Botswana's `banner_image_id` is 58727, `header-botswana.jpg`, which is the
 *    image live paints into `.page-banner-image`. `useFeaturedImage` stays on
 *    underneath as the fallback, so a destination with no banner image keeps
 *    its thumbnail rather than rendering an empty plate.
 *
 * 2. **No strapline.** Live gates the tagline on post type —
 *   `.single #lsx-banner .banner-content .tagline { display: none }` with only
 *   `.single-lsx-to-tour` putting it back (custom.css:1787) — and the country
 *   and region measured above carry no `.tagline` at all, at either width. In a
 *   block theme that is simply: the paragraph is in the hero pattern and not in
 *   this one. `sd/banner`'s `subtitle` key would resolve here (for a region it
 *   returns the parent country's title), which is why the absence is written
 *   down rather than left to look like an oversight.
 */
?>
<!-- wp:cover {"useFeaturedImage":true,"dimRatio":100,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":400,"minHeightUnit":"px","contentPosition":"bottom center","align":"full","tagName":"section","metadata":{"name":"Banner","bindings":{"content":{"source":"lsx/post-meta","args":{"key":"banner_image_id"}}}},"className":"is-style-hero-banner","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"constrained"}} -->
<section class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center is-style-hero-banner" style="min-height:400px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-100 has-background-dim"></span><div class="wp-block-cover__inner-container">

	<?php
	/*
	 * Flow layout, not constrained, so `alignwide` reaches the title instead of
	 * being re-clamped to the content measure. The hero pattern has the note in
	 * full.
	 */
	?>
	<!-- wp:group {"metadata":{"name":"Banner Content"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group alignwide">

		<!-- wp:post-title {"level":1,"metadata":{"name":"Title"},"className":"is-style-script-accent","fontSize":"800"} /-->

	</div>
	<!-- /wp:group -->

</div></section>
<!-- /wp:cover -->
