<?php
/**
 * Title: Template: Page
 * Slug: sd-theme-2026/template-page-centered
 * Description: Default page template body — light header, a centred page title and constrained post content, then the dark footer. Used by the default Page template.
 * Categories: hidden
 * Keywords: template, page, default, title
 * Block Types: core/post-content
 * Template Types: page
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 *
 * @package sd-theme-2026
 */

/*
 * The breadcrumb strip sits first in `<main>`, as it does on every other inner
 * template. Live shows "Home / Terms & Conditions" on Privacy Policy and Terms
 * & Conditions, the pages this template serves (measured 2026-11-13); until
 * then they had no trail. The title and content keep their own padded group
 * below, so the strip runs edge to edge with no gap above it.
 *
 * `require`, not a nested `<!-- wp:pattern /-->`: a pattern reference inside a
 * pattern is dropped on front-end render. → wp-pattern-runtime-pitfalls
 */

?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Page"},"align":"full","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"},"blockGap":"0"}},"layout":{"type":"constrained"}} -->
<main class="wp-block-group alignfull" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php require __DIR__ . '/breadcrumbs.php'; ?>

	<!-- wp:group {"metadata":{"name":"Page Body"},"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"},"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)">

		<!-- wp:post-title {"textAlign":"center","level":1,"fontFamily":"heading","fontSize":"700","textColor":"contrast","style":{"typography":{"fontWeight":"var:custom|font-weight|semi-bold","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase"}}} /-->

		<!-- wp:post-content {"align":"full","layout":{"type":"constrained"}} /-->

	</div>
	<!-- /wp:group -->

</main>
<!-- /wp:group -->
