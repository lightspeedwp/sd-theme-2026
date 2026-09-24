<?php
/**
 * Title: Template: Page (Full Width, Banner)
 * Slug: sd-theme-2026/template-page-full
 * Description: Page template body — the hero banner (featured image, title, banner_subtitle strapline) and the breadcrumb trail, full-width post content, then the "Why choose Southern Destinations" closing band. Used by the "Page (Full Width, Banner)" template — About Us, its three children and Contact Us.
 * Categories: hidden
 * Keywords: template, page, full width, banner, hero
 * Block Types: core/post-content
 * Template Types: page
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 *
 * @package sd-theme-2026
 */

?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Page"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php
	/*
	 * The banner and the trail, as every Tour Operator template composes them.
	 *
	 * Until 2026-09-23 each of this template's five pages carried its own
	 * banner inside its content: a 454px cover with inline padding, a raw
	 * `#636c75` overlay and an "About Us" `<h2>` eyebrow above the real title —
	 * five copies of a device the theme had already replaced, which is why they
	 * drifted from `patterns/hero-page-banner.php` the moment it changed. The
	 * banner is a template concern for the same reason the closing band below
	 * is: it belongs to every page this template serves, and declaring it once
	 * is what keeps it the one banner.
	 *
	 * The page's featured image is the photograph (`useFeaturedImage`) and its
	 * `banner_subtitle` meta is the strapline, through `sd/banner`. Nothing per
	 * page is left in the markup, so an editor sets both in the page sidebar
	 * and never touches the banner itself.
	 *
	 * The title is now visible, so this is no longer a "no title" template in
	 * anything but its slug. The slug stays `page-no-title`: it is the value
	 * stored in each page's `_wp_page_template`, and renaming it would orphan
	 * all five. Only the label in theme.json changed.
	 *
	 * `require`, for the reason given at the closing band below.
	 */
	require __DIR__ . '/hero-page-banner.php';
	require __DIR__ . '/breadcrumbs.php';
	?>

	<!-- wp:post-content {"align":"full","layout":{"type":"constrained"}} /-->

	<?php
	/*
	 * The closing band, and the follow-up patterns/why-choose-sd.php was
	 * waiting on.
	 *
	 * On live the band is not page content: the child theme emits
	 * `.footer-cta-section` beneath every inner page, which is why it appears
	 * verbatim on /about-us/social-responsibility/ and /about-us/connect-with-us/
	 * while being absent from both pages' stored content. It is a template
	 * concern, so it is declared once here rather than pasted into five page
	 * bodies where the sixth would be forgotten.
	 *
	 * This template is the right home for it because it is the static-page
	 * template and nothing else: About Us and its three children, plus Contact
	 * Us, are its only consumers (decision 2026-09-03). The default
	 * templates/page.html deliberately does *not* carry the band — that one
	 * still serves Privacy Policy, Terms, Sitemap, Thank You and the two enquiry
	 * pages, none of which close on a marketing CTA.
	 *
	 * `require`, not a nested `<!-- wp:pattern /-->`: a pattern reference inside
	 * a pattern is dropped on front-end render while still resolving under a
	 * WP-CLI `do_blocks()` test. templates/front-page.html can use the pattern
	 * reference because it is a template file, not a pattern.
	 * → wp-pattern-runtime-pitfalls
	 *
	 * `<main>` carries no bottom padding, so the band's own preset 70 is the
	 * only gap between the last content section and the footer.
	 */
	require __DIR__ . '/why-choose-sd.php';
	?>

</main>
<!-- /wp:group -->
