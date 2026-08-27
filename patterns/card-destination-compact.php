<?php
/**
 * Title: Card — Destination (Compact)
 * Slug: sd-theme-2026/card-destination-compact
 * Description: The compact destination tile the destination rails carry on every Tour Operator single — a landscape featured image above a tinted panel centring the title, the excerpt and a read-more.
 * Categories: sd-theme-2026/card, sd-theme-2026/tour-operator
 * Keywords: card, compact, tile, destination, related, carousel
 * Viewport Width: 480
 * Block Types: core/post-template
 * Post Types: destination
 * Inserter: true
 *
 * @package sd-theme-2026
 */

?>
<!-- wp:group {"metadata":{"name":"Destination Card — Compact"},"className":"is-style-listing-card-compact","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-listing-card-compact">
	<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9"} /-->

	<!-- wp:group {"metadata":{"name":"Body"},"style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|30","bottom":"var:preset|spacing|60","left":"var:preset|spacing|30"}},"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200","layout":{"type":"constrained"}} -->
	<div class="wp-block-group has-200-font-size" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--30);line-height:var(--wp--custom--line-height--body)">
		<!-- wp:post-title {"level":4,"isLink":true,"style":{"typography":{"textAlign":"center"}}} /-->

		<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":30,"style":{"typography":{"textAlign":"center"}}} /-->

		<!-- wp:read-more {"content":"View more"} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
