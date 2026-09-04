<?php
/**
 * Yoast SEO breadcrumb styling.
 *
 * Attaches assets/styles/yoast-breadcrumbs.css to `yoast-seo/breadcrumbs`, so
 * the rule loads only on the templates that draw the trail.
 *
 * ## Why this needs a module at all
 *
 * Yoast's block renders a bare `<div class="yoast-breadcrumbs">` — it does not
 * call `get_block_wrapper_attributes()`, so the markup carries no
 * `wp-block-yoast-seo-breadcrumbs` class. A `theme.json` `styles.blocks` entry
 * would be emitted against that missing selector and would never match, and a
 * `styles/**` partial has nothing to hang a block-style variation on. The
 * plugin's own class is the only handle, so the rule has to be authored CSS.
 *
 * `enqueue_custom_block_styles()` in functions.php globs `core-*.css` only, so
 * a non-core block's stylesheet is registered by its own module here — the
 * arrangement functions.php documents and inc/mega-menu.php already follows.
 *
 * ## Why this belongs in the theme
 *
 * It is presentation and nothing else: one font-size, on the band the
 * `sd-theme-2026/destination-breadcrumbs` pattern places. The trail's
 * *contents* — what Yoast puts in it and any `wpseo_breadcrumb_links`
 * filtering — remain plugin work, as inc/README.md requires, and none of that
 * is here.
 *
 * `yoast-seo/breadcrumbs` is registered only while Yoast SEO is active; when it
 * is not, the block never renders and this stylesheet is never printed.
 *
 * @package sd-theme-2026
 */

namespace SdTheme2026;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the breadcrumb stylesheet against the block that renders it.
 *
 * `wp_enqueue_block_style()` is lazy: the stylesheet is printed only when the
 * block appears on the page, and inlined when it is small enough. Passing
 * `path` is what enables that inlining, so it is set alongside `src`.
 */
function enqueue_yoast_breadcrumbs_style() {
	$relative = 'assets/styles/yoast-breadcrumbs.css';

	wp_enqueue_block_style(
		'yoast-seo/breadcrumbs',
		array(
			'handle' => 'sd-theme-2026-block-yoast-breadcrumbs',
			'src'    => get_theme_file_uri( $relative ),
			'path'   => get_theme_file_path( $relative ),
			'ver'    => asset_version( $relative ),
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\\enqueue_yoast_breadcrumbs_style' );
