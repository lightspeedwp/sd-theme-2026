<?php
/**
 * Title: Card — Post (List)
 * Slug: sd-theme-2026/card-post-list
 * Description: The post row the live blog landing and every category archive render. A one-third featured image on the leading edge, and a body column carrying the title, a single italic byline line reading date, author and categories, the excerpt, and a rule closing the row above the tags. Left-aligned on desktop; the image goes full-width above centred text on a phone.
 * Categories: sd-theme-2026/card, sd-theme-2026/posts
 * Keywords: card, list, row, post, blog, news, archive, category, byline, tags
 * Viewport Width: 1280
 * Block Types: core/post-template
 * Post Types: post
 * Inserter: true
 */
?>
<!-- wp:group {"metadata":{"name":"Post Card — List"},"className":"is-style-blog-card-wide","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-blog-card-wide" style="margin-bottom:var(--wp--preset--spacing--50)">
	<!-- wp:group {"metadata":{"name":"Media"},"className":"blog-card-wide__media","layout":{"type":"default"}} -->
	<div class="wp-block-group blog-card-wide__media">
		<!-- wp:post-featured-image {"isLink":true} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"Body"},"className":"blog-card-wide__body","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group blog-card-wide__body">
		<!-- wp:post-title {"level":3,"isLink":true} /-->

		<!-- wp:group {"metadata":{"name":"Byline"},"className":"blog-card-wide__meta","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
		<div class="wp-block-group blog-card-wide__meta">
			<!-- wp:post-date {"format":"F j, Y","isLink":false} /-->

			<!-- wp:post-author {"showAvatar":false,"showBio":false,"byline":"by","isLink":true} /-->

			<!-- wp:post-terms {"term":"category","prefix":"Posted in: "} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:post-excerpt {"moreText":".../","showMoreOnNewLine":false,"excerptLength":40} /-->

		<!-- wp:post-terms {"term":"post_tag","separator":" ","prefix":"Tags: ","className":"blog-card-wide__tags"} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
