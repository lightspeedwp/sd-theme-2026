<?php
/**
 * Title: Template: Single Region
 * Slug: sd-theme-2026/template-single-region
 * Description: The region single — the destination sections with the accommodation shelf and without the regions shelf: banner, the summary band pairing the region copy and the safari expert with the cluster map, the gallery, the accommodation and tour shelves and connected reviews, closing on the enquiry and Why Choose bands.
 * Categories: sd-theme-2026/tour-operator
 * Keywords: destination, region, single, accommodation, safari, template
 * Viewport Width: 1400
 * Template Types: single
 * Post Types: wp_template
 * Inserter: false
 *
 * @package sd-theme-2026
 */

/*
 * Live's *region* branch of the destination single, stated explicitly.
 *
 * Measured from /destination/botswana/chobe-national-park/ on 2026-08-31
 * against sd-lsx-child/includes/layout.php:166-215
 * (`sd_lsx_to_destination_single_content_bottom()`) and
 * sd-lsx-child/includes/template-tags.php:520-600, whose region arm renders
 * gallery → accommodation → specials → tours → reviews.
 *
 * ## What this file is, and when WordPress reaches it
 *
 * It is not a route. `single-region` is one of Tour Operator's two *assignable*
 * block templates — `lsx\blocks\Templates` registers it with
 * `post_types: [ destination ]`
 * (includes/classes/blocks/class-templates.php:89-99), so it appears in the
 * Template panel on a destination and an author picks it per post. Every
 * destination on live carries `destination_attribute: default`, so nothing
 * selects it today; `templates/single-destination.html` is what WordPress
 * resolves, and it serves a region correctly on its own.
 *
 * This exists so the branch can be *chosen* rather than inferred, and it is a
 * theme file rather than the plugin's default because a theme template of the
 * same slug replaces a registered one — `get_block_templates()` filters out any
 * registered template that has a theme file (block-template-utils.php:1231).
 * Before this, `templates/single-region.html` was Tour Operator's own default,
 * copied in wholesale by commit de58c9a: a Yoast breadcrumb strip on `primary`,
 * a gradient cover and the plugin's section set. None of that is live's design.
 *
 * ## The difference from the destination single is two `require` lines
 *
 * Every section is the same partial file, so there is one copy of each and no
 * second place to keep in step:
 *
 *   destination → banner · summary · gallery · regions · accommodation · tours · reviews
 *   region      → banner · summary · gallery ·         · accommodation · tours · reviews
 *
 * `destination-regions` is the omission, and it is the one section that would
 * have removed itself anyway: its `lsx-regions-query-wrapper` is checked
 * against `lsx_to_item_has_children( get_the_ID(), 'destination' )`
 * (class-query-loop.php:159), which is false for a region by definition — a
 * region is a leaf. Leaving it out is therefore the same output as the route
 * template, one query cheaper, and it says in the file what was previously only
 * true at runtime.
 *
 * `destination-accommodation` is kept and is the point of the page. Live's
 * `sd_lsx_to_region_accommodation()` heads it "Our Favourite {title}
 * Accommodations" (template-tags.php:524) and gates the whole section on
 * `! lsx_to_item_has_children()`, i.e. on the destination being a region. Here
 * the gate is the connection: `accommodation-related-destination` resolves
 * through the `accommodation_to_destination` meta, so a region with nothing
 * connected loses the band.
 *
 * ⚠️ **The specials shelf is still missing.** Live's region branch renders
 * `#special` between the accommodation and tour shelves — one full-width offer
 * panel per connected special, with the `special-badge-single.svg` plate over
 * it. The band is one `core/query` on `lsx-special-related-destination-query`,
 * but the tile does not exist yet: `styles/sections/cards/special-card.json` is
 * written and registered and no pattern uses it. When that card lands the
 * section becomes a `destination-specials` partial and is required directly
 * between `destination-accommodation` and `destination-tours` below. This is
 * the template on which its absence is visible, since live only ever showed it
 * on a region. → flagged on LS-2033
 *
 * Everything else — the banner and its `banner_image_id` binding, the tinted
 * summary band and its map caveat, the gallery placeholder pass, the flat white
 * grounds, the closing pair's order — is documented in the partial that carries
 * it and in `patterns/template-single-destination.php`. It is not restated here.
 *
 * `require`, not `<!-- wp:pattern -->`: a nested pattern reference inside
 * another *pattern* is dropped on front-end render while still resolving under
 * a WP-CLI `do_blocks()` test, which is exactly what makes the partials safe to
 * share across the three templates.
 * → .claude/skills/wp-pattern-runtime-pitfalls
 */

?>
<!-- wp:group {"tagName":"main","metadata":{"name":"Region Single"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php
	require __DIR__ . '/destination-banner.php';
	require __DIR__ . '/destination-summary.php';
	require __DIR__ . '/destination-gallery.php';
	require __DIR__ . '/destination-accommodation.php';
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
