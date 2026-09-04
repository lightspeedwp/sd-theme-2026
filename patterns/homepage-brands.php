<?php
/**
 * Title: Homepage — Africa's Finest Brands
 * Slug: sd-theme-2026/homepage-brands
 * Description: The "We only work with Africa's finest" band — a Slick carousel of the accommodation brand logos, five at a time. Driven by a Terms Query, so the shelf follows the taxonomy rather than a hand-written list.
 * Categories: sd-theme-2026/features, sd-theme-2026/tour-operator
 * Keywords: brands, logos, partners, accommodation, carousel, slider, terms
 * Viewport Width: 1400
 * Template Types: front-page
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * Why this needs no new block, and no hard-coded list of brands.
 *
 * Live builds this band with Tour Operator's own taxonomy block — a Slick
 * carousel of `accommodation-brand` terms, five slides at a time. TO 2.2 no
 * longer ships that block, so the question was what replaces it. Two pieces
 * already exist and cover the shelf:
 *
 * 1. **`core/terms-query` + `core/term-template`** (core since 6.9; this install
 *    is on 7.1) query the taxonomy directly, so all 21 brands come from the
 *    taxonomy and there is no list here to keep in sync.
 *
 * 2. **Slick is already applied.** Tour Operator's "Enable Slider" checkbox on
 *    the query blocks (src/js/blocks/slider-query.js:174-190) sets
 *    `hasCustomClass` and adds `lsx-to-slider`; custom.js:449-480 then
 *    initialises Slick on the descendant `.wp-block-term-template` — it matches
 *    term templates as well as post templates. Both attributes are written out
 *    here so the checkbox reads as ticked when the pattern is opened.
 *
 * `slidesToShow` is read off a `columns-N` class on the template element
 * (custom.js:458-464). `core/post-template` emits that class from its own
 * `layout.columnCount` (wp-includes/blocks/post-template.php:95) but
 * `core/term-template` does not, so `columns-5` is set explicitly to get live's
 * five-up shelf. The grid layout beside it is the fallback with JavaScript off,
 * or with Tour Operator deactivated.
 *
 * ## The logo comes from `core/post-featured-image`
 *
 * Tour Operator filters `render_block_core/post-featured-image` and, when the
 * block has a `termId` in context, swaps in the term's `thumbnail` meta, wraps
 * it in `get_term_link()` and sets the term name as `alt` —
 * `lsx\frontend\Taxonomy_Images`, class-taxonomy-images.php:46,60-64,229-240.
 * That gives logos only, each clicking through to its own brand archive, with
 * an accessible name, and with no list of brands in this file. `thumbnail` is
 * the same meta key live reads, so these are the same logo files.
 *
 * ## The hover zoom is on the term template, not on the image
 *
 * `is-style-image-hover-zoom` looks misplaced on `core/term-template` and is the
 * only place it works. Setting it on `core/post-featured-image` is what an
 * author would try — measured on dev 2026-09-04, it registers, it emits CSS,
 * and it does nothing: Tour Operator builds that `<figure>` with
 * `get_block_wrapper_attributes()` from inside a `render_block` filter, which
 * outside a block render of its own returns the *enclosing* block's classes, so
 * the figure came back as `<figure class="columns-5 wp-block-term-template">`
 * with no `wp-block-post-featured-image` and no `is-style-*` on it. The
 * generated selector is `.wp-block-post-featured-image.is-style-image-hover-zoom--N`,
 * so nothing matched. The class on the term template *does* land on that figure,
 * because the term template is the block whose attributes leak into it — the same
 * leak `assets/styles/core-post-featured-image.css` and
 * `SD\Enhancements\Queries::FEATURED_TERMS_CLASS` are written around, used here
 * rather than worked around.
 *
 * Two consequences worth knowing. The variation is declared for
 * `core/term-template` in `styles/blocks/media/image-hover-zoom.json` so the
 * class is real rather than a hand-written helper, which also means its
 * `overflow: hidden` lands on the `<ul>` Slick initialises on — harmless, and
 * what a slider track wants anyway. And there is now **one** copy of this CSS in
 * the page instead of the twenty-two the per-image class produced, one per tile.
 *
 * That filter could not fire as TO ships it. A block receives only the context
 * keys its own type declares — `WP_Block::__construct()` intersects the
 * available context with `$block_type->uses_context`, class-wp-block.php:163-168
 * — and `core/post-featured-image` declares just `postId`, `postType` and
 * `queryId`, while `core/term-template` publishes `termId` and `taxonomy`
 * (term-template.php:88-90). TO reads a key it never declared, so its whole
 * term branch was unreachable.
 *
 * `SD\Enhancements\Compat::declare_term_context_on_featured_image()` adds the
 * two keys, which is what makes this pattern work. The reasoning, the evidence
 * and the note to retire it once Tour Operator declares the context itself are
 * all on that method. Verified end to end on 2026-08-26 against a scratch term:
 * an `<img>` from the term thumbnail, wrapped in `/brand/<slug>/`, `alt` set to
 * the term name.
 */
?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Homepage - Africa's finest"},"align":"full","className":"is-style-light-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-light-page-section">
	<!-- wp:heading {"textAlign":"center","className":"is-style-section-title","anchor":"h-we-only-work-with-africas-finest"} -->
	<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-we-only-work-with-africas-finest"><?php esc_html_e( 'We only work with Africa’s finest', 'sd-theme-2026' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:terms-query {"termQuery":{"perPage":21,"taxonomy":"accommodation-brand","order":"asc","orderBy":"name","include":[],"hideEmpty":true,"showNested":false,"inherit":false},"hasCustomClass":true,"align":"wide","className":"is-style-slider-frame lsx-to-slider","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
	<div class="wp-block-terms-query alignwide is-style-slider-frame lsx-to-slider">
		<!-- wp:term-template {"className":"columns-5 is-style-image-hover-zoom","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":5}} -->
			<!-- wp:group {"metadata":{"name":"Brand Logo"},"style":{"spacing":{"blockGap":"0","padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center","verticalAlignment":"center"}} -->
			<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
				<!-- wp:post-featured-image {"isLink":true,"height":"100px","scale":"contain","sizeSlug":"medium"} /-->
			</div>
			<!-- /wp:group -->
		<!-- /wp:term-template -->
	</div>
	<!-- /wp:terms-query -->
</section>
<!-- /wp:group -->
