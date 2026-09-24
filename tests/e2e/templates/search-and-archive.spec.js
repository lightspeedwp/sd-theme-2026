/**
 * Search results and the all-archives page, finalised 2026-09-23.
 *
 * LS-2024 (line 14, Search and Filtering — 14.4 search templates) and LS-2022
 * (line 12, Blog Templates — the archives blog content lands on). Covers:
 *
 *   - `search.html`              → patterns/template-page-search.php
 *   - `archive.html`, `tag.html` → patterns/template-page-archive.php
 *
 * **The banner** on both is the hero banner, phone stack included, on the
 * 360px floor — utils/hero-banner.js carries that contract. The archive's `h1`
 * is the archive title, with no "Tag:" prefix.
 *
 * **The archive is the search layout less three things**: the keyword box, the
 * result count and the sort. The rail, its Content Type facet, the Filters
 * trigger and the pager stay.
 *
 * **The search page's keyword box survives the phone collapse.** FacetWP Flyout
 * carries facets into its panel and nothing else, so the `core/search` field is
 * exempted from the collapse and sits above the Filters button.
 *
 * Empty-state and header-search behaviour stay in search-and-404.spec.js.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );
const { describeHeroBanners } = require( '../utils/hero-banner.js' );

const SEARCH = '/?s=safari';
const ARCHIVES = [
	{ name: 'tag archive', path: ( routes ) => routes.term( 'post_tag' ) },
	{ name: 'author archive', path: ( routes ) => routes.archive( 'author' ) },
];

describeHeroBanners( { test, expect }, [
	{ name: 'search results', path: () => SEARCH, strapline: true },
	...ARCHIVES.map( ( route ) => ( { ...route, strapline: true } ) ),
] );

/**
 * Wait for FacetWP's first load, and report whether the Flyout add-on came
 * with it — the script marks the root only when `FWP.flyout` exists.
 *
 * @param {import('@playwright/test').Page} page Page.
 * @return {Promise<boolean>} Whether the flyout is available.
 */
async function flyoutReady( page ) {
	await page.waitForFunction( () => window.FWP && window.FWP.loaded, null, { timeout: 15000 } ).catch( () => {} );

	return page.evaluate( () => document.documentElement.classList.contains( 'sd-has-filter-flyout' ) );
}

test.describe( 'All archives', () => {
	for ( const archive of ARCHIVES ) {
		test( `${ archive.name } titles the banner with the archive name, unprefixed`, async ( {
			page,
			visit,
			routes,
		} ) => {
			const target = archive.path( routes );
			test.skip( ! target, `No ${ archive.name }` );

			await visit( target );

			const title = page.locator( 'main > .wp-block-cover h1.wp-block-query-title' );
			await expect( title ).toHaveCount( 1 );
			await expect( title ).not.toHaveText( /^\s*$/ );
			await expect( title ).not.toHaveText( /^\s*(tag|category|author|archives?)\s*:/i );
		} );

		test( `${ archive.name } is the search layout without the keyword box, count or sort`, async ( {
			page,
			visit,
			routes,
		} ) => {
			const target = archive.path( routes );
			test.skip( ! target, `No ${ archive.name }` );

			await page.setViewportSize( { width: 1280, height: 900 } );
			await visit( target );

			const main = page.locator( 'main' );
			await expect( main.locator( 'aside.sd-search-filters' ) ).toHaveCount( 1 );
			await expect( main.locator( 'aside.sd-search-filters .sd-filters-toggle' ) ).toHaveCount( 1 );
			await expect( main.locator( '.wp-block-query' ) ).toHaveCount( 1 );

			await expect(
				main.locator( '.wp-block-search' ),
				'the archive still carries the keyword box'
			).toHaveCount( 0 );
			await expect(
				main.locator( '.sd-search-toolbar' ),
				'the archive still carries the results toolbar'
			).toHaveCount( 0 );
			await expect( main.locator( '.sd-search-sort, .sd-search-counts' ) ).toHaveCount( 0 );
		} );
	}
} );

test.describe( 'Search results filter rail', () => {
	test( 'opens with the keyword box, above the rail on desktop @responsive', async ( { page, visit } ) => {
		await page.setViewportSize( { width: 1280, height: 900 } );
		await visit( SEARCH );
		await flyoutReady( page );

		const field = page.locator( 'aside.sd-search-filters > .wp-block-search' );
		await expect( field ).toBeVisible();
		await expect( field.locator( 'input[type="search"]' ) ).toHaveValue( 'safari' );
		await expect( page.locator( '.sd-filters-toggle' ) ).toBeHidden();
	} );

	test( 'keeps the keyword box on phones, above the Filters button @responsive', async ( { page, visit } ) => {
		await page.setViewportSize( { width: 390, height: 844 } );
		await visit( SEARCH );
		test.skip( ! ( await flyoutReady( page ) ), 'FacetWP Flyout is not active here' );

		const field = page.locator( 'aside.sd-search-filters > .wp-block-search' );
		const trigger = page.locator( 'aside.sd-search-filters > .sd-filters-toggle button' );

		await expect( field, 'the phone collapse hid the keyword box' ).toBeVisible();
		await expect( trigger ).toBeVisible();
		await expect( page.locator( 'aside.sd-search-filters > .wp-block-heading' ) ).toBeHidden();

		const [ fieldBox, triggerBox ] = await Promise.all( [ field.boundingBox(), trigger.boundingBox() ] );
		expect( fieldBox.y, 'the keyword box is not above the Filters button' ).toBeLessThan( triggerBox.y );

		await trigger.click();

		const panel = page.locator( '.facetwp-flyout' );
		await expect( panel ).toHaveClass( /\bactive\b/ );
		await expect( panel.locator( '.facetwp-facet-post_type' ) ).toHaveCount( 1 );
		await expect( panel.locator( '.facetwp-type-sort' ) ).toHaveCount( 0 );
	} );
} );
