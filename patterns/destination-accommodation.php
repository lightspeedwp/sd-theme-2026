<?php
/**
 * Title: Destination — Accommodation Shelf
 * Slug: sd-theme-2026/destination-accommodation
 * Description: The connected-accommodation carousel: the destination's accommodation_to_destination connections as compact accommodation cards, three across.
 * Categories: sd-theme-2026/tour-operator
 * Keywords: destination, accommodation, lodges, carousel, shelf
 * Viewport Width: 1400
 * Template Types: single
 * Post Types: wp_template
 * Inserter: false
 *
 * @package sd-theme-2026
 */

/*
 * The accommodation shelf — live's `#accommodation`, the region branch's
 * first section, "Our Favourite Chobe National Park Accommodations".
 * `sd_lsx_to_region_accommodation()` composes that heading from the post
 * title (template-tags.php:524) and gates the whole section on
 * `! lsx_to_item_has_children()`, i.e. on the destination being a region.
 *
 * Here it is gated instead by there being connected accommodation:
 * `accommodation-related-destination` resolves through the
 * `accommodation_to_destination` meta, and a country carries that meta too —
 * Botswana lists 93. So this band would appear on a country where live hides
 * it. Left in, deliberately: the shelf is correct content either way, it is
 * the country page's most useful outbound link after its regions, and the
 * alternative is a theme conditional reproducing a branch the plugin already
 * expresses better. Flagged rather than filtered — if it should match live
 * exactly, that is a `parents-only`-style key on the plugin side, not PHP
 * here. → LS-2033
 *
 * The tile is `patterns/card-accommodation-compact.php`, which already
 * describes itself as the card these carousels carry. Three across, as
 * live's `slidesToShow: 3`.
 */
?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Accommodation"},"align":"full","className":"is-style-light-page-section lsx-accommodation-related-destination-query-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"accommodation"} -->
<section class="wp-block-group alignfull is-style-light-page-section lsx-accommodation-related-destination-query-wrapper" id="accommodation">

	<!-- wp:heading {"textAlign":"center","metadata":{"name":"Accommodation Heading","bindings":{"content":{"source":"sd/post-field","args":{"field":"title","prefix":"<?php esc_attr_e( 'Our Favourite ', 'sd-theme-2026' ); ?>","suffix":"<?php esc_attr_e( ' Accommodations', 'sd-theme-2026' ); ?>"}}}},"className":"is-style-section-title","anchor":"h-accommodation"} -->
	<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-accommodation"><?php esc_html_e( 'Our Favourite Accommodations', 'sd-theme-2026' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:query {"query":{"perPage":15,"pages":0,"offset":0,"postType":"accommodation","order":"asc","orderBy":"title","search":"","exclude":[],"sticky":"","inherit":false},"hasCustomClass":true,"align":"wide","className":"is-style-slider-frame lsx-to-slider","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
	<div class="wp-block-query alignwide is-style-slider-frame lsx-to-slider">
		<!-- wp:post-template {"className":"lsx-accommodation-related-destination-query","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16px"}} -->
			<!-- wp:pattern {"slug":"sd-theme-2026/card-accommodation-compact"} /-->
		<!-- /wp:post-template -->
	</div>
	<!-- /wp:query -->

</section>
<!-- /wp:group -->
