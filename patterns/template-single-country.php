<?php
/**
 * Title: Template: Single Country
 * Slug: sd-theme-2026/template-single-country
 * Description: The country single — the destination sections with the regions shelf and without the accommodation shelf: banner, the summary band pairing the country copy and the safari expert with the cluster map, the gallery, the regions and tour shelves and connected reviews, closing on the enquiry and Why Choose bands.
 * Categories: sd-theme-2026/tour-operator
 * Keywords: destination, country, single, regions, safari, template
 * Viewport Width: 1400
 * Template Types: single
 * Post Types: wp_template
 * Inserter: false
 *
 * @package sd-theme-2026
 */

/*
 * Live's *country* branch of the destination single, stated explicitly.
 *
 * Measured from /destination/botswana/ on 2026-08-31 against
 * sd-lsx-child/includes/layout.php:166-215
 * (`sd_lsx_to_destination_single_content_bottom()`), whose country arm renders
 * gallery → regions → tours and nothing else.
 *
 * ## What this file is, and when WordPress reaches it
 *
 * It is not a route. `single-country` is one of Tour Operator's two
 * *assignable* block templates — `lsx\blocks\Templates` registers it with
 * `post_types: [ destination ]`
 * (includes/classes/blocks/class-templates.php:89-99), so it appears in the
 * Template panel on a destination and an author picks it per post. Every
 * destination on live carries `destination_attribute: default`, so nothing
 * selects it today; `templates/single-destination.html` is what WordPress
 * resolves, and it serves a country correctly on its own.
 *
 * This exists so the branch can be *chosen* rather than inferred, and it is a
 * theme file rather than the plugin's default because a theme template of the
 * same slug replaces a registered one — `get_block_templates()` filters out any
 * registered template that has a theme file (block-template-utils.php:1231).
 * Before this, `templates/single-country.html` was Tour Operator's own default,
 * copied in wholesale by commit de58c9a: a Yoast breadcrumb strip on `primary`,
 * a gradient cover and the plugin's section set. None of that is live's design.
 *
 * ## The difference from the destination single is two `require` lines
 *
 * Every section is the same partial file, so there is one copy of each and no
 * second place to keep in step:
 *
 *   destination → banner · breadcrumbs · summary · gallery · regions · accommodation · tours · reviews
 *   country     → banner · breadcrumbs · summary · gallery · regions ·               · tours · reviews
 *
 * `destination-accommodation` is the omission. On the route template that shelf
 * hides itself on a destination with no `accommodation_to_destination` meta, but
 * a country carries that meta too — Botswana lists 93 — so it renders there
 * where live hides it. That is the deviation
 * `patterns/template-single-destination.php` flagged on LS-2033, and this
 * template is the answer to it: assign Single Country to a country and the
 * shelf is gone, because the section is not in the file.
 *
 * `destination-regions` is kept and is the point of the page. It reads no
 * connection meta — `Query_Loop::query_args_filter()` sets
 * `post_parent__in => [ get_the_ID() ]` (class-query-loop.php:378), i.e. the
 * country's children — and its `lsx-regions-query-wrapper` is checked against
 * `lsx_to_item_has_children( get_the_ID(), 'destination' )`, the same test
 * live's PHP branches on. A country with no child regions still loses the band;
 * that is the plugin's gate, not a bug in this file, and it is the right
 * behaviour for a country whose regions have not been published yet.
 *
 * ⚠️ **Connected reviews are kept, and live's country branch has none.**
 * `sd_lsx_to_post_type_reviews()` is called only from the region arm, so live
 * shows no reviews on /destination/botswana/ even though Botswana connects 11.
 * Keeping the band is Zared's instruction for this pair — the two differences
 * asked for were the regions and accommodation shelves — and the band is
 * correct content either way. If it should match live's branch exactly, it is
 * the `destination-reviews` line below and nothing else. Specials are not here
 * for the same reason they are nowhere yet: the tile does not exist.
 * → LS-2033
 *
 * Everything else — the banner and its `banner_image_id` binding, the breadcrumb
 * strip, the tinted summary band, Tour Operator's `google-map` variation and its
 * upstream caveat, the gallery placeholder pass, the tinted tours shelf, the
 * closing pair's order — is documented in the partial that carries it and in
 * `patterns/template-single-destination.php`. It is not restated here. Because
 * every one of those is a shared partial, the Site Editor changes imported from
 * dev on 2026-09-03 — the breadcrumb band, the map, the tours ground — landed on
 * this template at the same time and by construction, not by being copied.
 *
 * `require`, not `<!-- wp:pattern -->`: a nested pattern reference inside
 * another *pattern* is dropped on front-end render while still resolving under
 * a WP-CLI `do_blocks()` test, which is exactly what makes the partials safe to
 * share across the three templates.
 * → .claude/skills/wp-pattern-runtime-pitfalls
 */

?>
<!-- wp:group {"tagName":"main","metadata":{"name":"Country Single"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php
	require __DIR__ . '/destination-banner.php';
	require __DIR__ . '/destination-breadcrumbs.php';
	require __DIR__ . '/destination-summary.php';
	require __DIR__ . '/destination-gallery.php';
	require __DIR__ . '/destination-regions.php';
	require __DIR__ . '/destination-tours.php';
	require __DIR__ . '/destination-reviews.php';

	/*
	 * The two closing bands, in live's order — the CTA first, the Why Choose
	 * band last, as `sd_lsx_to_destination_single_content_bottom()` emits them
	 * (layout.php:208-215). This is why `<main>` above carries no bottom
	 * padding: the Why Choose band brings its own.
	 */
	require __DIR__ . '/cta-not-sure-where-to-go.php';
	require __DIR__ . '/why-choose-sd.php';
	?>

</main>
<!-- /wp:group -->
