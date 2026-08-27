<?php
/**
 * Title: Card — Tour (Compact)
 * Slug: sd-theme-2026/card-tour-compact
 * Description: The compact tour tile the related-tours carousels carry on every Tour Operator single — a landscape featured image above a tinted panel centring the title, the travel style and the connected destinations.
 * Categories: sd-theme-2026/card, sd-theme-2026/tour-operator
 * Keywords: card, compact, tile, tour, related, carousel
 * Viewport Width: 480
 * Block Types: core/post-template
 * Post Types: tour
 * Inserter: true
 *
 * @package sd-theme-2026
 */

?>
<!-- wp:group {"metadata":{"name":"Tour Card — Compact"},"className":"is-style-listing-card-compact","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-listing-card-compact">
	<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9"} /-->

	<!-- wp:group {"metadata":{"name":"Body"},"style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|30","bottom":"var:preset|spacing|60","left":"var:preset|spacing|30"}},"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200","layout":{"type":"constrained"}} -->
	<div class="wp-block-group has-200-font-size" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--30);line-height:var(--wp--custom--line-height--body)">
		<!-- wp:post-title {"level":4,"isLink":true,"style":{"typography":{"textAlign":"center"}}} /-->

		<!-- wp:post-terms {"term":"travel-style","prefix":"Travel Style: ","style":{"typography":{"textAlign":"center"}}} /-->

		<!-- wp:paragraph {"metadata":{"name":"Destinations","bindings":{"content":{"source":"lsx/post-connection","args":{"key":"destination_to_tour"}}}},"className":"lsx-destination-to-tour-wrapper","prefix":"Destinations:","prefixBold":true,"style":{"typography":{"textAlign":"center"}}} -->
		<p class="has-text-align-center lsx-destination-to-tour-wrapper"></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
