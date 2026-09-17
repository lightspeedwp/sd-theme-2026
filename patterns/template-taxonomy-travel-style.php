<?php
/**
 * Title: Template: Travel Style
 * Slug: sd-theme-2026/template-taxonomy-travel-style
 * Description: The travel-style term archive — the tour search banner, the breadcrumb strip, then the tour rows behind a keyword search, a Destinations filter and a Travel Styles filter, with a result count, a sort control and a FacetWP pager. Used by the travel-style taxonomy template.
 * Categories: hidden
 * Keywords: template, taxonomy, travel style, tours, query, facetwp, facets, filters, sort
 * Block Types: core/query
 * Template Types: taxonomy-travel-style
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 *
 * @package sd-theme-2026
 */

/*
 * The travel-style results page — the single-post-type sibling of
 * `patterns/template-page-search.php`. **That file is the reference for
 * everything structural here**, and its reasoning is recorded there rather than
 * repeated: the 25/75 rail-and-results split, the `sd-search-*` hooks the
 * stylesheet and the fold script key off, `enableFacetWP` on the loop, the
 * count as a Pager facet in *Result counts* mode, the `sort_` and `pager_`
 * trailing underscores, the `is-style-light-page-section` ground.
 *
 * ⚠️ `patterns/template-taxonomy-accommodation-type.php` is **not** a reference
 * for this page. It predates the search results page and is itself due a pass
 * to bring it into line (Zared, 2026-09-17); copying from it would propagate
 * what that pass exists to correct.
 *
 * Five things are this page's own.
 *
 * ## 1. What this template is for
 *
 * Live has no travel-style term archive. The equivalent page is a hacked site
 * search — `/search/tours/beach+safari+vacations` — whose FacetWP template
 * query (stored on dev in `facetwp_settings.templates[0]`) resolves the URL
 * segment to a `tour_keyword` term, runs it through SearchWP, and returns
 * WooCommerce `product` posts filtered to the `tours` product category.
 *
 * None of that survives the rebuild: there is no WooCommerce, the tours are
 * `tour` posts, and the travel styles are a real taxonomy. So the page the
 * reader reaches from the Tours mega-menu and from the tours archive's Travel
 * Styles shelf is the term archive, `/travel-style/<slug>/`, and this is its
 * template.
 *
 * ## 2. The banner, the heading and the standfirst are live's, verbatim
 *
 * Measured on `/search/tours/beach+safari+vacations` 2026-09-17: the banner is
 * `sd-lsx-child/images/tour-search-banner.jpg`, the `h1.page-title` is "Tours &
 * Safaris" and the `p.tagline` is "Trip ideas to inspire your own!".
 *
 * The photograph was not in the media library — the `sd-lsx-child` path is a
 * theme file, and this rebuild addresses every banner by its uploads URL — so
 * it was uploaded to dev on 2026-09-17 and is addressed the same way as the
 * rest. → `template-archive-destination.php` for why `dimRatio: 100` is correct
 * against `is-style-hero-banner`, why the content group is flow layout, and why
 * the media is addressed by its dev URL.
 *
 * ⚠️ **The `h1` is static, and it is static on every term.** That is live's
 * behaviour: its tour search banner says "Tours & Safaris" whichever travel
 * style you are looking at, and the term name appears only in the breadcrumb.
 * Carried deliberately, because the brief is to preserve the design rather than
 * improve it — but it is the one thing on this page worth a second look, since
 * a term archive whose heading never names its term is weak orientation and
 * weaker SEO. **Swapping it is a one-block change**: replace the `core/heading`
 * below with
 * `<!-- wp:query-title {"type":"archive","level":1,"showPrefix":false,"className":"is-style-script-accent","fontSize":"800"} /-->`
 * and the standfirst can stay as it is.
 *
 * The standfirst is authored at `font-weight: medium`, which is what nine of
 * the ten `is-style-subheading-large` standfirsts in this theme carry. The
 * tours archive is the outlier at `semi-bold`; it is not the precedent.
 *
 * ## 3. The rail is the tour search, Destinations and Travel Styles
 *
 * The keyword box is a **FacetWP search facet**, not `core/search` (Zared,
 * 2026-09-17). The search results page uses `core/search` because there the
 * keyword *is* the query — the page only exists because of `s=`. Here the
 * keyword is a filter over a term archive, exactly as it is on the
 * accommodation results page, so it is a facet: it narrows what the term
 * returned instead of navigating away from it. `hasHeader: false` keeps it out
 * of the fold treatment — the script's section test requires a heading — and
 * leaves it unplated, as live's keyword box is.
 *
 * The facet is registered. Measured in `facetwp_settings` on dev 2026-09-17,
 * after Zared added it: name `search_tours` — **plural**, and the `facetName`
 * below has to match it character for character or the block renders nothing —
 * label "Search - Tours", type `Search`, engine `swp_tours`, placeholder
 * "Search tours...", relevance `yes`, auto refresh `yes`.
 *
 * `swp_tours` is **SearchWP - Tours** as it stores:
 * `facetwp/includes/integrations/searchwp/searchwp.php:202` keys the dropdown
 * `swp_ . $engine_key` and `:171` strips the four-character prefix back off.
 * The engine is `searchwp_engines['tours']`, sourced from `post.tour`,
 * weighting `travel-style` at 90.
 *
 * ⚠️ While you are in there: `search_accommodation` is set to
 * `swp_accommodation_engine`, which resolves to an engine named
 * `accommodation_engine`. Dev's engine is keyed `accommodation`. That facet is
 * pointing at nothing — an existing defect on the accommodation results page,
 * not something this template introduces.
 *
 * The two filters are live's, in live's order, minus one:
 *
 *   - `destinations_to_tour` — "Destinations", `cf/destination_to_tour`,
 *     hierarchical, the plural-named facet live's page actually uses. (Dev also
 *     carries a singular `destination_to_tour` on the same source; they are
 *     duplicates and this is the one with live's `count: 20`.)
 *   - `travel_style` — "Travel Styles", `tax/travel-style`. Redundant with the
 *     queried term on its own, but its operator is AND, so ticking a second
 *     style narrows to tours carrying both — which is the only way to reach a
 *     combination from here.
 *
 * ⚠️ **Live's "Price Per Person" slider is deliberately not carried.** The
 * `price` facet reads `cf/price`, and `price` is an *accommodation* field in
 * Tour Operator 2.2 — `includes/metaboxes/config-accommodation.php:181` is the
 * only place it is registered. Live could offer it because live's tour search
 * ran over WooCommerce `product` posts and sorted on `_regular_price` /
 * `search_price`; the rebuild has neither. The facet would index empty and
 * `hideOnEmpty` would hide it on every request, so it is left out rather than
 * carried as a control that never appears. **Noted for LS-2033** — restoring a
 * tour price filter needs a price on the tour, which is a data-model question
 * and not theme work.
 *
 * ## 4. The loop is scoped to tours in the plugin, not here
 *
 * `travel-style` is registered against **six** post types — accommodation,
 * tour, destination, review, vehicle and special
 * (`tour-operator/includes/taxonomies/config-travel-style.php:18-25`) — and
 * they all carry terms. Measured on dev 2026-09-17: 68 published
 * accommodations, 37 reviews, 26 tours, 13 destinations and 3 specials.
 *
 * `inherit: true` is not optional on this page: it is what scopes the loop to
 * the queried term without a hardcoded `taxQuery`, it is the mode FacetWP's
 * integration requires, and it is the condition under which `core/query-no-results`
 * is injected rather than suppressed. But it also means the loop returns every
 * one of those post types, through a tour-shaped card.
 *
 * So the constraint is a `pre_get_posts` on the main query, in
 * `sd-enhancements-2026`'s queries module — `Queries::limit_travel_style_archive()`.
 * It belongs there under this project's deactivation test: the page is wrong
 * without it whether or not this theme is active.
 *
 * ⚠️ **Plugin dependency.** With `sd-enhancements-2026` deactivated this page
 * lists accommodation and reviews through the tour card.
 *
 * ## 5. The sort control's price orderings do nothing here
 *
 * `sort_` is the shared facet, so the toolbar is the search page's toolbar. Two
 * of its four options — Price (Highest) and Price (Lowest) — order on
 * `cf/price_rating`, which only accommodation carries, so on a tours-only
 * result set they leave the order as it was. Title (A-Z) and Title (Z-A) work.
 * The option list is a FacetWP setting rather than theme markup; trimming it,
 * or registering a second sort facet for this page, is done in the admin and
 * nothing here changes if it is.
 */

