<?php
/**
 * Title: Trustpilot Score
 * Slug: sd-theme-2026/trustpilot-score
 * Description: The Trustpilot rating badge — the band word, the Trustpilot mark, the star tile and the TrustScore line. Reads the live score through the sd/trustpilot binding source; inherits its colours from whatever it is placed on.
 * Categories: sd-theme-2026/testimonial
 * Keywords: trustpilot, reviews, rating, score, stars, badge, trust
 * Viewport Width: 520
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * What this replaces, and why it is not an image.
 *
 * `[tp_show_score]` — `tp_overall_score()` in
 * sd-lsx-child/includes/shortcodes.php:96 — called three times on the live site:
 * `color="brown"` in the header utility bar, and `color="black"` in the safari
 * expert panel and the "Why choose Southern Destinations" footer block. One
 * component, three colour variants, all of them PHP.
 *
 * There is a *second*, unrelated Trustpilot thing in the live header — a
 * `trust-menu` nav item holding `uploads/2019/07/trustpilot.png` linked to `#`.
 * That is a static PNG and nothing else, and an earlier pass at parts/header
 * reproduced *it* rather than this. It is dropped: it duplicates the badge
 * beside it and its link goes nowhere.
 *
 * Everything variable here arrives through the `sd/trustpilot` binding source,
 * which reads a twice-daily cron cache. Nothing is fetched on the render path
 * and no credential is involved at this end.
 *
 * ## One pattern, three placements, no colour variants
 *
 * The band word, the TrustScore line and the mark all take `currentColor`, and
 * nothing here sets a colour — so the badge inherits the ground it is placed on
 * and the live site's three `color=` variants collapse into one file. The only
 * asset with a fixed colour is the star tile, and that is deliberate: see below.
 *
 * ## The star tile is Trustpilot's, and its colour is the rating
 *
 * `stars` returns a number, so the tile is chosen by `stars_image`, which rounds
 * to Trustpilot's half-star scale and asks the theme for the file
 * (inc/trustpilot.php). The ten official tiles run red at one star through to
 * green at four and a half — the hue *is* the band, which is why the tile is
 * never recoloured and why the child theme's `5star-brown.svg` and
 * `5star-white.svg` are not ported.
 *
 * It is bound on `url` and left decorative (`alt=""`): the rating is already in
 * the text either side of it, and the mark next to it is a link to the same
 * place, so making this a second link to Trustpilot would be two adjacent links
 * to one target — which is what live does.
 *
 * ## What renders before the API key is rotated
 *
 * Nothing is cached until `SD_TRUSTPILOT_API_KEY` is installed, so every binding
 * returns null and each block falls back to what is authored below. That is why
 * the two number paragraphs are authored **empty** — a null binding leaves them
 * empty rather than printing a stale figure — and why the star tile falls back to
 * `stars-0.svg`, the grey no-rating tile, rather than a five-star one. The band
 * word falls back to "Excellent", which is SD's actual band and is replaced by
 * the live one the moment the cache fills.
 */

?>
<!-- wp:group {"metadata":{"name":"Trustpilot Score"},"className":"sd-trustpilot","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group sd-trustpilot">

	<?php /* The band word — Trustpilot's own vocabulary for the rating, so the plugin owns the bands. */ ?>
	<!-- wp:paragraph {"metadata":{"name":"Rating word","bindings":{"content":{"source":"sd/trustpilot","args":{"key":"wording"}}}},"className":"sd-trustpilot__wording","style":{"typography":{"fontWeight":"var:custom|font-weight|semi-bold"}},"fontSize":"100"} -->
	<p class="sd-trustpilot__wording has-100-font-size" style="font-weight:var(--wp--custom--font-weight--semi-bold)"><?php echo esc_html_x( 'Excellent', 'Trustpilot rating band', 'sd-theme-2026' ); ?></p>
	<!-- /wp:paragraph -->

	<?php /* The Trustpilot mark. Static — the file is a theme asset and the review URL is a constant — and the one link in the badge. */ ?>
	<!-- wp:image {"width":"90px","sizeSlug":"full","linkDestination":"custom","className":"sd-trustpilot__logo"} -->
	<figure class="wp-block-image size-full is-resized sd-trustpilot__logo"><a href="https://www.trustpilot.com/review/southerndestinations.com" target="_blank" rel="noreferrer noopener"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/trustpilot/trustpilot-logo.svg' ) ); ?>" alt="<?php esc_attr_e( 'Trustpilot', 'sd-theme-2026' ); ?>" style="width:90px"/></a></figure>
	<!-- /wp:image -->

	<?php /* The star tile. Bound on `url`; decorative, because the rating is in the text on both sides. */ ?>
	<!-- wp:image {"width":"100px","sizeSlug":"full","className":"sd-trustpilot__stars","metadata":{"name":"Star rating","bindings":{"url":{"source":"sd/trustpilot","args":{"key":"stars_image"}}}}} -->
	<figure class="wp-block-image size-full is-resized sd-trustpilot__stars"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/trustpilot/stars/stars-0.svg' ) ); ?>" alt="" style="width:100px"/></figure>
	<!-- /wp:image -->

	<?php
	/*
	 * The TrustScore line. Two paragraphs rather than one with a bound span in
	 * it, because a binding replaces a block's whole `content` — so the static
	 * words travel as the source's `prefix` and `suffix` args instead. The
	 * spaces sit in the pattern, outside the translation calls, so a translator
	 * cannot drop the one thing holding the words apart.
	 */
	?>
	<!-- wp:paragraph {"metadata":{"name":"TrustScore","bindings":{"content":{"source":"sd/trustpilot","args":{"key":"score","prefix":"<?php echo esc_attr_x( 'TrustScore', 'precedes the Trustpilot score figure', 'sd-theme-2026' ); ?> ","suffix":" |"}}}},"className":"sd-trustpilot__score","fontSize":"100"} -->
	<p class="sd-trustpilot__score has-100-font-size"></p>
	<!-- /wp:paragraph -->

	<!-- wp:paragraph {"metadata":{"name":"Review count","bindings":{"content":{"source":"sd/trustpilot","args":{"key":"count","suffix":" <?php echo esc_attr_x( 'reviews', 'follows the Trustpilot review count', 'sd-theme-2026' ); ?>"}}}},"className":"sd-trustpilot__count","fontSize":"100"} -->
	<p class="sd-trustpilot__count has-100-font-size"></p>
	<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
