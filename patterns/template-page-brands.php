<?php
/**
 * Title: Template: Brands Landing
 * Slug: sd-theme-2026/template-page-brands
 * Description: The Brands landing page — the photographic banner, the breadcrumb strip, the intro standfirst, and the grid of accommodation-brand logos, each clicking through to its own brand archive.
 * Categories: hidden
 * Keywords: brands, logos, partners, operators, accommodation, landing, terms
 * Block Types: core/query
 * Template Types: page
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 *
 * @package sd-theme-2026
 */

/*
 * Ported from https://www.southerndestinations.com/brands/, read 2026-09-10.
 * It is a *page* (dev 52337, slug `brands`), not a taxonomy archive —
 * `accommodation-brand` is a taxonomy and WordPress gives taxonomies no
 * landing route of their own — so this is bound through `templates/page-brands.html`,
 * which core resolves by page slug. That keeps the template in theme files
 * rather than in a DB-stored page layout. → wp-db-override-reconciliation
 *
 * ## The grid is a Terms Query, and there is no list of brands here
 *
 * `patterns/homepage-brands.php` already established this route for the
 * homepage shelf and its reasoning applies unchanged: `core/terms-query` +
 * `core/term-template` (core since 6.9; this install is on 7.1) query the
 * taxonomy directly, so all twenty-one brands come from the taxonomy and there
 * is nothing here to keep in sync when one is added.
 *
 * ⚠️ **The logo block differs from the homepage's, deliberately.** The homepage
 * uses `core/post-featured-image` and leans on Tour Operator's filter, which
 * reads a term's `thumbnail` meta and needs
 * `SD\Enhancements\Compat::declare_term_context_on_featured_image()` to see
 * `termId` at all. This page uses `sd/term-image`, which is the block
 * sd-enhancements built for exactly this job — its own render.php says so:
 * "it replaces the `<a href="{term_url}"><img …></a>` that `sd_brands_archive()`
 * printed, while the grid around it stays a theme pattern." This file is that
 * grid. The block reads `sd_thumbnail` (the key `modules/term-meta.php`
 * registers for this taxonomy), links to `get_term_link()`, sets the term name
 * as `alt`, and renders *nothing* when a brand has no logo — a gap the grid
 * closes rather than an empty frame.
 *
 * The two blocks therefore read two different meta keys. That is a real
 * inconsistency between this page and the homepage shelf, not a subtlety of
 * this file: if the homepage logos and these logos ever disagree, that is why.
 * → flagged on LS-2033, not resolved here, because changing the homepage band
 * is not this line item.
 *
 * ## Four across, not five
 *
 * The homepage shelf runs five-up because it is a carousel of a fixed height.
 * This is a static grid of twenty-one logos at the content measure, where five
 * across leaves the wordmarks too small to read; four is the widest that keeps
 * them legible. Live's own grid is four across at 1140. Core's grid layout
 * handles the reflow, so no breakpoint is authored.
 *
 * ## The copy comes from the page, not from here
 *
 * Live's standfirst is the page's own content, and it is content — migrated,
 * not rewritten. `core/post-content` renders it so it stays editable in the
 * page where an editor expects to find it, rather than being frozen into a
 * translatable string in the theme. That is the opposite of what
 * `patterns/template-archive-tour.php` does, and correctly so: an archive has
 * no post to draw copy from and must carry its own, a page does not.
 */

