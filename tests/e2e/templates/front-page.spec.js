/**
 * The front page's per-screen-size swaps.
 *
 * The homepage is the one template that is *swapped* rather than restyled
 * between breakpoints: the hero is three covers (desktop 720px, tablet 544px,
 * phone 153px) and each of the four photograph panels has a phone-only copy,
 * all chosen by Block Visibility's `screenSize` control. Every one of those
 * values was measured on the live site on 2026-09-23. The failure these
 * guard against is silent — two heroes stacked, a panel shown twice, or none —
 * and it would only be noticed by someone reading the page on a phone.
 *
 * The variant is derived from the viewport width. That is only safe because
 * the three projects that run `@responsive` sit well inside one Block
 * Visibility screen size on every install: 375px is *small*, 768px *medium*
 * and 1280px *large* on both dev (large ≥992px) and local (large ≥1200px).
 * A project at 992–1199px would land in different sizes per environment —
 * see the plugin's printed `block-visibility-screen-size-styles-inline-css`.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );

/**
 * What each screen size shows, as measured on live.
 */
const VARIANTS = {
	phone: { hero: 153, quote: false, intro: false },
	tablet: { hero: 544, quote: true, intro: true },
	desktop: { hero: 720, quote: true, intro: true },
};

/**
 * The four main panels, by the start of their heading.
 */
const PANELS = [
	'Explore the destinations',
	'Review the lodges',
	'Choose your safari experience',
	'Ask us to design your dream trip',
];

/**
 * @param {import('@playwright/test').Page} page Page under test.
 * @return {'phone'|'tablet'|'desktop'} The variant this viewport should show.
 */
function variantFor( page ) {
	const { width } = page.viewportSize();

	if ( width < 768 ) {
		return 'phone';
	}

	return width < 992 ? 'tablet' : 'desktop';
}

