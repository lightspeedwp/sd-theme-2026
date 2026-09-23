<?php
/**
 * Title: Template: Accommodation Archive
 * Slug: sd-theme-2026/template-archive-accommodation
 * Description: The accommodation landing page — the photographic banner, the two-panel Best Price Guarantee and specials band, and the grid of accommodation-type tiles, closing on the brands shelf and the value band.
 * Categories: hidden
 * Keywords: accommodation, lodges, camps, hotels, archive, landing, tour operator, grid, banner
 * Block Types: core/terms-query
 * Template Types: archive
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 *
 * @package sd-theme-2026
 */

/*
 * Ported from https://www.southerndestinations.com/accommodation/, measured
 * 2026-08-31. The third of the three Tour Operator landing pages, and
 * deliberately the same file as `template-archive-tour.php` with one band
 * swapped — the reasoning that applies to all three is recorded on
 * `template-archive-destination.php` rather than repeated here:
 *
 *   - why the banner is composed here and not `hero-page-banner`
 *   - why `dimRatio: 100` is correct against `is-style-hero-banner`
 *   - why the banner content group is flow layout and not constrained
 *   - why media is addressed by its dev URL
 *   - (the breadcrumb bar *is* built here — see below)
 *   - why only one copy of the archive description is rendered
 *
 * ## Values taken from Tour Operator's own settings
 *
 * The banner image, the title and the tagline are the `accommodation` block of
 * `_lsx-to_settings` — `banner` (attachment 51864), `title` and `tagline` —
 * read on dev 2026-08-31, so they are the same three values live renders and
 * not a transcription of the rendered page. They are authored rather than bound
 * for the reason set out on the destinations pattern: there is no block for a
 * Tour Operator archive setting in TO 2.2 or anywhere else.
 *
 * The `description` in that block is the Best Price Guarantee copy below, run
 * together as one string with a newline after the first three words. Live
 * renders it twice — once raw in `.lsx-to-archive-header` and again, split into
 * a heading and a paragraph, inside `#accommodation-cta-header` — and hides the
 * first with `.post-type-archive-lsx-to-accommodation #primary #main
 * .lsx-to-archive-header { display: none }` (custom.css:1449). Only the visible
 * copy is reproduced, and it is reproduced as the two elements it reads as.
 *
 * ## The intro band is not the one the other two archives run
 *
 * Tours and destinations pair the archive description with `#safari-expert-box`.
 * This page has no expert panel — `grep -c safari-expert-box` over the rendered
 * page returns 0. In its place live runs `#accommodation-cta-header`
 * (custom.css:3957), the child theme's `partials/accommodation-search.php`
 * (port inventory K-17, routed THEME): a full-bleed `#f7f5f2` band holding two
 * photographic panels side by side, capped at 1130px.
 *
 * ## The one real difference from the tours archive: the grid lists types
 *
 * Live's accommodation archive does not list accommodation. It lists
 * **accommodation types** — ten tiles reading "Safari Lodges", "Luxury Tented
 * Camps", "Boutique Hotels" and so on. The properties live one level down. So
 * this runs a `core/terms-query` over the `accommodation-type` taxonomy, exactly
 * as the tours archive runs one over `travel-style`, and it carries the same
 * `sd-featured-terms-query` marker class — `SD\Enhancements\TermMeta` registers
 * the `featured` checkbox against **both** taxonomies, and
 * `SD\Enhancements\Queries::arm_featured_terms()` reads the taxonomy off the
 * query block, so the constraint needed no plugin change to reach this page.
 * Dev already holds the flags: twelve of the twenty-six `accommodation-type`
 * terms are ticked.
 *
 * Eleven of those twelve render. Luxury Trains (term 1795) is featured and
 * carries a thumbnail but has a count of 0, so `hideEmpty` drops it — live
 * drops it too, and no action is wanted. The other ⚠️ this file used to carry,
 * Africa's Finest (term 1799) having no `thumbnail` and so falling through to
 * Tour Operator's grey placeholder, is **resolved**: re-measured on dev
 * 2026-09-04 the term has `thumbnail` 51625, and all twelve featured terms now
 * have one.
 *
 * The page size, the column count, the crop and the two inert Tour Operator
 * query markers are all set out on the grid itself, further down this file.
 *
 * ## Where the tiles point
 *
 * Live's tiles link to a SearchWP facet URL — `/search/accommodation/safari+lodges/`
 * — not to the term archive. `card-media-overlay-term.php` links to
 * `get_term_link()`, as it already does on the tours archive, so these land on
 * `templates/taxonomy-accommodation-type.html`. That template is still Tour
 * Operator's stub; routing the tiles at the facet search instead is a decision
 * for whoever builds the search line, not something to hardcode into a card.
 */

