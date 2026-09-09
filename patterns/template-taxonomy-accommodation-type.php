<?php
/**
 * Title: Template: Accommodation Type Taxonomy
 * Slug: sd-theme-2026/template-taxonomy-accommodation-type
 * Description: The accommodation-type results page — the photographic banner carrying the term name, the breadcrumb strip, a FacetWP filter sidebar of dropdowns, and the horizontal accommodation rows behind a result count, a sort control and a FacetWP pager.
 * Categories: hidden
 * Keywords: accommodation, type, taxonomy, search, results, facetwp, facets, filters, dropdown, sidebar
 * Block Types: core/query
 * Template Types: taxonomy
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 *
 * @package sd-theme-2026
 */

/*
 * Ported from https://www.southerndestinations.com/search/accommodation/safari+lodges/,
 * measured in Chrome at 1440 / 992 / 390 on 2026-09-04. That is where live's
 * accommodation-type tiles point. The accommodation archive's tiles link to
 * `get_term_link()` and so land here instead —
 * `patterns/template-archive-accommodation.php` recorded that as an open
 * decision ("routing the tiles at the facet search instead is a decision for
 * whoever builds the search line"). This file is that decision taken the other
 * way: the term archive becomes the results page, and the facet search URL is
 * not reproduced.
 *
 * ## Why it is not a like-for-like port of the live page
 *
 * Live's `/search/accommodation/*` is a **FacetWP legacy template** — a PHP
 * query stored in `facetwp_settings.templates[0]`, read on dev 2026-09-04. It
 * queries `post_type => 'product'` and filters on the `product_cat` taxonomy,
 * feeds SearchWP's post IDs in through `post__in`, and sorts on a
 * `search_price` meta key it writes on every request. Live's accommodation
 * search runs on **WooCommerce products**.
 *
 * There is no WooCommerce in this rebuild, and AGENTS.md is explicit that
 * finding yourself writing commerce markup means a wrong turn has been taken.
 * So what is ported is the *design* — the sidebar, the toolbar, the horizontal
 * rows, the pager — and the mechanism is rebuilt native: a Query Loop over the
 * `accommodation` post type inheriting the term archive's own query, with
 * FacetWP filtering it. That is the source-of-truth order working as intended:
 * the live site is design intent, not implementation.
 *
 * ## The layout is measured, not chosen
 *
 * Live is **two columns, sidebar leading**, and the proportions are Bootstrap 3
 * floats with hard `max-width`s in `lsx-search.css` rather than grid classes:
 * `#secondary` 271px and `#primary` 859px inside a 1140px row — **24% / 75%**.
 * They are reproduced here as a `core/columns` at 25/75, which is the nearest
 * the theme's own measure gets and is how `template-page-right-sidebar.php`
 * already expresses a two-column page. Live's breakpoint is Bootstrap `xs`
 * (<768px); core stacks columns at 781px on its own, so no media query is
 * authored for it.
 *
 * ## Four places this deliberately departs from live
 *
 * 1. **The `h1` is the term name, not "Accommodation".** Live renders
 *    `h1.page-title` = "Accommodation" — the post-type label — identically on
 *    every search URL, and puts the actual query only in the Yoast crumb ("You
 *    searched for safari lodges"). On a *term archive* that would print the
 *    same `h1` on all twenty-six type pages, so `core/query-title` with
 *    `showPrefix: false` renders the queried term instead. This is the one
 *    place the port does not follow live, and it follows the reasoning
 *    `patterns/template-archive-team.php` used to *drop* a hidden Tour Operator
 *    title rather than reproduce an artefact.
 *
 * 2. **The keyword box is a facet, not a form.** Live's sidebar search is a
 *    plain `GET` form to the site root with no hidden `post_type` field, so
 *    submitting it reloads the page, 302s to `/search/<term>` and **loses both
 *    the `accommodation` segment and every active `fwp_*` argument** — measured,
 *    not inferred. Dev already carries a `search` facet, `search_accommodation`,
 *    running SearchWP's `swp_accommodation_engine`; it filters in place through
 *    the same AJAX refresh as the other facets and loses nothing. The facet is
 *    used.
 *
 * 3. **The breadcrumb strip sits below the banner, not inside it.** Live nests
 *    Yoast's trail in `#lsx-banner`. Every other template in this theme runs
 *    `patterns/breadcrumbs.php` as its own tinted band directly beneath the
 *    banner, and consistency across the rebuild's templates is worth more than
 *    matching one page's nesting.
 *
 * 4. **`#accommodation-cta-header` is not repeated here.** Live puts the Best
 *    Price Guarantee / Specials pair on this page as well as on the
 *    accommodation archive. It is authored inline in
 *    `patterns/template-archive-accommodation.php` rather than as a shared
 *    pattern, so reproducing it would mean a third copy to keep in step for a
 *    band that pushes the first result below the fold. A results page shows
 *    results. → flagged, not silently dropped.
 *
 * ## The facet set, and the one facet that cannot work here
 *
 * Live registers exactly **three** facets on this page — `Object.keys(FWP.facets)`
 * returns `destination_to_accommodation`, `specials_to_accommodation` and
 * `accommodation_type`. Not brands, not travel styles, not a price slider,
 * although all of those are configured in `facetwp_settings` for other pages.
 * The first two are carried over verbatim, by name.
 *
 * ⚠️ **`accommodation_type` is left out, and it is not an oversight.** Live can
 * offer it because its search page is not actually scoped: `total_rows` and
 * `total_rows_unfiltered` both come back as 200 on `/safari+lodges/` *and* on
 * bare `/accommodation/`, and the facet lists 16 types with "Safari Lodges
 * (162)" among them — the path segment does not narrow the base query at all.
 * A term archive does narrow it, before FacetWP ever sees the query, so the
 * facet's only possible choice here is the term the visitor is already on. A
 * dropdown offering one pre-applied option reads as broken. The term does that
 * job and is named in the `h1`; broadening to another type is the accommodation
 * archive's grid of tiles, one level up.
 *
 * If the type facet is genuinely wanted alongside the results, this cannot be a
 * taxonomy template — it has to be a page with an unscoped loop, which is a
 * different line item. → Change-Control Register, not a build decision.
 *
 * ## The count, the sort and the pager are facet blocks, not shortcodes
 *
 * FacetWP exposes each of these twice, and the two routes are not equivalent:
 *
 *   - as **extras**, via `[facetwp counts]` / `[facetwp sort]` / `[facetwp pager]`
 *     — `facetwp/includes/class-display.php:120-138`, one shortcode branch each,
 *     needing no entry in `facetwp_settings`;
 *   - as **facet types** — `pager` (whose "Pager type" is *Page numbers*,
 *     *Result counts*, *Load more* or *Per page*) and `sort`, both of which the
 *     `facetwp/facet` block renders like any other facet.
 *
 * The blocks are used. They cost three facets in FacetWP → Settings, and buy a
 * page whose every control is a block the Site Editor can see, move and style,
 * with per-facet settings instead of global ones — the sort orderings in
 * particular belong to this page's facet rather than to the whole site. The
 * block route is also the one the plugin itself is built for: its front.js
 * special-cases `data-type="pager"` when deciding whether a block is empty,
 * which only makes sense for a pager rendered *as a facet*.
 *
 * ⚠️ **Three facets must exist before this page is complete.** Until they do,
 * `facetwp_display()` returns an empty string for a name it cannot find
 * (`class-display.php:75`), so each block renders its wrapper and nothing else
 * — no notice, no fatal. The dropdown script also requires a `.facetwp-facet`
 * child before it will fit a control, so a missing facet never gets one it
 * cannot open.
 *
 *   | Name            | Type  | Setting that matters                          |
 *   |-----------------|-------|-----------------------------------------------|
 *   | `results_count` | Pager | Pager type *Result counts*; count text `([total])` |
 *   | `sort`          | Sort  | The four orderings — see below                |
 *   | `pager`         | Pager | Pager type *Page numbers*; inner size 1       |
 *
 * That configuration is site data in `wp_options`, not theme code, so it is not
 * something this repo can carry. → Change-Control Register note, not a blocker.
 *
 * This also settles pagination. `core/query-pagination` **cannot** be used on a
 * FacetWP page: `add_facetwp_query_args()` reads FacetWP's own `fwp_paged`
 * argument and writes `page`/`paged` straight onto `$GLOBALS['wp_the_query']`,
 * so core's `/page/N/` links are overridden after they are built. Live proves
 * the point from the other side — it renders a windowed `« 1 2 3 … »` and
 * refreshes over AJAX against `/wp-json/facetwp/v1/refresh` with no page load.
 *
 * ## Three settings this template cannot carry
 *
 * All three are FacetWP or WordPress configuration, so they belong to the site
 * and not to the theme. None blocks the page from rendering.
 *
 *   - **Twelve results a page.** Live runs `FWP.settings.pager.per_page: 12`.
 *     An inheriting query takes the archive's own page size, so twelve is
 *     Settings → Reading (or Tour Operator's per-post-type archive setting) —
 *     deliberately not baked in here.
 *   - **The count text.** `[total]` and its siblings are **square**-bracketed
 *     (`includes/facets/pager.php:284-300`), so the plural and singular fields
 *     want `([total])` and the no-results field `(0)` for the toolbar to read
 *     "Results (200)" as live does.
 *   - **The sort options.** Live offers Title A–Z / Z–A and Price Highest /
 *     Lowest, the last two ordering on the `price` custom field. ⚠️ Live's own
 *     labels are inverted — its `price_asc` is labelled "Price (Highest)" — so
 *     set these deliberately rather than transcribing them.
 *
 * ## The dropdowns are the one thing live does differently
 *
 * Live's facets are **Bootstrap accordions** at every breakpoint, collapsed by
 * default with the first force-opened by `lsx-search.js`, and below 768px the
 * whole sidebar becomes an off-canvas drawer behind a "Filters" button with
 * Apply / Close controls. What is built here is a **dropdown** — the panel opens
 * over the results instead of pushing the sidebar open — because that is what
 * was asked for, and it is the same device `kwv-theme-2026` already carries on
 * its shop filters. The behaviour is `assets/js/search-filters.js` and
 * `assets/styles/facetwp-facets.css`; both files carry their own reasoning,
 * including why FacetWP's native `fselect` facet type was not used instead.
 * Switching to live's fold is a change to those two files, not to this one.
 */

