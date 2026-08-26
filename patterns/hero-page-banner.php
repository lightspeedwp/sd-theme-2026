<?php
/**
 * Title: Page Hero Banner
 * Slug: sd-theme-2026/hero-page-banner
 * Description: The inner-page banner — a full-bleed featured image about 454px tall under a warm-dark scrim, carrying a small uppercase eyebrow (the section the page sits under) above the page title. Pulls its image from the page's featured image, so one pattern serves every inner page.
 * Categories: sd-theme-2026/hero, sd-theme-2026/pages
 * Keywords: hero, banner, page header, title, cover, about
 * Viewport Width: 1400
 * Block Types: core/cover
 * Template Types: page
 * Post Types: page
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * What this replaces — the About Us tree's `lsx-blocks/lsx-banner-box`.
 *
 * Live builds this banner as an LSX Banner Box: a `<picture>` with four
 * hand-written `crop=…&resize=…` sources, a wrapper painted `#ffffff`, and the
 * type coloured with inline `style="color:#ffffff;font-size:40px"` on every
 * node. All of that is replaced by one `core/cover` carrying
 * `is-style-hero-banner`, which owns the ground, the scrim and the type colours
 * — see styles/sections/hero-banner.json. Nothing here sets a colour.
 *
 * ## The image is the featured image, not a baked URL
 *
 * `useFeaturedImage` is the whole reason this is one pattern rather than four.
 * The four About Us pages all point at the same `header-about-us-new.jpg` on
 * live, but they point at it by URL — so the crop, the srcset and the alt text
 * were re-typed per page and drifted. Here each page sets a featured image and
 * core generates the srcset. A page with no featured image falls back to the
 * scrim over the section ground, which is legible rather than broken.
 *
 * ## Heading levels — deliberately not live's
 *
 * Live emits `<h2 class="lsx-banner-name">About Us</h2>` for the eyebrow and a
 * `<p class="lsx-banner-title">` for the actual page name, while the real
 * `<h1 class="page-title">` is hidden off-screen by `lsx-disabled-hidden-title`.
 * That is an h2 standing in for the page's h1, with the h1 hidden — three
 * separate a11y problems in one banner.
 *
 * Here the page title is the h1 (`core/post-title`, so it stays dynamic) and the
 * section label above it is a plain paragraph, because it labels the banner and
 * does not head a section. The visual hierarchy is the one thing that changes
 * from live: live sets the eyebrow at 40px and the page name at ~26px, i.e. the
 * page's own name is the smaller of the two. That inversion is not preserved —
 * the title reads larger than its eyebrow here. Recorded as a light-refresh
 * call, not a redesign. → AGENTS.md "No redesign"
 *
 * ## Why a fixed min-height and not an aspect ratio
 *
 * 454px is the height the live banner image is actually served at (1920x454),
 * and the section style pins it so a short image cannot collapse the band. The
 * cover crops rather than letterboxes, which is what live does.
 */

?>
<!-- wp:cover {"useFeaturedImage":true,"dimRatio":50,"minHeight":454,"minHeightUnit":"px","metadata":{"name":"Page Hero Banner"},"align":"full","className":"is-style-hero-banner","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull is-style-hero-banner" style="min-height:454px">
	<span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span>
	<div class="wp-block-cover__inner-container">

		<!-- wp:paragraph {"align":"center","metadata":{"name":"Eyebrow"},"style":{"typography":{"textTransform":"uppercase","letterSpacing":"var:custom|letter-spacing|wide","fontWeight":"var:custom|font-weight|semi-bold"},"spacing":{"margin":{"bottom":"var:preset|spacing|10"}}},"fontFamily":"heading","fontSize":"300"} -->
		<p class="has-text-align-center has-heading-font-family has-300-font-size" style="margin-bottom:var(--wp--preset--spacing--10);font-weight:var(--wp--custom--font-weight--semi-bold);letter-spacing:var(--wp--custom--letter-spacing--wide);text-transform:uppercase"><?php esc_html_e( 'About Us', 'sd-theme-2026' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:post-title {"textAlign":"center","level":1,"metadata":{"name":"Page Title"},"style":{"typography":{"fontWeight":"var:custom|font-weight|black","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase"}},"fontFamily":"heading","fontSize":"700"} /-->

	</div>
</div>
<!-- /wp:cover -->
