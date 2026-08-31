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
 * ## One template, not two — and why the country/region branch disappears
 *
 * Live branches in PHP. `sd_lsx_to_destination_single_content_bottom()` asks
 * `lsx_to_item_has_children( get_the_ID(), 'destination' )` and renders one of
 * two section sets:
 *
 *   country  → gallery → regions → tours
 *   region   → gallery → accommodation → specials → tours → reviews
 *
 * In blocks that branch is not a conditional, it is the sections hiding
 * themselves. Every shelf below carries an `lsx-…-wrapper` class, and Tour
 * Operator's `Query_Loop::maybe_hide_varitaion()`
 * (includes/classes/blocks/class-query-loop.php:79) returns an empty string —
 * heading included — when the query behind it yields nothing. The `regions` key
 * is checked against `lsx_to_item_has_children()` by name (line 159), which is
 * *the same test live branches on*. So a country renders gallery → regions →
 * tours because its accommodation and review shelves are empty, and a region
 * renders gallery → accommodation → tours → reviews because its regions shelf
 * is. Both come out in live's order, from one template, with no theme PHP.
 *
 * That is also why this file is `single-destination` and not a pair.
 * Tour Operator registers `single-region` and `single-country` as *assignable*
 * block templates (class-templates.php:91-99, `post_types: [ destination ]`) —
 * they are a per-post override an author picks, not a route WordPress takes.
 * Nothing on live picks one: every destination carries
 * `destination_attribute: default`. Those two files in templates/ are still the
 * Tour Operator defaults that commit de58c9a copied in wholesale; they are not
 * touched here, and they are worth deleting once this template is signed off.
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
 *   specials archive's work, not this template's. When it lands, the section
 *   goes directly between "Accommodation" and "Related Tours" below and needs
 *   nothing else. → flagged on LS-2033
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
 * - **The breadcrumb bar.** Live draws Yoast's trail in a 58px `#ece9e3` strip
 *   under the banner. Breadcrumb output is a filter over a third-party plugin's
 *   trail — behaviour, not design — so it is `sd-enhancements` work, exactly as
 *   recorded in patterns/template-single-tour.php and
 *   patterns/template-archive-destination.php. When it lands it goes directly
 *   beneath the cover, outside it.
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
 * Only the summary band is tinted — `#collapse-summary .collapse-inner > .row`
 * is full-bleed `#f7f5f2` at 6.4rem (custom.css:2128), which is `neutral-200`
 * and the spacing-70 `is-style-tinted-page-section` already carries. Every
 * section below it sits on white. That is deliberately *not* the tinted/light
 * stripe `patterns/template-single-tour.php` runs, because the tour single's
 * gallery genuinely is tinted on live (custom.css:2420) and the destination's
 * is not (custom.css:1802 sets padding only). Preserved rather than
 * regularised. If the flat run is ever judged too flat, it is one className per
 * section, and it is a design decision rather than a translation.
 *
 * `require`, not `<!-- wp:pattern -->`, for the expert panel and the two
 * closing bands — a nested pattern reference inside another *pattern* is
 * dropped on front-end render while still resolving under a WP-CLI
 * `do_blocks()` test. References inside a *query loop* are fine, which is why
 * the four card patterns below are still written as references.
 * → .claude/skills/wp-pattern-runtime-pitfalls
 */

