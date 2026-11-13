<?php
/**
 * Title: Template: FAQ Page
 * Slug: sd-theme-2026/template-page-faq
 * Description: FAQ page template body — the hero banner and breadcrumb trail, the page's own content as an intro, then the general FAQ template parts in one place: About, enquiries, service, connecting, and destinations. Country FAQs stay on their country pages. Closes on the "Why choose Southern Destinations" band. Used by the "FAQ" template.
 * Categories: hidden
 * Keywords: template, page, faq, questions
 * Block Types: core/post-content
 * Template Types: page
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 *
 * @package sd-theme-2026
 */

?>

<!-- wp:group {"tagName":"main","metadata":{"name":"FAQ Page"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php
	/*
	 * The banner and the trail, composed exactly as template-page-full does —
	 * `require`, because a nested pattern reference is dropped on front-end
	 * render (→ wp-pattern-runtime-pitfalls).
	 */
	require __DIR__ . '/hero-page-banner.php';
	require __DIR__ . '/breadcrumbs.php';
	?>

	<!-- wp:post-content {"align":"full","layout":{"type":"constrained"}} /-->

	<?php
	/*
	 * Every FAQ template part, in one place, the way ASC's FAQ page composes
	 * its own (asc-2025/templates/page-faq.html). The same parts serve the
	 * pages they belong to, so there is one copy of each question.
	 *
	 * Order: the general questions first (About, enquiries, service,
	 * connecting — parts/pages.html), then destinations as a whole. Each group
	 * brings its own section title. The per-country groups
	 * (parts/faq-countries.html) stay on their own country pages and are not
	 * repeated here — ASC's FAQ page is general questions only too.
	 *
	 * ⚠️ Every group in `pages` is gated by Block Visibility to the page it
	 * belongs to. Each carries a second rule set for the FAQ page (ID 51738) —
	 * rule sets are OR'd
	 * (block-visibility/includes/frontend/visibility-tests/location.php) — so
	 * the groups appear here as well as on their own pages. A new general FAQ
	 * group needs that second rule set too, or it will not show on this page.
	 * The ID survives because dev is deployed to live wholesale.
	 */
	?>
	<!-- wp:template-part {"slug":"pages"} /-->

	<!-- wp:template-part {"slug":"destinations"} /-->

	<?php
	/*
	 * The same closing band as the other static pages (template-page-full).
	 */
	require __DIR__ . '/why-choose-sd.php';
	?>

</main>
<!-- /wp:group -->