?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Brands Landing"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php
	/*
	 * The banner. Live's own `banner-brands-1920x454.png` — attachment 51693 on
	 * dev, already in the media library, so it is addressed by its dev URL
	 * rather than ported into the theme.
	 * → template-archive-destination.php for why `dimRatio: 100` is correct
	 * against `is-style-hero-banner`, why the content group is flow layout, and
	 * why the media is addressed by its dev URL.
	 *
	 * 454px to match the accommodation archive, so the brands page reads as a
	 * sibling of it. The tagline is live's own — the single word "Accommodation"
	 * above the title, which is the section this page belongs to.
	 */
	?>
	<!-- wp:cover {"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/08/banner-brands-1920x454.png","alt":"","dimRatio":100,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":454,"minHeightUnit":"px","contentPosition":"bottom center","align":"full","className":"is-style-hero-banner","tagName":"section","metadata":{"name":"Banner"},"style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center is-style-hero-banner" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);min-height:454px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-100 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/08/banner-brands-1920x454.png" data-object-fit="cover"/><div class="wp-block-cover__inner-container">

		<!-- wp:group {"metadata":{"name":"Banner Content"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:post-title {"level":1,"className":"is-style-script-accent","fontSize":"800"} /-->

			<!-- wp:paragraph {"className":"is-style-subheading-large","style":{"typography":{"fontWeight":"var:custom|font-weight|medium"}},"fontFamily":"heading"} -->
			<p class="is-style-subheading-large has-heading-font-family" style="font-weight:var(--wp--custom--font-weight--medium)"><?php esc_html_e( 'Accommodation', 'sd-theme-2026' ); ?></p>
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

	<?php
	/*
	 * The intro band. `is-style-tinted-page-section` is the warm ground the
	 * other archive intros sit on, and the italic standfirst comes from the
	 * group's own inline `font-style`, not from the class on `core/post-content`.
	 *
	 * ⚠️ `is-style-archive-intro` is registered for `core/paragraph` only
	 * (`styles/blocks/paragraph/archive-intro.json`), and `core/paragraph`'s
	 * `selectors.root` is a bare `p`, so the variation compiles to
	 * `p.is-style-archive-intro` and can never match `core/post-content`'s
	 * wrapper. The class is kept because it marks the block's role and because
	 * `patterns/destination-summary.php` — which reasoned this out in full —
	 * carries it for the same reason; the italic actually rendered comes from
	 * the group. Worth knowing if either is ever touched in isolation.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Brands Intro"},"align":"full","className":"is-style-tinted-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-group alignfull is-style-tinted-page-section">

		<!-- wp:group {"align":"wide","style":{"typography":{"fontStyle":"italic","fontWeight":"var:custom|font-weight|regular"},"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group alignwide" style="font-style:italic;font-weight:var(--wp--custom--font-weight--regular)">

			<!-- wp:post-content {"className":"is-style-archive-intro","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} /-->

		</div>
		<!-- /wp:group -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The logo grid.
	 *
	 * `hideEmpty: true` keeps a brand with no accommodation off the page — it
	 * would click through to an archive with nothing on it. `perPage: 21` is
	 * the count dev carries today and the same ceiling the homepage shelf uses;
	 * it is a page size, not a list, so a twenty-second brand pages rather than
	 * disappears.
	 *
	 * No pagination block: twenty-one logos fit on one page and a pager under a
	 * single page of results is noise. If the taxonomy grows past the page size
	 * that becomes wrong, which is why the ceiling is stated here rather than
	 * left implicit.
	 *
	 * Each cell is a flex group so logos of different aspect ratios sit centred
	 * on a common baseline instead of hugging the top of their cell.
	 * `scale: contain` keeps a wide wordmark and a square crest the same visual
	 * weight; `height: 100px` is the shelf height the homepage band already uses.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Brands Grid"},"align":"full","style":{"spacing":{"blockGap":"var:preset|spacing|40","padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"},"anchor":"brands"} -->
	<section class="wp-block-group alignfull" id="brands" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--80)">

		<!-- wp:heading {"textAlign":"center","className":"is-style-section-title","anchor":"h-our-preferred-operators"} -->
		<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-our-preferred-operators"><?php esc_html_e( 'Our preferred safari lodge operators', 'sd-theme-2026' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:terms-query {"termQuery":{"perPage":21,"taxonomy":"accommodation-brand","order":"asc","orderBy":"name","include":[],"hideEmpty":true,"showNested":false,"inherit":false},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
		<div class="wp-block-terms-query alignwide">

			<!-- wp:term-template {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":4}} -->
				<!-- wp:group {"metadata":{"name":"Brand Logo"},"style":{"spacing":{"blockGap":"0","padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center","verticalAlignment":"center"}} -->
				<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
					<!-- wp:sd/term-image {"sizeSlug":"medium","isLink":true} /-->
				</div>
				<!-- /wp:group -->
			<!-- /wp:term-template -->

		</div>
		<!-- /wp:terms-query -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The closing band. Live ends this page on `#footer-choose-cta` and the
	 * Trustpilot widget; only the first is a section this template owns.
	 * `require` for the runtime reason above.
	 */
	require __DIR__ . '/why-choose-sd.php';
	?>

</main>
<!-- /wp:group -->
