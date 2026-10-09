/**
 * `assets/js/intro-collapse.js` — the Read more on a term description.
 *
 * The script's whole promise is "never a control that does nothing": the button
 * and the clamp appear only when clamping actually hides text. jsdom has no
 * layout, so `getBoundingClientRect()` is stubbed to model the clamp — the box is
 * its natural height, or its clamped height once the container carries
 * `.is-enhanced`. That is the same two readings the script compares.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

import { afterEach, beforeEach, describe, expect, it } from 'vitest';

import { loadScript, unloadScripts } from '../helpers/load-script';

/**
 * Build one collapse container, with its text measuring as given.
 *
 * @param {Object} measure         Heights the stubbed layout reports.
 * @param {number} measure.natural Height with no clamp.
 * @param {number} measure.clamped Height once `.is-enhanced` is on.
 * @param {string} measure.textId  An id the text already carries.
 * @return {HTMLElement} The container, attached to the body.
 */
function collapse( { natural, clamped, textId = '' } ) {
	const container = document.createElement( 'div' );

	container.className = 'sd-intro-collapse';
	container.innerHTML = `
		<div class="sd-intro-collapse__text" ${ textId ? `id="${ textId }"` : '' }>Long description</div>
		<div class="sd-intro-collapse__toggle"><a class="wp-block-button__link">Read more</a></div>`;

	container.querySelector(
		'.sd-intro-collapse__text'
	).getBoundingClientRect = () => ( {
		height: container.classList.contains( 'is-enhanced' )
			? clamped
			: natural,
	} );

	document.body.append( container );

	return container;
}

const buttonOf = ( container ) =>
	container.querySelector( '.sd-intro-collapse__toggle a' );