// get_post_type_archive_link() returns false when the Special post type is not
// registered — TO Specials deactivated, or a context where its post types have
// not been declared. Falling back to live's literal path keeps the panel
// pointing somewhere real rather than emitting href="".
$sd_specials_archive = get_post_type_archive_link( 'special' );

if ( ! is_string( $sd_specials_archive ) || '' === $sd_specials_archive ) {
	$sd_specials_archive = home_url( '/specials/' );
}
?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Accommodation Archive"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php
	/*
	 * The banner. → template-archive-destination.php for the full reasoning on
	 * `dimRatio`, the decorative `alt` and the flow-layout content group.
	 */
	?>
	<!-- wp:cover {"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/09/accommodation-landing.jpg","alt":"","dimRatio":100,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":400,"minHeightUnit":"px","contentPosition":"bottom center","align":"full","className":"is-style-hero-banner","tagName":"section","metadata":{"name":"Banner"},"style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center is-style-hero-banner" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);min-height:400px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-100 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/09/accommodation-landing.jpg" data-object-fit="cover"/><div class="wp-block-cover__inner-container">

		<!-- wp:group {"metadata":{"name":"Banner Content"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:heading {"level":1,"className":"is-style-script-accent","fontSize":"800","anchor":"h-accommodation"} -->
			<h1 class="wp-block-heading is-style-script-accent has-800-font-size" id="h-accommodation"><?php esc_html_e( 'Accommodation', 'sd-theme-2026' ); ?></h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"className":"is-style-subheading-large","style":{"typography":{"fontWeight":"var:custom|font-weight|medium"}},"fontFamily":"heading"} -->
			<p class="is-style-subheading-large has-heading-font-family" style="font-weight:var(--wp--custom--font-weight--medium)"><?php esc_html_e( 'Africa’s Premium Lodges, Hotels & Safari Camps', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:group -->

	</div></section>
	<!-- /wp:cover -->

	<?php
	/*
	 * The breadcrumb bar, directly under the banner — the same
	 * `patterns/breadcrumbs.php` the destination and tour singles run, in the
	 * same position live puts it. The distinction the note on
	 * `patterns/breadcrumbs.php` records: filtering what Yoast *puts* in the
	 * trail is plugin work, but the band it sits in is a strip of theme markup
	 * around a third-party block, and it deactivates with the theme.
	 */
	require __DIR__ . '/breadcrumbs.php';
	?>

	<?php
	/*
	 * The intro band — live's `#accommodation-cta-header`.
	 *
	 * The ground is `#f7f5f2`, which is `neutral-200` to the digit and what
	 * `is-style-tinted-page-section` owns, so the same band the other two
	 * archives put their intro on carries this one too. Live's 70px vertical
	 * padding is that style's spacing-70.
	 *
	 * The row is `alignwide`, not the `contentSize: 1130px` this pattern
	 * launched with. That 1130px was live's cap (custom.css:3971) taken
	 * literally, and it read as a measure this theme does not otherwise use —
	 * a row narrower than every other wide row on the page for no reason a
	 * reader can see. Re-authored on dev 2026-09-04 against the theme's own
	 * wide measure; the two panels are still an even split, so they are equal
	 * columns rather than weighted ones.
	 *
	 * The columns are top-aligned rather than stretched, and each panel carries
	 * its own `minHeight: 300`. Stretching made the shorter panel's photograph
	 * grow to match the taller one, so the pair's height was decided by whichever
	 * had the longer copy; a floor on both gives live's even pair of plates
	 * without that coupling.
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Archive Intro"},"align":"full","className":"is-style-tinted-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-group alignfull is-style-tinted-page-section">

		<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
		<div class="wp-block-columns alignwide">

			<!-- wp:column {"verticalAlignment":"top"} -->
			<div class="wp-block-column is-vertically-aligned-top">

				<?php
				/*
				 * The Best Price Guarantee panel — live's `#guarantee-cta`
				 * (custom.css:4000). The same panel the accommodation single
				 * carries beside its rating box, and deliberately the same
				 * markup: the brown filigree texture behind a gold heading and
				 * white running copy, centred.
				 *
				 * It is written out rather than `require`d from the
				 * accommodation single's template pattern, which is a whole
				 * template and not a section, so there is nothing there to
				 * include. ⚠️ The two copies must stay in step; if this panel
				 * gains a third sibling it earns a pattern of its own.
				 *
				 * `dimRatio: 0` — the artwork is already neutral-800-dark and
				 * live sets no scrim over it; the overlay colour is declared
				 * anyway so the block degrades to a solid dark panel if the file
				 * is ever missing.
				 *
				 * An `h2`. Live writes an `h3` here and an `h2` in the panel
				 * beside it; the two panels are siblings under the banner's
				 * `h1`, so they take the same level.
				 *
				 * The heading is `is-style-script-accent` at font-size 700,
				 * re-authored on dev 2026-09-04. Live sets this in the heading
				 * face at 24px; the script line is the device the banner and the
				 * homepage already use for a gold accent heading, and at 700 the
				 * two panels' headings read as a matched pair across the row
				 * rather than as one label and one link. The paragraph loses its
				 * explicit 200 and takes the body size for the same reason —
				 * three type sizes inside a 400px plate was one too many.
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

			<!-- wp:column {"verticalAlignment":"top"} -->
			<div class="wp-block-column is-vertically-aligned-top">

				<?php
				/*
				 * The specials panel — live's `.accommodation-specials-cta`
				 * (custom.css:3978): the same cover device on a second
				 * photograph, with the heading and the badge in a row rather
				 * than stacked.
				 *
				 * Live wraps the *whole* panel in one `<a href="/specials/">`,
				 * and so does this now: `sdLinkTo: "custom"` on a group around
				 * the cover, with `sdLinkUrl` the same URL the heading links to.
				 * `SD\Enhancements\GroupLink` extends `core/group` only — a
				 * deliberate limit recorded on that class — which is why the
				 * group is there at all rather than the attribute sitting on the
				 * `core/cover`. It tests the rendered panel for a link already
				 * pointing at the same URL, finds the heading's, and leaves the
				 * overlay presentational, so the panel gains no second tab stop.
				 *
				 * No `sdLinkLabel`. With none set, `GroupLink::label()` falls
				 * back to the first heading inside the group — "View Current
				 * Accommodation Specials", which is both the right name for the
				 * link and already translated through `esc_html_e()` below. A
				 * literal on the attribute would be neither, since there is no
				 * way to put a translated string inside a block comment without
				 * risking the JSON.
				 *
				 * The wrapper is **flow** layout, not the constrained layout the editor
				 * saved. Its only job is to carry the attribute, and a constrained
				 * group picks up `has-global-padding`, which inset this cover by
				 * `spacing|20` a side and left the specials panel visibly narrower
				 * than the guarantee panel beside it. Flow adds nothing.
				 *
				 * The badge is decorative and carries an empty `alt`; it
				 * advertises the same destination the heading names.
				 *
				 * `current-accommodation-bg.jpg` and `specials-badge.svg` are
				 * ported from the child theme's `images/` as theme chrome, not
				 * seeded into the media library — the convention
				 * patterns/why-choose-sd.php and the accommodation single's
				 * guarantee panel already use. ⚠️ `specials-badge.svg` is the
				 * 169px archive plate and is **not** `special-badge.svg`, the
				 * 218px shadowed plate the accommodation single carries; live
				 * ships both and the names differ by one letter.
				 *
				 * The heading takes `base` for its link colour as well as its
				 * text: this cover carries no section style, so an unstyled link
				 * inside it would fall back to the theme's brand link colour
				 * against a dark photograph.
				 *
				 * The content row is `flexWrap: "nowrap"`. Wrapping put the
				 * badge on its own line at the column width this band now runs
				 * at, which read as a second element rather than as the plate
				 * beside the heading.
				 */
				?>
				<!-- wp:group {"metadata":{"name":"Specials Link"},"layout":{"type":"default"},"sdLinkTo":"custom","sdLinkUrl":"<?php echo esc_url( $sd_specials_archive ); ?>"} -->
				<div class="wp-block-group">

					<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/current-accommodation-bg.jpg' ) ); ?>","dimRatio":0,"overlayColor":"neutral-800","isUserOverlayColor":true,"minHeight":300,"minHeightUnit":"px","contentPosition":"center center","tagName":"aside","metadata":{"name":"Accommodation Specials"},"align":"center","style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|40","bottom":"var:preset|spacing|30","left":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
					<aside class="wp-block-cover aligncenter" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--40);min-height:300px"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/current-accommodation-bg.jpg' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-neutral-800-background-color has-background-dim-0 has-background-dim"></span><div class="wp-block-cover__inner-container">

						<!-- wp:group {"metadata":{"name":"Specials Content"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center","justifyContent":"center"}} -->
						<div class="wp-block-group">

							<!-- wp:heading {"className":"is-style-script-accent","style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}},"typography":{"textAlign":"center"}},"textColor":"base","fontSize":"700","anchor":"h-view-current-accommodation-specials"} -->
							<h2 class="wp-block-heading has-text-align-center is-style-script-accent has-base-color has-text-color has-link-color has-700-font-size" id="h-view-current-accommodation-specials"><a href="<?php echo esc_url( $sd_specials_archive ); ?>"><?php esc_html_e( 'View Current Accommodation Specials', 'sd-theme-2026' ); ?></a></h2>
							<!-- /wp:heading -->

							<!-- wp:image {"width":"169px","sizeSlug":"full","linkDestination":"none"} -->
							<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/specials-badge.svg' ) ); ?>" alt="" style="width:169px;height:auto"/></figure>
							<!-- /wp:image -->

						</div>
						<!-- /wp:group -->

					</div></aside>
					<!-- /wp:cover -->

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
	 * The grid — accommodation types, not accommodation.
	 *
	 * The term twin of the tours archive's travel-style grid, down to the marker
	 * class and the card; → template-archive-tour.php for why
	 * `core/terms-query` rather than a Query Loop, why the class sits on the
	 * query rather than on the term template, and what happens when nothing is
	 * flagged featured.
	 *
	 * ## Featured-only is working, and it is the whole of what bounds this grid
	 *
	 * `sd-featured-terms-query` is the only constraint, and re-measured on dev
	 * 2026-09-04 it is doing its job: twelve of the twenty-six
	 * `accommodation-type` terms carry `featured`, and the page renders
	 * **eleven** tiles — Luxury Trains (term 1795) is featured but has a count
	 * of 0, so `hideEmpty` drops it, exactly as live drops it. The ⚠️ this file
	 * used to carry about Africa's Finest (term 1799) rendering Tour Operator's
	 * grey placeholder is **resolved**: the term now has `thumbnail` 51625.
	 * All twelve featured terms have a thumbnail and all twelve are top-level.
	 *
	 * `perPage: 33` is a ceiling above the twenty-six terms in the taxonomy, not
	 * a page size — the featured filter, not the page size, decides what shows,
	 * and raising the ceiling above the *unfiltered* count means adding a
	 * thirteenth featured term is a tick on a term and nothing here. There is no
	 * term pagination block, and live pages nothing here either
	 * (`disable_archive_pagination` is on in the `accommodation` settings).
	 * Alphabetical by name, as the other two grids are; live's order is the
	 * stored term order, which no block query exposes.
	 *
	 * ⚠️ `parents-only` and `custom-order` were tried on this query on dev and
	 * are **inert**, so they are not carried here. Both are Tour Operator query
	 * markers and its handler allow-lists `core/query` only
	 * (tour-operator/includes/classes/blocks/class-query-loop.php:288-317) — a
	 * `core/terms-query` never reaches it. `parents-only` would be a no-op
	 * anyway, since all twelve featured terms are already `parent = 0`, and
	 * `custom-order` remains the thing no block query exposes.
	 *
	 * ## Two columns, and a 16/9 crop only here
	 *
	 * Live runs two columns and so does this, re-authored on dev 2026-09-04 —
	 * which reverses the note on `template-archive-tour.php` that the rebuild's
	 * archives share one column count. Eleven type tiles at three-up left a
	 * ragged last row; at two-up they read as live's pairs.
	 *
	 * A square tile is a very tall photograph at half the wide measure, so the
	 * card is asked for `16/9` here through `$sd_card_aspect_ratio`. That is
	 * this grid only: the card's default stays square. The tours archive went
	 * two-up as well on 2026-09-23 and asks for `3/2` the same way.
	 * → card-media-overlay-term.php
	 */
	$sd_card_aspect_ratio = '16/9';
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Accommodation Types"},"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)">

		<!-- wp:terms-query {"termQuery":{"perPage":33,"taxonomy":"accommodation-type","order":"asc","orderBy":"name","include":[],"hideEmpty":true,"showNested":false,"inherit":false},"align":"wide","className":"sd-featured-terms-query","layout":{"type":"default"}} -->
		<div class="wp-block-terms-query alignwide sd-featured-terms-query">

			<!-- wp:term-template {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":2,"minimumColumnWidth":null}} -->
				<?php require __DIR__ . '/card-media-overlay-term.php'; ?>
			<!-- /wp:term-template -->

		</div>
		<!-- /wp:terms-query -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The two closing bands live runs beneath this archive —
	 * `#footer-accommodation-brands` and `#footer-choose-cta`. Both are shared
	 * section patterns; `require` rather than a nested `wp:pattern` reference,
	 * because a pattern referencing another pattern resolves under WP-CLI and is
	 * silently dropped on front-end render.
	 * → .claude/skills/wp-pattern-runtime-pitfalls
	 *
	 * The brands shelf is the same `accommodation-brand` carousel the homepage
	 * runs, and it is the homepage pattern that is included. One difference is
	 * carried knowingly: live's widget on this page renders with no title, where
	 * the homepage's has "We only work with Africa's finest". The pattern keeps
	 * its heading. A five-up row of unlabelled logos is not a section, and the
	 * heading is true of this page as much as of the homepage — flagged rather
	 * than silently dropped, because it is the one place this file departs from
	 * what live renders.
	 */
	require __DIR__ . '/homepage-brands.php';
	require __DIR__ . '/why-choose-sd.php';
	?>

</main>
<!-- /wp:group -->
