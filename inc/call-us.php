<?php
/**
 * Call Us disclosure styling.
 *
 * Attaches assets/styles/sd-call-us.css to `sd/call-us`, so the panel styles
 * load only on pages where a Call Us button renders.
 *
 * ## Why this needs a module at all
 *
 * `enqueue_custom_block_styles()` in functions.php auto-enqueues by filename
 * convention — `core-button.css` attaches to `core/button` — and deliberately
 * globs `core-*.css` only. `sd/call-us` is not a core block, so it has no place
 * in that scan; a non-core block's stylesheet is registered by its own module
 * here, exactly as inc/mega-menu.php does for `ollie/mega-menu`.
 *
 * ## Why this belongs in the theme
 *
 * Because it is only styling, and because the block deliberately ships without
 * any. `sd/call-us` emits a button, a panel and the ARIA wiring between them —
 * no phone glyph, no chevron, no flags, no colours, not even a border. Every one
 * of those is a design decision, so all of them are here.
 *
 * That split is the point of the block. The live site could not restyle its Call
 * Us dropdown without touching PHP, because the widget was a `wp_nav_menu()`
 * call with a `menu_class` of `"menu-call-us menu lsx-to-meta-data phone btn
 * white-border-btn"` (sd-lsx-child/includes/functions.php:173) — six classes
 * doing the work of a stylesheet. Here the markup contract is five classes and
 * one attribute, documented at the foot of the block's render.php, and the theme
 * owns everything downstream of it.
 *
 * inc/README.md's ban on "sticky-header, mobile-menu and banner behaviour"
 * is not engaged: there is no JavaScript in this module and none in the theme
 * for this component. The disclosure's behaviour — `aria-expanded`, Escape,
 * click-outside, focus-out — is the plugin's, in blocks/call-us/view.js.
 *
 * @package sd-theme-2026
 */

namespace SdTheme2026;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Call Us stylesheet against the block that renders it.
 *
 * `wp_enqueue_block_style()` is lazy: the stylesheet is printed only when the
 * block appears on the page, and inlined when it is small enough. Passing `path`
 * is what enables that inlining, so it is set alongside `src`.
 *
 * The flag URLs inside the stylesheet are relative — `../images/flags/us.svg` —
 * and resolve against the stylesheet's own URL, so they survive being inlined
 * only because WordPress rewrites relative URLs when it inlines. That is worth
 * knowing if a flag ever goes missing on a page where the sheet was inlined:
 * check the printed CSS, not the file.
 */
function enqueue_call_us_style() {
	$relative = 'assets/styles/sd-call-us.css';

	wp_enqueue_block_style(
		'sd/call-us',
		array(
			'handle' => 'sd-theme-2026-block-sd-call-us',
			'src'    => get_theme_file_uri( $relative ),
			'path'   => get_theme_file_path( $relative ),
			'ver'    => asset_version( $relative ),
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\\enqueue_call_us_style' );
