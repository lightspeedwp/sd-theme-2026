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
			 * Two nested groups, and the nesting is load-bearing. The inner
			 * one carries the `lsx/map` binding: `Bindings::render_map_block()`
			 * (class-bindings.php:957) replaces that group's *entire*
			 * content with `lsx_to_map()`'s markup and copies the group's
			 * first class string onto the emitted `.lsx-map`, so it has to
			 * stay bare. The outer one carries
			 * `lsx-google-map-wrapper`, which `maybe_hide_varitaion()`
			 * resolves through its `'google-map'` branch to
			 * `lsx_to_has_map()` and removes when the destination has no
			 * `location` meta. Putting both on one group would leave the two
			 * `render_block` filters — both at priority 10 — racing for the
			 * same markup.
			 *
			 * Every SD destination measured has `location` populated
			 * (Botswana: lat -21.667639, long 24.312744, zoom 9), so the
			 * wrapper is insurance rather than a live branch; a destination
			 * without one drops the map and leaves the column empty rather
			 * than rendering a grey placeholder.
			 *
			 * ⚠️ **The map renders empty on Tour Operator 2.2, and the fault
			 * is upstream.** `lsx_to_map()`
			 * (tour-operator/includes/template-tags/maps.php:66) opens by
			 * reading the `{post_id}_location` transient, and when it finds
			 * one it builds `$map` and then executes a bare `return;`
			 * (line 233) — discarding the markup and ignoring `$echo`
			 * entirely. The `return $before . $map . $after` that honours
			 * `$echo` is below that block and unreachable whenever the
			 * transient is warm. It always is: `render_map_block()` calls
			 * `lsx_to_has_map()` first, and that function's last act is to
			 * `set_transient()` the args it just computed. So the google
			 * branch of the map binding returns null for every caller, not
			 * just this one.
			 *
			 * Measured on local 2026-08-31 against a seeded Botswana:
			 * `has_map=1 enabled=1 transient=array maplen=0`. The blocks
			 * below are correct and stay as authored — when the upstream
			 * `return;` is fixed, or `sd-enhancements` answers TO's own
			 * `lsx_to_map_override` filter, the map appears with no change
			 * here. Until then the right column renders empty.
			 * → flagged on LS-2033
			 *
			 * The `core/image` inside is the figure the plugin looks for in
			 * its WETU branch and harmless in this one; it is what the
			 * editor shows in place of a map it cannot draw. The placeholder
			 * ships with Tour Operator, so addressing it by plugin path is
			 * the one URL here that is not environment-specific.
			 */
			?>
			<!-- wp:group {"metadata":{"name":"Map"},"className":"lsx-google-map-wrapper","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
			<div class="wp-block-group lsx-google-map-wrapper">

				<!-- wp:group {"metadata":{"name":"Destination Map","bindings":{"content":{"source":"lsx/map","type":"google"}}},"className":"lsx-map","layout":{"type":"default"}} -->
				<div class="wp-block-group lsx-map"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
				<figure class="wp-block-image size-large"><img src="/wp-content/plugins/tour-operator/assets/img/placeholders/placeholder-map-1170x400.jpg" alt=""/></figure>
				<!-- /wp:image --></div>
				<!-- /wp:group -->

			</div>
			<!-- /wp:group -->

		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</section>
<!-- /wp:group -->
