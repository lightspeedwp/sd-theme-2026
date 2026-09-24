<?php
/**
 * Title: Template: Archive
 * Slug: sd-theme-2026/template-page-archive
 * Description: The all-archives page — the search results page's photographic banner carrying the archive title, the breadcrumb strip, then the result rows behind a Content Type filter rail and a FacetWP pager. Used by the archive and tag templates: tag, author and date archives, and any taxonomy without a template of its own.
 * Categories: hidden
 * Keywords: template, archive, tag, author, date, query, facetwp, facets, filters, content type
 * Block Types: core/query
 * Template Types: archive, tag
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 *
 * @package sd-theme-2026
 */

/*
 * The fallback archive, built as the search results page with three things
 * taken out (Zared, 2026-09-23). The two pages are one layout: an archive is a
 * result set the URL chose rather than a phrase, and it can span as many post
 * types as a search — `core/query-title` and the card both handle whichever
 * arrives. So everything here is `patterns/template-page-search.php`'s, and the
 * reasoning is recorded there rather than repeated: the banner and its
 * photograph, the 25/75 rail, the Content Type facet and why it must be OR,
 * the chips, `enableFacetWP` on an inherited loop, the `pager_` facet.
 *
 * ## What this page does not carry, and why
 *
 * - **No keyword box.** On the search page the field *is* the query. Here the
 *   query is the archive, and a site search from inside it would leave the
 *   archive rather than refine it — the header search already does that.
 * - **No results toolbar** — neither the "Results (n)" count nor the sort. An
 *   archive is ordered by date, which is what an archive means, and the two
 *   title orderings the `sort_` facet offers would override it. With the sort
 *   gone the count would sit alone on its row, so it goes with it.
 * - **No term description.** The previous version of this file printed
 *   `core/term-description` under a plain heading; the search layout has no
 *   slot for it, and none of dev's seven tags carries one (measured
 *   2026-09-23).
 *
 * ## The banner title is the archive's
 *
 * `core/query-title` in `archive` mode, in the slot the search page gives its
 * authored "Search" heading, with the same `h1` style and size. `showPrefix` is
 * **off**: core's prefixes ("Tag:", "Author:", "Month:") read as a system
 * message over a photograph for the same reason "Search results for …" does,
 * and the breadcrumb strip directly beneath already says what kind of archive
 * this is. ⚠️ `core/query-title` is dynamic — it takes a font-size preset and a
 * class safely, but nothing `var:custom|…`. → AGENTS.md, "dynamic block".
 *
 * The strapline is the search page's, as asked: the same banner, word for
 * word.
 */

?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Archive"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php
	/*
	 * The hero banner — the search page's exactly, photograph and strapline
	 * included. No padding of its own: `is-style-hero-banner` sets it, and an
	 * inline `style` would outrank the phone stack in `style.css`.
	 */
	?>
	<!-- wp:cover {"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/08/banner-search-tc-faq-1920x454.jpg","alt":"","dimRatio":100,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":360,"minHeightUnit":"px","contentPosition":"bottom center","align":"full","className":"is-style-hero-banner","tagName":"section","metadata":{"name":"Banner"},"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center is-style-hero-banner" style="min-height:360px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-100 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/08/banner-search-tc-faq-1920x454.jpg" data-object-fit="cover"/><div class="wp-block-cover__inner-container">

		<!-- wp:group {"metadata":{"name":"Banner Content"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:query-title {"type":"archive","showPrefix":false,"level":1,"className":"is-style-script-accent","fontSize":"800"} /-->

			<!-- wp:paragraph {"className":"is-style-subheading-large","style":{"typography":{"fontWeight":"var:custom|font-weight|medium"}},"fontFamily":"heading"} -->
			<p class="is-style-subheading-large has-heading-font-family" style="font-weight:var(--wp--custom--font-weight--medium)"><?php esc_html_e( 'Let us help you find what you’re searching for', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:group -->

	</div></section>
	<!-- /wp:cover -->

	<?php
	/*
	 * The breadcrumb bar. `require` rather than a nested `wp:pattern`
	 * reference, which is silently dropped on front-end render.
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
			 * The filter rail — the search page's, less its keyword box.
			 * `sd-search-filters` is the hook the stylesheet, the fold script and
			 * the flyout script all key off; **renaming it silently disables
			 * all three.**
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

				<!-- wp:heading {"level":2,"fontSize":"400","anchor":"h-refine-by"} -->
				<h2 class="wp-block-heading has-400-font-size" id="h-refine-by"><?php esc_html_e( 'Refine by', 'sd-theme-2026' ); ?></h2>
				<!-- /wp:heading -->

				<?php
				/*
				 * The Content Type facet, as on the search page. `hideOnEmpty`
				 * drops it when the archive has no choices to offer.
				 */
				?>
				<!-- wp:facetwp/facet {"facetName":"post_type","facetLabel":"Content Type","facetType":"checkboxes","hasHeader":true,"hideOnEmpty":true} -->
					<!-- wp:heading {"level":3,"fontSize":"400","anchor":"h-content-type"} -->
					<h3 class="wp-block-heading has-400-font-size" id="h-content-type"><?php esc_html_e( 'Content Type', 'sd-theme-2026' ); ?></h3>
					<!-- /wp:heading -->
				<!-- /wp:facetwp/facet -->

				<!-- wp:facetwp/facet {"facetName":"user_selections","facetLabel":"Selected filters","facetType":"selections","hasHeader":false,"className":"sd-search-selections"} /-->

			</aside>
			<!-- /wp:group -->

			</div>
			<!-- /wp:column -->

			<!-- wp:column {"width":"75%","style":{"spacing":{"blockGap":"var:preset|spacing|40"}}} -->
			<div class="wp-block-column" style="flex-basis:75%">

				<?php
				/*
				 * The loop. `inherit: true` is the archive's own main query — its
				 * term, author or date, its post types and its date ordering — and
				 * `enableFacetWP` hands it to the facets. ⚠️ Exactly one
				 * FacetWP-enabled block per page. → template-page-search.php
				 */
				?>
				<!-- wp:query {"queryId":0,"query":{"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[],"perPage":12,"excludeCurrent":null},"enableFacetWP":true,"layout":{"type":"default"}} -->
				<div class="wp-block-query">

					<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
						<?php require __DIR__ . '/card-search-result.php'; ?>
					<!-- /wp:post-template -->

					<!-- wp:query-no-results -->
						<!-- wp:paragraph {"align":"center","textColor":"neutral-700","fontSize":"300"} -->
						<p class="has-text-align-center has-neutral-700-color has-text-color has-300-font-size"><?php esc_html_e( 'Nothing to show here yet. Try clearing the content type filter.', 'sd-theme-2026' ); ?></p>
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
