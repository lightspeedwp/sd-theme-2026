<?php
/**
 * Title: Template: Specials Archive
 * Slug: sd-theme-2026/template-archive-special
 * Description: The Specials landing page — the photographic banner and introduction, then one full-width offer band per special carrying the property photograph as its ground, the offer name, its connections and its complete terms, closing on the enquiry band and the value panel.
 * Categories: hidden
 * Keywords: specials, offers, deals, archive, landing, tour operator, banner, band
 * Template Types: archive
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 *
 * @package sd-theme-2026
 */

/*
 * Ported from https://www.southerndestinations.com/specials/, measured
 * 2026-09-17. Live is a list, not a grid: one full-width band per offer, the
 * property photograph carried as the band's own ground with the offer name and
 * its terms set over it, four to a page. The three-column card grid that stood
 * in `templates/archive-special.html` until now was scaffolding, not a port of
 * anything, and it is replaced here rather than refined.
 *
 * ## The template is a shell and this file is the page
 *
 * `templates/archive-special.html` is now four lines — header, this pattern,
 * footer — matching `archive-tour.html`, `archive-destination.html`,
 * `archive-accommodation.html` and `archive-team.html`. The `<main>` landmark
 * moved here with the composition; shipping one in each is the specific failure
 * mode, and the page would then carry two.
 *
 * ## The query asserts nothing
 *
 * `inherit: true` and the post type, and deliberately no `perPage`, `order`,
 * `orderBy` or meta query. Count, ordering and the validity window are all
 * decided by `SD\Enhancements\Queries::limit_specials_archive()`. A `perPage`
 * here would be silently ignored while inheriting and would mislead the next
 * reader into thinking this file controls the list, so there is not one.
 *
 * That is the deactivation test, not a preference: a page that shows the right
 * offers only because a theme file filtered the query is exactly the coupling
 * the rebuild exists to remove.
 *
 * ## The band's anchor is not authored here
 *
 * The site links to offers as `/specials/#special-{slug}` rather than to a page
 * of their own, so each band needs `id="special-{slug}"`. That is per rendered
 * post, and this pattern renders once inside the `post-template` — so it cannot
 * be an `anchor` attribute. `SD\Enhancements\Specials::add_band_anchor()`
 * injects it at render time, keyed off the `is-style-special-card` class on the
 * band group below. **Do not rename that class without changing the module.**
 *
 * ## Why the band is a featured image and not a bound one
 *
 * FR-009 asks for the offer's banner image. Measured 2026-09-17: Tour Operator
 * registers a render filter for `render_block_core/cover` and for no other
 * image-bearing block, so binding `core/image` to `lsx/post-meta` →
 * `banner_image_id` returns a bare attachment ID into the block and renders
 * nothing usable. And on dev **none** of the 42 migrated offers carries a
 * `banner_image_id` row at all — `image_group` holds an empty `banner_image` —
 * while 41 of the 42 carry a featured image. `core/post-featured-image` is
 * therefore both the working route and the one the data supports, and it keeps
 * the image/absolute-body structure `styles/sections/cards/special-card.json`
 * was written against. Recorded on the register as a correction to FR-009.
 *
 * ## The meta rows hide themselves, and needed help to
 *
 * Elsewhere in this theme a connection row carries an `lsx-<key>-wrapper`
 * class, which is Tour Operator's hook rather than a styling class:
 * `Query_Loop::maybe_hide_varitaion()` matches it and empties the block when
 * the key is empty. **These rows deliberately do not**, and the reasoning is in
 * `Queries::hide_empty_connection_row()`: that helper counts `draft` as
 * existing, and its `post_ids_exist()` passes a whole ID list through one `%s`
 * placeholder, so any row with two or more connections is hidden outright.
 * Measured on local 2026-09-17. `Queries::omit_unpublished_connections()` and
 * `Queries::hide_empty_connection_row()` do both jobs instead, and they are in
 * the plugin because status is behaviour.
 *
 * `core/post-terms` needs none of this — core renders nothing at all when the
 * post has no terms in the taxonomy.
 *
 * ## The enquire button is not finished
 *
 * It opens the shared modal through the existing `#to-modal-enquiry` trigger
 * convention, which is all `SD\Enhancements\Enquiry::register_trigger_modal()`
 * needs. The per-band attribute naming *which* offer it is about is **not**
 * emitted yet: whether the form's field takes the offer's name, its slug or its
 * post ID is the line 15/16 owner's decision and it was still open when this
 * shipped. Until it lands the button is complete and inert on that one point,
 * per the enquiry-trigger contract's degraded-behaviour clause — SC-007 is
 * verified when that surface arrives, not waived.
 */

