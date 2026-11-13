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
		await visit( '/this-page-does-not-exist-sd-e2e/', {
			expectStatus: 404,
		} );

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

	test( 'the search form submits from the header', async ( {
		page,
		visit,
	} ) => {
		await visit( '/' );

		const form = page
			.locator( 'header.wp-block-template-part form' )
			.first();
		test.skip(
			0 === ( await form.count() ),
			'No search form in the header'
		);

		const input = form
			.locator( 'input[type="search"], input[name="s"]' )
			.first();

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

/**
 * The 2019 spec's pretty search endpoints (SF1, SF2, SF3):
 * `/search/key+words/`, `/search/tours/key+words/` and
 * `/search/accommodation/key+words/`. The post-type segment is meant to scope
 * the engine, so `/search/accommodation/safari/` is a search for "safari" in
 * accommodation.
 *
 * Measured on dev 2026-11-13: all three routes answer 200 and list results, but
 * the rewrite hands the whole path to WordPress as the keyword, so the box
 * reads "accommodation/safari" and the results are not narrowed to the post
 * type. `/search/` on its own is a 404; the spec names no bare endpoint, so
 * nothing here asserts on it.
 *
 * Search autocomplete and SearchWP weighting depend on an open decision and are
 * not tested here.
 */
const SEARCH_ENDPOINTS = [
	{ id: 'SF1', path: '/search/safari/', postType: null },
	{ id: 'SF2', path: '/search/tours/safari/', postType: 'tour' },
	{
		id: 'SF3',
		path: '/search/accommodation/safari/',
		postType: 'accommodation',
	},
];

/*
 * REGRESSION, kept failing. Live outranks the 2019 spec, and live scopes
 * /search/accommodation/safari/ and /search/tours/safari/ to that post type
 * and shows only "safari" in the keyword box (measured 2026-11-13). Dev
 * shows "accommodation/safari" and lists every post type. Owner: the
 * sd-enhancements plugin (search routing).
 */
test.describe( 'Pretty search endpoints', () => {
	for ( const endpoint of SEARCH_ENDPOINTS ) {
		// requirement: RQ-SF1..SF3 / test-case: TC-SF-01 (one per endpoint)
		test( `${ endpoint.path } renders a results page`, async ( {
			page,
			visit,
		} ) => {
			await visit( endpoint.path );

			await assertPageContract( page );

			await expect(
				results( page ),
				`${ endpoint.path } listed no results for "safari"`
			).not.toHaveCount( 0 );
		} );

		// requirement: RQ-SF1..SF3, RQ-SF7 / test-case: TC-SF-02
		test( `${ endpoint.path } puts only the keyword in the search box`, async ( {
			page,
			visit,
		} ) => {
			await visit( endpoint.path );

			await expect(
				mainContent( page ).locator( 'input[type="search"]' ).first(),
				'the search box should carry the keyword "safari", not the post-type segment of the URL'
			).toHaveValue( 'safari' );
		} );

		if ( endpoint.postType ) {
			// requirement: RQ-SF2, RQ-SF3 / test-case: TC-SF-03
			test( `${ endpoint.path } lists only ${ endpoint.postType } results`, async ( {
				page,
				visit,
			} ) => {
				await visit( endpoint.path );

				const total = await results( page ).count();
				const matching = results( page ).locator(
					`xpath=self::*[contains(concat(" ", @class, " "), " ${ endpoint.postType } ")]`
				);

				await expect(
					matching,
					`${ endpoint.path } should list ${ endpoint.postType } only, but ${ total - matching } of ${ total } results on page one are another post type`
				).toHaveCount( total );
			} );
		}
	}
} );
