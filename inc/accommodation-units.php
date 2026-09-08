<?php
/**
 * Accommodation units band — the Read more collapse.
 *
 * Tour Operator's `lsx/accommodation-units` binding repeats
 * `patterns/accommodation-unit.php` once per unit and fills the description
 * from the accommodation's own WYSIWYG value. The card carries a
 * `core/read-more` beneath that description, exactly as Tour Operator's own
 * `patterns/itinerary-list.php` does — but the plugin only wires two collapses
 * and neither reaches a unit:
 *
 * - `set_read_more()` acts on a `.wp-block-read-more` whose parent
 *   `.wp-block-group` holds a `.wp-block-post-content`. A unit's description is
 *   a `.unit-description` div, so the guard fails.
 * - `set_read_more_itinerary()` is scoped to `.lsx-itinerary-wrapper`.
 *
 * Both measured in `tour-operator/build/custom.js` against Tour Operator 2.2 on
 * 2026-09-04. The plugin *does* bind a `preventDefault()` click handler to every
 * `.single-tour-operator .wp-block-read-more`, so without this module the unit's
 * link is not merely unwired — it is inert.
 *
 * **Why this is theme work.** The markup is the theme's pattern and the collapse
 * is presentation of it: deactivate the theme and there is no units band to
 * collapse. It is the same class of module as `inc/facetwp.php` — behaviour glue
 * for third-party block markup that no `theme.json` or `styles/**` partial can
 * reach. → inc/README.md
 *
 * ⚠️ **The real home for this is upstream.** Extending Tour Operator's own
 * `set_read_more_itinerary()` to `.lsx-units-wrapper` / `.unit-description`
 * would serve every TO site and delete this file. Raised as an upstream
 * candidate; this module is the interim.
 *
 * @package sd-theme-2026
 */

namespace SdTheme2026;

/**
 * Enqueue the units read-more script when the band is on the page.
 *
 * Matched on the band's own `lsx-units-wrapper` class rather than on the
 * rendered markup, so the check is one `strpos` over a short attribute for every
 * `core/group` on the page instead of over its whole subtree — and the file is
 * never requested on a page with no units band. An accommodation with no units
 * has the band removed by Tour Operator's `maybe_hide_varitaion()`, which runs
 * on the same filter, so the script does not load there either.
 *
 * @param string $block_content The block's rendered markup, returned unchanged.
 * @param array  $block         The parsed block.
 * @return string
 */
function enqueue_units_read_more_script( $block_content, $block ) {
	$class_name = isset( $block['attrs']['className'] ) ? $block['attrs']['className'] : '';

	if ( false === strpos( $class_name, 'lsx-units-wrapper' ) ) {
		return $block_content;
	}

	$relative = 'assets/js/accommodation-units-read-more.js';

	wp_enqueue_script(
		'sd-theme-2026-accommodation-units-read-more',
		get_theme_file_uri( $relative ),
		array(),
		asset_version( $relative ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_localize_script(
		'sd-theme-2026-accommodation-units-read-more',
		'sdUnitsReadMore',
		array(
			'readLess' => __( 'Read less', 'sd-theme-2026' ),
		)
	);

	return $block_content;
}
add_filter( 'render_block_core/group', __NAMESPACE__ . '\\enqueue_units_read_more_script', 10, 2 );
