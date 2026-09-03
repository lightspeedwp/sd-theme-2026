<?php
/**
 * Title: Destination — Reviews Shelf
 * Slug: sd-theme-2026/destination-reviews
 * Description: The connected-reviews carousel — the destination's review_to_destination connections as full-width quotes, one at a time, with no heading.
 * Categories: sd-theme-2026/tour-operator
 * Keywords: destination, reviews, testimonials, quotes, carousel, shelf
 * Viewport Width: 1400
 * Template Types: single
 * Post Types: wp_template
 * Inserter: false
 *
 * @package sd-theme-2026
 */

/*
 * Connected reviews — live's `#review`.
 *
 * A `<div>`, not a `<section>`, and no heading. Live's `#review` has none —
 * the carousel opens straight into the first quote — and a `<section>` with
 * no accessible name is a landmark that announces itself and then says
 * nothing. Same composition, and same reasoning, as the tour single's
 * reviews band.
 *
 * One slide at a time, as live does (`slidesToShow: 1`).
 * `review-related-destination` resolves through the destination's
 * `review_to_destination` meta — 11 on Botswana — and the wrapper removes
 * the band where there are none.
 */
?>
<!-- wp:group {"metadata":{"name":"Destination Reviews"},"align":"full","className":"is-style-light-page-section lsx-review-related-destination-query-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-light-page-section lsx-review-related-destination-query-wrapper">

	<!-- wp:query {"query":{"perPage":6,"pages":0,"offset":0,"postType":"review","order":"desc","orderBy":"date","search":"","exclude":[],"sticky":"","inherit":false},"hasCustomClass":true,"align":"wide","className":"is-style-slider-frame lsx-to-slider","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
	<div class="wp-block-query alignwide is-style-slider-frame lsx-to-slider">
		<!-- wp:post-template {"className":"lsx-review-related-destination-query","layout":{"type":"grid","columnCount":1}} -->
			<!-- wp:pattern {"slug":"sd-theme-2026/card-review-quote"} /-->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->

</div>
<!-- /wp:group -->