describe( 'intro-collapse.js', () => {
	beforeEach( () => {
		Object.defineProperty( document, 'readyState', {
			value: 'complete',
			configurable: true,
		} );
		window.sdIntroCollapse = { expandedLabel: 'Lees minder' };
	} );

	afterEach( () => {
		unloadScripts();
		delete window.sdIntroCollapse;
		document.body.innerHTML = '';
	} );

	it( 'clamps and shows the button when the text overflows', async () => {
		const container = collapse( { natural: 300, clamped: 120 } );

		await loadScript( 'intro-collapse.js' );

		expect( container.classList.contains( 'is-enhanced' ) ).toBe( true );
		expect( buttonOf( container ).getAttribute( 'role' ) ).toBe( 'button' );
		expect( buttonOf( container ).getAttribute( 'tabindex' ) ).toBe( '0' );
		expect( buttonOf( container ).getAttribute( 'aria-expanded' ) ).toBe(
			'false'
		);
	} );

	it( 'leaves a short description whole, with no clamp and a dormant button', async () => {
		const container = collapse( { natural: 120, clamped: 120 } );

		await loadScript( 'intro-collapse.js' );

		expect( container.classList.contains( 'is-enhanced' ) ).toBe( false );
		expect( buttonOf( container ).hasAttribute( 'role' ) ).toBe( false );
		expect( buttonOf( container ).hasAttribute( 'aria-expanded' ) ).toBe(
			false
		);
	} );

	it( 'treats a difference inside the tolerance as no overflow', async () => {
		const within = collapse( { natural: 124, clamped: 120 } );
		const beyond = collapse( { natural: 125, clamped: 120 } );

		await loadScript( 'intro-collapse.js' );

		expect( within.classList.contains( 'is-enhanced' ) ).toBe( false );
		expect( beyond.classList.contains( 'is-enhanced' ) ).toBe( true );
	} );

	it( 'does nothing when the container has no button', async () => {
		const container = collapse( { natural: 300, clamped: 120 } );

		container.querySelector( '.sd-intro-collapse__toggle' ).remove();
		await loadScript( 'intro-collapse.js' );

		expect( container.classList.contains( 'is-enhanced' ) ).toBe( false );
	} );

	it( 'points the button at the text it controls', async () => {
		const container = collapse( { natural: 300, clamped: 120 } );

		await loadScript( 'intro-collapse.js' );

		const text = container.querySelector( '.sd-intro-collapse__text' );

		expect( text.id ).not.toBe( '' );
		expect( buttonOf( container ).getAttribute( 'aria-controls' ) ).toBe(
			text.id
		);
	} );

	it( 'keeps an id the text already has', async () => {
		const container = collapse( {
			natural: 300,
			clamped: 120,
			textId: 'brand-intro',
		} );

		await loadScript( 'intro-collapse.js' );

		expect( buttonOf( container ).getAttribute( 'aria-controls' ) ).toBe(
			'brand-intro'
		);
	} );

	it( 'gives each container its own fallback id, so aria-controls never crosses panels', async () => {
		const [ one, two ] = [
			collapse( { natural: 300, clamped: 120 } ),
			collapse( { natural: 300, clamped: 120 } ),
		];

		await loadScript( 'intro-collapse.js' );

		const idOne = buttonOf( one ).getAttribute( 'aria-controls' );
		const idTwo = buttonOf( two ).getAttribute( 'aria-controls' );

		expect( idOne ).not.toBe( idTwo );
		expect( one.querySelector( '.sd-intro-collapse__text' ).id ).toBe(
			idOne
		);
		expect( two.querySelector( '.sd-intro-collapse__text' ).id ).toBe(
			idTwo
		);
		expect( document.querySelectorAll( `[id="${ idOne }"]` ) ).toHaveLength(
			1
		);
	} );

	it( 'skips a fallback id that another element already holds', async () => {
		const taken = document.createElement( 'div' );

		taken.id = 'sd-intro-collapse-text-1';
		document.body.append( taken );

		const container = collapse( { natural: 300, clamped: 120 } );

		await loadScript( 'intro-collapse.js' );

		expect(
			buttonOf( container ).getAttribute( 'aria-controls' )
		).not.toBe( 'sd-intro-collapse-text-1' );
	} );

	it( 'toggles on click, swapping the label and aria-expanded', async () => {
		const container = collapse( { natural: 300, clamped: 120 } );

		await loadScript( 'intro-collapse.js' );

		const button = buttonOf( container );

		button.click();
		expect( container.classList.contains( 'is-expanded' ) ).toBe( true );
		expect( button.getAttribute( 'aria-expanded' ) ).toBe( 'true' );
		expect( button.textContent ).toBe( 'Lees minder' );

		button.click();
		expect( container.classList.contains( 'is-expanded' ) ).toBe( false );
		expect( button.getAttribute( 'aria-expanded' ) ).toBe( 'false' );
		expect( button.textContent ).toBe( 'Read more' );
	} );

	it( 'falls back to "Read less" when the label was never localised', async () => {
		delete window.sdIntroCollapse;
		const container = collapse( { natural: 300, clamped: 120 } );

		await loadScript( 'intro-collapse.js' );

		buttonOf( container ).click();

		expect( buttonOf( container ).textContent ).toBe( 'Read less' );
	} );

	it.each( [ 'Enter', ' ', 'Spacebar' ] )(
		'toggles on the %j key without letting the page scroll',
		async ( key ) => {
			const container = collapse( { natural: 300, clamped: 120 } );

			await loadScript( 'intro-collapse.js' );

			const event = new KeyboardEvent( 'keydown', {
				key,
				cancelable: true,
			} );
			buttonOf( container ).dispatchEvent( event );

			expect( container.classList.contains( 'is-expanded' ) ).toBe(
				true
			);
			expect( event.defaultPrevented ).toBe( true );
		}
	);

	it( 'ignores other keys', async () => {
		const container = collapse( { natural: 300, clamped: 120 } );

		await loadScript( 'intro-collapse.js' );

		buttonOf( container ).dispatchEvent(
			new KeyboardEvent( 'keydown', { key: 'Tab', cancelable: true } )
		);

		expect( container.classList.contains( 'is-expanded' ) ).toBe( false );
	} );
} );
