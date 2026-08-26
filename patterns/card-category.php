<?php
/**
 * Title: Card — Category
 * Slug: sd-theme-2026/card-category
 * Description: The short "Browse By Category" tile that runs above the live blog landing and every category archive — a 100px band of photograph under a scrim, with the category name centred over it. The scrim deepens on hover, which is the inverse of every other card on the site. Drop it into a Terms Query's term template. The image is bound to the term's featured image and falls back to whatever is placed in the editor.
 * Categories: sd-theme-2026/card, sd-theme-2026/posts
 * Keywords: card, category, term, tile, taxonomy, browse, strip
 * Viewport Width: 400
 * Block Types: core/term-template
 * Inserter: true
 *
 * @package sd-theme-2026
 */

?>
<!-- wp:group {"metadata":{"name":"Category Tile"},"className":"is-style-category-card","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-category-card">
	<!-- wp:group {"metadata":{"name":"Media"},"className":"category-card__media","layout":{"type":"default"}} -->
	<div class="wp-block-group category-card__media">
		<!-- wp:image {"metadata":{"name":"Category Image","bindings":{"url":{"source":"sd/term-meta","args":{"key":"sd_thumbnail","format":"attachment-url","size":"medium_large"}},"alt":{"source":"sd/term-meta","args":{"key":"sd_thumbnail","format":"attachment-alt"}}}},"sizeSlug":"medium_large","linkDestination":"none"} -->
		<figure class="wp-block-image size-medium_large"><img alt=""/></figure>
		<!-- /wp:image -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"Label"},"className":"category-card__label","layout":{"type":"constrained"}} -->
	<div class="wp-block-group category-card__label">
		<!-- wp:term-name {"textAlign":"center","level":4,"isLink":true} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
