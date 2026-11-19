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
 * ## The byline, excerpt and tags share one size
 *
 * The rows were `200` (16px) and the excerpt `300`. All of them now take
 * `custom|font-size|card` (15px) from styles/sections/cards/post-grid-card.json
 * — the size the compact tour and accommodation cards use — and none carries a
 * `fontSize` attribute, because a preset size writes the `!important`
 * `has-*-font-size` class and would beat the card style. BugHerd #20,
 * 2026-10-30. The 2px `padding-block` is the optical alignment applied across
 * the meta rows; see patterns/card-tour-compact.php for what it settles.
 *
 * ## The body padding is a step up from the signed-off composition
 *
 * BugHerd #20 (2026-10-30): with body copy at its larger size the panel read
 * crowded, so the Body group steps up two rungs each way — 30/20 to 50/40
 * (block/inline), the padding the compact tour and accommodation cards now
 * share. Live is no guide to the number: its panel has no padding at
 * all and the excerpt runs edge to edge; its air comes from 24px margins
 * between title, byline and excerpt (measured 2026-10-30 at 1200px). Like the
 * square corners, this is a markup change, so the dev front-page DB override
 * is behind this file until it is reconciled.
 */
?>
<!-- wp:group {"metadata":{"name":"Card — Post (Grid)"},"className":"is-style-post-grid-card","style":{"shadow":"var:preset|shadow|200","spacing":{"blockGap":"var:preset|spacing|0"}},"backgroundColor":"neutral-100","layout":{"type":"default"},"sdLinkTo":"post"} -->
<div class="wp-block-group is-style-post-grid-card has-neutral-100-background-color has-background" style="box-shadow:var(--wp--preset--shadow--200)">
	<!-- wp:group {"metadata":{"name":"Media"},"className":"post-grid-card__media","layout":{"type":"default"}} -->
	<div class="wp-block-group post-grid-card__media">
		<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9"} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"Body"},"style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--40)">
		<!-- wp:post-title {"level":3,"isLink":true,"style":{"typography":{"textAlign":"center"}}} /-->

		<!-- wp:group {"metadata":{"name":"Byline"},"style":{"spacing":{"blockGap":"var:preset|spacing|0"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">
			<!-- wp:post-date {"format":"F j, Y","metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}},"style":{"typography":{"textAlign":"center"},"spacing":{"padding":{"top":"2px","bottom":"2px"}}}} /-->

			<!-- wp:post-terms {"term":"category","prefix":"Posted in: ","style":{"typography":{"textAlign":"center"},"spacing":{"padding":{"top":"2px","bottom":"2px"}}}} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:post-excerpt {"moreText":"/..","showMoreOnNewLine":false,"excerptLength":30,"style":{"typography":{"textAlign":"center"}}} /-->

		<!-- wp:group {"metadata":{"name":"Tags"},"style":{"border":{"top":{"color":"var:preset|color|primary-300","width":"1px"}},"spacing":{"padding":{"top":"var:preset|spacing|20"}}},"layout":{"type":"constrained","justifyContent":"left"}} -->
		<div class="wp-block-group" style="border-top-color:var(--wp--preset--color--primary-300);border-top-width:1px;padding-top:var(--wp--preset--spacing--20)">
			<!-- wp:post-terms {"term":"post_tag","prefix":"Tags: ","style":{"typography":{"textAlign":"center"}}} /-->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
