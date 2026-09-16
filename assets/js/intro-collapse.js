/**
 * SD intro collapse — the Read more on a term description.
 *
 * Clamps `.sd-intro-collapse__text` and reveals a toggle, but *only* when the
 * text is genuinely overflowing its clamp. Presentation (the clamp itself, the
 * hidden button) lives in assets/styles/core-term-description.css; this script
 * only measures, toggles state classes, and wires the accessibility contract.
 *
 * ## Why a script at all
 *
 * `patterns/destination-summary.php` gets a Read more for free —
 * `core/read-more` collapses `core/post-content` to its first block and expands
 * it in place, no script required. That route is closed on a taxonomy archive
 * twice over: `core/read-more` renders a link to a *post* permalink and there
 * is no post in context, and `core/term-description` is a single dynamic block
 * that emits the whole description at once, so there is no first block to
 * collapse to.
 *
 * ## The progressive-enhancement contract
 *
 * The pattern renders the full description with the button hidden by CSS. This
 * script adds `.is-enhanced` — which is what turns the clamp on *and* the
 * button visible — only after confirming the text overflows. So:
 *
 *   - JavaScript off  → full description, no button, no clamp;
 *   - short description → full description, no button, no clamp;
 *   - long description → clamped, with a working button.
 *
 * There is never a control that does nothing. That matters here because live
 * has no Read more on this page at all, so a dead button would be a regression
 * against live rather than an addition to it.
 *
 * ## The measurement — it measures the clamp, it does not model it
 *
 * The test reads the text's height unclamped, adds `.is-enhanced` to apply the
 * clamp, reads the height again, and keeps the class only if the second
 * reading is shorter — that is, only if clamping actually hid something. Both
 * reads are synchronous within one task, so nothing paints in between and the
 * class is gone again before the frame renders when the text fits.
 *
 * Two routes were tried first and neither works here:
 *
 * `scrollHeight` against `clientHeight` on the clamped element is the obvious
 * test. A clamped `-webkit-box` reports the clamped height for both, so it
 * always says "no overflow".
 *
 * Comparing the unclamped height against the height N lines *would* occupy,
 * derived from the computed `line-height`, was what this file did until
 * 2026-09-16. It has to duplicate the line count from
 * assets/styles/core-term-description.css and keep it in step by hand, and it
 * over-reports on any description whose paragraphs carry margins — those count
 * toward the height but not toward the line count, so the button appeared on
 * text that was not being cut.
 *
 * Measuring the clamp itself has neither problem: the CSS is the only place
 * the line count is written, and whatever the box does — margins, the drop cap,
 * `-webkit-box` layout — is present in both readings and cancels out.
 *
 * @package sd-theme-2026
 */

