<?php
/**
 * The Trustpilot review carousel on the team single.
 *
 * Attaches assets/js/review-slider.js to `sd/trustpilot-reviews`, so the review
 * row slides the way the Tours, Destinations and Blog shelves below it do. The
 * stylesheet is not enqueued here: the slide gutters and the equal-height track
 * live in `assets/styles/core-group.css`, which `enqueue_custom_block_styles()`
 * picks up through its `core-*` scan in functions.php — the carousel's frame is
 * a `core/group`, so the rules belong with the rest of the slider-frame CSS
 * rather than in a sheet of their own.
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
 * Enqueue the review carousel script when the reviews block is rendered.
 *
 * There is no script counterpart to `wp_enqueue_block_style()`, so the enqueue
 * is hooked to the block's own render and runs at most once per request — the
 * same lazy contract `inc/intro-collapse.php` and `inc/facetwp.php` use. A page
 * with no review row on it never loads the file.
 *
 * `render_block_sd/trustpilot-reviews` fires while the page body is being
 * built, which is before the footer scripts are printed, so an `in_footer`
 * enqueue from here is still in time.
 *
 * `slick` is Tour Operator's handle, registered in
 * `tour-operator/includes/classes/legacy/class-frontend.php:95`. Declaring it
 * as a dependency orders this file after the vendor and after jQuery; where
 * Tour Operator is inactive the handle is simply absent and the script's own
 * guard leaves the authored grid standing. `defer` is deliberately not used —
 * the vendor and TO's own `custom.js` are both plain footer scripts, and
 * deferring only this one would order it against them for no gain.
 *
 * The block renders nothing at all when its cache is empty, in which case this
 * filter does not fire and no script is enqueued.
 *
 * @param string $block_content The block's rendered markup, returned unchanged.
 * @return string
 */
function enqueue_review_slider_script( $block_content ) {
	$relative = 'assets/js/review-slider.js';

	wp_enqueue_script(
		'sd-theme-2026-review-slider',
		get_theme_file_uri( $relative ),
		array( 'jquery', 'slick' ),
		asset_version( $relative ),
		array(
			'in_footer' => true,
		)
	);

	return $block_content;
}
add_filter( 'render_block_sd/trustpilot-reviews', __NAMESPACE__ . '\\enqueue_review_slider_script' );
