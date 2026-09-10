<?php
/**
 * FacetWP facet styling.
 *
 * Attaches assets/styles/facetwp-facets.css and assets/js/search-filters.js to
 * the `facetwp/facet` block, so both load only on the templates that render a
 * facet — today that is templates/taxonomy-accommodation-type.html.
 *
 * ## Why this needs a module at all
 *
 * FacetWP renders a bare `<div class="facetwp-facet facetwp-facet-NAME
 * facetwp-type-TYPE">` from `facetwp/includes/class-display.php:79` and fills it
 * from JS. It never calls `get_block_wrapper_attributes()` on that div, so a
 * `theme.json` `styles.blocks` entry would compile against a selector the
 * markup does not carry, and a `styles/**` partial has no element to hang a
 * variation on. The plugin's own classes are the only handle, so the rules have
 * to be authored CSS — and three of the things they do (`:hover` flips,
 * `content:` glyphs, a `@media` query) are things a block-style `css` field
 * provably cannot express. The stylesheet's header sets that out in full.
 *
 * `enqueue_custom_block_styles()` in functions.php globs `core-*.css` only, so a
 * non-core block's stylesheet is registered by its own module here — the
 * arrangement functions.php documents and inc/mega-menu.php and
 * inc/yoast-breadcrumbs.php already follow.
 *
 * ## Why this belongs in the theme
 *
 * It is presentation and nothing else: the facets' colours, type and the
 * fold affordance. What the facets *are* — their names, types, data sources
 * and operators — is FacetWP configuration in `wp_options.facetwp_settings`,
 * and the search engine behind the keyword facet is SearchWP's. None of that is
 * here, and none of it should be: deactivating this theme must leave the search
 * working, just unstyled.
 *
 * `facetwp/facet` is registered only while FacetWP Blocks (Beta) is active; when
 * it is not, the block never renders and neither asset is printed.
 *
 * @package sd-theme-2026
 */

namespace SdTheme2026;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the facet stylesheet against the block that renders it.
 *
 * `wp_enqueue_block_style()` is lazy: the stylesheet is printed only when the
 * block appears on the page, and inlined when it is small enough. Passing
 * `path` is what enables that inlining, so it is set alongside `src`.
 */
function enqueue_facetwp_facet_style() {
	$relative = 'assets/styles/facetwp-facets.css';

	wp_enqueue_block_style(
		'facetwp/facet',
		array(
			'handle' => 'sd-theme-2026-block-facetwp-facet',
			'src'    => get_theme_file_uri( $relative ),
			'path'   => get_theme_file_path( $relative ),
			'ver'    => asset_version( $relative ),
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\\enqueue_facetwp_facet_style' );

/**
 * Enqueue the fold script when a facet is rendered.
 *
 * There is no script counterpart to `wp_enqueue_block_style()`, so the enqueue
 * is hooked to the block's own render and runs at most once per request. That
 * keeps the same lazy contract as the stylesheet — a page with no facet on it
 * never loads the file.
 *
 * `render_block_facetwp/facet` fires while the page body is being built, which
 * is before the footer scripts are printed, so an `in_footer` enqueue from here
 * is still in time.
 *
 * @param string $block_content The block's rendered markup, returned unchanged.
 * @return string
 */
function enqueue_search_filters_script( $block_content ) {
	$relative = 'assets/js/search-filters.js';

	wp_enqueue_script(
		'sd-theme-2026-search-filters',
		get_theme_file_uri( $relative ),
		array(),
		asset_version( $relative ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	return $block_content;
}
add_filter( 'render_block_facetwp/facet', __NAMESPACE__ . '\\enqueue_search_filters_script' );
