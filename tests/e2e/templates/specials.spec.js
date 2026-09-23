/**
 * Specials — the Specials landing page, finalised 2026-09-23.
 *
 * LS-2021 (Specials). Covers `archive-special.html` →
 * patterns/template-archive-special.php. There is no single template: the site
 * links to an offer as `/specials/#special-{slug}`, never to a page of its own.
 *
 * **The banner** is the hero banner, phone stack included, on the 360px floor —
 * utils/hero-banner.js carries that contract.
 *
 * **The offer band** is one full-width band per special: the featured image as
 * its ground, a 460px scrim panel against the leading edge of odd bands and the
 * trailing edge of even ones, never shorter than 540px, the bands butted
 * together. Below 900px the panel narrows to 90% and centres. Measured from
 * live's `.lsx-to-archive-item` on 2026-09-17 (styles/sections/cards/special-card.json).
 *
 * **The anchor** `special-{slug}` is injected per band by
 * `SD\Enhancements\Specials::add_band_anchor()`; these tests fail if the plugin
 * stops doing so or the band's `is-style-special-card` class is renamed.
 *
 * **Book Special** on every band points at `#to-modal-modal-special`, and the
 * page prints that one dialog however many bands carry the button.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );
const { describeHeroBanners } = require( '../utils/hero-banner.js' );

/**
 * A literal path, prefixed the way this environment's permalinks need.
 *
 * Local Studio runs PATH_INFO permalinks (`/index.php/…`); the prefix is read
 * off a permalink global setup already resolved.
 *
 * @param {Object} routes The `routes` fixture.
 * @param {string} path   Path with a leading slash.
 * @return {string} Path for this environment.
 */
function envPath( routes, path ) {
	const sample = routes.post( 'tour' ) || routes.post( 'accommodation' ) || '';
	return `${ sample.startsWith( '/index.php/' ) ? '/index.php' : '' }${ path }`;
}

const BAND = 'main .wp-block-post-template > li > .is-style-special-card';
const PANEL = `${ BAND } > .wp-block-group`;
const MODAL = '#to-modal-modal-special';

/**
 * Visit the landing page and skip when it lists no offers.
 *
 * @param {Object} fixtures `{ page, visit, routes }`.
 * @return {Promise<import('@playwright/test').Locator>} The bands.
 */
async function visitSpecials( { page, visit, routes } ) {
	await visit( envPath( routes, '/specials/' ) );

	const bands = page.locator( BAND );
	test.skip( 0 === ( await bands.count() ), 'No published specials in this environment' );

	return bands;
}

describeHeroBanners( { test, expect }, [
	{ name: 'specials archive', path: ( routes ) => envPath( routes, '/specials/' ), strapline: true },
] );

