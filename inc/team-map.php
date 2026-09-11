<?php
/**
 * The team member map's plate label.
 *
 * Attaches assets/styles/sd-team-map.css to `sd/team-map`, the block
 * sd-enhancements renders on a team single. `sd/team-map` is registered only
 * while sd-enhancements is active; when it is not, the block never renders and
 * the stylesheet is never printed.
 *
 * Why this is a module rather than a `core-*.css` file: the scan in
 * functions.php maps `core-<block>.css` to `core/<block>` and only core blocks
 * participate. Sheets for non-core blocks are enqueued by their own modules
 * here — the same arrangement `inc/brand-regions.php`, `inc/facetwp.php` and
 * `inc/mega-menu.php` use.
 *
 * Why not theme.json: the target is one element *inside* the block, addressed
 * by its own class. `styles.blocks` carries structured properties against the
 * block's wrapper and `elements.link` would reach every `<a>` in it — including
 * the ones Google writes into the marker info windows once the map has drawn.
 * The plate label needs a selector, so it gets a stylesheet.
 *
 * @package sd-theme-2026
 */

namespace SdTheme2026;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the plate-label stylesheet against the block that renders it.
 *
 * `wp_enqueue_block_style()` is lazy: the stylesheet is printed only when the
 * block appears on the page, and inlined when it is small enough. Passing
 * `path` is what enables that inlining, so it is set alongside `src`.
 *
 * The block renders nothing when the member has no plottable connection, when
 * maps are switched off in Tour Operator's settings, or when there is no Google
 * Maps API key — so on those singles this sheet is registered and never
 * printed, which is the intended outcome rather than a gap.
 */
function enqueue_team_map_style() {
	$relative = 'assets/styles/sd-team-map.css';

	wp_enqueue_block_style(
		'sd/team-map',
		array(
			'handle' => 'sd-theme-2026-block-sd-team-map',
			'src'    => get_theme_file_uri( $relative ),
			'path'   => get_theme_file_path( $relative ),
			'ver'    => asset_version( $relative ),
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\\enqueue_team_map_style' );
