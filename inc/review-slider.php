<?php
/**
 * The Trustpilot review carousel on the team single.
 *
 * Attaches assets/js/review-slider.js to the page when Tour Operator's own
 * front-end script is there, so the review row slides the way the Tours,
 * Destinations and Blog shelves below it do. The stylesheet is not enqueued
 * here: the slide gutters and the equal-height track live in
 * `assets/styles/core-group.css`, which `enqueue_custom_block_styles()` picks up
 * through its `core-*` scan in functions.php — the carousel's frame is a
 * `core/group`, so the rules belong with the rest of the slider-frame CSS.
 *
 * Why the behaviour is a script at all, why it is not Tour Operator's own
 * initialiser, and what it falls back to, are documented at the head of the
 * JavaScript file.
 *
 * @package sd-theme-2026
 */

namespace SdTheme2026;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue the review carousel script, after Tour Operator's own.
 *
 * ⚠️ **Never name `slick` as a dependency here, and do not move this back to a
 * lazy `render_block` enqueue.** Both together broke every slider on the team
 * single on dev, 2026-09-16, and the mechanism is not obvious:
 *
 * 1. `WP_Dependencies::query( $handle, 'queue' )` — what `wp_script_is()` calls
 *    — does not only test the queue array. It falls through to
 *    `recurse_deps()`, so it answers **true for any handle that is a dependency
 *    of something queued**, registered or not.
 * 2. Tour Operator guards its own vendor enqueue with exactly that question:
 *    `$has_slick = wp_script_is( 'slick', 'queue' )`
 *    (`tour-operator/includes/classes/legacy/class-frontend.php:81`), on
 *    `wp_enqueue_scripts` at priority **1**.
 * 3. A `render_block` enqueue naming `slick` can land *before* that, because
 *    something on the page renders block content during `wp_head` — an SEO
 *    plugin building its description and `og:` tags will do it. TO then sees
 *    `$has_slick === true`, registers neither `slick` nor `slick-lightbox` nor
 *    their stylesheets, and enqueues `tour-operator-script` against two handles
 *    that do not exist. WordPress drops it silently at print time.
 * 4. Everything downstream goes with it: TO's `custom.js` and so every shelf
 *    slider on the page, `sd-enhancements`' `to-slider.js` (which gates on
 *    `tour-operator-script`), and this script too.
 *
 * So the dependency is `tour-operator-script`, which already depends on Slick
 * and is a handle nobody guards on, and the hook is `wp_enqueue_scripts` at a
 * priority below TO's rather than a block render. That is the shape
 * `sd-enhancements/modules/to-slider.php` uses for the same reason, and it is
 * proven on this site.
 *
 * The gate is TO's script rather than the post type: without it there is no
 * Slick to initialise, and with it the file is a few hundred bytes that does
 * nothing on a page carrying no review row.
 *
 * @return void
 */
function enqueue_review_slider_script() {
	// Priority 20 puts this after Tour Operator's own enqueue at 1, so the
	// question below is answerable. Footer scripts are not printed until
	// `wp_footer`, so this is still in time.
	if ( ! wp_script_is( 'tour-operator-script', 'enqueued' ) ) {
		return;
	}

	$relative = 'assets/js/review-slider.js';

	wp_enqueue_script(
		'sd-theme-2026-review-slider',
		get_theme_file_uri( $relative ),
		array( 'jquery', 'tour-operator-script' ),
		asset_version( $relative ),
		array(
			'in_footer' => true,
		)
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_review_slider_script', 20 );
