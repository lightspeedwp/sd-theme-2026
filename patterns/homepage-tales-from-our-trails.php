<?php
/**
 * Title: Homepage — Tales From Our Trails
 * Slug: sd-theme-2026/homepage-tales-from-our-trails
 * Description: The homepage news band — a Slick carousel of post tiles, three at a time, each carrying the landscape featured image, the title, a date-and-categories byline and the excerpt.
 * Categories: sd-theme-2026/posts, sd-theme-2026/features
 * Keywords: news, blog, posts, tales, trails, carousel, slider, latest
 * Viewport Width: 1400
 * Template Types: front-page
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * What this replaces, and why the card is a reference rather than a copy.
 *
 * This band was built on dev as "Latest News": a static four-up grid of image
 * and title, nothing else. Live is neither — it is `lsx-block-post-carousel`
 * under the heading "Tales from our trails", and each card carries the landscape
 * image, the title, an italic date-and-categories byline and the excerpt
 * (measured on the live homepage 2026-08-26, eighteen posts in the carousel).
 * So the heading, the card content and the carousel all change here.
 *
 * `patterns/card-post-grid.php` already describes itself as "the post tile the
 * homepage 'Tales from our trails' carousel renders" and already holds exactly
 * that composition, so this band references it instead of restating it.
 *
 * **Nested `wp:pattern` inside a query loop does work** — worth stating plainly,
 * because it is the kind of thing that gets assumed broken. `core/pattern` has a
 * server-side render callback (wp-includes/blocks/pattern.php:33) which
 * `do_blocks()` its content, and the post-template's per-post context reaches it
 * intact. Verified locally 2026-08-26 against three fixture posts: three
 * distinct titles and three distinct featured images, one per post.
 *
 * ## The carousel is Tour Operator's Slick, not the Carousel Block
 *
 * Tour Operator's "Enable Slider" checkbox extends `core/query`
 * (src/js/blocks/slider-query.js:17) — it sets `hasCustomClass` and adds
 * `lsx-to-slider`, and custom.js:449-480 initialises Slick on the descendant
 * `.wp-block-post-template`. Both attributes are written out so the checkbox
 * reads as ticked when the pattern is opened.
 *
 * `slidesToShow` is read off a `columns-N` class on the template element
 * (custom.js:458-464), and `core/post-template` emits that class from its own
 * `layout.columnCount` (wp-includes/blocks/post-template.php:95) — so the grid
 * layout below does double duty: it sets the three-up shelf for Slick *and* it
 * is what the band degrades to with JavaScript off, or with Tour Operator
 * deactivated. Nothing needs a hand-written class here, unlike the brand shelf
 * in patterns/homepage-brands.php where `core/term-template` emits no such
 * class.
 *
 * `sticky: "exclude"` so a pinned post does not jump the queue in a band that is
 * chronological by design.
 */
?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Homepage - Tales from our trails"},"align":"full","className":"is-style-light-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-light-page-section">
	<!-- wp:heading {"textAlign":"center","className":"is-style-section-title","anchor":"h-tales-from-our-trails"} -->
	<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-tales-from-our-trails"><?php echo esc_html__( 'Tales from our trails', 'sd-theme-2026' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:query {"queryId":0,"query":{"perPage":18,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","search":"","exclude":[],"sticky":"exclude","inherit":false},"hasCustomClass":true,"align":"wide","className":"is-style-slider-frame lsx-to-slider","layout":{"type":"default"}} -->
	<div class="wp-block-query alignwide is-style-slider-frame lsx-to-slider">
		<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3}} -->
			<!-- wp:pattern {"slug":"sd-theme-2026/card-post-grid"} /-->
		<!-- /wp:post-template -->

		<!-- wp:query-no-results -->
			<!-- wp:paragraph {"align":"center"} -->
			<p class="has-text-align-center"><?php echo esc_html__( 'No stories yet — watch this space.', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->
</section>
<!-- /wp:group -->