?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Accommodation Type Results"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php
	/*
	 * The banner. Live draws `banner-brands-1920x454.png` from the child
	 * theme's `images/` at 380px on this page, where the accommodation archive
	 * draws `accommodation-landing.jpg` at 454px. The archive's photograph and
	 * height are used, so a type page reads as a child of the archive rather
	 * than of the search engine — and it keeps the banner on a media-library
	 * asset (attachment 51864, the `accommodation` block of `_lsx-to_settings`)
	 * instead of a ported PNG.
	 * → template-archive-destination.php for why `dimRatio: 100` is correct
	 * against `is-style-hero-banner`, why the content group is flow layout, and
	 * why the media is addressed by its dev URL.
	 *
	 * The tagline is live's own `.banner-content > p.tagline`, identical on
	 * every accommodation search URL and the same string the archive carries.
	 */
	?>
	<!-- wp:cover {"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/09/accommodation-landing.jpg","alt":"","dimRatio":100,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":454,"minHeightUnit":"px","contentPosition":"bottom center","align":"full","className":"is-style-hero-banner","tagName":"section","metadata":{"name":"Banner"},"style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center is-style-hero-banner" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);min-height:454px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-100 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/09/accommodation-landing.jpg" data-object-fit="cover"/><div class="wp-block-cover__inner-container">

		<!-- wp:group {"metadata":{"name":"Banner Content"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:query-title {"type":"archive","level":1,"showPrefix":false,"className":"is-style-script-accent","fontSize":"800"} /-->

			<!-- wp:paragraph {"className":"is-style-subheading-large","style":{"typography":{"fontWeight":"var:custom|font-weight|medium"}},"fontFamily":"heading"} -->
			<p class="is-style-subheading-large has-heading-font-family" style="font-weight:var(--wp--custom--font-weight--medium)"><?php esc_html_e( 'Africa’s Premium Lodges, Hotels & Safari Camps', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:group -->

	</div></section>
	<!-- /wp:cover -->

	<?php
	/*
	 * The breadcrumb strip — the shared pattern, in the band position every
	 * other SD template uses. `require` rather than a nested `wp:pattern`
	 * reference: a pattern referencing another pattern resolves under WP-CLI and
	 * is silently dropped on front-end render.
	 * → .claude/skills/wp-pattern-runtime-pitfalls
	 */
	require __DIR__ . '/breadcrumbs.php';
	?>

	<!-- wp:group {"tagName":"section","metadata":{"name":"Results"},"align":"full","style":{"spacing":{"blockGap":"var:preset|spacing|50","padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--80)">

		<?php
		/*
		 * `core/term-description` leads the region and renders nothing at all
		 * when the term has no description — which most of the twenty-six do
		 * not. Only the terms that carry one get a standfirst; the rest close
		 * the gap. Live has no editorial term description on this page at all,
		 * so this is additive: a term archive should be able to introduce
		 * itself.
		 *
		 * ⚠️ Not `is-style-archive-intro`. That variation is registered for
		 * `core/paragraph` only (`styles/blocks/paragraph/archive-intro.json`),
		 * so on this block the class would render with nothing behind it. The
		 * colour and size are set as attributes instead, which is what
		 * `patterns/template-page-archive.php` already does for the same block.
		 * Both are safe on a dynamic block: `var:preset|color|…` and
		 * `var:preset|font-size|…` are the two families the server-side style
		 * engine does expand.
		 */
		?>
		<!-- wp:term-description {"align":"wide","textColor":"neutral-700","fontSize":"300"} /-->

		<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
		<div class="wp-block-columns alignwide">

			<?php
			/*
			 * The filter sidebar — live's `#secondary.facetwp-sidebar` at 271
			 * of 1140, so 25%.
			 *
			 * `sd-search-filters` is the hook both the stylesheet and the
			 * script key off. **Renaming it silently disables the dropdowns**,
			 * because the script's container selector and every gated rule in
			 * the stylesheet are written against it.
			 *
			 * The rail is an `aside`, which is the landmark a filter region
			 * should be, and it holds a real `h2` — live's
			 * `h3.facetwp-filter-title` "Refine by", promoted to the level it
			 * belongs at under the banner's `h1`.
			 *
			 * ⚠️ The `aside` is a `core/group` **inside** the column, not the
			 * column itself. `core/column` has exactly three attributes —
			 * `verticalAlignment`, `width`, `templateLock`
			 * (`wp-includes/blocks/column/block.json`) — and no `tagName`, so a
			 * `tagName` written onto it is not a supported attribute and the
			 * `<aside>` in the saved markup would not match what the block's
			 * save function produces: a block-validation error in the editor the
			 * moment the template is opened. `core/group` is the block that
			 * carries `tagName`, which is also how
			 * `patterns/template-page-right-sidebar.php` gets its `aside`.
			 */
			?>
			<!-- wp:column {"width":"25%"} -->
			<div class="wp-block-column" style="flex-basis:25%">

			<!-- wp:group {"tagName":"aside","metadata":{"name":"Filter Rail"},"className":"sd-search-filters","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
			<aside class="wp-block-group sd-search-filters">

				<!-- wp:heading {"level":2,"fontSize":"400","anchor":"h-refine-by"} -->
				<h2 class="wp-block-heading has-400-font-size" id="h-refine-by"><?php esc_html_e( 'Refine by', 'sd-theme-2026' ); ?></h2>
				<!-- /wp:heading -->

				<?php
				/*
				 * The keyword box, leading the rail as it does on live.
				 *
				 * `hasHeader: false` is what keeps it out of the dropdown
				 * treatment: the script's section test requires a heading, so a
				 * headerless facet stays open and usable. Its placeholder and
				 * its `auto_refresh: no` (it filters on Enter, not per
				 * keystroke) are the facet's own FacetWP settings, not theme
				 * strings — which is why there is no copy to translate here.
				 */
				?>
				<!-- wp:facetwp/facet {"facetName":"search_accommodation","facetLabel":"Search","facetType":"search","hasHeader":false} /-->

				<?php
				/*
				 * The two facets live registers on this page, in live's order.
				 *
				 * Each carries `hasHeader: true` **and** an inner
				 * `core/heading`. The attribute is what makes the block print a
				 * label; the inner heading is what makes that label *ours* —
				 * given no inner content the block falls back to echoing the
				 * admin-set `facetLabel` inside a hard-coded `h4`, which is
				 * neither translatable through this theme's text domain nor at
				 * the right level under the `h2` above. The block's
				 * `allowedBlocks: ["core/heading"]` is exactly this affordance.
				 * `facetLabel` is still set because the editor sidebar reads it
				 * and it is the fallback; it is not what renders.
				 *
				 * `hideOnEmpty: true` makes the block ask FacetWP whether the
				 * facet still has choices for the current result set and add
				 * `.facetwp-hidden` to the wrapper when it does not — so a
				 * filter that can no longer narrow anything disappears instead
				 * of opening onto an empty panel. Live does the same thing by
				 * hand in `lsx-search.js`. ⚠️ It is also why nothing in the
				 * stylesheet may set `display` on `.facet-wrap`; that trap is
				 * documented at the head of assets/styles/facetwp-facets.css.
				 *
				 * `destination_to_accommodation` is `hierarchical: yes`, so
				 * FacetWP may nest children in `.facetwp-depth`. On live all ten
				 * choices come back at depth 0 and nothing nests, but the
				 * stylesheet carries the indent rule regardless — the data can
				 * change without the markup doing so.
				 */
				?>
				<!-- wp:facetwp/facet {"facetName":"destination_to_accommodation","facetLabel":"Destinations","facetType":"checkboxes","hasHeader":true,"hideOnEmpty":true} -->
					<!-- wp:heading {"level":3,"fontSize":"200"} -->
					<h3 class="wp-block-heading has-200-font-size"><?php esc_html_e( 'Destinations', 'sd-theme-2026' ); ?></h3>
					<!-- /wp:heading -->
				<!-- /wp:facetwp/facet -->

				<!-- wp:facetwp/facet {"facetName":"specials_to_accommodation","facetLabel":"Specials","facetType":"checkboxes","hasHeader":true,"hideOnEmpty":true} -->
					<!-- wp:heading {"level":3,"fontSize":"200"} -->
					<h3 class="wp-block-heading has-200-font-size"><?php esc_html_e( 'Specials', 'sd-theme-2026' ); ?></h3>
					<!-- /wp:heading -->
				<!-- /wp:facetwp/facet -->

				<?php
				/*
				 * The selected-filter chips. Live renders none — `.facetwp-selections`
				 * is absent from the delivered HTML before and after filtering,
				 * and `lsx-search.js` carries a `clearFacets()` whose
				 * `clear-facets` control never appears — so this is additive.
				 *
				 * It is worth adding precisely because the facets are dropdowns
				 * here: with the panels shut, the chips are the only thing that
				 * shows what is currently applied, and each one removes its own
				 * filter. `user_selections` needs no FacetWP configuration —
				 * the block's render.php special-cases the name and calls
				 * `facetwp_display( 'selections' )` — and it renders nothing
				 * while no filter is active.
				 */
				?>
				<!-- wp:facetwp/facet {"facetName":"user_selections","facetLabel":"Selected filters","facetType":"selections","hasHeader":false,"className":"sd-search-selections"} /-->

			</aside>
			<!-- /wp:group -->

			</div>
			<!-- /wp:column -->

			<?php
			/*
			 * The results column — live's `#primary` at 859 of 1140, so 75%.
			 */
			?>
			<!-- wp:column {"width":"75%","style":{"spacing":{"blockGap":"var:preset|spacing|40"}}} -->
			<div class="wp-block-column" style="flex-basis:75%">

				<?php
				/*
				 * The toolbar — live's `#facetwp-top`: the result count on the
				 * leading edge, the sort control on the trailing one, on one
				 * flex row above the list.
				 *
				 * Live renders the count as `Results (<div class="facetwp-counts">200</div>)`
				 * inside a single `h3`, with the parentheses as literal text
				 * either side of the div. The `h2` keeps "Results" translatable
				 * and gives the results region the heading it needs — without
				 * one the card titles (`h3`) would read as children of the
				 * sidebar's "Refine by". The brackets come from the facet's own
				 * count text, configured in FacetWP; see the ⚠️ below.
				 *
				 * Live hides this whole row below 768px (`hidden-xs`), taking
				 * the sort control away with it. It is kept at every width here:
				 * a result count is more useful on a phone than on a desktop,
				 * and losing the only sort control at that width is a defect
				 * rather than a design.
				 */
				?>
				<!-- wp:group {"metadata":{"name":"Results Toolbar"},"className":"sd-search-toolbar","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center","justifyContent":"space-between"}} -->
				<div class="wp-block-group sd-search-toolbar">

					<!-- wp:group {"metadata":{"name":"Result Count"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"bottom"}} -->
					<div class="wp-block-group">

						<!-- wp:heading {"level":2,"fontSize":"400","anchor":"h-results"} -->
						<h2 class="wp-block-heading has-400-font-size" id="h-results"><?php esc_html_e( 'Results', 'sd-theme-2026' ); ?></h2>
						<!-- /wp:heading -->

						<?php
						/*
						 * ⚠️ `results_count` is a **Pager** facet with its
						 * "Pager type" set to *Result counts* — the count is a
						 * mode of the pager facet type
						 * (`facetwp/includes/facets/pager.php:215-218`), not a
						 * type of its own. Its "Count text" fields take
						 * `[total]`, `[lower]`, `[upper]` and friends in
						 * **square** brackets, so set plural and singular to
						 * `([total])` and no-results to `(0)` to read as live's
						 * "Results (200)". Left at FacetWP's default it prints a
						 * whole sentence beside the heading instead.
						 *
						 * `hideOnEmpty` is deliberately **off** here even though
						 * it is on for the pager below. The block's front.js
						 * keys that check off `data-type` — and for *any* facet
						 * of type `pager` it hides the block when
						 * `FWP.settings.pager.total_pages < 2`. On the count
						 * that is exactly wrong: a single page of results still
						 * has a count worth showing.
						 */
						?>
						<!-- wp:facetwp/facet {"facetName":"results_count","facetLabel":"Result count","facetType":"pager","hasHeader":false,"className":"sd-search-counts"} /-->

					</div>
					<!-- /wp:group -->

					<?php
					/*
					 * The sort control. A **Sort** facet, whose own
					 * `sort_options` carry the orderings — which is why this is
					 * a facet and not the global sort extra: the options belong
					 * to this page's facet, not to the whole site.
					 *
					 * ⚠️ The sort facet renders a bare `<select>` with **no
					 * class** (`includes/facets/sort.php:34`). The
					 * `.facetwp-sort-select` class seen on live belongs to the
					 * `sort` *extra*, which is a different code path. The
					 * stylesheet targets the element, not that class.
					 */
					?>
					<!-- wp:facetwp/facet {"facetName":"sort","facetLabel":"Sort By","facetType":"sort","hasHeader":false,"className":"sd-search-sort"} /-->

				</div>
				<!-- /wp:group -->

				<?php
				/*
				 * The loop.
				 *
				 * `enableFacetWP: true` is the whole integration. It is a
				 * registered boolean attribute added to `core/query` by FacetWP
				 * Blocks (Beta)
				 * (`facetwp-blocks-beta/src/attributes/sidebarSelect.js`),
				 * surfaced in the editor as an "Enable FacetWP" toggle. On
				 * render the plugin puts a `facetwp-template` class on the inner
				 * `core/post-template` and adds `facetwp => true` to the query
				 * args. ⚠️ **Exactly one FacetWP-enabled block per page** — the
				 * plugin's own help text says so, and a second one silently
				 * takes the facets over.
				 *
				 * `inherit: true` is load-bearing three times over: it scopes
				 * the loop to the queried term without a `taxQuery` this file
				 * would otherwise have to hardcode per page; it is the mode
				 * FacetWP's integration documents as needing `offset` set to 0
				 * rather than left unset; and it is the condition under which
				 * the no-results block is injected rather than suppressed.
				 *
				 * A single column, because the row *is* the layout. Live's
				 * `.post-wrapper` is a wrapping flex whose articles are
				 * `flex: 0 1 100%` — a one-column list expressed the long way
				 * round.
				 */
				?>
				<!-- wp:query {"queryId":0,"query":{"pages":0,"offset":0,"postType":"accommodation","order":"asc","orderBy":"title","author":"","search":"","exclude":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[]},"enableFacetWP":true,"layout":{"type":"default"}} -->
				<div class="wp-block-query">

					<?php
					/*
					 * The row. `require` for the runtime reason above, and it
					 * is the card pattern this theme already carries —
					 * `styles/sections/cards/listing-card-list.json` was
					 * measured against this very page in August.
					 *
					 * ⚠️ Three deltas from live measured 2026-09-04, all left
					 * for the card's own uplift rather than corrected here:
					 * live's thumbnail is **29%** of the row where the pattern
					 * uses 25%; live's excerpt is **26 words** where the pattern
					 * asks for 40; and live's card ground is `#f6f3f0` with the
					 * meta strip on `#f0ebe5` at **33%**, where the pattern puts
					 * a `neutral-200` strip at 25% on a `base` card. Live also
					 * shows no hover state at all on this template — the Tour
					 * Operator hover rules need a `.lsx-to-archive-item`
					 * ancestor that this page never renders — so the theme's
					 * hover is additive and wanted.
					 */
					?>
					<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
						<?php require __DIR__ . '/card-accommodation-list.php'; ?>
					<!-- /wp:post-template -->

					<?php
					/*
					 * Live and reachable **on this template only**. The ⚠️ on
					 * `patterns/template-archive-team.php` records that FacetWP
					 * Blocks Beta hooks `render_block_core/query-no-results` and
					 * returns `''` unconditionally (LS-2529), which kills the
					 * fallback on every *non-inheriting* loop in this theme. Its
					 * stated reason — that core prints no-results content inside
					 * `core/post-template` when the query inherits from the
					 * template — is true, and this loop does inherit, so the
					 * plugin stores this block and injects it there. The message
					 * renders.
					 */
					?>
					<!-- wp:query-no-results -->
						<!-- wp:paragraph {"align":"center","textColor":"neutral-700","fontSize":"300"} -->
						<p class="has-text-align-center has-neutral-700-color has-text-color has-300-font-size"><?php esc_html_e( 'No properties match those filters. Try clearing a filter, or browse another accommodation type.', 'sd-theme-2026' ); ?></p>
						<!-- /wp:paragraph -->
					<!-- /wp:query-no-results -->

				</div>
				<!-- /wp:query -->

				<?php
				/*
				 * The pager — FacetWP's, not `core/query-pagination`, for the
				 * reason set out in this file's header.
				 *
				 * A **Pager** facet with "Pager type" set to *Page numbers*,
				 * which gives live's windowed `« 1 2 3 … »`. Its "Inner size"
				 * is the ±N around the current page (live runs 1) and its dots,
				 * prev and next labels are facet settings too.
				 *
				 * `hideOnEmpty: true` is right here, and it is the one facet
				 * type the block's empty test handles properly: it reads
				 * `FWP.settings.pager.total_pages` and hides the block on a
				 * single-page result, rather than the `num_choices` check it
				 * uses for everything else.
				 */
				?>
				<!-- wp:facetwp/facet {"facetName":"pager","facetLabel":"Pagination","facetType":"pager","hasHeader":false,"hideOnEmpty":true,"className":"sd-search-pager"} /-->

			</div>
			<!-- /wp:column -->

		</div>
		<!-- /wp:columns -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The closing band. Live ends this page on `#footer-choose-cta`, then a
	 * Trustpilot widget, then `#footer-accommodation-brands`. Only the first is
	 * carried: the brands shelf would sit oddly under a list a destination
	 * facet has just narrowed, and the Trustpilot widget is not a section this
	 * template owns.
	 */
	require __DIR__ . '/why-choose-sd.php';
	?>

</main>
<!-- /wp:group -->
