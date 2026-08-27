<?php
/**
 * Title: Card — Media Overlay
 * Slug: sd-theme-2026/card-media-overlay
 * Description: The grid tile every Tour Operator archive uses — a full-bleed featured image with a warm scrim across it and the post title centred on top. The whole tile is the target; there is no call to action. Drop it into a Query Loop's post template on the tour, accommodation or destination archive.
 * Categories: sd-theme-2026/card, sd-theme-2026/tour-operator
 * Keywords: card, tile, grid, overlay, scrim, tour, accommodation, destination, archive
 * Viewport Width: 640
 * Block Types: core/post-template
 * Post Types: tour, accommodation, destination
 * Inserter: true
 *
 * @package sd-theme-2026
 */

?>
<!-- wp:group {"metadata":{"name":"Media Overlay Card"},"className":"is-style-media-overlay-card","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-media-overlay-card">
	<!-- wp:group {"metadata":{"name":"Media"},"className":"media-overlay-card__media","layout":{"type":"default"}} -->
	<div class="wp-block-group media-overlay-card__media">
		<!-- wp:post-featured-image {"isLink":true} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"Scrim"},"className":"media-overlay-card__scrim","layout":{"type":"constrained"}} -->
	<div class="wp-block-group media-overlay-card__scrim">
		<!-- wp:post-title {"textAlign":"center","level":3,"isLink":true} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
