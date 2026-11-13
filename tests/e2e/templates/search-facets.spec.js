/**
 * Search facets and sort, against the 2019 functional spec.
 *
 * ASD-41 item 21.3. search-and-archive.spec.js proves the filter rail opens and
 * collapses; this file asserts what the rail offers and what it does.
 *
 *   SF4   Facets: Search Type, Price Slider, Style, Destinations.
 *   SF5   Accommodation facets: Destinations, Style/Type, Brand/Operator.
 *   SF6   Sort by: Relevance, Sort by price.
 *   SF10  Tours and accommodation search form: Country, Region, Type.
 *   SF13  Map / List view selector.
 *
 * Graded against live, which outranks the 2019 spec (AGENTS.md, "Source-of-
 * truth precedence"), measured 2026-11-13. Live's global search rail has one
 * facet, Content Type, so dev matches it and the price slider, Style and
 * Destinations facets are not asserted there. Live has no Brand/Operator
 * facet either. Two assertions stay failing on purpose: the accommodation
 * type archive has no Style/Type facet (live does), and Price (Lowest/
 * Highest) does not order by the displayed price rating (DATA / FacetWP sort
 * config).
 *
 * The Country/Region/Type form and the Map/List selector do not exist on dev
 * yet, so those tests skip with that reason and switch on when they are built.
 *
 * Out of scope here: autocomplete and SearchWP weighting (open decision).
 * GET only; nothing is submitted.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );
const { results } = require( '../utils/page-contract.js' );

const SEARCH = '/?s=safari';

/**
 * Open a search page and wait for FacetWP's first load, so the facets exist.
 *
 * @param {import('@playwright/test').Page} page  Page.
 * @param {Function}                        visit Visit helper.
 * @param {string}                          path  Route.
 */
async function openWithFacets( page, visit, path ) {
	await visit( path );
	await page
		.waitForFunction( () => window.FWP && window.FWP.loaded, null, {
			timeout: 20000,
		} )
		.catch( () => {} );
}

/**
 * The post type of each result card, read from the card's post-type class
 * (`tour`, `accommodation`, ...).
 *
 * @param {import('@playwright/test').Page} page Page.
 * @return {Promise<string[]>} Post type per card, in page order.
 */
async function cardTypes( page ) {
	return results( page ).evaluateAll( ( cards ) =>
		cards.map(
			( card ) =>
				[
					'tour',
					'accommodation',
					'destination',
					'special',
					'team',
					'post',
					'page',
				].find( ( type ) => card.classList.contains( type ) ) || 'other'
		)
	);
}

/**
 * The "Price Rating: $$$" badge count on each card that has one.
 *
 * @param {import('@playwright/test').Page} page Page.
 * @return {Promise<number[]>} Ratings in page order.
 */
async function cardPriceRatings( page ) {
	return results( page ).evaluateAll( ( cards ) =>
		cards
			.map( ( card ) => {
				const rating = card.querySelector(
					'.lsx-price-rating-wrapper'
				);
				return rating
					? ( rating.textContent.match( /\$/g ) || [] ).length
					: 0;
			} )
			.filter( ( count ) => 0 < count )
	);
}

/**
 * Wait for FacetWP to finish a refresh, by watching the card list change.
 *
 * @param {import('@playwright/test').Page} page   Page.
 * @param {string}                          before Card titles before the change, joined.
 */
async function waitForRefresh( page, before ) {
	await expect
		.poll(
			async () =>
				(
					await results( page ).locator( 'h2, h3' ).allInnerTexts()
				).join( '|' ),
			{ message: 'FacetWP did not refresh the results', timeout: 20000 }
		)
		.not.toBe( before );
}

test.describe( 'Search facets (global rail)', () => {
	// requirement: RQ-SF4 / test-case: TC-SF4-01
	test( 'offers a Search Type facet with tours and accommodation', async ( {
		page,
		visit,
	} ) => {
		await openWithFacets( page, visit, SEARCH );

		const facet = page.locator( '.facetwp-facet-post_type' );

		await expect( facet ).toContainText( 'Tours' );
		await expect( facet ).toContainText( 'Accommodation' );
	} );

	// requirement: RQ-SF4 / test-case: TC-SF4-02
	test( 'choosing Tours narrows the results to tours', async ( {
		page,
		visit,
	} ) => {
		await openWithFacets( page, visit, SEARCH );

		const before = (
			await results( page ).locator( 'h2, h3' ).allInnerTexts()
		).join( '|' );
		const total = page.locator( '.facetwp-facet-results_count' );
		const totalBefore = await total.innerText();

		await page
			.locator( '.facetwp-facet-post_type .facetwp-checkbox' )
			.filter( { hasText: 'Tours' } )
			.click();
		await waitForRefresh( page, before );

		await expect( total, 'the result count did not drop' ).not.toHaveText(
			totalBefore
		);

		const types = await cardTypes( page );

		expect( types.length, 'no results after filtering' ).toBeGreaterThan(
			0
		);
		expect(
			new Set( types ),
			'non-tour results survived the filter'
		).toEqual( new Set( [ 'tour' ] ) );
	} );

	/**
	 * Live outranks the 2019 spec. Live's global /search/safari rail carries a
	 * single Content Type (`post_type`) facet: no price slider, Style or
	 * Destinations (measured 2026-11-13). Those belong to the post-type
	 * scoped searches, so dev's single-facet global rail matches live.
	 */
	// requirement: RQ-SF4 / test-case: TC-SF4-03
	test( 'offers only the Content Type facet, as live does', async ( {
		page,
		visit,
	} ) => {
		await openWithFacets( page, visit, SEARCH );

		const facets = await page
			.locator( 'aside.sd-search-filters .facetwp-facet' )
			.evaluateAll( ( nodes ) =>
				nodes
					.map( ( node ) => node.dataset.name )
					.filter(
						( name ) =>
							! /^(results_count|sort_|pager_?)$/.test( name )
					)
			);

		expect(
			facets,
			'the global rail should offer Content Type only'
		).toEqual( [ 'post_type' ] );
	} );
} );

