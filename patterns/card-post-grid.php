<?php
/**
 * Title: Card — Post (Grid)
 * Slug: sd-theme-2026/card-post-grid
 * Description: The post tile the homepage "Tales from our trails" carousel renders, and the shape the blog landing would use if it ever went to a grid. A landscape featured image above a tinted panel carrying a centred title, a date-and-categories byline, the excerpt, and a ruled tag footer that disappears when the post carries no tags.
 * Categories: sd-theme-2026/card, sd-theme-2026/posts
 * Keywords: card, grid, tile, post, blog, news, carousel, byline, tags
 * Viewport Width: 480
 * Block Types: core/post-template
 * Post Types: post
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * Finalised from the composition signed off on the dev homepage (2026-08-27),
 * captured back out of the DB front-page override into this file so the pattern
 * is the source of truth again. Everything here — the 16/9 image, the
 * neutral-100 panel with shadow-200, the centred title, the date/category
 * byline, the "/.." excerpt more-text and the ruled tag footer — is that
 * composition unchanged, with one later change: the corners are square. The
 * radius|100 the signed-off version carried was removed on 2026-08-31 — the card
 * is a square panel now, and patterns/card-tour-compact.php matches it.
 * The whole card is clickable via `sdLinkTo: "post"`
 * (sd-enhancements' group-link module), so nothing inside needs its own link
 * except the blocks that already carry one.
 *
 * ## The tag footer hides itself when the post has no tags
 *
 * `core/post-terms` returns an empty string when the post has no terms in the
 * taxonomy — wp-includes/blocks/post-terms.php:57. The block vanishes; its
 * wrapper does not. Left alone, an untagged post renders this footer as a bare
 * primary-300 rule with padding under the excerpt: a divider dividing nothing.
 *
 * The rule that hides it lives in the card's style partial,
 * styles/sections/cards/post-grid-card.json, and reads "any group inside this
 * card that rendered no element children is hidden" — keyed off core's own
 * `.wp-block-group`, with no hand-rolled helper class added here. That is the
 * AGENTS.md rule ("never off a hand-written helper class"), and it means this
 * markup carries no hand-rolled class for it: the fix is
 * entirely in the style JSON, so the dev front-page DB override needed no edit
 * for it. (The square corners above *are* a markup change, so that override is
 * now one radius behind this file — see the reconciliation note in the
 * changelog.) It generalises correctly too — a post with no featured image empties the
 * Media group, and an empty box is no more wanted there than an empty rule.
 *
 * Two reasons it is CSS and not a visibility control: the condition is per-post
 * inside a Query Loop, which Block Visibility cannot express — its conditions
 * are request-level (role, date, screen size, query string), not per-item; and
 * the emptiness is only knowable after `core/post-terms` has rendered, so there
 * is nothing to branch on at block level. Keeping the group in the markup also
 * keeps it selectable and labelled in the editor, where an author can still see
 * and edit the footer that a tagless post will hide.
 *
 * ## The byline is base, not tiny
 *
 * The date and category rows were `100` (0.75rem) against the tag row's `200`,
 * which put three sizes on a card carrying four meta rows. Both are `200` now —
 * Zared's call, 2026-09-16 — so the byline reads at the same weight as the tags
 * beneath it and as the tour card's meta block on the same template. The 2px
 * `padding-block` is the optical alignment applied across the meta rows there;
 * see patterns/card-tour-compact.php for what it settles.
 */
?>
<!-- wp:group {"metadata":{"name":"Card — Post (Grid)"},"className":"is-style-post-grid-card","style":{"shadow":"var:preset|shadow|200","spacing":{"blockGap":"var:preset|spacing|0"}},"backgroundColor":"neutral-100","layout":{"type":"default"},"sdLinkTo":"post"} -->
<div class="wp-block-group is-style-post-grid-card has-neutral-100-background-color has-background" style="box-shadow:var(--wp--preset--shadow--200)">
	<!-- wp:group {"metadata":{"name":"Media"},"className":"post-grid-card__media","layout":{"type":"default"}} -->
	<div class="wp-block-group post-grid-card__media">
		<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9"} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"Body"},"style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|20","right":"var:preset|spacing|20"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--20)">
		<!-- wp:post-title {"level":3,"isLink":true,"style":{"typography":{"textAlign":"center"}}} /-->

		<!-- wp:group {"metadata":{"name":"Byline"},"style":{"spacing":{"blockGap":"var:preset|spacing|0"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">
			<!-- wp:post-date {"format":"F j, Y","metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}},"style":{"typography":{"textAlign":"center"},"spacing":{"padding":{"top":"2px","bottom":"2px"}}},"fontSize":"200"} /-->

			<!-- wp:post-terms {"term":"category","prefix":"Posted in: ","style":{"typography":{"textAlign":"center"},"spacing":{"padding":{"top":"2px","bottom":"2px"}}},"fontSize":"200"} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:post-excerpt {"moreText":"/..","showMoreOnNewLine":false,"excerptLength":30,"style":{"typography":{"textAlign":"center"}}} /-->

		<!-- wp:group {"metadata":{"name":"Tags"},"style":{"border":{"top":{"color":"var:preset|color|primary-300","width":"1px"}},"spacing":{"padding":{"top":"var:preset|spacing|20"}}},"layout":{"type":"constrained","justifyContent":"left"}} -->
		<div class="wp-block-group" style="border-top-color:var(--wp--preset--color--primary-300);border-top-width:1px;padding-top:var(--wp--preset--spacing--20)">
			<!-- wp:post-terms {"term":"post_tag","prefix":"Tags: ","style":{"typography":{"textAlign":"center"}},"fontSize":"200"} /-->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
