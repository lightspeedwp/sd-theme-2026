<?php
/**
 * Title: Template: Tours Archive
 * Slug: sd-theme-2026/template-archive-tour
 * Description: The tours landing page — the photographic banner, the warm intro band pairing the archive description with the safari expert panel, and the grid of travel-style tiles beneath it.
 * Categories: hidden
 * Keywords: tours, safaris, archive, landing, travel style, tour operator, grid, banner
 * Block Types: core/terms-query
 * Template Types: archive
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 *
 * @package sd-theme-2026
 */

/*
 * Ported from https://www.southerndestinations.com/tours/, measured 2026-08-28.
 * The page is the destinations archive's twin — same banner device, same intro
 * band, same tile grid — so this file is deliberately
 * `template-archive-destination.php` with one section swapped, and the reasoning
 * that applies to both is recorded there rather than repeated here:
 *
 *   - why the banner is composed here and not `hero-page-banner`
 *   - why `dimRatio: 100` is correct against `is-style-hero-banner`
 *   - why the banner content group is flow layout and not constrained
 *   - why media is addressed by its dev URL
 *   - why the breadcrumb bar is not built (it is `sd-enhancements` work)
 *   - why only one copy of the archive description is rendered
 *
 * ## The one real difference: the grid lists terms, not tours
 *
 * Live's tours archive does not list tours. It lists **travel styles** — ten
 * tiles reading "Safari Honeymoons", "Family Safaris", "Gorilla Trekking" and
 * so on, each clicking through to that term's own archive. The individual tours
 * live one level down. So where the destinations archive runs a `core/query`
 * over the `destination` post type, this runs a `core/terms-query` over the
 * `travel-style` taxonomy.
 *
 * ## Values taken from Tour Operator's own settings
 *
 * The banner image, the title and the tagline below are the `tour` block of
 * `_lsx-to_settings` — `banner` (attachment 58809), `title` and `tagline` — read
 * on dev 2026-08-28, so they are the same three values live renders and not a
 * transcription of the rendered page. The description is that block's
 * `description`, carried verbatim; it is byte-for-byte the destinations one,
 * which is live's doing and not a copy-paste here.
 *
 * They are authored rather than bound for the reason set out on the
 * destinations pattern: there is no block for a Tour Operator archive setting in
 * TO 2.2 or anywhere else. All four become candidates for bindings the moment
 * `sd-enhancements` exposes those settings as a source.
 *
 * The tagline is stored upper case — `TRIP IDEAS TO INSPIRE YOUR OWN!` — and is
 * set in sentence case here, matching the destinations banner this page is
 * being brought into line with and the casing authored on dev. It is the one
 * deviation from the stored value on the page; flip the string if the shout is
 * wanted back.
 */
