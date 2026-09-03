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
 *     .tb-review-box img        40% wide stars    not rendered — see below
 *     .tb-review-date           12px              font-size 100
 *     .tb-title                 margin-top 1.5rem the card's blockGap
 *     .tb-title h3              15px, #4c5250     h3, font-size 200, neutral-700
 *     .tb-title h3 a:hover      #cc7f16           elements.link :hover, brand-500
 *     .tb-review-text p         inherited, 15px   font-size 200
 *     .tb-person p              inherited         font-size 200
 *
 * Every value arrives through `sd/trustpilot-review`, which takes its subject
 * from the `sdTrustpilotIndex` context the `sd/trustpilot-reviews` block
 * provides — the same shape `core/term-template` uses for terms. So this file
 * is authored once and the block repeats it, and nothing here reads a post.
 * That block caps the cache at three reviews (`Trustpilot::REVIEW_COUNT`),
 * which is the row live draws.
 *
 * ## The star row is not here, and it is a plugin gap rather than a decision
 *
 * Live opens each box with the same 5-star SVG the badge beside it carries.
 * `sd/trustpilot` exposes `stars_image` — a URL the theme itself answers,
 * through inc/trustpilot.php — but `sd/trustpilot-review` does not: its keys
 * are `stars`, `title`, `text`, `author`, `date` and `url`, and `stars` is a
 * bare float. Binding an image to the *business* rating would paint the
 * company's score onto an individual review, which is wrong, and binding a
 * paragraph to `stars` renders the digit "5" on its own.
 *
 * So the row is left out until `sd/trustpilot-review` grows a `stars_image`
 * key — one call to the same private `stars_image()` the score source already
 * uses, in sd-enhancements, not here. Nothing in this file changes when it
 * lands except the image block that goes back on top. → flagged on LS-2033
 *
 * ## The headline is not a link, and live's three all point at one URL
 *
 * Live wraps each headline in an `<a>` to
 * `trustpilot.com/review/southerndestinations.com` — the company's review page,
 * not the individual review. All three boxes carry that identical href, and so
 * does the Trustpilot mark in the badge sitting beside them, which
 * patterns/trustpilot-score.php already renders. Reproducing it would put four
 * links to one destination inside one section.
 *
 * It is also not expressible: `core/heading` has no bindable `href`, and the
 * only core block that does is `core/button`. Turning three review headlines
 * into three buttons to say the same thing the badge says is worse markup for
 * no gain. `sd/trustpilot-review`'s `url` key stays available for the day the
 * API is asked for per-review permalinks.
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

	<?php /* Live's `.tb-review-date` — 12px, above the headline, in the site's own date format. */ ?>
	<!-- wp:paragraph {"metadata":{"name":"Review date","bindings":{"content":{"source":"sd/trustpilot-review","args":{"key":"date"}}}},"className":"sd-trustpilot-review__date","fontSize":"100"} -->
	<p class="sd-trustpilot-review__date has-100-font-size"></p>
	<!-- /wp:paragraph -->

	<?php
	/*
	 * The headline. `h3` under the section's `h2` — live's is an `h3` too, and
	 * at the same level relative to its section heading, so the outline is
	 * carried across unchanged.
	 */
	?>
	<!-- wp:heading {"level":3,"metadata":{"name":"Review headline","bindings":{"content":{"source":"sd/trustpilot-review","args":{"key":"title"}}}},"className":"sd-trustpilot-review__title","textColor":"neutral-700","fontSize":"200"} -->
	<h3 class="wp-block-heading sd-trustpilot-review__title has-neutral-700-color has-text-color has-200-font-size"></h3>
	<!-- /wp:heading -->

	<?php
	/*
	 * The extract. Trustpilot returns the full review body and live truncates
	 * it in CSS; the plugin stores it whole, so the length is a content matter
	 * rather than something this card should clamp.
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
