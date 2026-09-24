<?php
/**
 * Title: Template: Search Results
 * Slug: sd-theme-2026/template-page-search
 * Description: The site search results page — the photographic banner, the breadcrumb strip, then the mixed-post-type result rows behind a Content Type filter rail, a result count, a sort control and a FacetWP pager. Used by the search template.
 * Categories: hidden
 * Keywords: template, search, results, query, facetwp, facets, filters, sort, content type
 * Block Types: core/query
 * Template Types: search
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 *
 * @package sd-theme-2026
 */

/*
 * The search results page, built as the mixed-post-type sibling of
 * `patterns/template-taxonomy-accommodation-type.php`. Everything structural —
 * the 25/75 rail-and-results split, the `sd-search-*` hooks the stylesheet and
 * the fold script key off, `enableFacetWP` on the loop, the count as a Pager
 * facet in *Result counts* mode — is that file's, and its reasoning is recorded
 * there rather than repeated here. Five things are this page's own.
 *
 * ## 1. The banner
 *
 * Live's search page runs the LSX rotating banner over
 * `sd-lsx-child/images/banner-search-tc-faq-1920x454.jpg`, and the same
 * photograph is already in the media library (attachment 51741, uploaded
 * 2019-08-30) — so it is addressed by its uploads URL like every other banner
 * in this theme rather than ported into `assets/images/` as theme chrome. It is
 * the same cover device, `dimRatio`, overlay colour and 360px floor the three
 * Tour Operator archives carry; → `template-archive-destination.php` for why
 * each of those is what it is.
 *
 * The heading is "Search" and the standfirst is Zared's copy, 2026-09-17. The
 * searched-for phrase is deliberately *not* in the `h1`: `core/query-title`
 * with `type: search` renders "Search results for …", which reads as a system
 * message over a photograph, and the phrase is already in the search field in
 * the rail — which is also where a reader goes to change it.
 *
 * ## 2. One filter, and it is the only one
 *
 * The rail carries the **Content Type** facet and nothing else (Zared,
 * 2026-09-17). A result set spanning accommodation, tours, destinations,
 * specials, reviews, team and articles has no field in common to refine on, so
 * the one filter that means anything across all of them is what kind of thing
 * the result is. `post_type` is an existing FacetWP facet on dev — labelled
 * "Content Type", source `post_type`, already indexed.
 *
 * ⚠️ Its **behaviour must be OR (match any)**. A `post_type` facet set to AND
 * returns nothing the moment two types are ticked, because no post is two post
 * types at once. Set on dev 2026-09-17.
 *
 * ⚠️ `envira` (Galleries) is in that facet's index and should not be offered as
 * a content type. Excluding it is `exclude_from_search` in the plugin layer,
 * not theme work.
 *
 * ## 3. The keyword box is `core/search`, not a FacetWP search facet
 *
 * The accommodation results page puts a FacetWP `search` facet at the head of
 * the rail because there the keyword is a *filter over a term archive*. Here
 * the keyword **is** the query: the page exists because of `s=`. A FacetWP
 * search facet would AND a second keyword over the first and leave the two
 * disagreeing about what the page is showing, so the field is a plain
 * `core/search`, which re-runs the search and carries the current phrase in its
 * value. It sits above "Refine by" for the reason recorded on the accommodation
 * template: searching is not refining.
 *
 * It is **dressed as that page's field**, though — Zared, 2026-09-17. The two
 * rails are the same rail and a reader moving between them should not see two
 * different keyword boxes, so `buttonUseIcon` swaps core's "Search" label for
 * the magnifier and `assets/styles/facetwp-facets.css` gives the block the
 * facet field's box and the same brand tile on its trailing edge. Only the
 * mechanism differs, and the mechanism is not visible.
 *
 * ⚠️ That is also why the button here is a **real `<button>`** where the facet's
 * is an `<i>` with a click handler. The keyboard path on this page runs through
 * the button as well as Enter; the facet's does not, which is the caveat
 * recorded against it in the stylesheet.
 *
 * ## 4. The sort control
 *
 * Added 2026-09-17 at Zared's request; this file previously argued for the
 * count alone. It is the accommodation page's facet — **`sort_`**, with the
 * trailing underscore, because `sort` is reserved and FacetWP will not save a
 * facet under it. A block pointing at `sort` renders nothing at all.
 *
 * ⚠️ Two of that facet's four options are accommodation-shaped. Measured on dev
 * 2026-09-17 it offers Title (A-Z), Title (Z-A), Price (Highest) and Price
 * (Lowest), and the two price orderings run on `cf/price_rating`, which only
 * accommodation carries — so choosing one on a mixed result set orders the rows
 * that have the field and leaves the rest at the tail. The two title orderings
 * are meaningful across every post type. The option list is a **FacetWP
 * setting**, not theme markup: trimming it, or registering a second sort facet
 * for this page, is done in the admin, and nothing here changes if it is.
 *
 * ⚠️ Picking any sort replaces relevance ordering, which is the only ranking a
 * mixed result set has. The facet's own `default_label` ("Sort by") is the
 * resting state and leaves the main query's relevance order alone.
 *
 * ## 5. Two things the results region does differently
 *
 * The "Results" heading is authored `textTransform: none`. The theme's `h2`
 * element is uppercase in `theme.json`, which is right for a section heading and
 * wrong for a label sitting beside a number. The count beside it is sized and
 * coloured to match the heading in `assets/styles/facetwp-facets.css`, and the
 * brackets around the number are the `results_count` facet's own count text
 * (`([total])`), not CSS — they are content. Both are Zared's calls, 2026-09-17,
 * and both transfer to the accommodation results page, which shares the facet
 * and the stylesheet.
 *
 * The toolbar carries no rule beneath it. The `border-bottom` that used to
 * close it off was removed from `.sd-search-toolbar` on 2026-09-17 (Zared): the
 * first card's own ground already draws the line, and the rule under a row that
 * is itself a label and a control read as a third divider. It is a shared
 * class, so the accommodation results page loses the rule too.
 *
 * The pager facet is **`pager_`**, with the trailing underscore, which is the
 * facet dev actually carries. ⚠️ `template-taxonomy-accommodation-type.php`
 * asks for `pager`, which is not a facet on dev and renders nothing.
 */

