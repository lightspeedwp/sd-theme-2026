<?php
/**
 * Title: Template: Single Destination
 * Slug: sd-theme-2026/template-single-destination
 * Description: The destination single — the banner, the summary band pairing the destination copy and the safari expert with the cluster map, the gallery, and the region, accommodation, tour and review shelves, closing on the enquiry and Why Choose bands.
 * Categories: sd-theme-2026/tour-operator
 * Keywords: destination, country, region, single, safari, template
 * Viewport Width: 1400
 * Template Types: single
 * Post Types: wp_template
 * Inserter: false
 *
 * @package sd-theme-2026
 */

/*
 * Live's destination single, translated to blocks.
 *
 * Measured from /destination/botswana/ (a country) and
 * /destination/botswana/chobe-national-park/ (a region) on 2026-08-31, against
 * sd-lsx-child/includes/layout.php:166-215
 * (`sd_lsx_to_destination_single_content_bottom()`) and
 * sd-lsx-child/includes/template-tags.php:520-600
 * (`sd_lsx_to_region_accommodation()`, `sd_lsx_to_destination_specials()`,
 * `sd_lsx_to_destination_tours()`, `sd_lsx_to_post_type_reviews()`).
 *
 * ## Three templates, one set of sections
 *
 * Live branches in PHP. `sd_lsx_to_destination_single_content_bottom()` asks
 * `lsx_to_item_has_children( get_the_ID(), 'destination' )` and renders one of
 * two section sets:
 *
 *   country  → gallery → regions → tours
 *   region   → gallery → accommodation → specials → tours → reviews
 *
 * This file is the route. It is the template WordPress resolves for *every*
 * destination, and it carries both branches' sections, because it has to serve
 * a destination of either kind. It does not need a conditional to do that:
 * every shelf carries an `lsx-…-wrapper` class, and Tour Operator's
 * `Query_Loop::maybe_hide_varitaion()`
 * (includes/classes/blocks/class-query-loop.php:79) returns an empty string —
 * heading included — when the query behind it yields nothing. The `regions` key
 * is checked against `lsx_to_item_has_children()` by name (line 159), which is
 * *the same test live branches on*. So a country renders gallery → regions →
 * tours because its regions shelf is the one that fills, and a region renders
 * gallery → accommodation → tours → reviews because its regions shelf is empty.
 * Both come out in live's order, from this one template, with no theme PHP.
 *
 * `patterns/template-single-country.php` and
 * `patterns/template-single-region.php` state that branch explicitly instead.
 * They are Tour Operator's two *assignable* block templates — registered by
 * `lsx\blocks\Templates` with `post_types: [ destination ]`
 * (includes/classes/blocks/class-templates.php:89-99), so they are a per-post
 * override an author picks in the Template panel, not a route WordPress takes
 * on its own. A theme file of the same slug replaces the plugin's default
 * (block-template-utils.php:1231 drops any registered template that has a theme
 * file), which is why `templates/single-country.html` and
 * `templates/single-region.html` are now SD's, not the ones commit de58c9a
 * copied in wholesale.
 *
 * All three assemble the same seven section partials — the difference is which
 * two they omit — so there is one copy of every section and no third place to
 * keep in step:
 *
 *   destination → banner · breadcrumbs · summary · gallery · regions · accommodation · tours · reviews
 *   country     → banner · breadcrumbs · summary · gallery · regions ·               · tours · reviews
 *   region      → banner · breadcrumbs · summary · gallery ·         · accommodation · tours · reviews
 *
 * ## The shelves are Tour Operator's connection queries
 *
 * Each is a `core/query` whose `core/post-template` carries an
 * `lsx-<to>-related-<from>-query` class. `Query_Loop::query_args_filter()`
 * reads it and rewrites the query to the ids in the current post's
 * `<to>_to_<from>` meta. Measured on dev against Botswana (ID 39917), which
 * carries all four: `tour_to_destination` (13), `accommodation_to_destination`
 * (93), `review_to_destination` (11) and `special_to_destination` (6). The
 * class goes on the **post-template**, not the query — `query_loop_block_query_vars`
 * is applied by `render_block_core_post_template()` — and the matching
 * `…-query-wrapper` on the section group is what removes the band when the
 * connection is empty.
 *
 * `regions` is the exception and reads no meta: it is
 * `post_parent__in => [ get_the_ID() ]`, i.e. the destination's children.
 *
 * ## What this template does not carry, and why
 *
 * - **The specials shelf.** Live's region branch renders `#special` between the
 *   accommodation and tour shelves — one full-width offer panel per connected
 *   special, with the `special-badge-single.svg` plate over it. The band is one
 *   `core/query` on `lsx-special-related-destination-query`, but the tile it
 *   needs does not exist: `styles/sections/cards/special-card.json` is written
 *   and registered, and no pattern uses it yet. Building that card is the
 *   specials archive's work, not this template's. When it lands it becomes an
 *   eighth partial, `destination-specials`, required directly between
 *   `destination-accommodation` and `destination-tours` here and in
 *   `template-single-region.php`, and needs nothing else. → flagged on LS-2033
 * - **The `.more-text` read-more collapse.** Live truncates the destination copy
 *   to its first paragraph and appends a "Read More…" link
 *   (sd-lsx-child/assets/js/custom.js:224-275). That is a JavaScript behaviour
 *   over post content, so by the deactivation test it is `sd-enhancements`
 *   work, not a theme block. `patterns/template-single-tour.php` left it out for
 *   the same reason and this file follows it; `core/post-content` renders whole.
 * - **The gold drop cap** on the first letter of the copy
 *   (`.entry-content .more-text:first-letter`, custom.css:2716, ≥900px only).
 *   It rides on `.more-text`, which the point above does not reproduce. The
 *   theme already has the device — `is-style-archive-intro` plus the rule in
 *   assets/styles/core-paragraph.css — so it is a style away, not a build.
 *   Left out here so the tour and destination singles stay identical.
 * - ~~**The breadcrumb bar.**~~ **Built, 2026-09-03** —
 *   `patterns/breadcrumbs.php` (renamed from `destination-breadcrumbs.php` once
 *   `patterns/template-single-tour.php` started requiring it too), imported
 *   from the Site Editor on dev (wp_template 65929) and required directly
 *   beneath the cover on all three destination templates. This file previously
 *   recorded the bar as `sd-enhancements` work on the grounds that breadcrumb
 *   output is a filter over a third-party plugin's trail. That holds for the
 *   trail's *contents* — what Yoast puts in it, and any `wpseo_breadcrumb_links`
 *   filtering SD needs, is still plugin work — but *placing* the block and
 *   choosing the band's ground is design, so the block is a theme block. The
 *   same correction has now been made to patterns/template-single-tour.php;
 *   patterns/template-archive-destination.php still carries the old reasoning
 *   verbatim and is left alone here rather than edited outside this task's
 *   scope. → noted on LS-2033
 * - **Tour Operator's sticky section menu.** The plugin's own
 *   `single-destination.html` opens with `lsx-tour-operator/sticky-menu`, and
 *   live has the equivalent markup — `.lsx-to-navigation .lsx-to-content-spy`,
 *   listing Summary / Map / Information / Regions / Gallery / Tours / Reviews /
 *   Posts — and then switches it off: `.single .lsx-to-navigation { display:
 *   none !important }` (custom.css:1795). It has never been visible on a single.
 * - **Travel Information.** The plugin's template carries the ten-field
 *   travel-information accordion, and Botswana has every one of those fields
 *   populated. Live does not render it: `lsx_to_destination_travel_info()` is
 *   commented out at layout.php:169. Reproducing it would be adding a section
 *   nobody costed, to a page that has not shown it in years.
 *
 * ## Section grounds follow live, and live does not alternate here
 *
 * The summary band is tinted — `#collapse-summary .collapse-inner > .row` is
 * full-bleed `#f7f5f2` at 6.4rem (custom.css:2128), which is `neutral-200` and
 * the spacing-70 `is-style-tinted-page-section` already carries — and **the
 * tours shelf is tinted too, as of the 2026-09-03 dev import**. Everything else
 * sits on white: gallery, regions, accommodation and reviews.
 *
 * That is a design decision, not a translation. Live paints every section below
 * the summary on white (custom.css:1802 sets padding on `#gallery` only), so
 * the second tint is Zared's, taken in the Site Editor, and it is preserved
 * here as authored rather than argued back to live. It does mean the
 * destination single now alternates once, which is closer to
 * `patterns/template-single-tour.php` than the flat run this file previously
 * described.
 *
 * `require`, not `<!-- wp:pattern -->`, for every section and for the two
 * closing bands — a nested pattern reference inside another *pattern* is
 * dropped on front-end render while still resolving under a WP-CLI
 * `do_blocks()` test. That is also what makes the section partials safe to
 * share across the three templates. References inside a *query loop* are fine,
 * which is why the card patterns inside each shelf are still written as
 * references.
 * → .claude/skills/wp-pattern-runtime-pitfalls
 */

