<?php
/**
 * Title: Card — Search Result (List)
 * Slug: sd-theme-2026/card-search-result
 * Description: The horizontal row the site search renders, for any post type. A square thumbnail on the leading third carrying a post-type badge, then a nested split with the title and excerpt beside a tinted meta panel that shows the accommodation fields on an accommodation, the tour fields on a tour, and nothing at all on a page, a destination or an article. Drop it into a Query Loop's post template.
 * Categories: sd-theme-2026/card, sd-theme-2026/tour-operator
 * Keywords: card, list, row, search, results, facetwp, badge, mixed
 * Viewport Width: 1280
 * Block Types: core/post-template
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * The mixed-post-type twin of `patterns/card-accommodation-list.php`, and
 * deliberately the same row: `is-style-listing-card-list`, a 30% square
 * thumbnail, and a nested split whose trailing 35% is the tinted meta panel.
 * The reasoning behind those measurements is recorded on that file and is not
 * repeated here. Three things are this card's own.
 *
 * ## 1. The badge
 *
 * A search result is the one place on this site where the reader cannot tell
 * what kind of thing they are looking at from the page they are on, so the row
 * says so: a plate on the leading corner of the thumbnail naming the post type.
 *
 * It is written out once per post type rather than bound, because there is no
 * binding that answers for a post type and there should not be one —
 * `SD\Enhancements\Bindings::get_post_field_value()` allow-lists `title` and
 * `permalink` and records why it will not grow into a general post-field
 * reader. Written out, each label is a translatable literal in this theme's
 * text domain and reads the way an editor would name the thing ("Article", not
 * "Post"), which a `get_post_type_object()->labels` lookup could not give.
 *
 * Only the positioning lives in CSS — `styles/sections/cards/listing-card-list.json`
 * carries `.listing-card-list__media` and `.listing-card-list__badge`. Colour,
 * size, weight and padding are block attributes, so the badge is editable.
 *
 * ⚠️ A post type with no badge below simply renders none. `envira` (Galleries)
 * is currently indexed by FacetWP's Content Type facet and is the one type that
 * can reach this card without a label; excluding it from search is an
 * `exclude_from_search` change in the plugin layer, not something to answer
 * with a label here.
 *
 * ## 2. Visibility is Block Visibility's Location control, per post type
 *
 * Every gate on this card is
 * `blockVisibility.controlSets[].controls.location.ruleSets[].rules[]` with
 * `field: postType`. The plugin's `run_location_post_type_test()`
 * (block-visibility/includes/frontend/visibility-tests/location.php:303) reads
 * `get_post_type()` with no argument — the *global* post — and inside a
 * `core/post-template` loop that is the row being rendered, not the queried
 * object. So one authored card answers for every post type, and the test is
 * re-run per row, including on FacetWP's AJAX refresh (it is a `render_block`
 * filter, and FacetWP re-renders the template through the same path).
 *
 * ⚠️ This is a **plugin dependency**: with Block Visibility deactivated every
 * gated block renders, so an article would show an empty meta panel. That is
 * the same dependency `parts/header.php` already takes for its responsive
 * header, and the degradation is cosmetic rather than broken.
 *
 * Belt and braces is deliberate: the panel column is gated to
 * accommodation-or-tour *and* each row inside it is gated to its own type. The
 * outer gate is what removes the panel — without it a `lsx/post-connection`
 * binding that resolves to nothing still leaves an empty `<p>` and the column
 * would render as a tinted empty plate.
 *
 * ## 3. The copy column has no width
 *
 * `card-accommodation-list.php` splits 65/35 because the panel is always there.
 * Here it is not, so only the panel carries a width and the copy column is left
 * to `flex-grow`. When the panel is gated away the copy takes the whole row on
 * its own, with no empty 35% and no second card variant to keep in step.
 *
 * ⚠️ No `margin-bottom` on the card — the gap between rows is the enclosing
 * `core/post-template`'s `blockGap`. → card-accommodation-list.php
 */

