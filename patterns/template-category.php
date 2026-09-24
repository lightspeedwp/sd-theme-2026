<?php
/**
 * Title: Template: Blog Archive
 * Slug: sd-theme-2026/template-category
 * Description: Blog archive body for a category, a tag or an author — the photographic banner titled with the queried term or author, the breadcrumb strip, a "Back To Blog" link, the paginated list of post rows, and the value band that closes the page.
 * Categories: hidden
 * Keywords: template, category, tag, author, archive, blog, news, query, tales
 * Block Types: core/query
 * Template Types: category, tag, author
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 *
 * @package sd-theme-2026
 */

/*
 * Ported from https://www.southerndestinations.com/category/rwanda/, measured
 * 2026-09-16. Live assembles the page from four things:
 *
 *   1. `#lsx-banner` — a real, visible banner here, unlike the blog landing.
 *      See "The banner is live's own" below.
 *   2. `.archive-category-title` — a bare `a.back-to-blog` reading
 *      "Back To Blog", and nothing else.
 *   3. `.post-wrapper` — the post rows over the main query, then
 *      `.lsx-pagination` when there is more than one page.
 *   4. `#footer-choose-cta` — the "Why choose Southern Destinations" value
 *      band, which lives in live's footer region and in this theme's
 *      templates.
 *
 * It is the blog landing with the intro band taken out and a back-link put in.
 * → patterns/template-home-blog.php
 *
 * ## One blog archive — category, tag and author
 *
 * Since 2026-09-24 `templates/tag.html` and `templates/author.html` render this
 * pattern too. Until then the tag template pointed at, and author archives
 * fell through (via `archive.html`) to, `template-page-archive` — the
 * KWV base's three-up card grid under a plain uppercase heading, which was
 * never measured against this site and was the one post archive with no hero
 * banner.
 *
 * Live's tag archive (`/tag/fam-trip/`, measured 2026-09-24) is this page with
 * the banner collapsed: the same `a.back-to-blog`, the same `.post-wrapper`
 * rows, the same `#footer-choose-cta`, and its only `h1` is the
 * `display: none` `archive-title`. So a tag reads as a category here, banner
 * and visible `h1` included. The banner is the blog's own, not a new element,
 * and giving the page a heading a sighted reader can see is the project's
 * accessibility floor, not a redesign. Author archives are indexed on dev (Yoast
 * `disable-author` is off) and list the same post rows; date archives are
 * redirected away by Yoast (`disable-date` on), so there is no `date.html`.
 *
 * Nothing below is category-specific. `core/query-title` with `showPrefix`
 * off prints the bare tag name or author display name, and the query inherits.
 * The `sd/term-meta` binding and `sd-enhancements`' TermBanner swap both
 * return early unless the queried object is a `WP_Term` carrying `banner`
 * meta, so a tag, or an author, wears the fallback photograph below.
 *
 * ## What this file replaces
 *
 * The previous pattern here ran a dark "News" `core/cover` hero over a
 * `core/query-title` and a sticky `core/categories` sidebar in a 90/20 column
 * pair. That is the KWV base's news archive, carried across with the theme and
 * never measured against this site — the same pattern, and the same reasoning,
 * as the `template-index-news` removal recorded on
 * patterns/template-home-blog.php. Live's category archive has no sidebar, no
 * dark hero and no categories list; it is a single full-width column under a
 * photograph. So it is replaced rather than left standing as a second,
 * contradictory blog archive.
 *
 * ## The banner is live's own — measured, not restored
 *
 * The blog landing's banner had to be ruled back in because live collapses it
 * to a 50px sliver. This one needs no such decision: the category archive
 * renders the banner at full height. Measured on live 2026-09-16 —
 * `body:not(.home) #lsx-banner .page-banner-wrap .page-banner` carries
 * `min-height: 38rem` at ≥768px (sd-lsx-child/assets/css/custom.css:324), and
 * with LSX's responsive root at 10px on desktop that is 380px of visible
 * photograph. `body` carries `page-has-banner`. The scrim is explicitly
 * cleared on every non-home page, which is why `is-style-hero-banner` runs its
 * overlay at 0% — the reasoning is written out on
 * patterns/template-archive-destination.php and is not repeated here.
 *
 * **The photograph is per-term, as it is on live.** Live's banner image is a
 * setting on the category itself — `/category/rwanda/` draws
 * `uploads/2013/10/header-gorilla-trekking-uganda.jpg`, and a different
 * category draws a different file. The same association is already in the
 * database as `banner` term meta: measured on dev 2026-09-16, 9 of the 11
 * category terms carry one, and `sd-enhancements`' TermMeta module declares
 * the field.
 *
 * The cover declares that source as a `sd/term-meta` binding on `url`, which
 * is where a reader and the Site Editor can see it. The binding cannot paint
 * it on its own — `core/cover` is a static block whose `url` declares no
 * `source` in block.json, so `WP_Block::replace_html()` resolves the value and
 * then leaves the HTML alone — so `sd-enhancements`' TermBanner module swaps
 * the `<img>` on `render_block_core/cover`. The full reasoning, including why
 * Tour Operator's own banner filter cannot serve this page, is on
 * `modules/term-banner.php`; it is not repeated here.
 *
 * The `url` below is therefore the **fallback**, not the banner: it is what
 * the two categories with no `banner` row (Safari Tips, Botswana) wear, and
 * what the page wears with the plugin deactivated. It is deliberately the same
 * file the blog landing uses, so an unset category still reads as the same
 * section of the site.
 *
 * ## The height is the singles' height
 *
 * 360px — ruled 2026-09-16 because the category archive is a list page and
 * the banner was crowding the first row off the fold, and the floor for every
 * hero banner in the theme since 2026-09-23. The blog landing
 * moved with it — patterns/template-home-blog.php — because the two are one
 * section and a step in banner height between them would read as a mistake.
 *
 * ## The `h1` is the term name
 *
 * Live prints the title twice. `h1.page-title` inside the banner container is
 * the visible one and reads "Rwanda" — the bare term. `h1.archive-title`
 * inside `.archive-header-wrapper` reads "Category: Rwanda" and is hidden by
 * `display: none` — the same Tour Operator artefact the team, destinations and
 * blog archives all carry. The visible one is reproduced and the hidden one is
 * dropped, which is the call patterns/template-archive-team.php already took.
 *
 * `core/query-title` with `showPrefix: false` renders exactly live's visible
 * string, and `is-style-script-accent` is registered against
 * `core/query-title` precisely so archive titles can stay dynamic in the
 * banner face. → styles/blocks/heading/script-accent.json
 *
 * ## What is deliberately not here
 *
 * **The intro band.** No "Tales from our trails" standfirst and no Browse By
 * Category shelf. The child theme *defines* `.sd-blog-header` for
 * `.archive.category` (custom.css:1635) but the category template never emits
 * one — measured, the markup goes straight from the breadcrumb strip to
 * `.archive-category-title`. Dead CSS, not a missing element.
 *
 * **`core/term-description`.** Live renders no category description on this
 * page. Adding one is a single block if an editor ever wants it.
 *
 * **The "Not sure where to go" CTA.** Live's blog archives close on the value
 * band alone; the destination and tour archives close on both (decided
 * 2026-08-28). This follows live, as the blog landing does.
 *
 * ## The query inherits
 *
 * `inherit: true` — the loop is the main query, so the category's own
 * pagination and Settings → Reading govern it and the editorial workflow is
 * untouched. Rwanda holds three posts and live prints no pagination for it;
 * `core/query-pagination` hides itself on a single page, so the markup below
 * reproduces that without a condition. → LS-2022
 *
 * `core/query-no-results` is written out and, on this install, will not
 * render: FacetWP Blocks Beta returns `''` for every instance of the block
 * sitewide. Kept anyway — the suppression is a third-party defect, not a
 * property of this template. → LS-2529
 *
 * `require`, not nested `wp:pattern` references — a pattern referencing
 * another pattern resolves under WP-CLI and is silently dropped on front-end
 * render. → .claude/skills/wp-pattern-runtime-pitfalls
 */

