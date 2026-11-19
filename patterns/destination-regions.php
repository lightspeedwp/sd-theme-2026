<?php
/**
 * Title: Destination — Regions Shelf
 * Slug: sd-theme-2026/destination-regions
 * Description: The regions carousel a country carries: its child destinations as overlay tiles, three across, under a heading composed from the country's title.
 * Categories: sd-theme-2026/tour-operator
 * Keywords: destination, regions, country, children, carousel, shelf
 * Viewport Width: 1400
 * Template Types: single
 * Post Types: wp_template
 * Inserter: false
 *
 * @package sd-theme-2026
 */

/*
 * The regions shelf — live's `#regions`, the country branch's first section.
 *
 * "Popular Travel Destinations in Botswana" is live's heading verbatim:
 * `$regions_page_title = 'Popular Travel Destinations in ' . $post->post_title`
 * (layout.php:180). A binding replaces a block's whole `content`, so the
 * standing half cannot be static text beside a bound span — `sd/post-field`'s
 * `prefix` arg carries it, which is the case that source's docblock was
 * written for. The authored fallback is what shows in the editor and on any
 * render where the binding cannot resolve a post.
 *
 * `lsx-regions-query` is the one shelf here that reads no connection meta:
 * `query_args_filter()` sets `post_parent__in => [ get_the_ID() ]`
 * (class-query-loop.php:378), so it is literally the destination's children.
 * The matching wrapper is checked against
 * `lsx_to_item_has_children( get_the_ID(), 'destination' )` by name
 * (line 159) — the same test live's PHP branches on — so this band is
 * present on a country and gone on a region without a conditional.
 *
 * The tile is `patterns/card-media-overlay.php`: the square photograph with
 * the linked title over a scrim, the same tile the destinations archive
 * grids. Live's own region card is an image over a white panel with an
 * excerpt and a "View more"; the overlay tile is Zared's call for this
 * shelf, and it makes the regions on a country read as the same object as
 * the countries on /destinations/.
 *
 * Three across, as live's `slidesToShow: 3`. Slick reads that count off the
 * `columns-N` class `core/post-template` emits from its own
 * `layout.columnCount`, so the grid layout is both the carousel's setting
 * and what the shelf degrades to with JavaScript off.
 */
?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Regions"},"align":"full","className":"is-style-light-page-section lsx-regions-query-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"regions"} -->
<section class="wp-block-group alignfull is-style-light-page-section lsx-regions-query-wrapper" id="regions">

	<!-- wp:heading {"textAlign":"center","metadata":{"name":"Regions Heading","bindings":{"content":{"source":"sd/post-field","args":{"field":"title","prefix":"<?php esc_attr_e( 'Popular Travel Destinations in ', 'sd-theme-2026' ); ?>"}}}},"className":"is-style-section-title","anchor":"h-regions"} -->
	<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-regions"><?php esc_html_e( 'Popular Travel Destinations', 'sd-theme-2026' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:query {"query":{"perPage":15,"pages":0,"offset":0,"postType":"destination","order":"asc","orderBy":"title","search":"","exclude":[],"sticky":"","inherit":false},"hasCustomClass":true,"align":"wide","className":"is-style-slider-frame lsx-to-slider","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
	<div class="wp-block-query alignwide is-style-slider-frame lsx-to-slider">
		<!-- wp:post-template {"className":"lsx-regions-query","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16px"}} -->
			<!-- wp:pattern {"slug":"sd-theme-2026/card-media-overlay"} /-->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->

</section>
<!-- /wp:group -->
