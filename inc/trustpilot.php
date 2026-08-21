<?php
/**
 * Trustpilot star graphics.
 *
 * Answers the plugin's `sd_enh_trustpilot_stars_image` filter with the theme's
 * own rating tile. That is the whole module — there is no Trustpilot stylesheet,
 * because the badge needs none: `patterns/trustpilot-score.php` is a flex Group
 * of core blocks, so its layout, its image widths and its type all come from
 * block attributes and preset tokens, and its colours are inherited from
 * whichever ground it is placed on. That is what lets one pattern serve the
 * light utility bar, the dark expert panel and the footer without a variation
 * for each — which is three `[tp_show_score color="…"]` calls on the live site.
 *
 * ## Why the theme answers this filter
 *
 * Because the star tiles are theme assets, and the plugin says so. `sd/trustpilot`
 * exposes the rating as a **number** — SD Enhancements' Trustpilot module is
 * explicit that "picking the star graphic is the theme's job" — but a number
 * cannot become a row of stars in a block pattern: bindings can write `content`,
 * `url`, `alt` and `title`, and never `style`, so a width-clipped overlay is off
 * the table and the star row has to be an `<img>` whose `src` is bound.
 *
 * So the plugin rounds the rating to Trustpilot's half-star scale — their scale,
 * their business — and asks here for the file. This module is the only place in
 * the theme that knows the tiles exist or where they live, which is the whole
 * reason it is a filter and not a path built in the plugin.
 *
 * With this file absent the filter goes unanswered, the binding returns null, and
 * the pattern falls back to the placeholder image it authored. Nothing errors.
 *
 * ## Why the graphics are not tokenised
 *
 * These are Trustpilot's official RGB tiles and their **colour is data**: the
 * scale runs red at one star (#FF3722) through orange and yellow to green at
 * four and a half (#00B67A). Recolouring them to a theme token would state a
 * different rating than the one being reported, so they are the one set of
 * assets in this theme that is deliberately exempt from the token rule — and the
 * reason `theme-orphaned-refs` will never see a colour reference for them.
 *
 * The child theme shipped `5star-brown.svg` and `5star-white.svg` to match the
 * header and footer grounds. Both are dropped: they only ever rendered because
 * `tp_overall_score()` hardcoded `$stars = 5`, and against a real score they
 * would have been the wrong colour four bands out of five.
 *
 * @package sd-theme-2026
 */

namespace SdTheme2026;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The star tile for a rating.
 *
 * Ten tiles, keyed by the rating they depict. The keys are strings rather than
 * floats because a float is a poor array key — `0.5` and `1.5` both cast to
 * integers in some PHP array contexts — and because the string is also the
 * filename, so the map is its own documentation.
 *
 * @param string|null $url     URL from a previous filter, if any.
 * @param float       $rounded Rating rounded to the nearest half star, 0–5.
 * @return string|null Absolute URL of the tile, or the incoming value.
 */
function trustpilot_stars_image( $url, $rounded ) {
	// Respect anything that answered first, so a child theme or a staging
	// override wins over this default.
	if ( is_string( $url ) && '' !== trim( $url ) ) {
		return $url;
	}

	$tiles = array(
		'0'   => 'stars-0.svg',
		'0.5' => 'stars-1.svg',
		'1'   => 'stars-1.svg',
		'1.5' => 'stars-1-5.svg',
		'2'   => 'stars-2.svg',
		'2.5' => 'stars-2-5.svg',
		'3'   => 'stars-3.svg',
		'3.5' => 'stars-3-5.svg',
		'4'   => 'stars-4.svg',
		'4.5' => 'stars-4-5.svg',
		'5'   => 'stars-5.svg',
	);

	/*
	 * Trustpilot publishes no half-star tile below one star, so a 0.5 rating
	 * takes the one-star tile — the same band, the same colour. Formatted rather
	 * than cast so 4.0 keys as '4' and 4.5 as '4.5'; rtrim removes the trailing
	 * '.0' and then the orphaned separator.
	 */
	$key = rtrim( rtrim( number_format( (float) $rounded, 1, '.', '' ), '0' ), '.' );

	if ( ! isset( $tiles[ $key ] ) ) {
		return $url;
	}

	$relative = 'assets/images/trustpilot/stars/' . $tiles[ $key ];

	// A missing file would bind an image to a 404. Better to answer nothing and
	// let the pattern's authored fallback stand.
	if ( ! is_readable( get_theme_file_path( $relative ) ) ) {
		return $url;
	}

	return get_theme_file_uri( $relative );
}
add_filter( 'sd_enh_trustpilot_stars_image', __NAMESPACE__ . '\\trustpilot_stars_image', 10, 2 );
