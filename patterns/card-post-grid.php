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
 *
 * @package sd-theme-2026
 */

?>
<!-- wp:group {"metadata":{"name":"Post Grid Card"},"className":"is-style-post-grid-card","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-post-grid-card">
	<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9"} /-->

	<!-- wp:group {"metadata":{"name":"Body"},"style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|30","bottom":"var:preset|spacing|40","left":"var:preset|spacing|30"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--30)">
		<!-- wp:post-title {"level":3,"isLink":true,"style":{"typography":{"textAlign":"center"}}} /-->

		<!-- wp:group {"metadata":{"name":"Byline"},"style":{"spacing":{"blockGap":"0"},"typography":{"fontStyle":"italic","lineHeight":"var:custom|line-height|body"},"elements":{"link":{"color":{"text":"var:preset|color|brand-600"}}}},"textColor":"brand-600","fontSize":"100","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
		<div class="wp-block-group has-brand-600-color has-text-color has-link-color has-100-font-size" style="font-style:italic;line-height:var(--wp--custom--line-height--body)">
			<!-- wp:post-date {"format":"F j, Y","isLink":false} /-->

			<!-- wp:post-terms {"term":"category","prefix":". Posted in: "} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":30,"style":{"typography":{"textAlign":"center","lineHeight":"var:custom|line-height|body"}},"fontSize":"200"} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
