<?php
/**
 * Title: Template: 404 Not Found
 * Slug: sd-theme-2026/template-page-404
 * Description: 404 error page body — the photographic "404!" banner, then the warm-grey not-found band with a search form, the spelling-suggestions band beneath it, and the two closing CTAs. Used by the 404 template.
 * Categories: hidden
 * Keywords: template, 404, error, not found, search, suggestions
 * Template Types: 404
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 *
 * @package sd-theme-2026
 */

/*
 * Live's 404, converted. Measured from
 * https://www.southerndestinations.com/this-page-does-not-exist-404-test/ on
 * 2026-09-17 against `sd-lsx-child/404.php`, which is the template that draws
 * it. Five bands, in this order:
 *
 *   1. the `#lsx-banner` photograph — "404!" over "Can't find what you're
 *      looking for?"
 *   2. `section.error-404.not-found` — "Nothing Found", a standfirst and the
 *      search form, on the warm-grey ground
 *   3. `section.error-404.not-found.sub-section` — the three spelling
 *      suggestions, on white
 *   4. `#footer-info-cta` — the enquiry band, heading "Feeling Lost?…"
 *   5. `#footer-choose-cta` — the Why Choose band
 *
 * The previous version of this file was a placeholder: a centred "404 /
 * Page not found" stack with authored copy and a "Back to homepage" button,
 * none of which is on live. It is replaced wholesale rather than extended.
 *
 * ## Bands 4 and 5 are in `<main>` here because live puts them there
 *
 * `#footer-info-cta` and `#footer-choose-cta` are *not* 404-specific — they are
 * on every inner page (measured on /contact-us/, which carries both) — so the
 * instinct is to read them as footer chrome and leave them to `parts/footer.html`.
 * They are not: `sd-lsx-child/404.php:55-60` emits them inside `#main`, and so
 * does every other child-theme template, each choosing its own CTA heading. That
 * per-template choice is exactly why they stay in the template patterns in this
 * theme too — → `patterns/cta-not-sure-where-to-go.php`, which records the
 * four-heading switch and why a block theme resolves it by inclusion.
 *
 * Their order here — CTA, then Why Choose — is live's: `sd_call_info_section()`
 * then `sd_cta_why_choose_section()` in `404.php`, the same order
 * `patterns/template-single-destination.php` and the two other single-destination
 * templates use. ⚠️ The archive templates run the pair the other way round. Both
 * orders are in the theme already; this file follows the page it is converting.
 *
 * That pair is also why `<main>` sets no bottom padding — the Why Choose band
 * brings its own and is full-bleed, so a wrapper padding would show as a strip
 * of page ground beneath it.
 *
 * ## One `h1`, where live has two
 *
 * Live prints `<h1 class="page-title">404!</h1>` in the banner *and*
 * `<h1 class="page-title">Nothing Found</h1>` in the body. Two `h1`s in one
 * document is a failure this theme does not port: the banner keeps the `h1`,
 * because it is the page's title and every other template's banner carries it,
 * and "Nothing Found" becomes the `h2` that opens the first body section. No
 * copy changes and nothing moves.
 *
 * ## No breadcrumb bar
 *
 * Every other inner template in this theme requires `patterns/breadcrumbs.php`
 * directly beneath the banner. Live's 404 does not — `404.php` goes straight
 * from `#lsx-banner` to `#primary`, with no call to the breadcrumb partial, and
 * the fetched page confirms it. That is the right behaviour rather than an
 * omission: a trail ending in a page that does not exist has nothing to point at.
 *
 * ## The banner
 *
 * `404-bg-img-1920x790.jpg`, the image live names inline in `404.php` as a CSS
 * `background-image`. Verified present on dev (HTTP 200, 2026-09-17), so it is
 * addressed by its uploads URL like every other banner in this theme — the
 * go-live deploy runs a find-and-replace over the dev host. → AGENTS.md.
 *
 * It is the cover device, `dimRatio`, overlay colour and 360px floor that
 * `patterns/template-archive-destination.php` established and every banner since
 * has used, including `patterns/template-page-search.php`. Live's own 404 banner
 * is taller, but that is a per-page height in the child theme's CSS, not a design
 * decision the rebuild carries — the theme has one banner height.
 *
 * ⚠️ Live's `.page-banner.rotating` means the LSX Banners random-image picker is
 * active on this template, but the 404 has exactly one image hardcoded in
 * `404.php`, so there is nothing to rotate. A single cover is faithful.
 *
 * ## Grounds, sizes and alignment
 *
 * Taken from `sd-lsx-child/assets/css/custom.css`:
 *
 *  - `.error-404.not-found:not(.sub-section)` is `#f7f5f2`, which is neutral-200
 *    — `is-style-tinted-page-section` exactly, and its `spacing|70` padding is
 *    live's `7rem`. The sub-section takes no background, so it is white:
 *    `is-style-light-page-section`.
 *  - `.para-404` is `18px` italic → font size 300 (1.2rem) with `fontStyle`.
 *  - The sub-section's `p` and `ul` are `16px` → font size 200.
 *  - `.list-404` is `list-style-type: none; padding-left: 0` →
 *    `is-style-list-plain`, added with this template. Live bakes the leading
 *    dash into each item's copy; that is content and it is migrated as-is.
 *  - `.copy-wrapper` is `text-align: left`, so both bands are left-aligned on
 *    the `alignwide` rail rather than centred like the placeholder was.
 *
 * ⚠️ **`padding-left: 150px` on `.copy-wrapper` is deliberately not ported.** It
 * is a raw pixel indent with no token behind it and no responsive floor — at
 * narrow widths it eats most of the column — and the theme's rail already sets
 * where a left-aligned band starts. Flagged rather than silently dropped: if the
 * indent is wanted it is a spacing preset on the inner group, not 150px.
 *
 * ## The search form
 *
 * `core/search` with live's own placeholder, "Search Southern Destinations" —
 * not the "Search the site…" string `patterns/template-page-search.php` uses,
 * because this is the string live puts in this field and copy is migrated. The
 * button is `buttonPosition: button-inside` and needs no colour set: live's
 * `.error-404.not-found .btn` is `#cc7f16`, which is brand-500, and theme.json's
 * button element is already brand-500 on base.
 */