?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Search Results"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<!-- wp:cover {"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/08/banner-search-tc-faq-1920x454.jpg","alt":"","dimRatio":100,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":360,"minHeightUnit":"px","contentPosition":"bottom center","align":"full","className":"is-style-hero-banner","tagName":"section","metadata":{"name":"Banner"},"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center is-style-hero-banner" style="min-height:360px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-100 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/08/banner-search-tc-faq-1920x454.jpg" data-object-fit="cover"/><div class="wp-block-cover__inner-container">

		<!-- wp:group {"metadata":{"name":"Banner Content"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:heading {"level":1,"className":"is-style-script-accent","fontSize":"800","anchor":"h-search"} -->
			<h1 class="wp-block-heading is-style-script-accent has-800-font-size" id="h-search"><?php esc_html_e( 'Search', 'sd-theme-2026' ); ?></h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"className":"is-style-subheading-large","style":{"typography":{"fontWeight":"var:custom|font-weight|medium"}},"fontFamily":"heading"} -->
			<p class="is-style-subheading-large has-heading-font-family" style="font-weight:var(--wp--custom--font-weight--medium)"><?php esc_html_e( 'Let us help you find what you’re searching for', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:group -->

	</div></section>
	<!-- /wp:cover -->

	<?php
	/*
	 * The breadcrumb bar, directly under the banner — the same
	 * `patterns/breadcrumbs.php` every other inner page runs, in the same
	 * position. `require` rather than a nested `wp:pattern` reference, because a
	 * pattern referencing another pattern resolves under WP-CLI and is silently
	 * dropped on front-end render.
	 * → .claude/skills/wp-pattern-runtime-pitfalls
	 */
	require __DIR__ . '/breadcrumbs.php';
	?>

	<!-- wp:group {"tagName":"section","metadata":{"name":"Results"},"align":"full","className":"is-style-light-page-section","layout":{"type":"constrained"}} -->
	<section class="wp-block-group alignfull is-style-light-page-section">

		<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
		<div class="wp-block-columns alignwide">

			<?php
			/*
			 * The filter rail. `sd-search-filters` is the hook both the
			 * stylesheet and the fold script key off — **renaming it silently
			 * disables the folds.** The `aside` is a `core/group` inside the
			 * column rather than the column itself, because `core/column` has no
			 * `tagName` attribute and writing one onto it is a block-validation
			 * error the moment the template is opened in the editor.
			 * → template-taxonomy-accommodation-type.php
			 */
			?>
			<!-- wp:column {"width":"25%"} -->
			<div class="wp-block-column" style="flex-basis:25%">

			<!-- wp:group {"tagName":"aside","metadata":{"name":"Filter Rail"},"className":"sd-search-filters","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"default"}} -->
			<aside class="wp-block-group sd-search-filters">

				<?php
				/*
				 * The phone trigger for the filter flyout — the note is on
				 * patterns/template-taxonomy-accommodation-type.php.
				 */
				?>
				<!-- wp:buttons {"metadata":{"name":"Filters Trigger"},"className":"sd-filters-toggle facetwp-flyout-open","layout":{"type":"flex","justifyContent":"stretch"}} -->
				<div class="wp-block-buttons sd-filters-toggle facetwp-flyout-open">
					<!-- wp:button {"tagName":"button","width":100} -->
					<div class="wp-block-button has-custom-width wp-block-button__width-100"><button type="button" class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Filters', 'sd-theme-2026' ); ?></button></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->

				<?php
				/*
				 * The keyword box. `core/search`, not a FacetWP search facet — see
				 * §3 at the head of this file for why, and why it is nonetheless
				 * drawn as the accommodation rail's facet field.
				 *
				 * `buttonUseIcon` is what makes it look like that field: core then
				 * renders the button as `<button class="… has-icon" aria-label="…">`
				 * around `svg.search-icon` rather than the word "Search", which is
				 * the same glyph-on-a-brand-tile the facet's `<i>` is styled into.
				 * `buttonText` stays set because that string is what core puts in the
				 * `aria-label` once the icon replaces the visible label.
				 *
				 * No heading, so the fold script leaves it alone — its section test
				 * requires one, and this control must never be foldable away.
				 */
				?>
				<!-- wp:search {"label":"<?php echo esc_attr_x( 'Search', 'search form label', 'sd-theme-2026' ); ?>","showLabel":false,"placeholder":"<?php echo esc_attr_x( 'Search the site…', 'search form placeholder', 'sd-theme-2026' ); ?>","buttonText":"<?php echo esc_attr_x( 'Search', 'search form button', 'sd-theme-2026' ); ?>","buttonPosition":"button-inside","buttonUseIcon":true} /-->

				<!-- wp:heading {"level":2,"fontSize":"400","anchor":"h-refine-by"} -->
				<h2 class="wp-block-heading has-400-font-size" id="h-refine-by"><?php esc_html_e( 'Refine by', 'sd-theme-2026' ); ?></h2>
				<!-- /wp:heading -->

				<?php
				/*
				 * The one facet. `hasHeader: true` makes the block print a
				 * label; the inner `core/heading` makes that label ours —
				 * given no inner content the block echoes the admin-set
				 * `facetLabel` inside a hard-coded `h4`, which is neither
				 * translatable through this theme's text domain nor at the
				 * right level under the `h2` above.
				 *
				 * `hideOnEmpty: true` asks FacetWP whether the facet still has
				 * choices for the current result set and adds `.facetwp-hidden`
				 * when it does not — so a search returning one kind of thing
				 * does not offer a filter that cannot narrow it. ⚠️ Nothing in
				 * the stylesheet may set `display` on `.facet-wrap`; that trap
				 * is documented at the head of assets/styles/facetwp-facets.css.
				 */
				?>
				<!-- wp:facetwp/facet {"facetName":"post_type","facetLabel":"Content Type","facetType":"checkboxes","hasHeader":true,"hideOnEmpty":true} -->
					<!-- wp:heading {"level":3,"fontSize":"400","anchor":"h-content-type"} -->
					<h3 class="wp-block-heading has-400-font-size" id="h-content-type"><?php esc_html_e( 'Content Type', 'sd-theme-2026' ); ?></h3>
					<!-- /wp:heading -->
				<!-- /wp:facetwp/facet -->

				<?php
				/*
				 * The selected-filter chips. `user_selections` needs no FacetWP
				 * configuration — the block's render.php special-cases the name
				 * and calls `facetwp_display( 'selections' )` — and it renders
				 * nothing while no filter is active. It is worth carrying
				 * precisely because the facet folds shut: with the panel closed
				 * the chips are the only thing that shows what is applied, and
				 * each one removes its own filter.
				 */
				?>
				<!-- wp:facetwp/facet {"facetName":"user_selections","facetLabel":"Selected filters","facetType":"selections","hasHeader":false,"className":"sd-search-selections"} /-->

			</aside>
			<!-- /wp:group -->

			</div>
			<!-- /wp:column -->

			<!-- wp:column {"width":"75%","style":{"spacing":{"blockGap":"var:preset|spacing|40"}}} -->
			<div class="wp-block-column" style="flex-basis:75%">

				<?php
				/*
				 * The toolbar — the count on the leading edge, the sort on the
				 * trailing one, on one flex row above the list. The accommodation
				 * results page's toolbar exactly, and it shares its classes and
				 * its rules. See the head of this file for what the sort facet's
				 * options do across seven post types, why the heading is not
				 * uppercase, where the brackets around the number come from, and
				 * why the row is no longer ruled off beneath.
				 *
				 * ⚠️ `results_count` is a **Pager** facet with its "Pager type"
				 * set to *Result counts* — the count is a mode of the pager
				 * facet type (`facetwp/includes/facets/pager.php:215-218`), not
				 * a type of its own. `hideOnEmpty` is deliberately **off**: the
				 * block's front.js hides any `pager` facet when
				 * `FWP.settings.pager.total_pages < 2`, and a single page of
				 * results still has a count worth showing.
				 */
				?>
				<!-- wp:group {"metadata":{"name":"Results Toolbar"},"className":"sd-search-toolbar","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center","justifyContent":"space-between"}} -->
				<div class="wp-block-group sd-search-toolbar">

					<!-- wp:group {"metadata":{"name":"Result Count"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
					<div class="wp-block-group">

						<!-- wp:heading {"level":2,"style":{"typography":{"textTransform":"none"}},"fontSize":"400","anchor":"h-results"} -->
						<h2 class="wp-block-heading has-400-font-size" id="h-results" style="text-transform:none"><?php esc_html_e( 'Results', 'sd-theme-2026' ); ?></h2>
						<!-- /wp:heading -->

						<!-- wp:facetwp/facet {"facetName":"results_count","facetLabel":"Result count","facetType":"pager","hasHeader":false,"className":"sd-search-counts"} /-->

					</div>
					<!-- /wp:group -->

					<?php
					/*
					 * The sort control, on the trailing edge — the accommodation
					 * results page's facet and its stylesheet rules, so the two
					 * toolbars are one toolbar. → §4 at the head of this file for the
					 * trailing underscore in `sort_`, for what its four options
					 * actually do across seven post types, and for what picking one
					 * costs a relevance-ordered result set.
					 */
					?>
					<!-- wp:facetwp/facet {"facetName":"sort_","facetLabel":"Sort","facetType":"sort","hasHeader":false,"className":"sd-search-sort"} /-->

				</div>
				<!-- /wp:group -->

				<?php
				/*
				 * The loop.
				 *
				 * `inherit: true` is the search query itself — the phrase, the
				 * searchable post types and the relevance ordering all come from
				 * the main query, and there is no way to express a multi-post-type
				 * query on `core/query` otherwise: its `postType` is a single
				 * string. It is also the mode FacetWP's integration documents as
				 * needing `offset` set to 0 rather than left unset, and the
				 * condition under which the no-results block is injected rather
				 * than suppressed.
				 *
				 * `enableFacetWP: true` is the whole facet integration — a
				 * registered boolean attribute FacetWP Blocks (Beta) adds to
				 * `core/query`. ⚠️ **Exactly one FacetWP-enabled block per
				 * page**; a second one silently takes the facets over.
				 *
				 * A single column, because the row *is* the layout.
				 */
				?>
				<!-- wp:query {"queryId":0,"query":{"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[],"perPage":12,"excludeCurrent":null},"enableFacetWP":true,"layout":{"type":"default"}} -->
				<div class="wp-block-query">

					<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
						<?php require __DIR__ . '/card-search-result.php'; ?>
					<!-- /wp:post-template -->

					<!-- wp:query-no-results -->
						<!-- wp:paragraph {"align":"center","textColor":"neutral-700","fontSize":"300"} -->
						<p class="has-text-align-center has-neutral-700-color has-text-color has-300-font-size"><?php esc_html_e( 'Nothing matched that search. Try a different word, or clear the content type filter.', 'sd-theme-2026' ); ?></p>
						<!-- /wp:paragraph -->
					<!-- /wp:query-no-results -->

				</div>
				<!-- /wp:query -->

				<!-- wp:facetwp/facet {"facetName":"pager_","facetLabel":"Pagination","facetType":"pager","hasHeader":false,"hideOnEmpty":true,"className":"sd-search-pager"} /-->

			</div>
			<!-- /wp:column -->

		</div>
		<!-- /wp:columns -->

	</section>
	<!-- /wp:group -->

</main>
<!-- /wp:group -->
