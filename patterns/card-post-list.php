<?php
/**
 * Title: Post Card — List
 * Slug: sd-theme-2026/card-post-list
 * Description: The wide blog-landing row — a portrait featured image beside the post title, an italic date-author-category byline in brand orange, the excerpt and a ruled tag list.
 * Categories: sd-theme-2026/card, sd-theme-2026/posts
 * Keywords: card, list, row, wide, post, blog, news, byline, tags
 * Viewport Width: 1280
 * Block Types: core/post-template
 * Post Types: post
 * Inserter: true
 *
 * @package sd-theme-2026
 */

?>
<!-- wp:columns {"metadata":{"name":"Post Card — List"},"className":"is-style-blog-card-wide","verticalAlignment":"top","style":{"spacing":{"blockGap":"var:preset|spacing|40","margin":{"bottom":"var:preset|spacing|50"},"padding":{"bottom":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns are-vertically-aligned-top is-style-blog-card-wide" style="margin-bottom:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)">
	<!-- wp:column {"verticalAlignment":"top","width":"31.5%"} -->
	<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:31.5%">
		<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3"} /-->
	</div>
	<!-- /wp:column -->

	<!-- wp:column {"verticalAlignment":"top","style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
	<div class="wp-block-column is-vertically-aligned-top">
		<!-- wp:post-title {"level":3,"isLink":true} /-->

		<!-- wp:group {"metadata":{"name":"Byline"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"},"typography":{"fontStyle":"italic","lineHeight":"var:custom|line-height|body"},"elements":{"link":{"color":{"text":"var:preset|color|brand-600"}}}},"textColor":"brand-600","fontSize":"200","layout":{"type":"flex","flexWrap":"wrap"}} -->
		<div class="wp-block-group has-brand-600-color has-text-color has-link-color has-200-font-size" style="font-style:italic;line-height:var(--wp--custom--line-height--body)">
			<!-- wp:post-date {"format":"F j, Y","isLink":false} /-->

			<!-- wp:post-author {"showAvatar":false,"showBio":false,"byline":"by","isLink":true} /-->

			<!-- wp:post-terms {"term":"category","prefix":"Posted in: "} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:post-excerpt {"moreText":".../","showMoreOnNewLine":false,"excerptLength":40,"style":{"typography":{"lineHeight":"var:custom|line-height|body"}}} /-->

		<!-- wp:post-terms {"term":"post_tag","separator":" ","prefix":"Tags: ","style":{"spacing":{"margin":{"top":"var:preset|spacing|30"},"padding":{"top":"var:preset|spacing|20"}},"border":{"top":{"color":"var:preset|color|neutral-300","width":"var:custom|border-width|200"}},"typography":{"fontStyle":"italic"},"elements":{"link":{"color":{"text":"var:preset|color|brand-600"}}}},"textColor":"brand-600","fontSize":"200"} /-->
	</div>
	<!-- /wp:column -->
</div>
<!-- /wp:columns -->