?>
<!-- wp:group {"tagName":"main","metadata":{"name":"Destination Single"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php
	require __DIR__ . '/destination-banner.php';
	require __DIR__ . '/breadcrumbs.php';
	require __DIR__ . '/destination-summary.php';
	require __DIR__ . '/destination-gallery.php';
	require __DIR__ . '/destination-regions.php';
	require __DIR__ . '/destination-accommodation.php';
	require __DIR__ . '/destination-tours.php';
	require __DIR__ . '/destination-reviews.php';

	/*
	 * The two closing bands, in live's order.
	 *
	 * `sd_lsx_to_destination_single_content_bottom()` ends with
	 * `sd_call_info_section( 'Not sure where to go? Chat to one of our safari
	 * gurus!' )` inside `.lsx-full-width-base-small`, then
	 * `sd_cta_why_choose_section()` (layout.php:208-215) — which is why the CTA
	 * comes first here and the Why Choose band last, the opposite way round from
	 * the destinations archive, where the same pair is emitted by
	 * `partials/footer-cta.php` in the other order. That heading is exactly the
	 * one `patterns/cta-not-sure-where-to-go.php` is named for, and the Why
	 * Choose band carries the Trustpilot score inside it.
	 *
	 * This is why `<main>` above carries no bottom padding: the Why Choose band
	 * brings its own, and a padding on the wrapper would show as a strip of page
	 * ground under a full-bleed section.
	 */
	require __DIR__ . '/cta-not-sure-where-to-go.php';
	require __DIR__ . '/why-choose-sd.php';
	?>

</main>
<!-- /wp:group -->
