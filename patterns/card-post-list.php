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

/*
 * Imported from the Site Editor on dev 2026-09-16 (wp_template 65949, the Blog
 * Home override) — the row as authored against real migrated posts rather than
 * against the six local fixtures.
 *
 * ## The byline is composed, not `core/post-author`
 *
 * `core/post-author` renders its `byline` and the name into one flex container
 * it owns, so the word "by" could not be spaced against the name independently
 * of the gap between the byline's three parts. Splitting it into a literal
 * paragraph plus `core/post-author-name` puts the pair in their own nowrap
 * group at `spacing|5` while the byline itself opens out to `spacing|20` —
 * date · by author · category, which is live's rhythm. `1em` on the name keeps
 * it at the group's 200 rather than inheriting the block's own default.
 *
 * ## The tag rule is `1px`, and that literal is deliberate
 *
 * This block is **dynamic**, so its `style` object is resolved by the
 * server-side style engine — which expands `var:preset|…` only, and only for
 * properties declaring `css_vars`. `border.width` declares none. Measured on
 * local 2026-09-16, `wp_style_engine_get_styles()` given
 * `border.top.width: var:custom|border-width|100` alongside
 * `border.top.color: var:preset|color|neutral-300` returns
 * `border-top-color:var(--wp--preset--color--neutral-300);` and nothing else —
 * the width is dropped silently. So the `var:custom|border-width|200` this file
 * previously carried had never drawn a rule at all; the visible hairline came
 * from elsewhere. A literal is the only form that renders here.
 * → AGENTS.md, "on a *dynamic* block, `var:custom|…` is silently dropped"
 *
 * The `margin.top` that sat beside it is gone: the column above sets a
 * `blockGap`, which compiles to `margin-block: 0` on every child on the front
 * end and loses to the margin in the editor. The parent owns the spacing.
 * → AGENTS.md
 */

?>
<!-- wp:columns {"metadata":{"name":"Post Card — List"},"className":"is-style-blog-card-wide","style":{"spacing":{"blockGap":"var:preset|spacing|40","margin":{"bottom":"var:preset|spacing|50"},"padding":{"bottom":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns is-style-blog-card-wide" style="margin-bottom:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)">
	<!-- wp:column {"verticalAlignment":"top","width":"30%"} -->
	<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:30%">
		<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3"} /-->
	</div>
	<!-- /wp:column -->

	<!-- wp:column {"verticalAlignment":"top","style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|30"}}}} -->
	<div class="wp-block-column is-vertically-aligned-top" style="padding-top:var(--wp--preset--spacing--30)">
		<!-- wp:post-title {"level":3,"isLink":true} /-->

		<!-- wp:group {"metadata":{"name":"Byline"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"},"typography":{"fontStyle":"italic","lineHeight":"var:custom|line-height|body"},"elements":{"link":{"color":{"text":"var:preset|color|brand-600"}}}},"textColor":"brand-600","fontSize":"200","layout":{"type":"flex","flexWrap":"wrap"}} -->
		<div class="wp-block-group has-brand-600-color has-text-color has-link-color has-200-font-size" style="font-style:italic;line-height:var(--wp--custom--line-height--body)">
			<!-- wp:post-date {"format":"F j, Y","metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}}} /-->

			<!-- wp:group {"style":{"layout":{"selfStretch":"fit","flexSize":null},"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top","justifyContent":"left"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph {"fontSize":"200"} -->
				<p class="has-200-font-size"><?php echo esc_html_x( 'by', 'precedes the post author name', 'sd-theme-2026' ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:post-author-name {"isLink":true,"style":{"typography":{"fontSize":"1em"}}} /-->
			</div>
			<!-- /wp:group -->

			<!-- wp:post-terms {"term":"category","prefix":"Posted in: "} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:post-excerpt {"moreText":".../","showMoreOnNewLine":false,"excerptLength":40,"style":{"typography":{"lineHeight":"var:custom|line-height|body"}}} /-->

		<!-- wp:post-terms {"term":"post_tag","separator":" ","prefix":"Tags: ","style":{"spacing":{"padding":{"top":"var:preset|spacing|20"}},"border":{"top":{"color":"var:preset|color|neutral-300","width":"1px"}},"typography":{"fontStyle":"italic"},"elements":{"link":{"color":{"text":"var:preset|color|brand-600"}}}},"textColor":"brand-600","fontSize":"200"} /-->
	</div>
	<!-- /wp:column -->
</div>
<!-- /wp:columns -->
