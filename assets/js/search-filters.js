/**
 * SD search filters — FacetWP facets as in-flow folds.
 *
 * Turns each `facetwp/facet` block inside `.sd-search-filters` into a
 * disclosure: the facet's heading becomes the control, and the facet itself
 * becomes a panel that folds open in flow, pushing the facets below it down the
 * rail. Presentation (the plate, the grid row-collapse, the chevron) lives in
 * assets/styles/facetwp-facets.css; this script only toggles state classes and
 * wires the accessibility contract.
 *
 * ## Why a script rather than the fSelect facet type
 *
 * FacetWP ships a facet type that is already a dropdown — `fselect`, a
 * `<select multiple>` upgraded into `.fs-wrap` markup by
 * `facetwp/assets/vendor/fSelect`. It was not taken, for two reasons. Facet
 * *type* is site configuration (`wp_options.facetwp_settings`), not theme, and
 * every facet on this page is shared with the tours search — `travel_style`,
 * `price` and the `destination_to_*` connections all appear on both, so
 * retyping them changes a page this line item does not cover. And `fselect` is
 * a dropdown, which is not what live does: live's facets are Bootstrap
 * `.collapse` accordions that push the rail open. Styling the `checkboxes`
 * facets FacetWP already renders keeps the change inside the theme, which is
 * where the boundary test puts it, and lets the fold match live.
 *
 * ## What this had to do differently from the KWV original
 *
 * Adapted from `kwv-theme-2026/assets/js/shop-filters.js`, which does the same
 * job for WooCommerce's Product Filters. Two differences, both because FacetWP
 * is not the Interactivity API:
 *
 *  1. **No MutationObserver.** WooCommerce re-renders the whole filter region
 *     on every change and wipes injected attributes. FacetWP refreshes only the
 *     *inside* of `.facetwp-facet` (`includes/class-display.php:79` emits the
 *     wrapper once and front.js fills it), so the `.facet-wrap` block wrapper
 *     this script marks up survives every refresh untouched. The one thing that
 *     does need re-running is the empty-facet check, and FacetWP announces that
 *     itself — see `facetwp-loaded` below.
 *  2. **The first fold opens on load.** KWV defaults every section closed.
 *     Live's `lsx-search.js` force-opens its first `.collapse`, so the rail
 *     never arrives as a stack of shut headings, and that is followed here.
 *
 * ## Why there is no outside-click and no Escape handler
 *
 * There was, until 2026-09-10, when the panel stopped being an
 * absolutely-positioned dropdown over the results and became live's in-flow
 * fold. Both handlers — and the close-the-siblings call in `toggle()` — existed
 * only to get the visitor out from under an overlay. A fold covers nothing, so
 * closing on an outside click would take away a list the visitor is still
 * reading, and **more than one facet may be open at once**, which is also what
 * live allows (its collapses carry no `data-parent`). The stylesheet's header
 * records the same three consequences from the CSS side.
 *
 * `facetwp-loaded` is a native `CustomEvent` with `bubbles: true` — FacetWP's
 * own `fUtil.trigger()` dispatches it that way
 * (`facetwp/assets/js/dist/front.min.js`), so no jQuery and no `fUtil`
 * dependency is needed to listen for it.
 *
 * Progressive enhancement: the container only gets `sd-filters-collapsible`
 * (which the CSS gates the fold behaviour on) once this runs, so with no JS
 * every facet renders as a plain open list inside its plate and stays fully
 * usable.
 *
 * @package sd-theme-2026
 */
