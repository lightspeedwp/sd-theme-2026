<?php
/**
 * Title: Template: Single Tour
 * Slug: sd-theme-2026/template-single-tour
 * Description: The tour single — banner, the summary band with the itinerary spine, tour highlights, the gallery, the WETU map, connected reviews, the enquiry band, related tours and the Why Choose band.
 * Categories: sd-theme-2026/tour-operator
 * Keywords: tour, single, itinerary, safari, template
 * Viewport Width: 1400
 * Template Types: single
 * Post Types: wp_template
 * Inserter: false
 *
 * @package sd-theme-2026
 */

/*
 * Live's tour single, translated to blocks.
 *
 * Measured from /tour/botswana-victoria-falls-safari/ and
 * /tour/best-of-southern-africa/ on 2026-08-28, against
 * sd-lsx-child/includes/layout.php:220-390 (`sd_lsx_to_tour_single_layout()`,
 * `sd_lsx_to_tour_single_fast_facts()`, `sd_lsx_itinerary_list()`) and
 * sd-lsx-child/includes/functions.php:634 (`sd_lsx_to_tour_single_highlights()`).
 *
 * Section order is live's, and it is the order
 * .github/reports/live-site-audit-2026-08-12.md §3.3 records as the section
 * budget for this template:
 *
 *   banner → summary (content + expert | nights, price, validity, itinerary)
 *   → tour highlights → gallery → WETU map → connected reviews
 *   → "Tell us your trip ideas" → related tours → Why Choose → Trustpilot
 *
 * ## What this template does *not* carry, and why
 *
 * - **Tour Operator's sticky section menu.** The default TO template opens with
 *   `lsx-tour-operator/sticky-menu`. Live has that markup too — `.lsx-to-navigation
 *   .lsx-to-content-spy` — and then switches it off: `.single .lsx-to-navigation
 *   { display: none !important }` (custom.css:1795). It is a spy nav that has
 *   never been visible on a single. Porting it would be adding a component, not
 *   preserving one.
 * - **The breadcrumb bar.** Live draws Yoast's trail in a 58px `#ece9e3` strip
 *   under the banner. Breadcrumb output is a filter over a third-party plugin's
 *   trail — behaviour, not design — so by the deactivation test it is
 *   `sd-enhancements` work, exactly as recorded in
 *   patterns/template-archive-destination.php. When it lands it goes directly
 *   beneath the cover, outside it.
 * - **The 18 accommodation modals** the live page carries in its DOM. Tour
 *   Operator 2.2 renders its own `<dialog>` modals from `parts/modal-*.html`;
 *   the theme's half of that work is the heading rule in
 *   sd-enhancements/docs/blocks-and-bindings.md §8, and it belongs with the
 *   accommodation single, not here.
 * - **Price includes / excludes.** The TO default template carries an
 *   include/exclude panel. Live's tour single has none, and no SD tour has the
 *   `included` / `not_included` fields populated — the panel is Tour Operator's
 *   own composition, not SD's. Left out rather than shipped empty.
 *
 * ## Two sections that are live's but were open questions
 *
 * `patterns/why-choose-sd.php` and the enquiry band both say they are
 * deliberately not attached to a template yet, with placement open on LS-2033.
 * On the tour single that question is already answered by live:
 * `sd_lsx_to_tour_single_layout()` calls `sd_call_info_section()` and then
 * `sd_cta_why_choose_section()` in that order, inside the single's own content,
 * with the related-tours shelf between them. Both are attached here in that
 * order. This settles their placement on *this template only*; the archives are
 * still open.
 *
 * `require`, not `<!-- wp:pattern -->`, for both — a nested pattern reference
 * inside another *pattern* is dropped on front-end render while still resolving
 * under a WP-CLI `do_blocks()` test. References inside a *template* are fine,
 * and references inside a query loop are fine, which is why the two card
 * patterns below are still written as references.
 * → .claude/skills/wp-pattern-runtime-pitfalls
 *
 * ## Empty fields hide themselves, and that is a plugin contract
 *
 * Every optional section carries an `lsx-<field>-wrapper` class. Tour Operator's
 * `Query_Loop::maybe_hide_varitaion()` (includes/classes/blocks/class-query-loop.php:79)
 * matches `/(lsx|facts)-(.*?)-wrapper/` on a group's className and returns an
 * empty string when the field, taxonomy, map, gallery, itinerary or connected
 * query behind it is empty. So a tour with no gallery drops the gallery band
 * including its heading, and none of that is a theme conditional. The names are
 * the meta keys with underscores as dashes — `lsx-highlights-wrapper`,
 * `lsx-gallery-wrapper`, `lsx-itinerary-wrapper`,
 * `lsx-booking-validity-start-wrapper`.
 *
 * Note the one that is *not* a free choice: the itinerary list must carry
 * `lsx-itinerary-wrapper`, because Tour Operator only gives `.hidden`
 * `display: none` inside that class (tour-operator/build/style.css, final rule)
 * and that is how an itinerary row drops a field it has no value for.
 */

