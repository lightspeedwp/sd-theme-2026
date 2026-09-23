<?php
/**
 * Title: Template: Accommodation Brand Taxonomy
 * Slug: sd-theme-2026/template-taxonomy-accommodation-brand
 * Description: The single-brand archive — the photographic banner carrying the brand name, the breadcrumb strip, a two-column intro of the brand's story beside its logo, then a FacetWP filter rail beside the brand's accommodation as horizontal rows, with the region strip heading the results column.
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
 * Finalised against https://www.southerndestinations.com/brand/wilderness-safaris/
 * on 2026-09-16. That pass took the page in the opposite direction on one
 * point: **back toward live on the region strip, and further from the type
 * archive on the chrome.** The strip moved out of the full-width slot above the
 * columns and into the head of the results column, and was restyled to live's
 * tinted bar of flush segments (`assets/styles/sd-brand-regions.css` carries
 * the measurements); the keyword box, the result count and the sort select all
 * came out. Each change is noted at its site.
 *
 * The sibling template `patterns/template-taxonomy-accommodation-type.php`
 * reasoned out the whole facet apparatus — why the count, sort and pager are
 * facet *blocks* rather than shortcodes, why `core/query-pagination` cannot be
 * used on a FacetWP page, why each facet carries an inner `core/heading`, and
 * the three settings that live in `wp_options` rather than in theme code. All
 * of that applies here unchanged and is not restated; read that file first.
 *
 * ⚠️ **The two templates are no longer chrome-for-chrome the same.** The type
 * archive keeps its keyword box, its result count and its sort control; this
 * one has none of the three. Do not "restore consistency" by putting them back
 * — the divergence is the decision, not drift. The facets themselves are still
 * configured in `wp_options`, so returning any of them is a markup change here
 * and nothing else.
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
 * **The tabs narrow the results, and the plugin is what does it.** Until
 * 2026-09-16 they did not: `BrandEndpoints` resolved the `endpoint` query var
 * and `BrandRegions::current_endpoint()` read it to mark the active tab, but
 * nothing filtered the accommodation query, so every tab returned the same rows.
 * `SD\Enhancements\Queries::scope_brand_archive_to_region()` now sets `post__in`
 * on the main query from the region's connected accommodation. Nothing in this
 * template expresses that and nothing here should — the `core/query` below
 * inherits the main query, which is the whole mechanism.
 *
 * ## The facet set
 *
 * Live registers four controls on this page — a keyword box, Destination,
 * Specials and Types. **Three are carried; the keyword box is not**, dropped
 * on 2026-09-16 with the result count and the sort select. **Types belongs
 * here where it did not on the type archive**: this archive is scoped by
 * *brand*, so `accommodation_type` still has real choices to offer, where on
 * `taxonomy-accommodation-type` its only possible value was the term the
 * visitor was already standing on.
 *
 * Destination overlaps the region tabs — both narrow by place, one by URL and
 * one by facet. They **compose**, and no longer by accident: the region scope is
 * set on `pre_get_posts` at priority 10 and FacetWP reads the main query's vars
 * at 999, so the facet narrows *within* the active region and its counts are
 * counted there. Kept on that basis rather than removed.
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
	<!-- wp:cover {"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/08/banner-brands-1920x454.png","alt":"","dimRatio":100,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":400,"minHeightUnit":"px","contentPosition":"bottom center","align":"full","className":"is-style-hero-banner","tagName":"section","metadata":{"name":"Banner"},"style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center is-style-hero-banner" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);min-height:400px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-100 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/08/banner-brands-1920x454.png" data-object-fit="cover"/><div class="wp-block-cover__inner-container">

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
				 *
				 * The group's `blockGap` is `spacing|20`, one step in from the
				 * `30` it carried, so the toggle reads as the tail of the story
				 * rather than as a separate row under it. Zared's call,
				 * 2026-09-16. It is the gap between the text and the toggle and
				 * nothing else — the group holds exactly those two children.
				 */
				?>
				<!-- wp:group {"metadata":{"name":"Brand Story"},"className":"sd-intro-collapse","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","justifyContent":"left"}} -->
				<div class="wp-block-group sd-intro-collapse">

					<!-- wp:term-description {"className":"is-style-archive-intro sd-intro-collapse__text"} /-->

					<?php
					/*
					 * ⚠️ **A `core/button` that is dressed as a link, and it has
					 * to stay a button.** `assets/js/intro-collapse.js` binds to
					 * `.sd-intro-collapse__toggle a`, sets `aria-expanded` and
					 * `aria-controls` on it and swaps its label — so the element
					 * is a real control and `core/read-more` cannot replace it
					 * (that block renders a link to a post permalink, and there
					 * is no post in context on a taxonomy archive; the file
					 * header carries the full reasoning).
					 *
					 * What changed on 2026-09-16 is only its appearance: it now
					 * reads as the plain `core/read-more` link every Tour
					 * Operator single carries — `patterns/destination-summary.php`
					 * line for line — rather than as an outlined button. Zared's
					 * call. `is-style-outline` is therefore **gone**, and no
					 * other button style replaces it: the flat look is written
					 * in `assets/styles/core-term-description.css`, scoped to
					 * `.sd-intro-collapse__toggle`, because core's own
					 * `.wp-block-button__link` defaults would otherwise fill it
					 * brand-500. `fontSize` is `300` to match the read-more it
					 * is imitating, up from `200`.
					 *
					 * The label is **"Read more..."**, with the ellipsis
					 * `destination-summary.php` uses, and it is set in italics
					 * by `assets/styles/core-term-description.css`. Zared's
					 * call, 2026-09-16 — this supersedes the note that stood
					 * here, which kept the label plain for symmetry with the
					 * toggle's other half.
					 *
					 * `inc/intro-collapse.php`'s "Read less" stays plain. The
					 * ellipsis says the text continues past the cut, which is
					 * true of the collapsed state and not of the expanded one;
					 * the two labels are a pair in function, not in shape.
					 */
					?>
					<!-- wp:buttons {"className":"sd-intro-collapse__actions"} -->
					<div class="wp-block-buttons sd-intro-collapse__actions">
						<!-- wp:button {"className":"sd-intro-collapse__toggle","fontSize":"300"} -->
						<div class="wp-block-button has-custom-font-size sd-intro-collapse__toggle has-300-font-size"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Read more...', 'sd-theme-2026' ); ?></a></div>
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
				 * ⚠️ **No keyword box.** The accommodation-type page opens its
				 * rail with a `search_accommodation` facet above "Refine by";
				 * this one deliberately does not. Zared's call, 2026-09-16,
				 * with the result count and the sort control below — a brand
				 * archive is already a narrow set (Wilderness Safaris, the
				 * largest, is a few dozen properties across six countries), and
				 * three chrome controls over a list that short read as search
				 * furniture rather than as a brand's page. The regions strip and
				 * the three checkbox facets are the whole navigation.
				 *
				 * The FacetWP facet itself is untouched and still configured —
				 * only this template stops rendering it, so the type archive
				 * keeps its keyword box and nothing needs reconfiguring to put
				 * this one back.
				 */
				?>
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
				 * The region strip, at the head of the results column.
				 *
				 * It ran full-width above the two columns until 2026-09-16,
				 * where it read as a second navigation belonging to the page
				 * rather than as a control over the list. Zared's call: it
				 * belongs to the results, so it sits in the results column,
				 * directly over the first card and in line with the rail's
				 * "Refine by" — the same relationship live has, where the strip
				 * sits immediately above `.lsx-to-archive-items`.
				 *
				 * `align` is therefore **gone**: `alignwide` on a block inside a
				 * `core/column` is meaningless (the column is the containing
				 * block, and it is not a constrained layout), and leaving it on
				 * only invited the next reader to think the strip still spanned
				 * something.
				 *
				 * See this file's header for why these are links and not
				 * `core/tabs`, and for how
				 * `SD\Enhancements\Queries::scope_brand_archive_to_region()` narrows
				 * the main query via `post__in`.
				 *
				 * The block renders nothing when a brand has fewer than two
				 * regions — one region is not a choice — so brands like Ilios
				 * Travel (one property) simply do not get a strip, and the cards
				 * close the gap. `label` names the landmark for a screen reader;
				 * the block falls back to "Regions" if it is empty, but it is
				 * set here so the string belongs to this theme's text domain
				 * rather than the plugin's.
				 */
				?>
				<!-- wp:sd/brand-regions {"tagName":"nav","label":"<?php esc_attr_e( 'Regions', 'sd-theme-2026' ); ?>","className":"sd-brand-regions"} /-->

				<?php
				/*
				 * The results heading, and nothing else.
				 *
				 * ⚠️ **The toolbar is gone but its `h2` is not, and must not
				 * be.** What stood here was a flex row carrying "Results", a
				 * `results_count` pager facet and the `sort_` select; all three
				 * came out on 2026-09-16 with the keyword box above — Zared's
				 * call, reasoning on the rail.
				 *
				 * The heading stays because the *cards* are `h3`. With no `h2`
				 * between them and the rail's own "Refine by", every card title
				 * on the page would be announced as a child of the filter rail —
				 * a screen-reader reading of the document outline that says the
				 * results belong to the filters.
				 *
				 * `screen-reader-text` is core's own utility and the theme adds
				 * no rule for it. Verified on this install rather than assumed:
				 * `wp_should_load_separate_core_block_assets()` is **true**
				 * here, so the monolithic `block-library/style.css` never loads
				 * and the class comes from `block-library/common.css:222`, which
				 * is what the `wp-block-library` handle resolves to and is
				 * enqueued on every front-end request. Both files define it, so
				 * the class survives that setting being flipped either way.
				 *
				 * The `id` is kept: `#h-results` is a real anchor and removing
				 * it would break any link already pointing at it.
				 *
				 * The FacetWP facets themselves are untouched — `results_count`
				 * is still the Pager facet set to *Result counts*, and `sort_`
				 * is still configured. Only this template stops rendering them.
				 */
				?>
				<!-- wp:heading {"level":2,"className":"screen-reader-text","anchor":"h-results"} -->
				<h2 class="wp-block-heading screen-reader-text" id="h-results"><?php esc_html_e( 'Results', 'sd-theme-2026' ); ?></h2>
				<!-- /wp:heading -->

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
