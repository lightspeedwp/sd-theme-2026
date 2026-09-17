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
 * therefore both the working route and the one the data supports, and it is the
 * absolutely-positioned ground `styles/sections/cards/special-card.json` draws
 * the band against. Recorded on the register as a correction to FR-009.
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
 * ## The enquire button
 *
 * Every band's button points at `#to-modal-modal-enquiry` — the same href the
 * other six enquiry CTAs in this theme use, and the one
 * `SD\Enhancements\Enquiry::register_trigger_modal()` actually resolves: it
 * reads the slug out of the href and registers `parts/modal-enquiry.html` as
 * the modal's content, so the offer bands open the same Gravity Form 1 dialog,
 * styled the same way, as every other "Send an Email" on the site.
 *
 * ⚠️ It said `#to-modal-enquiry` until 2026-09-17, which is **not** a
 * template-part slug — the part is `modal-enquiry`. `Enquiry` verifies the slug
 * resolves to a real `wp_template_part` before rendering it, by design, so the
 * mismatch registered nothing and all four buttons were inert anchors. The
 * module deduplicates by slug, so the four bands on a page still print one
 * dialog between them.
 *
 * The per-band attribute naming *which* offer the enquiry is about is still
 * **not** emitted: whether the form's field takes the offer's name, its slug or
 * its post ID is the line 15/16 owner's decision and it was open when this
 * shipped. SC-007 is verified when that surface arrives, not waived.
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
	 * The heading and the tagline are an ordinary `core/heading` and
	 * `core/paragraph` carrying live's own wording. They were bound to Tour
	 * Operator's settings registry through `sd/to-setting` until 2026-09-17;
	 * that was wrong twice over — neither setting is populated on dev or local,
	 * so every render fell through to the authored copy anyway, and the other
	 * four Tour Operator landing pages all author theirs directly. Matching
	 * them is the point: one composition, edited in one place.
	 */
	?>
	<!-- wp:cover {"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/08/banner-specials-1920x454.jpg","alt":"","dimRatio":100,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":454,"minHeightUnit":"px","contentPosition":"bottom center","align":"full","className":"is-style-hero-banner","tagName":"section","metadata":{"name":"Banner"},"style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center is-style-hero-banner" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);min-height:454px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-100 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/08/banner-specials-1920x454.jpg" data-object-fit="cover"/><div class="wp-block-cover__inner-container">

		<!-- wp:group {"metadata":{"name":"Banner Content"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:heading {"level":1,"metadata":{"name":"Page Title"},"className":"is-style-script-accent","fontSize":"800","anchor":"h-specials"} -->
			<h1 class="wp-block-heading is-style-script-accent has-800-font-size" id="h-specials"><?php esc_html_e( 'Specials', 'sd-theme-2026' ); ?></h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"metadata":{"name":"Tagline"},"className":"is-style-subheading-large","style":{"typography":{"fontWeight":"var:custom|font-weight|medium"}},"fontFamily":"heading"} -->
			<p class="is-style-subheading-large has-heading-font-family" style="font-weight:var(--wp--custom--font-weight--medium)"><?php esc_html_e( 'Hotels, Guest Lodges &amp; Camps', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:group -->

	</div></section>
	<!-- /wp:cover -->

	<!-- wp:pattern {"slug":"sd-theme-2026/breadcrumbs"} /-->

	<?php
	/*
	 * The introduction, below the banner and above the list (FR-002). A plain
	 * `core/paragraph` carrying live's own `.lsx-to-archive-description` copy,
	 * word for word.
	 *
	 * `is-style-archive-intro` is the one deliberate deviation from live on
	 * this page: live sets the standfirst in the same body face as everything
	 * else, where the other four Tour Operator archives in this theme open on
	 * the italic, drop-capped intro. Consistency across the five wins over
	 * fidelity on one.
	 */
	?>
	<!-- wp:group {"metadata":{"name":"Introduction"},"align":"full","className":"is-style-light-page-section","layout":{"type":"constrained","contentSize":"1100px"}} -->
	<div class="wp-block-group alignfull is-style-light-page-section">

		<!-- wp:paragraph {"metadata":{"name":"Introduction"},"align":"wide","className":"is-style-archive-intro"} -->
		<p class="alignwide is-style-archive-intro"><?php esc_html_e( 'There are loads of great travel deals out there. Depending on when you are travelling and how many nights you are staying, there are some excellent discounts to take advantage of. Here is a hand picked list of the ones that we feel are a good fit.', 'sd-theme-2026' ); ?></p>
		<!-- /wp:paragraph -->

	</div>
	<!-- /wp:group -->

	<?php
	/*
	 * The offer list sits on the same white band as the introduction above it,
	 * with its top padding taken off so the two read as one section rather than
	 * two stacked ones.
	 */
	?>
	<!-- wp:group {"metadata":{"name":"Offers Band"},"align":"full","className":"is-style-light-page-section","style":{"spacing":{"padding":{"top":"0"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull is-style-light-page-section" style="padding-top:0">

	<!-- wp:query {"queryId":0,"query":{"inherit":true,"postType":"special"},"align":"full","metadata":{"name":"Offers"},"style":{"spacing":{"blockGap":"var:preset|spacing|50","padding":{"bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-query alignfull" style="padding-bottom:var(--wp--preset--spacing--70)">

		<?php
		/*
		 * `blockGap: 0`. Live's bands butt straight up against each other —
		 * `.lsx-to-archive-item { margin: 0 }` — and the alternating left/right
		 * panels only read as a rhythm if nothing separates the photographs.
		 *
		 * `align: wide`, not full. Live runs the bands edge to edge; here they
		 * stop at the wide measure so the list sits inside the white band like
		 * every other archive in this theme rather than breaking out of it.
		 * Set in the Site Editor 2026-09-17 and reconciled back here.
		 */
		?>
		<!-- wp:post-template {"align":"wide","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->

			<?php
			/*
			 * The band. `is-style-special-card` is load-bearing twice over: it
			 * is the section style that draws the card, and it is what
			 * `SD\Enhancements\Specials::add_band_anchor()` matches on to give
			 * this group its `special-{slug}` id at render time.
			 *
			 * ## No presentational classes on the children
			 *
			 * The photograph and the panel carried `special-card__media` and
			 * `special-card__body` until 2026-09-17. They are gone: the section
			 * style reaches its two children as `& > .wp-block-post-featured-image`
			 * and `& > .wp-block-group` instead. The band has exactly one image
			 * and exactly one group, so the block selectors are as precise as
			 * the classes were — and an editor who rebuilds this band by hand
			 * now gets the layout without having to know two invented class
			 * names. Add a second group inside the band and you break that; add
			 * it *inside* the panel instead.
			 */
			?>
			<!-- wp:group {"metadata":{"name":"Offer Band"},"className":"is-style-special-card","align":"full","style":{"spacing":{"blockGap":"0","padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"layout":{"type":"default"}} -->
			<div class="wp-block-group alignfull is-style-special-card" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">

				<?php
				/*
				 * No `aspectRatio`: the photograph is the band's ground, sized
				 * by the band, exactly as live's `background-size: cover` is.
				 * An aspect ratio here would set the band's height from the
				 * viewport width instead and fight the 540px floor.
				 */
				?>
				<!-- wp:post-featured-image /-->

				<!-- wp:group {"metadata":{"name":"Offer Body"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"default"}} -->
				<div class="wp-block-group">

					<!-- wp:post-title {"level":2} /-->

					<!-- wp:group {"metadata":{"name":"Meta"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"default"}} -->
					<div class="wp-block-group">

						<!-- wp:paragraph {"metadata":{"name":"Accommodation","bindings":{"content":{"source":"lsx/post-connection","args":{"key":"accommodation_to_special"}}}},"fontSize":"200","prefix":"Accommodation:","prefixBold":true} -->
						<p class="has-200-font-size"></p>
						<!-- /wp:paragraph -->

						<!-- wp:paragraph {"metadata":{"name":"Destinations","bindings":{"content":{"source":"lsx/post-connection","args":{"key":"destination_to_special"}}}},"fontSize":"200","prefix":"Destinations:","prefixBold":true} -->
						<p class="has-200-font-size"></p>
						<!-- /wp:paragraph -->

						<!-- wp:post-terms {"term":"travel-style","prefix":"Travel Style: ","fontSize":"200"} /-->

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
					<!-- wp:post-content {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"fontSize":"200","layout":{"type":"default"}} /-->

					<!-- wp:buttons {"metadata":{"name":"Enquire"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|10"}}},"layout":{"type":"flex","justifyContent":"left"}} -->
					<div class="wp-block-buttons is-content-justification-left" style="margin-top:var(--wp--preset--spacing--10)"><!-- wp:button {"className":"is-style-fill"} -->
					<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="#to-modal-modal-enquiry"><?php esc_html_e( 'Enquire about this special', 'sd-theme-2026' ); ?></a></div>
					<!-- /wp:button --></div>
					<!-- /wp:buttons -->

				</div>
				<!-- /wp:group -->

			</div>
			<!-- /wp:group -->

		<!-- /wp:post-template -->

		<?php
		/*
		 * `paginationArrow: chevron` with `showLabel: false` — chevrons alone,
		 * no "Previous"/"Next" wording, matching `template-home-blog.php`.
		 * Both attributes live on `core/query-pagination` and inherit down to
		 * the previous/next children; setting them on the children does
		 * nothing.
		 */
		?>
		<!-- wp:query-pagination {"paginationArrow":"chevron","showLabel":false,"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
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

	</div>
	<!-- /wp:group -->

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
