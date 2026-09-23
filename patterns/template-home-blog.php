<?php
/**
 * Title: Template: Blog Landing
 * Slug: sd-theme-2026/template-home-blog
 * Description: The blog landing page — the photographic banner, the breadcrumb strip, a warm intro band carrying the "Tales from our trails" standfirst and the Browse By Category shelf, then the paginated list of post rows and the value band that closes the page.
 * Categories: hidden
 * Keywords: blog, landing, news, index, home, posts, archive, tales
 * Block Types: core/query
 * Template Types: home, index
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 *
 * @package sd-theme-2026
 */

/*
 * Ported from https://www.southerndestinations.com/blog/, measured in the
 * browser on 2026-09-16. Live assembles the page from four things:
 *
 *   1. `#lsx-banner` — which on this page holds nothing but the breadcrumb
 *      trail. See "The banner is restored" below.
 *   2. `.sd-blog-header` — a warm band carrying the "Tales from our trails"
 *      heading, the italic standfirst and the `#categories-slider` shelf.
 *   3. `.post-wrapper` — twelve post rows over the main query, then
 *      `.lsx-pagination`.
 *   4. `#footer-choose-cta` — the "Why choose Southern Destinations" value
 *      band, which lives in live's footer region and in this theme's
 *      templates.
 *
 * ## The banner is restored — a decision, not a measurement
 *
 * Live has no *visible* banner here, and that was measured rather than
 * assumed. LSX Banners *is* configured on the blog — `.page-banner` carries
 * `min-height: 450px` as an inline style and points at
 * `uploads/2019/03/blog_header.jpg` — but the child theme overrides the floor
 * to 50px, so the banner computes to a 50px sliver whose only visible content
 * is the breadcrumb bar sitting absolutely inside it. Measured on live
 * 2026-09-16: `.page-banner` height 50px, `#lsx-banner` height 50px,
 * `.breadcrumbs-container` height 58px. The photograph is never seen.
 *
 * This file previously recorded that as "so the page opens on the breadcrumb
 * strip, and adding the banner back would be a redesign". **Zared took that
 * call on 2026-09-16 and ruled the banner in**, so the blog landing now opens
 * on a `core/cover` like every other landing page in the theme — at the 360px
 * floor the Tour Operator singles use rather than the 400px the page landings
 * do, for the reason recorded on the block itself.
 * The note is corrected here rather than left to contradict the markup below.
 *
 * It is a restoration of a banner live already configures rather than a new
 * element, it reuses `is-style-hero-banner` and the archive banner's exact
 * construction, and it is the blog landing's own funded line — so it is not a
 * Change-Control Register entry. → AGENTS.md, "Detail that's obviously part of
 * the element you're already building"
 *
 * ## The `h1` is "Blog", in the banner
 *
 * Live puts `<h1 class="archive-title">Blog</h1>` inside
 * `.archive-header-wrapper` and sets that wrapper to `display: none` —
 * measured, not inferred. The same Tour Operator artefact the team and
 * destinations archives carry. With the banner restored that heading finally
 * has somewhere visible to sit, so it is the banner title, and the page keeps
 * exactly one `h1`.
 *
 * "Tales from our trails" is therefore an `h2`. It is live's
 * `.sd-blog-header h3` — 30px, semi-bold, title case, left-aligned, in the
 * warm brown the tinted band already supplies. Font size 500 (32px) is the
 * nearest token; `h2` is the level that follows the banner's `h1` without
 * skipping one.
 *
 * The same string opens the homepage news carousel, where it is a centred
 * uppercase `is-style-section-title` — patterns/homepage-tales-from-our-trails.php.
 * Live styles the two differently and so does this theme; that is not a drift.
 *
 * ## The query inherits, so the editorial workflow is untouched
 *
 * `inherit: true` — the loop is the main query, so Settings → Reading governs
 * how many posts a page holds and the blog's own permalink structure governs
 * pagination. Live runs twelve to a page, and `perPage` is set to 12 to match —
 * but with `inherit: true` core ignores it, so nothing here actually pins a
 * number. That is task 12.5 in practice: an editor who changes the
 * posts-per-page setting, or publishes, schedules or back-dates a post, sees
 * exactly what they saw before. → LS-2022
 *
 * `core/query-no-results` is written out and, on this install, will not render:
 * FacetWP Blocks Beta returns `''` for every instance of the block sitewide
 * (`facetwp-blocks-beta/includes/class-blocks-integration.php:724`). For an
 * inheriting loop like this one core also prints its own empty-query notice
 * inside `core/post-template`, so the page does not fall silent — but the
 * markup is kept rather than deleted, because the suppression is a third-party
 * defect and not a property of this template. → LS-2529
 *
 * ## What is deliberately not here
 *
 * **The "Browse By Category" heading.** Live has it in the markup and hides it
 * with `display: none`. The reasoning is on patterns/blog-categories.php.
 *
 * **The "Not sure where to go" CTA.** Every other archive in this theme closes
 * on the value band *and* that CTA (decided 2026-08-28 — see
 * patterns/template-archive-destination.php). Live's blog landing closes on the
 * value band alone, and this follows live. Adding it is one `require`.
 *
 * **An Archive sidebar.** The pattern this file replaces — `template-index-news`
 * — ran a dark "News" cover hero and a sticky `core/categories` sidebar down
 * the right-hand side. Neither is on the live blog landing: the page is a
 * single full-width column, and the categories are the shelf in the intro band.
 * That pattern came across from the KWV base and was never measured against
 * this site, so it is removed rather than left as a second, contradictory blog
 * landing. Its template, `templates/index.html`, now points here alongside the
 * new `templates/home.html`.
 *
 * `require`, not nested `wp:pattern` references — a pattern referencing another
 * pattern resolves under WP-CLI and is silently dropped on front-end render.
 * → .claude/skills/wp-pattern-runtime-pitfalls
 */

