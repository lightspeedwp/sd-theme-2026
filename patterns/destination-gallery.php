<?php
/**
 * Title: Destination — Gallery
 * Slug: sd-theme-2026/destination-gallery
 * Description: The destination gallery band — a three-column grid rebuilt from Tour Operator's gallery meta, on the page's own white ground.
 * Categories: sd-theme-2026/tour-operator
 * Keywords: destination, gallery, images, single
 * Viewport Width: 1400
 * Template Types: single
 * Post Types: wp_template
 * Inserter: false
 *
 * @package sd-theme-2026
 */

/*
 * The gallery.
 *
 * Live's `#gallery` runs edge to edge on the page's own white ground
 * (`padding: 65px 9999rem 45px`, custom.css:1802) — no tint, unlike the tour
 * single's. The engine is Envira on live and Tour Operator's own gallery
 * binding here: `Bindings::render_gallery_block()` reads the `gallery` meta
 * and rebuilds the figure from it, discarding whatever image blocks are
 * authored inside. The three below exist so the block has something to show
 * in the editor; they are never rendered on the front end. Botswana's
 * `gallery` meta holds twelve images, and all twelve render.
 *
 * ⚠️ **This is the placeholder pass, not the gallery build.** Live shows a
 * staggered three-column grid capped at five visible tiles with a "+N more"
 * overlay on the fifth, and opens an Envira lightbox. This renders every
 * image in a plain grid, which is what was asked for now. The staggered
 * layout, the overflow tile and the lightbox are a separate task; nothing
 * here needs to change for them except this block.
 *
 * `lsx-gallery-wrapper` drops the band, heading included, when the meta is
 * not an array — `maybe_hide_varitaion()`, `'gallery'` branch.
 */
?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Gallery"},"align":"full","className":"is-style-light-page-section lsx-gallery-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"gallery"} -->
<section class="wp-block-group alignfull is-style-light-page-section lsx-gallery-wrapper" id="gallery">

	<!-- wp:heading {"textAlign":"center","className":"is-style-section-title","anchor":"h-gallery"} -->
	<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-gallery"><?php esc_html_e( 'Gallery', 'sd-theme-2026' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:gallery {"columns":3,"linkTo":"media","linkTarget":"_blank","sizeSlug":"large","align":"wide","metadata":{"name":"Destination Gallery","bindings":{"content":{"source":"lsx/gallery"}}},"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|10","left":"var:preset|spacing|10"}}}} -->
	<figure class="wp-block-gallery alignwide has-nested-images columns-3 is-cropped"><!-- wp:image {"linkDestination":"media"} -->
	<figure class="wp-block-image"><img alt=""/></figure>
	<!-- /wp:image -->

	<!-- wp:image {"linkDestination":"media"} -->
	<figure class="wp-block-image"><img alt=""/></figure>
	<!-- /wp:image -->

	<!-- wp:image {"linkDestination":"media"} -->
	<figure class="wp-block-image"><img alt=""/></figure>
	<!-- /wp:image --></figure>
	<!-- /wp:gallery -->

</section>
<!-- /wp:group -->
