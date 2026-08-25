<?php
/**
 * Title: Card — Tour (Compact)
 * Slug: sd-theme-2026/card-tour-compact
 * Description: The related-tours card the carousels on every Tour Operator single render — "Tours in Botswana" and the like — and the same card the mega menu's featured slots use. A landscape featured image above a tinted panel carrying the centred title, the travel styles and the connected destinations.
 * Categories: sd-theme-2026/card, sd-theme-2026/tour-operator
 * Keywords: card, compact, tile, carousel, related, tour
 * Viewport Width: 480
 * Block Types: core/post-template
 * Post Types: tour
 * Inserter: true
 */
?>
<!-- wp:group {"metadata":{"name":"Tour Card — Compact"},"className":"is-style-listing-card-compact","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-listing-card-compact">
	<!-- wp:group {"metadata":{"name":"Media"},"className":"listing-card-compact__media","layout":{"type":"default"}} -->
	<div class="wp-block-group listing-card-compact__media">
		<!-- wp:post-featured-image {"isLink":true} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"Body"},"className":"listing-card-compact__body","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group listing-card-compact__body">
		<!-- wp:post-title {"textAlign":"center","level":4,"isLink":true} /-->

		<!-- wp:post-terms {"term":"travel-style","prefix":"Travel Style: "} /-->

		<!-- wp:paragraph {"metadata":{"name":"Destinations","bindings":{"content":{"source":"lsx/post-connection","args":{"key":"destination_to_tour"}}}},"className":"lsx-destination-to-tour-wrapper","prefix":"Destinations:","prefixBold":true} -->
		<p class="lsx-destination-to-tour-wrapper"></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
