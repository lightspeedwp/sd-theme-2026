<?php
/**
 * Title: Card — Accommodation (List)
 * Slug: sd-theme-2026/card-accommodation-list
 * Description: The horizontal accommodation result row the FacetWP accommodation search renders. A square thumbnail on the leading third, then a nested 65/35 split carrying the title and excerpt beside a tinted meta panel that leads with the price band, then the accommodation type and the connected destination. Drop it into a Query Loop's post template.
 * Categories: sd-theme-2026/card, sd-theme-2026/tour-operator
 * Keywords: card, list, row, accommodation, lodge, search, archive, facetwp
 * Viewport Width: 1280
 * Block Types: core/post-template
 * Post Types: accommodation
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * Reworked in the Site Editor on dev 2026-09-10 against
 * /accommodation-type/africas-finest/ (wp_template 65946) and imported here.
 * Five deltas from the row this file carried before, all deliberate:
 *
 *   - the card ground is `neutral-200` and the meta panel `neutral-100`, so the
 *     strip reads as a plate *on* the card rather than the only tinted thing in
 *     the row. Live measures #f6f3f0 with the strip on #f0ebe5; that is this
 *     pair, the right way round.
 *   - the thumbnail is **30%** and square (`aspectRatio: 1`), not 25% at 4/3 —
 *     live runs 29% of the row, and a square plate stops a portrait lodge
 *     photograph being cropped to a letterbox.
 *   - the meta panel moved *inside* the content column as a nested 65/35 split
 *     rather than sitting as a third top-level column. That is what lets it
 *     stretch to the copy's height and inset itself from the card edge; a
 *     top-level column could only ever run the full height of the row,
 *     thumbnail included.
 *   - the excerpt is 45 words (live's is 26) and lives in its own group so the
 *     title/excerpt gap is set independently of the column's `blockGap`.
 *   - `core/read-more` is gone. The whole title is a link and the card carries
 *     a hover state, so a third affordance to the same URL was noise.
 *
 * ⚠️ No `margin-bottom` on the card. The gap between rows is the enclosing
 * `core/post-template`'s `blockGap` — set to `spacing|30` in both templates
 * that require this file. A margin here would double it.
 *
 * ⚠️ Shared with `patterns/template-taxonomy-accommodation-brand.php`. Any
 * change lands on the brand term archive too.
 */

?>
<!-- wp:columns {"metadata":{"name":"Accommodation Card — List"},"className":"is-style-listing-card-list","style":{"spacing":{"blockGap":"0"}},"backgroundColor":"neutral-200"} -->
<div class="wp-block-columns is-style-listing-card-list has-neutral-200-background-color has-background">

	<!-- wp:column {"width":"30%"} -->
	<div class="wp-block-column" style="flex-basis:30%">
		<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"1"} /-->
	</div>
	<!-- /wp:column -->

	<!-- wp:column {"verticalAlignment":"stretch","style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
	<div class="wp-block-column is-vertically-aligned-stretch" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">

		<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|40"}}}} -->
		<div class="wp-block-columns">

			<!-- wp:column {"width":"65%","style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
			<div class="wp-block-column" style="flex-basis:65%">

				<!-- wp:post-title {"level":3,"isLink":true} /-->

				<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group">
					<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":45,"fontSize":"200"} /-->
				</div>
				<!-- /wp:group -->

			</div>
			<!-- /wp:column -->

			<?php
			/*
			 * The meta panel. Price band, then type, then the connected
			 * destination — live's order.
			 *
			 * Both bound paragraphs are authored **empty** so a null binding
			 * prints nothing rather than a stray prefix, and the `prefix` /
			 * `prefixBold` attributes are `sd-enhancements`' addition to
			 * `core/paragraph`, not core's.
			 *
			 * The rows sit at `spacing|10` (XS), one step in from the `20` (S)
			 * they carried and from the panel's own inset padding, which stays
			 * at `20`. Three short prefixed read-outs at S read as three
			 * separate statements rather than one block of facts, and the gap
			 * was wider than the leading inside each row. Zared's call,
			 * 2026-09-16. The gap and the padding are deliberately no longer
			 * the same token — they are doing different jobs.
			 */
			?>
			<!-- wp:column {"verticalAlignment":"stretch","width":"35%","style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20"}},"typography":{"lineHeight":"var:custom|line-height|body"}},"backgroundColor":"neutral-100","fontSize":"200"} -->
			<div class="wp-block-column is-vertically-aligned-stretch has-neutral-100-background-color has-background has-200-font-size" style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20);line-height:var(--wp--custom--line-height--body);flex-basis:35%">

				<!-- wp:paragraph {"metadata":{"name":"Price Rating","bindings":{"content":{"source":"sd/post-meta","args":{"key":"price_rating","format":"price-band"}}}},"className":"lsx-price-rating-wrapper","prefix":"Price Rating:","prefixBold":true} -->
				<p class="lsx-price-rating-wrapper"></p>
				<!-- /wp:paragraph -->

				<!-- wp:post-terms {"term":"accommodation-type","prefix":"Type: ","style":{"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200"} /-->

				<!-- wp:paragraph {"metadata":{"name":"Location","bindings":{"content":{"source":"lsx/post-connection","args":{"key":"destination_to_accommodation"}}}},"className":"lsx-destination-to-accommodation-wrapper","prefix":"Location:","prefixBold":true} -->
				<p class="lsx-destination-to-accommodation-wrapper"></p>
				<!-- /wp:paragraph -->

			</div>
			<!-- /wp:column -->

		</div>
		<!-- /wp:columns -->

	</div>
	<!-- /wp:column -->

</div>
<!-- /wp:columns -->