?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Blog Landing"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php
	/*
	 * The banner. → template-archive-destination.php for the full reasoning on
	 * `dimRatio`, the decorative `alt` and the flow-layout content group.
	 *
	 * The image is `uploads/2019/07/about-us-banner.jpg` — the old About Us
	 * banner, nominated 2026-09-16. Live's own blog banner image
	 * (`uploads/2019/03/blog_header.jpg`) is never visible behind the collapsed
	 * 50px sliver, so there is no "the live one" to carry across; this is a
	 * chosen photograph, and swapping it later is a one-line change.
	 *
	 * **360px, not the 400px the page landings use** — ruled 2026-09-16, with
	 * the category archive, because the banner was crowding the first post row
	 * off the fold on both. It is the floor the two Tour Operator singles
	 * already carry (patterns/destination-banner.php,
	 * patterns/template-single-tour.php), so the list pages now open at the
	 * same height as the pages they lead to. The two blog pages move together:
	 * a step in banner height between the landing and a category would read as
	 * a mistake. → patterns/template-category.php
	 */
	?>
	<!-- wp:cover {"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/07/about-us-banner.jpg","alt":"","dimRatio":100,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":360,"minHeightUnit":"px","contentPosition":"bottom center","align":"full","className":"is-style-hero-banner","tagName":"section","metadata":{"name":"Banner"},"style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center is-style-hero-banner" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);min-height:360px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-100 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/07/about-us-banner.jpg" data-object-fit="cover"/><div class="wp-block-cover__inner-container">

		<!-- wp:group {"metadata":{"name":"Banner Content"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group alignwide">

			<?php
			/*
			 * "Blog" — live's own `<h1 class="archive-title">`, which live
			 * authors and then hides with `display: none` on
			 * `.archive-header-wrapper`. Bringing the banner back gives that
			 * heading somewhere visible to live, so the string is live's rather
			 * than invented. Authored, not `core/query-title`, for the reason
			 * template-archive-destination.php sets out.
			 */
			?>
			<!-- wp:heading {"level":1,"className":"is-style-script-accent","fontSize":"800"} -->
			<h1 class="wp-block-heading is-style-script-accent has-800-font-size"><?php esc_html_e( 'Blog', 'sd-theme-2026' ); ?></h1>
			<!-- /wp:heading -->

		</div>
		<!-- /wp:group -->

	</div></section>
	<!-- /wp:cover -->

	<?php
	/*
	 * The breadcrumb bar, directly under the banner — the same band in the same
	 * position as every other template.
	 */
	require __DIR__ . '/breadcrumbs.php';
	?>

	<?php
	/*
	 * The intro band — live's `.sd-blog-header`, which computes to
	 * `rgb(247, 245, 242)`: neutral-200 exactly, which is what
	 * `is-style-tinted-page-section` paints. The heading colour comes from the
	 * band too, so nothing here sets a colour.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Blog Intro"},"align":"full","className":"is-style-tinted-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-group alignfull is-style-tinted-page-section">

		<!-- wp:group {"metadata":{"name":"Standfirst"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:heading {"level":2,"fontSize":"500"} -->
			<h2 class="wp-block-heading has-500-font-size"><?php esc_html_e( 'Tales from our trails', 'sd-theme-2026' ); ?></h2>
			<!-- /wp:heading -->

			<?php
			/*
			 * Live's `.blog-description` — italic at 18px, opening on a
			 * brand-500 drop cap in the heading face. `is-style-archive-intro`
			 * is that exact device; it is the style the destinations and tours
			 * archives already use for their standfirst, and the drop cap
			 * itself lives in assets/styles/core-paragraph.css because live
			 * gates it at 900px and a `css`-field `@media` is unwrapped rather
			 * than honoured.
			 *
			 * The copy is carried verbatim — content is migrated, not
			 * rewritten — minus the run of whitespace live emits mid-sentence
			 * after "advice,", which is markup noise. On live this string is a
			 * theme setting rather than post content, so it is authored here;
			 * editing it means editing the template until `sd-enhancements`
			 * exposes the setting as a binding source.
			 */
			?>
			<!-- wp:paragraph {"className":"is-style-archive-intro"} -->
			<p class="is-style-archive-intro"><?php esc_html_e( 'Our African travel blog features a smorgasbord collection of articles ranging from updates from the bush, safari stories, travel tips and advice, conservation matters, industry gossip and insightful news and reviews about travel in Southern and East Africa. With contributions by travel experts with a passion for Africa, you will be inspired, informed and prepared for your next African adventure.', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:group -->

		<?php require __DIR__ . '/blog-categories.php'; ?>

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The post list. One full-width column — live has no sidebar here — with
	 * the rows on the wide rail, which is where live's 1140px container lands
	 * against this theme's scale.
	 *
	 * `blockGap` at spacing|50 on the post template rather than leaving the
	 * card's own bottom margin to do it: the parent owns the spacing, and an
	 * explicit gap is the only form that agrees between the editor and the
	 * front end. → AGENTS.md
	 *
	 * The row is patterns/card-post-list.php — the card built for this page
	 * and for the category archives, image leading and body trailing, closing
	 * on a rule. This is its first use.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Posts"},"align":"full","className":"is-style-light-page-section","layout":{"type":"constrained"}} -->
	<section class="wp-block-group alignfull is-style-light-page-section">

		<!-- wp:query {"queryId":0,"query":{"perPage":12,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","search":"","exclude":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[]},"align":"wide","layout":{"type":"default"}} -->
		<div class="wp-block-query alignwide">

			<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"default"}} -->
				<?php require __DIR__ . '/card-post-list.php'; ?>
			<!-- /wp:post-template -->

			<?php
			/*
			 * Live centres its pagination and prints numbers with an ellipsis
			 * and a trailing "Next →"; core hides Previous on the first page by
			 * itself, so the flex row reproduces that without a condition.
			 */
			?>
			<!-- wp:query-pagination {"paginationArrow":"chevron","showLabel":false,"style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
				<!-- wp:query-pagination-previous /-->
				<!-- wp:query-pagination-numbers /-->
				<!-- wp:query-pagination-next /-->
			<!-- /wp:query-pagination -->

			<!-- wp:query-no-results -->
				<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}}} -->
				<p class="has-text-align-center"><?php esc_html_e( 'No stories yet, check back in regularly as we post often.', 'sd-theme-2026' ); ?></p>
				<!-- /wp:paragraph -->
			<!-- /wp:query-no-results -->

		</div>
		<!-- /wp:query -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The closing band — the value panel over its photograph, which is where
	 * live's blog landing ends. This is why `<main>` above carries no bottom
	 * padding: the band brings its own, and a padding on the wrapper would show
	 * as a strip of page ground beneath a full-bleed section.
	 */
	require __DIR__ . '/why-choose-sd.php';
	?>

</main>
<!-- /wp:group -->
