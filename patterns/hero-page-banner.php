<?php
/**
 * Title: Hero Banner
 * Slug: sd-theme-2026/hero-page-banner
 * Description: The one inner-page banner — a full-bleed featured image on a 360px floor carrying the page title in the Joe Hand script face with an optional strapline under it. Over the photograph from 768px up; below it, on a neutral-200 plate, on phones. One pattern for every post type and template.
 * Categories: sd-theme-2026/hero, sd-theme-2026/pages, sd-theme-2026/tour-operator
 * Keywords: hero, banner, page header, title, tagline, cover
 * Viewport Width: 1400
 * Block Types: core/cover
 * Template Types: page, single, archive
 * Post Types: wp_template
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * What this is, and what it stopped being.
 *
 * Until 2026-09-22 this pattern ported the About Us tree's
 * `lsx-blocks/lsx-banner-box` — centred, an uppercase eyebrow over an uppercase
 * title, heading face throughout. That device is not what live puts at the top
 * of a page. Measured on live at 1440px on `/about-us/`,
 * `/tour/luxury-adventure-cape-town-vic-falls-botswana/` and
 * `/destination/botswana/moremi-game-reserve/`, all three render the *same*
 * device: LSX Banners' `#lsx-banner .page-banner`, left-aligned on the
 * container, the title in Joe Hand at 60px/200, with `.tagline` under it in the
 * heading face at 30px/600. The banner-box only ever appeared inside the
 * content of the About children, never as the page header.
 *
 * So this is now that one device, and the archive banners
 * (`template-archive-destination.php` and its siblings) are its authored twin
 * rather than a separate species. They stay composed inline for now — the
 * distinction those files record is an *image source* one, not a compositional
 * one: an archive has no featured image to read. Rolling them onto this pattern
 * is the next step. → LS-2033 is not involved; this is the funded template work.
 *
 * ## The image is the featured image, not a baked URL
 *
 * `useFeaturedImage` is the whole reason this is one pattern rather than one per
 * post type. Live points four About pages at the same `header-about-us-new.jpg`
 * *by URL*, so the crop, the srcset and the alt text were re-typed per page and
 * drifted. Here each post sets a featured image and core generates the srcset. A
 * post with no featured image falls back to the section ground, which is legible
 * rather than broken.
 *
 * ## Heading levels — deliberately not live's
 *
 * Live emits `<h2 class="lsx-banner-name">` for the eyebrow and a
 * `<p class="lsx-banner-title">` for the page name, while the real
 * `<h1 class="page-title">` is hidden off-screen by
 * `lsx-disabled-hidden-title` — an h2 standing in for the h1, with the h1
 * hidden. Here the page title is the h1 (`core/post-title`, so it stays
 * dynamic) and the strapline is a plain paragraph, because it heads nothing.
 *
 * ## The strapline is a placeholder, on purpose
 *
 * Live's subtitle is dynamic: `sd-lsx-child/classes/class-sd-banner-integration.php`
 * (line 52-71) reads the `banner_subtitle` post meta, falling back to the parent
 * destination's title on `destination` singles and to `reviewer_name` on
 * `review` singles. That filter is registered against `lsx_banner_title` and is
 * **not firing on live today** — every page measured renders the post title
 * straight into the `<h1>` with no `.sd-taglines` at all.
 *
 * Reproducing the fallback chain needs a binding source in `sd-enhancements`,
 * not markup here, so the paragraph below is authored and editable for now. When
 * that source lands, this paragraph gains a `bindings` entry and nothing else
 * about the pattern changes. → AGENTS.md, "theme = design, plugin = behaviour"
 *
 * ## Why a fixed floor and not an aspect ratio
 *
 * **360px as of 2026-09-23** (Zared's call). It was 454 until the tours pass
 * that morning, then 400, and came down again to the 360 the single
 * accommodation banner already stood on, which read best. Live's own computed
 * height is 380px (38rem against a 10px root); 454 was the height live *serves
 * the image at* (1920x454), not the height it shows it at.
 *
 * `is-style-hero-banner` pins the same floor so a short image cannot collapse
 * the band, and every banner pattern repeats it as `minHeight` — change all of
 * them together, or the banners stop being one height. The cover crops rather
 * than letterboxes, which is what live does.
 *
 * ## No padding set here
 *
 * `is-style-hero-banner` carries spacing-90 top, spacing-50 bottom (90 until
 * 2026-09-23 — it left the title sitting too high) and spacing-20 inline, and
 * the mobile stack in `style.css` has to override it. An inline `style` attribute on
 * the block would outrank a stylesheet at any specificity and force an
 * `!important` into that media query for no gain, so the padding stays where the
 * style already puts it. `minHeight` is the one exception — see the note beside
 * the media query in `style.css`.
 *
 * ## No breadcrumbs
 *
 * Live tucks the Yoast trail into the bottom 58px of the banner. This theme runs
 * it as `patterns/breadcrumbs.php` directly *under* the banner and outside it,
 * which is the position every Tour Operator template already places it in.
 * Templates compose the two; the banner does not swallow the trail.
 */

