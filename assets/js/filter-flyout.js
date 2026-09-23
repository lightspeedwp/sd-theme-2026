/**
 * SD filter flyout — the filter rail as a FacetWP Flyout panel on phones.
 *
 * Below 782px (the width core stacks `core/columns`) the filter rail collapses
 * to a "Filters" button, and the facets open in FacetWP Flyout's off-canvas
 * panel instead. That is live's device: `lsx-search`'s `.facetwp-filters-button`
 * over an off-canvas `#lsx-search-filters`, shown on Bootstrap's `xs` only.
 * Presentation — the button's breakpoint, the panel, the close bar, the fog —
 * lives in assets/styles/facetwp-facets.css; this script does four things the
 * add-on does not.
 *
 * 1. **It says the flyout is there.** `html.sd-has-filter-flyout` is set only
 *    once `FWP.flyout` exists, and every mobile rule in the stylesheet hangs off
 *    it. Without FacetWP Flyout — or without JavaScript — the rail stays exactly
 *    as it is on desktop and the button stays hidden, so a missing add-on never
 *    hides the filters behind a button that does nothing.
 * 2. **It limits the panel to the rail's facets.** The add-on moves *every*
 *    facet on the page into the panel bar `map` and `pager` types, which would
 *    take the results toolbar's `sort_` facet with it. `facetwp/flyout/facets`
 *    keeps the facets that live in `.sd-search-filters` — or, once the panel is
 *    open, in the panel — in their rail order.
 * 3. **It makes the panel a dialog.** The add-on's close control is a
 *    `<div>x</div>` and its panel has no role. `facetwp/flyout/flyout_html`
 *    swaps the div for a real `<button>` carrying live's "Close Filters" label,
 *    names the panel as a modal dialog, and puts `sd-search-filters` on the
 *    panel's content so the rail's facet styling (every rule in
 *    facetwp-facets.css is scoped to that class) follows the facets in.
 * 4. **It manages focus.** On open, focus moves to the close button, Tab and
 *    Shift+Tab stay inside the panel, and Escape closes it; on close, focus
 *    returns to the button that opened it and every trigger's
 *    `aria-expanded` is put back. → AGENTS.md, "focus traps on every modal".
 *
 * ## Why the hooks go on at the first `facetwp-refresh`
 *
 * The add-on builds its panel on the first `facetwp-loaded`
 * (facetwp-flyout/assets/js/front.js), reading both filters as it does. FacetWP
 * fires `facetwp-refresh` before that first load (facetwp front.js:160 vs 459)
 * and `FWP.hooks` exists by then, so registering there is the last moment that
 * is still early enough — and it does not depend on script order, which a
 * `defer`red theme script and FacetWP's own footer script do not guarantee.
 *
 * Strings arrive through `window.sdFilterFlyout`, printed by inc/facetwp.php in
 * the theme's text domain.
 *
 * @package sd-theme-2026
 */