?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Category Archive"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php
	/*
	 * The hero banner — `patterns/hero-page-banner.php` with `core/query-title`
	 * in place of the post title. No padding of its own since 2026-09-24:
	 * `is-style-hero-banner` sets it, and an inline `style` would outrank both
	 * that and the phone stack in `style.css`.
	 */
	?>
	<!-- wp:cover {"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/07/about-us-banner.jpg","alt":"","dimRatio":100,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":360,"minHeightUnit":"px","contentPosition":"bottom center","align":"full","className":"is-style-hero-banner","tagName":"section","metadata":{"name":"Banner","bindings":{"url":{"source":"sd/term-meta","args":{"key":"banner","format":"attachment-url"}}}},"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center is-style-hero-banner" style="min-height:360px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-100 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/07/about-us-banner.jpg" data-object-fit="cover"/><div class="wp-block-cover__inner-container">

		<?php
		/*
		 * Flow layout, not constrained — a constrained group re-clamps every
		 * unaligned child to `contentSize` and hands the heading back a 900px
		 * rail. → template-archive-destination.php
		 */
		?>
		<!-- wp:group {"metadata":{"name":"Banner Content"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:query-title {"type":"archive","level":1,"showPrefix":false,"className":"is-style-script-accent","fontSize":"800"} /-->

		</div>
		<!-- /wp:group -->

	</div></section>
	<!-- /wp:cover -->

	<?php
	/*
	 * The breadcrumb bar, directly under the banner — Home, Blog, then the
	 * term or author, as live reads. Yoast alone prints Home / Rwanda; the Blog
	 * crumb came from the LSX parent theme on live and is now
	 * `sd-enhancements`' `Breadcrumbs::add_blog_crumb()`, because the trail's
	 * content is behaviour. The separator is Yoast's sitewide setting.
	 */
	require __DIR__ . '/breadcrumbs.php';
	?>

	<?php
	/*
	 * Live's `.archive-category-title` — a single link back to the blog
	 * landing, sitting above the first row with nothing else in the band.
	 *
	 * It is a paragraph link rather than a `core/button`: live's anchor is a bare
	 * link, and the one plain-link button variation this theme has
	 * (`is-style-link-plain`) paints its label `base` for use over a scrim.
	 *
	 * Re-measured 2026-09-24 on `/category/rwanda/`: 15px, uppercase, a long
	 * left arrow in front, `#cc7f16`. The case and the arrow come from the LSX
	 * *parent* theme (lsx/assets/css/scss/templates/_archive.scss:54-66), which
	 * the first port did not read — it found nothing in the child theme and
	 * carried a sentence-case label at font size 300. Now font size 200 (14-16px)
	 * and `is-style-back-link` carries the case and the arrow.
	 *
	 * The colour is brand-600, not live's brand-500, and that is deliberate:
	 * `#cc7f16` on this light ground is ~3.2:1, under AA for text this size.
	 * brand-600 is the link colour the post byline already uses here for the
	 * same reason.
	 *
	 * `home_url( '/blog/' )` inline is the theme's idiom for a known page —
	 * patterns/homepage-main-content.php and patterns/header.php do the same.
	 *
	 * `is-style-back-link` puts the arrow in front of the label. It is a masked
	 * pseudo-element on the anchor rather than a
	 * character or an `<img>`: it inherits `currentColor`, so the hover state
	 * carries it without a second rule, and its `content` is empty — the shape
	 * is a mask — so the link's accessible name stays "Back To Blog".
	 * → styles/blocks/paragraph/back-link.json
	 *
	 * ## The band is tighter than the section default
	 *
	 * `is-style-light-page-section` opens on spacing|70, which is right for a
	 * content band and too much for a one-line back-link: it put ~70px between
	 * the breadcrumb strip and the link, and another ~70px between the link and
	 * the first post row. Ruled 2026-09-16 — the top padding drops to spacing|40
	 * here and the post list below opens on spacing|30, so the three elements
	 * read as one group under the breadcrumbs instead of three separated bands.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Back To Blog"},"align":"full","className":"is-style-light-page-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"0"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-group alignfull is-style-light-page-section" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:0">

		<!-- wp:paragraph {"align":"wide","className":"is-style-back-link","style":{"elements":{"link":{"color":{"text":"var:preset|color|brand-600"}}}},"textColor":"brand-600","fontSize":"200"} -->
		<p class="alignwide is-style-back-link has-brand-600-color has-text-color has-link-color has-200-font-size"><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Back To Blog', 'sd-theme-2026' ); ?></a></p>
		<!-- /wp:paragraph -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The post list. One full-width column — live has no sidebar on this page
	 * either — with the rows on the wide rail.
	 *
	 * The row is patterns/card-post-list.php, the same card the blog landing
	 * runs. It reproduces live's `.post-wrapper` article exactly: featured
	 * image leading, title, the date · by author · "Posted in:" byline, the
	 * excerpt with its `.../` moretag, and the rule that closes each row
	 * (live's `.lsx-breaker`).
	 *
	 * `blockGap` on the post template rather than the card's own margin: the
	 * parent owns the spacing, and an explicit gap is the only form that agrees
	 * between the editor and the front end. → AGENTS.md
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Posts"},"align":"full","className":"is-style-light-page-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|30"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-group alignfull is-style-light-page-section" style="padding-top:var(--wp--preset--spacing--30)">

		<!-- wp:query {"queryId":0,"query":{"perPage":12,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","search":"","exclude":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[]},"align":"wide","layout":{"type":"default"}} -->
		<div class="wp-block-query alignwide">

			<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"default"}} -->
				<?php require __DIR__ . '/card-post-list.php'; ?>
			<!-- /wp:post-template -->

			<!-- wp:query-pagination {"paginationArrow":"chevron","showLabel":false,"style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
				<!-- wp:query-pagination-previous /-->
				<!-- wp:query-pagination-numbers /-->
				<!-- wp:query-pagination-next /-->
			<!-- /wp:query-pagination -->

			<!-- wp:query-no-results -->
				<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}}} -->
				<p class="has-text-align-center"><?php esc_html_e( 'No stories here yet, check back in regularly as we post often.', 'sd-theme-2026' ); ?></p>
				<!-- /wp:paragraph -->
			<!-- /wp:query-no-results -->

		</div>
		<!-- /wp:query -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The closing band — the value panel over its photograph, which is where
	 * live's category archive ends. This is why `<main>` above carries no
	 * bottom padding: the band brings its own.
	 */
	require __DIR__ . '/why-choose-sd.php';
	?>

</main>
<!-- /wp:group -->
