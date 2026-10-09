<?php
/**
 * Title: Template: Page (About Us, Own Banner)
 * Slug: sd-theme-2026/template-page-about-us
 * Description: Page template body for the static About Us pages — full-width post content, then the "Why choose Southern Destinations" closing band. The same as Page (Full Width, Banner) without its hero banner: each page carries its own banner cover in its content, because the image and the strapline differ per page.
 * Categories: hidden
 * Keywords: template, page, full width, about us, banner
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
	 * No banner here, and no breadcrumb trail.
	 *
	 * This is `template-page-full.php` minus `hero-page-banner.php`. That
	 * template declares one banner for every page it serves, with the featured
	 * image as the photograph and `banner_subtitle` as the strapline. The About
	 * Us tree cannot share one: live gives Social Responsibility a different
	 * photograph from its siblings, and only some pages have a strapline. So
	 * each page carries its own `core/cover` in its content — image, title and
	 * any strapline typed in — built to the same attributes as the pattern and
	 * wearing the same `is-style-hero-banner`.
	 *
	 * The title is the content's `<h1>`. The template must not add a second.
	 *
	 * The breadcrumb trail is the one other thing that stood between the banner
	 * and the content in `template-page-full.php`. A template cannot place it
	 * after a banner that lives inside the content, and above the banner it
	 * would sit under the header with the photograph below it, which is not
	 * where live puts it. Each page therefore opens its content with the cover
	 * and follows it with the same Yoast breadcrumbs group
	 * (`patterns/breadcrumbs.php`).
	 *
	 * `template-page-full.php` and `page-no-title` are untouched: other pages
	 * still use them.
	 */
	?>

	<!-- wp:post-content {"align":"full","layout":{"type":"constrained"}} /-->

	<?php
	/*
	 * The closing band, exactly as `template-page-full.php` carries it.
	 *
	 * `require`, not a nested `<!-- wp:pattern /-->`: a pattern reference
	 * inside a pattern is dropped on front-end render while still resolving
	 * under a WP-CLI `do_blocks()` test. → wp-pattern-runtime-pitfalls
	 */
	require __DIR__ . '/why-choose-sd.php';
	?>

<!-- wp:template-part {"slug":"pages"} /-->
</main>
<!-- /wp:group -->
