<?php
/**
 * Title: Template: Accommodation Brand Taxonomy
 * Slug: sd-theme-2026/template-taxonomy-accommodation-brand
 * Description: The single-brand archive — the photographic banner carrying the brand name, the breadcrumb strip, a two-column intro of the brand's story beside its logo, then the region tabs over a FacetWP filter rail and the brand's accommodation as horizontal rows.
 * Categories: hidden
 * Keywords: brand, accommodation, taxonomy, operator, lodge, regions, tabs, facetwp, facets, filters
 * Block Types: core/query
 * Template Types: taxonomy
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 *
 * @package sd-theme-2026
 */

/*
 * Ported from https://www.southerndestinations.com/brand/natural-selection/,
 * read 2026-09-10, with four deliberate departures asked for at the time —
 * they are listed under "What this does not copy from live" below.
 *
 * The sibling template `patterns/template-taxonomy-accommodation-type.php`
 * reasoned out the whole facet apparatus — why the count, sort and pager are
 * facet *blocks* rather than shortcodes, why `core/query-pagination` cannot be
 * used on a FacetWP page, why each facet carries an inner `core/heading`, and
 * the three settings that live in `wp_options` rather than in theme code. All
 * of that applies here unchanged and is not restated; read that file first.
 *
 * ## What this does not copy from live
 *
 * 1. **The filters are out of the intro.** Live stacks "Refine by" directly
 *    under the brand's story, above the results, so the first thing on the page
 *    after the copy is a wall of controls. They move into the results section's
 *    leading rail, which is where the type archive already puts them and where
 *    a filter belongs relative to the thing it filters.
 *
 * 2. **The intro is two columns, not one.** The brand's story on the leading
 *    side, its logo on the trailing side. Live prints the logo inline above the
 *    copy at full width, which on a wide wordmark leaves the paragraph starting
 *    a third of the way down the page.
 *
 * 3. **The story is collapsed behind a Read more.** Brand descriptions run long
 *    — see the note on the collapse below for why this needed a script where
 *    `patterns/destination-summary.php` did not.
 *
 * 4. **The rows are the horizontal accommodation card.** Live renders a
 *    150x150 thumbnail beside the name, excerpt, price band, type and location;
 *    `patterns/card-accommodation-list.php` is that row and is already the card
 *    the type archive uses. One card, two templates.
 *
 * ## The region tabs are links, and that is load-bearing
 *
 * ⚠️ **This template renders `sd/brand-regions`, not `core/tabs`.** WordPress
 * 7.1 does ship `core/tabs` (with `core/tab-list`, `core/tab-panels` and
 * `core/tab-panel` — verified registered on this install), and it was the
 * asked-for block. It is not used, for two reasons that are worth stating
 * plainly because the second one is not reversible by a design decision:
 *
 *   - **The tab set is derived per brand, and `core/tabs` is authored.**
 *     `core/tab-list` stores its labels as a saved `tabs` array and
 *     `core/tab-panels` holds one authored `core/tab-panel` per tab. A brand's
 *     regions are the top-level destinations its accommodation is connected to
 *     — three for Natural Selection, a different three for Wilderness Safaris —
 *     so the panels cannot be written into a template that serves all
 *     twenty-one brands. A dynamic block would have to emit the tabs markup and
 *     its Interactivity directives server-side.
 *
 *   - **`brand/{brand}/{region}/` is a real URL, and the redirect map depends
 *     on it.** `SD\Enhancements\BrandEndpoints` registers that rewrite and
 *     `modules/brand-endpoints.php` opens by calling it "the only bespoke URL
 *     structure on the site, and it is launch-critical: it feeds the redirect
 *     map." `core/tabs` switches panels client-side on one URL; adopting it
 *     without keeping the endpoint would take those URLs off the site.
 *
 * So the sanctioned block is used: `sd/brand-regions` renders the strip as
 * links, marks the active one with `aria-current="page"`, hides itself when a
 * brand has fewer than two regions, and needs no JavaScript. LS-2528 (Done)
 * built it. → The full note on what a `core/tabs` conversion would take, and
 * the query gap below, is in `.github/tasks/`.
 *
 * ⚠️ **The tabs do not narrow the results yet.** `BrandEndpoints` resolves the
 * `endpoint` query var and `BrandRegions::current_endpoint()` reads it to mark
 * the active tab, but nothing filters the accommodation query by it — grepped
 * across sd-enhancements 2026-09-10: the only readers of `endpoint` are the
 * resolver and the tab strip. `Queries::bound_brand_archive()` caps the archive
 * at a page size and does nothing per region. So today every region tab returns
 * the same rows. That is plugin work in sd-enhancements, not something a
 * template can fix. → flagged, see the task note.
 *
 * ## The facet set
 *
 * Live registers four controls on this page — a keyword box, Destination,
 * Specials and Types. All four are carried. **Types belongs here where it did
 * not on the type archive**: this archive is scoped by *brand*, so
 * `accommodation_type` still has real choices to offer, where on
 * `taxonomy-accommodation-type` its only possible value was the term the
 * visitor was already standing on.
 *
 * ⚠️ Destination overlaps the region tabs — both narrow by place, one by URL
 * and one by facet, and neither knows about the other. Once the endpoint scopes
 * the query (above), the two will need to compose or one will need to go.
 * → flagged with the query gap, same task note.
 *
 * The same ⚠️ from the type archive applies: `facetwp_display()` returns an
 * empty string for a facet name that does not exist in `facetwp_settings`, with
 * no notice and no fatal, so each block renders its wrapper and nothing else
 * until the facets are configured. That configuration is site data in
 * `wp_options`, not theme code.
 */

