<?php
/**
 * Title: Card — Accommodation (List)
 * Slug: sd-theme-2026/card-accommodation-list
 * Description: The horizontal accommodation result row the FacetWP accommodation search renders. The same shape as the tour row with no tagline — accommodation carries no duration — and a meta strip that leads with the price band, then the accommodation type and the connected destination. Drop it into a Query Loop's post template.
 * Categories: sd-theme-2026/card, sd-theme-2026/tour-operator
 * Keywords: card, list, row, accommodation, lodge, search, archive, facetwp
 * Viewport Width: 1280
 * Block Types: core/post-template
 * Post Types: accommodation
 * Inserter: true
 *
 * @package sd-theme-2026
 */

?>
<!-- wp:group {"metadata":{"name":"Accommodation Card — List"},"className":"is-style-listing-card-list","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|30"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-listing-card-list" style="margin-bottom:var(--wp--preset--spacing--30)">
	<!-- wp:group {"metadata":{"name":"Media"},"className":"listing-card__media","layout":{"type":"default"}} -->
	<div class="wp-block-group listing-card__media">
		<!-- wp:post-featured-image {"isLink":true} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"Wrapper"},"className":"listing-card__wrapper","layout":{"type":"default"}} -->
	<div class="wp-block-group listing-card__wrapper">
		<!-- wp:group {"metadata":{"name":"Body"},"className":"listing-card__body","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group listing-card__body">
			<!-- wp:post-title {"level":3,"isLink":true} /-->

			<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":40,"fontSize":"200"} /-->

			<!-- wp:read-more {"content":"View more","className":"listing-card__more","fontSize":"200"} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"metadata":{"name":"Meta"},"className":"listing-card__meta","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group listing-card__meta">
			<!-- wp:paragraph {"metadata":{"name":"Price Rating","bindings":{"content":{"source":"sd/post-meta","args":{"key":"price_rating","format":"price-band"}}}},"className":"lsx-price-rating-wrapper","prefix":"Price Rating:","prefixBold":true} -->
			<p class="lsx-price-rating-wrapper"></p>
			<!-- /wp:paragraph -->

			<!-- wp:post-terms {"term":"accommodation-type","prefix":"Type: "} /-->

			<!-- wp:paragraph {"metadata":{"name":"Location","bindings":{"content":{"source":"lsx/post-connection","args":{"key":"destination_to_accommodation"}}}},"className":"lsx-destination-to-accommodation-wrapper","prefix":"Location:","prefixBold":true} -->
			<p class="lsx-destination-to-accommodation-wrapper"></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
