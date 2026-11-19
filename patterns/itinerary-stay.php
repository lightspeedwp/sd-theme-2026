<?php
/**
 * Title: Itinerary Stay
 * Slug: sd-theme-2026/itinerary-stay
 * Description: One row of the tour summary's itinerary list — a numbered marker beside the night count, the lodge and the destination, with the stay's country beneath. Repeated once per stay by Tour Operator's itinerary binding.
 * Categories: sd-theme-2026/tour-operator
 * Keywords: itinerary, tour, stay, night, lodge, summary
 * Viewport Width: 500
 * Block Types: core/group
 * Template Types: single
 * Post Types: tour
 * Inserter: false
 *
 * @package sd-theme-2026
 */

/*
 * What this replaces — live's `sd_lsx_itinerary_list()`.
 *
 * `#single-tour-summary .itinerary-data .itinerary-list .itinerary-item`,
 * drawn by sd-lsx-child/includes/layout.php:333 and measured from
 * /tour/botswana-victoria-falls-safari/ on 2026-08-28. Live renders a numbered
 * spine down the right-hand column of the tour summary: a filled circle
 * carrying the stay's index, a dashed rule joining it to the next, and beside
 * it the night count, the lodge and the destination.
 *
 * ## This file is the *repeated unit*, not the list
 *
 * The `lsx/tour-itinerary` binding on the group below is not an ordinary
 * binding. Tour Operator's `Bindings::render_itinerary_block()`
 * (includes/classes/blocks/class-bindings.php:306) takes the group's **entire
 * rendered output, wrapper included**, uses it as a template, and returns one
 * copy per itinerary row — so the `<div class="wp-block-group sd-itinerary__stay">`
 * you see here is what repeats. That is why this pattern is a single row and
 * why the list that holds it lives in the template.
 *
 * Field values are substituted by class, not by block binding:
 *
 * | Class on the block          | Filled with                            |
 * |-----------------------------|----------------------------------------|
 * | `itinerary-title`  (h1–h6)  | `lsx_to_itinerary_title()`             |
 * | `itinerary-accommodation`   | `lsx_to_itinerary_accommodation()`     |
 * | `itinerary-location`        | `lsx_to_itinerary_destinations()`      |
 * | `itinerary-country`         | `sd-enhancements`, per row (see below) |
 *
 * Two conventions come with that mechanism and both are load-bearing:
 *
 *  - **The authored text must be exactly `Card Link`.** `maybe_get_prefix()`
 *    (class-bindings.php:453) reads the paragraph's existing content, strips
 *    the literal `Card Link` and keeps whatever is left as a prefix on the real
 *    value. Any other placeholder is silently prepended to every row. It is a
 *    machine token rather than display copy, so it is written plainly and is
 *    not translated — translating it would break the substitution in every
 *    locale but English.
 *  - **Each field sits in a group classed `itin-<field>-wrapper`.** When the
 *    field is empty, `build_itinerary_field()` rewrites that class to
 *    `hidden itin-<field>-wrapper`. `.hidden` is only given `display: none`
 *    inside `.lsx-itinerary-wrapper` (tour-operator/build/style.css, last
 *    rule), which is why the list in the template carries that class.
 *
 * ## Empty fields — why a missing value renders blank, not "Card Link"
 *
 * The two conventions above only work together. An empty field is *not*
 * substituted: its paragraph keeps the literal `Card Link`, and the only thing
 * that stops it showing is the `hidden` class landing on a wrapper *around that
 * paragraph*. The rewrite is a regex on the class name, applied to every match
 * in the row, so the wrapper has to be named for the field it holds and for
 * nothing else.
 *
 * Until 2026-09-23 `itinerary-location` had no wrapper of its own, and
 * `itin-location-wrapper` sat on the country line instead. A stay with no
 * published destination — measured on dev, five of the nine rows on
 * /tour/namibia-wonderland/ — therefore hid the country line and printed
 * "Card Link" in the destination's place. Live prints nothing there, which is
 * what this does now: the destination has its own `itin-location-wrapper`.
 *
 * ## The row reads as one sentence
 *
 * Rebuilt on dev 2026-09-01 to match live's reading order: the night count, the
 * lodge and the lodge's own destination run together on one line — "3 Nights
 * Sable Alley, Khwai Private Reserve" — with the tour's parent destinations on
 * a quieter second line beneath.
 *
 * That is why `itinerary-location` sits *inside* the accommodation wrapper,
 * beside the comma: the comma has to belong to the lodge, so that a stay with
 * no lodge drops the comma with it when Tour Operator marks
 * `itin-accommodation-wrapper` hidden. The comma is also dropped when the
 * *destination* is hidden — it is drawn only while the next sibling is a
 * visible `itin-location-wrapper` (assets/styles/core-group.css). Live leaves a
 * dangling ", " there; that is a defect, not a design.
 *
 * The second line is the stay's **country** — live's `.itinerary-country`,
 * the parent of the stay's destination — in `itin-country-wrapper`, filled by
 * `sd-enhancements`. See the note at that block.
 *
 * ## Wrapping — the row breaks on the word, not on the term
 *
 * The night count, the lodge and the lodge's destination are **one inline
 * flow**, so a row too long for its column breaks between words in the middle
 * of whichever term is crossing the edge. It does not move a whole term to the
 * next line.
 *
 * That is live's behaviour. `.lsx-to-archive-content` is a plain block and the
 * night-count `h3` inside it is `display: inline` (custom.css:2375-2378), with
 * the lodge and destination as `<span>`s after it — nothing there is a box that
 * can drop as a unit.
 *
 * The three rows were previously flex with `flexWrap: wrap`, which is what
 * produced the term break: each `itin-<field>-wrapper` was an atomic flex item,
 * so the lodge dropped whole below the night count and the destination dropped
 * whole below the lodge. They are now flow layout, and the wrappers, the
 * heading and the two paragraphs are made `display: inline` by
 * `.sd-itinerary__stay .itin-title-wrapper:not(.hidden)` and its companions in
 * assets/styles/core-group.css.
 *
 * The wrappers stay in the markup even though they now render inline: Tour
 * Operator rewrites `itin-<field>-wrapper` to `hidden itin-<field>-wrapper` to
 * drop an empty field, so flattening them would lose that. The `:not(.hidden)`
 * on each rule is what keeps `display: inline` from out-specifying the
 * plugin's `display: none`.
 *
 * The `flex: 0 0 auto` that used to pin the night count is gone with the flex
 * rows. It existed because a shrinking flex item broke "2 Nights" mid-word into
 * "2 / Night / s"; in an inline flow there is no item to shrink and the count
 * cannot break inside a word.
 *
 * The spaces between the three terms are the newlines between the wrappers in
 * the serialized markup, collapsed by the inline formatting context — the same
 * single space live's `echo ' '` emits.
 *
 * The comma between the lodge and its destination is **not a block**. It was
 * authored as a paragraph of its own beside the lodge, which made it a separate
 * flex item: once the lodge name wrapped, the comma stayed at the right-hand
 * edge of the lodge's shrunken box, stranded a line away from the word it
 * belongs to. It is now
 * `.sd-itinerary__stay .itinerary-accommodation::after { content: "," }` in
 * assets/styles/core-group.css — live's own device (custom.css:2383), so it is
 * part of the lodge's own inline flow and can neither detach nor begin a line. It also leaves one fewer empty block
 * in the editor, and it disappears with the lodge when Tour Operator marks
 * `itin-accommodation-wrapper` hidden.
 *
 * ## The number is a CSS counter, not a value
 *
 * `render_itinerary_block()` passes a row index into `build_itinerary_field()`
 * and then never uses it, so there is no field to bind a number to. A CSS
 * counter on the repeated rows gives the same 1..N sequence with no PHP and no
 * plugin dependency, and it renumbers correctly when a row is hidden. The
 * circle, the number and the dashed spine are all in
 * assets/styles/core-group.css: every one of them needs `content:`, which a
 * block-style `css` field mangles. → AGENTS.md, "Styling lives in JSON"
 *
 * ## One row per stay, not one per day
 *
 * Tour Operator stores an itinerary as one row per day, so the binding would
 * repeat this pattern fourteen times on a fourteen-day tour. Live shows one row
 * per *stay* — consecutive days sharing a lodge merged, and the row labelled
 * with its night count.
 *
 * That merge is the plugin's, not the theme's: `SD\Enhancements\Itinerary`
 * (port-inventory item K-08) seeds the `$tour_itinerary` global with collapsed
 * rows on `render_block` at priority 9, one ahead of the binding, and rewrites
 * each row's stored `title` to "3 Nights". Tour Operator's renderer is
 * unmodified and this pattern is unchanged by it — `itinerary-title` is filled
 * from `lsx_to_itinerary_title()` either way. The authored text below is an
 * editor placeholder only, and is written as a night count so the editor
 * preview matches the front end.
 *
 * ## Type and colour
 *
 * Live sets the whole content column in the heading face at 19px/600 with an
 * 18px leading — font-size 300 (19.2px) at semi-bold with the snug line-height,
 * to the digit. Lodge links are `#cc7f16` (brand-500); the destination link is
 * `#60483b` (neutral-700), i.e. it is deliberately *not* accented, so the eye
 * follows the lodges down the spine. The numbered marker takes the deeper
 * brand-600. The heading is an `h3` because the column it sits in is headed by
 * an `h2`.
 */

