<?php
/**
 * Title: Card — Tour (List)
 * Slug: sd-theme-2026/card-tour-list
 * Description: The horizontal tour result row the travel-style and tour searches render — a square thumbnail on the leading third, then a nested split with the title, tagline, excerpt and read-more beside a tinted meta panel carrying the connected destination and the travel styles. Drop it into a Query Loop's post template.
 * Categories: sd-theme-2026/card, sd-theme-2026/tour-operator
 * Keywords: card, list, row, tour, search, archive, facetwp, travel style
 * Viewport Width: 1280
 * Block Types: core/post-template
 * Post Types: tour
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * The tour-only twin of `patterns/card-search-result.php`, and deliberately the
 * same row — rebuilt against it 2026-09-17 (LS-4204) so a reader moving from
 * the search results page to a travel-style page sees one card, not two. The
 * measurements are that file's and `patterns/card-accommodation-list.php`'s,
 * and their reasoning is recorded there rather than repeated: the
 * `is-style-listing-card-list` style, the 30% square thumbnail, the nested
 * split whose trailing 35% is the tinted meta panel, the `neutral-200` card
 * ground with the panel a step lighter on `neutral-100`.
 *
 * Four things are worth saying here.
 *
 * ## 1. What changed, and what it means for anything using this card
 *
 * The previous shape was a 25 / grow / 25 split on a `base` ground with a
 * `neutral-200` meta strip, a 4:3 thumbnail and `spacing|40` padding. It is now
 * the search row: `neutral-200` card, 30% 1:1 thumbnail, `neutral-100` panel at
 * 35%, `spacing|30` padding on the copy column and `spacing|20` in the panel.
 *
 * ⚠️ **The `margin-bottom` is gone.** The card used to space itself; the gap
 * between rows is now the enclosing `core/post-template`'s `blockGap`, as it is
 * on every other list card in this theme. A loop that drops this card in
 * without setting `blockGap` gets rows with no gap at all — `spacing|30` is
 * what the two results templates use.
 *
 * ## 2. No post-type badge
 *
 * `card-search-result.php` carries one because a mixed result set is the one
 * place a reader cannot tell what kind of thing a row is. Every loop this card
 * runs in is tours only, so the badge would say "Tour" on every row of a page
 * whose heading already says Tours. `.listing-card-list__media` is still on the
 * media column — it is the positioning context the badge needs, it costs
 * nothing, and it keeps the two cards' markup in step if a badge is ever
 * wanted here.
 *
 * ## 3. The tagline is the tour's, and it is a binding
 *
 * `core/post-meta` on `tagline` — Tour Operator's own field, which live renders
 * as `p.lsx-to-archive-content-tagline` ("16 Nights", "9 Days") under the
 * title. Authored **empty**, so a tour with no tagline prints nothing rather
 * than an empty plate, and `.lsx-tagline-wrapper` is the class Tour Operator's
 * own styles key off.
 *
 * ## 4. Every paragraph in the panel sets its own `fontSize`
 *
 * theme.json styles `core/paragraph`, whose block selector is `p`, so global
 * styles emit `:root :where(p){font-size:var(--wp--preset--font-size--300)}`.
 * At (0,1,0) that beats *inheritance* from the column's `.has-200-font-size`,
 * so a bare `<p>` in the panel renders at 300 unless it carries the class
 * itself. `core/post-terms` needs no such care — it renders a `div`. The
 * excerpt cannot be answered that way at all and is handled once, for the whole
 * theme, in `assets/styles/core-post-excerpt.css`.
 * → card-search-result.php for the long form of this.
 */

?>
<!-- wp:columns {"metadata":{"name":"Tour Card — List"},"className":"is-style-listing-card-list","style":{"spacing":{"blockGap":"0"}},"backgroundColor":"neutral-200"} -->
<div class="wp-block-columns is-style-listing-card-list has-neutral-200-background-color has-background">

	<!-- wp:column {"width":"30%","className":"listing-card-list__media"} -->
	<div class="wp-block-column listing-card-list__media" style="flex-basis:30%">
		<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"1"} /-->
	</div>
	<!-- /wp:column -->

	<!-- wp:column {"verticalAlignment":"stretch","style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
	<div class="wp-block-column is-vertically-aligned-stretch" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">

		<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|40"}}}} -->
		<div class="wp-block-columns">

			<?php
			/*
			 * The copy. No `width` — the column grows into whatever the meta
			 * panel leaves, which is what keeps this card and the search row
			 * identical without a second variant to hold in step.
			 */
			?>
			<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
			<div class="wp-block-column">

				<!-- wp:post-title {"level":3,"isLink":true} /-->

				<!-- wp:paragraph {"metadata":{"name":"Tagline","bindings":{"content":{"source":"core/post-meta","args":{"key":"tagline"}}}},"className":"lsx-tagline-wrapper","textColor":"brand-500","fontSize":"200","fontFamily":"heading","style":{"typography":{"textTransform":"uppercase","letterSpacing":"var:custom|letter-spacing|heading"}}} -->
				<p class="lsx-tagline-wrapper has-brand-500-color has-text-color has-200-font-size has-heading-font-family" style="letter-spacing:var(--wp--custom--letter-spacing--heading);text-transform:uppercase"></p>
				<!-- /wp:paragraph -->

				<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group">
					<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":45,"fontSize":"200"} /-->

					<!-- wp:read-more {"content":"View more","fontSize":"200"} /-->
				</div>
				<!-- /wp:group -->

			</div>
			<!-- /wp:column -->

			<?php
			/*
			 * The meta panel — the search row's panel, carrying the two rows a
			 * tour has: the connected destination, then the travel styles, in
			 * live's order. The bound paragraph is authored **empty** so a null
			 * binding prints nothing rather than a stray prefix; `prefix` /
			 * `prefixBold` are `sd-enhancements`' addition to `core/paragraph`,
			 * not core's.
			 */
			?>
			<!-- wp:column {"verticalAlignment":"stretch","width":"35%","style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20"}},"typography":{"lineHeight":"var:custom|line-height|body"}},"backgroundColor":"neutral-100","fontSize":"200"} -->
			<div class="wp-block-column is-vertically-aligned-stretch has-neutral-100-background-color has-background has-200-font-size" style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20);line-height:var(--wp--custom--line-height--body);flex-basis:35%">

				<!-- wp:paragraph {"metadata":{"name":"Location","bindings":{"content":{"source":"lsx/post-connection","args":{"key":"destination_to_tour"}}}},"className":"lsx-destination-to-tour-wrapper","prefix":"Location:","prefixBold":true,"fontSize":"200"} -->
				<p class="lsx-destination-to-tour-wrapper has-200-font-size"></p>
				<!-- /wp:paragraph -->

				<!-- wp:post-terms {"term":"travel-style","prefix":"Travel Style: ","style":{"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200"} /-->

			</div>
			<!-- /wp:column -->

		</div>
		<!-- /wp:columns -->

	</div>
	<!-- /wp:column -->

</div>
<!-- /wp:columns -->