?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Brand Archive"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php
	/*
	 * The banner — live's own `banner-brands-1920x454.png`, attachment 51693 on
	 * dev, the same photograph the Brands landing carries so a brand page reads
	 * as a child of it.
	 * → template-archive-destination.php for why `dimRatio: 100` is correct
	 * against `is-style-hero-banner` and why the media is addressed by its dev URL.
	 *
	 * `showPrefix: false` renders the brand name alone. Core's default would
	 * print "Accommodation Brand: Natural Selection", which is the taxonomy's
	 * label leaking into the design.
	 */
	?>
	<!-- wp:cover {"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/08/banner-brands-1920x454.png","alt":"","dimRatio":100,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":454,"minHeightUnit":"px","contentPosition":"bottom center","align":"full","className":"is-style-hero-banner","tagName":"section","metadata":{"name":"Banner"},"style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center is-style-hero-banner" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);min-height:454px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-100 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/08/banner-brands-1920x454.png" data-object-fit="cover"/><div class="wp-block-cover__inner-container">

		<!-- wp:group {"metadata":{"name":"Banner Content"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:query-title {"type":"archive","level":1,"showPrefix":false,"className":"is-style-script-accent","fontSize":"800"} /-->

			<!-- wp:paragraph {"className":"is-style-subheading-large","style":{"typography":{"fontWeight":"var:custom|font-weight|medium"}},"fontFamily":"heading"} -->
			<p class="is-style-subheading-large has-heading-font-family" style="font-weight:var(--wp--custom--font-weight--medium)"><?php esc_html_e( 'Accommodation', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:group -->

	</div></section>
	<!-- /wp:cover -->

	<?php
	/*
	 * The breadcrumb strip. `require` rather than a nested `wp:pattern`
	 * reference: a pattern referencing another pattern resolves under WP-CLI and
	 * is silently dropped on front-end render.
	 * → .claude/skills/wp-pattern-runtime-pitfalls
	 */
	require __DIR__ . '/breadcrumbs.php';
	?>

	<?php
	/*
	 * The intro band — the brand's story beside its logo.
	 *
	 * 8/4 of twelve, the same split `patterns/template-archive-tour.php` uses
	 * for copy-beside-panel, and the columns are top-aligned so a short
	 * description does not drag the logo down the page with it.
	 *
	 * ## The italic, and the class that does not do the work
	 *
	 * ⚠️ `is-style-archive-intro` is registered for `core/paragraph` only
	 * (`styles/blocks/paragraph/archive-intro.json`), and `core/paragraph`'s
	 * `selectors.root` is a bare `p`, so the variation compiles to a
	 * `p.is-style-archive-intro` rule that can only ever match a `<p>` carrying
	 * the class itself. `core/term-description` renders a `<div>` wrapper with
	 * the description's own paragraphs inside it, so the class on this block is
	 * marking the block's role, not styling it — exactly the situation
	 * `patterns/destination-summary.php` documented for `core/post-content` and
	 * `patterns/template-taxonomy-accommodation-type.php` warned about.
	 *
	 * What actually renders the archive-intro look here is
	 * `assets/styles/core-term-description.css`, which the theme's
	 * `enqueue_custom_block_styles()` convention loads whenever this block
	 * appears (`core-<block>.css` → `core/<block>`). That file carries the
	 * italic, the size, the measure and the drop cap, and it is the one place
	 * to change them. The size is *not* set as a block attribute as well: two
	 * sources for one value is how they drift apart.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Brand Intro"},"align":"full","className":"is-style-tinted-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"about"} -->
	<section class="wp-block-group alignfull is-style-tinted-page-section" id="about">

		<!-- wp:columns {"verticalAlignment":"top","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|60"}}}} -->
		<div class="wp-block-columns alignwide are-vertically-aligned-top">

			<!-- wp:column {"verticalAlignment":"top","width":"66.66%"} -->
			<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:66.66%">

				<?php
				/*
				 * The story, collapsed behind a Read more.
				 *
				 * `patterns/destination-summary.php` gets this for free —
				 * `core/read-more` collapses `core/post-content` to its first
				 * block and expands it in place, no script required. That route
				 * is closed here twice over: `core/read-more` renders a link to
				 * a *post* permalink and there is no post in context on a
				 * taxonomy archive, and `core/term-description` is a single
				 * dynamic block that emits the whole description at once, so
				 * there is no first block to collapse to.
				 *
				 * So it is a clamp plus a toggle, in
				 * `assets/js/intro-collapse.js` and the same stylesheet as
				 * above. It is written as progressive enhancement in the strict
				 * sense: the markup below renders the full description with the
				 * button *hidden*, and the script reveals the button only after
				 * it has confirmed the text is actually overflowing. With
				 * JavaScript off, or on a short description, the reader gets the
				 * whole story and no dead control — which is also live's
				 * behaviour, since live has no Read more on this page at all.
				 *
				 * `sd-intro-collapse` is the hook both files key off. **Renaming
				 * it silently disables the collapse**, the same contract
				 * `sd-search-filters` carries on the rail below.
				 */
				?>
				<!-- wp:group {"metadata":{"name":"Brand Story"},"className":"sd-intro-collapse","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","justifyContent":"left"}} -->
				<div class="wp-block-group sd-intro-collapse">

					<!-- wp:term-description {"className":"is-style-archive-intro sd-intro-collapse__text"} /-->

					<!-- wp:buttons {"className":"sd-intro-collapse__actions"} -->
					<div class="wp-block-buttons sd-intro-collapse__actions">
						<!-- wp:button {"className":"is-style-outline sd-intro-collapse__toggle","fontSize":"200"} -->
						<div class="wp-block-button has-custom-font-size is-style-outline sd-intro-collapse__toggle has-200-font-size"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Read more', 'sd-theme-2026' ); ?></a></div>
						<!-- /wp:button -->
					</div>
					<!-- /wp:buttons -->

				</div>
				<!-- /wp:group -->

			</div>
			<!-- /wp:column -->

			<!-- wp:column {"verticalAlignment":"top","width":"33.33%"} -->
			<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:33.33%">

				<?php
				/*
				 * The logo.
				 *
				 * `sd/term-image` reads the term's `sd_thumbnail` meta — the key
				 * `sd-enhancements/modules/term-meta.php` registers for this
				 * taxonomy — and its `TermImage::term()` "falls back to the
				 * queried object so the block also works in a taxonomy template
				 * outside a `core/term-template` loop — a brand archive header,
				 * for instance". That is this. No context wiring is needed.
				 *
				 * ⚠️ `isLink: false`, against the block's own default of true.
				 * The block links to `get_term_link()`, which on this page is
				 * the page the reader is already on. A self-link is a dead
				 * control and, wrapped round the only image in the region, a
				 * confusing one for a screen reader.
				 *
				 * `large` rather than the block's default `medium`: `medium`
				 * caps at 300px and this column is wider than that on a desktop,
				 * so the logo would be upscaled. It renders nothing at all when
				 * a brand has no logo, which is why there is no placeholder
				 * frame authored around it.
				 */
				?>
				<!-- wp:group {"metadata":{"name":"Brand Logo"},"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center","verticalAlignment":"top"}} -->
				<div class="wp-block-group">
					<!-- wp:sd/term-image {"sizeSlug":"large","isLink":false} /-->
				</div>
				<!-- /wp:group -->

			</div>
			<!-- /wp:column -->

		</div>
		<!-- /wp:columns -->

	</section>
	<!-- /wp:group -->

	<!-- wp:group {"tagName":"section","metadata":{"name":"Results"},"align":"full","style":{"spacing":{"blockGap":"var:preset|spacing|50","padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"},"anchor":"accommodation"} -->
	<section class="wp-block-group alignfull" id="accommodation" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--80)">

		<?php
		/*
		 * The region tabs. See this file's header for why these are links and
		 * not `core/tabs`, and for the ⚠️ that they do not yet narrow the query.
		 *
		 * The block renders nothing when a brand has fewer than two regions —
		 * one region is not a choice — so brands like Ilios Travel (one
		 * property) simply do not get a strip, and the results below close the
		 * gap. `label` names the landmark for a screen reader; the block falls
		 * back to "Regions" if it is empty, but it is set here so the string
		 * belongs to this theme's text domain rather than the plugin's.
		 */
		?>
		<!-- wp:sd/brand-regions {"tagName":"nav","label":"<?php esc_attr_e( 'Regions', 'sd-theme-2026' ); ?>","align":"wide","className":"sd-brand-regions"} /-->

		<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
		<div class="wp-block-columns alignwide">

			<?php
			/*
			 * The filter rail — the same 25/75 split, the same `sd-search-filters`
			 * hook and the same `aside`-inside-the-column construction the type
			 * archive documented in full. In particular: `tagName` is not a
			 * `core/column` attribute, so the `<aside>` has to be a `core/group`
			 * inside the column or the editor throws a block-validation error
			 * the moment the template is opened.
			 */
			?>
			<!-- wp:column {"width":"25%"} -->
			<div class="wp-block-column" style="flex-basis:25%">

			<!-- wp:group {"tagName":"aside","metadata":{"name":"Filter Rail"},"className":"sd-search-filters","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"default"}} -->
			<aside class="wp-block-group sd-search-filters">

				<?php
				/*
				 * The keyword box **above** "Refine by", as on the
				 * accommodation-type page — searching re-queries the set, where
				 * everything below the heading narrows it. That file carries the
				 * reasoning in full.
				 *
				 * `hasHeader: false` keeps it out of the fold treatment: the
				 * script's section test requires a heading, so a headerless
				 * facet stays open and usable, and unplated, as live's keyword
				 * box is.
				 */
				?>
				<!-- wp:facetwp/facet {"facetName":"search_accommodation","facetLabel":"Search","facetType":"search","hasHeader":false} /-->

				<!-- wp:heading {"level":2,"fontSize":"400","anchor":"h-refine-by"} -->
				<h2 class="wp-block-heading has-400-font-size" id="h-refine-by"><?php esc_html_e( 'Refine by', 'sd-theme-2026' ); ?></h2>
				<!-- /wp:heading -->

				<?php
				/*
				 * Live's three facets, in live's order. Each carries
				 * `hasHeader: true` *and* an inner `core/heading`: the attribute
				 * makes the block print a label, the inner heading makes that
				 * label ours — without it the block echoes the admin-set
				 * `facetLabel` inside a hard-coded `h4`, which is neither
				 * translatable through this theme's text domain nor at the right
				 * level under the `h2` above.
				 *
				 * `hideOnEmpty: true` makes each block ask FacetWP whether the
				 * facet still has choices for the current result set, so a
				 * filter that can no longer narrow anything disappears instead
				 * of opening onto an empty panel. ⚠️ It is also why nothing in
				 * `assets/styles/facetwp-facets.css` may set `display` on
				 * `.facet-wrap`; that trap is documented at the head of that
				 * file.
				 */
				?>
				<!-- wp:facetwp/facet {"facetName":"destination_to_accommodation","facetLabel":"Destinations","facetType":"checkboxes","hasHeader":true,"hideOnEmpty":true} -->
					<!-- wp:heading {"level":3,"fontSize":"400"} -->
					<h3 class="wp-block-heading has-400-font-size"><?php esc_html_e( 'Destinations', 'sd-theme-2026' ); ?></h3>
					<!-- /wp:heading -->
				<!-- /wp:facetwp/facet -->

				<!-- wp:facetwp/facet {"facetName":"specials_to_accommodation","facetLabel":"Specials","facetType":"checkboxes","hasHeader":true,"hideOnEmpty":true} -->
					<!-- wp:heading {"level":3,"fontSize":"400"} -->
					<h3 class="wp-block-heading has-400-font-size"><?php esc_html_e( 'Specials', 'sd-theme-2026' ); ?></h3>
					<!-- /wp:heading -->
				<!-- /wp:facetwp/facet -->

				<?php
				/*
				 * Types. Present here where the type archive deliberately left
				 * it out: that page was already scoped to a type, so the facet's
				 * only possible choice was the one the visitor was standing on.
				 * A brand archive is scoped by brand, so this still narrows.
				 */
				?>
				<!-- wp:facetwp/facet {"facetName":"accommodation_type","facetLabel":"Types","facetType":"checkboxes","hasHeader":true,"hideOnEmpty":true} -->
					<!-- wp:heading {"level":3,"fontSize":"400"} -->
					<h3 class="wp-block-heading has-400-font-size"><?php esc_html_e( 'Types', 'sd-theme-2026' ); ?></h3>
					<!-- /wp:heading -->
				<!-- /wp:facetwp/facet -->

				<?php
				/*
				 * The selected-filter chips. With the panels shut, these are the
				 * only thing that shows what is currently applied, and each one
				 * removes its own filter. `user_selections` needs no FacetWP
				 * configuration — the block's render.php special-cases the name
				 * — and renders nothing while no filter is active.
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
				 * The toolbar — the result count on the leading edge, the sort
				 * control on the trailing one. The `h2` keeps "Results"
				 * translatable and gives the region the heading it needs;
				 * without one the card titles (`h3`) would read as children of
				 * the rail's "Refine by".
				 *
				 * ⚠️ `results_count` is a **Pager** facet with its "Pager type"
				 * set to *Result counts*, and `hideOnEmpty` is deliberately off
				 * — the block's front.js hides any `pager` facet when
				 * `total_pages < 2`, which on a count is exactly wrong. Full
				 * reasoning, and the `([total])` count-text setting, on the type
				 * archive.
				 */
				?>
				<!-- wp:group {"metadata":{"name":"Results Toolbar"},"className":"sd-search-toolbar","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center","justifyContent":"space-between"}} -->
				<div class="wp-block-group sd-search-toolbar">

					<!-- wp:group {"metadata":{"name":"Result Count"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"bottom"}} -->
					<div class="wp-block-group">

						<!-- wp:heading {"level":2,"fontSize":"400","anchor":"h-results"} -->
						<h2 class="wp-block-heading has-400-font-size" id="h-results"><?php esc_html_e( 'Results', 'sd-theme-2026' ); ?></h2>
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
				 * `enableFacetWP: true` is the whole integration — a registered
				 * boolean added to `core/query` by FacetWP Blocks (Beta), which
				 * on render puts a `facetwp-template` class on the inner
				 * `core/post-template` and adds `facetwp => true` to the query
				 * args. ⚠️ **Exactly one FacetWP-enabled block per page**; a
				 * second one silently takes the facets over.
				 *
				 * `inherit: true` scopes the loop to the queried brand term
				 * without a `taxQuery` this file would have to hardcode per
				 * brand, it is the mode FacetWP documents as needing `offset`
				 * set to 0 rather than left unset, and it is the condition under
				 * which `core/query-no-results` is injected rather than
				 * suppressed.
				 *
				 * The page size is `SD\Enhancements\Queries::bound_brand_archive()`,
				 * which caps the brand archive through `pre_get_posts` and is
				 * filterable on `sd_enh_brand_posts_per_page`. It is not set
				 * here: an inheriting query takes the archive's own page size,
				 * and writing a second number into the template is how the two
				 * drift apart.
				 *
				 * A single column, because the row *is* the layout.
				 */
				?>
				<!-- wp:query {"queryId":0,"query":{"pages":0,"offset":0,"postType":"accommodation","order":"asc","orderBy":"title","author":"","search":"","exclude":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[]},"enableFacetWP":true,"layout":{"type":"default"}} -->
				<div class="wp-block-query">

					<?php
					/*
					 * The horizontal row — `require` for the runtime reason
					 * above, and the same card the type archive uses. Its own
					 * file records the three measured deltas from live (29% vs
					 * 25% thumbnail, 26 vs 40 excerpt words, the card ground),
					 * all left for the card's own uplift rather than forked
					 * here. One card, two templates.
					 */
					?>
					<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
						<?php require __DIR__ . '/card-accommodation-list.php'; ?>
					<!-- /wp:post-template -->

					<?php
					/*
					 * Reachable because this loop inherits. FacetWP Blocks Beta
					 * hooks `render_block_core/query-no-results` and returns `''`
					 * unconditionally (LS-2529), which kills the fallback on
					 * every *non-inheriting* loop in this theme; on an
					 * inheriting one core prints the content inside
					 * `core/post-template` instead, so the message renders.
					 */
					?>
					<!-- wp:query-no-results -->
						<!-- wp:paragraph {"align":"center","textColor":"neutral-700","fontSize":"300"} -->
						<p class="has-text-align-center has-neutral-700-color has-text-color has-300-font-size"><?php esc_html_e( 'No properties from this brand match those filters. Try clearing a filter, or browse another region.', 'sd-theme-2026' ); ?></p>
						<!-- /wp:paragraph -->
					<!-- /wp:query-no-results -->

				</div>
				<!-- /wp:query -->

				<?php
				/*
				 * The pager — FacetWP's, not `core/query-pagination`, which
				 * cannot work on a FacetWP page: `add_facetwp_query_args()`
				 * reads `fwp_paged` and writes `page`/`paged` straight onto
				 * `$GLOBALS['wp_the_query']`, overriding core's `/page/N/` links
				 * after they are built.
				 *
				 * `hideOnEmpty: true` is right here, and the pager is the one
				 * facet type the block's empty test handles properly — it reads
				 * `FWP.settings.pager.total_pages` rather than counting choices.
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
	 * The closing band. Live ends on `#footer-choose-cta`, then Trustpilot,
	 * then the brands shelf; only the first is a section this template owns —
	 * and repeating the brands shelf under a single brand's results would send
	 * the reader back out of the page they just arrived on.
	 */
	require __DIR__ . '/why-choose-sd.php';
	?>

</main>
<!-- /wp:group -->
