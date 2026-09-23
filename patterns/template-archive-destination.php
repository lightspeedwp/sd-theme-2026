<?php
/**
 * Title: Template: Destinations Archive
 * Slug: sd-theme-2026/template-archive-destination
 * Description: The destinations landing page — the photographic banner, the warm intro band pairing the archive description with the safari expert panel, the grid of 3:2 landscape destination tiles, and the value and contact bands that close the page.
 * Categories: hidden
 * Keywords: destinations, archive, landing, tour operator, grid, banner
 * Block Types: core/query
 * Template Types: archive
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 *
 * @package sd-theme-2026
 */

/*
 * Ported from https://www.southerndestinations.com/destinations/, measured
 * 2026-08-26. Live builds this page from three things Tour Operator and
 * LSX Banners assemble at render time; each is translated to blocks below.
 *
 *   1. `#lsx-banner .page-banner` — the photograph, the script title and the
 *      tagline. LSX Banners' own markup, driven by a per-archive banner image
 *      setting.
 *   2. `section.lsx-to-archive-header-tour` — the `#f7f5f2` band holding the
 *      archive description on the left and `#safari-expert-box` on the right.
 *   3. `.lsx-to-archive-items.lsx-to-archive-template-grid` — the 3-up tile
 *      grid, each tile a photograph under a scrim that lifts on hover.
 *
 * Two bands close the page beneath that grid — the "Why choose Southern
 * Destinations" value band and the "Not sure where to go" contact CTA — added
 * 2026-08-28. They are not on live's destinations archive; they are the pair
 * patterns/template-archive-tour.php already closes with, and the page now ends
 * the way its sibling archive does. Both are `require`d from their own patterns.
 *
 * The banner's type sits on the floor of the photograph rather than centred in
 * it (Zared's, 2026-08-28), and the tiles are 3:2 landscape, as live crops them
 * (Zared's, 2026-09-23). Each is noted at the block it applies to.
 *
 * Live renders the archive description and the archive `<h1>` **twice** — once
 * in `.archive-header-wrapper` above `#primary` and again inside the intro
 * band — and hides the first with
 * `.lsx-to-archive-header-tour + .lsx-to-archive-header { display: none }`
 * (custom.css:1359). Only the visible copy is reproduced; the duplicate is a
 * template artefact, not a design decision, and reproducing it would put a
 * second `<h1>` on the page.
 *
 * ## What is deliberately not here
 *
 * **A banner slider.** Live's wrapper carries `.page-banner.rotating`, but the
 * destinations archive ships exactly one banner image and the class does
 * nothing on this page — the rotation is the homepage's, and it is PHP picking
 * one of eleven images per request rather than a JS slider. Nothing to port.
 *
 * ## Why the banner is inlined rather than `require`d
 *
 * Until 2026-09-22 `patterns/hero-page-banner.php` ported a different device —
 * the About tree's centred `lsx-blocks/lsx-banner-box` — and this file recorded
 * why the two were kept apart. That pattern is now live's `#lsx-banner
 * .page-banner`, the same device this banner is, and as of 2026-09-23 this
 * banner is its markup (see the note at the block).
 *
 * It is still written out here rather than `require`d, because the pattern
 * reads the post: `useFeaturedImage` and `core/post-title`. An archive has no
 * post of its own, so both would resolve against the first destination in the
 * main query. The archive keeps its per-archive photograph and an authored
 * `<h1>`, which is the one substitution the pattern's own notes allow for.
 */

/*
 * Media is addressed by its URL on dev — the same deliberate exception, for the
 * same reasons, that patterns/footer.php sets out at length. The short version:
 * dev holds the real migrated media and is deployed to live wholesale, so a
 * per-environment attachment-ID resolver buys nothing and costs a lookup per
 * asset.
 *
 * The URLs are written literally, as core writes asset URLs. The go-live
 * deployment runs a find-and-replace over the dev host by convention, so they
 * need no code change and no indirection here.
 */