( function () {
	'use strict';

	/**
	 * Slack, in pixels, before a height difference counts as an overflow.
	 *
	 * Sub-pixel rounding, and the margins that `-webkit-box` lays out where
	 * block flow would have collapsed them, can leave the two readings a
	 * fraction apart on text that fits exactly. Without this, those get a
	 * button that reveals nothing.
	 */
	var OVERFLOW_TOLERANCE = 4;

	/**
	 * The translated "Read less" label.
	 *
	 * Localised onto `window.sdIntroCollapse` by inc/intro-collapse.php. The
	 * English fallback is here only so the control still reads correctly if the
	 * script is ever enqueued without its localisation — it is not the string
	 * the site ships.
	 *
	 * @return {string} Label for the expanded state.
	 */
	function expandedLabel() {
		var config = window.sdIntroCollapse;

		if ( config && config.expandedLabel ) {
			return config.expandedLabel;
		}

		return 'Read less';
	}

	/**
	 * Whether clamping the text actually hides any of it.
	 *
	 * Applies the clamp to measure it — `.is-enhanced` is what turns
	 * `-webkit-line-clamp` on — and leaves the class in place when the answer
	 * is yes, because the caller wants it there and removing it only to add it
	 * back is a second reflow for nothing. Both `getBoundingClientRect()` calls
	 * flush layout, so the second reading is of the clamped box.
	 *
	 * @param {HTMLElement} container The `.sd-intro-collapse`.
	 * @param {HTMLElement} text      The `.sd-intro-collapse__text` inside it.
	 * @return {boolean} True when the text overflows its clamp.
	 */
	function overflowsClamp( container, text ) {
		var natural = text.getBoundingClientRect().height;
		var clamped;

		container.classList.add( 'is-enhanced' );

		clamped = text.getBoundingClientRect().height;

		if ( natural - clamped > OVERFLOW_TOLERANCE ) {
			return true;
		}

		container.classList.remove( 'is-enhanced' );

		return false;
	}

	/**
	 * Point the button's label and state at whether the text is open.
	 *
	 * The collapsed label is whatever the pattern rendered into the button, so
	 * it is translated through the theme's text domain like any other pattern
	 * string. The expanded label cannot be: `core/button` saves a fixed `<a>`
	 * and any extra attribute written into the pattern would fail block
	 * validation the moment the template is opened in the editor. It arrives
	 * instead on `window.sdIntroCollapse`, localised in inc/intro-collapse.php.
	 *
	 * @param {HTMLElement} button   The toggle's `<a>`.
	 * @param {boolean}     expanded Whether the text is now open.
	 */
	function setButtonState( button, expanded ) {
		var collapsedLabel = button.getAttribute( 'data-label-collapsed' );
		var expandedLabel = button.getAttribute( 'data-label-expanded' );

		button.setAttribute( 'aria-expanded', expanded ? 'true' : 'false' );
		button.textContent = expanded ? expandedLabel : collapsedLabel;
	}

	/**
	 * Wire one collapse container.
	 *
	 * @param {HTMLElement} container An `.sd-intro-collapse`.
	 */
	function setup( container ) {
		var text = container.querySelector( '.sd-intro-collapse__text' );
		var toggle = container.querySelector( '.sd-intro-collapse__toggle' );
		var button = toggle && toggle.querySelector( 'a' );

		if ( ! text || ! button ) {
			return;
		}

		// Applies `.is-enhanced` when it returns true, and leaves the element
		// untouched when it returns false — see the header for the reasoning.
		if ( ! overflowsClamp( container, text ) ) {
			return;
		}

		/*
		 * The button is authored as a `core/button`, so it is an `<a>` with no
		 * `href`. That is correct for a control that goes nowhere, but an `<a>`
		 * without an `href` is not focusable and is not announced as a control,
		 * so the role and the tab stop are set explicitly and the space key is
		 * wired by hand — a `<button>`'s two behaviours that an anchor does not
		 * inherit. `preventDefault` on space stops the page scrolling under it.
		 */
		button.setAttribute( 'role', 'button' );
		button.setAttribute( 'tabindex', '0' );
		button.setAttribute( 'data-label-collapsed', button.textContent.trim() );
		button.setAttribute( 'data-label-expanded', expandedLabel() );

		/*
		 * The panel the button controls. `core/term-description` renders no id
		 * of its own, so one is minted here rather than authored in the
		 * pattern — the pattern serves every brand and an id has to be unique
		 * on the page, not across the template.
		 */
		if ( ! text.id ) {
			text.id = 'sd-intro-collapse-text';
		}

		button.setAttribute( 'aria-controls', text.id );

		setButtonState( button, false );

		function toggleState( event ) {
			event.preventDefault();

			var expanded = container.classList.toggle( 'is-expanded' );

			setButtonState( button, expanded );
		}

		button.addEventListener( 'click', toggleState );

		button.addEventListener( 'keydown', function ( event ) {
			if ( 'Enter' === event.key || ' ' === event.key || 'Spacebar' === event.key ) {
				toggleState( event );
			}
		} );
	}

	/**
	 * Find every collapse on the page and wire it.
	 */
	function init() {
		var containers = document.querySelectorAll( '.sd-intro-collapse' );

		Array.prototype.forEach.call( containers, setup );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
