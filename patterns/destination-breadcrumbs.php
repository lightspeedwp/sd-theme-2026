<?php
/**
 * Title: Destination — Breadcrumbs
 * Slug: sd-theme-2026/destination-breadcrumbs
 * Description: The breadcrumb strip live runs beneath the destination banner — Yoast's trail on the warm-grey band, at the content measure.
 * Categories: sd-theme-2026/tour-operator
 * Keywords: destination, breadcrumbs, trail, navigation, yoast
 * Viewport Width: 1400
 * Template Types: single
 * Post Types: wp_template
 * Inserter: false
 *
 * @package sd-theme-2026
 */

/*
 * Live's breadcrumb bar, as authored in the Site Editor on dev 2026-09-03
 * (wp_template 65929) and imported here verbatim.
 *
 * Live draws Yoast's trail in a 58px `#ece9e3` strip directly under the
 * banner. `primary-100` is the token nearest that grey and is what was picked
 * in the editor; the band is full-width with the trail itself held at
 * `alignwide`, which is why there are two groups rather than one.
 *
 * ## Why this is a theme block after all
 *
 * `patterns/template-single-destination.php` recorded the breadcrumb bar as
 * `sd-enhancements` work, on the grounds that breadcrumb *output* is a filter
 * over a third-party plugin's trail. That reasoning still holds for the trail's
 * contents — what Yoast puts in it, and any `wpseo_breadcrumb_links` filtering
 * SD needs, remains plugin work. **Placing** the block, and deciding the band's
 * ground and rhythm, is design, so the block itself belongs here. Decision
 * 2026-09-03; the note in that file has been corrected rather than left to
 * contradict this.
 *
 * `yoast-seo/breadcrumbs` renders nothing at all when Yoast SEO is inactive, so
 * the band needs no wrapper class and no conditional — it collapses to an empty
 * strip rather than an error. Yoast SEO Premium 28.2 is installed on live and
 * dev, measured in the live-site audit.
 *
 * ⚠️ **The trail is only as good as Yoast's settings.** On a region, Yoast
 * builds the trail from the post's `post_parent`, so a region reads
 * Home → Destinations → Botswana → Chobe National Park only where the parent is
 * set. Every SD region measured has one.
 */

?>
<!-- wp:group {"metadata":{"name":"Breadcrumbs"},"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|10"}}},"backgroundColor":"primary-100","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-primary-100-background-color has-background" style="padding-top:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--10)">

	<!-- wp:group {"align":"wide","layout":{"type":"default"}} -->
	<div class="wp-block-group alignwide"><!-- wp:yoast-seo/breadcrumbs /--></div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
