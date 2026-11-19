<?php
/**
 * Yoast FAQ block styling.
 *
 * Attaches assets/styles/yoast-faq-block.css to `yoast/faq-block`, so the rules
 * load only on a page that renders the block (the three FAQ template parts).
 *
 * ## Why this needs a module at all
 *
 * The block's front-end markup is a bare `.schema-faq` wrapper with
 * `.schema-faq-section` / `.schema-faq-question` / `.schema-faq-answer` inside,
 * and the question is a `<strong>`. There is no block-style variation to hang
 * on and the `css` field of one strips the `:hover` and `:focus-within` states
 * this needs. A non-core block is also outside `enqueue_custom_block_styles()`'
 * `core-*` scan in functions.php, so it registers its own stylesheet here — the
 * arrangement inc/yoast-breadcrumbs.php follows.
 *
 * Presentation only. What the block outputs, and its FAQPage schema, remain
 * Yoast's.
 *
 * @package sd-theme-2026
 */

namespace SdTheme2026;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the FAQ stylesheet against the block that renders it.
 *
 * `wp_enqueue_block_style()` is lazy: the stylesheet is printed only when the
 * block appears on the page, and inlined when it is small enough. Passing
 * `path` is what enables that inlining, so it is set alongside `src`.
 */
function enqueue_yoast_faq_style() {
	$relative = 'assets/styles/yoast-faq-block.css';

	wp_enqueue_block_style(
		'yoast/faq-block',
		array(
			'handle' => 'sd-theme-2026-block-yoast-faq',
			'src'    => get_theme_file_uri( $relative ),
			'path'   => get_theme_file_path( $relative ),
			'ver'    => asset_version( $relative ),
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\\enqueue_yoast_faq_style' );
