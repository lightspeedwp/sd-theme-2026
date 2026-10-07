<?php
/**
 * Every template and template part file resolves through WordPress.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

namespace SdTheme2026\Tests\Integration;

/**
 * Template contract: resolvable from the theme, and only known core blocks.
 */
final class Test_Templates extends Theme_Test_Case {

	/**
	 * Templates and parts as data provider rows, with their post type.
	 *
	 * @return array<string, array{0: string, 1: string}>
	 */
	public static function data_template_files(): array {
		$rows = array();

		foreach ( array(
			'templates' => 'wp_template',
			'parts'     => 'wp_template_part',
		) as $directory => $post_type ) {
			foreach ( self::theme_files( $directory, 'html' ) as $name => $row ) {
				$rows[ "{$directory}/{$name}" ] = array( $row[0], $post_type );
			}
		}

		return $rows;
	}

	/**
	 * WordPress resolves the file as this theme's own template.
	 *
	 * The test database holds no Site Editor customisations, so `source` must
	 * be `theme` — the file, not a database override.
	 *
	 * @dataProvider data_template_files
	 *
	 * @param string $path      Template file.
	 * @param string $post_type wp_template or wp_template_part.
	 * @return void
	 */
	public function test_template_resolves_from_the_theme( $path, $post_type ) {
		$slug     = basename( $path, '.html' );
		$template = get_block_template( "sd-theme-2026//{$slug}", $post_type );

		$this->assertNotNull( $template, "{$slug} does not resolve as a {$post_type}" );
		$this->assertSame( 'theme', $template->source );
		$this->assertSame( array(), self::unknown_core_blocks( $template->content ), 'unknown core/* blocks' );
	}
}
