<?php
/**
 * Title: Template: Single Accommodation
 * Slug: sd-theme-2026/template-single-accommodation
 * Description: The accommodation single — the banner, the summary band pairing the property copy with the rating box and the Best Price Guarantee panel, the gallery, the units band, the tours and review shelves, closing on the enquiry and Why Choose bands.
 * Categories: sd-theme-2026/tour-operator
 * Keywords: accommodation, lodge, camp, hotel, single, safari, template
 * Viewport Width: 1400
 * Template Types: single
 * Post Types: wp_template
 * Inserter: false
 *
 * @package sd-theme-2026
 */

/*
 * Live's accommodation single, translated to blocks.
 *
 * Measured from /accommodation/chitwa-chitwa-private-game-lodge/ on 2026-08-31,
 * against sd-lsx-child/includes/layout.php:117-155
 * (`sd_lsx_to_accommodation_single_content_bottom()`),
 * sd-lsx-child/includes/functions.php:531 (`sd_single_accommodation_box()`) and
 * :683 (`sd_lsx_to_accommodation_units()`).
 *
 * Section order is live's:
 *
 *   banner → summary (property copy | rating box, Best Price Guarantee)
 *   → gallery → units → tours featuring → connected reviews
 *   → "Inspired by this property?" → Why Choose → Trustpilot
 *
 * It is the third of the three Tour Operator singles and it is built on the two
 * that came first: the banner, the tinted summary, the bound gallery, the
 * connection shelves and the two closing bands are all
 * patterns/template-single-tour.php and patterns/template-single-destination.php
 * unchanged. Only the summary's right-hand column is new, and it is the one
 * thing this page has that neither of the others does.
 *
 * ## The shelves are Tour Operator's connection queries
 *
 * Each is a `core/query` whose `core/post-template` carries an
 * `lsx-<to>-related-<from>-query` class. `Query_Loop::query_args_filter()`
 * (includes/classes/blocks/class-query-loop.php:363) reads it and rewrites the
 * query to the ids in this accommodation's `<to>_to_accommodation` meta. The
 * class goes on the **post-template**, not the query —
 * `query_loop_block_query_vars` is applied by
 * `render_block_core_post_template()` — and the matching `…-query-wrapper` on
 * the section group removes the band, heading included, when the connection is
 * empty. That is why Chitwa Chitwa renders banner → summary → gallery → units →
 * CTA on live and nothing between: it connects no tours and no reviews.
 *
 * `tour-related-accommodation` is the one that resolves through the shared
 * destinations as well as the `tour_to_accommodation` connection
 * (class-query-loop.php:405-440), which is live's behaviour too.
 *
 * ## What this template does not carry, and why
 *
 * - **The specials shelf.** Live renders `#special` between the units band and
 *   the reviews — one full-width offer panel per connected special, with
 *   `special-badge-single.svg` over it (`sd_lsx_to_accommodation_specials()`,
 *   template-tags.php:548). The band is one `core/query` on
 *   `lsx-special-related-accommodation-query`, but the tile it needs does not
 *   exist: `styles/sections/cards/special-card.json` is written and registered
 *   and no pattern uses it yet. Building that card is the specials archive's
 *   work, exactly as recorded in patterns/template-single-destination.php.
 *   When it lands the section goes directly between "Units" and the reviews and
 *   needs nothing else. The *specials badge* is not deferred with it — it is
 *   part of the rating box below and is built here. → flagged on LS-2033
 * - **The breadcrumb bar.** Live draws Yoast's trail inside the banner. Breadcrumb
 *   output is a filter over a third-party plugin's trail — behaviour, not
 *   design — so by the deactivation test it is `sd-enhancements` work, as
 *   recorded in both sibling singles. When it lands it goes directly beneath
 *   the cover, outside it.
 * - **Tour Operator's sticky section menu.** The plugin's own
 *   `single-accommodation.html` opens with `lsx-tour-operator/sticky-menu`, and
 *   live has the equivalent markup — `.lsx-to-navigation .lsx-to-content-spy`,
 *   listing Summary / Map / Rooms / Facilities / Gallery / Videos /
 *   Accommodation — and then switches it off: `.single .lsx-to-navigation
 *   { display: none !important }` (custom.css:1795). It has never been visible
 *   on a single.
 * - **The map, the facilities band and the videos band.** All three are in that
 *   spy nav and in the plugin's default template; none of the three renders on
 *   live. `sd_lsx_to_accommodation_single_content_bottom()` emits gallery,
 *   units, tours, specials and reviews and nothing else, and the measured page
 *   has no `#accommodation-map`, no `#facilities` and no `#videos` — even
 *   though Chitwa Chitwa carries 37 `facility` terms. Reproducing them would be
 *   adding sections nobody costed to a page that has not shown them in years.
 *   `facility` remains the open question §5 of the live-site audit records.
 * - **The related-accommodation shelf.** Same story: `#related-items` is in the
 *   spy nav and in the plugin's default template, and live's accommodation
 *   single does not render it.
 * - **The `.more-text` read-more collapse and the gold drop cap.** Live
 *   truncates the property copy to its first paragraph with a "Read More…"
 *   link (custom.js:224-275) and gives its first letter the gold drop cap
 *   (custom.css:2716, ≥900px). The collapse is a JavaScript behaviour over post
 *   content, so it is `sd-enhancements` work; the drop cap rides on the class
 *   the collapse creates. Both sibling singles left them out for the same
 *   reason and this one follows.
 *
 * ## Section grounds follow live
 *
 * Only the summary band is tinted — `#collapse-summary .collapse-inner > .row`
 * is full-bleed `#f7f5f2` at 6.4rem (custom.css:2126), which is `neutral-200`
 * at the spacing-70 `is-style-tinted-page-section` already carries, and the
 * rule names `.single-lsx-to-accommodation` first. The gallery is *not* tinted
 * here: `custom.css:2420` scopes that to `.single-lsx-to-tour`, and
 * `custom.css:1802` gives the accommodation gallery padding only.
 *
 * The one deliberate departure is the units band. Live paints it `#f7f5f2` with
 * `#ece9e3` cards (custom.css:1820, 1828) — a half-step the palette does not
 * have, because it resolves both to `neutral-200`. Keeping live's band colour
 * would make the cards vanish into it, so the band is white and the cards keep
 * the tint. The contrast relationship live draws is preserved; the two absolute
 * values are not. → patterns/accommodation-unit.php
 *
 * `require`, not `<!-- wp:pattern -->`, for the unit card and the two closing
 * bands — a nested pattern reference inside another *pattern* is dropped on
 * front-end render while still resolving under a WP-CLI `do_blocks()` test.
 * References inside a *query loop* are fine, which is why the two card patterns
 * below are still written as references.
 * → .claude/skills/wp-pattern-runtime-pitfalls
 */

