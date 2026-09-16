<?php
/**
 * Title: Template: Single Post
 * Slug: sd-theme-2026/template-single-post
 * Description: A single blog post — the breadcrumb strip, the article with its date-and-author byline above the title and its categories beneath, the post body, then the tinted closing band carrying three related posts and the previous/next pager, and the Why Choose value band.
 * Categories: hidden
 * Keywords: post, single, blog, news, article, related, pager
 * Template Types: single
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 *
 * @package sd-theme-2026
 */

/*
 * Ported from https://www.southerndestinations.com/12-nights-7-landscapes-one-verdict-namibia-belongs-at-the-top-of-your-list/,
 * measured 2026-09-16 against a second post
 * (/africas-finest-namibias-top-5-eco-retreats/) so nothing here is a property
 * of one article, and against sd-lsx-child/assets/css/custom.css:2742-2810 and
 * lsx-blog-customizer/assets/css/lsx-blog-customizer.css.
 *
 * Live builds the page from four things, in this order:
 *
 *   1. The Yoast breadcrumb strip — Home › Blog › Namibia › {title}.
 *   2. `main > article.post` — the byline, the `h1`, the categories, the body.
 *   3. `.sd-single-post-bottom` — a full-bleed `#f7f5f2` band holding the
 *      "Related Posts" shelf and, beneath it, `nav.post-navigation`.
 *   4. `#footer-choose-cta` — the "Why choose Southern Destinations" value
 *      band, which lives in live's footer region and in this theme's templates.
 *
 * ## This replaces the KWV-era single, it does not amend it
 *
 * The file that stood here came across from the `kwv-theme-2026` base and was
 * never measured against this site. It ran a "← Back to News" link, an author
 * avatar, a 1:1 featured image beside the title in a 60/40 pair, and a bare
 * prev/next row. Live has none of the first three and puts the fourth inside
 * the closing band. Each is removed below with its reason; the pager is kept
 * and moved.
 *
 * ## Live does not render the featured image on a single post
 *
 * Measured on both posts: `article.post` goes byline → `h1` → categories →
 * `.entry-content`, with no `.entry-image` anywhere in the header. The
 * photograph at the top of the Namibia post is a `core/image` *inside the post
 * content* — the author placed it — and the second post, which also carries
 * `has-post-thumbnail`, shows no image at all. The featured image is for the
 * cards: the blog landing row, the related shelf, the homepage carousel.
 *
 * So there is no `core/post-featured-image` here. Putting one back is a new
 * design decision rather than a translation of this one, and it is one block
 * away if Zared wants it. → AGENTS.md, "No redesign"
 *
 * ## The byline is split around the title, and that is live
 *
 * `.post-meta.post-meta-top-first` (date, author) sits *above* the `h1`;
 * `.entry-meta > .post-meta.post-meta-top-last` (categories) sits *below* it.
 * Both are italic 13px. The word "By" is painted `#cc7f16` — `brand-500`
 * exactly — and `span.fn` is given `font-size: 0` purely to swallow the
 * trailing comma after the author's name, a hack with nothing to port.
 *
 * Same three fields, same italic, same brand tint as the blog-landing row in
 * patterns/card-post-list.php, so a post's byline reads the same wherever it
 * appears. The theme sets the treatment on the wrapping group rather than on
 * the post-* blocks inside it: those are dynamic, and the style engine drops
 * `fontStyle` on a dynamic block. → AGENTS.md
 *
 * ## The `h1` is 30px and mixed case
 *
 * `custom.css:951` sets `h1 { font-size: 30px }` and only `h2` carries
 * `text-transform: uppercase`. The heading face and the `#60483b` brown come
 * from the same rule, and `is-style-light-page-section` already supplies that
 * colour through `elements.heading`, so this heading sets nothing but its size.
 * Font size 500 (32px) is the nearest token — the same reading
 * patterns/template-home-blog.php made of the blog landing's 30px heading.
 *
 * The uppercase, letter-spaced 600 the previous version carried was the KWV
 * base's decision, not this site's.
 *
 * ## The closing band is one band, and it is neutral-200
 *
 * `.sd-single-post-bottom` is `margin: 0 -9999rem; padding: 6.4rem 9999rem;
 * background-color: #f7f5f2` — a full-bleed tinted strip, and `#f7f5f2` is
 * `neutral-200` exactly, which is what `is-style-tinted-page-section` paints.
 * Both the related shelf and the pager sit inside it, on the same ground.
 *
 * ## The related shelf is a Tour Operator related query
 *
 * "Posts sharing a category with this one, minus this one" cannot be written in
 * block markup: `core/query`'s `taxQuery` holds fixed term IDs and `exclude`
 * holds fixed post IDs, and both change with every post being viewed. It is
 * also behaviour rather than design — deactivate the theme and the rule should
 * survive — so it belongs in the plugin under the deactivation test.
 *
 * The `core/post-template` therefore carries `lsx-post-related-post-query`, and
 * that name is not decorative. Tour Operator's `Query_Loop::query_args_filter()`
 * matches `/(lsx|facts)-(.*?)-query/` against the post-template's className and
 * runs the matched key through its related-content machinery
 * (tour-operator/includes/classes/blocks/class-query-loop.php:358-372), so this
 * shelf is on the same rails as `lsx-tour-related-tour-query` and
 * `lsx-accommodation-related-accommodation-query` rather than beside them on a
 * bespoke one. The class goes on the post-template and not on the query wrapper
 * because `query_loop_block_query_vars` is applied by
 * `render_block_core_post_template()` — that is the block whose className the
 * filter can see.
 *
 * ## What Tour Operator supplies, and what it does not
 *
 * Measured against the installed plugin, 2026-09-16 — TO 2.2 locally **and** on
 * dev, so this is the shipped behaviour and not a stale local copy.
 *
 * TO 2.2 ships **no post↔post variation**: `src/blocks/` has variations for
 * tour, accommodation, destination, review, special and team, and none for
 * `post`, and the switch in `query_args_filter()` has no `post-related-post`
 * case. Its `default:` branch reads a `post_to_post` connection meta key, which
 * SD's blog posts have never had — TO's own post metabox
 * (includes/metaboxes/config-post.php) connects a post to accommodation,
 * destinations and tours, never to another post. Left alone, that branch would
 * set `post__in` to the post being read and the shelf would show the reader the
 * article they are already on.
 *
 * So the shelf uses TO's plumbing and TO's own extension point for the rule:
 * `sd-enhancements` hooks `lsx_to_query_loop_query_args_post-related-post`,
 * which TO applies at the end of `query_args_filter()`, and swaps that
 * connection lookup for the category match live actually uses. →
 * `SD\Enhancements\Queries::relate_posts_by_category()`.
 *
 * The one TO affordance deliberately **not** taken is the wrapper class. A
 * `core/group` classed `lsx-post-related-post-query-wrapper` would be hidden
 * whole by `maybe_hide_varitaion()` whenever TO flags the key disabled — which
 * is what live does with an empty shelf. It is not usable here: TO flags the
 * key disabled inside the connection lookup, *before* the filter above has had
 * a chance to replace that lookup, so the shelf would never render at all. The
 * plugin filter therefore guarantees a non-empty shelf instead — a post with no
 * categories falls back to the most recent posts, minus itself — on the same
 * reasoning `constrain_safari_gurus_query()` records for its own empty-term
 * fallback: a visibly wrong shelf gets fixed, a silently empty one looks like a
 * template bug and can survive a release.
 *
 * ## The related tile is card-post-grid, whole
 *
 * Live's related card is `.entry-layout` at 33.33% with everything centred:
 * image, title in `#60483b` at 22px with `text-transform: initial`, an italic
 * date, the excerpt. The author, the categories, the "Read More" and the tags
 * are all switched off with `display: none` in the child theme
 * (custom.css:2791-2809).
 *
 * `patterns/card-post-grid.php` is that card — centred title, centred date,
 * centred excerpt — plus a category byline and a tag footer live hides here.
 * It is used whole rather than forked, on the same principle
 * patterns/template-single-team.php shelves its blog posts with: a post looks
 * like the same object wherever it appears. Two lines of difference did not
 * justify a second post tile. Say the word and the byline's `core/post-terms`
 * and the Tags group come out.
 *
 * ## The pager
 *
 * Live: `nav.post-navigation > .nav-links.pager.row`, two `col-sm-6` halves,
 * each an anchor wrapping "Previous Post" / "Next Post" over the adjacent
 * post's title. `core/post-navigation-link` with `linkLabel` renders exactly
 * that shape — label span and title span, both inside the one anchor — so the
 * only thing authored CSS adds is the line break between them, in
 * assets/styles/core-post-navigation-link.css beside the hover rules that file
 * already exists for.
 *
 * `core/columns` rather than a flex group: two fixed halves are what live has,
 * and core stacks columns below 782px by itself.
 *
 * Live also draws a 55px chevron inside each half, pointing the way the link
 * goes. That is `assets/styles/core-post-navigation-link.css`, which carries the
 * measurement and the reasoning for drawing it as a mask rather than as core's
 * `arrow` attribute. Unlike live's, it moves on hover and on keyboard focus.
 *
 * ## What live has that this does not
 *
 * **`footer.footer-meta`** — the sharing row. It renders empty on both posts
 * measured; there is nothing to port. Sharing is plugin work if it is ever
 * wanted back.
 *
 * **The "Not sure where to go?" CTA.** `#footer-cta` is present in live's
 * markup on this page and holds one empty `.lsx-hero-unit`. The page closes on
 * the value band alone, as the blog landing does.
 *
 * `require`, not nested `wp:pattern` references — a pattern referencing another
 * pattern resolves under WP-CLI and is silently dropped on front-end render.
 * → .claude/skills/wp-pattern-runtime-pitfalls
 */

