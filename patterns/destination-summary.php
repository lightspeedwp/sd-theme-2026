<?php
/**
 * Title: Destination — Summary Band
 * Slug: sd-theme-2026/destination-summary
 * Description: The tinted summary band: the destination copy with the safari expert panel beneath it in the left column, and Tour Operator's Google cluster map in the right.
 * Categories: sd-theme-2026/tour-operator
 * Keywords: destination, summary, map, expert, single
 * Viewport Width: 1400
 * Template Types: single
 * Post Types: wp_template
 * Inserter: false
 *
 * @package sd-theme-2026
 */

/*
 * The summary band.
 *
 * Live's two `col-md-6` columns inside `#collapse-summary`. The left is the
 * destination copy with the safari expert panel beneath it; the right is
 * `#map-box`, which holds the whole `#destination-map` section. Two equal
 * columns, as live has them — unlike the tour single, whose right column is
 * a 497px-capped fast-facts card.
 *
 * The expert panel is live's `#safari-expert-box`, which sits inside the
 * destination's `.entry-content` on /destination/botswana/ and carries the
 * Trustpilot badge under it — both are in `patterns/safari-expert.php`, and
 * `sd/safari-expert` renders nothing when it cannot resolve an expert, so
 * there is no conditional here.
 */
?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Destination Summary"},"align":"full","className":"is-style-tinted-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"summary"} -->
<section class="wp-block-group alignfull is-style-tinted-page-section" id="summary">

	<!-- wp:columns {"verticalAlignment":"top","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|60"}}}} -->
	<div class="wp-block-columns alignwide are-vertically-aligned-top">

		<!-- wp:column {"verticalAlignment":"top","style":{"spacing":{"blockGap":"var:preset|spacing|50"}}} -->
		<div class="wp-block-column is-vertically-aligned-top">

			<!-- wp:post-content {"layout":{"type":"constrained"}} /-->

			<?php require __DIR__ . '/safari-expert.php'; ?>

		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"top"} -->
		<div class="wp-block-column is-vertically-aligned-top">

			<?php
			/*
			 * The map — live's `#destination-map`, Tour Operator's Google
			 * cluster map rather than the tour single's WETU embed.
			 *
			 * This is Tour Operator's own `lsx-tour-operator/google-map` block
			 * variation, authored in the Site Editor on dev 2026-09-03
			 * (wp_template 65929) and imported here. The plugin registers it in
			 * src/blocks/google-map/index.js as a `core/group` variation with a
			 * fixed inner shape, and reproducing that shape is what makes the
			 * plugin's own JavaScript find it:
			 *
			 *   group.lsx-location-wrapper          ← the hide wrapper
			 *   └── group "Map Container"
			 *       ├── cover.lsx-map-preview       ← the click-to-load plate
			 *       └── group.hidden "Map Details"  ← the lsx/map binding
			 *
			 * **`lsx-location-wrapper`, not `lsx-google-map-wrapper`.** Both
			 * resolve through the same branch of
			 * `Query_Loop::maybe_hide_varitaion()` — `'location'`, `'google_map'`,
			 * `'wetu_map'`, `'wetu-map'` and `'google-map'` are all checked
			 * against `lsx_to_has_map()` at class-query-loop.php:213 — so the
			 * band still disappears on a destination with no `location` meta.
			 * The plugin's own class is used because maps.js keys off it too.
			 *
			 * **The preview plate is a lazy-load, and it is the plugin's.**
			 * maps.js's ready handler asks whether `.lsx-map`'s
			 * `.lsx-location-wrapper` ancestor contains a `.lsx-map-preview`:
			 * if it does it calls `watchMapTriggers()`, which binds
			 * `.lsx-map-preview a` click → `preventDefault()` →
			 * `getScript(google_url)` → `initThis()`; if it does not it
			 * initialises Google Maps on page load. So the `href="#"` is
			 * deliberate and wired upstream, Google Maps is not requested until
			 * somebody asks for the map, and this is not theme behaviour to
			 * reimplement. Removing the preview cover would switch the map back
			 * to loading eagerly.
			 *
			 * **"Map Details" carries the binding and stays empty.**
			 * `Bindings::render_map_block()` replaces that group's entire
			 * content with `lsx_to_map()`'s markup, so anything authored inside
			 * is discarded — hence no placeholder figure here, unlike the
			 * composition this replaces. `.hidden` keeps it out of the way until
			 * maps.js reveals it.
			 *
			 * ⚠️ **Two things carried over from the editor rather than chosen
			 * here.** The cover's `#e2f0f7` overlay is the plugin's own
			 * placeholder tint — it ships that hex in
			 * tour-operator/templates/single-accommodation.html and
			 * single-tour.html, and there is no theme token near it (the closest
			 * palette entry is `neutral-200`, a warm off-white, a different
			 * hue). It is kept verbatim so the plate matches the plugin, and it
			 * is the one raw hex in the theme's authored files. The dev-host
			 * absolute URL the editor wrote for the placeholder image is *not*
			 * kept: the plugin asset is addressed by root-relative plugin path,
			 * which is how Tour Operator's own templates write it and the one
			 * URL here that is not environment-specific. → flagged on LS-2033
			 *
			 * The plugin's variation also opens with a "Title" group — separator,
			 * a centred "Location" heading, separator. It is deliberately not
			 * here: live's `#destination-map` has no heading, and the summary
			 * band's right column is the map alone.
			 *
			 * ⚠️ **The map may still render empty on Tour Operator 2.2, and the
			 * fault is upstream.** `lsx_to_map()`
			 * (tour-operator/includes/template-tags/maps.php:66) builds `$map`
			 * and then executes a bare `return;` (line 233) — discarding the
			 * markup and ignoring `$echo` — whenever it takes that branch. The
			 * `return $before . $map . $after` that honours `$echo` sits below
			 * it and is unreachable. Measured on local 2026-08-31 against a
			 * seeded Botswana: `has_map=1 enabled=1 transient=array maplen=0`.
			 * Re-checked against TO 2.2 on 2026-09-03: the `return;` is still
			 * there. The blocks below are correct and stay as authored — when
			 * the upstream `return;` is fixed, or `sd-enhancements` answers TO's
			 * own `lsx_to_map_override` filter, the map appears with no change
			 * here. The preview plate renders either way, so the column is no
			 * longer empty while that is outstanding.
			 *
			 * ⚠️ **And that makes the plate a dead click on TO 2.2.** maps.js's
			 * whole ready handler is gated on `.lsx-map` existing —
			 * `jQuery(".lsx-map").length > 0 && ( … ? watchMapTriggers() :
			 * initThis() )` — so with the binding emitting nothing, no click
			 * handler is ever bound and "Click here to display the map" does
			 * nothing at all. Measured on local 2026-09-03 with `location` meta
			 * seeded on Botswana: `lsx_to_has_map()` returns true, the wrapper
			 * and the preview plate both render, and the "Map Details" group is
			 * removed entirely — `mapwrap=1 preview=1 maplink=1 mapdetails=0
			 * lsxmap=0`. The markup here is the plugin's own and is correct; the
			 * failure is one upstream `return;`. Worth resolving before launch
			 * either by patching Tour Operator or by answering
			 * `lsx_to_map_override` from `sd-enhancements`, because as it stands
			 * the page offers the reader a control that cannot work.
			 * → flagged on LS-2033
			 */
			?>
			<!-- wp:group {"tagName":"section","metadata":{"name":"Google Map"},"align":"full","className":"lsx-location-wrapper","layout":{"type":"constrained"}} -->
			<section class="wp-block-group alignfull lsx-location-wrapper">

				<!-- wp:group {"metadata":{"name":"Map Container"},"align":"wide","layout":{"type":"default"}} -->
				<div class="wp-block-group alignwide">

					<!-- wp:cover {"url":"/wp-content/plugins/tour-operator/assets/img/blocks/placeholder-map-1920x656.jpg","dimRatio":50,"customOverlayColor":"#e2f0f7","isUserOverlayColor":false,"isDark":false,"className":"lsx-map-preview","style":{"dimensions":{"aspectRatio":"1"}},"layout":{"type":"constrained"}} -->
					<div class="wp-block-cover is-light lsx-map-preview"><img class="wp-block-cover__image-background" alt="" src="/wp-content/plugins/tour-operator/assets/img/blocks/placeholder-map-1920x656.jpg" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim" style="background-color:#e2f0f7"></span><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"className":"has-text-align-center has-large-font-size","style":{"typography":{"textAlign":"center"}},"fontSize":"large"} -->
					<p class="has-text-align-center has-large-font-size"><a href="#"><?php esc_html_e( 'Click here to display the map', 'sd-theme-2026' ); ?></a></p>
					<!-- /wp:paragraph --></div></div>
					<!-- /wp:cover -->

					<!-- wp:group {"metadata":{"name":"Map Details","bindings":{"content":{"source":"lsx/map","type":"google"}}},"align":"wide","className":"hidden","layout":{"type":"default"}} -->
					<div class="wp-block-group alignwide hidden"></div>
					<!-- /wp:group -->

				</div>
				<!-- /wp:group -->

			</section>
			<!-- /wp:group -->

		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</section>
<!-- /wp:group -->
