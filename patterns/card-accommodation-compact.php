<?php
/**
 * Title: Card — Accommodation (Compact)
 * Slug: sd-theme-2026/card-accommodation-compact
 * Description: The compact accommodation tile the related-accommodation carousels carry on every Tour Operator single — a landscape featured image above a tinted panel centring the title, the price band, the travel styles, the brand and type, the connected destination and the star rating.
 * Categories: sd-theme-2026/card, sd-theme-2026/tour-operator
 * Keywords: card, compact, tile, accommodation, lodge, related, carousel, rating
 * Viewport Width: 480
 * Block Types: core/post-template
 * Post Types: accommodation
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * Captured back out of the dev single-destination DB override on 2026-09-01,
 * where the card was restructured in the Site Editor. What changed from the
 * first pass:
 *
 *  - **It is a panel now, not a bare tint.** An explicit `neutral-200` plus an
 *    inline `shadow|200`, written as block attributes so the editor can see and
 *    change them, over the `neutral-200` the section style already paints. The
 *    ground is the same either way; the shadow is what lifts the tile off the
 *    section, and it is what the hover flip in assets/styles/core-group.css
 *    steps up. That makes it the same device as patterns/card-tour-compact.php
 *    and patterns/card-post-grid.php.
 *  - **An even spacing|30 panel** in place of the 40/30/60/30 that left room for
 *    a read-more the carousel hides. The excerpt fills that space now.
 *  - **A Meta group** at `blockGap: spacing|10`, holding the price band, the
 *    travel styles, the brand, the type, the connected destination and the star
 *    rating as one tighter block than the panel's own spacing|20 — so the title,
 *    the meta and the excerpt read as three things rather than eight.
 *  - **`sdLinkTo: "post"`** — the whole tile is the target, via
 *    sd-enhancements' group-link module, exactly as the tour card does it. The
 *    blocks that already carry their own anchor keep them.
 *
 * ## Three places the dev markup is not copied verbatim
 *
 * 1. **`var:preset|spacing|0` is not a token.** The editor wrote it for the
 *    rating row's zeroed paddings, but `settings.spacing.spacingSizes` runs
 *    5 → 100 with no `0` member, so `var(--wp--preset--spacing--0)` resolves to
 *    nothing and the declaration is dropped at computed-value time. A plain `0`
 *    is authored instead — same result, no orphaned reference. The same
 *    correction is recorded on patterns/card-tour-compact.php.
 *    → `theme-orphaned-refs`
 * 2. **The Type row's `primary-500` link colour is dropped.** The editor set one
 *    on `accommodation-type` alone, which left the four link rows on this card
 *    at three different colours. Every link in the card now takes the section
 *    style's `elements.link` — `neutral-800`, `brand-600` on hover, no
 *    underline — which is the whole point of having it there.
 * 3. **The rating stars are not restyled here, because they are not ours.**
 *    `lsx/post-meta` on `rating` returns Tour Operator's own markup:
 *    `lsx_to_accommodation_rating()` (includes/classes/legacy/class-accommodation.php:179)
 *    emits five `<figure class="wp-block-image">` wrappers around 20px PNGs,
 *    `rating-star-full.png` and `rating-star-empty.png`. The brand-500 Phosphor
 *    stars that replace them are CSS masks in assets/styles/core-group.css —
 *    presentation over vendor markup, so no pattern attribute can carry it.
 *
 * The `prefix` / `prefixBold` attributes on the bound paragraphs are Tour
 * Operator's, not core's, and its bindings render them — which is why that copy
 * is not in a translation call here. `core/post-terms`' own `prefix` follows the
 * same form as every other card in this theme.
 */

?>
<!-- wp:group {"metadata":{"name":"Accommodation Card — Compact"},"className":"is-style-listing-card-compact","style":{"spacing":{"blockGap":"0"},"shadow":"var:preset|shadow|200"},"backgroundColor":"neutral-200","layout":{"type":"default"},"sdLinkTo":"post"} -->
<div class="wp-block-group is-style-listing-card-compact has-neutral-200-background-color has-background" style="box-shadow:var(--wp--preset--shadow--200)">
	<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9"} /-->

	<!-- wp:group {"metadata":{"name":"Body"},"style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}},"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200","layout":{"type":"constrained"}} -->
	<div class="wp-block-group has-200-font-size" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30);line-height:var(--wp--custom--line-height--body)">
		<!-- wp:post-title {"level":4,"isLink":true,"style":{"typography":{"textAlign":"center"}}} /-->

		<!-- wp:group {"metadata":{"name":"Meta"},"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">

			<!-- wp:paragraph {"metadata":{"name":"Price Rating","bindings":{"content":{"source":"sd/post-meta","args":{"key":"price_rating","format":"price-band"}}}},"className":"lsx-price-rating-wrapper","style":{"typography":{"textAlign":"center"}},"prefix":"Price Rating:","prefixBold":true} -->
			<p class="has-text-align-center lsx-price-rating-wrapper"></p>
			<!-- /wp:paragraph -->

			<!-- wp:post-terms {"term":"travel-style","prefix":"Travel styles: ","style":{"typography":{"textAlign":"center"}},"fontSize":"200","fontFamily":"body"} /-->

			<!-- wp:post-terms {"term":"accommodation-brand","prefix":"Brand: ","style":{"typography":{"textAlign":"center"}}} /-->

			<!-- wp:post-terms {"term":"accommodation-type","prefix":"Type: ","style":{"typography":{"textAlign":"center"}}} /-->

			<!-- wp:paragraph {"metadata":{"name":"Location","bindings":{"content":{"source":"lsx/post-connection","args":{"key":"destination_to_accommodation"}}}},"className":"lsx-destination-to-accommodation-wrapper","style":{"typography":{"textAlign":"center"}},"prefix":"Destination: ","prefixBold":true} -->
			<p class="has-text-align-center lsx-destination-to-accommodation-wrapper"></p>
			<!-- /wp:paragraph -->

			<!-- wp:group {"metadata":{"name":"Rating"},"className":"lsx-rating-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|5","padding":{"top":"0","bottom":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
			<div class="wp-block-group lsx-rating-wrapper" style="padding-top:0;padding-bottom:0">
				<!-- wp:paragraph {"metadata":{"name":"Rating Value","bindings":{"content":{"source":"lsx/post-meta","args":{"key":"rating"}}}},"prefix":"Rating:","prefixBold":true} -->
				<p></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"metadata":{"name":"Rating Authority","bindings":{"content":{"source":"lsx/post-meta","args":{"key":"rating_type"}}}}} -->
				<p></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->

		</div>
		<!-- /wp:group -->

		<!-- wp:post-excerpt {"moreText":"/..","showMoreOnNewLine":false,"excerptLength":30,"style":{"typography":{"textAlign":"center"}}} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
