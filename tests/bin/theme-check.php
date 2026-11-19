<?php
/**
 * Run Theme Check against this theme from WP-CLI.
 *
 * Theme Check (the wordpress.org review plugin) ships no WP-CLI command, only an
 * admin screen. Its runner is a plain function, though, so this calls it
 * directly and prints the findings as text:
 *
 *   npm run check:theme
 *
 * which is `wp eval-file tests/bin/theme-check.php` from the WordPress root.
 * Theme Check must be installed and active on the site WP-CLI resolves — it is
 * on local. Exits non-zero when any REQUIRED finding is reported, so the script
 * can gate; RECOMMENDED, WARNING and INFO lines are printed but do not fail.
 *
 * Repository tooling is excluded from the scan: the directory check is about
 * what ships in the theme zip, and tests, specs and agent config never do.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

if ( ! function_exists( 'run_themechecks_against_theme' ) ) {
	$sd_theme_check_base = WP_PLUGIN_DIR . '/theme-check/checkbase.php';

	if ( ! file_exists( $sd_theme_check_base ) ) {
		WP_CLI::error( 'Theme Check is not installed. Install and activate the theme-check plugin first.' );
	}

	require_once $sd_theme_check_base;
}

add_filter(
	'theme_scandir_exclusions',
	static function ( $exclusions ) {
		return array_merge(
			$exclusions,
			array( 'tests', 'specs', '.github', '.claude', '.specify', '.git' )
		);
	}
);
add_filter( 'tc_skip_development_directories', '__return_true' );

$sd_theme_check_theme = wp_get_theme( 'sd-theme-2026' );

if ( ! $sd_theme_check_theme->exists() ) {
	WP_CLI::error( 'sd-theme-2026 is not installed on this site.' );
}

run_themechecks_against_theme( $sd_theme_check_theme, 'sd-theme-2026' );

$sd_theme_check_findings = array();

foreach ( $GLOBALS['themechecks'] as $sd_theme_check ) {
	if ( $sd_theme_check instanceof themecheck ) {
		$sd_theme_check_findings = array_merge( $sd_theme_check_findings, (array) $sd_theme_check->getError() );
	}
}

$sd_theme_check_findings = array_unique(
	array_map(
		static function ( $finding ) {
			return trim( html_entity_decode( wp_strip_all_tags( $finding ), ENT_QUOTES ) );
		},
		$sd_theme_check_findings
	)
);
rsort( $sd_theme_check_findings );

$sd_theme_check_required = 0;

foreach ( $sd_theme_check_findings as $sd_theme_check_finding ) {
	if ( 0 === strpos( $sd_theme_check_finding, 'REQUIRED' ) ) {
		++$sd_theme_check_required;
	}

	WP_CLI::line( '* ' . $sd_theme_check_finding );
}

if ( $sd_theme_check_required > 0 ) {
	WP_CLI::error( sprintf( 'Theme Check: %d REQUIRED finding(s).', $sd_theme_check_required ) );
}

WP_CLI::success( sprintf( 'Theme Check: no REQUIRED findings (%d other).', count( $sd_theme_check_findings ) ) );
