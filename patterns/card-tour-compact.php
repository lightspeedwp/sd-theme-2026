<?php
/**
 * Title: Card — Tour (Compact)
 * Slug: sd-theme-2026/card-tour-compact
 * Description: The compact tour tile the related-tours carousel carries on every Tour Operator single — a 16/9 featured image above a tinted panel centring the title, the duration, the travel styles, the connected destinations and the excerpt.
 * Categories: sd-theme-2026/card, sd-theme-2026/tour-operator
 * Keywords: card, compact, tile, tour, related, carousel, duration
 * Viewport Width: 480
 * Block Types: core/post-template
 * Post Types: tour
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * Captured back out of the dev single-tour DB override on 2026-08-31, where the
 * card was restructured in the Site Editor. What changed from the first pass:
 *
 *  - **It is a panel now, not a bare tint.** `neutral-100` plus `shadow|200`,
 *    written as block attributes so the editor can see them, over the
 *    `neutral-200` the section style paints. That makes it the same device as
 *    patterns/card-post-grid.php, and it is why the hover lift below can be the
 *    same one — see assets/styles/core-group.css.
 *  - **16/9, not 3/2.** Same crop as the post grid card, so the two shelves
 *    read as one system. Image crops are `aspectRatio`, never CSS. → AGENTS.md
 *  - **An even 30 panel** in place of the 40/30/60/30 that left room for a
 *    read-more the carousel hides. The excerpt fills that space now.
 *  - **A duration row and an excerpt** joined the title, travel styles and
 *    destinations.
 *  - **`sdLinkTo: "post"`** — the whole tile is the target, via
 *    sd-enhancements' group-link module, exactly as the post grid card does it.
 *    The blocks that already carry their own anchor keep them.
 *
 * ## Two places the dev markup is not copied verbatim
 *
 * 1. **`var:preset|spacing|0` is not a token.** The editor wrote it for the
 *    zeroed paddings, but `settings.spacing.spacingSizes` runs 5 → 100 with no
 *    `0` member, so `var(--wp--preset--spacing--0)` resolves to nothing and the
 *    declaration is dropped at computed-value time. A plain `0` is authored
 *    instead — same result, no orphaned reference. → `theme-orphaned-refs`
 * 2. **The travel-style weight is the parenthesised token.** The editor wrote a
 *    raw `500`. `core/post-terms` is dynamic — it has no saved markup, so the
 *    server-side style engine builds its inline style, and that engine does not
 *    expand the `var:custom|…` shorthand for `fontWeight`; the declaration
 *    comes out absent. `var(--wp--custom--font-weight--medium)` resolves.
 *    Measured in patterns/safari-expert.php, which carries the full table.
 *
 * The `prefix` / `prefixBold` attributes on the bound blocks are Tour
 * Operator's, not core's, and its bindings render them — which is why that copy
 * is not in a translation call here. The one literal string the theme owns is
 * "days", and it is.
 *
 * ## Every meta row carries its own font size, and has to
 *
 * The Body group is `fontSize: 200` and the rows inside it were left to
 * inherit. They did not. `theme.json` sets
 * `styles.blocks.core/paragraph.fontSize` to `300`, and a block-level global
 * style is not inheritance — it lands on every `core/paragraph` and beats the
 * ancestor's `has-200-font-size` outright. So the three paragraph rows rendered
 * at 300 while `core/post-terms` — a `<div>`, with no block style of its own —
 * inherited 200 and the excerpt carried an explicit 200: three sizes in one
 * meta block. Measured on dev against /team/liesl-mathews/ 2026-09-16.
 *
 * The fix is an explicit `200` on each row rather than a `css`-field override
 * on the card style, because the size is a property of the row and the editor
 * should show it. **Do not remove these thinking they are redundant** — the
 * moment one comes off, that row goes back to 300.
 *
 * `patterns/card-tour-list.php` has the same latent split on its Location row
 * and is not fixed here; it is a different card on a different template.
 *
 * ## The 2px padding is optical alignment, not spacing
 *
 * "days" is a plain paragraph sitting beside a bound one in a nowrap flex row,
 * and the two sat a hair out of line. A 2px `padding-block` on every field in
 * the group — not just the one that needed it — settles them, and is carried on
 * the taxonomy and destination rows too so the whole meta block sits on one
 * rhythm. Zared's measurement, 2026-09-16.
 *
 * ## The duration row hides itself
 *
 * `lsx-duration-wrapper` is Tour Operator's hook, not a styling class:
 * `Query_Loop::maybe_hide_varitaion()`
 * (tour-operator/includes/classes/blocks/class-query-loop.php:96) filters
 * `render_block`, matches `(lsx|facts)-<key>-wrapper` on a `core/group` or a
 * `core/paragraph`, and returns an empty string when that key's post meta is
 * empty. `duration` is neither a query nor a taxonomy, so it falls through to
 * `get_post_meta()`.
 *
 * It is on the **group**, deliberately. TO prepends the prefix with no test on
 * the value, so a tour with no duration rendered
 * `<p><strong>Duration:</strong> </p>` next to a live "days" — the row read
 * "Duration: days". The paragraph is not `:empty` either, so the card style's
 * `p:empty` rule cannot reach it. Hiding the group takes the value and the
 * "days" together, which is the only version of this that is correct. Luxury
 * Honeymoon Adventure (dev, 57936) is the case it was measured against: its
 * `duration` meta is `""`.
 */