?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Destinations Archive"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php
	/*
	 * The banner.
	 *
	 * `is-style-hero-banner` owns the ground: the 400px floor, the vertical
	 * padding, the scrim and the base type and link colours. Only the
	 * photograph and the composition are here.
	 *
	 * ⚠️ **The scrim is 0% as of 2026-09-10 — the photograph runs at full
	 * brightness.** It was neutral-900 at 45%. Live dims only the homepage and
	 * explicitly clears the overlay on every inner page
	 * (`sd-lsx-child/assets/css/custom.css`:
	 * `body:not(.home) … .page-banner-image:after { background-color: transparent }`),
	 * so bright is what live does and what was asked for. The alpha lives in
	 * one place — the `color-mix()` in `styles/sections/hero-banner.json` — and
	 * putting a scrim back is that one number.
	 *
	 * ⚠️ **`base` type now sits on an undimmed photograph.** The same trade-off
	 * was already taken on the Tour Operator singles on 2026-08-28, so this is
	 * consistent rather than new — but live does not carry white-on-photo on
	 * inner pages at all: it puts the title and tagline on an opaque #ece9e3
	 * plate in #cc7f16 and #60483b. If a banner title ever fails contrast, that
	 * plate is the fix live already ships. → flagged, not adopted here.
	 *
	 * `dimRatio: 100`, which looks wrong and is not. Core's dim classes are an
	 * `opacity` on `.wp-block-cover__background`, and the section style carries
	 * the scrim's alpha inside a `color-mix()` — so any dim below 100 would
	 * multiply the two and the style would stop being the single source of the
	 * scrim. 100 keeps it the only place the alpha is set, which is what makes
	 * the change above a one-line change.
	 *
	 * The photograph is decorative: `alt=""`. It is a mood shot behind the page
	 * title, it is not referred to by the copy, and naming it would put a
	 * description of scenery between the header landmark and the `<h1>`.
	 *
	 * No `id` attribute — that is a per-install value and cannot be right in two
	 * environments at once. → AGENTS.md, "never hardcode … an uploads URL"
	 *
	 * ## On patterns/hero-page-banner.php since 2026-09-23
	 *
	 * This is that pattern's markup, with the two substitutions it names for an
	 * archive: the photograph is this archive's `url` rather than
	 * `useFeaturedImage`, and the title is an authored `core/heading` rather
	 * than `core/post-title` — outside the loop a post title would print the
	 * first destination's name. Everything else is the pattern's, so the phone
	 * stack in `style.css` ("Hero banner — the phone stack") applies here as it
	 * does on every single: below 768px the photograph shrinks to a 3:1 strip
	 * and the title and strapline drop onto a neutral-200 plate beneath it.
	 *
	 * **No inline padding**, which is what the adoption actually changed. The
	 * cover used to carry spacing-40 top and bottom as a `style` attribute; the
	 * pattern leaves padding to `is-style-hero-banner` (spacing-90) so the phone
	 * stack's `padding: 0` can reset it. An inline value outranks that media
	 * query and left a strip of plate above the photograph on phones.
	 */
	?>
	<!-- wp:cover {"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/08/destination-banner.jpg","alt":"","dimRatio":100,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":400,"minHeightUnit":"px","contentPosition":"bottom center","align":"full","tagName":"section","metadata":{"name":"Banner"},"className":"is-style-hero-banner","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center is-style-hero-banner" style="min-height:400px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-100 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/08/destination-banner.jpg" data-object-fit="cover"/><div class="wp-block-cover__inner-container">

		<?php
		/*
		 * Flow layout, not constrained.
		 *
		 * A constrained group re-clamps every unaligned child to `contentSize`,
		 * so the `alignwide` on this group buys the *group* the 1520px rail and
		 * then hands the heading back a 900px one — measured at x=343 in a
		 * 1600px viewport, i.e. indented by 300px instead of sitting on the
		 * rail. Live's banner type starts at the left edge of its container
		 * (`text-align: left; width: 100%`, custom.css:366), so the children
		 * want the full rail and flow is what gives it to them.
		 */
		?>
		<!-- wp:group {"metadata":{"name":"Banner Content"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group alignwide">

			<?php
			/*
			 * The title, in the Joe Hand script face — live's
			 * `body:not(.home) #lsx-banner .container .page-title`, 60px at
			 * weight 200. That pairing is exactly what `is-style-script-accent`
			 * holds, so only the size is set here: the style rests at font-size
			 * 600 (40px) for in-page use and the banner wants 800 (64px).
			 *
			 * Not `core/query-title`. It would render "Archive: Destinations"
			 * or, with `showPrefix` off, the post type's plural label — live's
			 * banner title is the LSX Banners title, which on this archive
			 * happens to read "Destinations" and is editable independently of
			 * the post type's label. Authored, so the two cannot drift.
			 */
			?>
			<!-- wp:heading {"level":1,"metadata":{"name":"Title"},"className":"is-style-script-accent","fontSize":"800"} -->
			<h1 class="wp-block-heading is-style-script-accent has-800-font-size"><?php esc_html_e( 'Destinations', 'sd-theme-2026' ); ?></h1>
			<!-- /wp:heading -->

			<?php
			/*
			 * The tagline. Live's `.tagline` is the heading face at 30px and
			 * weight 600 — `is-style-subheading-large` carries the size and the
			 * snug leading, the family and the weight are set here.
			 *
			 * Sentence case, not uppercase: this is why it is not
			 * `is-style-section-title`, which is the site's other heading-face
			 * treatment and is uppercase by definition.
			 *
			 * A paragraph, not a heading — it is a strapline under the h1, and
			 * it heads nothing.
			 */
			?>
			<!-- wp:paragraph {"metadata":{"name":"Strapline"},"className":"is-style-subheading-large","style":{"typography":{"fontWeight":"var:custom|font-weight|medium"}},"fontFamily":"heading"} -->
			<p class="is-style-subheading-large has-heading-font-family" style="font-weight:var(--wp--custom--font-weight--medium)"><?php esc_html_e( 'Your African adventure starts here!', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:group -->

	</div></section>
	<!-- /wp:cover -->

	<?php
	/*
	 * The breadcrumb bar, directly under the banner and outside it — the same
	 * `patterns/breadcrumbs.php` every other Tour Operator template runs, in the
	 * position live puts it. Filtering what Yoast *puts* in the trail is plugin
	 * work and lives in `sd-enhancements`; the band it sits in is a strip of
	 * theme markup around a third-party block, and it deactivates with the
	 * theme. → AGENTS.md, theme/plugin boundary
	 */
	require __DIR__ . '/breadcrumbs.php';
	?>

	<?php
	/*
	 * The intro band.
	 *
	 * Live's `.lsx-to-archive-header.row` is full-bleed `#f7f5f2` — which is
	 * `neutral-200` to the digit, and `is-style-tinted-page-section` is the
	 * band that owns it. Live's 6.4rem vertical padding is the style's
	 * spacing-70; close enough that adding a fourth padding scale to match it
	 * exactly would cost more than it buys.
	 *
	 * The columns were live's `col-md-7` / `col-md-5` — 58.33% and 41.67% —
	 * then 55% and auto on 2026-08-28. They are **50/50** as of 2026-09-23,
	 * Zared's split, so that the expert card lays out in its wide arrangement
	 * (portrait spanning both rows, name and actions stacked beside it) on
	 * desktop rather than falling back to the narrow one.
	 *
	 * That arrangement is a container query on `.sd-expert` at 36rem (576px,
	 * assets/styles/core-group.css), so the split is what decides it. Against
	 * the 1340px wide rail less the spacing-50 gap, a half column is ~645px at
	 * a 1440px viewport and ~595px at 1280px — both clear of 576px. Below
	 * ~1240px it drops back to the narrow card, and below 782px core stacks the
	 * columns and the card has the full width again.
	 *
	 * The vertical alignment is split the way live splits it: the row is
	 * `align-items: center`, and the description overrides itself back to the
	 * top with `align-self: baseline` (custom.css:1330). So the columns align
	 * top and the expert column re-centres.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Archive Intro"},"align":"full","className":"is-style-tinted-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-group alignfull is-style-tinted-page-section">

		<!-- wp:columns {"verticalAlignment":"top","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
		<div class="wp-block-columns alignwide are-vertically-aligned-top">

			<!-- wp:column {"verticalAlignment":"top","width":"50%"} -->
			<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:50%">
				<?php
				/*
				 * The description. `is-style-archive-intro` carries the italic
				 * and the size; the drop cap it opens with is in
				 * assets/styles/core-paragraph.css, because live gates it on a
				 * breakpoint and a `css`-field `@media` is unwrapped rather
				 * than honoured.
				 *
				 * On live the copy is a Tour Operator *setting*, not post
				 * content — there is no block for it, in TO 2.2 or anywhere
				 * else, so it is authored here. It is the one piece of copy
				 * this file carries, and it is carried verbatim: content is
				 * migrated, not rewritten.
				 *
				 * It is a candidate for a block binding the moment
				 * `sd-enhancements` exposes the TO archive-description setting
				 * as a source — at which point this becomes the fallback rather
				 * than the value. Until then, editing it means editing the
				 * template.
				 */
				?>
				<!-- wp:paragraph {"className":"is-style-archive-intro"} -->
				<p class="is-style-archive-intro"><?php esc_html_e( 'From the thick bushveld of the Kruger in South Africa to the grassy plains of the Masai Mara in East Africa and beyond, Africa is a place of startling contrasts and stupendous beauty. Let us share our favourite destinations and travel insights with you as you prepare to explore and experience this amazing continent.', 'sd-theme-2026' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"verticalAlignment":"center","width":"50%"} -->
			<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%">
				<?php
				/*
				 * The safari expert panel — portrait, name, Call Us, email
				 * action and the Trustpilot badge.
				 *
				 * `require`, not a nested `wp:pattern` reference: a pattern
				 * referencing another pattern resolves under WP-CLI and is
				 * silently dropped on front-end render.
				 * → .claude/skills/wp-pattern-runtime-pitfalls
				 *
				 * On an archive there is no post in context, so
				 * `sd/safari-expert` falls through term → post → pool and
				 * lands on a random member of the expert pool. That is what
				 * live does here too; pin it with the
				 * `sd_enh_safari_expert_id` filter if full-page caching makes
				 * the randomness a problem.
				 */
				require __DIR__ . '/safari-expert.php';
				?>
			</div>
			<!-- /wp:column -->

		</div>
		<!-- /wp:columns -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The grid.
	 *
	 * `inherit: true` — this is an archive template, so the loop is the main
	 * query and Tour Operator's own archive ordering and per-page setting
	 * apply. Live returns all ten destinations alphabetically; that ordering is
	 * TO's, and hardcoding `orderBy` here would override it rather than
	 * reproduce it.
	 *
	 * The tiles are `patterns/card-media-overlay.php` written out inline, with
	 * one difference. **They are 3:2 landscape as of 2026-09-23**:
	 * `aspectRatio: "3/2"` on the featured image, Zared's change. That is live's
	 * shape for this archive. Live flattens the destination tiles alone
	 * (`min-height: 240px; max-height: 240px` at custom.css:1461, against
	 * ~360px columns, which is 3:2), while tours and accommodation run
	 * near-square.
	 *
	 * History: 3/4 portrait until 2026-08-28, then square until 2026-09-23.
	 *
	 * Inline rather than `require`d because the card is shared, and its other
	 * two callers stay square: the regions grid on the country single
	 * (patterns/destination-regions.php) and the destinations on the team
	 * single (patterns/template-single-team.php). Only the landing changed
	 * shape, so only the landing carries the new crop.
	 *
	 * ⚠️ **Keep this copy in step with patterns/card-media-overlay.php.**
	 * Everything but `aspectRatio` is identical: the section style, the
	 * `sdLinkTo`, the scrim padding and the shadowed title. That file's notes
	 * explain each of them. If the other callers go 3:2 too, put the ratio on
	 * the card and go back to the `require`.
	 *
	 * The crop is a block attribute and not CSS, deliberately: `aspectRatio`
	 * serialises as an inline style on the `<img>`, where a `css`-field height
	 * would sit at (0,1,0) and lose to the block library's
	 * `.wp-block-image img{height:auto}`. → AGENTS.md, "Image crops are
	 * `aspectRatio`, never CSS"
	 *
	 * ⚠️ **No pagination and no `query-no-results`.** Both were removed on
	 * 2026-08-28, carried over from Zared's edit. The grid is a bare loop: with
	 * ten destinations and Tour Operator's per-page setting inherited nothing
	 * paginates today, but an archive that returned zero rows would now render
	 * an empty band rather than a message. Restoring the fallback is four lines
	 * and no visual change at any non-empty count.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Destinations"},"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)">

		<!-- wp:query {"queryId":0,"query":{"perPage":12,"pages":0,"offset":0,"postType":"destination","order":"asc","orderBy":"title","search":"","exclude":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[]},"align":"wide","layout":{"type":"default"}} -->
		<div class="wp-block-query alignwide">

			<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":null}} -->
				<!-- wp:group {"metadata":{"name":"Media Overlay Card"},"className":"is-style-media-overlay-card","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"},"sdLinkTo":"post"} -->
				<div class="wp-block-group is-style-media-overlay-card">
					<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"3/2"} /-->

					<!-- wp:group {"metadata":{"name":"Scrim"},"className":"media-overlay-card__scrim","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
					<div class="wp-block-group media-overlay-card__scrim" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
						<!-- wp:post-title {"level":3,"isLink":true,"className":"is-style-shadow-text","style":{"typography":{"textAlign":"center"}}} /-->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->
			<!-- /wp:post-template -->

		</div>
		<!-- /wp:query -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The two closing bands, added 2026-08-28 — the same pair, in the same
	 * order, that patterns/template-archive-tour.php closes with. The value
	 * band over its photograph, then the contact CTA on the tinted ground. That
	 * is why `<main>` above carries no bottom padding: the CTA band brings its
	 * own, and a padding on the wrapper would show as a strip of page ground
	 * under a full-bleed section.
	 *
	 * `require`, not nested `wp:pattern` references — a pattern referencing
	 * another pattern resolves under WP-CLI and is silently dropped on
	 * front-end render. → .claude/skills/wp-pattern-runtime-pitfalls
	 *
	 * ⚠️ The `archive-destination` override these came from held an **inline
	 * expansion** of the CTA, not a reference, and that expansion was stale: it
	 * carried `+1 646-906-8113` (the New York desk) where the pattern carries
	 * `+1-844-292-8240` (the toll-free line the pattern's own note explains),
	 * laid the two offices out as `core/columns` rather than a centred flex row,
	 * and coloured the glyph and number primary-600 rather than neutral-700.
	 * The maintained pattern is required here instead, so this page shows the
	 * same CTA as every other page that uses it. Do not re-inline it.
	 */
	require __DIR__ . '/why-choose-sd.php';
	require __DIR__ . '/cta-not-sure-where-to-go.php';
	?>

</main>
<!-- /wp:group -->
