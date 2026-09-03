<?php
/**
 * Title: Template: Page (Full Width, No Title)
 * Slug: sd-theme-2026/template-page-full
 * Description: Page template body — light header, full-width post content with no page title, then the "Why choose Southern Destinations" closing band, dark footer. Used by the "Page (Full Width, No Title)" template.
 * Categories: hidden
 * Keywords: template, page, full width, no title
 * Block Types: core/post-content
 * Template Types: page
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 *
 * @package sd-theme-2026
 */

?>

<!-- wp:group {"tagName":"main","align":"full","style":{"spacing":{"margin":{"top":"0"}}},"layout":{"type":"constrained"}} -->
<main class="wp-block-group alignfull" style="margin-top:0">
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
