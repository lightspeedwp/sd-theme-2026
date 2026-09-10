<?php
/**
 * The brand region tab strip.
 *
 * Attaches assets/styles/sd-brand-regions.css to `sd/brand-regions`, the block
 * sd-enhancements renders on a brand archive. `sd/brand-regions` is registered
 * only while sd-enhancements is active; when it is not, the block never renders
 * and the stylesheet is never printed.
 *
 * Why this is a module rather than a `core-*.css` file: the scan in
 * functions.php maps `core-<block>.css` to `core/<block>` and only core blocks
 * participate. Sheets for non-core blocks are enqueued by their own modules
 * here — the same arrangement `inc/facetwp.php` and `inc/mega-menu.php` use.
 *
 * @package sd-theme-2026
 */

namespace SdTheme2026;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the region-tab stylesheet against the block that renders it.
 *
 * `wp_enqueue_block_style()` is lazy: the stylesheet is printed only when the
 * block appears on the page, and inlined when it is small enough. Passing
 * `path` is what enables that inlining, so it is set alongside `src`.
 *
 * The block hides itself when a brand has fewer than two regions, so on those
 * archives this sheet is registered and never printed — which is the intended
 * outcome, not a gap.
 */
function enqueue_brand_regions_style() {
	$relative = 'assets/styles/sd-brand-regions.css';

	wp_enqueue_block_style(
		'sd/brand-regions',
		array(
			'handle' => 'sd-theme-2026-block-sd-brand-regions',
			'src'    => get_theme_file_uri( $relative ),
			'path'   => get_theme_file_path( $relative ),
			'ver'    => asset_version( $relative ),
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\\enqueue_brand_regions_style' );