?>
<!-- wp:group {"metadata":{"name":"Tour Card — Compact"},"className":"is-style-listing-card-compact","style":{"spacing":{"blockGap":"0","padding":{"top":"0","bottom":"0"}},"shadow":"var:preset|shadow|200"},"backgroundColor":"neutral-100","layout":{"type":"default"},"sdLinkTo":"post"} -->
<div class="wp-block-group is-style-listing-card-compact has-neutral-100-background-color has-background" style="padding-top:0;padding-bottom:0;box-shadow:var(--wp--preset--shadow--200)">
	<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9"} /-->

	<!-- wp:group {"metadata":{"name":"Body"},"style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}},"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200","layout":{"type":"constrained"}} -->
	<div class="wp-block-group has-200-font-size" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30);line-height:var(--wp--custom--line-height--body)">
		<!-- wp:post-title {"level":4,"isLink":true,"style":{"typography":{"textAlign":"center"}}} /-->

		<!-- wp:group {"metadata":{"name":"Meta"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">

			<!-- wp:group {"metadata":{"name":"Duration"},"className":"lsx-duration-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|5","padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
			<div class="wp-block-group lsx-duration-wrapper" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
				<!-- wp:paragraph {"metadata":{"name":"Duration Value","bindings":{"content":{"source":"lsx/post-meta","args":{"key":"duration"}}}},"style":{"spacing":{"padding":{"top":"2px","bottom":"2px"}}},"fontSize":"200","prefix":"Duration:","prefixBold":true} -->
				<p class="has-200-font-size" style="padding-top:2px;padding-bottom:2px"></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"style":{"spacing":{"padding":{"top":"2px","bottom":"2px"}}},"fontSize":"200"} -->
				<p class="has-200-font-size" style="padding-top:2px;padding-bottom:2px"><?php esc_html_e( 'days', 'sd-theme-2026' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->

			<!-- wp:post-terms {"term":"travel-style","prefix":"Travel Styles: ","style":{"typography":{"textAlign":"center","fontStyle":"normal"},"spacing":{"padding":{"top":"2px","bottom":"2px"}}},"fontSize":"200"} /-->

			<!-- wp:paragraph {"metadata":{"name":"Destinations","bindings":{"content":{"source":"lsx/post-connection","args":{"key":"destination_to_tour","parents":true}}}},"className":"lsx-destination-to-tour-wrapper","style":{"typography":{"textAlign":"center"},"spacing":{"padding":{"top":"2px","bottom":"2px"}}},"fontSize":"200","prefix":"Destinations:","prefixBold":true} -->
			<p class="has-text-align-center lsx-destination-to-tour-wrapper has-200-font-size" style="padding-top:2px;padding-bottom:2px"></p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:group -->

		<!-- wp:post-excerpt {"moreText":"/..","showMoreOnNewLine":false,"excerptLength":30,"style":{"typography":{"textAlign":"center"}},"fontSize":"200"} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