?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Travel Style Results"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<!-- wp:cover {"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2026/09/tour-search-banner.jpg","alt":"","dimRatio":100,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":454,"minHeightUnit":"px","contentPosition":"bottom center","align":"full","className":"is-style-hero-banner","tagName":"section","metadata":{"name":"Banner"},"style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center is-style-hero-banner" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);min-height:454px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-100 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2026/09/tour-search-banner.jpg" data-object-fit="cover"/><div class="wp-block-cover__inner-container">

		<!-- wp:group {"metadata":{"name":"Banner Content"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:heading {"level":1,"className":"is-style-script-accent","fontSize":"800","anchor":"h-tours-safaris"} -->
			<h1 class="wp-block-heading is-style-script-accent has-800-font-size" id="h-tours-safaris"><?php esc_html_e( 'Tours & Safaris', 'sd-theme-2026' ); ?></h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"className":"is-style-subheading-large","style":{"typography":{"fontWeight":"var:custom|font-weight|medium"}},"fontFamily":"heading"} -->
			<p class="is-style-subheading-large has-heading-font-family" style="font-weight:var(--wp--custom--font-weight--medium)"><?php esc_html_e( 'Trip ideas to inspire your own!', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:group -->

	</div></section>
	<!-- /wp:cover -->

	<?php
	/*
	 * The breadcrumb bar, directly under the banner — the same
	 * `patterns/breadcrumbs.php` every other inner page runs, in the same
	 * position, and on this template the only place the term is named.
	 * `require` rather than a nested `wp:pattern` reference, because a pattern
	 * referencing another pattern resolves under WP-CLI and is silently dropped
	 * on front-end render.
	 * → .claude/skills/wp-pattern-runtime-pitfalls
	 */
	require __DIR__ . '/breadcrumbs.php';
	?>

	<!-- wp:group {"tagName":"section","metadata":{"name":"Results"},"align":"full","className":"is-style-light-page-section","layout":{"type":"constrained"}} -->
	<section class="wp-block-group alignfull is-style-light-page-section">

		<?php
		/*
		 * ⚠️ No `core/term-description`. The travel-style terms carry none on
		 * dev, and the accommodation-type page removed the block for the same
		 * reason — a standfirst that is empty on almost every term still costs
		 * the reader the scroll on the ones where it is not.
		 */
		?>
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
			 * → template-page-search.php
			 */
			?>
			<!-- wp:column {"width":"25%"} -->
			<div class="wp-block-column" style="flex-basis:25%">

			<!-- wp:group {"tagName":"aside","metadata":{"name":"Filter Rail"},"className":"sd-search-filters","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"default"}} -->
			<aside class="wp-block-group sd-search-filters">

				<?php
				/*
				 * The keyword box — a FacetWP search facet over the Tours
				 * SearchWP engine, above "Refine by" because searching is not
				 * refining. Its placeholder and its `auto_refresh` are the
				 * facet's own FacetWP settings rather than theme strings, which
				 * is why there is no copy to translate here.
				 *
				 * ⚠️ `facetName` is `search_tours`, plural, because that is the
				 * name the facet is registered under — §3 at the head of this
				 * file has the rest of its settings as measured. A mismatch here
				 * renders nothing, silently.
				 */
				?>
				<!-- wp:facetwp/facet {"facetName":"search_tours","facetLabel":"Search - Tours","facetType":"search","hasHeader":false} /-->

				<!-- wp:heading {"level":2,"fontSize":"400","anchor":"h-refine-by"} -->
				<h2 class="wp-block-heading has-400-font-size" id="h-refine-by"><?php esc_html_e( 'Refine by', 'sd-theme-2026' ); ?></h2>
				<!-- /wp:heading -->

				<?php
				/*
				 * The two filters, in live's order.
				 *
				 * `hasHeader: true` makes each block print a label; the inner
				 * `core/heading` makes that label ours — given no inner content
				 * the block echoes the admin-set `facetLabel` inside a
				 * hard-coded `h4`, which is neither translatable through this
				 * theme's text domain nor at the right level under the `h2`
				 * above.
				 *
				 * `hideOnEmpty: true` asks FacetWP whether the facet still has
				 * choices for the current result set and adds `.facetwp-hidden`
				 * when it does not. ⚠️ Nothing in the stylesheet may set
				 * `display` on `.facet-wrap`; that trap is documented at the
				 * head of assets/styles/facetwp-facets.css.
				 *
				 * `destinations_to_tour` is `hierarchical: yes`, so FacetWP may
				 * nest children in `.facetwp-depth`; the stylesheet carries the
				 * indent rule whether or not the current data nests.
				 */
				?>
				<!-- wp:facetwp/facet {"facetName":"destinations_to_tour","facetLabel":"Destinations","facetType":"checkboxes","hasHeader":true,"hideOnEmpty":true} -->
					<!-- wp:heading {"level":3,"fontSize":"400","anchor":"h-destinations"} -->
					<h3 class="wp-block-heading has-400-font-size" id="h-destinations"><?php esc_html_e( 'Destinations', 'sd-theme-2026' ); ?></h3>
					<!-- /wp:heading -->
				<!-- /wp:facetwp/facet -->

				<!-- wp:facetwp/facet {"facetName":"travel_style","facetLabel":"Travel Styles","facetType":"checkboxes","hasHeader":true,"hideOnEmpty":true} -->
					<!-- wp:heading {"level":3,"fontSize":"400","anchor":"h-travel-styles"} -->
					<h3 class="wp-block-heading has-400-font-size" id="h-travel-styles"><?php esc_html_e( 'Travel Styles', 'sd-theme-2026' ); ?></h3>
					<!-- /wp:heading -->
				<!-- /wp:facetwp/facet -->

			</aside>
			<!-- /wp:group -->

			</div>
			<!-- /wp:column -->

			<!-- wp:column {"width":"75%","style":{"spacing":{"blockGap":"var:preset|spacing|40"}}} -->
			<div class="wp-block-column" style="flex-basis:75%">

				<?php
				/*
				 * The toolbar — the count on the leading edge, the sort on the
				 * trailing one, on one flex row above the list. The search
				 * results page's toolbar exactly: same classes, same facets,
				 * same rules in `assets/styles/facetwp-facets.css`. See
				 * `template-page-search.php` for why the heading is authored
				 * `textTransform: none`, where the brackets around the number
				 * come from, and why `results_count` does **not** take
				 * `hideOnEmpty`. §5 at the head of this file covers what the
				 * sort facet's price orderings do on a tours-only result set.
				 */
				?>
				<!-- wp:group {"metadata":{"name":"Results Toolbar"},"className":"sd-search-toolbar","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center","justifyContent":"space-between"}} -->
				<div class="wp-block-group sd-search-toolbar">

					<!-- wp:group {"metadata":{"name":"Result Count"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
					<div class="wp-block-group">

						<!-- wp:heading {"level":2,"style":{"typography":{"textTransform":"none"},"spacing":{"padding":{"top":"4px","bottom":"0px"}}},"fontSize":"400","anchor":"h-results"} -->
						<h2 class="wp-block-heading has-400-font-size" id="h-results" style="padding-top:4px;padding-bottom:0px;text-transform:none"><?php esc_html_e( 'Results', 'sd-theme-2026' ); ?></h2>
						<!-- /wp:heading -->

						<!-- wp:facetwp/facet {"facetName":"results_count","facetLabel":"Result count","facetType":"pager","hasHeader":false,"className":"sd-search-counts"} /-->

					</div>
					<!-- /wp:group -->

					<!-- wp:facetwp/facet {"facetName":"sort_","facetLabel":"Sort","facetType":"sort","hasHeader":false,"className":"sd-search-sort"} /-->

				</div>
				<!-- /wp:group -->

				<?php
				/*
				 * The loop.
				 *
				 * `inherit: true` scopes it to the queried term, is the mode
				 * FacetWP's integration documents as needing `offset` set to 0
				 * rather than left unset, and is the condition under which the
				 * no-results block is injected rather than suppressed. The
				 * `postType` written below is inert while `inherit` is true —
				 * the tours-only constraint is the plugin's, §4 above.
				 *
				 * `enableFacetWP: true` is the whole facet integration — a
				 * registered boolean attribute FacetWP Blocks (Beta) adds to
				 * `core/query`. ⚠️ **Exactly one FacetWP-enabled block per
				 * page**; a second one silently takes the facets over.
				 *
				 * A single column, because the row *is* the layout.
				 */
				?>
				<!-- wp:query {"queryId":0,"query":{"pages":0,"offset":0,"postType":"tour","order":"asc","orderBy":"title","author":"","search":"","exclude":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[],"perPage":12,"excludeCurrent":null},"enableFacetWP":true,"layout":{"type":"default"}} -->
				<div class="wp-block-query">

					<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
						<?php require __DIR__ . '/card-tour-list.php'; ?>
					<!-- /wp:post-template -->

					<!-- wp:query-no-results -->
						<!-- wp:paragraph {"align":"center","textColor":"neutral-700","fontSize":"300"} -->
						<p class="has-text-align-center has-neutral-700-color has-text-color has-300-font-size"><?php esc_html_e( 'No tours match those filters. Try clearing a filter, or browse another travel style.', 'sd-theme-2026' ); ?></p>
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

	<?php
	/*
	 * The closing band. The tours archive ends on "Why choose Southern
	 * Destinations" and this page is a child of it, so it ends the same way.
	 * `require` for the nested-pattern reason above.
	 */
	require __DIR__ . '/why-choose-sd.php';
	?>

</main>
<!-- /wp:group -->