?>

<!-- wp:group {"tagName":"main","metadata":{"name":"404"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<!-- wp:cover {"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/08/404-bg-img-1920x790.jpg","alt":"","dimRatio":100,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":360,"minHeightUnit":"px","contentPosition":"bottom center","align":"full","className":"is-style-hero-banner","tagName":"section","metadata":{"name":"Banner"},"style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center is-style-hero-banner" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);min-height:360px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-100 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/08/404-bg-img-1920x790.jpg" data-object-fit="cover"/><div class="wp-block-cover__inner-container">

		<?php
		/*
		 * Flow layout, not constrained, and the heading in the script face at
		 * 800 — the banner contract this theme settled on the destination
		 * archive. Both decisions are reasoned there in full:
		 * → patterns/template-archive-destination.php
		 */
		?>
		<!-- wp:group {"metadata":{"name":"Banner Content"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:heading {"level":1,"className":"is-style-script-accent","fontSize":"800","anchor":"h-404"} -->
			<h1 class="wp-block-heading is-style-script-accent has-800-font-size" id="h-404"><?php echo esc_html_x( '404!', 'error page banner title', 'sd-theme-2026' ); ?></h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"className":"is-style-subheading-large","style":{"typography":{"fontWeight":"var:custom|font-weight|medium"}},"fontFamily":"heading"} -->
			<p class="is-style-subheading-large has-heading-font-family" style="font-weight:var(--wp--custom--font-weight--medium)"><?php esc_html_e( 'Can’t find what you’re looking for?', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:group -->

	</div></section>
	<!-- /wp:cover -->

	<?php
	/*
	 * Band 2 — live's `section.error-404.not-found`, on the warm-grey ground.
	 * The heading is `is-style-section-title-left` rather than the centred
	 * `is-style-section-title` because live's `.copy-wrapper` is
	 * `text-align: left`; the left variant is the same uppercase heading face
	 * with the gold rule sitting flush on the text instead of centred.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Not Found"},"align":"full","className":"is-style-tinted-page-section","layout":{"type":"constrained"}} -->
	<section class="wp-block-group alignfull is-style-tinted-page-section">

		<!-- wp:group {"metadata":{"name":"Not Found Copy"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"720px","justifyContent":"left"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:heading {"className":"is-style-section-title-left","anchor":"h-nothing-found"} -->
			<h2 class="wp-block-heading is-style-section-title-left" id="h-nothing-found"><?php esc_html_e( 'Nothing Found', 'sd-theme-2026' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"fontSize":"300","style":{"typography":{"fontStyle":"italic","fontWeight":"var:custom|font-weight|regular"}}} -->
			<p class="has-300-font-size" style="font-style:italic;font-weight:var(--wp--custom--font-weight--regular)"><?php esc_html_e( 'Sorry the page you’re looking for is unavailable. Maybe you’d like to perform a search?', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:search {"label":"<?php echo esc_attr_x( 'Search', 'search form label', 'sd-theme-2026' ); ?>","showLabel":false,"placeholder":"<?php echo esc_attr_x( 'Search Southern Destinations', 'search form placeholder', 'sd-theme-2026' ); ?>","buttonText":"<?php echo esc_attr_x( 'Search', 'search form button', 'sd-theme-2026' ); ?>","buttonPosition":"button-inside","buttonUseIcon":true} /-->

		</div>
		<!-- /wp:group -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * Band 3 — live's `.sub-section`, on white. The lead line is a
	 * `<p><strong>` on live and stays a paragraph here: it labels the list but
	 * it does not open a section, and promoting it to a heading would put an
	 * `h3` under an `h2` that is not its parent.
	 *
	 * The three items keep their leading dashes, which live writes into the
	 * copy itself — → the note on `is-style-list-plain` at the head of this file.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Search Suggestions"},"align":"full","className":"is-style-light-page-section","layout":{"type":"constrained"}} -->
	<section class="wp-block-group alignfull is-style-light-page-section">

		<!-- wp:group {"metadata":{"name":"Suggestions Copy"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","contentSize":"720px","justifyContent":"left"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:paragraph {"fontSize":"200"} -->
			<p class="has-200-font-size"><strong><?php esc_html_e( 'For best results, mind the following suggestions:', 'sd-theme-2026' ); ?></strong></p>
			<!-- /wp:paragraph -->

			<!-- wp:list {"className":"is-style-list-plain","fontSize":"200"} -->
			<ul class="wp-block-list is-style-list-plain has-200-font-size">
				<!-- wp:list-item -->
				<li><?php esc_html_e( '- Always double check your spelling', 'sd-theme-2026' ); ?></li>
				<!-- /wp:list-item -->

				<!-- wp:list-item -->
				<li><?php esc_html_e( '- Try similar keywords, for example tablet instead of laptop', 'sd-theme-2026' ); ?></li>
				<!-- /wp:list-item -->

				<!-- wp:list-item -->
				<li><?php esc_html_e( '- Try using more than one keyword', 'sd-theme-2026' ); ?></li>
				<!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->

		</div>
		<!-- /wp:group -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * Bands 4 and 5 — the enquiry CTA in its 404 wording, then the Why Choose
	 * band. `require` rather than a nested `wp:pattern` reference: a pattern
	 * referencing another pattern resolves under WP-CLI and is silently dropped
	 * on front-end render. → .claude/skills/wp-pattern-runtime-pitfalls
	 */
	require __DIR__ . '/cta-feeling-lost.php';
	require __DIR__ . '/why-choose-sd.php';
	?>

</main>
<!-- /wp:group -->
