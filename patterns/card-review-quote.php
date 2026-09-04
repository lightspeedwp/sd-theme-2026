<?php
/**
 * Title: Card — Review (Quote)
 * Slug: sd-theme-2026/card-review-quote
 * Description: The full-bleed review slide the carousels on every Tour Operator single carry — a gold quote mark over the reviewer's photograph, the review headline, an extract and a Read More link.
 * Categories: sd-theme-2026/card, sd-theme-2026/testimonial, sd-theme-2026/tour-operator
 * Keywords: card, review, testimonial, quote, carousel, slide
 * Viewport Width: 1200
 * Block Types: core/post-template
 * Template Types: single
 * Post Types: review
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * What this replaces — live's `sd_lsx_to_post_type_reviews()` slide.
 *
 * `.lsx-to-widget-item-wrap.lsx-review > article.review`, measured from
 * /tour/botswana-victoria-falls-safari/ on 2026-08-28. Live paints the review's
 * featured image as the `<article>`'s own `background-image` at
 * `background-size: cover; min-height: 500px` and centres a `<blockquote>` over
 * it: a gold quote mark, the review headline in white, an extract, and a "Read
 * More" link in gold (custom.css:1919-1968).
 *
 * A `core/cover` with `useFeaturedImage` is the same device with the image in
 * the markup rather than in an inline style, so core writes the `srcset` and
 * the crop instead of the theme serving one 1920px JPEG to a phone.
 *
 * ## The scrim is new, and it is not a design change
 *
 * Live sets white type directly on the photograph with no overlay. It survives
 * on the three reviews connected to this tour because all three photographs
 * happen to be dark, but it is white-on-photo with no floor — a light image
 * puts the headline at 1:1. The theme's own banner answers this with a
 * neutral-900 scrim (styles/sections/hero-banner.json) and this card uses the
 * same one at the same weight, so the two read as one family.
 *
 * That is the a11y line in AGENTS.md working agreement 5, not a redesign: the
 * composition, the crop, the type and every colour are live's.
 *
 * ## The quote mark is inline SVG, not a CSS background
 *
 * Live draws it with `content: url(../../images/quotes.svg)` on the
 * blockquote's `:before`, which means a fixed-size raster request, a hard-coded
 * relative path and no way to recolour it. The same path is written out here as
 * an `outermost/icon-block` with `fill="currentColor"`, so the colour comes
 * from the block's own icon-colour attribute. The nested `<g transform>` pairs
 * are the ones the Sketch export shipped and are kept verbatim: they are what
 * places the path inside the 51x33 viewBox.
 *
 * ## Read More, not the excerpt's own more link
 *
 * Live emits two links — an inline `…/` inside the extract and a separate
 * `.moretag` whose label is replaced in CSS (`font-size: 0` plus a
 * `content: "Read More"` pseudo-element). Two links to the same place, one of
 * them with no accessible text at all. Here the extract carries no more link
 * (`moreText` empty) and `core/read-more` provides the single labelled one.
 *
 * ## Widths
 *
 * Live holds the headline to 900px and the extract to 550px inside a 10rem
 * padded blockquote. The cover's inner container is `constrained`, so the
 * headline takes the content measure and the extract sets its own narrower one
 * through a nested constrained group — both are block attributes, not CSS.
 */

?>
<!-- wp:cover {"useFeaturedImage":true,"dimRatio":50,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":500,"minHeightUnit":"px","contentPosition":"center center","tagName":"article","metadata":{"name":"Review Card — Quote"},"className":"is-style-review-quote-card","style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"top":"var:preset|spacing|80","right":"var:preset|spacing|40","bottom":"var:preset|spacing|80","left":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
<article class="wp-block-cover is-style-review-quote-card" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--40);min-height:500px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim"></span><div class="wp-block-cover__inner-container">

	<!-- wp:group {"metadata":{"name":"Quote Mark"},"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-group"><!-- wp:outermost/icon-block {"iconName":"","iconColor":"accent-500","width":"51px"} -->
	<div class="wp-block-outermost-icon-block"><div class="icon-container has-icon-color has-accent-500-color" style="width:51px"><svg xmlns="http://www.w3.org/2000/svg" width="51" height="33" viewBox="0 0 51 33" fill="currentColor" aria-hidden="true" focusable="false"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g transform="translate(-678.000000, -3624.000000)" fill="currentColor"><g transform="translate(2.000000, 3552.000000)"><path d="M676.8335,92.282 C676.8335,95.847 677.8745,98.792 679.9575,101.118 C682.0405,103.445 684.5785,104.607 687.5725,104.607 C690.1315,104.607 692.1055,103.87 693.4945,102.394 C694.8825,100.92 695.5775,99.119 695.5775,96.992 C695.5775,94.477 694.7415,92.697 693.0715,91.656 C691.4005,90.614 689.8505,90.094 688.4185,90.094 C688.2005,90.094 686.6825,90.246 683.8625,90.549 C683.3415,90.549 682.9185,90.419 682.5935,90.159 C682.2675,89.898 682.1055,89.506 682.1055,88.982 C682.1055,86.325 683.1795,83.7 685.3265,81.107 C687.4745,78.515 690.0665,76.347 693.1045,74.604 L691.8675,72 C688.0495,73.739 684.5885,76.523 681.4865,80.348 C678.3845,84.174 676.8335,88.152 676.8335,92.282 M707.4225,92.804 C707.4225,95.847 708.4205,98.576 710.4165,100.988 C712.4115,103.401 714.9725,104.607 718.0965,104.607 C721.0025,104.607 723.0755,103.772 724.3115,102.101 C725.5485,100.432 726.1665,98.707 726.1665,96.927 C726.1665,94.672 725.3745,92.968 723.7915,91.818 C722.2065,90.669 720.6125,90.094 719.0075,90.094 C718.7475,90.094 717.2275,90.246 714.4515,90.549 C713.8005,90.549 713.3455,90.386 713.0845,90.06 C712.8245,89.735 712.6945,89.332 712.6945,88.852 C712.6945,86.194 713.7685,83.59 715.9155,81.042 C718.0635,78.494 720.6555,76.347 723.6935,74.604 L722.4565,72 C718.7245,73.697 715.2865,76.424 712.1415,80.185 C708.9945,83.945 707.4225,88.152 707.4225,92.804"></path></g></g></g></svg></div></div>
	<!-- /wp:outermost/icon-block --></div>
	<!-- /wp:group -->

	<!-- wp:post-title {"level":3,"isLink":true,"style":{"typography":{"textAlign":"center","textTransform":"uppercase","lineHeight":"var:custom|line-height|heading"}},"fontSize":"400","fontFamily":"heading"} /-->

	<!-- wp:group {"metadata":{"name":"Extract"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"550px"}} -->
	<div class="wp-block-group">

		<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":40,"style":{"typography":{"textAlign":"center","fontWeight":"var:custom|font-weight|light","lineHeight":"var:custom|line-height|body"}},"fontSize":"300"} /-->

		<?php
		/*
		 * `core/read-more` has no text-alignment support of its own — its
		 * block.json declares only `content` and `linkTarget` — so the centring
		 * is a flex row around it rather than an attribute on it.
		 */
		?>
		<!-- wp:group {"metadata":{"name":"Read More"},"layout":{"type":"flex","justifyContent":"center"}} -->
		<div class="wp-block-group"><!-- wp:read-more {"content":"Read More","textColor":"accent-500","fontSize":"300"} /--></div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

</div></article>
<!-- /wp:cover -->
