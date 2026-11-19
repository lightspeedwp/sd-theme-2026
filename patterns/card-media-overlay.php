<?php
/**
 * Title: Card — Media Overlay
 * Slug: sd-theme-2026/card-media-overlay
 * Description: A destination tile — the featured image cropped square with the linked title centred over it on a dark scrim.
 * Categories: sd-theme-2026/card, sd-theme-2026/tour-operator
 * Keywords: card, tile, overlay, scrim, destination, image
 * Viewport Width: 480
 * Block Types: core/post-template
 * Post Types: destination
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * The title carries `is-style-shadow-text`.
 *
 * The rest of the card's appearance is shared through the section style
 * `styles/sections/cards/media-overlay-card.json`; the shadow is a block style
 * on the title instead, because that is what it is — the existing variation
 * `styles/blocks/heading/shadow-text.json`, already used by the homepage hero,
 * reused rather than a second copy of the same `text-shadow` inside a section
 * style's `elements.heading`.
 *
 * It is load-bearing rather than decorative: the scrim does not deepen on
 * hover, it *lifts* — all the way to `transparent` — so for half a second the
 * white title sits directly on an undimmed photograph. The shadow is what
 * carries it over a bright frame.
 *
 * ⚠️ The term twin `card-media-overlay-term.php` carries the same class, and
 * `core/term-name` had to be added to that variation's `blockTypes` for it to
 * do anything there — the variation's CSS is generated per declared block type.
 *
 * ## The label's weight comes from the section style
 *
 * There is no `fontWeight` on the title here and there should not be. The
 * section style set `bold`, the term twin overrode it to `semi-bold`, and both
 * were too heavy for a label sitting on a photograph — so
 * `styles/sections/cards/media-overlay-card.json` now carries `medium` for the
 * two cards together and neither card overrides it. Change it there, once, and
 * both tiles move.
 *
 * ## The whole tile is the link
 *
 * `sdLinkTo: "post"` — `SD\Enhancements\GroupLink` resolves `postId` from
 * context (the Query Loop's answer inside `template-archive-destination.php`
 * and `destination-regions.php`) and falls back to `get_the_ID()` when there is
 * none, which does not arise here since both callers are query-loop contexts.
 * The card's own title and featured image already point at the same permalink,
 * so the module marks them `sd-link-echo` and leaves the overlay
 * presentational — no extra tab stop, same as the term twin. Added to match
 * `card-media-overlay-term.php`, which already carries `sdLinkTo: "term"`; the
 * post twin was missing it and only the title text was clickable.
 */

?>
<!-- wp:group {"metadata":{"name":"Media Overlay Card"},"className":"is-style-media-overlay-card","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"},"sdLinkTo":"post"} -->
<div class="wp-block-group is-style-media-overlay-card">
	<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"1"} /-->

	<!-- wp:group {"metadata":{"name":"Scrim"},"className":"media-overlay-card__scrim","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group media-overlay-card__scrim" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
		<!-- wp:post-title {"level":3,"isLink":true,"className":"is-style-shadow-text","style":{"typography":{"textAlign":"center"}}} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