?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Specials Archive"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php
	/*
	 * The banner. Same composition as the other three Tour Operator landing
	 * pages — see template-archive-destination.php for the reasoning on
	 * `dimRatio`, the decorative `alt` and the flow-layout content group.
	 *
	 * The image is live's own `banner-specials-1920x454.jpg`, written out as a
	 * literal uploads URL against the dev host: that is the one exception to
	 * "no per-install values" in this theme, because the go-live deployment
	 * runs a find-and-replace over the host and an indirection would still be
	 * a hardcoded dev host, one step further away.
	 *
	 * The heading and the tagline are bound to Tour Operator's settings
	 * registry through `sd/to-setting` so an editor can change them without a
	 * deploy (FR-003). Neither setting is populated on dev or local today, so
	 * both render the authored copy below — which is live's own wording, and
	 * is the binding's fallback working, not a defect.
	 */
	?>
	<!-- wp:cover {"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/08/banner-specials-1920x454.jpg","alt":"","dimRatio":100,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":454,"minHeightUnit":"px","contentPosition":"bottom center","align":"full","className":"is-style-hero-banner","tagName":"section","metadata":{"name":"Banner"},"style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center is-style-hero-banner" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);min-height:454px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-100 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/08/banner-specials-1920x454.jpg" data-object-fit="cover"/><div class="wp-block-cover__inner-container">

		<!-- wp:group {"metadata":{"name":"Banner Content"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:heading {"level":1,"metadata":{"name":"Page Title","bindings":{"content":{"source":"sd/to-setting","args":{"key":"{post_type}.title"}}}},"className":"is-style-script-accent","fontSize":"800","anchor":"h-specials"} -->
			<h1 class="wp-block-heading is-style-script-accent has-800-font-size" id="h-specials"><?php esc_html_e( 'Specials', 'sd-theme-2026' ); ?></h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"metadata":{"name":"Tagline","bindings":{"content":{"source":"sd/to-setting","args":{"key":"{post_type}.tagline"}}}},"className":"is-style-subheading-large","style":{"typography":{"fontWeight":"var:custom|font-weight|medium"}},"fontFamily":"heading"} -->
			<p class="is-style-subheading-large has-heading-font-family" style="font-weight:var(--wp--custom--font-weight--medium)"><?php esc_html_e( 'Hotels, Guest Lodges &amp; Camps', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:group -->

	</div></section>
	<!-- /wp:cover -->

	<!-- wp:pattern {"slug":"sd-theme-2026/breadcrumbs"} /-->

	<?php
	/*
	 * The introduction, below the banner and above the list (FR-002). Bound to
	 * the same settings registry as the banner strings, at the shortened length
	 * the spec asks for.
	 */
	?>
	<!-- wp:group {"metadata":{"name":"Introduction"},"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--50)">

		<!-- wp:paragraph {"metadata":{"name":"Introduction","bindings":{"content":{"source":"sd/to-setting","args":{"key":"{post_type}.description"}}}},"align":"wide","style":{"typography":{"textAlign":"center"}},"fontSize":"300"} -->
		<p class="has-text-align-center alignwide has-300-font-size"><?php esc_html_e( 'Our current offers on Africa’s finest lodges, camps and hotels — negotiated directly with the properties we know best. Each one is limited, seasonal and subject to availability, so tell us which appeals and we will build the itinerary around it.', 'sd-theme-2026' ); ?></p>
		<!-- /wp:paragraph -->

	</div>
	<!-- /wp:group -->

	<!-- wp:query {"queryId":0,"query":{"inherit":true,"postType":"special"},"align":"full","metadata":{"name":"Offers"},"style":{"spacing":{"blockGap":"var:preset|spacing|50","padding":{"bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-query alignfull" style="padding-bottom:var(--wp--preset--spacing--70)">

		<!-- wp:post-template {"align":"full","style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"default"}} -->

			<?php
			/*
			 * The band. `is-style-special-card` is load-bearing twice over: it
			 * is the section style that draws the card, and it is what
			 * `SD\Enhancements\Specials::add_band_anchor()` matches on to give
			 * this group its `special-{slug}` id at render time.
			 */
			?>
			<!-- wp:group {"metadata":{"name":"Offer Band"},"className":"is-style-special-card","align":"full","style":{"spacing":{"blockGap":"0","padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"layout":{"type":"default"}} -->
			<div class="wp-block-group alignfull is-style-special-card" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">

				<!-- wp:post-featured-image {"aspectRatio":"21/9","className":"special-card__media"} /-->

				<!-- wp:group {"metadata":{"name":"Offer Body"},"className":"special-card__body","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group special-card__body">

					<!-- wp:post-title {"level":2,"style":{"typography":{"textAlign":"center"}}} /-->

					<!-- wp:group {"metadata":{"name":"Meta"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"constrained"}} -->
					<div class="wp-block-group">

						<!-- wp:paragraph {"metadata":{"name":"Accommodation","bindings":{"content":{"source":"lsx/post-connection","args":{"key":"accommodation_to_special"}}}},"style":{"typography":{"textAlign":"center"}},"fontSize":"200","prefix":"Accommodation:","prefixBold":true} -->
						<p class="has-text-align-center has-200-font-size"></p>
						<!-- /wp:paragraph -->

						<!-- wp:paragraph {"metadata":{"name":"Destinations","bindings":{"content":{"source":"lsx/post-connection","args":{"key":"destination_to_special"}}}},"style":{"typography":{"textAlign":"center"}},"fontSize":"200","prefix":"Destinations:","prefixBold":true} -->
						<p class="has-text-align-center has-200-font-size"></p>
						<!-- /wp:paragraph -->

						<!-- wp:post-terms {"term":"travel-style","prefix":"Travel Style: ","style":{"typography":{"textAlign":"center"}},"fontSize":"200"} /-->

					</div>
					<!-- /wp:group -->

					<?php
					/*
					 * The offer's complete body copy, with its authored lists,
					 * links and emphasis intact — not an excerpt, and with no
					 * "read more" (FR-012). Live prints the whole of it and so
					 * does this.
					 */
					?>
					<!-- wp:post-content {"style":{"typography":{"textAlign":"center"}},"fontSize":"200","layout":{"type":"constrained"}} /-->

					<!-- wp:buttons {"metadata":{"name":"Enquire"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|10"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
					<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--10)"><!-- wp:button {"className":"is-style-fill"} -->
					<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="#to-modal-enquiry"><?php esc_html_e( 'Enquire about this special', 'sd-theme-2026' ); ?></a></div>
					<!-- /wp:button --></div>
					<!-- /wp:buttons -->

				</div>
				<!-- /wp:group -->

			</div>
			<!-- /wp:group -->

		<!-- /wp:post-template -->

		<!-- wp:query-pagination {"align":"wide","layout":{"type":"flex","justifyContent":"center"}} -->
		<!-- wp:query-pagination-previous /-->

		<!-- wp:query-pagination-numbers /-->

		<!-- wp:query-pagination-next /-->
		<!-- /wp:query-pagination -->

		<!-- wp:query-no-results -->
		<!-- wp:paragraph {"align":"wide","style":{"typography":{"textAlign":"center"}},"fontSize":"300"} -->
		<p class="has-text-align-center alignwide has-300-font-size"><?php esc_html_e( 'There are no current specials just now. New offers are negotiated every season — tell us where you would like to go and we will let you know the moment one lands.', 'sd-theme-2026' ); ?></p>
		<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->

	</div>
	<!-- /wp:query -->

	<?php
	/*
	 * The two closing bands (FR-020).
	 *
	 * None of the three existing `cta-*` patterns carried this page's live
	 * wording — measured on /specials/ 2026-09-17, the heading is "Like what
	 * you see? Let's start planning!" and the three hold the destination,
	 * property and generic variants instead. That is not a gap:
	 * `cta-not-sure-where-to-go.php`'s own docblock records that live's
	 * `sd_call_info_section()` takes its title from the caller, that
	 * `partials/footer-cta.php` switches on body class to pick one of four,
	 * that a block theme turns that conditional into *which pattern each
	 * template includes*, and that the specials variant belongs with this
	 * issue by name. `cta-like-what-you-see.php` is that variant — the same
	 * band, one copy string apart — not a fourth CTA design.
	 */
	?>
	<!-- wp:pattern {"slug":"sd-theme-2026/cta-like-what-you-see"} /-->

	<!-- wp:pattern {"slug":"sd-theme-2026/why-choose-sd"} /-->

</main>
<!-- /wp:group -->
