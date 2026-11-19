<?php
/**
 * WordPress test-suite configuration, read from the environment.
 *
 * Shared by the integration suite only. Every value can be overridden with an
 * environment variable of the same name; the defaults suit the local Homebrew
 * MySQL stack described in the workspace AGENTS.md.
 *
 * ⚠️ The suite drops and recreates every table carrying WP_TESTS_TABLE_PREFIX in
 * WP_TESTS_DB_NAME. The default database is a dedicated throwaway one, and the
 * guard below refuses the local site's own database by name.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

/**
 * Read a setting from the environment, falling back to a default.
 *
 * @param string $name     Variable name.
 * @param string $fallback Default.
 * @return string
 */
function sd_theme_tests_env( $name, $fallback ) {
	$value = getenv( $name );

	return ( false === $value || '' === $value ) ? $fallback : $value;
}

/*
 * WordPress core to boot. Locally the theme sits inside a full core install
 * (wp-content/themes/<theme>), so core is three levels up; CI downloads core
 * and points WP_CORE_DIR at it.
 */
$sd_theme_tests_core = rtrim( sd_theme_tests_env( 'WP_CORE_DIR', dirname( __DIR__, 5 ) ), '/' ) . '/';

if ( ! file_exists( $sd_theme_tests_core . 'wp-settings.php' ) ) {
	fwrite( STDERR, "WordPress core not found at {$sd_theme_tests_core}. Set WP_CORE_DIR.\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fwrite, WordPress.Security.EscapeOutput.OutputNotEscaped -- CLI diagnostic before WordPress exists.
	exit( 1 );
}

define( 'ABSPATH', $sd_theme_tests_core );

define( 'DB_NAME', sd_theme_tests_env( 'WP_TESTS_DB_NAME', 'southerndestinations_tests' ) );
define( 'DB_USER', sd_theme_tests_env( 'WP_TESTS_DB_USER', 'root' ) );
define( 'DB_PASSWORD', sd_theme_tests_env( 'WP_TESTS_DB_PASSWORD', '' ) );
define( 'DB_HOST', sd_theme_tests_env( 'WP_TESTS_DB_HOST', '127.0.0.1' ) );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

if ( 'southerndestinations' === DB_NAME ) {
	fwrite( STDERR, "Refusing to run: WP_TESTS_DB_NAME is the local site's own database, and the test suite would drop its tables.\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fwrite -- CLI diagnostic before WordPress exists.
	exit( 1 );
}

$table_prefix = sd_theme_tests_env( 'WP_TESTS_TABLE_PREFIX', 'wptests_' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- the name is the test suite's.

define( 'WP_TESTS_DOMAIN', 'example.org' );
define( 'WP_TESTS_EMAIL', 'admin@example.org' );
define( 'WP_TESTS_TITLE', 'Test Blog' );
define( 'WP_PHP_BINARY', 'php' );
define( 'WPLANG', '' );
define( 'WP_DEBUG', true );
define( 'WP_ENVIRONMENT_TYPE', 'local' );

define( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH', dirname( __DIR__, 2 ) . '/vendor/yoast/phpunit-polyfills' );

/*
 * Unique keys are pointless for a throwaway database, but core warns without
 * them. Fixed strings keep runs reproducible.
 */
foreach ( array( 'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT' ) as $sd_theme_tests_key ) {
	define( $sd_theme_tests_key, 'sd-theme-2026-tests-' . strtolower( $sd_theme_tests_key ) );
}
