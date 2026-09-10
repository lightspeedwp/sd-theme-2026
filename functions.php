<?php
/**
 * This file adds functions to the Southern Destinations 2026 WordPress theme.
 *
 * Scope note: this theme carries **design only**. Business logic — post-type
 * registration, expiration rules, WETU import, form handlers, breadcrumb
 * filters, search integration and CRM routing — belongs in the companion
 * Southern Destinations block plugin. The test: *if deactivating the theme
 * would break it, it does not belong here.*
 *
 * @package sd-theme-2026
 * @author  LightSpeed
 * @license GNU General Public License v2 or later
 * @link    https://www.southerndestinations.com/
 */

namespace SdTheme2026;

/**
 * Cache-busting version string for a theme asset.
 *
 * Returns the asset's last-modified time so that any edit to the file changes
 * its enqueued URL — which means no browser, device cache, CDN, or page cache
 * can ever serve a stale copy after a deploy. Falls back to the theme version
 * if the file can't be read (e.g. on a symlinked mount).
 *
 * Use this for every locally-shipped stylesheet/script `$ver`; never hardcode
 * a version string, and don't lean on the theme `Version` header — it isn't
 * bumped per asset edit, so it goes stale exactly like a literal would.
 *
 * @param string $relative_path Asset path relative to the theme root,
 *                              e.g. 'assets/styles/core-button.css'.
 * @return string Version string suitable for wp_enqueue_* `$ver`.
 */
function asset_version( $relative_path ) {
	$file  = get_theme_file_path( $relative_path );
	$mtime = is_readable( $file ) ? filemtime( $file ) : false;

	return (string) ( false !== $mtime ? $mtime : wp_get_theme()->get( 'Version' ) );
}

/**
 * Set up theme defaults and register various WordPress features.
 */
