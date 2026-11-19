<?php
/**
 * Title: Card — Trustpilot Review
 * Slug: sd-theme-2026/card-trustpilot-review
 * Description: One cached Trustpilot review as the team single renders it — the review date, its headline, an extract and the reviewer's name. Repeated by the sd/trustpilot-reviews block.
 * Categories: sd-theme-2026/card, sd-theme-2026/testimonial
 * Keywords: card, review, trustpilot, testimonial, feedback, consultant, team
 * Viewport Width: 480
 * Block Types: sd/trustpilot-reviews
 * Template Types: single
 * Inserter: false
 *
 * @package sd-theme-2026
 */

/*
 * Live's `.tb-review-box`, measured from /team/camille-rowe/ on 2026-09-02
 * against sd-lsx-child/assets/css/custom.css:4384-4416 and the markup
 * `tp_show_reviews()` emits (sd-lsx-child/includes/shortcodes.php:22).
 *
 *     live                                       here
 *     -----------------------------------------  -------------------------
 *     .tb-review-box            flex 0 1 25%      one grid cell, 3 across
 *     .tb-review-box img        40% wide stars    110px, beside the date
 *     .tb-review-date           12px              font-size 100
 *     .tb-title                 margin-top 1.5rem the card's blockGap
 *     .tb-title h3              15px, #4c5250     h3, font-size 200, neutral-700
 *     .tb-title h3 a:hover      #cc7f16           elements.link :hover, brand-600
 *     .tb-review-text p         inherited, 15px   font-size 200, clamped to 3
 *     .tb-person p              inherited         font-size 200
 *
 * Every value arrives through `sd/trustpilot-review`, which takes its subject
 * from the `sdTrustpilotIndex` context the `sd/trustpilot-reviews` block
 * provides — the same shape `core/term-template` uses for terms. So this file
 * is authored once and the block repeats it, and nothing here reads a post.
 * That block caps the cache at three reviews (`Trustpilot::REVIEW_COUNT`),
 * which is the row live draws.
 *
 * ## The star row — the review's own rating, not the company's
 *
 * Live opens each box with a star SVG beside the date. This was left out until
 * 2026-09-16 because `sd/trustpilot-review` exposed `stars` as a bare float and
 * nothing else: binding an image to `sd/trustpilot`'s `stars_image` would have
 * painted the *business* rating onto an individual review, and binding a
 * paragraph to `stars` renders the digit "5" on its own.
 *
 * The source now answers `stars_image` from the review's own rating, through the
 * same `sd_enh_trustpilot_stars_image` filter inc/trustpilot.php already answers
 * for the badge — so the tile map lives in one place and serves both. Decorative
 * (`alt=""`), because the date beside it is the labelled content and the
 * headline below already links to the same place a linked tile would.
 *
 * Width is 110px: live's `.tb-review-box img` is 40% of a box that measures
 * ~300px in the 75% column, and 40% of a flex row would have been a percentage
 * of the wrong parent.
 *
 * ## The headline is a link, and live's three all point at one URL
 *
 * Live wraps each headline in an `<a>` to
 * `trustpilot.com/review/southerndestinations.com` — the company's review page,
 * not the individual review. All three boxes carry that identical href, and so
 * does the Trustpilot mark in the badge beside them.
 *
 * The anchor arrives inside the bound value, because a binding replaces a
 * block's whole `content` and `core/heading` has no bindable `href`. That is a
 * sanctioned route rather than a trick: core runs rich-text replacements through
 * `wp_kses_post()` (`WP_Block::replace_html()`), and the href is a constant in
 * sd-enhancements — see the `link` arg on `sd/trustpilot-review`. With `link`
 * absent the same key returns the plain title, so the card degrades to text
 * rather than to markup-as-text.
 *
 * The hover is `brand-600` against live's `#cc7f16`, set as `elements.link` on
 * the heading so it travels with the block rather than needing a stylesheet.
 * `sd/trustpilot-review`'s `url` key stays available for the day the API is
 * asked for per-review permalinks.
 *
 * ## Why the fallbacks are empty and not sample copy
 *
 * A binding that resolves to null leaves the authored content standing. With no
 * `SD_TRUSTPILOT_API_KEY` set the whole section is gone — `sd/trustpilot-reviews`
 * renders nothing when the cache is empty — so authored copy here could only
 * ever appear as a phantom review attributed to a real name. Empty is the
 * honest fallback.
 */

