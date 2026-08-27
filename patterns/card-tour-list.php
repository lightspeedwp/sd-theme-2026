<?php
/**
 * Title: Card — Tour (List)
 * Slug: sd-theme-2026/card-tour-list
 * Description: The horizontal tour result row the FacetWP tour search renders. A square-cropped featured image on the leading quarter, the title, the tour's tagline and its excerpt in the body, and a tinted meta strip closing the trailing third with the connected destinations and the travel styles. Drop it into a Query Loop's post template.
 * Categories: sd-theme-2026/card, sd-theme-2026/tour-operator
 * Keywords: card, list, row, tour, search, archive, facetwp
 * Viewport Width: 1280
 * Block Types: core/post-template
 * Post Types: tour
 * Inserter: true
 *
 * @package sd-theme-2026
 */

?>
<!-- wp:group {"metadata":{"name":"Tour Card — List"},"className":"is-style-listing-card-list","style":{"spacing":{"blockGap":"0","margin":{"bottom":"var:preset|spacing|30"}}},"layout":{"type":"default"}} -->
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

			<!-- wp:paragraph {"metadata":{"name":"Tagline","bindings":{"content":{"source":"core/post-meta","args":{"key":"tagline"}}}},"className":"listing-card__tagline lsx-tagline-wrapper","fontSize":"200"} -->
			<p class="listing-card__tagline lsx-tagline-wrapper has-200-font-size"></p>
			<!-- /wp:paragraph -->

			<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":40,"fontSize":"200"} /-->

			<!-- wp:read-more {"content":"View more","className":"listing-card__more","fontSize":"200"} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"metadata":{"name":"Meta"},"className":"listing-card__meta","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group listing-card__meta">
			<!-- wp:paragraph {"metadata":{"name":"Location","bindings":{"content":{"source":"lsx/post-connection","args":{"key":"destination_to_tour"}}}},"className":"lsx-destination-to-tour-wrapper","prefix":"Location:","prefixBold":true} -->
			<p class="lsx-destination-to-tour-wrapper"></p>
			<!-- /wp:paragraph -->

			<!-- wp:post-terms {"term":"travel-style","prefix":"Travel Style: "} /-->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