function setup() {

	// Make the theme available for translation.
	load_theme_textdomain( 'sd-theme-2026', get_template_directory() . '/languages' );

	// Enqueue editor styles and fonts.
	add_editor_style( 'style.css' );

	// Remove core block patterns.
	remove_theme_support( 'core-block-patterns' );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\setup' );


/**
 * Enqueue styles.
 */
function enqueue_style_sheet() {
	wp_enqueue_style( 'sd-theme-2026', get_template_directory_uri() . '/style.css', array(), asset_version( 'style.css' ) );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_style_sheet' );


/**
 * Add block style variations.
 *
 * Each style registered here has a matching rule in `assets/styles/core-*.css`
 * or a `styles/**` JSON partial. Prefer the JSON partial — see AGENTS.md,
 * "Styling lives in JSON".
 */
function register_block_styles() {

	$block_styles = array(
		'core/list'         => array(
			'list-check' => __( 'Check', 'sd-theme-2026' ),
		),
		'core/post-excerpt' => array(
			'excerpt-truncate-3' => __( 'Truncate 3 Lines', 'sd-theme-2026' ),
		),
		'core/group'        => array(
			'background-blur' => __( 'Background Blur', 'sd-theme-2026' ),
		),
		'core/separator'    => array(
			'separator-thin' => __( 'Thin', 'sd-theme-2026' ),
		),
		'core/categories'   => array(
			'categories-chevron' => __( 'Chevron', 'sd-theme-2026' ),
		),
	);

	foreach ( $block_styles as $block => $styles ) {
		foreach ( $styles as $style_name => $style_label ) {
			register_block_style(
				$block,
				array(
					'name'  => $style_name,
					'label' => $style_label,
				)
			);
		}
	}
}
add_action( 'init', __NAMESPACE__ . '\register_block_styles' );


/**
 * Load per-block CSS only when the block is used.
 *
 * Convention: `assets/styles/core-<block>.css` maps to the core block `core/<block>`
 * (e.g. `core-button.css` → `core/button`) and is lazy-enqueued when that block renders.
 * Only `core-*.css` files participate; sheets for non-core blocks are enqueued by their
 * own modules in `inc/`. Don't break the filename → block-name convention.
 */
function enqueue_custom_block_styles() {

	// Scan our styles folder for per-core-block stylesheets.
	$files = glob( get_template_directory() . '/assets/styles/core-*.css' );

	foreach ( $files as $file ) {

		// Get the filename and core block name (core-button -> core/button).
		$filename   = basename( $file, '.css' );
		$block_name = str_replace( 'core-', 'core/', $filename );

		wp_enqueue_block_style(
			$block_name,
			array(
				'handle' => "sd-theme-2026-block-{$filename}",
				'src'    => get_theme_file_uri( "assets/styles/{$filename}.css" ),
				'path'   => get_theme_file_path( "assets/styles/{$filename}.css" ),
				'ver'    => asset_version( "assets/styles/{$filename}.css" ),
			)
		);
	}
}
add_action( 'init', __NAMESPACE__ . '\enqueue_custom_block_styles' );


/**
 * Register pattern categories.
 *
 * Keep this list tight — a category exists because in-scope patterns are filed
 * under it. New categories follow new approved scope, not the other way round.
 */
function pattern_categories() {

	$block_pattern_categories = array(
		'sd-theme-2026/hero'           => array(
			'label' => __( 'Hero', 'sd-theme-2026' ),
		),
		'sd-theme-2026/card'           => array(
			'label' => __( 'Cards', 'sd-theme-2026' ),
		),
		'sd-theme-2026/call-to-action' => array(
			'label' => __( 'Call To Action', 'sd-theme-2026' ),
		),
		'sd-theme-2026/features'       => array(
			'label' => __( 'Features', 'sd-theme-2026' ),
		),
		'sd-theme-2026/menu'           => array(
			'label' => __( 'Menu', 'sd-theme-2026' ),
		),
		'sd-theme-2026/pages'          => array(
			'label' => __( 'Pages', 'sd-theme-2026' ),
		),
		'sd-theme-2026/posts'          => array(
			'label' => __( 'Posts', 'sd-theme-2026' ),
		),
		'sd-theme-2026/testimonial'    => array(
			'label' => __( 'Testimonials', 'sd-theme-2026' ),
		),
		'sd-theme-2026/tour-operator'  => array(
			'label' => __( 'Tour Operator', 'sd-theme-2026' ),
		),
	);

	foreach ( $block_pattern_categories as $name => $properties ) {
		register_block_pattern_category( $name, $properties );
	}
}
add_action( 'init', __NAMESPACE__ . '\pattern_categories', 9 );


/**
 * Remove last separator on blog/archive if no pagination exists.
 */
function is_paginated() {
	global $wp_query;
	if ( $wp_query->max_num_pages < 2 ) {
		echo '<style>.blog .wp-block-post-template .wp-block-post:last-child .entry-content + .wp-block-separator, .archive .wp-block-post-template .wp-block-post:last-child .entry-content + .wp-block-separator, .blog .wp-block-post-template .wp-block-post:last-child .entry-content + .wp-block-separator, .search .wp-block-post-template .wp-block-post:last-child .wp-block-post-excerpt + .wp-block-separator { display: none; }</style>';
	}
}
add_action( 'wp_head', __NAMESPACE__ . '\is_paginated' );


/**
 * Print the MyFonts licence notice for the bundled Optima face.
 *
 * The self-hosting kit for Optima Pro Demi Bold ships an `@license` block and
 * asks that it appear in the `head` of every page that serves the font. The
 * kit's own instructions deliver that by linking its `MyWebfontsKit.css`; this
 * theme cannot, because `theme.json` already registers the face and WordPress
 * emits the `@font-face` rule itself. Linking the kit stylesheet as well would
 * load the same file a second time under a second family name
 * (`OptimaProDemiBold`) that nothing here references, and its relative
 * `webFonts/…` paths do not exist in this theme. So the notice is printed
 * directly instead — same obligation, no duplicate download.
 *
 * The text is reproduced verbatim and must not be edited or minified. The same
 * notice is kept beside the font as `assets/fonts/optima-600-normal.LICENSE.txt`.
 *
 * This is design-layer, not business logic: the obligation exists only because
 * this theme serves that font file. Deactivate the theme and it goes with it.
 */
function font_licence_notice() {
	echo "<!--\n",
		"/**\n",
		" * @license\n",
		" * MyFonts Webfont Build ID 3867246, 2020-12-16T11:57:38-0500\n",
		" *\n",
		" * The fonts listed in this notice are subject to the End User License\n",
		" * Agreement(s) entered into by the website owner. All other parties are\n",
		" * explicitly restricted from using the Licensed Webfonts(s).\n",
		" *\n",
		" * You may obtain a valid license at the URLs below.\n",
		" *\n",
		" * Webfont: Optima Pro Demi Bold by Zapf Alphabets\n",
		" * URL: https://www.myfonts.com/collections/zapf-alphabets-foundry\n",
		" *\n",
		" * \u{00A9} 2026 MyFonts Inc. */\n",
		"-->\n";
}
add_action( 'wp_head', __NAMESPACE__ . '\font_licence_notice', 1 );


/**
 * Add a Sidebar template part area.
 *
 * @param array $areas Registered template part areas.
 * @return array
 */
function template_part_areas( array $areas ) {
	$areas[] = array(
		'area'        => 'sidebar',
		'area_tag'    => 'section',
		'label'       => __( 'Sidebar', 'sd-theme-2026' ),
		'description' => __( 'The Sidebar template defines a page area that can be found on the Page (With Sidebar) template.', 'sd-theme-2026' ),
		'icon'        => 'sidebar',
	);

	return $areas;
}
add_filter( 'default_wp_template_part_areas', __NAMESPACE__ . '\template_part_areas' );


/*
 * Design-only modules. Each is loaded here with a note on why it cannot be
 * expressed in theme.json or a styles/** partial — see inc/README.md.
 */

// Styles ollie/mega-menu, a third-party block whose container markup is
// generated by the plugin's render.php, so no block-style variation can reach
// it — and a non-core block is outside enqueue_custom_block_styles()' core-*
// scan.
require_once get_theme_file_path( 'inc/mega-menu.php' );

// Styles yoast-seo/breadcrumbs, whose markup is a bare `.yoast-breadcrumbs`
// div with no block wrapper attributes — so there is no `wp-block-*` selector
// for theme.json to target — and a non-core block is outside
// enqueue_custom_block_styles()' core-* scan.
require_once get_theme_file_path( 'inc/yoast-breadcrumbs.php' );

// Styles facetwp/facet, whose inner markup is emitted by the plugin as a bare
// `.facetwp-facet` div and then filled from JS, so it carries no block wrapper
// attributes for theme.json to target — and a non-core block is outside
// enqueue_custom_block_styles()' core-* scan. Also carries the dropdown script,
// because there is no `wp_enqueue_block_script()` counterpart.
require_once get_theme_file_path( 'inc/facetwp.php' );

// Answers the plugin's `sd_enh_trustpilot_stars_image` filter with the theme's
// own rating tile. The plugin exposes the rating as a number and says explicitly
// that picking the graphic is the theme's job; this is the theme doing that, and
// it is the only place that knows where the tiles live.
require_once get_theme_file_path( 'inc/trustpilot.php' );