?>
<!-- wp:cover {"useFeaturedImage":true,"dimRatio":100,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":360,"minHeightUnit":"px","contentPosition":"bottom center","align":"full","tagName":"section","metadata":{"name":"Banner"},"className":"is-style-hero-banner","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"constrained"}} -->
<section class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center is-style-hero-banner" style="min-height:360px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-100 has-background-dim"></span><div class="wp-block-cover__inner-container">

	<?php
	/*
	 * Flow layout, not constrained.
	 *
	 * A constrained group re-clamps every unaligned child to `contentSize`, so
	 * the `alignwide` on this group buys the *group* the wide rail and then
	 * hands the heading back the narrow one. Live's banner type starts at the
	 * left edge of its container (`text-align: left; width: 100%`,
	 * sd-lsx-child/assets/css/custom.css:366), so the children want the full
	 * rail and flow is what gives it to them.
	 */
	?>
	<!-- wp:group {"metadata":{"name":"Banner Content"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group alignwide">

		<?php
		/*
		 * The title, in the Joe Hand script face — live's
		 * `body:not(.home) #lsx-banner .container .page-title`, 60px at weight
		 * 200. `is-style-script-accent` holds that pairing, so only the size is
		 * set here: the style rests at font-size 600 (40px) for in-page use and
		 * the banner wants 800 (64px).
		 *
		 * `core/post-title`, not `core/heading` — on a single, an accommodation
		 * or a page the title has to stay dynamic. The archives author theirs as
		 * a heading because an archive has no post to read, which is the one
		 * substitution a template makes when it adopts this pattern.
		 */
		?>
		<!-- wp:post-title {"level":1,"metadata":{"name":"Title"},"className":"is-style-script-accent","fontSize":"800"} /-->

		<?php
		/*
		 * The strapline. Live's `.tagline` is the heading face at 30px and
		 * weight 600 — `is-style-subheading-large` carries the size and the snug
		 * leading, the family and the weight are set here.
		 *
		 * Sentence case, not uppercase: this is why it is not
		 * `is-style-section-title`, which is the site's other heading-face
		 * treatment and is uppercase by definition.
		 *
		 * A paragraph, not a heading — it is a strapline under the h1, and it
		 * heads nothing. Delete it on a post type that has no subtitle; the
		 * banner is composed to read correctly without it.
		 */
		?>
		<!-- wp:paragraph {"metadata":{"name":"Strapline"},"className":"is-style-subheading-large","style":{"typography":{"fontWeight":"var:custom|font-weight|medium"}},"fontFamily":"heading"} -->
		<p class="is-style-subheading-large has-heading-font-family" style="font-weight:var(--wp--custom--font-weight--medium)"><?php esc_html_e( 'Your African adventure starts here!', 'sd-theme-2026' ); ?></p>
		<!-- /wp:paragraph -->

	</div>
	<!-- /wp:group -->

</div></section>
<!-- /wp:cover -->
