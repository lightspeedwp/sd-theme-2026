<?php
/**
 * Title: Itinerary Stay
 * Slug: sd-theme-2026/itinerary-stay
 * Description: One row of the tour summary's itinerary list — a numbered marker beside the night count, the lodge and the destination. Repeated once per stay by Tour Operator's itinerary binding.
 * Categories: sd-theme-2026/tour-operator
 * Keywords: itinerary, tour, stay, night, lodge, summary
 * Viewport Width: 500
 * Block Types: core/group
 * Template Types: single
 * Post Types: tour
 * Inserter: false
 *
 * @package sd-theme-2026
 */

/*
 * What this replaces — live's `sd_lsx_itinerary_list()`.
 *
 * `#single-tour-summary .itinerary-data .itinerary-list .itinerary-item`,
 * drawn by sd-lsx-child/includes/layout.php:333 and measured from
 * /tour/botswana-victoria-falls-safari/ on 2026-08-28. Live renders a numbered
 * spine down the right-hand column of the tour summary: a filled circle
 * carrying the stay's index, a dashed rule joining it to the next, and beside
 * it the night count, the lodge and the destination.
 *
 * ## This file is the *repeated unit*, not the list
 *
 * The `lsx/tour-itinerary` binding on the group below is not an ordinary
 * binding. Tour Operator's `Bindings::render_itinerary_block()`
 * (includes/classes/blocks/class-bindings.php:306) takes the group's **entire
 * rendered output, wrapper included**, uses it as a template, and returns one
 * copy per itinerary row — so the `<div class="wp-block-group sd-itinerary__stay">`
 * you see here is what repeats. That is why this pattern is a single row and
 * why the list that holds it lives in the template.
 *
 * Field values are substituted by class, not by block binding:
 *
 * | Class on the block          | Filled with                            |
 * |-----------------------------|----------------------------------------|
 * | `itinerary-title`  (h1–h6)  | `lsx_to_itinerary_title()`             |
 * | `itinerary-accommodation`   | `lsx_to_itinerary_accommodation()`     |
 * | `itinerary-location`        | `lsx_to_itinerary_destinations()`      |
 *
 * Two conventions come with that mechanism and both are load-bearing:
 *
 *  - **The authored text must be exactly `Card Link`.** `maybe_get_prefix()`
 *    (class-bindings.php:453) reads the paragraph's existing content, strips
 *    the literal `Card Link` and keeps whatever is left as a prefix on the real
 *    value. Any other placeholder is silently prepended to every row. It is a
 *    machine token rather than display copy, so it is written plainly and is
 *    not translated — translating it would break the substitution in every
 *    locale but English.
 *  - **Each field sits in a group classed `itin-<field>-wrapper`.** When the
 *    field is empty, `build_itinerary_field()` rewrites that class to
 *    `hidden itin-<field>-wrapper`. `.hidden` is only given `display: none`
 *    inside `.lsx-itinerary-wrapper` (tour-operator/build/style.css, last
 *    rule), which is why the list in the template carries that class.
 *
 * ## The number is a CSS counter, not a value
 *
 * `render_itinerary_block()` passes a row index into `build_itinerary_field()`
 * and then never uses it, so there is no field to bind a number to. A CSS
 * counter on the repeated rows gives the same 1..N sequence with no PHP and no
 * plugin dependency, and it renumbers correctly when a row is hidden. The
 * circle, the number and the dashed spine are all in
 * assets/styles/core-group.css: every one of them needs `content:`, which a
 * block-style `css` field mangles. → AGENTS.md, "Styling lives in JSON"
 *
 * ## One row per stay, not one per day
 *
 * Tour Operator stores an itinerary as one row per day, so the binding would
 * repeat this pattern fourteen times on a fourteen-day tour. Live shows one row
 * per *stay* — consecutive days sharing a lodge merged, and the row labelled
 * with its night count.
 *
 * That merge is the plugin's, not the theme's: `SD\Enhancements\Itinerary`
 * (port-inventory item K-08) seeds the `$tour_itinerary` global with collapsed
 * rows on `render_block` at priority 9, one ahead of the binding, and rewrites
 * each row's stored `title` to "3 Nights". Tour Operator's renderer is
 * unmodified and this pattern is unchanged by it — `itinerary-title` is filled
 * from `lsx_to_itinerary_title()` either way. The authored text below is an
 * editor placeholder only, and is written as a night count so the editor
 * preview matches the front end.
 *
 * ## The country line — still missing, flagged
 *
 *  - Live's `.itinerary-country` came from
 *    `lsx_to_itinerary_country()`, which the child theme *redefined* over Tour
 *    Operator's own tag (M-09) and which 2.2 does not expose as an itinerary
 *    field. There is no binding for it, so the row stops at the destination.
 *    → .github/reports/sd-lsx-child-port-inventory-2026-08-12.md
 *
 * ## Type and colour
 *
 * Live sets the whole content column in the heading face at 19px/600 with an
 * 18px leading — font-size 300 (19.2px) at semi-bold with the snug line-height,
 * to the digit. Lodge links are `#cc7f16` (brand-500); the destination link is
 * `#60483b` (neutral-700), i.e. it is deliberately *not* accented, so the eye
 * follows the lodges down the spine. The heading is an `h3` because the column
 * it sits in is headed by an `h2`.
 */