( function () {
	'use strict';

	var CONTAINER = '.sd-search-filters';
	var READY_CLASS = 'sd-filters-collapsible';
	var FOLD = 'sd-facet-fold';
	var OPEN = 'sd-filter-open';

	// A fold is a facet block that has its own heading to hang the control on.
	// The `hasHeader` attribute is what puts one there, so the facets authored
	// without a header — the keyword search, the sort, the reset and the
	// selected-filter chips — fail this test and stay permanently visible. That
	// is deliberate: it is the template that decides which facets fold, by
	// giving them a heading or not.
	var SECTION = '.facet-wrap';

	var uid = 0;

	function heading( section ) {
		var child = section.firstElementChild;
		for ( ; child; child = child.nextElementSibling ) {
			if ( child.classList && child.classList.contains( 'wp-block-heading' ) ) {
				return child;
			}
		}
		return null;
	}

	function panel( section ) {
		return section.querySelector( '.facetwp-facet' );
	}

	/**
	 * Is this facet block one we turn into a fold?
	 *
	 * Both halves must be present: a heading to click and a facet to reveal.
	 * A facet FacetWP has hidden as empty is excluded — `.facetwp-hidden` is
	 * `display:none` in the plugin's own front.css and the block's front.js
	 * adds it whenever `num_choices` for the facet drops to zero, so folding
	 * one open would reveal an empty panel under a live-looking control.
	 */
	function isFold( section ) {
		return !! heading( section ) &&
			!! panel( section ) &&
			! section.classList.contains( 'facetwp-hidden' );
	}

	function setState( section, isOpen ) {
		var control = heading( section );

		section.classList.toggle( OPEN, isOpen );

		if ( control ) {
			control.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		}
	}

	/**
	 * Give one facet block its control semantics.
	 *
	 * Idempotent — re-running it on an already-enhanced block resets nothing,
	 * which is what lets `facetwp-loaded` call it again after a refresh has
	 * revealed a facet that was previously hidden as empty.
	 *
	 * @param {Element} section   The `.facet-wrap` block wrapper.
	 * @param {boolean} openFirst Open this one if nothing in its rail is open
	 *                            yet — live's force-open of the leading facet.
	 */
	function enhance( section, openFirst ) {
		var control = heading( section );
		var target = panel( section );

		if ( ! isFold( section ) ) {
			return;
		}

		section.classList.add( FOLD );

		if ( ! target.id ) {
			uid += 1;
			target.id = 'sd-facet-panel-' + uid;
		}

		control.setAttribute( 'aria-controls', target.id );

		if ( ! control.hasAttribute( 'tabindex' ) ) {
			control.setAttribute( 'tabindex', '0' );
		}

		// Keep whatever state this block already has — so a refresh that
		// re-runs this does not shut a fold the visitor is filtering inside.
		setState( section, section.classList.contains( OPEN ) || !! openFirst );
	}

	/**
	 * Enhance every facet block in one rail.
	 *
	 * The first fold is opened only when nothing in the rail is open already.
	 * On load that is live's behaviour; after a refresh it means a rail whose
	 * only open facet has just been hidden as empty still shows one list rather
	 * than collapsing to a stack of shut headings.
	 *
	 * @param {Element} root The `.sd-search-filters` container.
	 */
	function enhanceAll( root ) {
		var sections = root.querySelectorAll( SECTION );
		var hasOpen = !! root.querySelector( SECTION + '.' + OPEN );
		var opened = false;

		Array.prototype.forEach.call( sections, function ( section ) {
			var openThis = ! hasOpen && ! opened && isFold( section );

			enhance( section, openThis );

			if ( openThis ) {
				opened = true;
			}
		} );
	}

	function toggle( section ) {
		// No `closeAll()`. The panels fold in flow and overlap nothing, so
		// there is no reason to shut a sibling — and live allows several open.
		setState( section, ! section.classList.contains( OPEN ) );
	}

	/**
	 * The facet block whose *heading* an event came from.
	 *
	 * Returns null for an event inside the panel, so ticking a checkbox never
	 * closes the fold it was ticked in.
	 *
	 * @param {Event} event A delegated click or keydown.
	 * @return {Element|null} The `.facet-wrap` to toggle, or null.
	 */
	function sectionFromEvent( event ) {
		if ( ! event.target || ! event.target.closest ) {
			return null;
		}

		var control = event.target.closest( CONTAINER + ' ' + SECTION + ' > .wp-block-heading' );

		if ( ! control ) {
			return null;
		}

		var section = control.closest( SECTION );

		return section && heading( section ) === control && isFold( section )
			? section
			: null;
	}

	function init() {
		var containers = document.querySelectorAll( CONTAINER );

		if ( ! containers.length ) {
			return;
		}

		Array.prototype.forEach.call( containers, function ( container ) {
			container.classList.add( READY_CLASS );
			enhanceAll( container );
		} );

		// A FacetWP refresh can change which facets are empty, and the block's
		// own front.js adds or removes `.facetwp-hidden` on this same event.
		// Ours is registered after that one loads, but the order does not
		// matter: enhance() is idempotent and the next refresh corrects it.
		document.addEventListener( 'facetwp-loaded', function () {
			Array.prototype.forEach.call( containers, enhanceAll );
		} );

		// Delegated, so a facet revealed by a later refresh needs no binding.
		document.addEventListener( 'click', function ( event ) {
			var section = sectionFromEvent( event );

			if ( section ) {
				event.preventDefault();
				toggle( section );
			}
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Enter' !== event.key && ' ' !== event.key && 'Spacebar' !== event.key ) {
				return;
			}

			var section = sectionFromEvent( event );

			if ( section ) {
				event.preventDefault();
				toggle( section );
			}
		} );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