?>
<!-- wp:group {"tagName":"main","metadata":{"name":"Tour Single"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php
	/*
	 * The banner.
	 *
	 * `is-style-hero-banner` owns the ground, the scrim and the type colours, as
	 * it does on the destination archive. Two things differ from that placement
	 * and both are measured:
	 *
	 *  - **608px, not the style's 454px.** `body:not(.home) #lsx-banner
	 *    .page-banner-wrap .page-banner { min-height: 38rem }` from 768px up
	 *    (custom.css:324). The cover serialises `minHeight` as an inline style,
	 *    which outranks the style's `css` field, so this is one attribute rather
	 *    than a variant.
	 *  - **The image is the tour's banner image, not its featured image.** Tour
	 *    Operator's `Bindings::render_banner_block()` swaps a cover's background
	 *    for the `banner_image_id` meta whenever the cover carries an
	 *    `lsx/post-meta` binding — the args are only a marker; the key it reads
	 *    is fixed. `useFeaturedImage` stays on underneath as the fallback: a
	 *    tour with no banner image set keeps its thumbnail rather than
	 *    rendering an empty scrim.
	 *
	 * The title is the Joe Hand script line at 60px/200 that live gives every
	 * non-home banner (custom.css:366) — `is-style-script-accent` at font-size
	 * 800 — and the tagline is the heading face at 30px (custom.css:378), which is
	 * `is-style-subheading-large` plus the family and weight — medium (500)
	 * rather than live's 600, set on dev 2026-08-28. Exactly
	 * the pairing patterns/template-archive-destination.php uses, so the archive
	 * and the single read as one device.
	 *
	 * The tagline is `banner_subtitle` ("13 Nights"), reached through
	 * `sd/banner`'s `subtitle` key. Live gates it on post type —
	 * `.single #lsx-banner .banner-content .tagline { display: none }` with
	 * `.single-lsx-to-tour` putting it back (custom.css:1787) — which in a block
	 * theme is simply: it is in the tour template and not in the others.
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

			<!-- wp:post-title {"level":1,"metadata":{"name":"Tour Title"},"className":"is-style-script-accent","fontSize":"800"} /-->

			<!-- wp:paragraph {"metadata":{"name":"Tour Tagline","bindings":{"content":{"source":"sd/banner","args":{"key":"subtitle"}}}},"className":"is-style-subheading-large","style":{"typography":{"fontStyle":"normal","fontWeight":"var:custom|font-weight|medium"}},"fontFamily":"heading"} -->
			<p class="is-style-subheading-large has-heading-font-family" style="font-style:normal;font-weight:var(--wp--custom--font-weight--medium)"><?php esc_html_e( 'Nights', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:group -->

	</div></section>
	<!-- /wp:cover -->

	<?php
	/*
	 * The summary band.
	 *
	 * Live's `#collapse-summary .collapse-inner > .row` is full-bleed `#f7f5f2`
	 * at 6.4rem vertical padding (custom.css:2395) — neutral-200, which is what
	 * `is-style-tinted-page-section` paints, at the spacing-70 that band already
	 * carries.
	 *
	 * Two `col-md-6` columns. The left is the tour copy with the safari expert
	 * panel beneath it; the right is `#single-tour-summary`, which live caps at
	 * `max-width: 497px` (custom.css:2308). That cap is a `contentSize` on a
	 * constrained group, not CSS: core turns it into the group's own measure and
	 * the editor shows it.
	 *
	 * The expert panel is live's `#safari-expert-box`, which sits inside the
	 * tour's `.entry-content` on /tour/best-of-southern-africa/ and is absent on
	 * a tour with no consultant. `sd/safari-expert` reproduces that exactly — it
	 * renders nothing when it cannot resolve an expert — so there is no
	 * conditional here either.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Tour Summary"},"align":"full","className":"is-style-tinted-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-group alignfull is-style-tinted-page-section">

		<!-- wp:columns {"verticalAlignment":"top","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|60"}}}} -->
		<div class="wp-block-columns alignwide are-vertically-aligned-top">

			<!-- wp:column {"verticalAlignment":"top","style":{"spacing":{"blockGap":"var:preset|spacing|50"}}} -->
			<div class="wp-block-column is-vertically-aligned-top">

				<!-- wp:post-content {"layout":{"type":"constrained"}} /-->

				<?php require __DIR__ . '/safari-expert.php'; ?>

			</div>
			<!-- /wp:column -->

			<!-- wp:column {"verticalAlignment":"top","style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
			<div class="wp-block-column is-vertically-aligned-top">

				<!-- wp:group {"metadata":{"name":"Summary Card"},"className":"sd-tour-summary","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"497px","justifyContent":"left"}} -->
				<div class="wp-block-group sd-tour-summary">

					<?php
					/*
					 * "13 nights in total".
					 *
					 * Live derives it in PHP: `$nights = (int) $duration - 1`
					 * (layout.php:300-309), because Tour Operator's `duration`
					 * counts days and SD has always counted nights. That
					 * subtraction is `sd/post-meta`'s `nights` format, so the
					 * heading is one bound block with no theme PHP. The suffix
					 * is display copy and therefore lives here, in the theme's
					 * own translation call — which is the reason the plugin
					 * takes it as an argument rather than holding the string.
					 *
					 * `is-style-section-title-left` is live's `.lsx-title`
					 * with the gold rule flush left rather than centred
					 * (`.lsx-title.lsx-title-left:after`, custom.css:1014) —
					 * the variant that exists for exactly this placement,
					 * a section heading sharing a row with body copy.
					 *
					 * An `h2`: the banner title is the page's `h1` and this
					 * heads the first section under it.
					 */
					?>
					<!-- wp:heading {"level":2,"metadata":{"name":"Nights","bindings":{"content":{"source":"sd/post-meta","args":{"key":"duration","format":"nights","suffix":" nights in total"}}}},"className":"is-style-section-title-left","fontSize":"400"} -->
					<h2 class="wp-block-heading is-style-section-title-left has-400-font-size"><?php esc_html_e( 'Nights in total', 'sd-theme-2026' ); ?></h2>
					<!-- /wp:heading -->

					<?php
					/*
					 * The price. Live's `.starting-from-title` is the heading
					 * face at 24px semi-bold with the leading label a step
					 * smaller (custom.css:2313-2322).
					 *
					 * Bound through Tour Operator's own `lsx/post-meta` rather
					 * than `sd/post-meta`, because TO's source runs the value
					 * through `lsx_to_custom_field_query()`, which is what wraps
					 * the figure in the `.currency-icon` markup its stylesheet
					 * turns into the right symbol. The `amount` class is what
					 * that stylesheet keys off.
					 *
					 * No SD tour currently carries a price, so on today's data
					 * `lsx-price-wrapper` removes this row entirely. It is built
					 * because live builds it and the field is in the content
					 * model, not because it renders today.
					 */
					?>
					<!-- wp:group {"metadata":{"name":"Price"},"className":"lsx-price-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|10"},"typography":{"fontWeight":"var:custom|font-weight|semi-bold"}},"textColor":"brand-500","fontFamily":"heading","layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"bottom"}} -->
					<div class="wp-block-group lsx-price-wrapper has-brand-500-color has-text-color has-heading-font-family" style="font-weight:var(--wp--custom--font-weight--semi-bold)">

						<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0","bottom":"0"}}},"fontSize":"300"} -->
						<p class="has-300-font-size" style="margin-top:0;margin-bottom:0"><?php echo esc_html_x( 'Starting from', 'tour price label', 'sd-theme-2026' ); ?></p>
						<!-- /wp:paragraph -->

						<!-- wp:paragraph {"metadata":{"name":"Price Value","bindings":{"content":{"source":"lsx/post-meta","args":{"key":"price"}}}},"className":"amount","style":{"spacing":{"margin":{"top":"0","bottom":"0"}}},"fontSize":"400"} -->
						<p class="amount has-400-font-size" style="margin-top:0;margin-bottom:0"></p>
						<!-- /wp:paragraph -->

						<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0","bottom":"0"}}},"fontSize":"300"} -->
						<p class="has-300-font-size" style="margin-top:0;margin-bottom:0"><?php echo esc_html_x( 'p/p sharing', 'tour price basis', 'sd-theme-2026' ); ?></p>
						<!-- /wp:paragraph -->

					</div>
					<!-- /wp:group -->

					<?php
					/*
					 * The booking window — live's `.validity`, "Valid From November 1,
					 * 2019 to November 30, 2019" (layout.php:319).
					 *
					 * Four blocks rather than one, because the two dates are two meta
					 * rows and a binding replaces a block's whole content. The end date
					 * and its "to" sit in their own wrapper, so a tour with an open end
					 * date reads "Valid From 1 Nov 2026" and stops — which is what live
					 * does, its `$end_val` branch being nested inside the `$start_val`
					 * one.
					 *
					 * ⚠️ **Tour Operator's `lsx/post-meta`, not SD's.** `sd/post-meta`
					 * only reads meta that is registered with `show_in_rest`
					 * (Bindings::meta_is_readable()), and Tour Operator 2.2 registers
					 * `duration` and `price` but not `booking_validity_start` or
					 * `booking_validity_end` — verified on this install 2026-08-28, both
					 * return `NO`. TO's own source has no such gate and already formats
					 * these two keys as dates (`$date_transforms`,
					 * class-bindings.php:262), so it is both the working route and the
					 * one that keeps the date format in one place.
					 */
					?>
					<!-- wp:group {"metadata":{"name":"Booking Validity"},"className":"lsx-booking-validity-start-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"fontSize":"200","layout":{"type":"flex","flexWrap":"wrap"}} -->
					<div class="wp-block-group lsx-booking-validity-start-wrapper has-200-font-size">

						<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0","bottom":"0"}}},"fontSize":"200"} -->
						<p class="has-200-font-size" style="margin-top:0;margin-bottom:0"><?php echo esc_html_x( 'Valid From', 'tour booking window', 'sd-theme-2026' ); ?></p>
						<!-- /wp:paragraph -->

						<!-- wp:paragraph {"metadata":{"name":"Valid From","bindings":{"content":{"source":"lsx/post-meta","args":{"key":"booking_validity_start"}}}},"style":{"spacing":{"margin":{"top":"0","bottom":"0"}}},"fontSize":"200"} -->
						<p class="has-200-font-size" style="margin-top:0;margin-bottom:0"></p>
						<!-- /wp:paragraph -->

						<!-- wp:group {"metadata":{"name":"Valid To"},"className":"lsx-booking-validity-end-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
						<div class="wp-block-group lsx-booking-validity-end-wrapper">

							<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0","bottom":"0"}}},"fontSize":"200"} -->
							<p class="has-200-font-size" style="margin-top:0;margin-bottom:0"><?php echo esc_html_x( 'to', 'tour booking window', 'sd-theme-2026' ); ?></p>
							<!-- /wp:paragraph -->

							<!-- wp:paragraph {"metadata":{"name":"Valid To","bindings":{"content":{"source":"lsx/post-meta","args":{"key":"booking_validity_end"}}}},"style":{"spacing":{"margin":{"top":"0","bottom":"0"}}},"fontSize":"200"} -->
							<p class="has-200-font-size" style="margin-top:0;margin-bottom:0"></p>
							<!-- /wp:paragraph -->

						</div>
						<!-- /wp:group -->

					</div>
					<!-- /wp:group -->

					<?php
					/*
					 * The itinerary spine.
					 *
					 * This group is the *list*; the row inside it is
					 * patterns/itinerary-stay.php, which carries the
					 * `lsx/tour-itinerary` binding and is what repeats. The
					 * numbering is a CSS counter reset on this element — see
					 * that file, and assets/styles/core-group.css for the
					 * marker itself.
					 *
					 * `lsx-itinerary-wrapper` is doing two jobs and both are
					 * required: it hides the whole list on a tour with no
					 * itinerary, and it is the only scope in which Tour
					 * Operator gives `.hidden` `display: none`, which is how an
					 * individual row drops a field it has no value for.
					 *
					 * Live puts a 30px `margin-top` on `.itinerary-data`
					 * (custom.css:2334). It is not reproduced: this group sits inside a
					 * parent that carries a `blockGap`, where a child margin is inert on
					 * the front end and floors the gap control in the editor. The card's
					 * own gap owns the spacing instead — 20 rather than 30, which is the
					 * whole of the difference. → AGENTS.md, "spacing.margin belongs to
					 * page-section styles only"
					 */
					?>
					<!-- wp:group {"metadata":{"name":"Itinerary"},"className":"sd-itinerary lsx-itinerary-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","justifyContent":"left"}} -->
					<div class="wp-block-group sd-itinerary lsx-itinerary-wrapper">

						<?php require __DIR__ . '/itinerary-stay.php'; ?>

					</div>
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
	 * Tour highlights.
	 *
	 * The `highlights` field is a WYSIWYG holding a `<ul>`, so the band is one
	 * bound paragraph and the list arrives as markup. Three things follow from
	 * that and each is deliberate:
	 *
	 *  - **The key is `highlights`, not `hightlights`.** The field was stored
	 *    under a misspelling for the life of the old site
	 *    (sd-lsx-child/includes/functions.php:636); migration D-1 copies it onto
	 *    the spelling Tour Operator 2.2 registers, and dev is migrated. →
	 *    sd-enhancements/docs/data-migrations.md
	 *  - **A paragraph is the only block that can hold it.** Tour Operator's
	 *    `lsx/post-meta` answers for `core/paragraph`, `core/image` and
	 *    `core/cover` and nothing else, which is the same route the tour
	 *    include/exclude panels take on GoAfrica and ASNZ. WordPress 7.1 runs
	 *    the bound value through `wp_kses_post()`
	 *    (wp-includes/class-wp-block.php:412), so the `<ul>` survives.
	 *  - **The list ends up a *sibling* of that paragraph, not a child.** A
	 *    `<ul>` inside a `<p>` is not parseable HTML, so the browser closes the
	 *    paragraph before it. The wrapping group is therefore the only stable
	 *    place to hang the list's styling from, which is why
	 *    `is-style-highlights-list` is on the group and not on the paragraph.
	 *
	 * `lsx-highlights-wrapper` removes the band, heading included, on a tour
	 * with no highlights — verified locally 2026-08-28 against a tour with the
	 * field set and the same tour with it deleted.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Tour Highlights"},"align":"full","className":"is-style-light-page-section lsx-highlights-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"highlights"} -->
	<section class="wp-block-group alignfull is-style-light-page-section lsx-highlights-wrapper" id="highlights">

		<!-- wp:heading {"textAlign":"center","className":"is-style-section-title","anchor":"h-tour-highlights"} -->
		<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-tour-highlights"><?php esc_html_e( 'Tour Highlights', 'sd-theme-2026' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:group {"metadata":{"name":"Highlights List"},"align":"wide","className":"is-style-highlights-list","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group alignwide is-style-highlights-list"><!-- wp:paragraph {"metadata":{"name":"Highlights","bindings":{"content":{"source":"lsx/post-meta","args":{"key":"highlights"}}}},"fontSize":"200"} -->
		<p class="has-200-font-size"></p>
		<!-- /wp:paragraph --></div>
		<!-- /wp:group -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The gallery.
	 *
	 * Live's `#gallery` sits on `#f7f5f2` (custom.css:2420), so the band is the
	 * tinted one. The engine is Envira on live and Tour Operator's own gallery
	 * binding here — `Bindings::render_gallery_block()` reads the `gallery` meta
	 * and rebuilds the figure from it, discarding whatever image blocks are
	 * authored inside. The three below exist so the block has something to show
	 * in the editor; they are never rendered on the front end.
	 *
	 * ⚠️ **This is the placeholder pass, not the gallery build.** Live shows five
	 * images in a staggered grid with a "+N more" tile over the fifth, and
	 * opens a lightbox. This renders every image in a plain grid. The staggered
	 * layout, the overflow tile and the lightbox are a separate task with its
	 * own context; nothing here needs to change for them except this block.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Gallery"},"align":"full","className":"is-style-tinted-page-section lsx-gallery-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"gallery"} -->
	<section class="wp-block-group alignfull is-style-tinted-page-section lsx-gallery-wrapper" id="gallery">

		<!-- wp:heading {"textAlign":"center","className":"is-style-section-title","anchor":"h-gallery"} -->
		<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-gallery"><?php esc_html_e( 'Gallery', 'sd-theme-2026' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:gallery {"columns":3,"linkTo":"media","linkTarget":"_blank","sizeSlug":"large","align":"wide","metadata":{"name":"Tour Gallery","bindings":{"content":{"source":"lsx/gallery"}}},"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|10","left":"var:preset|spacing|10"}}}} -->
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
	 * The map.
	 *
	 * Live replaces Tour Operator's Google map with a WETU itinerary embed —
	 * `Frontend::lsx_to_map_override()` (port-inventory item F-02) pointing at
	 * `wetu.com/Itinerary/VI/{lsx_wetu_id}`. Tour Operator 2.2 does that itself:
	 * a group bound to `lsx/map` with `type: wetu` has its inner `<figure>`
	 * replaced with the iframe, built from the same `lsx_wetu_id` meta the WETU
	 * importer writes (class-bindings.php:509). Verified against dev, where the
	 * tour's stored id matches the one live's iframe carries.
	 *
	 * So the `core/image` inside is not decoration — it is the `<figure>` the
	 * plugin looks for. The placeholder ships with Tour Operator; addressing it
	 * by plugin path is the one URL here that is not environment-specific.
	 *
	 * Live runs the band edge to edge (`margin: 0 -9999rem`, custom.css:2425) on
	 * the page's own white ground. `lsx-wetu-map-wrapper` removes it when
	 * `lsx_to_has_map()` is false.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Tour Map"},"align":"full","className":"is-style-light-page-section lsx-wetu-map-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"tour-map"} -->
	<section class="wp-block-group alignfull is-style-light-page-section lsx-wetu-map-wrapper" id="tour-map">

		<!-- wp:group {"metadata":{"name":"WETU Map","bindings":{"content":{"source":"lsx/map","type":"wetu"}}},"align":"wide","className":"lsx-wetu-map","layout":{"type":"default"}} -->
		<div class="wp-block-group alignwide lsx-wetu-map"><!-- wp:image {"sizeSlug":"large","align":"wide","linkDestination":"none"} -->
		<figure class="wp-block-image alignwide size-large"><img src="/wp-content/plugins/tour-operator/assets/img/blocks/wetu-map-figme-prototype-image.png" alt=""/></figure>
		<!-- /wp:image --></div>
		<!-- /wp:group -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * Connected reviews.
	 *
	 * `lsx-review-related-tour-query` on the **post-template** — not on the
	 * query — is what Tour Operator's `Query_Loop::query_args_filter()` reads,
	 * because `query_loop_block_query_vars` is applied by
	 * `render_block_core_post_template()`. It rewrites the query to the posts in
	 * this tour's `review_to_tour` connection. The matching
	 * `…-query-wrapper` class on the group removes the section when there are
	 * none. Both are Tour Operator's conventions; the theme supplies no PHP.
	 *
	 * One slide at a time, as live does (`slidesToShow: 1`). Slick reads that
	 * count off the `columns-N` class `core/post-template` emits from its own
	 * `layout.columnCount`, so the grid layout below is both the carousel's
	 * setting and what the band degrades to with JavaScript off.
	 *
	 * A `<div>`, not a `<section>`. Live's `#review` has no heading — the
	 * carousel opens straight into the first quote — and a `<section>` with no
	 * accessible name is a landmark that announces itself and then says nothing.
	 * Keeping live's composition means keeping it out of the landmark tree.
	 */
	?>
	<!-- wp:group {"metadata":{"name":"Tour Reviews"},"align":"full","className":"is-style-light-page-section lsx-review-related-tour-query-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull is-style-light-page-section lsx-review-related-tour-query-wrapper">

		<!-- wp:query {"query":{"perPage":6,"pages":0,"offset":0,"postType":"review","order":"desc","orderBy":"date","search":"","exclude":[],"sticky":"","inherit":false},"hasCustomClass":true,"align":"wide","className":"is-style-slider-frame lsx-to-slider","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
		<div class="wp-block-query alignwide is-style-slider-frame lsx-to-slider">
			<!-- wp:post-template {"className":"lsx-review-related-tour-query","layout":{"type":"grid","columnCount":1}} -->
				<!-- wp:pattern {"slug":"sd-theme-2026/card-review-quote"} /-->
			<!-- /wp:post-template -->
		</div>
		<!-- /wp:query -->

	</div>
	<!-- /wp:group -->

	<?php
	/*
	 * The enquiry band. Live's `.lsx-full-width-base-small` wrapping
	 * `sd_call_info_section( "Tell us your trip ideas and we'll send you ours!" )`
	 * — the default of that function's five headings, and the one every Tour
	 * Operator single gets. See the head of this file for why it and the Why
	 * Choose band below are attached here rather than left unplaced.
	 */
	require __DIR__ . '/cta-tell-us-your-trip-ideas.php';
	?>

	<?php
	/*
	 * Related tours.
	 *
	 * "Other tours you might like" is live's heading, not "Related Tours" —
	 * `lsx_to_related_items()` is called with it verbatim (layout.php:281), and
	 * `global $columns = 3` is the three-up shelf.
	 *
	 * `excludeCurrent` keeps the tour being viewed out of its own shelf, and the
	 * page size is 15 rather than 9 — both set on dev 2026-08-28. The shelf shows
	 * three at a time either way; the count is how deep the carousel runs.
	 *
	 * Same query convention as the reviews above: `lsx-tour-related-tour-query`
	 * on the post-template, `…-wrapper` on the group. That key resolves through
	 * the tour's `tour` connection *and* its shared destinations, and Tour
	 * Operator disables the block when neither yields anything — which is why
	 * this shelf can be attached unconditionally.
	 *
	 * The tile is patterns/card-tour-compact.php, which already describes itself
	 * as the card these carousels carry. A `wp:pattern` reference inside a query
	 * loop resolves correctly — `core/pattern` has a server-side render callback
	 * and the post-template's per-post context reaches it.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Related Tours"},"align":"full","className":"is-style-light-page-section lsx-tour-related-tour-query-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"tours"} -->
	<section class="wp-block-group alignfull is-style-light-page-section lsx-tour-related-tour-query-wrapper" id="tours">

		<!-- wp:heading {"textAlign":"center","className":"is-style-section-title","anchor":"h-other-tours-you-might-like"} -->
		<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-other-tours-you-might-like"><?php esc_html_e( 'Other tours you might like', 'sd-theme-2026' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:query {"query":{"perPage":15,"pages":0,"offset":0,"postType":"tour","order":"asc","orderBy":"title","search":"","exclude":[],"sticky":"","inherit":false,"excludeCurrent":true},"hasCustomClass":true,"align":"wide","className":"is-style-slider-frame lsx-to-slider","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
		<div class="wp-block-query alignwide is-style-slider-frame lsx-to-slider">
			<!-- wp:post-template {"className":"lsx-tour-related-tour-query","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16px"}} -->
				<!-- wp:pattern {"slug":"sd-theme-2026/card-tour-compact"} /-->
			<!-- /wp:post-template -->
		</div>
		<!-- /wp:query -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The Why Choose band, with the Trustpilot score inside it —
	 * `sd_cta_why_choose_section()`, the last thing live's tour single renders
	 * before the footer.
	 */
	require __DIR__ . '/why-choose-sd.php';
	?>

</main>
<!-- /wp:group -->