test.describe( 'Front page', () => {
	test( 'shows exactly one hero, sized for this screen @responsive', async ( {
		page,
		visit,
	} ) => {
		await visit( '/' );
		const variant = variantFor( page );
		const expected = VARIANTS[ variant ];

		const heroes = page
			.locator( 'main .wp-block-cover' )
			.filter( { has: page.locator( 'h1' ) } )
			.filter( { visible: true } );

		await expect(
			heroes,
			`the ${ variant } view should show one hero cover — Block Visibility ` +
				'is showing none or several'
		).toHaveCount( 1 );

		await expect( page.getByRole( 'heading', { level: 1 } ) ).toHaveCount( 1 );

		const { height } = await heroes.first().boundingBox();

		expect(
			Math.abs( height - expected.hero ),
			`the ${ variant } hero is ${ Math.round( height ) }px; live is ${ expected.hero }px. ` +
				'On phones and tablets check the 430px cover floor in assets/styles/core-cover.css'
		).toBeLessThanOrEqual( 2 );

		const quote = heroes.first().getByText( 'The entire trip was amazing' );

		if ( expected.quote ) {
			await expect( quote ).toBeVisible();
		} else {
			await expect( quote ).toHaveCount( 0 );
		}
	} );

	test( 'renders each main panel once, in this screen’s layout @responsive', async ( {
		page,
		visit,
	} ) => {
		await visit( '/' );
		const variant = variantFor( page );

		for ( const name of PANELS ) {
			/**
			 * `getByRole` ignores `display: none`, so a count of one proves the
			 * hidden copy really is hidden and the shown one really is shown.
			 */
			const heading = page
				.locator( 'main' )
				.getByRole( 'heading', { level: 2, name: new RegExp( `^${ name }` ) } );

			await expect(
				heading,
				`"${ name }" should appear exactly once at ${ variant } width`
			).toHaveCount( 1 );

			const layout = await heading.evaluate( ( h ) => {
				// The panel is the heading's ancestor that sits directly in the
				// main-content wrapper — the group holding all eight copies.
				let panel = h;
				while (
					panel.parentElement &&
					panel.parentElement.querySelectorAll( ':scope > .wp-block-group' )
						.length < 4
				) {
					panel = panel.parentElement;
				}

				const photo = panel.querySelector( 'figure img' );
				const body = h.nextElementSibling;

				return {
					background: getComputedStyle( panel ).backgroundImage,
					photoAbove:
						!! photo &&
						photo.getBoundingClientRect().bottom <= h.getBoundingClientRect().top,
					bodyColor: body ? getComputedStyle( body ).color : null,
				};
			} );

			if ( 'phone' === variant ) {
				expect(
					layout.photoAbove,
					`"${ name }" on phones should have its photograph above the heading`
				).toBe( true );

				/**
				 * Live's own tablet view regressed exactly here: the copy kept
				 * its white colour after the photograph moved out from behind
				 * it, and rendered white on white.
				 */
				expect(
					layout.bodyColor,
					`"${ name }" body copy is white on a white page`
				).not.toBe( 'rgb(255, 255, 255)' );
			} else {
				expect(
					layout.background,
					`"${ name }" at ${ variant } width should sit on its background photograph`
				).toContain( 'url(' );
			}
		}
	} );

	test( 'shows the intro monogram and "Start here" everywhere but phones @responsive', async ( {
		page,
		visit,
	} ) => {
		await visit( '/' );
		const shown = VARIANTS[ variantFor( page ) ].intro;

		const monogram = page.locator( 'main img.wp-image-50292' );
		const startHere = page
			.locator( 'main' )
			.getByRole( 'heading', { name: /^Start here/ } );

		if ( shown ) {
			await expect( monogram ).toBeVisible();
			await expect( startHere ).toHaveCount( 1 );
		} else {
			await expect( monogram ).toBeHidden();
			await expect( startHere ).toHaveCount( 0 );
		}
	} );

	/**
	 * An SVG `mask="url(#id)"` resolves against the first element in the
	 * document with that id. The panel arrows are written out once per copy, so
	 * if a shown arrow's mask resolves into a copy Block Visibility has set to
	 * `display: none`, the browser has no rendered mask to apply and the arrow
	 * does not paint. The phone copies carry their own `sd-arrow-mobile-*` ids
	 * for this reason; this keeps a future edit from collapsing them.
	 */
	test( 'every shown arrow resolves its mask in a rendered SVG @responsive', async ( {
		page,
		visit,
	} ) => {
		await visit( '/' );

		const broken = await page.evaluate( () =>
			[ ...document.querySelectorAll( 'main svg [mask]' ) ]
				.filter( ( el ) => el.ownerSVGElement.checkVisibility() )
				.map( ( el ) => el.getAttribute( 'mask' ).match( /#([^)]+)/ )?.[ 1 ] )
				.filter( ( id ) => {
					const target = id && document.getElementById( id );
					return ! target || ! target.ownerSVGElement?.checkVisibility();
				} )
		);

		expect(
			broken,
			'these mask ids resolve to a missing or hidden definition'
		).toEqual( [] );
	} );

	/**
	 * Not `@responsive`: the fault lives between the fixed breakpoints, so it
	 * sweeps its own widths on the desktop project only. Below ~1434px the
	 * `alignwide` shelves run close to the viewport edge, and the arrows' design
	 * offset (38px outside the frame) used to push the document past it — 20px
	 * at 1280, 19px at 1366. The clamp in assets/styles/core-group.css keeps
	 * them in; this proves it at the common laptop widths either side.
	 */
	test( 'carousel arrows stay inside the viewport from 1200 to 1440px', async ( {
		page,
		visit,
	} ) => {
		for ( const width of [ 1200, 1280, 1366, 1440 ] ) {
			await page.setViewportSize( { width, height: 900 } );
			await visit( '/' );

			// Slick builds the arrows after DOMContentLoaded.
			await page.waitForLoadState( 'load' );

			// Local holds almost no content, so its shelves can be empty and
			// Tour Operator never starts a slider. Nothing to measure there.
			test.skip(
				0 === ( await page.locator( '.lsx-to-slider' ).count() ),
				'no carousel on this install’s front page'
			);

			await page
				.locator( '.slick-initialized' )
				.first()
				.waitFor( { state: 'attached' } );

			const result = await page.evaluate( () => {
				const clientWidth = document.documentElement.clientWidth;

				return {
					clientWidth,
					overflow: document.documentElement.scrollWidth - clientWidth,
					outside: [ ...document.querySelectorAll( '.slick-arrow' ) ]
						.filter( ( arrow ) => arrow.checkVisibility() )
						.map( ( arrow ) => arrow.getBoundingClientRect() )
						.filter( ( r ) => r.left < 0 || r.right > clientWidth + 1 )
						.map( ( r ) => `${ Math.round( r.left ) }–${ Math.round( r.right ) }` ),
				};
			} );

			expect(
				result.outside,
				`carousel arrows outside the ${ result.clientWidth }px viewport at ${ width }px`
			).toEqual( [] );

			expect(
				result.overflow,
				`the front page scrolls sideways at ${ width }px`
			).toBeLessThanOrEqual( 1 );
		}
	} );
} );
