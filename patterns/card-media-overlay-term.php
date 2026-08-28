<?php
/**
 * Title: Card — Media Overlay (Term)
 * Slug: sd-theme-2026/card-media-overlay-term
 * Description: A travel-style tile — the term's stored image cropped to a portrait tile with the linked term name centred over it on a dark scrim. The term twin of Card — Media Overlay.
 * Categories: sd-theme-2026/card, sd-theme-2026/tour-operator
 * Keywords: card, tile, overlay, scrim, term, taxonomy, travel style, image
 * Viewport Width: 480
 * Block Types: core/term-template
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * The term twin of `card-media-overlay.php`, and deliberately a copy of it
 * rather than a parameterisation.
 *
 * Two of the three blocks differ by name — `core/term-name` instead of
 * `core/post-title` — and a pattern is markup, so there is nothing to branch on
 * without turning the file into PHP that assembles block comments. The two
 * files are ten lines each and are kept in step by hand; the *appearance* is
 * shared properly, in `styles/sections/cards/media-overlay-card.json`, so a
 * change to the scrim, the type or the hover lift still lands on both.
 *
 * ## Why the image is `core/post-featured-image` and not `sd/term-image`
 *
 * It looks like the wrong block and is the right one. Tour Operator filters
 * `render_block_core/post-featured-image` and, when the block has a `termId` in
 * context, swaps in the term's `thumbnail` meta, wraps it in `get_term_link()`
 * and sets the term name as `alt`
 * (tour-operator/includes/classes/frontend/class-taxonomy-images.php:46,60-64,
 * 180-272). It emits the same `<figure style="aspect-ratio:…">` wrapper core
 * does, so the tile geometry the card style depends on is unchanged.
 *
 * That branch is only reachable because
 * `SD\Enhancements\Compat::declare_term_context_on_featured_image()` adds
 * `termId` and `taxonomy` to the block's `uses_context` — TO reads a key it
 * never declared, so core filters it out before it arrives. The same shim is
 * what makes the homepage brands shelf work; the reasoning, and the note to
 * retire it once Tour Operator declares the context itself, are on that method.
 *
 * `sd/term-image` is the other candidate and does not fit: its `metaKey`
 * allow-list is `sd_thumbnail` / `sd_thumbnail_color`, the two fields
 * `sd-enhancements` stores against `accommodation-brand`. Travel styles carry
 * Tour Operator's `thumbnail` instead, and widening that allow-list to reach it
 * would duplicate a resolver Tour Operator already ships.
 *
 * ## The whole tile is the link
 *
 * `sdLinkTo: "term"` — `SD\Enhancements\GroupLink` resolves the term in context
 * through `get_term_link()` and stretches an overlay across the card. It tests
 * the rendered card for a link already pointing at the same URL and, finding
 * the two here, leaves the overlay presentational rather than adding a third
 * tab stop to every tile. Keyboard users reach the term through the name; mouse
 * users get the whole photograph.
 */

?>
<!-- wp:group {"metadata":{"name":"Media Overlay Card"},"className":"is-style-media-overlay-card","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"},"sdLinkTo":"term"} -->
<div class="wp-block-group is-style-media-overlay-card">
	<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"3/4"} /-->

	<!-- wp:group {"metadata":{"name":"Scrim"},"className":"media-overlay-card__scrim","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group media-overlay-card__scrim" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
		<!-- wp:term-name {"level":3,"isLink":true,"style":{"typography":{"textAlign":"center"}}} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
