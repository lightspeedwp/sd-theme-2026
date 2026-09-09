/**
 * SD search filters — FacetWP facets as dropdowns.
 *
 * Turns each `facetwp/facet` block inside `.sd-search-filters` into a
 * disclosure: the facet's heading becomes the control, and the facet itself
 * becomes a panel that opens over the page rather than pushing it. Presentation
 * (the absolute panel, the chevron, the scroll cap) lives in
 * assets/styles/facetwp-facets.css; this script only toggles state classes and
 * wires the accessibility contract.
 *
 * ## Why a script rather than the fSelect facet type
 *
 * FacetWP ships a facet type that is already a dropdown — `fselect`, a
 * `<select multiple>` upgraded into `.fs-wrap` markup by
 * `facetwp/assets/vendor/fSelect`. It was not taken, for one reason: facet
 * *type* is site configuration (`wp_options.facetwp_settings`), not theme, and
 * every facet on this page is shared with the tours search — `travel_style`,
 * `price` and the `destination_to_*` connections all appear on both. Retyping
 * them to `fselect` changes a page this line item does not cover. Styling the
 * `checkboxes` facets FacetWP already renders keeps the change inside the
 * theme, which is where the boundary test puts it.
 *
 * If the facets are ever retyped to `fselect`, this file and its stylesheet
 * become dead and should be deleted rather than left to fight fSelect's own JS.
 *
 * ## What this had to do differently from the KWV original
 *
 * Adapted from `kwv-theme-2026/assets/js/shop-filters.js`, which does the same
 * job for WooCommerce's Product Filters. Three differences, all of them because
 * FacetWP is not the Interactivity API:
 *
 *  1. **No MutationObserver.** WooCommerce re-renders the whole filter region
 *     on every change and wipes injected attributes. FacetWP refreshes only the
 *     *inside* of `.facetwp-facet` (`includes/class-display.php:79` emits the
 *     wrapper once and front.js fills it), so the `.facet-wrap` block wrapper
 *     this script marks up survives every refresh untouched. The one thing that
 *     does need re-running is the empty-facet check, and FacetWP announces that
 *     itself — see `facetwp-loaded` below.
 *  2. **It is a dropdown, not a fold.** KWV's sections push the page open with
 *     a grid row-collapse. These open over it, so only one may be open at a
 *     time and outside-click and Escape have to close it.
 *  3. **Closed by default, both environments.** KWV also defaults closed, but
 *     there it is a preference; here it is the whole point — a facet that is
 *     open by default is not a dropdown.
 *
 * `facetwp-loaded` is a native `CustomEvent` with `bubbles: true` — FacetWP's
 * own `fUtil.trigger()` dispatches it that way
 * (`facetwp/assets/js/dist/front.min.js`), so no jQuery and no `fUtil`
 * dependency is needed to listen for it.
 *
 * Progressive enhancement: the container only gets `sd-filters-collapsible`
 * (which the CSS gates the dropdown behaviour on) once this runs, so with no JS
 * every facet renders as a plain open list and stays fully usable.
 *
 * @package sd-theme-2026
 */
( function () {
	'use strict';

	var CONTAINER = '.sd-search-filters';
	var READY_CLASS = 'sd-filters-collapsible';
	var OPEN = 'sd-filter-open';

	// A dropdown is a facet block that has its own heading to hang the control
	// on. The `hasHeader` attribute is what puts one there, so the facets
	// authored without a header — the keyword search, the sort, the reset and
	// the selected-filter chips — fail this test and stay permanently visible.
	// That is deliberate: it is the template that decides which facets are
	// dropdowns, by giving them a heading or not.
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
	 * Is this facet block one we turn into a dropdown?
	 *
	 * Both halves must be present: a heading to click and a facet to reveal.
	 * A facet FacetWP has hidden as empty is excluded — `.facetwp-hidden` is
	 * `display:none` in the plugin's own front.css and the block's front.js
	 * adds it whenever `num_choices` for the facet drops to zero, so opening
	 * one would reveal an empty panel under a live-looking control.
	 */
	function isDropdown( section ) {
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

	function closeAll( except ) {
		var open = document.querySelectorAll( CONTAINER + ' ' + SECTION + '.' + OPEN );

		Array.prototype.forEach.call( open, function ( section ) {
			if ( section !== except ) {
				setState( section, false );
			}
		} );
	}

	/**
	 * Give one facet block its control semantics.
	 *
	 * Idempotent — re-running it on an already-enhanced block resets nothing,
	 * which is what lets `facetwp-loaded` call it again after a refresh has
	 * revealed a facet that was previously hidden as empty.
	 */
	function enhance( section ) {
		var control = heading( section );
		var target = panel( section );

		if ( ! isDropdown( section ) ) {
			return;
		}

		section.classList.add( 'sd-facet-dropdown' );

		if ( ! target.id ) {
			uid += 1;
			target.id = 'sd-facet-panel-' + uid;
		}

		control.setAttribute( 'aria-controls', target.id );

		if ( ! control.hasAttribute( 'tabindex' ) ) {
			control.setAttribute( 'tabindex', '0' );
		}

		// Closed unless this block is already open — so a refresh that re-runs
		// this does not shut a dropdown the visitor is filtering inside.
		setState( section, section.classList.contains( OPEN ) );
	}

	function enhanceAll( root ) {
		Array.prototype.forEach.call( root.querySelectorAll( SECTION ), enhance );
	}

	function toggle( section ) {
		var isOpen = section.classList.contains( OPEN );

		// One at a time: these panels overlap the page and each other.
		closeAll( section );
		setState( section, ! isOpen );
	}

	/**
	 * The facet block whose *heading* an event came from.
	 *
	 * Returns null for an event inside the panel, so ticking a checkbox never
	 * closes the dropdown it was ticked in.
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

		return section && heading( section ) === control && isDropdown( section )
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
				return;
			}

			// A click anywhere outside an open panel closes it — but not one
			// inside a panel, which is how a checkbox list is used.
			if ( ! event.target.closest || ! event.target.closest( SECTION + '.' + OPEN ) ) {
				closeAll( null );
			}
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key ) {
				var inside = event.target.closest && event.target.closest( SECTION + '.' + OPEN );

				closeAll( null );

				// Send focus back to the control that opened the panel, rather
				// than leaving it on a checkbox that is now display:none.
				if ( inside ) {
					var control = heading( inside );

					if ( control ) {
						control.focus();
					}
				}

				return;
			}

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
