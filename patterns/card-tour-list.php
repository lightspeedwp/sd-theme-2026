<?php
/**
 * Title: Card — Tour (List)
 * Slug: sd-theme-2026/card-tour-list
 * Description: The horizontal tour result row the FacetWP tour search renders — featured image, title, tagline, excerpt and a read-more beside a tinted meta strip carrying the connected destination and travel style. Drop it into a Query Loop's post template.
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
<!-- wp:columns {"metadata":{"name":"Tour Card — List"},"className":"is-style-listing-card-list","style":{"spacing":{"blockGap":"0","margin":{"bottom":"var:preset|spacing|30"}}}} -->
<div class="wp-block-columns is-style-listing-card-list" style="margin-bottom:var(--wp--preset--spacing--30)">
	<!-- wp:column {"width":"25%"} -->
	<div class="wp-block-column" style="flex-basis:25%">
		<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3"} /-->
	</div>
	<!-- /wp:column -->

	<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
	<div class="wp-block-column" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
		<!-- wp:post-title {"level":3,"isLink":true} /-->

		<!-- wp:paragraph {"metadata":{"name":"Tagline","bindings":{"content":{"source":"core/post-meta","args":{"key":"tagline"}}}},"className":"lsx-tagline-wrapper","textColor":"brand-500","fontSize":"200","fontFamily":"heading","style":{"typography":{"textTransform":"uppercase","letterSpacing":"var:custom|letter-spacing|heading"}}} -->
		<p class="lsx-tagline-wrapper has-brand-500-color has-text-color has-200-font-size has-heading-font-family" style="letter-spacing:var(--wp--custom--letter-spacing--heading);text-transform:uppercase"></p>
		<!-- /wp:paragraph -->

		<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":40,"fontSize":"200"} /-->

		<!-- wp:read-more {"content":"View more","fontSize":"200"} /-->
	</div>
	<!-- /wp:column -->

	<!-- wp:column {"width":"25%","backgroundColor":"neutral-200","style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"}},"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200"} -->
	<div class="wp-block-column has-neutral-200-background-color has-background has-200-font-size" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40);line-height:var(--wp--custom--line-height--body);flex-basis:25%">
		<!-- wp:paragraph {"metadata":{"name":"Location","bindings":{"content":{"source":"lsx/post-connection","args":{"key":"destination_to_tour"}}}},"className":"lsx-destination-to-tour-wrapper","prefix":"Location:","prefixBold":true} -->
		<p class="lsx-destination-to-tour-wrapper"></p>
		<!-- /wp:paragraph -->

		<!-- wp:post-terms {"term":"travel-style","prefix":"Travel Style: ","fontSize":"200","style":{"typography":{"lineHeight":"var:custom|line-height|body"}}} /-->
	</div>
	<!-- /wp:column -->
</div>
<!-- /wp:columns -->
