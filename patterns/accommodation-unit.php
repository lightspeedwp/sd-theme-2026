<?php
/**
 * Title: Accommodation Unit
 * Slug: sd-theme-2026/accommodation-unit
 * Description: One unit of an accommodation's rooms band — the unit photograph above a tinted panel carrying the unit name and its description. Repeated once per unit by Tour Operator's accommodation-units binding.
 * Categories: sd-theme-2026/tour-operator
 * Keywords: accommodation, unit, room, chalet, tent, villa, suite, lodge
 * Viewport Width: 480
 * Block Types: core/group
 * Template Types: single
 * Post Types: accommodation
 * Inserter: false
 *
 * @package sd-theme-2026
 */

/*
 * What this replaces — live's `sd_lsx_to_accommodation_units()`.
 *
 * `#rooms .sd-rooms-wrapper .rooms-contents`, drawn by
 * sd-lsx-child/includes/functions.php:683 and measured from
 * /accommodation/chitwa-chitwa-private-game-lodge/ on 2026-08-31, which carries
 * three: Luxury Suite, Charlsy Suite, Chitwa House. Live renders a photograph,
 * an `<h5>` at 23px and the unit description, three across in a Slick carousel
 * (`slidesToShow: 3`).
 *
 * ## This file is the *repeated unit*, not the band
 *
 * The `lsx/accommodation-units` binding on the group below behaves exactly like
 * the itinerary binding in patterns/itinerary-stay.php. Tour Operator's
 * `Bindings::render_units_block()` (includes/classes/blocks/class-bindings.php:511)
 * takes the group's **entire rendered output, wrapper included**, uses it as a
 * template, and returns one copy per unit — so the
 * `<div class="wp-block-group is-style-listing-card-compact">` you see here is
 * what repeats, and the list that holds it lives in the template.
 *
 * Field values are substituted by class, not by block binding:
 *
 * | Class on the block         | Filled with                          |
 * |----------------------------|--------------------------------------|
 * | `unit-title`      (h1–h6)  | `$rooms->item_title()`               |
 * | `unit-description`  (p)    | `$rooms->item_description()`         |
 * | `unit-image`               | `$rooms->item_thumbnail()` → `img@src` |
 *
 * `unit-description` is replaced *whole* — `build_unit_field()` swaps the `<p>`
 * for a `<div>` carrying the same class string, because a unit description is a
 * WYSIWYG value holding its own paragraphs. So the type rules have to sit on
 * that paragraph's own classes, which is where they are.
 *
 * When a field is empty the renderer rewrites `unit-<field>-wrapper` to
 * `hidden unit-<field>-wrapper`, and `.hidden` is given `display: none` only
 * inside `.lsx-units-wrapper` (tour-operator/build/style.css, final rule) —
 * which is why the band in the template carries that class and why the
 * description sits in a wrapper of its own.
 *
 * ## Two fields live does not show, and one it cannot
 *
 * Tour Operator's own `patterns/room-card.php` carries `unit-type` and
 * `unit-price` panels. Live's rooms band has neither — `sd_lsx_to_accommodation_units()`
 * renders the thumbnail, the title and the description and nothing else — so
 * they are left out. `build_unit_field()` still runs for all five of its fields
 * on every render; with no matching class in this markup each `preg_replace`
 * simply finds nothing and returns the build unchanged, so omitting them is
 * safe rather than merely tidy. Verified by reading the substitution: every
 * branch's fallback pattern is a class match, and an unmatched pattern is a
 * no-op.
 *
 * The one it cannot show is the unit's own gallery. `$unit_fields` lists
 * `gallery`, but `build_unit_field()` has no `case` for it, so the value is
 * always empty and the field only ever hides a `unit-gallery-wrapper`. Live's
 * `.rooms-thumbnail-wrap` prints every unit image and hides all but the first
 * with `.hidden` — a JavaScript-driven thumbnail swap that never had controls
 * wired to it. One image is what both actually show.
 *
 * ## `size-large` is the last class, and that is deliberate
 *
 * `Bindings::build_image()` (class-bindings.php:466) chooses the image size by
 * looping the element's classes and keeping the last one it can strip `size-`
 * from — its guard is `0 <= stripos( $class, 'size-' )`, and in PHP 8 a `false`
 * return compares as `false <= false`, i.e. true for *every* class. So the size
 * is whatever the final class happens to be. Core appends a block's custom
 * `className` after its own `size-<slug>`, which is why Tour Operator's own
 * room card resolves its size to the string `unit-image` and serves the full
 * original. Writing `size-large` into the className *after* `unit-image`, and
 * leaving `sizeSlug` unset so core does not emit a second copy of it, is one
 * token that makes the band serve the 1024px file instead. Reported upstream;
 * when the guard is fixed this becomes an ordinary `sizeSlug`.
 *
 * The authored `src` is Tour Operator's own placeholder, which is what the
 * editor shows in place of a unit it cannot resolve — the plugin path is the
 * one URL here that is not environment-specific.
 *
 * ## Type and colour
 *
 * The card is `is-style-listing-card-compact`, the same tinted panel the
 * related-item carousels carry, so a unit reads as the same object as the
 * accommodation and tour tiles elsewhere on the page. Live's card is `#ece9e3`
 * on an `#f7f5f2` band and the palette resolves both to `neutral-200`; the band
 * in the template is white for that reason, and the note is there.
 *
 * The image runs 2/1. Live's carousel card caps the thumbnail at 180px tall
 * over `padding: 0 0 45%` (custom.css:1874), and the source Tour Operator asks
 * for is `lsx-thumbnail-wide` at 750×350 — 2.14 either way, which 2/1 is the
 * nearest ratio the block serialises.
 *
 * An `h3`: the band it sits in is headed by an `h2`.
 */

?>
<!-- wp:group {"metadata":{"name":"Accommodation Unit","bindings":{"content":{"source":"lsx/accommodation-units","type":"rooms"}}},"className":"is-style-listing-card-compact","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-listing-card-compact">

	<!-- wp:image {"aspectRatio":"2/1","scale":"cover","linkDestination":"none","className":"unit-image size-large"} -->
	<figure class="wp-block-image unit-image size-large"><img src="/wp-content/plugins/tour-operator/assets/img/blocks/placeholder.png" alt="" style="aspect-ratio:2/1;object-fit:cover"/></figure>
	<!-- /wp:image -->

	<!-- wp:group {"metadata":{"name":"Unit Body"},"style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|30","bottom":"var:preset|spacing|50","left":"var:preset|spacing|30"}},"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200","layout":{"type":"constrained"}} -->
	<div class="wp-block-group has-200-font-size" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--30);line-height:var(--wp--custom--line-height--body)">

		<!-- wp:heading {"textAlign":"center","level":3,"className":"unit-title","fontSize":"400"} -->
		<h3 class="wp-block-heading has-text-align-center unit-title has-400-font-size"><?php esc_html_e( 'Unit Name', 'sd-theme-2026' ); ?></h3>
		<!-- /wp:heading -->

		<!-- wp:group {"metadata":{"name":"Unit Description"},"className":"unit-description-wrapper","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group unit-description-wrapper"><!-- wp:paragraph {"align":"center","className":"unit-description","style":{"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200"} -->
		<p class="has-text-align-center unit-description has-200-font-size" style="line-height:var(--wp--custom--line-height--body)"><?php esc_html_e( 'The description of this unit, as it is entered on the accommodation.', 'sd-theme-2026' ); ?></p>
		<!-- /wp:paragraph --></div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
