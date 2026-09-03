<?php
/**
 * Title: Destination — Tours Shelf
 * Slug: sd-theme-2026/destination-tours
 * Description: The connected-tours carousel: the destination's tour_to_destination connections as compact tour cards, three across.
 * Categories: sd-theme-2026/tour-operator
 * Keywords: destination, tours, itineraries, carousel, shelf
 * Viewport Width: 1400
 * Template Types: single
 * Post Types: wp_template
 * Inserter: false
 *
 * @package sd-theme-2026
 */

/*
 * The tours shelf — live's `#tours`, "Botswana Tours to Inspire You".
 * `sd_lsx_to_destination_tours()` composes it as
 * `$post->post_title . ' Tours to Inspire You'` (template-tags.php:589), so
 * the standing half is a `suffix` here rather than a prefix.
 *
 * This is the one shelf both live branches render, which is why it sits
 * below the two that are exclusive to one branch each and above the reviews
 * that only a region gets. That is live's order in both directions:
 * country → regions → tours, region → accommodation → tours → reviews.
 *
 * The tile is `patterns/card-tour-compact.php` — the compact tour card, the
 * same one the tour single's related shelf carries, so a tour looks the same
 * wherever it is shelved. Three across, as live's `slidesToShow: 3`.
 * `perPage` is 15 rather than 3: the shelf shows three at a time either way,
 * the count is how deep the carousel runs, and Botswana connects 13 tours.
 */
?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Related Tours"},"align":"full","className":"is-style-light-page-section lsx-tour-related-destination-query-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"tours"} -->
<section class="wp-block-group alignfull is-style-light-page-section lsx-tour-related-destination-query-wrapper" id="tours">

	<!-- wp:heading {"textAlign":"center","metadata":{"name":"Tours Heading","bindings":{"content":{"source":"sd/post-field","args":{"field":"title","suffix":"<?php esc_attr_e( ' Tours to Inspire You', 'sd-theme-2026' ); ?>"}}}},"className":"is-style-section-title","anchor":"h-tours"} -->
	<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-tours"><?php esc_html_e( 'Tours to Inspire You', 'sd-theme-2026' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:query {"query":{"perPage":15,"pages":0,"offset":0,"postType":"tour","order":"asc","orderBy":"title","search":"","exclude":[],"sticky":"","inherit":false},"hasCustomClass":true,"align":"wide","className":"is-style-slider-frame lsx-to-slider","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
	<div class="wp-block-query alignwide is-style-slider-frame lsx-to-slider">
		<!-- wp:post-template {"className":"lsx-tour-related-destination-query","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16px"}} -->
			<!-- wp:pattern {"slug":"sd-theme-2026/card-tour-compact"} /-->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->

</section>
<!-- /wp:group -->
