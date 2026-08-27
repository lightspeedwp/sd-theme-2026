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
<!-- wp:columns {"metadata":{"name":"Accommodation Card — List"},"className":"is-style-listing-card-list","style":{"spacing":{"blockGap":"0","margin":{"bottom":"var:preset|spacing|30"}}}} -->
<div class="wp-block-columns is-style-listing-card-list" style="margin-bottom:var(--wp--preset--spacing--30)">
	<!-- wp:column {"width":"25%"} -->
	<div class="wp-block-column" style="flex-basis:25%">
		<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3"} /-->
	</div>
	<!-- /wp:column -->

	<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
	<div class="wp-block-column" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
		<!-- wp:post-title {"level":3,"isLink":true} /-->

		<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":40,"fontSize":"200"} /-->

		<!-- wp:read-more {"content":"View more","fontSize":"200"} /-->
	</div>
	<!-- /wp:column -->

	<!-- wp:column {"width":"25%","backgroundColor":"neutral-200","style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"}},"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200"} -->
	<div class="wp-block-column has-neutral-200-background-color has-background has-200-font-size" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40);line-height:var(--wp--custom--line-height--body);flex-basis:25%">
		<!-- wp:paragraph {"metadata":{"name":"Price Rating","bindings":{"content":{"source":"sd/post-meta","args":{"key":"price_rating","format":"price-band"}}}},"className":"lsx-price-rating-wrapper","prefix":"Price Rating:","prefixBold":true} -->
		<p class="lsx-price-rating-wrapper"></p>
		<!-- /wp:paragraph -->

		<!-- wp:post-terms {"term":"accommodation-type","prefix":"Type: ","fontSize":"200","style":{"typography":{"lineHeight":"var:custom|line-height|body"}}} /-->

		<!-- wp:paragraph {"metadata":{"name":"Location","bindings":{"content":{"source":"lsx/post-connection","args":{"key":"destination_to_accommodation"}}}},"className":"lsx-destination-to-accommodation-wrapper","prefix":"Location:","prefixBold":true} -->
		<p class="lsx-destination-to-accommodation-wrapper"></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:column -->
</div>
<!-- /wp:columns -->
