/**
 * `assets/js/call-us-flip.js` — the Call Us panel opens upwards near the bottom
 * of the viewport.
 *
 * jsdom has no layout, so the panel's box, its containing block's box and the
 * viewport height are stubbed. Those are the three readings the script compares.
 * The mutation observer delivers in a microtask, so each test awaits one tick
 * after changing `aria-expanded`.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

import { afterEach, beforeEach, describe, expect, it } from 'vitest';

import { loadScript, unloadScripts } from '../helpers/load-script';

const VIEWPORT = 800;
const GAP = 8;
const HEIGHT = 200;

/**
 * Build one Call Us item with stubbed geometry.
 *
 * @param {number} anchorTop Top of the containing block, in viewport px.
 * @param {number} height    Natural panel height.
 * @return {{toggle: HTMLElement, panel: HTMLElement}} The two elements.
 */
function callUs( anchorTop, height = HEIGHT ) {
	const nav = document.createElement( 'nav' );

	nav.className = 'is-style-call-us-navigation';
	nav.innerHTML = `
		<ul><li>
			<button class="wp-block-ollie-mega-menu__toggle" aria-expanded="false"></button>
			<div class="wp-block-ollie-mega-menu__menu-container"></div>
		</li></ul>`;
	document.body.append( nav );

	const toggle = nav.querySelector( '.wp-block-ollie-mega-menu__toggle' );
	const panel = nav.querySelector(
		'.wp-block-ollie-mega-menu__menu-container'
	);

	Object.defineProperty( panel, 'offsetParent', {
		value: {
			getBoundingClientRect: () => ( {
				top: anchorTop,
				bottom: anchorTop + 40,
			} ),
		},
	} );
	Object.defineProperty( panel, 'scrollHeight', { value: height } );
	panel.getBoundingClientRect = () => {
		const cappedHeight = Math.min(
			height,
			parseFloat(
				panel.style.getPropertyValue( '--sd-call-us-max-height' )
			) || height
		);
		const top = panel.classList.contains( 'is-flipped-up' )
			? anchorTop - GAP - cappedHeight
			: anchorTop + 40 + GAP;

		return { top, bottom: top + cappedHeight };
	};

	return {
		toggle,
		panel,
		move: ( top ) => {
			anchorTop = top;
		},
	};
}

const open = async ( toggle ) => {
	toggle.setAttribute( 'aria-expanded', 'true' );
	await Promise.resolve();
};

describe( 'call-us-flip.js', () => {
	beforeEach( () => {
		Object.defineProperty( document, 'readyState', {
			value: 'complete',
			configurable: true,
		} );
		window.innerHeight = VIEWPORT;
	} );

	afterEach( () => {
		unloadScripts();
		document.body.innerHTML = '';
	} );

	it( 'leaves the panel below the trigger when it fits', async () => {
		const { toggle, panel } = callUs( 100 );

		await loadScript( 'call-us-flip.js' );
		await open( toggle );

		expect( panel.classList.contains( 'is-flipped-up' ) ).toBe( false );
	} );

	it( 'flips the panel up when it would cross the bottom and there is more room above', async () => {
		const { toggle, panel } = callUs( 700 );

		await loadScript( 'call-us-flip.js' );
		await open( toggle );

		expect( panel.classList.contains( 'is-flipped-up' ) ).toBe( true );
	} );

	it( 'stays below when it overflows but there is even less room above', async () => {
		const { toggle, panel } = callUs( 20 );

		window.innerHeight = 150;
		await loadScript( 'call-us-flip.js' );
		await open( toggle );

		expect( panel.classList.contains( 'is-flipped-up' ) ).toBe( false );
	} );

	it( 'does nothing while the toggle is closed', async () => {
		const { toggle, panel } = callUs( 700 );

		await loadScript( 'call-us-flip.js' );
		toggle.setAttribute( 'aria-expanded', 'false' );
		await Promise.resolve();

		expect( panel.classList.contains( 'is-flipped-up' ) ).toBe( false );
	} );

	it( 're-measures on the next open', async () => {
		const { toggle, panel } = callUs( 700 );

		await loadScript( 'call-us-flip.js' );
		await open( toggle );
		expect( panel.classList.contains( 'is-flipped-up' ) ).toBe( true );

		toggle.setAttribute( 'aria-expanded', 'false' );
		window.innerHeight = 2000;
		await open( toggle );

		expect( panel.classList.contains( 'is-flipped-up' ) ).toBe( false );
	} );

	it( 'ignores a navigation that is not Call Us', async () => {
		const { toggle, panel } = callUs( 700 );

		toggle.closest( 'nav' ).className = 'is-style-main-navigation';
		await loadScript( 'call-us-flip.js' );
		await open( toggle );

		expect( panel.classList.contains( 'is-flipped-up' ) ).toBe( false );
	} );

	it.each( [ 'resize', 'scroll' ] )(
		'repositions open panels on %s',
		async ( event ) => {
			const { toggle, panel, move } = callUs( 100 );

			await loadScript( 'call-us-flip.js' );
			await open( toggle );
			move( 700 );
			window.dispatchEvent( new Event( event ) );
			expect( panel.classList.contains( 'is-flipped-up' ) ).toBe( true );
			expect(
				panel.style.getPropertyValue( '--sd-call-us-max-height' )
			).toBe( '692px' );

			move( 100 );
			window.dispatchEvent( new Event( event ) );
			expect( panel.classList.contains( 'is-flipped-up' ) ).toBe( false );
		}
	);

	it.each( [ 'resize', 'scroll' ] )(
		'ignores closed panels on %s',
		async ( event ) => {
			const { panel } = callUs( 700 );

			await loadScript( 'call-us-flip.js' );
			window.dispatchEvent( new Event( event ) );
			expect(
				panel.style.getPropertyValue( '--sd-call-us-max-height' )
			).toBe( '' );
			expect( panel.classList.contains( 'is-flipped-up' ) ).toBe( false );
		}
	);

	it( 'remeasures when a containing element scrolls without bubbling', async () => {
		const { toggle, panel, move } = callUs( 100 );

		await loadScript( 'call-us-flip.js' );
		await open( toggle );
		move( 700 );
		toggle.closest( 'nav' ).dispatchEvent( new Event( 'scroll' ) );
		expect( panel.classList.contains( 'is-flipped-up' ) ).toBe( true );
	} );

	it.each( [
		[ 100, false, '152px' ],
		[ 220, true, '212px' ],
	] )(
		'caps a tall panel on the chosen side at anchor %s',
		async ( top, flipped, cap ) => {
			const { toggle, panel } = callUs( top, 500 );

			window.innerHeight = 300;
			await loadScript( 'call-us-flip.js' );
			await open( toggle );
			expect( panel.classList.contains( 'is-flipped-up' ) ).toBe(
				flipped
			);
			expect(
				panel.style.getPropertyValue( '--sd-call-us-max-height' )
			).toBe( cap );
			panel.scrollTop = 50;
			panel.dispatchEvent( new Event( 'scroll' ) );
			expect( panel.classList.contains( 'is-flipped-up' ) ).toBe(
				flipped
			);
			expect(
				panel.style.getPropertyValue( '--sd-call-us-max-height' )
			).toBe( cap );
			expect( panel.scrollTop ).toBe( 50 );
		}
	);
} );
