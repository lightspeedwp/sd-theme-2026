<?php
/**
 * Title: Template: Destinations Archive
 * Slug: sd-theme-2026/template-archive-destination
 * Description: The destinations landing page — the photographic banner, the warm intro band pairing the archive description with the safari expert panel, and the grid of destination tiles beneath it.
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
 * **The breadcrumb bar.** Live draws Yoast's breadcrumbs in a 58px `#ece9e3`
 * strip along the bottom of the banner. Breadcrumb output is a filter over a
 * third-party plugin's trail — behaviour, not design — so by the deactivation
 * test it is `sd-enhancements` work and it is not built here. When it lands it
 * goes directly beneath the cover, outside it. → AGENTS.md, theme/plugin boundary
 *
 * ## Why the banner is composed here and not `hero-page-banner`
 *
 * They are two different devices on live, not one device used twice.
 * `patterns/hero-page-banner.php` ports the About tree's
 * `lsx-blocks/lsx-banner-box` — centred, uppercase, heading face, and driven by
 * the page's featured image. This is LSX Banners' `#lsx-banner .page-banner`,
 * which every archive gets: left-aligned, the Joe Hand script face at 60px, a
 * sentence-case strapline under it (custom.css:366-386), and an image that comes
 * from a per-archive setting rather than a post. An archive has no featured
 * image, so `useFeaturedImage` — the reason that pattern is one file rather than
 * four — has nothing to read. Keeping them separate preserves a distinction
 * live actually makes.
 *
 * **A banner slider.** Live's wrapper carries `.page-banner.rotating`, but the
 * destinations archive ships exactly one banner image and the class does
 * nothing on this page — the rotation is the homepage's, and it is PHP picking
 * one of eleven images per request rather than a JS slider. Nothing to port.
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

<!-- wp:group {"tagName":"main","metadata":{"name":"Destinations Archive"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"var:preset|spacing|90"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:var(--wp--preset--spacing--90)">

	<?php
	/*
	 * The banner.
	 *
	 * `is-style-hero-banner` owns the ground: the 454px floor, the vertical
	 * padding, the neutral-900 scrim at 45% and the base type and link colours.
	 * Only the photograph and the composition are here.
	 *
	 * `dimRatio: 100`, which looks wrong and is not. Core's dim classes are an
	 * `opacity` on `.wp-block-cover__background`, and the section style already
	 * carries the scrim's alpha inside a `color-mix()` — so any dim below 100
	 * multiplies the two and the scrim lands at roughly half its intended
	 * weight. 100 lets the style be the single source of the scrim.
	 *
	 * The photograph is decorative: `alt=""`. It is a mood shot behind the page
	 * title, it is not referred to by the copy, and naming it would put a
	 * description of scenery between the header landmark and the `<h1>`.
	 *
	 * No `id` attribute — that is a per-install value and cannot be right in two
	 * environments at once. → AGENTS.md, "never hardcode … an uploads URL"
	 */
	?>
	<!-- wp:cover {"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/08/destination-banner.jpg","alt":"","dimRatio":100,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":454,"minHeightUnit":"px","contentPosition":"center center","align":"full","className":"is-style-hero-banner","tagName":"section","metadata":{"name":"Banner"},"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-cover alignfull is-style-hero-banner" style="min-height:454px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-100 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/08/destination-banner.jpg" data-object-fit="cover"/><div class="wp-block-cover__inner-container">

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
			<!-- wp:heading {"level":1,"className":"is-style-script-accent","fontSize":"800"} -->
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
			<!-- wp:paragraph {"className":"is-style-subheading-large","style":{"typography":{"fontWeight":"var:custom|font-weight|semi-bold"}},"fontFamily":"heading"} -->
			<p class="is-style-subheading-large has-heading-font-family" style="font-weight:var(--wp--custom--font-weight--semi-bold)"><?php esc_html_e( 'Your African adventure starts here!', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:group -->

	</div></section>
	<!-- /wp:cover -->

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
	 * The two columns are live's `col-md-7` / `col-md-5`. The vertical
	 * alignment is split the way live splits it: the row is `align-items:
	 * center`, and the description overrides itself back to the top with
	 * `align-self: baseline` (custom.css:1330). So the columns align top and
	 * the expert column re-centres.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Archive Intro"},"align":"full","className":"is-style-tinted-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-group alignfull is-style-tinted-page-section">

		<!-- wp:columns {"verticalAlignment":"top","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
		<div class="wp-block-columns alignwide are-vertically-aligned-top">

			<!-- wp:column {"verticalAlignment":"top","width":"58.33%"} -->
			<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:58.33%">
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

			<!-- wp:column {"verticalAlignment":"center","width":"41.67%"} -->
			<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:41.67%">
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
	 * The tiles are `patterns/card-media-overlay.php` — the shared grid tile
	 * every Tour Operator archive uses, taken unmodified. Live crops this one
	 * archive flatter than the others (`min-height: 240px; max-height: 240px`
	 * at custom.css:1461, against ~360px columns, so 3:2 where tours and
	 * accommodation run near-square); that difference is **not** carried, by
	 * decision 2026-08-26. One tile shape across every archive beats
	 * reproducing a per-archive override, and it is what
	 * media-overlay-card.json already describes.
	 *
	 * `require`, not a nested `wp:pattern` reference — a pattern referencing
	 * another pattern resolves under WP-CLI and is silently dropped on
	 * front-end render. → .claude/skills/wp-pattern-runtime-pitfalls
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Destinations"},"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--80)">

		<!-- wp:query {"queryId":0,"query":{"perPage":12,"pages":0,"offset":0,"postType":"destination","order":"asc","orderBy":"title","search":"","exclude":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[]},"align":"wide","layout":{"type":"default"}} -->
		<div class="wp-block-query alignwide">

			<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":null}} -->
				<?php require __DIR__ . '/card-media-overlay.php'; ?>
			<!-- /wp:post-template -->

			<!-- wp:query-pagination {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|70"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
				<!-- wp:query-pagination-previous /-->
				<!-- wp:query-pagination-numbers /-->
				<!-- wp:query-pagination-next /-->
			<!-- /wp:query-pagination -->

			<!-- wp:query-no-results -->
				<!-- wp:paragraph {"textColor":"neutral-700","fontSize":"300"} -->
				<p class="has-neutral-700-color has-text-color has-300-font-size"><?php esc_html_e( 'No destinations found.', 'sd-theme-2026' ); ?></p>
				<!-- /wp:paragraph -->
			<!-- /wp:query-no-results -->

		</div>
		<!-- /wp:query -->

	</section>
	<!-- /wp:group -->

</main>
<!-- /wp:group -->
