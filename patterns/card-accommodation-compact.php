<?php
/**
 * Title: Card — Accommodation (Compact)
 * Slug: sd-theme-2026/card-accommodation-compact
 * Description: The related-accommodation card the carousels on every Tour Operator single render — "Accommodation in Botswana" and the like. A landscape featured image above a tinted panel carrying the centred title, the price band and the connected destination.
 * Categories: sd-theme-2026/card, sd-theme-2026/tour-operator
 * Keywords: card, compact, tile, carousel, related, accommodation, lodge
 * Viewport Width: 480
 * Block Types: core/post-template
 * Post Types: accommodation
 * Inserter: true
 *
 * @package sd-theme-2026
 */

?>
<!-- wp:group {"metadata":{"name":"Accommodation Card — Compact"},"className":"is-style-listing-card-compact","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-listing-card-compact">
	<!-- wp:group {"metadata":{"name":"Media"},"className":"listing-card-compact__media","layout":{"type":"default"}} -->
	<div class="wp-block-group listing-card-compact__media">
		<!-- wp:post-featured-image {"isLink":true} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"Body"},"className":"listing-card-compact__body","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group listing-card-compact__body">
		<!-- wp:post-title {"textAlign":"center","level":4,"isLink":true} /-->

		<!-- wp:paragraph {"metadata":{"name":"Price Rating","bindings":{"content":{"source":"sd/post-meta","args":{"key":"price_rating","format":"price-band"}}}},"className":"lsx-price-rating-wrapper","prefix":"Price Rating:","prefixBold":true} -->
		<p class="lsx-price-rating-wrapper"></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"metadata":{"name":"Location","bindings":{"content":{"source":"lsx/post-connection","args":{"key":"destination_to_accommodation"}}}},"className":"lsx-destination-to-accommodation-wrapper","prefix":"Location:","prefixBold":true} -->
		<p class="lsx-destination-to-accommodation-wrapper"></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
