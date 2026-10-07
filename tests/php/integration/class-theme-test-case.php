<?php
/**
 * Base class for the theme's integration tests.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

namespace SdTheme2026\Tests\Integration;

use WP_Block_Type_Registry;
use WP_UnitTestCase;

/**
 * WordPress's own test case, plus the helpers the theme's tests share.
 */
abstract class Theme_Test_Case extends WP_UnitTestCase {

	/**
	 * Skip — never fail — when a plugin the test depends on is not loaded.
	 *
	 * @param string $constant Constant the plugin defines.
	 * @param string $name     Plugin name, for the message.
	 * @return void
	 */
	protected function require_plugin( string $constant, string $name ): void {
		if ( ! defined( $constant ) ) {
			$this->markTestSkipped( "{$name} is not loaded — this test covers the theme's integration with it." );
		}
	}

	/**
	 * Every block name in parsed blocks, recursively.
	 *
	 * @param array $blocks Output of parse_blocks().
	 * @return string[] Unique names, nulls (freeform HTML) excluded.
	 */
	protected static function block_names( array $blocks ): array {
		$names = array();

		foreach ( $blocks as $block ) {
			if ( ! empty( $block['blockName'] ) ) {
				$names[] = $block['blockName'];
			}

			$names = array_merge( $names, self::block_names( $block['innerBlocks'] ) );
		}

		return array_values( array_unique( $names ) );
	}

	/**
	 * The `core/*` block names in markup that WordPress does not know.
	 *
	 * Plugin blocks are left out: whether `facetwp/facet` is registered says
	 * something about the test environment, not the theme. A `core/*` name
	 * WordPress does not recognise is a typo or a removed block, everywhere.
	 *
	 * @param string $markup Block markup.
	 * @return string[] Unknown core block names.
	 */
	protected static function unknown_core_blocks( string $markup ): array {
		$registry = WP_Block_Type_Registry::get_instance();

		return array_values(
			array_filter(
				self::block_names( parse_blocks( $markup ) ),
				static function ( $name ) use ( $registry ) {
					return 0 === strpos( $name, 'core/' ) && ! $registry->is_registered( $name );
				}
			)
		);
	}

	/**
	 * Files in a theme directory, as data provider rows keyed by file name.
	 *
	 * @param string $directory Directory relative to the theme root.
	 * @param string $extension Extension without the dot.
	 * @return array<string, array{0: string}>
	 */
	protected static function theme_files( string $directory, string $extension ): array {
		$rows = array();

		foreach ( glob( SD_THEME_TESTS_DIR . "{$directory}/*.{$extension}" ) as $path ) {
			$rows[ basename( $path ) ] = array( $path );
		}

		return $rows;
	}
}
