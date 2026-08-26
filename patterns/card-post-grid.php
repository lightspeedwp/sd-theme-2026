<?php
/**
 * Title: Card — Post (Grid)
 * Slug: sd-theme-2026/card-post-grid
 * Description: The post tile the homepage "Tales from our trails" carousel renders, and the shape the blog landing would use if it ever went to a grid. A landscape featured image above a tinted panel carrying a centred title, an italic date-and-categories byline in brand orange, and the excerpt.
 * Categories: sd-theme-2026/card, sd-theme-2026/posts
 * Keywords: card, grid, tile, post, blog, news, carousel, byline
 * Viewport Width: 480
 * Block Types: core/post-template
 * Post Types: post
 * Inserter: true
 */
?>
<!-- wp:group {"metadata":{"name":"Post Grid Card"},"className":"is-style-post-grid-card","layout":{"type":"default"}} -->
<div class="wp-block-group is-style-post-grid-card">
	<!-- wp:group {"metadata":{"name":"Media"},"className":"post-grid-card__media","layout":{"type":"default"}} -->
	<div class="wp-block-group post-grid-card__media">
		<!-- wp:post-featured-image {"isLink":true} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"Body"},"className":"post-grid-card__body","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group post-grid-card__body">
		<!-- wp:post-title {"textAlign":"center","level":3,"isLink":true} /-->

		<!-- wp:group {"metadata":{"name":"Byline"},"className":"post-grid-card__byline","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
		<div class="wp-block-group post-grid-card__byline">
			<!-- wp:post-date {"format":"F j, Y","isLink":false} /-->

			<!-- wp:post-terms {"term":"category","prefix":". Posted in: "} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:post-excerpt {"textAlign":"center","moreText":"","showMoreOnNewLine":false,"excerptLength":30} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
