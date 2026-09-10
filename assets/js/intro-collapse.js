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
 * ## The measurement, and why it runs before the clamp
 *
 * The overflow test compares `scrollHeight` against `clientHeight`, which on a
 * `-webkit-box` that is already clamped reports the clamped height for both and
 * always says "no overflow". So the test runs while the element is still
 * unclamped — before `.is-enhanced` is added — and compares the element's
 * natural height against the height six lines would occupy, derived from the
 * computed `line-height`. `getComputedStyle().lineHeight` resolves to a pixel
 * value in every browser that supports the clamp, but returns the string
 * `normal` when no line-height is inherited; the theme sets one
 * (`--wp--custom--line-height--body`) on this block, and the `normal` branch
 * falls back to 1.5 rather than producing `NaN` and clamping nothing.
 *
 * The clamp length is duplicated here as CLAMP_LINES. ⚠️ It must match
 * `-webkit-line-clamp` in assets/styles/core-term-description.css. There is no
 * way to read a `-webkit-line-clamp` value back reliably across browsers, so
 * the two are kept in step by hand; changing one without the other makes the
 * button appear on text that does not need it, or withholds it from text that
 * does.
 *
 * @package sd-theme-2026
 */

( function () {
	'use strict';

	/**
	 * Lines the text is clamped to. Must match `-webkit-line-clamp` in
	 * assets/styles/core-term-description.css.
	 */
	var CLAMP_LINES = 6;

	/**
	 * Fallback line-height multiplier when `getComputedStyle` reports `normal`.
	 */
	var FALLBACK_LINE_HEIGHT = 1.5;

	/**
	 * Slack, in pixels, before an overflow counts.
	 *
	 * Sub-pixel rounding means a description that happens to be exactly six
	 * lines can measure a fraction over. Without this, those get a button that
	 * reveals nothing.
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
	 * The height the clamped box will occupy, in pixels.
	 *
	 * @param {HTMLElement} element The text element, measured unclamped.
	 * @return {number} Height of CLAMP_LINES lines.
	 */
	function clampedHeight( element ) {
		var styles = window.getComputedStyle( element );
		var lineHeight = parseFloat( styles.lineHeight );

		if ( isNaN( lineHeight ) ) {
			lineHeight = parseFloat( styles.fontSize ) * FALLBACK_LINE_HEIGHT;
		}

		return lineHeight * CLAMP_LINES;
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

		// Measured unclamped — see the header for why this cannot run after
		// `.is-enhanced` is added.
		if ( text.scrollHeight <= clampedHeight( text ) + OVERFLOW_TOLERANCE ) {
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

		container.classList.add( 'is-enhanced' );
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