?>
<!-- wp:group {"metadata":{"name":"Trustpilot Review Card"},"className":"sd-trustpilot-review","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
<div class="wp-block-group sd-trustpilot-review">

	<?php
	/*
	 * Live's star tile and `.tb-review-date` — the tile at 40% of the box and
	 * the date at 12px, sharing the line above the headline. A flex row rather
	 * than two stacked blocks, so the two sit on one line at every width the
	 * card is drawn at.
	 */
	?>
	<!-- wp:group {"metadata":{"name":"Review Meta"},"className":"sd-trustpilot-review__meta","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
	<div class="wp-block-group sd-trustpilot-review__meta">

		<!-- wp:image {"width":"110px","sizeSlug":"full","className":"sd-trustpilot-review__stars","metadata":{"name":"Review stars","bindings":{"url":{"source":"sd/trustpilot-review","args":{"key":"stars_image"}}}}} -->
		<figure class="wp-block-image size-full is-resized sd-trustpilot-review__stars"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/trustpilot/stars/stars-0.svg' ) ); ?>" alt="" style="width: 110px; height: auto;"/></figure>
		<!-- /wp:image -->

		<!-- wp:paragraph {"metadata":{"name":"Review date","bindings":{"content":{"source":"sd/trustpilot-review","args":{"key":"date"}}}},"className":"sd-trustpilot-review__date","fontSize":"100"} -->
		<p class="sd-trustpilot-review__date has-100-font-size"></p>
		<!-- /wp:paragraph -->

	</div>
	<!-- /wp:group -->

	<?php
	/*
	 * The headline. `h3` under the section's `h2` — live's is an `h3` too, and
	 * at the same level relative to its section heading, so the outline is
	 * carried across unchanged. `link` on the binding wraps it in live's anchor;
	 * the head of this file has why the markup arrives through the value.
	 */
	?>
	<!-- wp:heading {"level":3,"metadata":{"name":"Review headline","bindings":{"content":{"source":"sd/trustpilot-review","args":{"key":"title","link":true}}}},"className":"sd-trustpilot-review__title","textColor":"neutral-700","style":{"elements":{"link":{"color":{"text":"var:preset|color|neutral-700"},":hover":{"color":{"text":"var:preset|color|brand-600"}}}}},"fontSize":"200"} -->
	<h3 class="wp-block-heading sd-trustpilot-review__title has-neutral-700-color has-text-color has-link-color has-200-font-size"></h3>
	<!-- /wp:heading -->

	<?php
	/*
	 * The extract. Trustpilot returns the full review body and the plugin stores
	 * it whole — live throws the rest away in PHP at ten words
	 * (`wp_trim_words( $review->text, 10 )`), which is lossy and reflows badly.
	 * Clamped to three lines in assets/styles/core-paragraph.css instead, so the
	 * cut follows the rendered measure and the whole review is still in the
	 * markup. Zared's call, 2026-09-16.
	 */
	?>
	<!-- wp:paragraph {"metadata":{"name":"Review text","bindings":{"content":{"source":"sd/trustpilot-review","args":{"key":"text"}}}},"className":"sd-trustpilot-review__text","fontSize":"200"} -->
	<p class="sd-trustpilot-review__text has-200-font-size"></p>
	<!-- /wp:paragraph -->

	<?php /* Live's `.tb-person` — the reviewer's display name, plain, closing the box. */ ?>
	<!-- wp:paragraph {"metadata":{"name":"Reviewer","bindings":{"content":{"source":"sd/trustpilot-review","args":{"key":"author"}}}},"className":"sd-trustpilot-review__author","style":{"typography":{"fontWeight":"var:custom|font-weight|semi-bold"}},"fontSize":"200"} -->
	<p class="sd-trustpilot-review__author has-200-font-size" style="font-weight:var(--wp--custom--font-weight--semi-bold)"></p>
	<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
