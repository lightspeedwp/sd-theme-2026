/**
 * `assets/js/search-filters.js` — the collapsible facet folds in the filter rail.
 *
 * Playwright covers the rail on a real page; what it cannot reach cheaply is the
 * branching: which fold opens first, what survives a FacetWP re-render, and
 * which keys count. Those are the parts that break quietly, so they are pinned
 * here against a hand-built rail shaped like FacetWP's.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

import { afterEach, beforeEach, describe, expect, it } from 'vitest';

import { loadScript, unloadScripts } from '../helpers/load-script';

/**
 * One facet section as the rail renders it: a heading, then FacetWP's facet.
 *
 * @param {string}  label          Heading text.
 * @param {Object}  options        Options.
 * @param {string}  options.hidden Extra class on the section, e.g. `facetwp-hidden`.
 * @param {boolean} options.bare   Leave out the facet, so there is nothing to fold.
 * @return {string} Markup.
 */
const section = ( label, { hidden = '', bare = false } = {} ) => `
	<div class="facet-wrap ${ hidden }">
		<h3 class="wp-block-heading">${ label }</h3>
		${ bare ? '' : '<div class="facetwp-facet"></div>' }
	</div>`;

/**
 * Build the rail and run the script over it.
 *
 * @param {string} sections Markup of the sections.
 * @return {Promise<void>} Resolves when the script has initialised.
 */
async function mount( sections ) {
	document.body.innerHTML = `<aside class="sd-search-filters">${ sections }</aside>`;

	await loadScript( 'search-filters.js' );
}

const rail = () => document.querySelector( '.sd-search-filters' );
const folds = () => [ ...document.querySelectorAll( '.facet-wrap' ) ];
const headingOf = ( fold ) => fold.querySelector( '.wp-block-heading' );

describe( 'search-filters.js', () => {
	beforeEach( () => {
		Object.defineProperty( document, 'readyState', {
			value: 'complete',
			configurable: true,
		} );
	} );

	afterEach( () => {
		unloadScripts();
		document.body.innerHTML = '';
	} );

	it( 'does nothing on a page with no filter rail', async () => {
		document.body.innerHTML = section( 'Country' );

		await loadScript( 'search-filters.js' );

		expect( folds()[ 0 ].classList.contains( 'sd-facet-fold' ) ).toBe(
			false
		);
	} );

	it( 'marks the rail collapsible and opens only the first eligible fold', async () => {
		await mount( section( 'Country' ) + section( 'Style' ) );

		const [ first, second ] = folds();

		expect( rail().classList.contains( 'sd-filters-collapsible' ) ).toBe(
			true
		);
		expect( first.classList.contains( 'sd-filter-open' ) ).toBe( true );
		expect( headingOf( first ).getAttribute( 'aria-expanded' ) ).toBe(
			'true'
		);
		expect( second.classList.contains( 'sd-filter-open' ) ).toBe( false );
		expect( headingOf( second ).getAttribute( 'aria-expanded' ) ).toBe(
			'false'
		);
	} );

	it( 'skips a hidden or empty section when choosing the one to open', async () => {
		await mount(
			section( 'Hidden', { hidden: 'facetwp-hidden' } ) +
				section( 'Empty', { bare: true } ) +
				section( 'Style' )
		);

		const [ hidden, empty, style ] = folds();

		expect( hidden.classList.contains( 'sd-facet-fold' ) ).toBe( false );
		expect( empty.classList.contains( 'sd-facet-fold' ) ).toBe( false );
		expect( style.classList.contains( 'sd-filter-open' ) ).toBe( true );
	} );

	it( 'wires each heading to its panel with a unique id and a tab stop', async () => {
		await mount( section( 'Country' ) + section( 'Style' ) );

		const ids = folds().map( ( fold ) => {
			const panel = fold.querySelector( '.facetwp-facet' );

			expect( headingOf( fold ).getAttribute( 'aria-controls' ) ).toBe(
				panel.id
			);
			expect( headingOf( fold ).getAttribute( 'tabindex' ) ).toBe( '0' );

			return panel.id;
		} );

		expect( new Set( ids ).size ).toBe( 2 );
		expect( ids[ 0 ] ).toMatch( /^sd-facet-panel-\d+$/ );
	} );

	it( 'toggles a fold on click, and back', async () => {
		await mount( section( 'Country' ) + section( 'Style' ) );

		const control = headingOf( folds()[ 1 ] );

		control.click();
		expect( control.getAttribute( 'aria-expanded' ) ).toBe( 'true' );

		control.click();
		expect( control.getAttribute( 'aria-expanded' ) ).toBe( 'false' );
	} );

	it.each( [ 'Enter', ' ', 'Spacebar' ] )(
		'toggles a fold on the %j key, and ignores others',
		async ( key ) => {
			await mount( section( 'Country' ) + section( 'Style' ) );

			const control = headingOf( folds()[ 1 ] );

			control.dispatchEvent(
				new KeyboardEvent( 'keydown', { key: 'a', bubbles: true } )
			);
			expect( control.getAttribute( 'aria-expanded' ) ).toBe( 'false' );

			const event = new KeyboardEvent( 'keydown', {
				key,
				bubbles: true,
				cancelable: true,
			} );
			control.dispatchEvent( event );

			expect( control.getAttribute( 'aria-expanded' ) ).toBe( 'true' );
			expect( event.defaultPrevented ).toBe( true );
		}
	);

	it( 'leaves a click outside any heading alone', async () => {
		await mount( section( 'Country' ) );

		const panel = document.querySelector( '.facetwp-facet' );
		const event = new MouseEvent( 'click', {
			bubbles: true,
			cancelable: true,
		} );
		panel.dispatchEvent( event );

		expect( event.defaultPrevented ).toBe( false );
		expect( folds()[ 0 ].classList.contains( 'sd-filter-open' ) ).toBe(
			true
		);
	} );

	/**
	 * FacetWP replaces the facet markup on every refine and fires
	 * `facetwp-loaded`. The script has to re-wire the new nodes without
	 * re-opening a fold the visitor has just closed — or opening a second one.
	 */
	it( 'keeps the visitor’s choice when FacetWP re-renders', async () => {
		await mount( section( 'Country' ) + section( 'Style' ) );

		const [ first, second ] = folds();

		headingOf( second ).click();
		headingOf( first ).click();

		expect( first.classList.contains( 'sd-filter-open' ) ).toBe( false );
		expect( second.classList.contains( 'sd-filter-open' ) ).toBe( true );

		rail().insertAdjacentHTML( 'beforeend', section( 'Month' ) );
		document.dispatchEvent( new Event( 'facetwp-loaded' ) );

		const [ , , added ] = folds();

		expect( first.classList.contains( 'sd-filter-open' ) ).toBe( false );
		expect( second.classList.contains( 'sd-filter-open' ) ).toBe( true );
		expect( added.classList.contains( 'sd-facet-fold' ) ).toBe( true );
		expect( added.classList.contains( 'sd-filter-open' ) ).toBe( false );
	} );
} );
