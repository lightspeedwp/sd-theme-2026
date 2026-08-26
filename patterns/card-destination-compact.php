<?php
/**
 * Title: Card — Destination (Compact)
 * Slug: sd-theme-2026/card-destination-compact
 * Description: The destination rail's card, carried by the carousels on every Tour Operator single. The same compact shape as the tour and accommodation cards, but with an excerpt in place of the meta read-outs — destinations have neither a price band nor a duration to show — and a read-more, which is the non-carousel state.
 * Categories: sd-theme-2026/card, sd-theme-2026/tour-operator
 * Keywords: card, compact, tile, carousel, related, destination, country, region
 * Viewport Width: 480
 * Block Types: core/post-template
 * Post Types: destination
 * Inserter: true
 */
?>
<!-- wp:group {"metadata":{"name":"Destination Card — Compact"},"className":"is-style-listing-card-compact","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-listing-card-compact">
	<!-- wp:group {"metadata":{"name":"Media"},"className":"listing-card-compact__media","layout":{"type":"default"}} -->
	<div class="wp-block-group listing-card-compact__media">
		<!-- wp:post-featured-image {"isLink":true} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"Body"},"className":"listing-card-compact__body","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group listing-card-compact__body">
		<!-- wp:post-title {"textAlign":"center","level":4,"isLink":true} /-->

		<!-- wp:post-excerpt {"textAlign":"center","moreText":"","showMoreOnNewLine":false,"excerptLength":30} /-->

		<!-- wp:read-more {"content":"View more","className":"listing-card-compact__more"} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