?>
<!-- wp:group {"tagName":"main","metadata":{"name":"Destination Single"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php
	/*
	 * The banner.
	 *
	 * The same device as the tour single, at the same 360px floor, so the two
	 * Tour Operator singles open identically: `is-style-hero-banner` owns the
	 * scrim and the type colours, and only the composition is here.
	 *
	 * The image is the destination's banner image, not its featured image. Tour
	 * Operator's `Bindings::render_banner_block()` (class-bindings.php:1144)
	 * swaps a cover's background for the `banner_image_id` meta whenever the
	 * cover carries an `lsx/post-meta` binding on `content` — the args are only
	 * a marker; the key it reads is fixed. Measured on dev: Botswana's
	 * `banner_image_id` is 58727, `header-botswana.jpg`, which is the image live
	 * paints into `.page-banner-image`. `useFeaturedImage` stays on underneath
	 * as the fallback, so a destination with no banner image set keeps its
	 * thumbnail rather than rendering an empty scrim.
	 *
	 * **No tagline.** The tour single carries one; this deliberately does not.
	 * Live gates it on post type — `.single #lsx-banner .banner-content .tagline
	 * { display: none }` with only `.single-lsx-to-tour` putting it back
	 * (custom.css:1787) — which in a block theme is simply: it is in the tour
	 * template and not in this one. Both destination banners measured carry the
	 * `<h1>` alone. `sd/banner`'s `subtitle` key would resolve here (for a region
	 * it returns the parent country's title), and that is exactly why the
	 * absence is written down rather than left to look like an oversight.
	 *
	 * A flow layout, not constrained, so `alignwide` reaches the children
	 * instead of being re-clamped to the content measure. Same reasoning as the
	 * destinations archive banner, which has the note in full.
	 */
	?>
	<!-- wp:cover {"useFeaturedImage":true,"dimRatio":0,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":360,"minHeightUnit":"px","contentPosition":"bottom center","isDark":false,"align":"full","tagName":"section","metadata":{"name":"Banner","bindings":{"content":{"source":"lsx/post-meta","args":{"key":"banner_image_id"}}}},"className":"is-style-hero-banner","style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-cover alignfull is-light has-custom-content-position is-position-bottom-center is-style-hero-banner" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);min-height:360px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container">

		<!-- wp:group {"metadata":{"name":"Banner Content"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:post-title {"level":1,"metadata":{"name":"Destination Title"},"className":"is-style-script-accent","fontSize":"800"} /-->

		</div>
		<!-- /wp:group -->

	</div></section>
	<!-- /wp:cover -->

	<?php
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

	<?php
	/*
	 * The gallery.
	 *
	 * Live's `#gallery` runs edge to edge on the page's own white ground
	 * (`padding: 65px 9999rem 45px`, custom.css:1802) — no tint, unlike the tour
	 * single's. The engine is Envira on live and Tour Operator's own gallery
	 * binding here: `Bindings::render_gallery_block()` reads the `gallery` meta
	 * and rebuilds the figure from it, discarding whatever image blocks are
	 * authored inside. The three below exist so the block has something to show
	 * in the editor; they are never rendered on the front end. Botswana's
	 * `gallery` meta holds twelve images, and all twelve render.
	 *
	 * ⚠️ **This is the placeholder pass, not the gallery build.** Live shows a
	 * staggered three-column grid capped at five visible tiles with a "+N more"
	 * overlay on the fifth, and opens an Envira lightbox. This renders every
	 * image in a plain grid, which is what was asked for now. The staggered
	 * layout, the overflow tile and the lightbox are a separate task; nothing
	 * here needs to change for them except this block.
	 *
	 * `lsx-gallery-wrapper` drops the band, heading included, when the meta is
	 * not an array — `maybe_hide_varitaion()`, `'gallery'` branch.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Gallery"},"align":"full","className":"is-style-light-page-section lsx-gallery-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"gallery"} -->
	<section class="wp-block-group alignfull is-style-light-page-section lsx-gallery-wrapper" id="gallery">

		<!-- wp:heading {"textAlign":"center","className":"is-style-section-title","anchor":"h-gallery"} -->
		<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-gallery"><?php esc_html_e( 'Gallery', 'sd-theme-2026' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:gallery {"columns":3,"linkTo":"media","linkTarget":"_blank","sizeSlug":"large","align":"wide","metadata":{"name":"Destination Gallery","bindings":{"content":{"source":"lsx/gallery"}}},"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|10","left":"var:preset|spacing|10"}}}} -->
		<figure class="wp-block-gallery alignwide has-nested-images columns-3 is-cropped"><!-- wp:image {"linkDestination":"media"} -->
		<figure class="wp-block-image"><img alt=""/></figure>
		<!-- /wp:image -->

		<!-- wp:image {"linkDestination":"media"} -->
		<figure class="wp-block-image"><img alt=""/></figure>
		<!-- /wp:image -->

		<!-- wp:image {"linkDestination":"media"} -->
		<figure class="wp-block-image"><img alt=""/></figure>
		<!-- /wp:image --></figure>
		<!-- /wp:gallery -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The regions shelf — live's `#regions`, the country branch's first section.
	 *
	 * "Popular Travel Destinations in Botswana" is live's heading verbatim:
	 * `$regions_page_title = 'Popular Travel Destinations in ' . $post->post_title`
	 * (layout.php:180). A binding replaces a block's whole `content`, so the
	 * standing half cannot be static text beside a bound span — `sd/post-field`'s
	 * `prefix` arg carries it, which is the case that source's docblock was
	 * written for. The authored fallback is what shows in the editor and on any
	 * render where the binding cannot resolve a post.
	 *
	 * `lsx-regions-query` is the one shelf here that reads no connection meta:
	 * `query_args_filter()` sets `post_parent__in => [ get_the_ID() ]`
	 * (class-query-loop.php:378), so it is literally the destination's children.
	 * The matching wrapper is checked against
	 * `lsx_to_item_has_children( get_the_ID(), 'destination' )` by name
	 * (line 159) — the same test live's PHP branches on — so this band is
	 * present on a country and gone on a region without a conditional.
	 *
	 * The tile is `patterns/card-media-overlay.php`: the square photograph with
	 * the linked title over a scrim, the same tile the destinations archive
	 * grids. Live's own region card is an image over a white panel with an
	 * excerpt and a "View more"; the overlay tile is Zared's call for this
	 * shelf, and it makes the regions on a country read as the same object as
	 * the countries on /destinations/.
	 *
	 * Three across, as live's `slidesToShow: 3`. Slick reads that count off the
	 * `columns-N` class `core/post-template` emits from its own
	 * `layout.columnCount`, so the grid layout is both the carousel's setting
	 * and what the shelf degrades to with JavaScript off.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Regions"},"align":"full","className":"is-style-light-page-section lsx-regions-query-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"regions"} -->
	<section class="wp-block-group alignfull is-style-light-page-section lsx-regions-query-wrapper" id="regions">

		<!-- wp:heading {"textAlign":"center","metadata":{"name":"Regions Heading","bindings":{"content":{"source":"sd/post-field","args":{"field":"title","prefix":"<?php esc_attr_e( 'Popular Travel Destinations in ', 'sd-theme-2026' ); ?>"}}}},"className":"is-style-section-title","anchor":"h-regions"} -->
		<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-regions"><?php esc_html_e( 'Popular Travel Destinations', 'sd-theme-2026' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:query {"query":{"perPage":15,"pages":0,"offset":0,"postType":"destination","order":"asc","orderBy":"title","search":"","exclude":[],"sticky":"","inherit":false},"hasCustomClass":true,"align":"wide","className":"is-style-slider-frame lsx-to-slider","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
		<div class="wp-block-query alignwide is-style-slider-frame lsx-to-slider">
			<!-- wp:post-template {"className":"lsx-regions-query","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16px"}} -->
				<!-- wp:pattern {"slug":"sd-theme-2026/card-media-overlay"} /-->
			<!-- /wp:post-template -->
		</div>
		<!-- /wp:query -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The accommodation shelf — live's `#accommodation`, the region branch's
	 * first section, "Our Favourite Chobe National Park Accommodations".
	 * `sd_lsx_to_region_accommodation()` composes that heading from the post
	 * title (template-tags.php:524) and gates the whole section on
	 * `! lsx_to_item_has_children()`, i.e. on the destination being a region.
	 *
	 * Here it is gated instead by there being connected accommodation:
	 * `accommodation-related-destination` resolves through the
	 * `accommodation_to_destination` meta, and a country carries that meta too —
	 * Botswana lists 93. So this band would appear on a country where live hides
	 * it. Left in, deliberately: the shelf is correct content either way, it is
	 * the country page's most useful outbound link after its regions, and the
	 * alternative is a theme conditional reproducing a branch the plugin already
	 * expresses better. Flagged rather than filtered — if it should match live
	 * exactly, that is a `parents-only`-style key on the plugin side, not PHP
	 * here. → LS-2033
	 *
	 * The tile is `patterns/card-accommodation-compact.php`, which already
	 * describes itself as the card these carousels carry. Three across, as
	 * live's `slidesToShow: 3`.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Accommodation"},"align":"full","className":"is-style-light-page-section lsx-accommodation-related-destination-query-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"accommodation"} -->
	<section class="wp-block-group alignfull is-style-light-page-section lsx-accommodation-related-destination-query-wrapper" id="accommodation">

		<!-- wp:heading {"textAlign":"center","metadata":{"name":"Accommodation Heading","bindings":{"content":{"source":"sd/post-field","args":{"field":"title","prefix":"<?php esc_attr_e( 'Our Favourite ', 'sd-theme-2026' ); ?>","suffix":"<?php esc_attr_e( ' Accommodations', 'sd-theme-2026' ); ?>"}}}},"className":"is-style-section-title","anchor":"h-accommodation"} -->
		<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-accommodation"><?php esc_html_e( 'Our Favourite Accommodations', 'sd-theme-2026' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:query {"query":{"perPage":15,"pages":0,"offset":0,"postType":"accommodation","order":"asc","orderBy":"title","search":"","exclude":[],"sticky":"","inherit":false},"hasCustomClass":true,"align":"wide","className":"is-style-slider-frame lsx-to-slider","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
		<div class="wp-block-query alignwide is-style-slider-frame lsx-to-slider">
			<!-- wp:post-template {"className":"lsx-accommodation-related-destination-query","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16px"}} -->
				<!-- wp:pattern {"slug":"sd-theme-2026/card-accommodation-compact"} /-->
			<!-- /wp:post-template -->
		</div>
		<!-- /wp:query -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The tours shelf — live's `#tours`, "Botswana Tours to Inspire You".
	 * `sd_lsx_to_destination_tours()` composes it as
	 * `$post->post_title . ' Tours to Inspire You'` (template-tags.php:589), so
	 * the standing half is a `suffix` here rather than a prefix.
	 *
	 * This is the one shelf both live branches render, which is why it sits
	 * below the two that are exclusive to one branch each and above the reviews
	 * that only a region gets. That is live's order in both directions:
	 * country → regions → tours, region → accommodation → tours → reviews.
	 *
	 * The tile is `patterns/card-tour-compact.php` — the compact tour card, the
	 * same one the tour single's related shelf carries, so a tour looks the same
	 * wherever it is shelved. Three across, as live's `slidesToShow: 3`.
	 * `perPage` is 15 rather than 3: the shelf shows three at a time either way,
	 * the count is how deep the carousel runs, and Botswana connects 13 tours.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Related Tours"},"align":"full","className":"is-style-light-page-section lsx-tour-related-destination-query-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"tours"} -->
	<section class="wp-block-group alignfull is-style-light-page-section lsx-tour-related-destination-query-wrapper" id="tours">

		<!-- wp:heading {"textAlign":"center","metadata":{"name":"Tours Heading","bindings":{"content":{"source":"sd/post-field","args":{"field":"title","suffix":"<?php esc_attr_e( ' Tours to Inspire You', 'sd-theme-2026' ); ?>"}}}},"className":"is-style-section-title","anchor":"h-tours"} -->
		<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-tours"><?php esc_html_e( 'Tours to Inspire You', 'sd-theme-2026' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:query {"query":{"perPage":15,"pages":0,"offset":0,"postType":"tour","order":"asc","orderBy":"title","search":"","exclude":[],"sticky":"","inherit":false},"hasCustomClass":true,"align":"wide","className":"is-style-slider-frame lsx-to-slider","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
		<div class="wp-block-query alignwide is-style-slider-frame lsx-to-slider">
			<!-- wp:post-template {"className":"lsx-tour-related-destination-query","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16px"}} -->
				<!-- wp:pattern {"slug":"sd-theme-2026/card-tour-compact"} /-->
			<!-- /wp:post-template -->
		</div>
		<!-- /wp:query -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * Connected reviews — live's `#review`.
	 *
	 * A `<div>`, not a `<section>`, and no heading. Live's `#review` has none —
	 * the carousel opens straight into the first quote — and a `<section>` with
	 * no accessible name is a landmark that announces itself and then says
	 * nothing. Same composition, and same reasoning, as the tour single's
	 * reviews band.
	 *
	 * One slide at a time, as live does (`slidesToShow: 1`).
	 * `review-related-destination` resolves through the destination's
	 * `review_to_destination` meta — 11 on Botswana — and the wrapper removes
	 * the band where there are none.
	 */
	?>
	<!-- wp:group {"metadata":{"name":"Destination Reviews"},"align":"full","className":"is-style-light-page-section lsx-review-related-destination-query-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull is-style-light-page-section lsx-review-related-destination-query-wrapper">

		<!-- wp:query {"query":{"perPage":6,"pages":0,"offset":0,"postType":"review","order":"desc","orderBy":"date","search":"","exclude":[],"sticky":"","inherit":false},"hasCustomClass":true,"align":"wide","className":"is-style-slider-frame lsx-to-slider","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
		<div class="wp-block-query alignwide is-style-slider-frame lsx-to-slider">
			<!-- wp:post-template {"className":"lsx-review-related-destination-query","layout":{"type":"grid","columnCount":1}} -->
				<!-- wp:pattern {"slug":"sd-theme-2026/card-review-quote"} /-->
			<!-- /wp:post-template -->
		</div>
		<!-- /wp:query -->

	</div>
	<!-- /wp:group -->

	<?php
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
