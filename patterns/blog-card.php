<?php
/**
 * Title: Blog Card
 * Slug: sd-theme-2026/blog-card
 * Description: A single post card — featured image with hover zoom above the post title. Built for the homepage "Latest News" query loop.
 * Categories: sd-theme-2026/card, sd-theme-2026/posts
 * Keywords: blog, post, card, news, latest, featured image
 * Viewport Width: 1280
 * Block Types: core/post-template
 * Post Types: post
 * Inserter: true
 *
 * @package sd-theme-2026
 */

?>
<!-- wp:group {"metadata":{"name":"Blog Card"},"className":"is-style-blog-card","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
<div class="wp-block-group is-style-blog-card">
	<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/5","className":"is-style-image-hover-zoom"} /-->

	<!-- wp:post-title {"isLink":true,"fontFamily":"heading","fontSize":"300","textColor":"contrast","style":{"typography":{"fontWeight":"var:custom|font-weight|medium","lineHeight":"var:custom|line-height|heading"}}} /-->
</div>
<!-- /wp:group -->
