/**
 * Search and 404 templates.
 *
 * Both have an empty state that is easy to ship broken, because the happy path
 * is what gets looked at during development.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );
const { SYNTHETIC_ROUTES } = require( '../fixtures/routes.js' );
const {
	assertPageContract,
	mainContent,
	results,
} = require( '../utils/page-contract.js' );

test.describe( 'Search and 404', () => {
	for ( const route of SYNTHETIC_ROUTES ) {
		test( `${ route.name } (${ route.template }) renders @responsive`, async ( {
			page,
			visit,
		} ) => {
			await visit( route.path, {
				expectStatus: route.expectStatus || 200,
			} );

			await assertPageContract( page );
		} );
	}

	test( 'a search with results lists them', async ( { page, visit } ) => {
		await visit( '/?s=safari' );

		await expect(
			results( page ),
			'search for "safari" returned nothing — check the search template query'
		).not.toHaveCount( 0 );
	} );

	test( 'a search with no results says so rather than rendering blank', async ( {
		page,
		visit,
	} ) => {
		await visit( '/?s=zzzznotarealquery' );

		const text = ( await mainContent( page ).innerText() ).trim();

		expect(
			text.length,
			'empty search rendered an empty main — no "nothing found" message'
		).toBeGreaterThan( 20 );

		await expect(
			results( page ),
			'a search that matched nothing still listed results'
		).toHaveCount( 0 );
	} );

	test( '404 offers a way back', async ( { page, visit } ) => {
		await visit( '/this-page-does-not-exist-sd-e2e/', { expectStatus: 404 } );

		/**
		 * A 404 that is only a headline is a dead end. Search or a link out
		 * is the minimum.
		 */
		const hasWayBack =
			0 <
			( await mainContent( page )
				.locator( 'a[href], .wp-block-search' )
				.count() );

		expect( hasWayBack, '404 template offers no search or link out' ).toBe(
			true
		);
	} );

	test( 'the search form submits from the header', async ( { page, visit } ) => {
		await visit( '/' );

		const form = page.locator( 'header.wp-block-template-part form' ).first();
		test.skip(
			0 === ( await form.count() ),
			'No search form in the header'
		);

		const input = form.locator( 'input[type="search"], input[name="s"]' ).first();

		/**
		 * The header search is a collapsed, expand-on-click control: until the
		 * button is pressed the input carries `aria-hidden="true"` and
		 * `tabindex="-1"`, so it is neither typeable nor reachable. Clicking
		 * the toggle first is what a real user does, and it also asserts that
		 * the Interactivity binding still works.
		 */
		const toggle = form.locator( 'button.wp-block-search__button' ).first();

		if ( 'true' === ( await input.getAttribute( 'aria-hidden' ) ) ) {
			await toggle.click();

			await expect(
				input,
				'clicking the search toggle did not reveal the input'
			).not.toHaveAttribute( 'aria-hidden', 'true' );
		}

		await input.fill( 'safari' );
		await input.press( 'Enter' );

		await page.waitForURL( /[?&]s=safari/ );

		expect( page.url() ).toMatch( /[?&]s=safari/ );
	} );
} );
