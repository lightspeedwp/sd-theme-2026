<?php
/**
 * The theme activates and registers what functions.php promises.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

namespace SdTheme2026\Tests\Integration;

use WP_Block_Pattern_Categories_Registry;
use WP_Block_Styles_Registry;

/**
 * Activation health: active theme, block theme, block styles, pattern categories.
 */
final class Test_Theme_Setup extends Theme_Test_Case {

	/**
	 * The suite is running against this theme, and WordPress sees a block theme.
	 *
	 * @return void
	 */
	public function test_theme_is_active_block_theme() {
		$theme = wp_get_theme();

		$this->assertSame( 'sd-theme-2026', $theme->get_stylesheet() );
		$this->assertTrue( $theme->is_block_theme(), 'templates/index.html is missing or unreadable' );
		$this->assertSame( array(), $theme->errors() ? $theme->errors()->get_error_messages() : array() );
	}

	/**
	 * Theme JSON resolves: presets reach global settings.
	 *
	 * @return void
	 */
	public function test_theme_json_presets_resolve() {
		$palette = wp_get_global_settings( array( 'color', 'palette', 'theme' ) );
		$spacing = wp_get_global_settings( array( 'spacing', 'spacingSizes', 'theme' ) );

		$this->assertNotEmpty( $palette, 'theme.json palette did not resolve — is theme.json valid JSON?' );
		$this->assertNotEmpty( $spacing, 'theme.json spacing sizes did not resolve' );
	}

	/**
	 * Every block style functions.php registers is in the registry.
	 *
	 * @return void
	 */
	public function test_block_styles_are_registered() {
		$expected = array(
			'core/list'         => 'list-check',
			'core/post-excerpt' => 'excerpt-truncate-3',
			'core/group'        => 'background-blur',
			'core/separator'    => 'separator-thin',
			'core/categories'   => 'categories-chevron',
		);

		$registry = WP_Block_Styles_Registry::get_instance();

		foreach ( $expected as $block => $style ) {
			$this->assertTrue( $registry->is_registered( $block, $style ), "{$block} is missing the {$style} block style" );
		}
	}

	/**
	 * Every category a pattern header names exists, so no pattern lands in
	 * "Uncategorized" in the inserter.
	 *
	 * Patterns with `Inserter: false` are left out: they never reach the
	 * inserter, so their categories are labels for people reading the file.
	 *
	 * @return void
	 */
	public function test_pattern_categories_named_by_patterns_are_registered() {
		$registry = WP_Block_Pattern_Categories_Registry::get_instance();
		$missing  = array();

		foreach ( self::theme_files( 'patterns', 'php' ) as $row ) {
			$headers = get_file_data(
				$row[0],
				array(
					'categories' => 'Categories',
					'inserter'   => 'Inserter',
				)
			);

			if ( in_array( strtolower( $headers['inserter'] ), array( 'false', 'no' ), true ) ) {
				continue;
			}

			foreach ( array_filter( array_map( 'trim', explode( ',', $headers['categories'] ) ) ) as $category ) {
				if ( ! $registry->is_registered( $category ) ) {
					$missing[ $category ][] = basename( $row[0] );
				}
			}
		}

		$this->assertSame( array(), $missing, 'pattern categories used but never registered' );
	}
}