?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Tours Archive"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php
	/*
	 * The banner. → template-archive-destination.php for the full reasoning on
	 * `dimRatio`, the decorative `alt` and the flow-layout content group.
	 */
	?>
	<!-- wp:cover {"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/11/header-tours-big5.jpg","alt":"","dimRatio":100,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":454,"minHeightUnit":"px","contentPosition":"bottom center","align":"full","className":"is-style-hero-banner","tagName":"section","metadata":{"name":"Banner"},"style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center is-style-hero-banner" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);min-height:454px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-100 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/11/header-tours-big5.jpg" data-object-fit="cover"/><div class="wp-block-cover__inner-container">

		<!-- wp:group {"metadata":{"name":"Banner Content"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:heading {"level":1,"className":"is-style-script-accent","fontSize":"800"} -->
			<h1 class="wp-block-heading is-style-script-accent has-800-font-size"><?php esc_html_e( 'Tours & Safaris', 'sd-theme-2026' ); ?></h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"className":"is-style-subheading-large","style":{"typography":{"fontWeight":"var:custom|font-weight|semi-bold"}},"fontFamily":"heading"} -->
			<p class="is-style-subheading-large has-heading-font-family" style="font-weight:var(--wp--custom--font-weight--semi-bold)"><?php esc_html_e( 'Trip ideas to inspire your own!', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:group -->

	</div></section>
	<!-- /wp:cover -->

	<?php
	/*
	 * The intro band. Identical in structure to the destinations archive's —
	 * live's `col-md-7` / `col-md-5`, the columns aligned top with the expert
	 * column re-centring itself, on the `neutral-200` ground that
	 * `is-style-tinted-page-section` owns.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Archive Intro"},"align":"full","className":"is-style-tinted-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-group alignfull is-style-tinted-page-section">

		<!-- wp:columns {"verticalAlignment":"top","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
		<div class="wp-block-columns alignwide are-vertically-aligned-top">

			<!-- wp:column {"verticalAlignment":"top","width":"58.33%"} -->
			<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:58.33%">
				<!-- wp:paragraph {"className":"is-style-archive-intro"} -->
				<p class="is-style-archive-intro"><?php esc_html_e( 'From the thick bushveld of the Kruger in South Africa to the grassy plains of the Masai Mara in East Africa and beyond, Africa is a place of startling contrasts and stupendous beauty. Let us share our favourite destinations and travel insights with you as you prepare to explore and experience this amazing continent.', 'sd-theme-2026' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"verticalAlignment":"center","width":"41.67%"} -->
			<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:41.67%">
				<?php
				/*
				 * `require`, not a nested `wp:pattern` reference: a pattern
				 * referencing another pattern resolves under WP-CLI and is
				 * silently dropped on front-end render.
				 * → .claude/skills/wp-pattern-runtime-pitfalls
				 *
				 * On an archive there is no post in context, so
				 * `sd/safari-expert` falls through term → post → pool and lands
				 * on a random member of the expert pool, which is what live does
				 * here too.
				 */
				require __DIR__ . '/safari-expert.php';
				?>
			</div>
			<!-- /wp:column -->

		</div>
		<!-- /wp:columns -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The grid — travel styles, not tours.
	 *
	 * ## Why `core/terms-query` and not a Query Loop
	 *
	 * There is no post type to query. The tiles are `travel-style` terms, and
	 * core has queried taxonomies directly since 6.9 (this install is on 7.1).
	 * The card inside is the term twin of the destinations tile and carries the
	 * same style, so the two archives stay one design.
	 *
	 * ## `sd-featured-terms-query`, and the eleventh tile
	 *
	 * Dev holds twelve `travel-style` terms. `hideEmpty` drops "Website", which
	 * carries no tours. That leaves eleven — one more than the ten live shows,
	 * and the extra one is "Top 10 Safari Tours": the curation term the tours
	 * mega menu is built from, whose `thumbnail` is attachment 51667, *the same
	 * photograph as Luxury Big Five Safaris*. Rendering it puts two identical
	 * images in one grid.
	 *
	 * The ten live shows are exactly the terms carrying the `featured` term meta
	 * that `SD\Enhancements\TermMeta` registers against `travel-style` and
	 * `accommodation-type`, labelled "Featured" and described "Feature this term
	 * on the post type archive". Nothing consumed it until now; this is the
	 * consumer that description was written for.
	 *
	 * `core/terms-query` has no meta filter and no equivalent of
	 * `query_loop_block_query_vars`, so the constraint is applied plugin-side:
	 * `SD\Enhancements\Queries::arm_featured_terms()` matches this class on the
	 * query and brackets a `get_terms_args` filter around the term template's
	 * render. The marker-class convention is the one `sd-mega-menu-tours-query`
	 * and `sd-safari-gurus-query` already use, and for the same reason — the
	 * selection is declared once, in the plugin, so no term IDs are baked into a
	 * template that has to work on local, dev and live alike.
	 *
	 * The class sits on the query rather than on the term template, where those
	 * two sit, because Tour Operator's term-image renderer calls
	 * `get_block_wrapper_attributes()` from outside a block render and so copies
	 * the enclosing block's classes onto every tile's `<figure>`. That method's
	 * docblock carries the measurement.
	 *
	 * If nothing is flagged featured the filter stands down and every term
	 * renders, so this grid degrades to eleven visible tiles rather than to
	 * nothing.
	 *
	 * ## Three columns, where live runs two
	 *
	 * A deliberate departure, decided 2026-08-28: the rebuild's archives share
	 * one grid, and the destinations archive is three-up. Ten tiles at three
	 * columns leave a row of one; that is the trade accepted for one tile shape
	 * and one column count across every Tour Operator archive.
	 *
	 * `perPage: 12` is a ceiling above the twelve terms that exist, not a page
	 * size — there is no term pagination block, and live pages nothing here
	 * either (`disable_archive_pagination` is on in the `tour` settings).
	 * Alphabetical by name, as the destinations grid is.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Travel Styles"},"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)">

		<!-- wp:terms-query {"termQuery":{"perPage":12,"taxonomy":"travel-style","order":"asc","orderBy":"name","include":[],"hideEmpty":true,"showNested":false,"inherit":false},"align":"wide","className":"sd-featured-terms-query","layout":{"type":"default"}} -->
		<div class="wp-block-terms-query alignwide sd-featured-terms-query">

			<!-- wp:term-template {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":null}} -->
				<?php require __DIR__ . '/card-media-overlay-term.php'; ?>
			<!-- /wp:term-template -->

		</div>
		<!-- /wp:terms-query -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The two closing bands live runs beneath this archive — the photographic
	 * "Why choose Southern Destinations" and the enquiry band. Both are shared
	 * section patterns; `require` for the nested-pattern reason above.
	 *
	 * The destinations archive carries both on dev but its pattern file does
	 * not yet — that file is behind its database copy, and reconciling it is its
	 * own job. → .claude/skills/wp-db-override-reconciliation
	 */
	require __DIR__ . '/why-choose-sd.php';
	require __DIR__ . '/cta-not-sure-where-to-go.php';
	?>

</main>
<!-- /wp:group -->
