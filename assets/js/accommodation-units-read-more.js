/**
 * Read more / Read less for the accommodation units band.
 *
 * Tour Operator ships this behaviour for itineraries and not for units. Its
 * `set_read_more()` only acts on a `.wp-block-read-more` whose parent
 * `.wp-block-group` holds a `.wp-block-post-content`, and
 * `set_read_more_itinerary()` is scoped to `.lsx-itinerary-wrapper`
 * (tour-operator/build/custom.js) — a unit's description is neither. TO does,
 * however, bind a `preventDefault()` click handler to *every*
 * `.single-tour-operator .wp-block-read-more`, so an unwired one is a dead link.
 * This is the missing handler, written against the same contract as TO's
 * itinerary one so the two behave identically.
 *
 * Progressive enhancement: nothing is collapsed until this runs, so with the
 * script blocked the reader gets the whole description and a link to the page
 * they are already on.
 *
 * @package sd-theme-2026
 */

( function () {
	'use strict';

	var WRAPPER = '.lsx-units-wrapper .unit-description-wrapper';

	/**
	 * The description's own block-level children — the paragraphs Tour Operator's
	 * `build_unit_field()` writes into the `<div class="unit-description">` it
	 * swaps in for the authored `<p>`.
	 *
	 * @param {Element} wrapper The `unit-description-wrapper` group.
	 * @return {Element[]} The description's children, or an empty array.
	 */
	function paragraphs( wrapper ) {
		var description = wrapper.querySelector( '.unit-description' );

		return description ? Array.prototype.slice.call( description.children ) : [];
	}

	/**
	 * The anchor's own label text node.
	 *
	 * `core/read-more` renders `Read more<span class="screen-reader-text">: Post
	 * Title</span>`, so the label is the first text node and not `textContent`.
	 * TO's handler reads it the same way. Writing to the node rather than to
	 * `textContent` also leaves anything else in the anchor alone.
	 *
	 * @param {Element} link The read-more anchor.
	 * @return {Text|null} The label node.
	 */
	function labelNode( link ) {
		return Array.prototype.find.call( link.childNodes, function ( node ) {
			return Node.TEXT_NODE === node.nodeType && node.nodeValue.trim();
		} ) || null;
	}

	/**
	 * Collapse to the first paragraph, or retire the link if there is nothing to
	 * collapse. Mirrors TO's `readMoreSet()`.
	 *
	 * @param {Element} link    The read-more anchor.
	 * @param {Element} wrapper The `unit-description-wrapper` group.
	 */
	function collapse( link, wrapper ) {
		var children = paragraphs( wrapper );

		if ( children.length < 2 ) {
			link.hidden = true;
			return;
		}

		children.forEach( function ( child, index ) {
			child.style.display = index === 0 ? '' : 'none';
		} );

		link.hidden = false;
		link.setAttribute( 'aria-expanded', 'false' );
		labelNode( link ).nodeValue = link.dataset.moreLabel;
	}

	/**
	 * Show the whole description. Mirrors TO's `readMoreOpen()`.
	 *
	 * @param {Element} link    The read-more anchor.
	 * @param {Element} wrapper The `unit-description-wrapper` group.
	 */
	function expand( link, wrapper ) {
		paragraphs( wrapper ).forEach( function ( child ) {
			child.style.display = '';
		} );

		link.setAttribute( 'aria-expanded', 'true' );
		labelNode( link ).nodeValue = link.dataset.lessLabel;
	}

	function init() {
		document.querySelectorAll( WRAPPER ).forEach( function ( wrapper ) {
			var link = wrapper.querySelector( '.wp-block-read-more' );
			var label = link && labelNode( link );

			if ( ! label ) {
				return;
			}

			/*
			 * This expands the copy in place; it does not navigate. So the href
			 * goes, the role becomes a button, and core's "Read more: <post
			 * title>" screen-reader suffix goes with it — it describes a link to
			 * the page the reader is already on.
			 */
			link.removeAttribute( 'href' );
			link.removeAttribute( 'target' );
			link.setAttribute( 'role', 'button' );
			link.setAttribute( 'tabindex', '0' );

			var suffix = link.querySelector( '.screen-reader-text' );

			if ( suffix ) {
				suffix.remove();
			}

			link.dataset.moreLabel = label.nodeValue.trim();
			link.dataset.lessLabel = sdUnitsReadMore.readLess;

			collapse( link, wrapper );

			function toggle( event ) {
				event.preventDefault();

				if ( 'true' === link.getAttribute( 'aria-expanded' ) ) {
					collapse( link, wrapper );
				} else {
					expand( link, wrapper );
				}
			}

			link.addEventListener( 'click', toggle );
			link.addEventListener( 'keydown', function ( event ) {
				if ( ' ' === event.key || 'Enter' === event.key ) {
					toggle( event );
				}
			} );
		} );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
