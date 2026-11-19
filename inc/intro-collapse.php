<?php
/**
 * The Read more collapse on a term description.
 *
 * Attaches assets/js/intro-collapse.js to `core/term-description`, so the brand
 * archive's story can be clamped behind a toggle. The stylesheet is not
 * enqueued here: `assets/styles/core-term-description.css` is picked up by
 * `enqueue_custom_block_styles()`' `core-*` scan in functions.php, which is the
 * convention for core blocks. Only the script and its localisation need a
 * module.
 *
 * Why the behaviour is a script at all, and the progressive-enhancement
 * contract it keeps, are documented at the head of the JavaScript file.
 *
 * @package sd-theme-2026
 */

namespace SdTheme2026;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue the collapse script when a term description is rendered.
 *
 * There is no script counterpart to `wp_enqueue_block_style()`, so the enqueue
 * is hooked to the block's own render and runs at most once per request — the
 * same lazy contract `inc/facetwp.php` uses for the filter dropdowns. A page
 * with no term description on it never loads the file.
 *
 * `render_block_core/term-description` fires while the page body is being
 * built, which is before the footer scripts are printed, so an `in_footer`
 * enqueue from here is still in time.
 *
 * The script is a no-op on any page whose description is short enough not to
 * overflow, and on any page with no `.sd-intro-collapse` container at all —
 * `core/term-description` appears on several archives and only the brand
 * archive wraps it. Gating the enqueue on the container instead would mean
 * parsing the rendered markup for a class the pattern puts on an *ancestor*
 * block, which is not available at this filter.
 *
 * @param string $block_content The block's rendered markup, returned unchanged.
 * @return string
 */
function enqueue_intro_collapse_script( $block_content ) {
	$relative = 'assets/js/intro-collapse.js';
	$handle   = 'sd-theme-2026-intro-collapse';

	wp_enqueue_script(
		$handle,
		get_theme_file_uri( $relative ),
		array(),
		asset_version( $relative ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	/*
	 * The expanded label cannot travel in the pattern with the collapsed one.
	 * `core/button` saves a fixed `<a class="wp-block-button__link …">`, so a
	 * `data-label-expanded` attribute written into the pattern's markup would
	 * not match what the block's save function produces — a block-validation
	 * error the moment the template is opened in the editor. Localising it is
	 * the way to keep the string translatable without breaking the block.
	 */
	wp_localize_script(
		$handle,
		'sdIntroCollapse',
		array(
			'expandedLabel' => __( 'Read less', 'sd-theme-2026' ),
		)
	);

	return $block_content;
}
add_filter( 'render_block_core/term-description', __NAMESPACE__ . '\\enqueue_intro_collapse_script' );
