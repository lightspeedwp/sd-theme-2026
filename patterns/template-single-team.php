<?php
/**
 * Title: Template: Single Team Member
 * Slug: sd-theme-2026/template-single-team
 * Description: The consultant profile — the banner, the tinted summary band pairing the bio with the portrait, the Trustpilot feedback row, the gallery, and the tour, destination and blog shelves, closing on the Why Choose band.
 * Categories: sd-theme-2026/tour-operator
 * Keywords: team, consultant, profile, single, safari, guru, expert, template
 * Viewport Width: 1400
 * Template Types: single
 * Post Types: wp_template
 * Inserter: false
 *
 * @package sd-theme-2026
 */

/*
 * Live's team single, translated to blocks.
 *
 * Measured from https://www.southerndestinations.com/team/camille-rowe/ on
 * 2026-09-02, against sd-lsx-child/assets/css/custom.css:2113-2230 and 4335-4416,
 * and checked against the same member on dev (post 41452), which carries every
 * field the page reads.
 *
 * Live renders seven sections in this order, and so does this file:
 *
 *     #summary      the bio beside the portrait, on the tinted band
 *     #feedback     the Trustpilot badge and three reviews
 *     #gallery      the member's own photographs
 *     #map          "Places {name} has visited"
 *     #tours        connected tours
 *     #destination  connected destinations
 *     #posts        connected blog posts
 *
 * ## Every heading is composed from the member's first name
 *
 * "Meet Camille", "Camille’s client feedback", "Camille’s Wild Adventures",
 * "Camille’s Favourite Tours". Live builds all of them through
 * `sd_first_name_team()` (sd-lsx-child/includes/functions.php:424), which is
 * `current( explode( ' ', $name ) )`.
 *
 * `sd/post-field` with `format: first-name` is that function, and it exists for
 * this template specifically — its docblock in sd-enhancements says so. A
 * binding replaces a block's whole `content` attribute, so the standing half of
 * each heading cannot be static text beside a bound span: the `prefix` and
 * `suffix` args carry it, and this file owns those strings and their
 * translation. The authored text inside each heading is the fallback — what the
 * editor shows, and what renders if the binding cannot resolve a post.
 *
 * The apostrophes are typographic (’), which is the theme's convention and also
 * the only form that survives the trip: a straight `'` inside an `esc_attr_e()`
 * in a block-comment attribute is escaped to `&#039;`, and the block parser
 * reads that comment as JSON without decoding entities, so it would render
 * literally.
 *
 * ## The shelves are Tour Operator's connection queries
 *
 * Each is a `core/query` whose `core/post-template` carries an
 * `lsx-<to>-related-team-query` class. `Query_Loop::query_args_filter()`
 * (tour-operator/includes/classes/blocks/class-query-loop.php:467) reads it and
 * rewrites the query to the ids in the current post's `<to>_to_team` meta; the
 * matching `…-query-wrapper` on the section group removes the band, heading
 * included, when the connection is empty. `to-team` 2.2.0 registers a query
 * variation for each of them (build/blocks/{tour,destination,post}-related-team),
 * so these are the plugin's own keys and not invented ones.
 *
 * Measured on dev against Camille (41452): `tour_to_team` 4, `destination_to_team`
 * 6, `post_to_team` 9 — which is exactly what live's three carousels show.
 *
 * The class goes on the **post-template**, not the query — `query_loop_block_query_vars`
 * is applied by `render_block_core_post_template()`. Same convention as
 * patterns/template-single-destination.php, which has the full note.
 *
 * ## The tiles, and why they are the ones they are
 *
 * - **Tours** — `patterns/card-tour-compact.php`, the tile every Tour Operator
 *   single already shelves a tour in, so a tour looks the same wherever it
 *   appears.
 * - **Destinations** — `patterns/card-media-overlay.php`, the square photograph
 *   with the title over a scrim. Live's destination tile here is the same
 *   image-over-white-panel card as its tours, but the overlay tile is what the
 *   destinations archive and the destination single's regions rail already use,
 *   and it makes a member's destinations read as the same object as the
 *   countries on /destinations/. Zared's call, 2026-09-02.
 * - **Posts** — `patterns/card-post-grid.php`, the tile the homepage "Tales
 *   from our trails" carousel carries. Same three-across carousel, same card.
 *
 * All three at three across, as live's `slidesToShow: 3`. Slick reads that
 * count off the `columns-N` class `core/post-template` emits from its own
 * `layout.columnCount`, so the grid is both the carousel's setting and what the
 * shelf degrades to with JavaScript off. `perPage` is well above what any
 * member connects: the shelf shows three at a time either way, and the count is
 * how deep the carousel runs.
 *
 * ## What this template does not carry, and why
 *
 * - **The socials, the phone and the email as a contact block.** `to-team`'s own
 *   `single-team.html` puts `role`, `contact_email`, `contact_number` and five
 *   social links in a boxed panel beside the bio. Live renders none of it on the
 *   single — only the role, under the name. The email is reached through the
 *   "Get in touch" action below, which is the one live gives.
 * - **Tour Operator's sticky section menu.** `to-team`'s template opens with
 *   `lsx-tour-operator/sticky-menu` and lists five sections. Live has the
 *   equivalent markup and switches it off — `.single .lsx-to-navigation
 *   { display: none !important }` (custom.css:1795). It has never been visible
 *   on a single.
 * - **The collapsing "Summary" heading.** Live's `h2.lsx-to-collapse-title` is
 *   `hidden-lg` and toggles a Bootstrap collapse below 1200px. It is a mobile
 *   accordion over a section that has no second state on desktop; the block
 *   equivalent is a `core/details`, which would change the desktop page to fix
 *   a phone. Left out, as it is on the other three singles.
 *
 * ## Section grounds follow live
 *
 * Only the summary band is tinted: `#collapse-summary .collapse-inner > .row`
 * is full-bleed `#f7f5f2` at 6.4rem (custom.css:2126-2132, which names
 * `.single-lsx-to-team` alongside the accommodation, tour and destination
 * singles) — that is `neutral-200`, and the spacing-70
 * `is-style-tinted-page-section` already carries it. Everything below sits on
 * white. Measured, not assumed: nothing in custom.css tints `#feedback`,
 * `#gallery`, `#tours`, `#destination` or `#posts` on this template.
 *
 * `require`, not `<!-- wp:pattern -->`, for the score badge, the review card
 * and the closing band — a nested pattern reference inside another *pattern* is
 * dropped on front-end render while still resolving under a WP-CLI
 * `do_blocks()` test.
 *
 * References inside a `core/query` loop are fine, which is why the three card
 * patterns below are still written as references: `core/post-template` sets
 * `$GLOBALS['post']` for each row, and the core blocks in those cards read the
 * global post, so losing the block *context* across `render_block_core_pattern()`
 * costs them nothing. A reference inside `sd/trustpilot-reviews` is **not** fine
 * for exactly that reason inverted — a review is not a post, so the only route
 * in is the context, and the context is what the reference throws away. That
 * cost three empty cards on dev until 2026-09-16; the detail is at the block.
 * → .claude/skills/wp-pattern-runtime-pitfalls
 */

