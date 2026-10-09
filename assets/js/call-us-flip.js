/**
 * SD Call Us flip — open the number panel upwards when there is no room below.
 *
 * `is-style-call-us-navigation` hangs its panel under the trigger. In the
 * header that is always right: the trigger is at the top of the page. On the
 * safari expert card the trigger can sit anywhere in the viewport, and when it
 * is near the bottom the panel would run off the screen.
 *
 * ## Why a script
 *
 * Whether the panel fits depends on where the trigger is when it opens, which
 * only the browser knows. Ollie's own answer is a script too: on every open it
 * caps the panel at `window.innerHeight - top - 24px` and makes it scroll. The
 * stylesheet turns that cap off (assets/styles/ollie-mega-menu.css, "Call Us:
 * the panel always contains its rows") because four phone numbers should never
 * need a scrollbar — so something has to move the panel instead.
 *
 * ## What it does
 *
 * Watches each Call Us toggle's `aria-expanded`, which Ollie's Interactivity
 * store sets for hover, click and keyboard opens alike. When the toggle opens it
 * measures the panel in its natural position below the trigger. If the panel
 * would cross the bottom of the viewport and there is more room above the
 * trigger than below it, it adds `.is-flipped-up` and the stylesheet re-anchors
 * the panel to the trigger's top edge. Otherwise it removes the class.
 *
 * Nothing about focus or the DOM order changes: the class only changes which
 * edge the absolutely positioned panel is pinned to, so keyboard opening, Tab
 * order, `Escape` and the close button are all Ollie's, untouched.
 *
 * Measuring happens in the mutation callback, which runs before the browser
 * paints the open state, so the panel never appears below and then jumps.
 *
 * Without JavaScript the panel simply opens below, as it always did.
 *
 * @package SD_Theme_2026
 */

( function () {
	'use strict';

	var ROOT = '.is-style-call-us-navigation';
	var TOGGLE = '.wp-block-ollie-mega-menu__toggle';
	var PANEL = '.wp-block-ollie-mega-menu__menu-container';
	var FLIPPED = 'is-flipped-up';

	/**
	 * Decide whether a panel should open above its anchor.
	 *
	 * `panel` is the panel's box in its natural position below the anchor, so
	 * the gap between the two is `panel.top - anchor.bottom`, and flipping puts
	 * that same gap above the anchor. Flip only when the panel actually crosses
	 * the bottom edge *and* the space above the anchor beats the space below the
	 * panel's top, so a cramped viewport never moves it somewhere worse.
	 *
	 * @param {{top: number, bottom: number}} panel          Natural panel box.
	 * @param {{top: number, bottom: number}} anchor         Containing block box.
	 * @param {number}                        viewportHeight Visible height.
	 * @return {boolean} True when the panel should open upwards.
	 */
	function shouldFlip( panel, anchor, viewportHeight ) {
		var spaceBelow = viewportHeight - panel.top;
		var spaceAbove = anchor.top - ( panel.top - anchor.bottom );

		if ( panel.bottom <= viewportHeight ) {
			return false;
		}

		return spaceAbove > spaceBelow;
	}

	/**
	 * Place one panel above or below its anchor for the current viewport.
	 *
	 * @param {HTMLElement} panel The `__menu-container` to place.
	 * @return {void}
	 */
	function place( panel ) {
		var anchor = panel.offsetParent || panel.parentElement;

		// Measure from the natural, downward position.
		panel.classList.remove( FLIPPED );

		if (
			anchor &&
			shouldFlip(
				panel.getBoundingClientRect(),
				anchor.getBoundingClientRect(),
				window.innerHeight
			)
		) {
			panel.classList.add( FLIPPED );
		}
	}

	/**
	 * Watch one toggle and re-place its panel whenever it opens or the window
	 * is resized while it is open.
	 *
	 * @param {HTMLElement} toggle A Call Us `__toggle` button.
	 * @return {void}
	 */
	function watch( toggle ) {
		var item = toggle.closest( 'li' );
		var panel = item && item.querySelector( PANEL );

		if ( ! panel ) {
			return;
		}

		function isOpen() {
			return 'true' === toggle.getAttribute( 'aria-expanded' );
		}

		new MutationObserver( function () {
			if ( isOpen() ) {
				place( panel );
			}
		} ).observe( toggle, {
			attributes: true,
			attributeFilter: [ 'aria-expanded' ],
		} );

		window.addEventListener( 'resize', function () {
			if ( isOpen() ) {
				place( panel );
			}
		} );
	}

	/**
	 * Wire every Call Us toggle on the page.
	 *
	 * @return {void}
	 */
	function init() {
		var toggles = document.querySelectorAll( ROOT + ' ' + TOGGLE );

		Array.prototype.forEach.call( toggles, watch );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
