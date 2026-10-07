<?php
/**
 * Every pattern file registers, and renders without a PHP error.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

namespace SdTheme2026\Tests\Integration;

use WP_Block_Patterns_Registry;

/**
 * Pattern contract: namespaced slug, registered, known core blocks, clean render.
 */
final class Test_Patterns extends Theme_Test_Case {

	/**
	 * Pattern files as data provider rows.
	 *
	 * @return array<string, array{0: string}>
	 */
	public static function data_pattern_files(): array {
		return self::theme_files( 'patterns', 'php' );
	}

	/**
	 * Registered under its `sd-theme-2026/` slug.
	 *
	 * Core registers theme patterns from these headers and skips a file it
	 * cannot read — silently, so a broken header just makes the pattern vanish
	 * from the inserter.
	 *
	 * @dataProvider data_pattern_files
	 *
	 * @param string $path Pattern file.
	 * @return void
	 */
	public function test_pattern_is_registered( $path ) {
		$headers = get_file_data( $path, array( 'slug' => 'Slug' ) );

		$this->assertStringStartsWith( 'sd-theme-2026/', $headers['slug'], 'pattern slug is not namespaced' );
		$this->assertTrue(
			WP_Block_Patterns_Registry::get_instance()->is_registered( $headers['slug'] ),
			"{$headers['slug']} is not registered — check its header block"
		);
	}

	/**
	 * Uses only core blocks WordPress knows, and renders without a PHP error.
	 *
	 * The test suite turns notices and warnings into failures, so a pattern
	 * whose PHP reads an undefined index, or whose dynamic blocks choke without
	 * a queried post, fails here rather than in a page's error log.
	 *
	 * @dataProvider data_pattern_files
	 *
	 * @param string $path Pattern file.
	 * @return void
	 */
	public function test_pattern_renders_cleanly( $path ) {
		$headers = get_file_data( $path, array( 'slug' => 'Slug' ) );
		$pattern = WP_Block_Patterns_Registry::get_instance()->get_registered( $headers['slug'] );

		$this->assertNotNull( $pattern, "{$headers['slug']} is not registered" );
		$this->assertSame( array(), self::unknown_core_blocks( $pattern['content'] ), 'unknown core/* blocks' );
		$this->assertIsString( do_blocks( $pattern['content'] ) );
	}
}
