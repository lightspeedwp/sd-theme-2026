<?php
/**
 * Title: Trustpilot Score — Stacked
 * Slug: sd-theme-2026/trustpilot-score-stacked
 * Description: The Trustpilot rating badge stacked into a column — the band word, the star tile, the review count and the Trustpilot mark. The arrangement live gives the badge beside a review row. Reads the live score through the sd/trustpilot binding source.
 * Categories: sd-theme-2026/testimonial
 * Keywords: trustpilot, reviews, rating, score, stars, badge, trust, stacked
 * Viewport Width: 320
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * Why this is a second file rather than a variant of patterns/trustpilot-score.php.
 *
 * Live has two arrangements of one component, and it separates them in CSS
 * rather than in PHP: `tp_overall_score()` emits the same four children every
 * time, and `sd-lsx-child/assets/css/custom.css` then re-orders and re-labels
 * them for the one placement that sits beside a review row.
 *
 *     custom.css:4302  #tb-horizon-review                       the default row
 *                      display:flex, centred, .tp-wording hidden
 *
 *     custom.css:4348  #tb-list-review-container #tb-horizon-review   this file
 *                      flex-direction:column
 *                      .tp-wording      display:initial; font-weight:600; order:1
 *                      .tp-review-stars order:2
 *                      .tb-score        order:3; span{display:none};
 *                                       :before{content:'Based on '}
 *                      .tp-review-logo  order:4
 *
 * So the two differ by more than a direction: the band word appears, the
 * TrustScore figure *disappears*, and the count is relabelled. Three of the four
 * children change, which is past what a `flex`/`orientation` switch on one
 * pattern can express — and re-ordering authored blocks with CSS `order` would
 * put the reading order and the visual order out of step for a screen reader.
 * Authoring the blocks in the order they are read is the cheaper correct answer,
 * and it is the same call patterns/why-choose-sd.php already made for its own
 * stacked copy.
 *
 * ## What is *not* carried over from live
 *
 * `.tb-score` is underlined on live, with the injected `Based on ` excluded from
 * the underline. It is not a link — nothing in `#tb-horizon-review` links except
 * the mark and the stars — so the underline advertises a destination that is not
 * there. Dropped deliberately; if it is wanted back it is one `textDecoration`
 * on the paragraph below, and it would then underline the words too.
 *
 * Sizes are this theme's, not live's: live caps every child at `max-height:25px`
 * and 12px text, and the badge here runs at the sizes established on dev —
 * font-size 100, a 160px star tile and a 132px mark. Zared's call, 2026-09-16.
 *
 * ## Everything else is patterns/trustpilot-score.php's, including its reasons
 *
 * The colour inheritance, the decorative `alt=""` on the star tile, why the tile
 * is never recoloured, and why the figures are authored empty are all documented
 * at the head of that file and are unchanged here. The one addition is the count
 * paragraph, which now carries both halves of its own line — `prefix` and
 * `suffix` on a single binding — because there is no longer a TrustScore
 * paragraph for it to sit beside.
 *
 * Consumer: patterns/template-single-team.php, the `#feedback` band.
 */

?>
<!-- wp:group {"metadata":{"name":"Trustpilot Score (stacked)"},"className":"sd-trustpilot sd-trustpilot--stacked","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
<div class="wp-block-group sd-trustpilot sd-trustpilot--stacked">

	<?php /* Live's `.tp-wording`, order 1 — hidden in the default row, shown and set to 600 here. */ ?>
	<!-- wp:paragraph {"metadata":{"name":"Rating word","bindings":{"content":{"source":"sd/trustpilot","args":{"key":"wording"}}}},"className":"sd-trustpilot__wording","style":{"typography":{"fontWeight":"var:custom|font-weight|semi-bold"}},"fontSize":"100"} -->
	<p class="sd-trustpilot__wording has-100-font-size" style="font-weight:var(--wp--custom--font-weight--semi-bold)"><?php echo esc_html_x( 'Excellent', 'Trustpilot rating band', 'sd-theme-2026' ); ?></p>
	<!-- /wp:paragraph -->

	<?php /* Live's `.tp-review-stars`, order 2. Bound on `url`; decorative, because the rating is in the text above and below it. */ ?>
	<!-- wp:image {"width":"160px","sizeSlug":"full","className":"sd-trustpilot__stars","metadata":{"name":"Star rating","bindings":{"url":{"source":"sd/trustpilot","args":{"key":"stars_image"}}}}} -->
	<figure class="wp-block-image size-full is-resized sd-trustpilot__stars"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/trustpilot/stars/stars-0.svg' ) ); ?>" alt="" style="width: 160px; height: auto;"/></figure>
	<!-- /wp:image -->

	<?php
	/*
	 * Live's `.tb-score`, order 3 — but only its second half. The `TrustScore 5 |`
	 * span is `display:none` in this placement and `Based on ` is injected in
	 * front of the count, so one bound paragraph carrying both affixes says
	 * exactly what live says. The spaces sit in the pattern, outside the
	 * translation calls, so a translator cannot drop the one thing holding the
	 * words apart.
	 */
	?>
	<!-- wp:paragraph {"metadata":{"name":"Review count","bindings":{"content":{"source":"sd/trustpilot","args":{"key":"count","prefix":"<?php echo esc_attr_x( 'Based on', 'precedes the Trustpilot review count', 'sd-theme-2026' ); ?> ","suffix":" <?php echo esc_attr_x( 'reviews', 'follows the Trustpilot review count', 'sd-theme-2026' ); ?>"}}}},"className":"sd-trustpilot__count","fontSize":"100"} -->
	<p class="sd-trustpilot__count has-100-font-size"></p>
	<!-- /wp:paragraph -->

	<?php /* Live's `.tp-review-logo`, order 4. Static — the file is a theme asset and the review URL is a constant — and the one link in the badge. */ ?>
	<!-- wp:image {"width":"132px","sizeSlug":"full","linkDestination":"custom","className":"sd-trustpilot__logo"} -->
	<figure class="wp-block-image size-full is-resized sd-trustpilot__logo"><a href="https://www.trustpilot.com/review/southerndestinations.com" target="_blank" rel="noreferrer noopener"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/trustpilot/trustpilot-logo.svg' ) ); ?>" alt="<?php esc_attr_e( 'Trustpilot', 'sd-theme-2026' ); ?>" style="width:132px"/></a></figure>
	<!-- /wp:image -->

</div>
<!-- /wp:group -->
