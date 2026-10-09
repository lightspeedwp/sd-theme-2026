<?php
/**
 * Image loading: hidden menu panels load lazily, and theme images name their
 * attachment so core can optimise them (ASD-36, 20.1).
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

namespace SdTheme2026\Tests\Integration;

/**
 * Covers inc/menu-images.php, and the rule that lets core add `srcset`,
 * `sizes`, dimensions and `loading` to the theme's own images.
 */
final class Test_Image_Loading extends Theme_Test_Case {

	/**
	 * Markup with an image of each kind the filter must handle.
	 */
	private const PANEL = '<div><img src="https://example.com/a.jpg" alt="" fetchpriority="high"><img src="https://example.com/b.jpg" alt="" loading="eager"></div>';

	/**
	 * Apply core's `render_block_{$name}` filter, as WP_Block::render() does.
	 *
	 * @param string $name  Block name.
	 * @param array  $attrs Block attributes.
	 * @return string
	 */
	private static function render( string $name, array $attrs = array() ): string {
		return apply_filters( "render_block_{$name}", self::PANEL, array( 'attrs' => $attrs ), null );
	}

	/**
	 * Each image's `loading` and `fetchpriority`, in order.
	 *
	 * @param string $html Markup.
	 * @return array<int, array{loading: mixed, fetchpriority: mixed}>
	 */
	private static function image_attributes( string $html ): array {
		$tags   = new \WP_HTML_Tag_Processor( $html );
		$images = array();

		while ( $tags->next_tag( array( 'tag_name' => 'img' ) ) ) {
			$images[] = array(
				'loading'       => $tags->get_attribute( 'loading' ),
				'fetchpriority' => $tags->get_attribute( 'fetchpriority' ),
			);
		}

		return $images;
	}

	/**
	 * A mega-menu panel's images load lazily; an explicit `loading` is kept.
	 *
	 * @return void
	 */
	public function test_mega_menu_images_are_lazy() {
		$images = self::image_attributes( self::render( 'ollie/mega-menu' ) );

		$this->assertSame( 'lazy', $images[0]['loading'], 'Mega-menu images load eagerly — inc/menu-images.php is not in place' );
		$this->assertNull( $images[0]['fetchpriority'], 'A hidden image kept fetchpriority="high"' );
		$this->assertSame( 'eager', $images[1]['loading'], 'An explicit loading attribute was overwritten' );
	}

	/**
	 * Only a navigation that always uses its overlay is treated as hidden.
	 *
	 * @return void
	 */
	public function test_only_always_overlay_navigation_is_lazy() {
		$always = self::image_attributes( self::render( 'core/navigation', array( 'overlayMenu' => 'always' ) ) );
		$mobile = self::image_attributes( self::render( 'core/navigation', array( 'overlayMenu' => 'mobile' ) ) );
		$unset  = self::image_attributes( self::render( 'core/navigation', array() ) );

		$this->assertSame( 'lazy', $always[0]['loading'], 'The always-overlay navigation (the mobile menu) loads its images eagerly' );
		$this->assertNull( $mobile[0]['loading'], 'A navigation shown inline at desktop widths was made lazy' );
		$this->assertNull( $unset[0]['loading'], 'A navigation with the default overlay setting was made lazy' );
	}

	/**
	 * Core's lazy-loading switch is honoured.
	 *
	 * @return void
	 */
	public function test_respects_wp_lazy_loading_enabled() {
		add_filter( 'wp_lazy_loading_enabled', '__return_false' );
		$images = self::image_attributes( self::render( 'ollie/mega-menu' ) );
		remove_filter( 'wp_lazy_loading_enabled', '__return_false' );

		$this->assertNull( $images[0]['loading'] );
	}

	/**
	 * Every theme image pointing at uploads names its attachment.
	 *
	 * Core adds `srcset`, `sizes`, width, height and `loading` only to an image
	 * carrying a `wp-image-{id}` class. Without it a 1,920px archive banner
	 * reaches a phone at full size. The theme's AGENTS.md allows attachment IDs
	 * (dev's match live's), so an uploads image without one is a mistake.
	 *
	 * @return void
	 */
	public function test_uploads_images_name_their_attachment() {
		$root    = get_theme_file_path();
		$files   = array_merge( glob( "{$root}/patterns/*.php" ), glob( "{$root}/parts/*.html" ), glob( "{$root}/templates/*.html" ) );
		$missing = array();

		foreach ( $files as $file ) {
			// Mask PHP first: an alt text written as esc_attr_e() holds a `>`.
			$markup = preg_replace( '/<\?php.*?\?>/s', 'PHP', (string) file_get_contents( $file ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- reading a theme file from disk.

			preg_match_all( '/<img\b[^>]*>/', $markup, $matches );

			foreach ( $matches[0] as $img ) {
				if ( false !== strpos( $img, '/wp-content/uploads/' ) && ! preg_match( '/\bwp-image-\d+\b/', $img ) ) {
					preg_match( '/src="([^"]*)"/', $img, $src );
					$missing[] = basename( $file ) . ': ' . basename( $src[1] ?? '?' );
				}
			}
		}

		$this->assertSame( array(), $missing, "Uploads images with no wp-image-{id} class, so core can't add srcset or lazy-load them" );
	}
}