?>
<!-- wp:group {"metadata":{"name":"Itinerary Stay","bindings":{"content":{"source":"lsx/tour-itinerary"}}},"className":"sd-itinerary__stay","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group sd-itinerary__stay">

	<!-- wp:group {"metadata":{"name":"Stay Detail"},"className":"sd-itinerary__detail","style":{"spacing":{"blockGap":"var:preset|spacing|5"},"typography":{"fontWeight":"var:custom|font-weight|semi-bold","lineHeight":"var:custom|line-height|snug"},"layout":{"selfStretch":"fill"}},"fontSize":"300","fontFamily":"heading","layout":{"type":"constrained","justifyContent":"left"}} -->
	<div class="wp-block-group sd-itinerary__detail has-heading-font-family has-300-font-size" style="font-weight:var(--wp--custom--font-weight--semi-bold);line-height:var(--wp--custom--line-height--snug)">

		<?php
		/*
		 * The night count and the lodge share a line, as live runs them
		 * together, so they are one wrapping flex row rather than two blocks in
		 * flow. Each keeps its own `itin-…-wrapper` so either can drop out on
		 * its own.
		 */
		?>
		<!-- wp:group {"metadata":{"name":"Stay Heading"},"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"top"}} -->
		<div class="wp-block-group">

			<!-- wp:group {"metadata":{"name":"Nights"},"className":"itin-title-wrapper","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"constrained","justifyContent":"left"}} -->
			<div class="wp-block-group itin-title-wrapper"><!-- wp:heading {"level":3,"className":"itinerary-title","style":{"typography":{"fontWeight":"var:custom|font-weight|semi-bold","lineHeight":"var:custom|line-height|snug","textTransform":"none","letterSpacing":"var:custom|letter-spacing|none"}},"textColor":"neutral-700","fontSize":"300"} -->
			<h3 class="wp-block-heading itinerary-title has-neutral-700-color has-text-color has-300-font-size" style="font-weight:var(--wp--custom--font-weight--semi-bold);letter-spacing:var(--wp--custom--letter-spacing--none);line-height:var(--wp--custom--line-height--snug);text-transform:none"><?php esc_html_e( '2 Nights', 'sd-theme-2026' ); ?></h3>
			<!-- /wp:heading --></div>
			<!-- /wp:group -->

			<!-- wp:group {"metadata":{"name":"Lodge"},"className":"itin-accommodation-wrapper","style":{"spacing":{"blockGap":"0"},"elements":{"link":{"color":{"text":"var:preset|color|brand-500"},":hover":{"color":{"text":"var:preset|color|brand-600"}}}}},"layout":{"type":"constrained","justifyContent":"left"}} -->
			<div class="wp-block-group itin-accommodation-wrapper has-link-color"><!-- wp:paragraph {"metadata":{"name":"Lodge Value"},"className":"itinerary-accommodation","style":{"spacing":{"margin":{"top":"0","bottom":"0"}}},"fontSize":"300"} -->
			<p class="itinerary-accommodation has-300-font-size" style="margin-top:0;margin-bottom:0">Card Link</p>
			<!-- /wp:paragraph --></div>
			<!-- /wp:group -->

		</div>
		<!-- /wp:group -->

		<!-- wp:group {"metadata":{"name":"Destination"},"className":"itin-location-wrapper","style":{"spacing":{"blockGap":"0"},"elements":{"link":{"color":{"text":"var:preset|color|neutral-700"},":hover":{"color":{"text":"var:preset|color|brand-600"}}}}},"textColor":"neutral-700","layout":{"type":"constrained","justifyContent":"left"}} -->
		<div class="wp-block-group itin-location-wrapper has-neutral-700-color has-text-color has-link-color"><!-- wp:paragraph {"metadata":{"name":"Destination Value"},"className":"itinerary-location","style":{"spacing":{"margin":{"top":"0","bottom":"0"}},"typography":{"fontWeight":"var:custom|font-weight|regular"}},"fontSize":"200"} -->
		<p class="itinerary-location has-200-font-size" style="margin-top:0;margin-bottom:0;font-weight:var(--wp--custom--font-weight--regular)">Card Link</p>
		<!-- /wp:paragraph --></div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