?>
<!-- wp:group {"tagName":"main","metadata":{"name":"Team Member Single"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php
	/*
	 * The banner.
	 *
	 * The same device as the other three Tour Operator singles, at the same
	 * 360px floor, so they all open identically: `is-style-hero-banner` owns the
	 * scrim and the type colours, and only the composition is here.
	 *
	 * The image is the member's banner image, not their portrait. Tour
	 * Operator's `Bindings::render_banner_block()` (class-bindings.php:1144)
	 * swaps a cover's background for the `banner_image_id` meta whenever the
	 * cover carries an `lsx/post-meta` binding on `content` — the args are only
	 * a marker; the key it reads is fixed. Measured on dev: Camille's
	 * `banner_image_id` is 51808, `header-team-camille.jpg`, which is the image
	 * live paints into `.page-banner-image`. `useFeaturedImage` stays on
	 * underneath as the fallback, so a member with no banner image set keeps
	 * their portrait rather than rendering an empty scrim.
	 *
	 * **No tagline**, as the destination single has none. `to-team` registers a
	 * `tagline` field and its own template binds a paragraph to it; live's team
	 * banner carries the `<h1>` alone, and no member on dev has the field
	 * populated.
	 *
	 * A flow layout, not constrained, so `alignwide` reaches the children
	 * instead of being re-clamped to the content measure.
	 */
	?>
	<!-- wp:cover {"useFeaturedImage":true,"dimRatio":0,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":360,"minHeightUnit":"px","contentPosition":"bottom center","isDark":false,"align":"full","tagName":"section","metadata":{"name":"Banner","bindings":{"content":{"source":"lsx/post-meta","args":{"key":"banner_image_id"}}}},"className":"is-style-hero-banner","style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-cover alignfull is-light has-custom-content-position is-position-bottom-center is-style-hero-banner" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);min-height:360px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container">

		<!-- wp:group {"metadata":{"name":"Banner Content"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:post-title {"level":1,"metadata":{"name":"Member Name"},"className":"is-style-script-accent","fontSize":"800"} /-->

		</div>
		<!-- /wp:group -->

	</div></section>
	<!-- /wp:cover -->

	<?php
	/*
	 * The breadcrumb bar, directly under the banner — the same
	 * `patterns/breadcrumbs.php` every other single in this theme runs, in the
	 * same position live puts it. The distinction the note on
	 * `patterns/breadcrumbs.php` records: filtering what Yoast *puts* in the
	 * trail is plugin work, but the band it sits in is a strip of theme markup
	 * around a third-party block, and it deactivates with the theme. This file
	 * used to record the opposite under "What this template does not carry";
	 * that note predated the 2026-09-03 decision and has been removed rather
	 * than left to contradict it.
	 *
	 * ⚠️ The trail's *contents* on a team member are still outstanding — Yoast
	 * builds it from `post_parent`, and the team post type's parent-link
	 * handling is LS-2020 item 10.7, in `sd-enhancements`. The band renders
	 * either way; what it says is that item's business.
	 */
	require __DIR__ . '/breadcrumbs.php';
	?>

	<?php
	/*
	 * The summary band — live's `#summary`.
	 *
	 * Two columns at live's `col-md-8` / `col-md-4`: the bio on the left, the
	 * portrait on the right. Unlike the destination single's two equal columns,
	 * because live's split here is 2:1.
	 *
	 * The order inside the left column is live's: the "Meet {name}" heading, the
	 * role beneath it, the bio, then the action.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Team Member Summary"},"align":"full","className":"is-style-tinted-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"summary"} -->
	<section class="wp-block-group alignfull is-style-tinted-page-section" id="summary">

		<!-- wp:columns {"verticalAlignment":"top","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|60"}}}} -->
		<div class="wp-block-columns alignwide are-vertically-aligned-top">

			<!-- wp:column {"verticalAlignment":"top","width":"66.66%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
			<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:66.66%">

				<?php
				/*
				 * Live's `h2.lsx-to-team-name`. `is-style-section-title-left`
				 * rather than the centred `is-style-section-title` the shelves
				 * below carry: this heading opens a column of running text and
				 * live sets it left, with the gold rule under it.
				 */
				?>
				<!-- wp:heading {"metadata":{"name":"Meet Heading","bindings":{"content":{"source":"sd/post-field","args":{"field":"title","format":"first-name","prefix":"<?php esc_attr_e( 'Meet ', 'sd-theme-2026' ); ?>"}}}},"className":"is-style-section-title-left","anchor":"h-summary"} -->
				<h2 class="wp-block-heading is-style-section-title-left" id="h-summary"><?php esc_html_e( 'Meet our safari guru', 'sd-theme-2026' ); ?></h2>
				<!-- /wp:heading -->

				<?php
				/*
				 * Live's `h5.lsx-to-team-job-title` — the bare role value, with
				 * no "Role:" label, because `.lsx-to-meta-data-key` is
				 * `display: none`. A paragraph and not a heading: it labels the
				 * person, it heads nothing.
				 *
				 * `lsx-role-wrapper` removes it when the member has no role.
				 * ⚠️ Note *how* it removes it: `maybe_hide_varitaion()` tests
				 * `taxonomy_exists()` before it falls through to post meta, and
				 * `role` is **both** a `to-team` taxonomy and a `to-team` meta
				 * key. So this wrapper resolves through the taxonomy branch and
				 * hides the paragraph when the member carries no `role` *term*,
				 * while the value printed comes from the *meta*. On dev the two
				 * agree on all fifteen published members (checked 2026-09-02) —
				 * every one has both — but they are two separate fields and a
				 * member could be given one without the other.
				 */
				?>
				<!-- wp:paragraph {"metadata":{"name":"Role","bindings":{"content":{"source":"lsx/post-meta","args":{"key":"role"}}}},"className":"lsx-role-wrapper","style":{"typography":{"fontWeight":"var:custom|font-weight|semi-bold"}},"fontSize":"300","fontFamily":"heading"} -->
				<p class="lsx-role-wrapper has-heading-font-family has-300-font-size" style="font-weight:var(--wp--custom--font-weight--semi-bold)"></p>
				<!-- /wp:paragraph -->

				<?php
				/*
				 * The bio. Live's `.lsx-to-team-content` is the post content,
				 * rendered whole — this template has no `.more-text` collapse to
				 * reproduce, unlike the tour and destination singles, because
				 * custom.js only truncates `.entry-content` on those.
				 *
				 * The gap between the bio's own paragraphs is set here rather
				 * than left to the root `blockGap`: `core/post-content` is a
				 * layout container whose children are authored copy, and at the
				 * root gap the bio read as a stack of separate statements
				 * instead of one passage. `M` is the same step the meta rows
				 * above it use. A `blockGap` belongs on the block markup and
				 * never in a variation JSON — see AGENTS.md.
				 */
				?>
				<!-- wp:post-content {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} /-->

				<?php
				/*
				 * Live's `.lsx-to-enquire-form > a.btn.cta-btn` — "Get in touch",
				 * which opens the site-wide enquiry modal.
				 *
				 * Here it is the member's own address. `sd/post-meta` with
				 * `format: mailto` is the same binding the homepage's "Meet our
				 * safari gurus" cards use for the same button on the same person
				 * (patterns/homepage-safari-gurus.php), so the two agree; the
				 * format returns null rather than a broken `mailto:` when the
				 * stored value is not an address, which leaves the authored
				 * `/contact/` href standing. That fallback is also what renders
				 * for a member with no email at all, and it is the target every
				 * other enquiry action in this theme uses.
				 *
				 * The modal itself is not reproduced: it is a Gravity Forms
				 * dialog with a form handler behind it, which is
				 * `sd-enhancements` work by the deactivation test, and the theme
				 * currently ships no modal parts.
				 */
				?>
				<!-- wp:buttons {"metadata":{"name":"Enquiry Action"},"layout":{"type":"flex"}} -->
				<div class="wp-block-buttons">
					<!-- wp:button {"className":"is-style-fill","metadata":{"name":"Get in touch","bindings":{"url":{"source":"sd/post-meta","args":{"key":"contact_email","format":"mailto"}}}}} -->
					<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Get in touch', 'sd-theme-2026' ); ?></a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->

			</div>
			<!-- /wp:column -->

			<!-- wp:column {"verticalAlignment":"top","width":"33.33%"} -->
			<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:33.33%">

				<?php
				/*
				 * Live's `figure.lsx-to-team-thumb` — a 350 x 350 crop of the
				 * portrait. Square, and not a link: this *is* the member's page.
				 * Image crops are `aspectRatio`, never CSS. → AGENTS.md
				 */
				?>
				<!-- wp:post-featured-image {"aspectRatio":"1","metadata":{"name":"Portrait"}} /-->

			</div>
			<!-- /wp:column -->

		</div>
		<!-- /wp:columns -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The Trustpilot feedback row — live's `#feedback`.
	 *
	 * `#tb-list-review-container` is a flex row capped at 1200px holding four
	 * children at 25% each: the score badge, then three reviews
	 * (custom.css:4335-4396). `core/columns` at 25/75 with the reviews three
	 * across is that row, and it stacks below 782px without a hand-written
	 * media query, which live needs one for.
	 *
	 * ## The whole section is gated on the member having a Trustpilot tag
	 *
	 * `lsx-truspilot-id-wrapper` — the misspelling is the stored meta key and is
	 * preserved deliberately; sd-enhancements says why. Tour Operator's
	 * `maybe_hide_varitaion()` finds no `truspilot-id` query, taxonomy or
	 * special case, falls through to its post-meta branch, converts the hyphens
	 * and reads `truspilot_id` — so a member with no tag loses the band,
	 * heading and badge included.
	 *
	 * That is deliberate rather than incidental. `Trustpilot::context_tag()`
	 * returns `''` when there is no tag, and an empty tag is the *company's*
	 * review list — so without this wrapper a member with no Trustpilot
	 * presence would show three unrelated company reviews under a heading
	 * reading "{their name}’s client feedback". Camille's tag is `Camille`
	 * (dev, 41452).
	 *
	 * The reviews themselves come from `sd/trustpilot-reviews`, which repeats
	 * its inner blocks once per cached review and renders nothing at all when
	 * the cache is empty — a cold cache, a missing `SD_TRUSTPILOT_API_KEY`, or a
	 * tag with no reviews. The cache holds three (`Trustpilot::REVIEW_COUNT`),
	 * which is the row live draws.
	 *
	 * ⚠️ **Nothing renders here until the API key is rotated and set.** The key
	 * that is committed to the `sd-lsx-child` repository must not be reused;
	 * the replacement belongs in `wp-config.php` as `SD_TRUSTPILOT_API_KEY` on
	 * each environment. Until then this band is absent, which is the intended
	 * cold-start behaviour and not a fault in this template. → AGENTS.md
	 *
	 * The `#feedback` anchor is load-bearing beyond this page:
	 * patterns/homepage-safari-gurus.php links each guru's "Read my reviews"
	 * button to `{permalink}#feedback` through `sd/post-field`.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Client Feedback"},"align":"full","className":"is-style-light-page-section lsx-truspilot-id-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"feedback"} -->
	<section class="wp-block-group alignfull is-style-light-page-section lsx-truspilot-id-wrapper" id="feedback">

		<!-- wp:heading {"textAlign":"center","metadata":{"name":"Feedback Heading","bindings":{"content":{"source":"sd/post-field","args":{"field":"title","format":"first-name","suffix":"<?php esc_attr_e( '’s client feedback', 'sd-theme-2026' ); ?>"}}}},"className":"is-style-section-title","anchor":"h-feedback"} -->
		<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-feedback"><?php esc_html_e( 'Client feedback', 'sd-theme-2026' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:columns {"verticalAlignment":"top","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|50"}}}} -->
		<div class="wp-block-columns alignwide are-vertically-aligned-top">

			<!-- wp:column {"verticalAlignment":"top","width":"25%"} -->
			<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:25%">

				<?php
				/*
				 * Live's `#tb-horizon-review` inside the row, and the company's
				 * score rather than the consultant's, because `sd/trustpilot`
				 * reads the business unit.
				 *
				 * The *stacked* badge, not patterns/trustpilot-score.php: live
				 * re-orders and re-labels the badge for this one placement
				 * (sd-lsx-child/assets/css/custom.css:4348) — band word, stars,
				 * "Based on N reviews", mark — and drops the TrustScore figure.
				 * The file it points at carries the full comparison.
				 *
				 * `require`, not a nested pattern reference. See the reviews
				 * block below for what that costs when it is got wrong.
				 */
				require __DIR__ . '/trustpilot-score-stacked.php';
				?>

			</div>
			<!-- /wp:column -->

			<!-- wp:column {"verticalAlignment":"top","width":"75%"} -->
			<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:75%">

				<?php
				/*
				 * The frame, not the track. Slick appends its arrows and its dot
				 * row to the *parent* of the element it initialises — see
				 * assets/js/review-slider.js — and styles/sections/slider-frame.json
				 * positions both against that parent's edges, which is why this
				 * group exists and why `is-style-slider-frame` is on it rather
				 * than on the reviews block. It is the same shape the three
				 * shelves further down the page have, where `core/query` is the
				 * frame and `core/post-template` the track.
				 *
				 * Live slides this row only below 767px
				 * (sd-lsx-child/assets/js/custom.js:358) and leaves it a static
				 * flex row above; the shelves' responsive curve is used instead,
				 * at Zared's direction 2026-09-16, so the reviews are not the one
				 * row on the page with its own behaviour. Desktop is unchanged
				 * either way — three reviews in three slots is what live draws.
				 */
				?>
				<!-- wp:group {"metadata":{"name":"Reviews Slider"},"className":"sd-review-slider is-style-slider-frame","style":{"spacing":{"blockGap":"0","padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"layout":{"type":"default"}} -->
				<div class="wp-block-group sd-review-slider is-style-slider-frame" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">

					<?php
					/*
					 * The grid is the no-JS presentation and the desktop layout
					 * both — three across, which is live's row. The script
					 * removes `is-layout-grid` only at the point Slick takes
					 * over, so a page with no jQuery, no Slick or no JavaScript
					 * still renders the finished row.
					 */
					?>
					<!-- wp:sd/trustpilot-reviews {"metadata":{"name":"Trustpilot Reviews"},"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16px"}} -->
					<?php
					/*
					 * ⚠️ **`require`, never `<!-- wp:pattern ... /-->` here.** The
					 * card was a nested pattern reference until 2026-09-16 and
					 * the row rendered three structurally perfect, completely
					 * empty cards on dev — right classes, right count, no date,
					 * no headline, no text, no name.
					 *
					 * `render_block_core_pattern()` (wp-includes/blocks/pattern.php)
					 * takes no `$block` argument and ends in `do_blocks( $content )`,
					 * which builds a fresh block tree with an **empty available
					 * context**. So `sd/trustpilot-reviews` handed each repeat its
					 * `sdTrustpilotIndex`, `core/pattern` threw it away, and every
					 * `sd/trustpilot-review` binding inside resolved to null —
					 * which is the card's authored fallback, and the card is
					 * authored empty on purpose. A silent failure in both
					 * directions.
					 *
					 * `require` puts the card's blocks in *this* file's parsed
					 * tree, so the repeater's context reaches them the way
					 * `core/post-template`'s reaches its inner blocks.
					 */
					require __DIR__ . '/card-trustpilot-review.php';
					?>
					<!-- /wp:sd/trustpilot-reviews -->

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
	 * The gallery — live's `#gallery`, "Camille’s Wild Adventures".
	 *
	 * `sd/gallery`, from `sd-enhancements`. It reads the same `gallery` meta the
	 * placeholder here used to read through Tour Operator's `lsx/gallery`
	 * binding, and renders live's actual layout: two tiles across the top row,
	 * three across the second, a `+N more` overlay on the fifth, and a lightbox
	 * over the whole set.
	 *
	 * **The placeholder pass this replaces is gone.** It was a `core/gallery`
	 * carrying the `lsx/gallery` binding, which `Bindings::render_gallery_block()`
	 * rewrote into a flat figure of every image — ten tiles for Camille, no
	 * stagger, no cap, no overlay, no lightbox, and `<img src>` with no `srcset`
	 * or `alt` because the binding only has the ID => URL map to work from. The
	 * three empty `core/image` blocks that existed to give the editor something
	 * to show are gone with it; `sd/gallery` previews on the server.
	 *
	 * Worth knowing if this is ever compared against production: **live's
	 * version of this layout does not render.** Envira emits the right classes
	 * and the right `+N more` label, but its own reset leaves the tile box
	 * collapsed to 4px, so the images spill out at 1:1 and the overlay sits over
	 * nothing. The measurement and the four stylesheets responsible are in
	 * `sd-enhancements/blocks/gallery/render.php`. Live is the design intent
	 * here, not the reference rendering.
	 *
	 * Attributes are left at their defaults — five visible, 3:2 tiles,
	 * `lsx-to-gallery` files, lightbox on. The block's own Settings panel
	 * carries all four.
	 *
	 * `lsx-gallery-wrapper` drops the band, heading included, when the meta is
	 * not an array — `maybe_hide_varitaion()`, `'gallery'` branch. That check
	 * reads the meta directly and does not care which block is inside, so it
	 * still fires.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Gallery"},"align":"full","className":"is-style-light-page-section lsx-gallery-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"gallery"} -->
	<section class="wp-block-group alignfull is-style-light-page-section lsx-gallery-wrapper" id="gallery">

		<!-- wp:heading {"textAlign":"center","metadata":{"name":"Gallery Heading","bindings":{"content":{"source":"sd/post-field","args":{"field":"title","format":"first-name","suffix":"<?php esc_attr_e( '’s Wild Adventures', 'sd-theme-2026' ); ?>"}}}},"className":"is-style-section-title","anchor":"h-gallery"} -->
		<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-gallery"><?php esc_html_e( 'Wild Adventures', 'sd-theme-2026' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:sd/gallery {"align":"wide","metadata":{"name":"Team Member Gallery"}} /-->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The map — live's `#map`, "Places Camille has visited".
	 *
	 * The cluster map of the accommodation the member has visited, behind a
	 * click-to-load plate. `sd/team-map` from `sd-enhancements`, not Tour
	 * Operator's `lsx-tour-operator/google-map` variation the destination
	 * summary composes by hand: three separate things on TO 2.2 stand between a
	 * team single and a map — `lsx_to_has_map()` has no `team` case, its
	 * `default` branch wants the post's own coordinates, which a person does
	 * not have, and `lsx_to_map()` discards its own output — so the `lsx/map`
	 * binding renders empty here. The block answers all three in the plugin,
	 * and it emits the plate, the `.lsx-map` data carrier and the marker data
	 * itself, which is why this section is three lines where
	 * patterns/destination-summary.php is forty.
	 *
	 * `lsx-location-wrapper` drops the band, heading included, when
	 * `lsx_to_has_map()` is false — `maybe_hide_varitaion()`, the `'location'`
	 * branch (class-query-loop.php:213) — which is the member with no
	 * plottable connection, maps switched off in Tour Operator's settings, or
	 * no Google Maps API key. The block's own wrapper carries the class too,
	 * because maps.js reaches the plate through it; the two nest harmlessly,
	 * since maps.js walks to the nearest matching ancestor.
	 *
	 * The heading lives here rather than in the block, like every other heading
	 * on this template — composed from the member's first name, and this file
	 * owns the standing halves and their translation.
	 *
	 * **Hidden on phones, because live hides it on phones.** custom.css:2642-2647
	 * puts `#map` in a `@media (max-width: 767px) { display: none }` beside
	 * `#tour-map`, `#destination-map` and `#accommodation-map` — a deliberate
	 * decision about a cluster map on a small screen, not an oversight. Block
	 * Visibility's `small` is that breakpoint and not approximately it: in basic
	 * mode it emits `@media (max-width: 767.98px)` off the `medium` setting,
	 * which is 768px (block-visibility/includes/frontend/visibility-tests/screen-size.php:226-232).
	 * A control rather than a CSS hide, which is the theme's standing rule for
	 * responsive show/hide — same as patterns/homepage-safari-gurus.php.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Map"},"align":"full","className":"is-style-light-page-section lsx-location-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"map","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"small":true}}}}]}} -->
	<section class="wp-block-group alignfull is-style-light-page-section lsx-location-wrapper" id="map">

		<!-- wp:heading {"textAlign":"center","metadata":{"name":"Map Heading","bindings":{"content":{"source":"sd/post-field","args":{"field":"title","format":"first-name","prefix":"<?php esc_attr_e( 'Places ', 'sd-theme-2026' ); ?>","suffix":"<?php esc_attr_e( ' has visited', 'sd-theme-2026' ); ?>"}}}},"className":"is-style-section-title","anchor":"h-map"} -->
		<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-map"><?php esc_html_e( 'Places visited', 'sd-theme-2026' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:sd/team-map {"align":"wide"} /-->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The tours shelf — live's `#tours`, "Camille’s Favourite Tours".
	 *
	 * `tour-related-team` resolves through the member's `tour_to_team` meta —
	 * four on Camille, which is live's carousel exactly. The wrapper removes the
	 * band where a member connects none.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Favourite Tours"},"align":"full","className":"is-style-light-page-section lsx-tour-related-team-query-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"tours"} -->
	<section class="wp-block-group alignfull is-style-light-page-section lsx-tour-related-team-query-wrapper" id="tours">

		<!-- wp:heading {"textAlign":"center","metadata":{"name":"Tours Heading","bindings":{"content":{"source":"sd/post-field","args":{"field":"title","format":"first-name","suffix":"<?php esc_attr_e( '’s Favourite Tours', 'sd-theme-2026' ); ?>"}}}},"className":"is-style-section-title","anchor":"h-tours"} -->
		<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-tours"><?php esc_html_e( 'Favourite Tours', 'sd-theme-2026' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:query {"query":{"perPage":15,"pages":0,"offset":0,"postType":"tour","order":"asc","orderBy":"title","search":"","exclude":[],"sticky":"","inherit":false},"hasCustomClass":true,"align":"wide","className":"is-style-slider-frame lsx-to-slider","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
		<div class="wp-block-query alignwide is-style-slider-frame lsx-to-slider">
			<!-- wp:post-template {"className":"lsx-tour-related-team-query","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16px"}} -->
				<!-- wp:pattern {"slug":"sd-theme-2026/card-tour-compact"} /-->
			<!-- /wp:post-template -->
		</div>
		<!-- /wp:query -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The destinations shelf — live's `#destination`, "Camille’s Favourite
	 * Destinations". Note the singular `id`: it is live's, and it is what any
	 * existing inbound anchor points at.
	 *
	 * `destination-related-team` resolves through `destination_to_team` — six on
	 * Camille, matching live. The tile is the media-overlay card; see the head
	 * of this file for why that one rather than live's panelled card.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Favourite Destinations"},"align":"full","className":"is-style-light-page-section lsx-destination-related-team-query-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"destination"} -->
	<section class="wp-block-group alignfull is-style-light-page-section lsx-destination-related-team-query-wrapper" id="destination">

		<!-- wp:heading {"textAlign":"center","metadata":{"name":"Destinations Heading","bindings":{"content":{"source":"sd/post-field","args":{"field":"title","format":"first-name","suffix":"<?php esc_attr_e( '’s Favourite Destinations', 'sd-theme-2026' ); ?>"}}}},"className":"is-style-section-title","anchor":"h-destination"} -->
		<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-destination"><?php esc_html_e( 'Favourite Destinations', 'sd-theme-2026' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:query {"query":{"perPage":15,"pages":0,"offset":0,"postType":"destination","order":"asc","orderBy":"title","search":"","exclude":[],"sticky":"","inherit":false},"hasCustomClass":true,"align":"wide","className":"is-style-slider-frame lsx-to-slider","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
		<div class="wp-block-query alignwide is-style-slider-frame lsx-to-slider">
			<!-- wp:post-template {"className":"lsx-destination-related-team-query","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16px"}} -->
				<!-- wp:pattern {"slug":"sd-theme-2026/card-media-overlay"} /-->
			<!-- /wp:post-template -->
		</div>
		<!-- /wp:query -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The blog shelf — live's `#posts`, "Read Camille’s Blog".
	 *
	 * `post-related-team` resolves through `post_to_team` — nine on Camille.
	 * Ordered newest first, as live's carousel is; the three shelves above are
	 * alphabetical because live's are, and this one is not.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Blog Posts"},"align":"full","className":"is-style-light-page-section lsx-post-related-team-query-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"anchor":"posts"} -->
	<section class="wp-block-group alignfull is-style-light-page-section lsx-post-related-team-query-wrapper" id="posts">

		<!-- wp:heading {"textAlign":"center","metadata":{"name":"Blog Heading","bindings":{"content":{"source":"sd/post-field","args":{"field":"title","format":"first-name","prefix":"<?php esc_attr_e( 'Read ', 'sd-theme-2026' ); ?>","suffix":"<?php esc_attr_e( '’s Blog', 'sd-theme-2026' ); ?>"}}}},"className":"is-style-section-title","anchor":"h-posts"} -->
		<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-posts"><?php esc_html_e( 'Read the Blog', 'sd-theme-2026' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:query {"query":{"perPage":15,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","search":"","exclude":[],"sticky":"exclude","inherit":false},"hasCustomClass":true,"align":"wide","className":"is-style-slider-frame lsx-to-slider","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
		<div class="wp-block-query alignwide is-style-slider-frame lsx-to-slider">
			<!-- wp:post-template {"className":"lsx-post-related-team-query","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16px"}} -->
				<!-- wp:pattern {"slug":"sd-theme-2026/card-post-grid"} /-->
			<!-- /wp:post-template -->
		</div>
		<!-- /wp:query -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The closing band.
	 *
	 * Live's team single ends on `#footer-choose-cta`, the "Why choose Southern
	 * Destinations" value band with the Trustpilot score inside it — and on that
	 * alone. There is no "Not sure where to go?" enquiry band beneath it, unlike
	 * the destination and tour singles, whose `sd_call_info_section()` call this
	 * template's PHP does not make. The member's own "Get in touch" action is up
	 * in the summary, which is where live puts the ask.
	 *
	 * This is why `<main>` above carries no bottom padding: the band brings its
	 * own, and a padding on the wrapper would show as a strip of page ground
	 * under a full-bleed section.
	 */
	require __DIR__ . '/why-choose-sd.php';
	?>

</main>
<!-- /wp:group -->
