<?php
/**
 * Title: Blog — Browse By Category
 * Slug: sd-theme-2026/blog-categories
 * Description: The continuous band of category tiles that runs under the blog standfirst — a Terms Query over the post categories, five tiles at a time on a Slick shelf, each the category name in uppercase over its scrim.
 * Categories: sd-theme-2026/posts
 * Keywords: blog, categories, browse, terms, slider, carousel, tiles
 * Viewport Width: 1400
 * Block Types: core/terms-query
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * Live's `#categories-slider`, measured in the browser on 2026-09-16.
 *
 * LSX Blog Customizer prints a Slick shelf of the post categories directly
 * beneath the blog standfirst: nine tiles, five visible at a time, each 228px
 * wide and 100px tall with the category name centred in uppercase white over a
 * grey ground. Here it is a `core/terms-query` over `category`, so the shelf
 * follows the taxonomy rather than a hand-written list — the same construction
 * as the brand shelf in patterns/homepage-brands.php, and for the same reason:
 * a literal list of nine categories would be stale the first time an editor
 * adds one. Task 12.5 — the editorial workflow is unaffected.
 *
 * ## The gutter is zero, and that is the design
 *
 * Live's five tiles measure 228px inside a 1140px rail — 5 × 228 = 1140
 * exactly. There is no gutter: the tiles butt together into one continuous
 * band, which is why the shelf reads as a single grey bar with five labels
 * rather than as five cards. `blockGap: 0` on the term template is what
 * reproduces that, and it is on the markup rather than in a style variation
 * because a variation's own `blockGap` is emitted nowhere. → AGENTS.md
 *
 * ## The carousel is Tour Operator's Slick, not the Carousel Block
 *
 * `hasCustomClass` plus `lsx-to-slider` is Tour Operator's "Enable Slider"
 * checkbox, and `columns-5` on the template element is what its `custom.js`
 * reads `slidesToShow` from — the same pair patterns/homepage-brands.php
 * carries, with the reasoning written out there. The grid layout does double
 * duty: it sets the five-up shelf for Slick, and it is what the band degrades
 * to with JavaScript off or with Tour Operator deactivated.
 *
 * `is-style-slider-frame` supplies the arrows and dots. Live draws two round
 * arrows outside the band and a pair of dots beneath it; the style's own
 * description records where that presentation was settled.
 *
 * ## `hideEmpty`, and why there is no heading
 *
 * `hideEmpty: true` matches live, which lists only categories that have posts.
 *
 * Live's own heading — `<h2 class="text-center categories-slider-title">Browse
 * By Category</h2>` — is in the markup and is `display: none`, measured, not
 * inferred. So the band has no visible heading on the live site and has none
 * here. Reproducing a hidden heading would need a screen-reader-only utility
 * class this theme does not carry, and adding a visible one is a design
 * decision rather than a translation. → AGENTS.md, "No redesign"
 *
 * The tile is patterns/card-category.php, `require`d rather than referenced as
 * a nested pattern — a `wp:pattern` reference inside another pattern resolves
 * under WP-CLI and is silently dropped on front-end render.
 * → .claude/skills/wp-pattern-runtime-pitfalls
 */

?>
<!-- wp:terms-query {"termQuery":{"perPage":100,"taxonomy":"category","order":"asc","orderBy":"name","include":[],"hideEmpty":true,"showNested":false,"inherit":false},"hasCustomClass":true,"align":"wide","className":"is-style-slider-frame lsx-to-slider"} -->
<div class="wp-block-terms-query alignwide is-style-slider-frame lsx-to-slider">

	<!-- wp:term-template {"className":"columns-5","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"grid","columnCount":5}} -->
		<?php require __DIR__ . '/card-category.php'; ?>
	<!-- /wp:term-template -->

</div>
<!-- /wp:terms-query -->