?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Single Post"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php
	/*
	 * The breadcrumb bar. Live draws it in the same 58px strip here as on every
	 * other template — `custom.css:830` only adjusts where it sits relative to
	 * the sticky masthead, which is not a design difference.
	 */
	require __DIR__ . '/breadcrumbs.php';
	?>

	<!-- wp:group {"tagName":"article","metadata":{"name":"Article"},"align":"full","className":"is-style-light-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
	<article class="wp-block-group alignfull is-style-light-page-section">

		<!-- wp:group {"metadata":{"name":"Article Header"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">

			<?php
			/*
			 * Live's `.post-meta.post-meta-top-first` — the date, then "by" and
			 * the author, italic and tinted. `core/post-author` carries the
			 * byline word itself; live lowercases its "By " in CSS, so it is
			 * authored lowercase here rather than transformed.
			 */
			?>
			<!-- wp:group {"metadata":{"name":"Byline"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"},"typography":{"fontStyle":"italic","lineHeight":"var:custom|line-height|body"},"elements":{"link":{"color":{"text":"var:preset|color|brand-500"}}}},"textColor":"brand-500","fontSize":"200","layout":{"type":"flex","flexWrap":"wrap"}} -->
			<div class="wp-block-group has-brand-500-color has-text-color has-link-color has-200-font-size" style="font-style:italic;line-height:var(--wp--custom--line-height--body)">

				<!-- wp:post-date {"format":"F j, Y","isLink":false} /-->

				<!-- wp:post-author {"showAvatar":false,"showBio":false,"byline":"<?php esc_attr_e( 'by', 'sd-theme-2026' ); ?>","isLink":true} /-->

			</div>
			<!-- /wp:group -->

			<!-- wp:post-title {"level":1,"fontSize":"500"} /-->

			<?php
			/*
			 * `.entry-meta > .post-meta.post-meta-top-last` — the categories,
			 * same italic and same tint as the byline above the title.
			 */
			?>
			<!-- wp:post-terms {"term":"category","prefix":"<?php esc_attr_e( 'Posted in: ', 'sd-theme-2026' ); ?>","style":{"elements":{"link":{"color":{"text":"var:preset|color|brand-500"}}}},"textColor":"brand-500","fontSize":"200"} /-->

		</div>
		<!-- /wp:group -->

		<!-- wp:post-content {"layout":{"type":"constrained"}} /-->

	</article>
	<!-- /wp:group -->

	<?php
	/*
	 * `.sd-single-post-bottom`. One tinted band, two things inside it.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Post Footer"},"align":"full","className":"is-style-tinted-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"},"anchor":"related"} -->
	<section class="wp-block-group alignfull is-style-tinted-page-section" id="related">

		<!-- wp:group {"metadata":{"name":"Related Posts"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:heading {"textAlign":"center","metadata":{"name":"Related Heading"},"className":"is-style-section-title","anchor":"h-related"} -->
			<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-related"><?php esc_html_e( 'Related Posts', 'sd-theme-2026' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:query {"queryId":0,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","search":"","exclude":[],"sticky":"exclude","inherit":false,"taxQuery":null,"parents":[]},"align":"wide","layout":{"type":"default"}} -->
			<div class="wp-block-query alignwide">

				<!-- wp:post-template {"className":"lsx-post-related-post-query","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":"16px"}} -->
					<?php require __DIR__ . '/card-post-grid.php'; ?>
				<!-- /wp:post-template -->

			</div>
			<!-- /wp:query -->

		</div>
		<!-- /wp:group -->

		<?php
		/*
		 * `nav.post-navigation`. Core hides whichever link has no adjacent post,
		 * so the newest and oldest posts render one half and no condition is
		 * needed here.
		 *
		 * No `<nav>` landmark: `core/columns` has no `tagName` support, and a
		 * group wrapped around it purely to supply one would add a second,
		 * unlabelled navigation landmark to every post — core's own
		 * `post-navigation-link` ships without one for the same reason.
		 */
		?>
		<!-- wp:columns {"metadata":{"name":"Post Navigation"},"align":"wide","verticalAlignment":"top","style":{"spacing":{"blockGap":"var:preset|spacing|40","padding":{"top":"var:preset|spacing|40"}},"border":{"top":{"color":"var:preset|color|neutral-300","width":"1px"}}}} -->
		<div class="wp-block-columns alignwide are-vertically-aligned-top" style="border-top-color:var(--wp--preset--color--neutral-300);border-top-width:1px;padding-top:var(--wp--preset--spacing--40)">

			<!-- wp:column {"verticalAlignment":"top"} -->
			<div class="wp-block-column is-vertically-aligned-top">
				<!-- wp:post-navigation-link {"textAlign":"left","type":"previous","label":"<?php esc_attr_e( 'Previous Post', 'sd-theme-2026' ); ?>","showTitle":true,"linkLabel":true,"className":"sd-post-nav","fontSize":"200"} /-->
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"verticalAlignment":"top"} -->
			<div class="wp-block-column is-vertically-aligned-top">
				<!-- wp:post-navigation-link {"textAlign":"right","label":"<?php esc_attr_e( 'Next Post', 'sd-theme-2026' ); ?>","showTitle":true,"linkLabel":true,"className":"sd-post-nav","fontSize":"200"} /-->
			</div>
			<!-- /wp:column -->

		</div>
		<!-- /wp:columns -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The closing band — `#footer-choose-cta`, which is where live's single post
	 * ends. This is why `<main>` above carries no bottom padding: the band
	 * brings its own, and a padding on the wrapper would show as a strip of page
	 * ground beneath a full-bleed section.
	 */
	require __DIR__ . '/why-choose-sd.php';
	?>

</main>
<!-- /wp:group -->
