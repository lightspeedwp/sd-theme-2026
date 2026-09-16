/**
 * Turns the Trustpilot review row on the team single into a carousel.
 *
 * Live's review row is `#tb-list-review-container.tb-review-carousel` — a flex
 * row of the score badge plus three `.tb-review-box` children at 25% each,
 * which sd-lsx-child/assets/js/custom.js:358 hands to Slick *only* below 767px
 * (`if ( $(window).width() < 767 )`), one slide at a time with arrows and a 4s
 * autoplay. Above that it never slides at all.
 *
 * This file is the same behaviour reached from the other end. The settings
 * mirror Tour Operator's own shelf slider (tour-operator/src/js/custom.js:449
 * and `get_responsive_breakpoints()`) rather than live's mobile-only block, so
 * the reviews behave like the Tours, Destinations and Blog shelves further down
 * the same page instead of being the one row on it with its own rules. On
 * desktop that is indistinguishable from live: three reviews in three slots.
 *
 * ## Why this is not Tour Operator's initialiser
 *
 * TO's selector is `.lsx-to-slider .wp-block-post-template` /
 * `.wp-block-term-template` (custom.js:451-452). `sd/trustpilot-reviews` is
 * neither — it is a repeater over a cached API response, not a query loop, and
 * it renders `.wp-block-sd-trustpilot-reviews`. Giving the block one of those
 * core classes to be picked up would drag core's post-template CSS onto it and
 * claim a query loop that is not there, so the block keeps its own class and
 * this file initialises it with TO's settings.
 *
 * ## Progressive enhancement
 *
 * The pattern authors the block as a three-column grid, which is the finished
 * desktop layout on its own. Everything here is additive: with no JavaScript,
 * no jQuery or no Slick the row still renders correctly, it simply does not
 * slide. `is-layout-grid` is removed only once Slick is about to take over —
 * the same order `pre_build_slider()` uses, and the reason the removal is not
 * done in CSS.
 *
 * @package sd-theme-2026
 */

( function () {
	'use strict';

	var SELECTOR = '.sd-review-slider .wp-block-sd-trustpilot-reviews';

	function init() {
		var $ = window.jQuery;

		// Slick is Tour Operator's, registered as the `slick` handle and
		// declared as this script's dependency. It is absent on a page where
		// Tour Operator is inactive, which is a supported state: the grid is
		// the fallback.
		if ( ! $ || ! $.fn || ! $.fn.slick ) {
			return;
		}

		$( SELECTOR ).each( function () {
			var $track = $( this );

			if ( $track.hasClass( 'slick-initialized' ) ) {
				return;
			}

			// A single review is a row, not a carousel. Slick would still
			// build its track and dots around it.
			if ( $track.children().length < 2 ) {
				return;
			}

			// The grid layout is the no-JS presentation. Slick sizes the track
			// and every slide itself, so the two cannot both be in force.
			$track.removeClass( 'is-layout-grid' );

			$track.slick( {
				infinite: true,
				speed: 500,
				slidesToShow: 3,
				slidesToScroll: 1,
				dots: true,
				// No arrows, at any width, and this is the one place the
				// settings depart from the shelves below. `Trustpilot::REVIEW_COUNT`
				// caps the cache at three and the desktop row shows three, so
				// above 1228px an arrow could never move anything — and the
				// frame's arrows are positioned outside its own edges
				// (assets/styles/core-group.css), which on a 75% column would
				// put the left one on top of the score badge beside it. Dots and
				// swipe carry the narrow widths, where the row genuinely pages.
				arrows: false,
				// Left on at every width rather than only the narrow ones: if
				// the cache cap is ever raised the desktop row stays usable
				// without an arrow to press.
				draggable: true,
				swipe: true,
				autoplay: false,
				// Appended to the frame — the `core/group` carrying
				// `is-style-slider-frame`, whose stylesheet positions the dot
				// row against its own edges.
				appendDots: $track.parent(),
				responsive: [
					{
						breakpoint: 1228,
						settings: {
							slidesToShow: 3,
							slidesToScroll: 1
						}
					},
					{
						breakpoint: 1028,
						settings: {
							slidesToShow: 2,
							slidesToScroll: 1
						}
					},
					{
						breakpoint: 782,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1
						}
					}
				]
			} );
		} );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