( function () {
	'use strict';

	var RAIL = '.sd-search-filters';
	var PANEL = '.facetwp-flyout';
	var TRIGGER = '.facetwp-flyout-open';
	var READY = 'sd-has-filter-flyout';
	var OPEN = 'sd-filter-flyout-is-open';
	var FOCUSABLE =
		'a[href], button:not([disabled]), input:not([disabled]):not([type="hidden"]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';

	var strings = window.sdFilterFlyout || {};
	var hooked = false;
	var returnFocus = null;

	/**
	 * Escape a string for use inside HTML text and attribute values.
	 *
	 * @param {string} value Raw string.
	 * @return {string} Escaped string.
	 */
	function escapeHtml( value ) {
		return String( value )
			.replace( /&/g, '&amp;' )
			.replace( /</g, '&lt;' )
			.replace( />/g, '&gt;' )
			.replace( /"/g, '&quot;' );
	}

	/**
	 * Keep only the facets that belong to the filter rail.
	 *
	 * The add-on calls this on open *and* on close. On close the facets are
	 * inside the panel, not the rail, so both places are checked — a rail-only
	 * test would return nothing and strand the facets in the closed panel.
	 *
	 * @param {string[]} names Facet names, in document order.
	 * @return {string[]} The rail's facets.
	 */
	function railFacets( names ) {
		return names.filter( function ( name ) {
			var facet = '.facetwp-facet-' + name;

			return !! document.querySelector( RAIL + ' ' + facet + ', ' + PANEL + ' ' + facet );
		} );
	}

	/**
	 * Replace the add-on's close `<div>` with a button, and name the panel.
	 *
	 * `facetwp-flyout-close` stays on the button, because it is the class the
	 * add-on's own click handler is delegated to.
	 *
	 * @param {string} html The add-on's panel markup.
	 * @return {string} Accessible panel markup.
	 */
	function accessiblePanel( html ) {
		var close = escapeHtml( strings.close || 'Close Filters' );
		var label = escapeHtml( strings.label || 'Filters' );

		return html
			.replace(
				'<div class="facetwp-flyout">',
				'<div class="facetwp-flyout" id="sd-filter-flyout" role="dialog" aria-modal="true" aria-label="' +
					label +
					'" tabindex="-1">'
			)
			.replace(
				'<div class="facetwp-flyout-close">x</div>',
				'<button type="button" class="facetwp-flyout-close">' +
					'<span class="facetwp-flyout-close__label">' +
					close +
					'</span>' +
					'<span class="facetwp-flyout-close__icon" aria-hidden="true">&times;</span>' +
					'</button>'
			)
			.replace(
				'<div class="facetwp-flyout-content">',
				'<div class="facetwp-flyout-content sd-search-filters">'
			);
	}

	/**
	 * Set every trigger's expanded state.
	 *
	 * @param {boolean} expanded Whether the panel is open.
	 */
	function setExpanded( expanded ) {
		document.querySelectorAll( TRIGGER + ' button, ' + TRIGGER + ' a' ).forEach( function ( control ) {
			control.setAttribute( 'aria-controls', 'sd-filter-flyout' );
			control.setAttribute( 'aria-expanded', expanded ? 'true' : 'false' );
		} );
	}

	/**
	 * The panel's focusable controls that are actually rendered.
	 *
	 * @param {Element} panel The open panel.
	 * @return {Element[]} Focusable controls, in tab order.
	 */
	function focusables( panel ) {
		return Array.prototype.filter.call( panel.querySelectorAll( FOCUSABLE ), function ( el ) {
			return el.offsetParent !== null || el === document.activeElement;
		} );
	}

	/**
	 * Escape closes; Tab and Shift+Tab wrap inside the panel.
	 *
	 * @param {KeyboardEvent} event Keydown.
	 */
	function onKeydown( event ) {
		var panel = document.querySelector( PANEL + '.active' );

		if ( ! panel ) {
			return;
		}

		if ( 'Escape' === event.key ) {
			event.preventDefault();
			window.FWP.flyout.close();
			return;
		}

		if ( 'Tab' !== event.key ) {
			return;
		}

		var items = focusables( panel );

		if ( ! items.length ) {
			event.preventDefault();
			panel.focus();
			return;
		}

		var first = items[ 0 ];
		var last = items[ items.length - 1 ];
		var inside = panel.contains( document.activeElement );

		if ( event.shiftKey && ( document.activeElement === first || ! inside ) ) {
			event.preventDefault();
			last.focus();
		} else if ( ! event.shiftKey && ( document.activeElement === last || ! inside ) ) {
			event.preventDefault();
			first.focus();
		}
	}

	function onOpen() {
		var panel = document.querySelector( PANEL );

		document.documentElement.classList.add( OPEN );
		setExpanded( true );

		if ( panel ) {
			var close = panel.querySelector( '.facetwp-flyout-close' );

			( close || panel ).focus();
		}

		document.addEventListener( 'keydown', onKeydown );
	}

	function onClose() {
		document.documentElement.classList.remove( OPEN );
		setExpanded( false );
		document.removeEventListener( 'keydown', onKeydown );

		if ( returnFocus && document.contains( returnFocus ) ) {
			returnFocus.focus();
		}

		returnFocus = null;
	}

	/**
	 * Register the add-on hooks, once, before it builds its panel.
	 */
	function hook() {
		if ( hooked || ! window.FWP || ! window.FWP.hooks || ! window.FWP.flyout ) {
			return;
		}

		hooked = true;
		document.documentElement.classList.add( READY );

		window.FWP.hooks.addFilter( 'facetwp/flyout/facets', railFacets );
		window.FWP.hooks.addFilter( 'facetwp/flyout/flyout_html', accessiblePanel );
		window.FWP.hooks.addAction( 'facetwp/flyout/open', onOpen );
		window.FWP.hooks.addAction( 'facetwp/flyout/close', onClose );

		setExpanded( false );
	}

	// Remember which trigger opened the panel, so focus can go back to it. A
	// capture listener, so it runs before the add-on's own handler opens the
	// panel and moves focus away.
	document.addEventListener(
		'click',
		function ( event ) {
			var trigger = event.target && event.target.closest ? event.target.closest( TRIGGER ) : null;

			if ( trigger ) {
				returnFocus = trigger.querySelector( 'button, a' ) || trigger;
			}
		},
		true
	);

	document.addEventListener( 'facetwp-refresh', hook );
	document.addEventListener( 'facetwp-loaded', hook );
} )();
