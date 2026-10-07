<?php
/**
 * Bootstrap for the integration suite: the WordPress test suite, with this
 * theme active and its plugin dependencies loaded where they can be found.
 *
 * - The theme is forced active through the `stylesheet` / `template` options,
 *   and its parent directory is registered as a theme root, so the suite works
 *   whether the theme sits inside a core install (local) or was checked out on
 *   its own (CI).
 * - Plugins the theme builds on are loaded when present, each from an
 *   environment variable or its sibling directory in wp-content/plugins:
 *   Tour Operator (TO_PLUGIN_DIR), sd-enhancements (SD_ENH_PLUGIN_DIR) and
 *   Ollie Menu Designer (OLLIE_MENU_PLUGIN_DIR), which registers the `menu`
 *   template part area the mega-menu parts declare. The theme's Tour Operator
 *   templates are inert without them, not broken — tests that need a plugin
 *   skip with a stated reason.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

define( 'SD_THEME_TESTS_DIR', dirname( __DIR__, 3 ) . '/' );

require_once SD_THEME_TESTS_DIR . 'vendor/autoload.php';

putenv( 'WP_PHPUNIT__TESTS_CONFIG=' . dirname( __DIR__ ) . '/wp-tests-config.php' ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_putenv -- wp-phpunit reads its config path from this variable; it is the documented interface.

$sd_theme_tests_lib = getenv( 'WP_PHPUNIT__DIR' );

require_once $sd_theme_tests_lib . '/includes/functions.php';

tests_add_filter(
	'muplugins_loaded',
	static function () {
		$plugins_dir = dirname( SD_THEME_TESTS_DIR, 2 ) . '/plugins';
		$plugins     = array(
			'TO_PLUGIN_DIR'         => array( 'tour-operator', 'tour-operator.php' ),
			'SD_ENH_PLUGIN_DIR'     => array( 'sd-enhancements-2026', 'sd-enhancements.php' ),
			'OLLIE_MENU_PLUGIN_DIR' => array( 'ollie-menu-designer', 'ollie-menu-designer.php' ),
		);

		foreach ( $plugins as $variable => $plugin ) {
			$directory = getenv( $variable ) ? rtrim( getenv( $variable ), '/' ) : $plugins_dir . '/' . $plugin[0];

			if ( file_exists( $directory . '/' . $plugin[1] ) ) {
				require_once $directory . '/' . $plugin[1];
			}
		}

		register_theme_directory( dirname( SD_THEME_TESTS_DIR ) );
	}
);

foreach ( array( 'pre_option_stylesheet', 'pre_option_template' ) as $sd_theme_tests_option ) {
	tests_add_filter(
		$sd_theme_tests_option,
		static function () {
			return 'sd-theme-2026';
		}
	);
}

require_once $sd_theme_tests_lib . '/includes/bootstrap.php';
require_once __DIR__ . '/class-theme-test-case.php';
