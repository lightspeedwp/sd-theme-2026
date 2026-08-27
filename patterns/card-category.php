<?php
/**
 * Title: Card — Category Tile
 * Slug: sd-theme-2026/card-category
 * Description: The "Browse by category" tile — the term's thumbnail with the linked term name centred over it. Drop it into a Term Template.
 * Categories: sd-theme-2026/card, sd-theme-2026/posts
 * Keywords: card, tile, category, term, taxonomy, browse, overlay
 * Viewport Width: 480
 * Block Types: core/term-template
 * Inserter: true
 *
 * @package sd-theme-2026
 */

?>
<!-- wp:group {"metadata":{"name":"Category Tile"},"className":"is-style-category-card","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-category-card">
	<!-- wp:image {"metadata":{"name":"Category Image","bindings":{"url":{"source":"sd/term-meta","args":{"key":"sd_thumbnail","format":"attachment-url","size":"medium_large"}},"alt":{"source":"sd/term-meta","args":{"key":"sd_thumbnail","format":"attachment-alt"}}}},"aspectRatio":"16/9","scale":"cover","sizeSlug":"medium_large","linkDestination":"none"} -->
	<figure class="wp-block-image size-medium_large"><img alt="" style="aspect-ratio:16/9;object-fit:cover"/></figure>
	<!-- /wp:image -->

	<!-- wp:group {"metadata":{"name":"Label"},"className":"category-card__label","style":{"spacing":{"padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group category-card__label" style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)">
		<!-- wp:term-name {"level":4,"isLink":true,"style":{"typography":{"textAlign":"center"}}} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
