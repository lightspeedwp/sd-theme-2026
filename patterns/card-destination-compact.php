<?php
/**
 * Title: Card — Destination (Compact)
 * Slug: sd-theme-2026/card-destination-compact
 * Description: The compact destination tile the destination rails carry on every Tour Operator single — a landscape featured image above a tinted panel centring the title, the continent, the parent country or the child regions, the travel styles and the excerpt.
 * Categories: sd-theme-2026/card, sd-theme-2026/tour-operator
 * Keywords: card, compact, tile, destination, region, country, related, carousel
 * Viewport Width: 480
 * Block Types: core/post-template
 * Post Types: destination
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * Brought into line with patterns/card-tour-compact.php and
 * patterns/card-accommodation-compact.php on 2026-09-01. It was the odd one of
 * the three: a bare tint with a title, an excerpt and a read-more, and no meta
 * at all. It is now the same device — a `neutral-200` panel carrying an inline
 * `shadow|200`, an even spacing|30 body, a Meta group at spacing|10 and
 * `sdLinkTo: "post"` so the whole tile is the target.
 *
 * Three structural changes came with that:
 *
 *  - **The read-more is gone**, as it is on the other two. The deep
 *    40/30/60/30 padding existed to leave room for it; the excerpt fills that
 *    space now, and it closes on `/..` the way the tour and accommodation
 *    excerpts do.
 *  - **A resting `shadow|200`.** `assets/styles/core-group.css` lifts every
 *    `.is-style-listing-card-compact` 4px and steps its shadow 200 → 300 on
 *    hover and focus-within. Without a resting value the hover would pop a
 *    shadow-300 in from nothing. Written as a block attribute, not in the
 *    section style, so it stays visible in the editor — and so the hover's
 *    `!important` has something to be important against.
 *  - **The meta the destination actually carries.** Measured over the 107
 *    published destinations on dev, 2026-09-01.
 *
 * ## Which meta, and why these four
 *
 * A destination's own fields are almost all *travel-information* prose —
 * `climate`, `visa`, `health`, `banking`, `cuisine`, `dress`, `electricity`,
 * `transport` — carried by eleven or twelve country records each and belonging
 * to the single's travel-information band, not to a card. What is left that is
 * short, present and useful on a tile is the hierarchy and the taxonomies:
 *
 * | Row | Source | Coverage on dev |
 * |---|---|---|
 * | Continent | `core/post-terms` on `continent` | 2 of 107 |
 * | Country | `lsx/post-connection` on `post_parent` | 97 of 107 — every region |
 * | Regions | `lsx/post-connection` on `post_children` | 10 of 107 — every country |
 * | Travel Styles | `core/post-terms` on `travel-style` | 13 of 107 |
 *
 * Country and Regions are deliberately both here and are never both filled: a
 * region has a parent and no children, a country has children and no parent.
 * One row shows per card, and it is the useful one either way.
 *
 * `location` is on 107 of 107 and is not a row — it is the lat/long the cluster
 * map reads. `best_time_to_visit` is on one record. `spoken_languages`, which
 * Tour Operator's own `parts/fast-facts-destination.html` carries, is on none,
 * so it is not authored here; add it if the WETU import ever populates it.
 *
 * ## The two `facts-*-wrapper` classes are load-bearing — do not drop them
 *
 * They are not styling hooks. Tour Operator's `Query_Loop::maybe_hide_varitaion()`
 * (includes/classes/blocks/class-query-loop.php:79) filters `render_block` on
 * `core/group` *and* `core/paragraph`, reads a `(lsx|facts)-(.*?)-wrapper` class
 * off the block and returns an empty string when the field behind it is empty.
 * `country-query` returns `''` when `get_post_parent()` is null;
 * `regions-query` returns `''` when `lsx_to_item_has_children()` is false
 * (class-query-loop.php:161-188). This is the same convention TO's own
 * fast-facts part uses, and the class goes on the paragraph rather than an extra
 * wrapper group because the filter accepts a paragraph directly.
 *
 * Without them both rows render on every card, and both fail visibly rather
 * than quietly:
 *
 * 1. **A dangling label.** TO's `render_paragraph_prefix_block()`
 *    (class-bindings.php:1228) prepends the `prefix` whenever the attribute is
 *    set, with no test on the bound value, so an empty `post_children` renders
 *    "**Regions:**" and nothing else. No CSS can hide it: `<p><strong>Regions:</strong> </p>`
 *    and `<p><strong>Continent:</strong> Africa</p>` are the same shape to a
 *    selector, because a text node is not an element.
 * 2. **A self-link.** `post_parent` calls `prep_links()` on
 *    `wp_get_post_parent_id()` without checking it, and `get_permalink( 0 )` /
 *    `get_the_title( 0 )` both fall through to the global post — so a country
 *    renders "Country: Botswana" linking to Botswana. Verified locally
 *    2026-09-01 against post 65904. The `country-query` wrapper removes the row
 *    before that renders, which is why the row is safe to author.
 *
 * The `prefix` / `prefixBold` attributes on the bound paragraphs are Tour
 * Operator's, not core's, and its bindings render them — which is why that copy
 * is not in a translation call here. `core/post-terms`' own `prefix` follows the
 * same form as every other card in this theme.
 */

?>
<!-- wp:group {"metadata":{"name":"Destination Card — Compact"},"className":"is-style-listing-card-compact","style":{"spacing":{"blockGap":"0"},"shadow":"var:preset|shadow|200"},"backgroundColor":"neutral-200","layout":{"type":"default"},"sdLinkTo":"post"} -->
<div class="wp-block-group is-style-listing-card-compact has-neutral-200-background-color has-background" style="box-shadow:var(--wp--preset--shadow--200)">
	<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9"} /-->

	<!-- wp:group {"metadata":{"name":"Body"},"style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}},"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200","layout":{"type":"constrained"}} -->
	<div class="wp-block-group has-200-font-size" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30);line-height:var(--wp--custom--line-height--body)">
		<!-- wp:post-title {"level":4,"isLink":true,"style":{"typography":{"textAlign":"center"}}} /-->

		<!-- wp:group {"metadata":{"name":"Meta"},"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">

			<!-- wp:post-terms {"term":"continent","prefix":"Continent: ","style":{"typography":{"textAlign":"center"}}} /-->

			<!-- wp:paragraph {"metadata":{"name":"Country","bindings":{"content":{"source":"lsx/post-connection","args":{"key":"post_parent"}}}},"className":"facts-country-query-wrapper","style":{"typography":{"textAlign":"center"}},"prefix":"Country:","prefixBold":true} -->
			<p class="has-text-align-center facts-country-query-wrapper"></p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"metadata":{"name":"Regions","bindings":{"content":{"source":"lsx/post-connection","args":{"key":"post_children"}}}},"className":"facts-regions-query-wrapper","style":{"typography":{"textAlign":"center"}},"prefix":"Regions:","prefixBold":true} -->
			<p class="has-text-align-center facts-regions-query-wrapper"></p>
			<!-- /wp:paragraph -->

			<!-- wp:post-terms {"term":"travel-style","prefix":"Travel styles: ","style":{"typography":{"textAlign":"center"}},"fontSize":"200","fontFamily":"body"} /-->

		</div>
		<!-- /wp:group -->

		<!-- wp:post-excerpt {"moreText":"/..","showMoreOnNewLine":false,"excerptLength":30,"style":{"typography":{"textAlign":"center"}}} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
