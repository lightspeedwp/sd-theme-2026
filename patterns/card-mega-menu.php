<?php
/**
 * Title: Card — Mega Menu
 * Slug: sd-theme-2026/card-mega-menu
 * Description: The featured card in the right-hand column of every header mega menu — the latest destination, tour, property or blog post, as a 3:2 featured image above its title and an excerpt clamped to three lines.
 * Categories: sd-theme-2026/card, sd-theme-2026/menu
 * Keywords: card, mega menu, featured, header, navigation, latest
 * Viewport Width: 320
 * Block Types: core/post-template
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * Replicated 2026-10-06 from the synced pattern "Card - Mega Menu" (`wp_block`
 * 65890), which the four mega menus referenced as `<!-- wp:block {"ref":65890} /-->`.
 * A synced pattern is per-install content: its ID only resolved because dev is
 * deployed to live wholesale, the same hazard AGENTS.md names for navigation
 * `ref`s. Markup is unchanged; only the block name gained its em dash.
 *
 * The four parts — parts/mega-menu-destinations.html, -tours.html,
 * -accommodation.html and -about.html — reference this file as
 * `<!-- wp:pattern {"slug":"sd-theme-2026/card-mega-menu"} /-->` inside their
 * `core/post-template`, so this is the only copy. The loop's post context
 * survives the reference: measured on local (WP 7) 2026-10-06, the same tour
 * title rendered inline and by reference. That holds for a part referencing a
 * pattern; a pattern nested inside another *pattern* is a different case
 * (→ `wp-pattern-runtime-pitfalls`).
 *
 * Resting styles come from `is-style-mega-panel` (styles/sections/mega-panel.json)
 * and the hover from assets/styles/ollie-mega-menu.css, both keyed off the panel,
 * so the card carries no class of its own.
 */

?>
<!-- wp:group {"metadata":{"name":"Card — Mega Menu"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
	<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"3/2","style":{"border":{"radius":{"topLeft":"0","topRight":"0","bottomLeft":"0","bottomRight":"0"}}}} /-->

	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:post-title {"isLink":true,"fontSize":"300"} /-->

		<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group">
			<!-- wp:post-excerpt {"excerptLength":35,"className":"is-style-excerpt-truncate-3","style":{"typography":{"textTransform":"none"}},"fontSize":"200"} /-->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