test.describe( 'Sort by', () => {
	// requirement: RQ-SF6 / test-case: TC-SF6-01
	test( 'offers price sorting', async ( { page, visit } ) => {
		await openWithFacets( page, visit, SEARCH );

		const options = await page
			.locator( '.facetwp-facet-sort_ select option' )
			.allInnerTexts();

		expect( options.join( ',' ) ).toMatch( /price \( ?highest/i );
		expect( options.join( ',' ) ).toMatch( /price \( ?lowest/i );
	} );

	/**
	 * DATA / config, kept failing. Dev's Price (Lowest) and Price (Highest)
	 * return the same order, so the FacetWP sort options are not wired to the
	 * price-rating meta. Live offers price sorting only on the post-type
	 * scoped accommodation search, not on the global /search/safari rail
	 * (measured 2026-11-13), so this global-search check is stricter than
	 * live and the fix belongs to the FacetWP sort configuration.
	 *
	 * What a visitor sees is the "Price Rating: $$$" badge on each card, so
	 * that is what the order is judged by. Cards with no badge (tours) are
	 * ignored. "Relevance" is the unlabelled first option ("Sort by"), which is
	 * FacetWP's default order, so it is not asserted by name.
	 */
	for ( const order of [
		{ label: 'Price (Lowest)', ascending: true },
		{ label: 'Price (Highest)', ascending: false },
	] ) {
		// requirement: RQ-SF6 / test-case: TC-SF6-02
		test( `${ order.label } orders the cards by price`, async ( {
			page,
			visit,
		} ) => {
			await openWithFacets( page, visit, SEARCH );

			const before = (
				await results( page ).locator( 'h2, h3' ).allInnerTexts()
			).join( '|' );

			await page
				.locator( '.facetwp-facet-sort_ select' )
				.selectOption( { label: order.label } );
			await waitForRefresh( page, before );

			const ratings = await cardPriceRatings( page );
			const sorted = [ ...ratings ].sort( ( a, b ) =>
				order.ascending ? a - b : b - a
			);

			expect(
				ratings.length,
				'no priced cards to judge the order by'
			).toBeGreaterThan( 2 );
			expect(
				ratings,
				`${ order.label } did not order the cards by their displayed price rating`
			).toEqual( sorted );
		} );
	}
} );

test.describe( 'Accommodation facets', () => {
	// requirement: RQ-SF5 / test-case: TC-SF5-01
	for ( const facet of [
		{ name: 'Destinations', pattern: /destination/i },
		// REGRESSION, kept failing. Live's accommodation rail carries an
		// `accommodation_type` facet (measured 2026-11-13); dev's does not.
		// Live has no Brand/Operator facet, so the 2019 Brand item is dropped.
		{ name: 'Style/Type', pattern: /(style|type)/i },
	] ) {
		test( `an accommodation archive offers a ${ facet.name } facet`, async ( {
			page,
			visit,
			routes,
		} ) => {
			const target = routes.term( 'accommodation-type' );
			test.skip( ! target, 'No accommodation type archive' );

			await page.setViewportSize( { width: 1280, height: 900 } );
			await openWithFacets( page, visit, target );

			const facets = await page
				.locator( 'aside.sd-search-filters .facetwp-facet' )
				.evaluateAll( ( nodes ) =>
					nodes.map( ( node ) => node.dataset.name )
				);

			expect(
				facets.some( ( name ) => facet.pattern.test( name ) ),
				`no ${ facet.name } facet; the rail offers: ${ facets.join( ', ' ) }`
			).toBe( true );
		} );
	}
} );

test.describe( 'Country / Region / Type search form', () => {
	// requirement: RQ-SF10 / test-case: TC-SF10-01
	test( 'selecting a country repopulates the region list', async ( {
		page,
		visit,
	} ) => {
		await visit( '/accommodation/' );

		const country = page.getByLabel( /^country$/i );
		const region = page.getByLabel( /^region$/i );

		test.skip(
			0 === ( await country.count() ),
			'Not built on dev (2026-11-13): no Country/Region/Type form on /accommodation/'
		);

		const regionsBefore = await region.locator( 'option' ).allInnerTexts();
		const target = (
			await country.locator( 'option' ).allInnerTexts()
		)[ 1 ];

		await country.selectOption( { label: target } );

		await expect
			.poll( async () => region.locator( 'option' ).allInnerTexts() )
			.not.toEqual( regionsBefore );
	} );
} );

test.describe( 'Map / List view selector', () => {
	// requirement: RQ-SF13 / test-case: TC-SF13-01
	test( 'accommodation search can switch to a map view', async ( {
		page,
		visit,
	} ) => {
		await openWithFacets( page, visit, '/search/accommodation/safari/' );

		const mapToggle = page
			.getByRole( 'button', { name: /map/i } )
			.or( page.getByRole( 'tab', { name: /map/i } ) )
			.or( page.getByRole( 'radio', { name: /map/i } ) );

		test.skip(
			0 === ( await mapToggle.count() ),
			'Not built on dev (2026-11-13): no Map/List selector on accommodation search'
		);

		await mapToggle.first().click();

		await expect(
			page.locator( '[class*="map"]' ).first(),
			'the map view did not show a map container'
		).toBeVisible();
	} );
} );