?>
<!-- wp:group {"tagName":"main","metadata":{"name":"Accommodation Single"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php
	/*
	 * The banner.
	 *
	 * The same device as the other two singles, at the same 360px floor, so all
	 * three Tour Operator singles open identically: `is-style-hero-banner` owns
	 * the ground, the scrim and the type colours, and only the composition is
	 * here.
	 *
	 * The image is the accommodation's banner image. Tour Operator's
	 * `Bindings::render_banner_block()` (class-bindings.php:1144) swaps a
	 * cover's background for the `banner_image_id` meta whenever the cover
	 * carries an `lsx/post-meta` binding on `content` — the args are only a
	 * marker; the key it reads is fixed, and it is not post-type specific.
	 * `useFeaturedImage` stays on underneath as the fallback.
	 *
	 * ⚠️ **This is one place the block build is deliberately not live.** Live's
	 * accommodation banner is `.page-banner.rotating`, and LSX Banners fills it
	 * with one of eleven site-wide images picked per request in PHP — Chitwa
	 * Chitwa's measured banner is `Elephant.jpg`, which has nothing to do with
	 * the lodge. A random generic photograph is not a design decision worth
	 * porting, it is not expressible in blocks without plugin work, and the tour
	 * and destination singles already resolved it the same way. The property's
	 * own banner image, then its own thumbnail, then nothing.
	 *
	 * **No tagline.** Live gates it on post type — `.single #lsx-banner
	 * .banner-content .tagline { display: none }` with only `.single-lsx-to-tour`
	 * putting it back (custom.css:1787) — which in a block theme is simply: it
	 * is in the tour template and not in this one. The measured banner carries
	 * the `<h1>` alone.
	 *
	 * A flow layout, not constrained, so `alignwide` reaches the children
	 * instead of being re-clamped to the content measure.
	 */
	?>
	<!-- wp:cover {"useFeaturedImage":true,"dimRatio":0,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":360,"minHeightUnit":"px","contentPosition":"bottom center","isDark":false,"align":"full","tagName":"section","metadata":{"name":"Banner","bindings":{"content":{"source":"lsx/post-meta","args":{"key":"banner_image_id"}}}},"className":"is-style-hero-banner","style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-cover alignfull is-light has-custom-content-position is-position-bottom-center is-style-hero-banner" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);min-height:360px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container">

		<!-- wp:group {"metadata":{"name":"Banner Content"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:post-title {"level":1,"metadata":{"name":"Accommodation Title"},"className":"is-style-script-accent","fontSize":"800"} /-->

		</div>
		<!-- /wp:group -->

	</div></section>
	<!-- /wp:cover -->

	<?php
	/*
	 * The summary band.
	 *
	 * Live's two `col-md-6` columns inside `#collapse-summary`: the property
	 * copy on the left, `.single-accommodation-box-wrap` on the right. Two equal
	 * columns, as live has them — like the destination single and like the
	 * tour, whose right column is a fast-facts card. (Live caps that card at
	 * 497px; this theme does not — see patterns/template-single-tour.php, "The
	 * summary band".)
	 *
	 * **No safari expert panel.** Both sibling singles carry one beneath the
	 * copy; live's accommodation single does not — `#safari-expert-box` is
	 * absent from the measured page, and `sd_single_accommodation_box()` fills
	 * that column with the rating box and the guarantee panel instead. The
	 * absence is written down rather than left to look like an oversight.
	 *
	 * **The copy is wrapped, italic, and cut with a Read More** — the same
	 * composition as `patterns/destination-summary.php` and
	 * `patterns/template-single-tour.php`, and for the same reason. Live
	 * truncates the property copy to its first paragraph and appends a
	 * "Read More…" link (sd-lsx-child/assets/js/custom.js:224-275);
	 * `core/read-more` collapses `core/post-content` to its first block and
	 * expands it in place on click, so the behaviour is core's and no script of
	 * ours is required. This template previously carried a bare
	 * `core/post-content` and routed that truncation to `sd-enhancements`,
	 * which is superseded on the same grounds as it was on the other two
	 * singles: there is a core block for it, so it is neither plugin work nor a
	 * script. The three Tour Operator singles now open their copy identically.
	 *
	 * The outer group's own inline `font-style:italic;font-weight:400`, not the
	 * `is-style-archive-intro` class, is what makes the copy italic —
	 * `core/paragraph`'s `selectors.root` is bare `p`, so the archive-intro
	 * variation compiles to `p.is-style-archive-intro` and can never match an
	 * ancestor, and here the class sits on `core/post-content`'s wrapper. It is
	 * kept because it marks the block's role and because it is what both
	 * sibling singles carry; the italic and the 400 weight the page shows come
	 * from inheritance off the group. → the same note in
	 * `patterns/destination-summary.php`
	 *
	 * `post-content`'s `blockGap` is `spacing|20`, tighter than the `spacing|60`
	 * root default, which is the closer paragraph rhythm the other two singles
	 * use.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Accommodation Summary"},"align":"full","className":"is-style-tinted-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"summary"} -->
	<section class="wp-block-group alignfull is-style-tinted-page-section" id="summary">

		<!-- wp:columns {"verticalAlignment":"top","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|60"}}}} -->
		<div class="wp-block-columns alignwide are-vertically-aligned-top">

			<!-- wp:column {"verticalAlignment":"top","style":{"spacing":{"blockGap":"var:preset|spacing|50"}}} -->
			<div class="wp-block-column is-vertically-aligned-top">

				<!-- wp:group {"style":{"typography":{"fontStyle":"italic","fontWeight":"var:custom|font-weight|regular"},"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","justifyContent":"left"}} -->
				<div class="wp-block-group" style="font-style:italic;font-weight:var(--wp--custom--font-weight--regular)">

					<!-- wp:post-content {"className":"is-style-archive-intro","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} /-->

					<!-- wp:read-more {"content":"<?php esc_attr_e( 'Read more...', 'sd-theme-2026' ); ?>","fontSize":"300"} /-->

				</div>
				<!-- /wp:group -->

			</div>
			<!-- /wp:column -->

			<!-- wp:column {"verticalAlignment":"top","style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
			<div class="wp-block-column is-vertically-aligned-top">

				<?php
				/*
				 * The rating box — live's `#sd-rating-box`.
				 *
				 * A 1px `#b4a48c` frame (neutral-400, the same value
				 * styles/sections/slider-frame.json resolves that hex to) at
				 * 15px 20px, holding the star rating and the price band on the
				 * left and the specials badge on the right (custom.css:2668).
				 *
				 * The three wrapper classes are the whole of its conditional
				 * logic and none of it is theme PHP:
				 *
				 *  - `lsx-accommodation-price-box-wrapper` removes the framed
				 *    box when the accommodation has no rating, no band and no
				 *    connected special.
				 *  - `lsx-accommodation-price-facts-wrapper` removes the left
				 *    column on its own, so a property with a special but no
				 *    rating shows the badge without an empty column beside it.
				 *  - `lsx-special-to-accommodation-wrapper` removes the badge.
				 *
				 * The first two are registered by `SD\Enhancements\Wrappers`
				 * against Tour Operator's `lsx_to_multi_field_wrappers` filter,
				 * which exists for exactly this; the third is a single-field
				 * wrapper Tour Operator already handles, and its `_to_` branch
				 * additionally checks that the connected specials still exist.
				 * → sd-enhancements/docs/blocks-and-bindings.md §6
				 *
				 * **`centered-rating` / `centered-special` are deliberately not
				 * ported.** They re-centred the surviving half of the box when
				 * the other half was empty; a `justifyContent: center` on the
				 * flex parent does that unconditionally and with no PHP.
				 */
				?>
				<!-- wp:group {"metadata":{"name":"Rating Box"},"className":"lsx-accommodation-price-box-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|30","bottom":"var:preset|spacing|20","left":"var:preset|spacing|30"}},"border":{"color":"var:preset|color|neutral-400","width":"var:custom|border-width|100","style":"solid"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center","verticalAlignment":"center"}} -->
				<div class="wp-block-group lsx-accommodation-price-box-wrapper" style="border-color:var(--wp--preset--color--neutral-400);border-style:solid;border-width:var(--wp--custom--border-width--100);padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--30)">

					<!-- wp:group {"metadata":{"name":"Rating and Price"},"className":"lsx-accommodation-price-facts-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|10"},"layout":{"selfStretch":"fill","flexSize":null}},"layout":{"type":"constrained"}} -->
					<div class="wp-block-group lsx-accommodation-price-facts-wrapper">

						<?php
						/*
						 * The stars.
						 *
						 * `lsx/post-meta` runs the value through
						 * `lsx_to_custom_field_query()`, and Tour Operator's
						 * `Accommodation::rating()` filter
						 * (includes/classes/legacy/class-accommodation.php:179)
						 * answers that call for `rating` on this post type with
						 * five 20px star images, filled down to the stored
						 * value. So the block route already draws live's
						 * `<i class="fa fa-star">` row and the theme supplies no
						 * glyphs — which is why the value is bound rather than
						 * composed.
						 *
						 * ⚠️ The filter returns a `<div class="rating-stars">`,
						 * and WordPress runs a bound value through
						 * `wp_kses_post()` (wp-includes/class-wp-block.php:412),
						 * so the markup survives — but a `<div>` inside a `<p>`
						 * is not parseable HTML and the browser closes the
						 * paragraph before it. The wrapping group is therefore
						 * the only stable place to hang alignment or spacing
						 * from, exactly as the tour single's highlights list.
						 * Nothing is hung off it today; the note is here so the
						 * next person does not reach for the paragraph.
						 *
						 * `lsx-rating-wrapper` is Tour Operator's own class for
						 * this — the `lsx-tour-operator/rating` "block" is a
						 * `core/group` *variation* that inserts exactly this
						 * composition, so a `<!-- wp:lsx-tour-operator/rating /-->`
						 * reference would not render. The heading sits inside
						 * the wrapper because live gates it on the rating alone.
						 *
						 * An `h2`: the banner title is the page's `h1` and the
						 * summary band carries no heading of its own — live's
						 * "Summary" `<h2>` is `hidden-lg` and never shown on the
						 * desktop page this was measured from.
						 */
						?>
						<!-- wp:group {"metadata":{"name":"Rating"},"className":"lsx-rating-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"constrained"}} -->
						<div class="wp-block-group lsx-rating-wrapper">

							<!-- wp:heading {"textAlign":"center","level":2,"metadata":{"name":"Rating Label"},"style":{"typography":{"textTransform":"uppercase","letterSpacing":"var:custom|letter-spacing|heading"}},"fontSize":"300"} -->
							<h2 class="wp-block-heading has-text-align-center has-300-font-size" style="letter-spacing:var(--wp--custom--letter-spacing--heading);text-transform:uppercase"><?php esc_html_e( 'This property is rated:', 'sd-theme-2026' ); ?></h2>
							<!-- /wp:heading -->

							<!-- wp:paragraph {"align":"center","metadata":{"name":"Rating Stars","bindings":{"content":{"source":"lsx/post-meta","args":{"key":"rating"}}}},"fontSize":"200"} -->
							<p class="has-text-align-center has-200-font-size"></p>
							<!-- /wp:paragraph -->

						</div>
						<!-- /wp:group -->

						<?php
						/*
						 * The price band — live's
						 * `.lsx-to-meta-data-price-rating`, the heading face at
						 * 20px semi-bold with the label a weight lighter
						 * (custom.css:2693).
						 *
						 * Bound through `sd/post-meta` rather than Tour
						 * Operator's source, because `price_rating` offers
						 * `none` as a real stored option and it is SD's most
						 * common value: the `price-band` format returns `null`
						 * for both empty and `none`, so the band never prints
						 * the word "none". That format is the default for this
						 * key, which is why there are no args beyond it.
						 * → sd-enhancements/docs/blocks-and-bindings.md, K-24
						 *
						 * `lsx-price-rating-wrapper` takes the label with it —
						 * `maybe_hide_varitaion()` maps the key back to
						 * `price_rating` and its single-field branch excludes
						 * `none` as well as empty, which is the branch that
						 * gets this right.
						 */
						?>
						<!-- wp:group {"metadata":{"name":"Price Rating"},"className":"lsx-price-rating-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"textColor":"neutral-700","fontFamily":"heading","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center","verticalAlignment":"bottom"}} -->
						<div class="wp-block-group lsx-price-rating-wrapper has-neutral-700-color has-text-color has-heading-font-family">

							<!-- wp:paragraph {"style":{"typography":{"fontWeight":"var:custom|font-weight|light"}},"fontSize":"300"} -->
							<p class="has-300-font-size" style="font-weight:var(--wp--custom--font-weight--light)"><?php echo esc_html_x( 'Price Rating:', 'accommodation price band label', 'sd-theme-2026' ); ?></p>
							<!-- /wp:paragraph -->

							<!-- wp:paragraph {"metadata":{"name":"Price Band","bindings":{"content":{"source":"sd/post-meta","args":{"key":"price_rating"}}}},"style":{"typography":{"fontWeight":"var:custom|font-weight|semi-bold"}},"fontSize":"300"} -->
							<p class="has-300-font-size" style="font-weight:var(--wp--custom--font-weight--semi-bold)"></p>
							<!-- /wp:paragraph -->

						</div>
						<!-- /wp:group -->

					</div>
					<!-- /wp:group -->

					<?php
					/*
					 * The specials badge — live's `.sd-badge-box`, capped at
					 * 150px on the trailing edge of the box (custom.css:2707).
					 *
					 * The plate itself is `special-badge-single.svg` from the
					 * child theme, copied into this theme's own assets rather
					 * than seeded into the media library: it is chrome the
					 * template owns, like the Trustpilot marks in
					 * patterns/why-choose-sd.php, not content an editor picks.
					 *
					 * It is decorative — the *section* it advertises is the
					 * specials shelf this template defers — so it carries an
					 * empty `alt` and no link. When the shelf lands it should
					 * become the link to it.
					 */
					?>
					<!-- wp:group {"metadata":{"name":"Specials Badge"},"className":"lsx-special-to-accommodation-wrapper","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"constrained","contentSize":"150px"}} -->
					<div class="wp-block-group lsx-special-to-accommodation-wrapper"><!-- wp:image {"width":"150px","sizeSlug":"full","linkDestination":"none"} -->
					<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/special-badge.svg' ) ); ?>" alt="" style="width:150px"/></figure>
					<!-- /wp:image --></div>
					<!-- /wp:group -->

				</div>
				<!-- /wp:group -->

				<?php
				/*
				 * The Best Price Guarantee panel — live's `#guarantee-cta`
				 * (custom.css:4000): the brown filigree texture behind a gold
				 * heading and white running copy, centred.
				 *
				 * ⚠️ **This is `patterns/template-archive-accommodation.php`'s
				 * panel, byte-for-byte, and the two must stay in step.** The
				 * archive re-authored it on dev 2026-09-04 — the script-accent
				 * heading, the 300px floor, the tighter block padding — and
				 * this copy was left on the earlier composition, so the same
				 * panel read as two different objects depending on which page
				 * you reached it from. The archive's is the settled version and
				 * it is the one carried here. If the panel ever gains a third
				 * sibling it earns a pattern of its own; with two, a `require`
				 * is impossible in either direction, because both files are
				 * whole templates rather than sections.
				 *
				 * A `core/cover` rather than a group with a background image,
				 * because the ground is an image and `core/group` has no image
				 * background. `dimRatio: 0` — the artwork is already
				 * neutral-800-dark and live sets no scrim over it; the overlay
				 * colour is declared anyway so the block degrades to a solid
				 * dark panel if the file is ever missing.
				 *
				 * The image is a theme asset addressed with
				 * `get_theme_file_uri()`, the convention
				 * patterns/why-choose-sd.php and patterns/trustpilot-score.php
				 * already use — no attachment ID, nothing per-install.
				 *
				 * The heading is `is-style-script-accent` at font-size 700, the
				 * gold-accent device the banner and the homepage already use,
				 * and the paragraph takes the body size rather than an explicit
				 * 200 — three type sizes inside a 400px plate was one too many.
				 * Static copy: live has no field behind either string. An `h2`,
				 * beside the rating box's `h2` rather than under it — the two
				 * panels are siblings and either can disappear on its own.
				 */
				?>
				<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/guarantee-bg.jpg' ) ); ?>","dimRatio":0,"overlayColor":"neutral-800","isUserOverlayColor":true,"minHeight":300,"contentPosition":"center center","tagName":"aside","metadata":{"name":"Best Price Guarantee"},"align":"center","style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|40","bottom":"var:preset|spacing|30","left":"var:preset|spacing|40"}}},"layout":{"type":"constrained","contentSize":"400px"}} -->
				<aside class="wp-block-cover aligncenter" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--40);min-height:300px"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/guarantee-bg.jpg' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-neutral-800-background-color has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container">

					<!-- wp:heading {"className":"is-style-script-accent","style":{"elements":{"link":{"color":{"text":"var:preset|color|accent-500"}}},"typography":{"textAlign":"center"}},"textColor":"accent-500","fontSize":"700","anchor":"h-best-price-guarantee"} -->
					<h2 class="wp-block-heading has-text-align-center is-style-script-accent has-accent-500-color has-text-color has-link-color has-700-font-size" id="h-best-price-guarantee"><?php esc_html_e( 'Best Price Guarantee', 'sd-theme-2026' ); ?></h2>
					<!-- /wp:heading -->

					<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"textColor":"base","fontFamily":"heading"} -->
					<p class="has-text-align-center has-base-color has-text-color has-heading-font-family"><?php esc_html_e( 'Booking via us is cheaper than going direct because we have access to the very best available rates at all of Africa’s premium safari lodges, camps and boutique hotels.', 'sd-theme-2026' ); ?></p>
					<!-- /wp:paragraph -->

				</div></aside>
				<!-- /wp:cover -->

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
	 * (custom.css:1802 sets padding only; the tinted variant at 2420 is scoped
	 * to `.single-lsx-to-tour`). The engine is Envira on live and Tour
	 * Operator's own gallery binding here: `Bindings::render_gallery_block()`
	 * reads the `gallery` meta and rebuilds the figure from it, discarding
	 * whatever image blocks are authored inside. The three below exist so the
	 * block has something to show in the editor; they are never rendered on the
	 * front end. Chitwa Chitwa's gallery holds twenty images, and all twenty
	 * render.
	 *
	 * ⚠️ **This is the placeholder pass, not the gallery build.** Live shows a
	 * staggered three-column grid capped at five visible tiles with a
	 * "see more" overlay on the fifth and the remaining fifteen carrying
	 * `see-more-hidden`, and opens an Envira lightbox. This renders every image
	 * in a plain grid, which is what was asked for now. The staggered layout,
	 * the overflow tile and the lightbox are a separate task; nothing here needs
	 * to change for them except this block.
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

		<!-- wp:gallery {"columns":3,"linkTo":"media","linkTarget":"_blank","sizeSlug":"large","align":"wide","metadata":{"name":"Accommodation Gallery","bindings":{"content":{"source":"lsx/gallery"}}},"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|10","left":"var:preset|spacing|10"}}}} -->
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
	 * The units band — live's `#rooms`.
	 *
	 * The card is patterns/accommodation-unit.php, which carries the
	 * `lsx/accommodation-units` binding and is what repeats; this group is the
	 * *list*.
	 *
	 * **One card per row, and each card is horizontal.** That is live, measured
	 * from `.sd-rooms-wrapper` on 2026-09-04: the unit sits in a `col-md-12`, so
	 * one to a row with 30px between them and none after the last
	 * (custom.css:1822), and `.rooms-contents` is `display: flex; flex-flow: row
	 * nowrap` at `max-width: 945px` centred, with the photograph taking the
	 * leading third (tour-operator/assets/css/style.css:1377) and the name and
	 * copy beside it. The band was previously a three-across grid on the reading
	 * of live's `data-slick` `slidesToShow: 3`; the Slick options are on the
	 * container, the `.lsx-to-slider .rooms-contents` override that would turn
	 * the card vertical never takes effect on the page, and what live actually
	 * renders is the stacked horizontal row. This is that.
	 *
	 * Tour Operator's slider is not an option here either way: it extends
	 * `core/query` and `core/terms-query` only
	 * (styles/sections/slider-frame.json), and a units repeat is neither.
	 *
	 * A `constrained` layout rather than live's literal 945px cap. 945px is a
	 * measure this theme does not otherwise use, and the default `contentSize`
	 * is 900px — near enough that the difference is invisible, and it keeps the
	 * band on the theme's own measure. The same call, for the same reason, as
	 * the archive intro's 1130px. `blockGap` is `spacing|30`, live's 30px.
	 *
	 * `lsx-units-wrapper` is doing two jobs and both are required: it removes
	 * the band, heading included, on an accommodation with no units
	 * (`maybe_hide_varitaion()`, `'units'` branch → `lsx_to_accommodation_has_rooms()`),
	 * and it is the only scope in which Tour Operator gives `.hidden`
	 * `display: none` (build/style.css, final rule), which is how an individual
	 * card drops a field it has no value for.
	 *
	 * ⚠️ **One heading, where live has up to five.** Live loops the five unit
	 * types — chalet, room, spa, tent, villa — and emits a separate `#chalets` /
	 * `#rooms` / … section per type, each headed with the type's own plural
	 * (functions.php:683-700). Tour Operator 2.2 collapses that:
	 * `render_units_block()` calls `lsx_to_accommodation_room_loop_item()` with
	 * no type argument, so every unit comes back in one pass and there is no
	 * per-type band to head. "Rooms" is live's heading on the measured page and
	 * on the great majority of SD's stock; a property whose units are tents will
	 * read "Rooms" over them. It is one word to change and it is not a theme
	 * conditional — splitting the band by type is a plugin change. → LS-2033
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Units"},"align":"full","className":"is-style-light-page-section lsx-units-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"rooms"} -->
	<section class="wp-block-group alignfull is-style-light-page-section lsx-units-wrapper" id="rooms">

		<!-- wp:heading {"textAlign":"center","className":"is-style-section-title","anchor":"h-rooms"} -->
		<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-rooms"><?php esc_html_e( 'Rooms', 'sd-theme-2026' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:group {"metadata":{"name":"Units List"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">

			<?php require __DIR__ . '/accommodation-unit.php'; ?>

		</div>
		<!-- /wp:group -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The tours shelf — live's `#tours`.
	 *
	 * **The heading names the property, where live names its type.**
	 * `sd_lsx_to_accommodation_single_content_bottom()` composes live's as
	 * `'Tours Featuring ' . $accommodation_type_title`, where the type title is
	 * the first `accommodation-type` term on the property and
	 * `'This Accommodation'` when it has none (layout.php:126-137) — so live
	 * usually reads "Tours Featuring Lodge". No source reaches that: a binding
	 * replaces a block's whole `content`, and the composed half would need the
	 * current post's first term in a taxonomy, which `sd/post-field` does not
	 * answer for and `sd/term-meta` cannot, needing a queried term.
	 *
	 * The property's own name does reach it, through `sd/post-field`'s `title`
	 * field and a `prefix` — the same composition the team single and both
	 * sibling singles use for every one of their shelf headings, so this page
	 * now heads its shelf the way the rest of the theme heads shelves. It reads
	 * "Tours Featuring Chitwa Chitwa Private Game Lodge", which is more use to
	 * a reader than live's "Tours Featuring Lodge": it says which property the
	 * tours below include rather than which category it falls in. The authored
	 * fallback is live's own un-typed string, verbatim, and it is what renders
	 * if the binding returns null.
	 *
	 * The tile is patterns/card-tour-compact.php, the same card the tour and
	 * destination singles shelve, so a tour looks the same wherever it appears.
	 * Three across, as live's `global $columns = 3`. `perPage` is 15 rather than
	 * 3: the shelf shows three at a time either way, and the count is how deep
	 * the carousel runs.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Related Tours"},"align":"full","className":"is-style-light-page-section lsx-tour-related-accommodation-query-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"tours"} -->
	<section class="wp-block-group alignfull is-style-light-page-section lsx-tour-related-accommodation-query-wrapper" id="tours">

		<!-- wp:heading {"textAlign":"center","metadata":{"name":"Tours Heading","bindings":{"content":{"source":"sd/post-field","args":{"field":"title","prefix":"<?php esc_attr_e( 'Tours Featuring ', 'sd-theme-2026' ); ?>"}}}},"className":"is-style-section-title","anchor":"h-tours"} -->
		<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-tours"><?php esc_html_e( 'Tours Featuring This Accommodation', 'sd-theme-2026' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:query {"query":{"perPage":15,"pages":0,"offset":0,"postType":"tour","order":"asc","orderBy":"title","search":"","exclude":[],"sticky":"","inherit":false},"hasCustomClass":true,"align":"wide","className":"is-style-slider-frame lsx-to-slider","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
		<div class="wp-block-query alignwide is-style-slider-frame lsx-to-slider">
			<!-- wp:post-template {"className":"lsx-tour-related-accommodation-query","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16px"}} -->
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
	 * nothing. Same composition, and same reasoning, as both sibling singles.
	 *
	 * One slide at a time, as live does (`sd_lsx_to_post_type_reviews()` passes
	 * `'column' => '1'`). `review-related-accommodation` resolves through the
	 * property's `review_to_accommodation` meta and the wrapper removes the band
	 * where there are none — which is why the measured page has no review band.
	 */
	?>
	<!-- wp:group {"metadata":{"name":"Accommodation Reviews"},"align":"full","className":"lsx-review-related-accommodation-query-wrapper","layout":{"type":"default"}} -->
	<div class="wp-block-group alignfull lsx-review-related-accommodation-query-wrapper">

		<!-- wp:query {"query":{"perPage":6,"pages":0,"offset":0,"postType":"review","order":"desc","orderBy":"date","search":"","exclude":[],"sticky":"","inherit":false},"hasCustomClass":true,"align":"full","className":"is-style-slider-frame lsx-to-slider sd-slider-nav-hidden sd-slider-flush","layout":{"type":"default"}} -->
		<div class="wp-block-query alignfull is-style-slider-frame lsx-to-slider sd-slider-nav-hidden sd-slider-flush">
			<!-- wp:post-template {"className":"lsx-review-related-accommodation-query","layout":{"type":"grid","columnCount":1}} -->
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
	 * `sd_lsx_to_accommodation_single_content_bottom()` ends with
	 * `sd_call_info_section( "Inspired by this property? Lets' start planning!" )`
	 * inside `.lsx-full-width-base-small`, then `sd_cta_why_choose_section()`
	 * (layout.php:145-153) — the same pair, in the same order, as the tour and
	 * destination singles, with this template's own heading on the first of
	 * them. The Why Choose band carries the Trustpilot score inside it.
	 *
	 * This is why `<main>` above carries no bottom padding: the Why Choose band
	 * brings its own, and a padding on the wrapper would show as a strip of page
	 * ground under a full-bleed section.
	 */
	require __DIR__ . '/cta-inspired-by-this-property.php';
	require __DIR__ . '/why-choose-sd.php';
	?>

</main>
<!-- /wp:group -->
