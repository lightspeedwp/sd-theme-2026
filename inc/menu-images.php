<?php
/**
 * Lazy-load the images in menu panels that start hidden.
 *
 * The header carries three sets of images nobody sees on arrival: the
 * mega-menu card thumbnails (`ollie/mega-menu` panels, open on hover), the
 * flags in the Call Us dropdown (another `ollie/mega-menu`), and the logo and
 * flags in the mobile menu (`parts/mobile-menu.html`, inside a
 * `core/navigation` overlay set to `overlayMenu: "always"`).
 *
 * ## Why core doesn't do it
 *
 * `wp_get_loading_optimization_attributes()` never lazy-loads in the header
 * area, and counts the first images it meets towards the eager allowance
 * (`wp_omit_loading_attr_threshold()`, 3). Lighthouse on dev, 2026-10-09:
 * the homepage fetched about 440 KiB of images off screen at load (mobile),
 * mostly these. Three were card thumbnails of 57–90 KiB each, and they used up
 * the eager allowance that belongs to the page's own first images.
 *
 * ## Why it is the theme's
 *
 * It adds an attribute to images the theme places inside panels it designs,
 * so it's presentation. The menus' behaviour (opening, focus, submenu state)
 * stays with Ollie Menu Designer and the plugin, as inc/README.md requires.
 * With the theme deactivated these panels don't exist, so nothing breaks.
 *
 * An explicit `loading` on an image is kept, and core's
 * `wp_lazy_loading_enabled` switch is honoured. A `fetchpriority="high"` is
 * dropped, because a hidden image is never the page's largest paint.
 *
 * @package sd-theme-2026
 */

namespace SdTheme2026;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add `loading="lazy"` to every image in some markup that has no `loading`.
 *
 * @param string $html Rendered markup.
 * @return string
 */
function lazy_load_images( $html ) {
	if ( ! is_string( $html ) || false === stripos( $html, '<img' ) ) {
		return $html;
	}

	if ( ! wp_lazy_loading_enabled( 'img', 'sd_hidden_menu' ) ) {
		return $html;
	}

	$tags = new \WP_HTML_Tag_Processor( $html );

	while ( $tags->next_tag( array( 'tag_name' => 'img' ) ) ) {
		if ( null !== $tags->get_attribute( 'loading' ) ) {
			continue;
		}

		$tags->set_attribute( 'loading', 'lazy' );

		if ( 'high' === $tags->get_attribute( 'fetchpriority' ) ) {
			$tags->remove_attribute( 'fetchpriority' );
		}
	}

	return $tags->get_updated_html();
}

/**
 * Lazy-load the images inside a mega-menu panel.
 *
 * @param string $block_content The block's rendered markup.
 * @return string
 */
function lazy_load_mega_menu_images( $block_content ) {
	return lazy_load_images( $block_content );
}
add_filter( 'render_block_ollie/mega-menu', __NAMESPACE__ . '\\lazy_load_mega_menu_images', 20 );

/**
 * Lazy-load the images inside a navigation that always uses its overlay.
 *
 * Only `overlayMenu: "always"`: with the default `"mobile"`, the same markup is
 * on screen at desktop widths.
 *
 * @param string $block_content The block's rendered markup.
 * @param array  $parsed_block  The parsed block.
 * @return string
 */
function lazy_load_overlay_navigation_images( $block_content, $parsed_block ) {
	if ( 'always' !== ( $parsed_block['attrs']['overlayMenu'] ?? '' ) ) {
		return $block_content;
	}

	return lazy_load_images( $block_content );
}
add_filter( 'render_block_core/navigation', __NAMESPACE__ . '\\lazy_load_overlay_navigation_images', 20, 2 );