?>
<!-- wp:group {"metadata":{"name":"Itinerary Stay","bindings":{"content":{"source":"lsx/tour-itinerary"}}},"className":"sd-itinerary__stay","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group sd-itinerary__stay">

	<!-- wp:group {"metadata":{"name":"Stay Detail"},"className":"sd-itinerary__detail","style":{"spacing":{"blockGap":"var:preset|spacing|5"},"typography":{"fontWeight":"var:custom|font-weight|semi-bold","lineHeight":"var:custom|line-height|snug"},"layout":{"selfStretch":"fill"}},"fontSize":"300","fontFamily":"heading","layout":{"type":"constrained","justifyContent":"left"}} -->
	<div class="wp-block-group sd-itinerary__detail has-heading-font-family has-300-font-size" style="font-weight:var(--wp--custom--font-weight--semi-bold);line-height:var(--wp--custom--line-height--snug)">

		<?php
		/*
		 * The night count and the lodge line share a row, and the row is one
		 * inline flow that breaks between words — see "Wrapping" above. Flow
		 * layout, not flex: flex would make each wrapper an atomic item. The
		 * night count keeps its own `itin-title-wrapper` so it can drop out on
		 * its own.
		 */
		?>
		<!-- wp:group {"metadata":{"name":"Stay Heading"},"layout":{"type":"default"}} -->
		<div class="wp-block-group">

			<!-- wp:group {"metadata":{"name":"Nights"},"className":"itin-title-wrapper","layout":{"type":"default"}} -->
			<div class="wp-block-group itin-title-wrapper"><!-- wp:heading {"level":3,"className":"itinerary-title","style":{"typography":{"fontWeight":"var:custom|font-weight|semi-bold","lineHeight":"var:custom|line-height|snug","textTransform":"none","letterSpacing":"var:custom|letter-spacing|none"}},"textColor":"neutral-700","fontSize":"300"} -->
			<h3 class="wp-block-heading itinerary-title has-neutral-700-color has-text-color has-300-font-size" style="font-weight:var(--wp--custom--font-weight--semi-bold);letter-spacing:var(--wp--custom--letter-spacing--none);line-height:var(--wp--custom--line-height--snug);text-transform:none"><?php esc_html_e( '2 Nights', 'sd-theme-2026' ); ?></h3>
			<!-- /wp:heading --></div>
			<!-- /wp:group -->

			<?php
			/*
			 * The lodge and the stay's destination. Both sit in the
			 * accommodation wrapper so that a stay with no lodge takes the
			 * comma — a `::after` on the lodge, not a block — down with it.
			 * See "The row reads as one sentence" above.
			 */
			?>
			<!-- wp:group {"metadata":{"name":"Lodge"},"className":"itin-accommodation-wrapper","style":{"elements":{"link":{"color":{"text":"var:preset|color|brand-500"},":hover":{"color":{"text":"var:preset|color|brand-600"}}}}},"layout":{"type":"default"}} -->
			<div class="wp-block-group itin-accommodation-wrapper has-link-color">

				<!-- wp:paragraph {"metadata":{"name":"Lodge Value"},"className":"itinerary-accommodation","style":{"spacing":{"margin":{"top":"0","bottom":"0"}}},"fontSize":"300"} -->
				<p class="itinerary-accommodation has-300-font-size" style="margin-top:0;margin-bottom:0">Card Link</p>
				<!-- /wp:paragraph -->

				<?php
				/*
				 * The stay's own destination, in its own `itin-location-wrapper`
				 * — the class Tour Operator rewrites to `hidden` when the stay
				 * has no published destination. Until 2026-09-23 that class
				 * was on the country line below instead, so an empty location
				 * hid the *wrong* group and left this paragraph printing its
				 * literal "Card Link". See "Empty fields" at the head of this
				 * file.
				 */
				?>
				<!-- wp:group {"metadata":{"name":"Destination"},"className":"itin-location-wrapper","layout":{"type":"default"}} -->
				<div class="wp-block-group itin-location-wrapper"><!-- wp:paragraph {"metadata":{"name":"Destination Value"},"className":"itinerary-location","style":{"spacing":{"margin":{"top":"0","bottom":"0"}},"elements":{"link":{"color":{"text":"var:preset|color|neutral-700"}}}},"textColor":"neutral-700","fontSize":"300"} -->
				<p class="itinerary-location has-neutral-700-color has-text-color has-link-color has-300-font-size" style="margin-top:0;margin-bottom:0">Card Link</p>
				<!-- /wp:paragraph --></div>
				<!-- /wp:group -->

			</div>
			<!-- /wp:group -->

		</div>
		<!-- /wp:group -->

		<?php
		/*
		 * The stay's country, on a quieter line of its own — live's
		 * `lsx_to_itinerary_country()` (sd-lsx-child/includes/template-tags.php:642),
		 * drawn as `.itinerary-country { display: block; font-size: 13px }`
		 * (custom.css:2387).
		 *
		 * The paragraph is authored empty and filled per row by
		 * `SD\Enhancements\Itinerary::fill_countries()`, which also rewrites
		 * `itin-country-wrapper` to `hidden itin-country-wrapper` on a stay with no
		 * country — the same convention Tour Operator uses for its own fields.
		 * Tour Operator has no country field, which is why this is the plugin's.
		 * With the plugin inactive the line is simply blank.
		 *
		 * Until 2026-09-23 this line was the tour's `destination_to_tour`
		 * connection with `parents: true` — the same list on every row, and not
		 * only countries: `parents` drops a destination *with* a parent, so the
		 * unparented draft regions WETU imported ("Okonjima Nature Reserve",
		 * "Etosha South") came through as well, linked to draft permalinks.
		 */
		?>
		<!-- wp:group {"metadata":{"name":"Country"},"className":"itin-country-wrapper","style":{"spacing":{"blockGap":"0"},"elements":{"link":{"color":{"text":"var:preset|color|neutral-700"},":hover":{"color":{"text":"var:preset|color|brand-600"}}}}},"textColor":"neutral-700","layout":{"type":"constrained","justifyContent":"left"}} -->
		<div class="wp-block-group itin-country-wrapper has-neutral-700-color has-text-color has-link-color"><!-- wp:paragraph {"metadata":{"name":"Country Value"},"className":"itinerary-country","style":{"typography":{"fontStyle":"normal","fontWeight":"400"}},"fontSize":"200"} -->
		<p class="itinerary-country has-200-font-size" style="font-style:normal;font-weight:400"></p>
		<!-- /wp:paragraph --></div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