?>
<!-- wp:columns {"metadata":{"name":"Search Result Card — List"},"className":"is-style-listing-card-list","style":{"spacing":{"blockGap":"0"}},"backgroundColor":"neutral-200"} -->
<div class="wp-block-columns is-style-listing-card-list has-neutral-200-background-color has-background">

	<!-- wp:column {"width":"30%","className":"listing-card-list__media"} -->
	<div class="wp-block-column listing-card-list__media" style="flex-basis:30%">
		<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"1"} /-->

		<?php
		/*
		 * The badges. One per searchable post type, each gated to its own type,
		 * all sharing `.listing-card-list__badge` — which is the class the card
		 * style positions on the leading corner. Exactly one can ever render.
		 */
		?>
		<!-- wp:paragraph {"metadata":{"name":"Badge — Accommodation"},"className":"listing-card-list__badge","backgroundColor":"brand-500","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|5","right":"var:preset|spacing|10","bottom":"var:preset|spacing|5","left":"var:preset|spacing|10"}},"typography":{"letterSpacing":"var:custom|letter-spacing|heading","textTransform":"uppercase"}},"fontSize":"100","fontFamily":"heading","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"location":{"ruleSets":[{"enable":true,"rules":[{"field":"postType","operator":"any","value":["accommodation"]}]}]}}}]}} -->
		<p class="listing-card-list__badge has-base-color has-brand-500-background-color has-text-color has-background has-heading-font-family has-100-font-size" style="padding-top:var(--wp--preset--spacing--5);padding-right:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--5);padding-left:var(--wp--preset--spacing--10);letter-spacing:var(--wp--custom--letter-spacing--heading);text-transform:uppercase"><?php esc_html_e( 'Accommodation', 'sd-theme-2026' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"metadata":{"name":"Badge — Tour"},"className":"listing-card-list__badge","backgroundColor":"brand-500","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|5","right":"var:preset|spacing|10","bottom":"var:preset|spacing|5","left":"var:preset|spacing|10"}},"typography":{"letterSpacing":"var:custom|letter-spacing|heading","textTransform":"uppercase"}},"fontSize":"100","fontFamily":"heading","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"location":{"ruleSets":[{"enable":true,"rules":[{"field":"postType","operator":"any","value":["tour"]}]}]}}}]}} -->
		<p class="listing-card-list__badge has-base-color has-brand-500-background-color has-text-color has-background has-heading-font-family has-100-font-size" style="padding-top:var(--wp--preset--spacing--5);padding-right:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--5);padding-left:var(--wp--preset--spacing--10);letter-spacing:var(--wp--custom--letter-spacing--heading);text-transform:uppercase"><?php esc_html_e( 'Tour', 'sd-theme-2026' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"metadata":{"name":"Badge — Destination"},"className":"listing-card-list__badge","backgroundColor":"brand-500","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|5","right":"var:preset|spacing|10","bottom":"var:preset|spacing|5","left":"var:preset|spacing|10"}},"typography":{"letterSpacing":"var:custom|letter-spacing|heading","textTransform":"uppercase"}},"fontSize":"100","fontFamily":"heading","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"location":{"ruleSets":[{"enable":true,"rules":[{"field":"postType","operator":"any","value":["destination"]}]}]}}}]}} -->
		<p class="listing-card-list__badge has-base-color has-brand-500-background-color has-text-color has-background has-heading-font-family has-100-font-size" style="padding-top:var(--wp--preset--spacing--5);padding-right:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--5);padding-left:var(--wp--preset--spacing--10);letter-spacing:var(--wp--custom--letter-spacing--heading);text-transform:uppercase"><?php esc_html_e( 'Destination', 'sd-theme-2026' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"metadata":{"name":"Badge — Special"},"className":"listing-card-list__badge","backgroundColor":"brand-500","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|5","right":"var:preset|spacing|10","bottom":"var:preset|spacing|5","left":"var:preset|spacing|10"}},"typography":{"letterSpacing":"var:custom|letter-spacing|heading","textTransform":"uppercase"}},"fontSize":"100","fontFamily":"heading","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"location":{"ruleSets":[{"enable":true,"rules":[{"field":"postType","operator":"any","value":["special"]}]}]}}}]}} -->
		<p class="listing-card-list__badge has-base-color has-brand-500-background-color has-text-color has-background has-heading-font-family has-100-font-size" style="padding-top:var(--wp--preset--spacing--5);padding-right:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--5);padding-left:var(--wp--preset--spacing--10);letter-spacing:var(--wp--custom--letter-spacing--heading);text-transform:uppercase"><?php esc_html_e( 'Special', 'sd-theme-2026' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"metadata":{"name":"Badge — Review"},"className":"listing-card-list__badge","backgroundColor":"brand-500","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|5","right":"var:preset|spacing|10","bottom":"var:preset|spacing|5","left":"var:preset|spacing|10"}},"typography":{"letterSpacing":"var:custom|letter-spacing|heading","textTransform":"uppercase"}},"fontSize":"100","fontFamily":"heading","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"location":{"ruleSets":[{"enable":true,"rules":[{"field":"postType","operator":"any","value":["review"]}]}]}}}]}} -->
		<p class="listing-card-list__badge has-base-color has-brand-500-background-color has-text-color has-background has-heading-font-family has-100-font-size" style="padding-top:var(--wp--preset--spacing--5);padding-right:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--5);padding-left:var(--wp--preset--spacing--10);letter-spacing:var(--wp--custom--letter-spacing--heading);text-transform:uppercase"><?php esc_html_e( 'Review', 'sd-theme-2026' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"metadata":{"name":"Badge — Team"},"className":"listing-card-list__badge","backgroundColor":"brand-500","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|5","right":"var:preset|spacing|10","bottom":"var:preset|spacing|5","left":"var:preset|spacing|10"}},"typography":{"letterSpacing":"var:custom|letter-spacing|heading","textTransform":"uppercase"}},"fontSize":"100","fontFamily":"heading","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"location":{"ruleSets":[{"enable":true,"rules":[{"field":"postType","operator":"any","value":["team"]}]}]}}}]}} -->
		<p class="listing-card-list__badge has-base-color has-brand-500-background-color has-text-color has-background has-heading-font-family has-100-font-size" style="padding-top:var(--wp--preset--spacing--5);padding-right:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--5);padding-left:var(--wp--preset--spacing--10);letter-spacing:var(--wp--custom--letter-spacing--heading);text-transform:uppercase"><?php echo esc_html_x( 'Team', 'post type badge', 'sd-theme-2026' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"metadata":{"name":"Badge — Article"},"className":"listing-card-list__badge","backgroundColor":"brand-500","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|5","right":"var:preset|spacing|10","bottom":"var:preset|spacing|5","left":"var:preset|spacing|10"}},"typography":{"letterSpacing":"var:custom|letter-spacing|heading","textTransform":"uppercase"}},"fontSize":"100","fontFamily":"heading","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"location":{"ruleSets":[{"enable":true,"rules":[{"field":"postType","operator":"any","value":["post"]}]}]}}}]}} -->
		<p class="listing-card-list__badge has-base-color has-brand-500-background-color has-text-color has-background has-heading-font-family has-100-font-size" style="padding-top:var(--wp--preset--spacing--5);padding-right:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--5);padding-left:var(--wp--preset--spacing--10);letter-spacing:var(--wp--custom--letter-spacing--heading);text-transform:uppercase"><?php echo esc_html_x( 'Article', 'post type badge', 'sd-theme-2026' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"metadata":{"name":"Badge — Page"},"className":"listing-card-list__badge","backgroundColor":"brand-500","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|5","right":"var:preset|spacing|10","bottom":"var:preset|spacing|5","left":"var:preset|spacing|10"}},"typography":{"letterSpacing":"var:custom|letter-spacing|heading","textTransform":"uppercase"}},"fontSize":"100","fontFamily":"heading","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"location":{"ruleSets":[{"enable":true,"rules":[{"field":"postType","operator":"any","value":["page"]}]}]}}}]}} -->
		<p class="listing-card-list__badge has-base-color has-brand-500-background-color has-text-color has-background has-heading-font-family has-100-font-size" style="padding-top:var(--wp--preset--spacing--5);padding-right:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--5);padding-left:var(--wp--preset--spacing--10);letter-spacing:var(--wp--custom--letter-spacing--heading);text-transform:uppercase"><?php echo esc_html_x( 'Page', 'post type badge', 'sd-theme-2026' ); ?></p>
		<!-- /wp:paragraph -->

	</div>
	<!-- /wp:column -->

	<!-- wp:column {"verticalAlignment":"stretch","style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
	<div class="wp-block-column is-vertically-aligned-stretch" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">

		<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|40"}}}} -->
		<div class="wp-block-columns">

			<?php
			/*
			 * The copy. No `width` — see the note at the head of this file: the
			 * column grows into the space the meta panel leaves when the panel
			 * is gated away, so a page or an article gets the full row for its
			 * title and excerpt rather than an empty trailing third.
			 *
			 * The title keeps the card style's heading size (400). The excerpt
			 * and everything in the panel are at 200 — "Base" — which is the
			 * one size the row's body text runs at. Zared's call, 2026-09-17.
			 */
			?>
			<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
			<div class="wp-block-column">

				<!-- wp:post-title {"level":3,"isLink":true} /-->

				<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group">
					<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":45,"fontSize":"200"} /-->
				</div>
				<!-- /wp:group -->

			</div>
			<!-- /wp:column -->

			<?php
			/*
			 * The meta panel, gated to the two post types that have fields
			 * worth reading in a list. Inside it, the accommodation rows are
			 * `card-accommodation-list.php`'s — price band, type, connected
			 * destination, in live's order — and the tour rows are
			 * `card-tour-list.php`'s: connected destination, then travel style.
			 *
			 * The bound paragraphs are authored **empty** so a null binding
			 * prints nothing rather than a stray prefix; `prefix` /
			 * `prefixBold` are `sd-enhancements`' addition to `core/paragraph`,
			 * not core's.
			 */
			?>
			<!-- wp:column {"verticalAlignment":"stretch","width":"35%","style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20"}},"typography":{"lineHeight":"var:custom|line-height|body"}},"backgroundColor":"neutral-100","fontSize":"200","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"location":{"ruleSets":[{"enable":true,"rules":[{"field":"postType","operator":"any","value":["accommodation","tour"]}]}]}}}]}} -->
			<div class="wp-block-column is-vertically-aligned-stretch has-neutral-100-background-color has-background has-200-font-size" style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20);line-height:var(--wp--custom--line-height--body);flex-basis:35%">

				<!-- wp:paragraph {"metadata":{"name":"Price Rating","bindings":{"content":{"source":"sd/post-meta","args":{"key":"price_rating","format":"price-band"}}}},"className":"lsx-price-rating-wrapper","prefix":"Price Rating:","prefixBold":true,"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"location":{"ruleSets":[{"enable":true,"rules":[{"field":"postType","operator":"any","value":["accommodation"]}]}]}}}]}} -->
				<p class="lsx-price-rating-wrapper"></p>
				<!-- /wp:paragraph -->

				<!-- wp:post-terms {"term":"accommodation-type","prefix":"Type: ","style":{"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"location":{"ruleSets":[{"enable":true,"rules":[{"field":"postType","operator":"any","value":["accommodation"]}]}]}}}]}} /-->

				<!-- wp:paragraph {"metadata":{"name":"Location — Accommodation","bindings":{"content":{"source":"lsx/post-connection","args":{"key":"destination_to_accommodation"}}}},"className":"lsx-destination-to-accommodation-wrapper","prefix":"Location:","prefixBold":true,"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"location":{"ruleSets":[{"enable":true,"rules":[{"field":"postType","operator":"any","value":["accommodation"]}]}]}}}]}} -->
				<p class="lsx-destination-to-accommodation-wrapper"></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"metadata":{"name":"Location — Tour","bindings":{"content":{"source":"lsx/post-connection","args":{"key":"destination_to_tour"}}}},"className":"lsx-destination-to-tour-wrapper","prefix":"Location:","prefixBold":true,"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"location":{"ruleSets":[{"enable":true,"rules":[{"field":"postType","operator":"any","value":["tour"]}]}]}}}]}} -->
				<p class="lsx-destination-to-tour-wrapper"></p>
				<!-- /wp:paragraph -->

				<!-- wp:post-terms {"term":"travel-style","prefix":"Travel Style: ","style":{"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"location":{"ruleSets":[{"enable":true,"rules":[{"field":"postType","operator":"any","value":["tour"]}]}]}}}]}} /-->

			</div>
			<!-- /wp:column -->

		</div>
		<!-- /wp:columns -->

	</div>
	<!-- /wp:column -->

</div>
<!-- /wp:columns -->
