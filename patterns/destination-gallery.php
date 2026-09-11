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
 * `sd/gallery`, from `sd-enhancements`, replaces the placeholder pass that
 * stood here. It reads the same `gallery` meta through its own render rather
 * than Tour Operator's `lsx/gallery` binding, and produces live's actual
 * layout: two tiles across the top row, three across the second, a `+N more`
 * overlay on the fifth, and a lightbox over the whole set.
 *
 * What it replaces was a `core/gallery` carrying that binding, which
 * `Bindings::render_gallery_block()` rewrote into a flat figure of every
 * image — no stagger, no cap, no overlay, no lightbox, and `<img src>` with
 * no `srcset` or `alt`, because the binding only has the ID => URL map to
 * work from. The three empty `core/image` blocks that gave the editor
 * something to show are gone with it; `sd/gallery` previews on the server.
 *
 * Worth knowing if this is ever compared against production: **live's
 * version of this layout does not render.** Envira emits the right classes
 * and the right `+N more` label, but its own reset leaves the tile box
 * collapsed to 4px, so the images spill out at 1:1 and the overlay sits over
 * nothing. The measurement and the four stylesheets responsible are in
 * `sd-enhancements/blocks/gallery/render.php`. Live is the design intent
 * here, not the reference rendering.
 *
 * Attributes are left at their defaults — five visible, 3:2 tiles,
 * `lsx-to-gallery` files, lightbox on. The block's Settings panel carries
 * all four.
 *
 * `lsx-gallery-wrapper` drops the band, heading included, when the meta is
 * not an array — `maybe_hide_varitaion()`, `'gallery'` branch. That check
 * reads the meta directly and does not care which block is inside, so it
 * still fires.
 */
?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Gallery"},"align":"full","className":"is-style-light-page-section lsx-gallery-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"gallery"} -->
<section class="wp-block-group alignfull is-style-light-page-section lsx-gallery-wrapper" id="gallery">

	<!-- wp:heading {"textAlign":"center","className":"is-style-section-title","anchor":"h-gallery"} -->
	<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-gallery"><?php esc_html_e( 'Gallery', 'sd-theme-2026' ); ?></h2>
	<!-- /wp:heading -->

<!-- wp:sd/gallery {"align":"wide","metadata":{"name":"Destination Gallery"}} /-->

</section>
<!-- /wp:group -->