test.describe( 'Specials offer bands', () => {
	test( 'lists at most four offers, each titled once with an h2', async ( { page, visit, routes } ) => {
		const bands = await visitSpecials( { page, visit, routes } );
		const count = await bands.count();

		expect( count, 'more than live\'s four offers to a page' ).toBeLessThanOrEqual( 4 );

		for ( let i = 0; i < count; i++ ) {
			const titles = bands.nth( i ).locator( 'h2.wp-block-post-title' );
			await expect( titles ).toHaveCount( 1 );
			expect( ( await titles.textContent() ).trim(), `band ${ i + 1 } has an empty title` ).not.toBe( '' );
		}
	} );

	test( 'gives every band a unique special-{slug} anchor', async ( { page, visit, routes } ) => {
		const bands = await visitSpecials( { page, visit, routes } );
		const ids = await bands.evaluateAll( ( els ) => els.map( ( el ) => el.id ) );

		for ( const id of ids ) {
			expect( id, 'a band has no special-{slug} anchor — is Specials::add_band_anchor() running?' ).toMatch( /^special-[a-z0-9-]+$/ );
		}

		expect( new Set( ids ).size, 'two bands share an anchor' ).toBe( ids.length );
	} );

	test( 'scrolls a /specials/#special-{slug} link to its band', async ( { page, visit, routes } ) => {
		const bands = await visitSpecials( { page, visit, routes } );
		const id = await bands.last().getAttribute( 'id' );
		test.skip( ! id, 'The last band has no anchor' );

		// A hash change on the loaded page is not a navigation; start from blank
		// so the browser's own load-time scroll to the fragment is what's tested.
		await page.goto( 'about:blank' );
		await page.setViewportSize( { width: 1280, height: 800 } );
		await visit( `${ envPath( routes, '/specials/' ) }#${ id }` );

		await expect( page.locator( `#${ id }` ) ).toBeInViewport();
	} );

	test( 'draws each band on its photograph, 540px floor, panel alternating edge @responsive', async ( {
		page,
		visit,
		routes,
	} ) => {
		await page.setViewportSize( { width: 1280, height: 900 } );
		const bands = await visitSpecials( { page, visit, routes } );
		const count = await bands.count();

		for ( let i = 0; i < count; i++ ) {
			const band = bands.nth( i );
			const box = await band.boundingBox();

			expect( box.height, `band ${ i + 1 } is below its 540px floor` ).toBeGreaterThanOrEqual( 539 );

			const image = band.locator( ':scope > .wp-block-post-featured-image' );

			if ( await image.count() ) {
				await expect( image ).toHaveCSS( 'position', 'absolute' );
				const imageBox = await image.boundingBox();
				expect( Math.abs( imageBox.height - box.height ), `band ${ i + 1 }'s photograph does not fill it` ).toBeLessThanOrEqual( 1 );
			}

			const panel = await band.locator( ':scope > .wp-block-group' ).boundingBox();
			expect( panel.width, `band ${ i + 1 }'s panel is wider than 460px` ).toBeLessThanOrEqual( 461 );

			// Odd bands (1st, 3rd) hold the panel on the leading edge, even on the trailing.
			if ( 0 === i % 2 ) {
				expect( Math.abs( panel.x - box.x ), `band ${ i + 1 }'s panel is not on the leading edge` ).toBeLessThanOrEqual( 1 );
			} else {
				expect(
					Math.abs( panel.x + panel.width - ( box.x + box.width ) ),
					`band ${ i + 1 }'s panel is not on the trailing edge`
				).toBeLessThanOrEqual( 1 );
			}
		}
	} );

	test( 'butts the bands together with no gap', async ( { page, visit, routes } ) => {
		await page.setViewportSize( { width: 1280, height: 900 } );
		const bands = await visitSpecials( { page, visit, routes } );
		const count = await bands.count();
		test.skip( 2 > count, 'Fewer than two offers to compare' );

		for ( let i = 1; i < count; i++ ) {
			const above = await bands.nth( i - 1 ).boundingBox();
			const below = await bands.nth( i ).boundingBox();

			expect( Math.abs( below.y - ( above.y + above.height ) ), `a gap sits above band ${ i + 1 }` ).toBeLessThanOrEqual( 1 );
		}
	} );

	test( 'narrows and centres the panel on phones @responsive', async ( { page, visit, routes } ) => {
		await page.setViewportSize( { width: 390, height: 844 } );
		const bands = await visitSpecials( { page, visit, routes } );
		const count = await bands.count();

		for ( let i = 0; i < Math.min( count, 2 ); i++ ) {
			const box = await bands.nth( i ).boundingBox();
			const panel = await page.locator( PANEL ).nth( i ).boundingBox();

			expect( panel.width / box.width, `band ${ i + 1 }'s panel is not 90% of the band` ).toBeCloseTo( 0.9, 1 );

			const left = panel.x - box.x;
			const right = box.x + box.width - ( panel.x + panel.width );
			expect( Math.abs( left - right ), `band ${ i + 1 }'s panel is not centred` ).toBeLessThanOrEqual( 2 );
		}
	} );
} );

test.describe( 'Book Special', () => {
	test( 'points every band at the one specials dialog', async ( { page, visit, routes } ) => {
		const bands = await visitSpecials( { page, visit, routes } );
		const count = await bands.count();

		const buttons = page.locator( `${ PANEL } .wp-block-button__link` );
		await expect( buttons ).toHaveCount( count );

		for ( let i = 0; i < count; i++ ) {
			await expect( buttons.nth( i ) ).toHaveText( /book special/i );
			await expect( buttons.nth( i ) ).toHaveAttribute( 'href', MODAL );
		}

		// Four bands, one dialog: the module deduplicates by slug.
		await expect( page.locator( `dialog${ MODAL }` ) ).toHaveCount( 1 );
	} );

	test( 'opens the specials dialog', async ( { page, visit, routes } ) => {
		await page.setViewportSize( { width: 1280, height: 900 } );
		await visitSpecials( { page, visit, routes } );

		await page.locator( `${ PANEL } .wp-block-button__link` ).first().click();

		await expect( page.locator( `dialog${ MODAL }` ) ).toBeVisible();
	} );
} );
