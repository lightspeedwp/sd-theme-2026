/**
 * Search results and the all-archives page, finalised 2026-09-23.
 *
 * LS-2024 (line 14, Search and Filtering — 14.4 search templates) and LS-2022
 * (line 12, Blog Templates — the archives blog content lands on). Covers:
 *
 *   - `search.html`  → patterns/template-page-search.php
 *   - `archive.html` → patterns/template-page-archive.php
 *
 * Tag and author archives are the blog archive, not this layout — blog.spec.js
 * covers them.
 *
 * `archive.html` has no route to test. It serves `special-type`, `post_format`
 * and `role`, none of which is in REST on dev or linked from anywhere, so there
 * is nothing to resolve. An "All archives" describe sat here with an empty
 * target list and produced no tests; it was removed 2026-10-09 (ASD-36). Bring
 * it back from git history when one of those archives gets a public URL.
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

describeHeroBanners( { test, expect }, [
	{ name: 'search results', path: () => SEARCH, strapline: true },
] );

/**
 * Wait for FacetWP's first load, and report whether the Flyout add-on came
 * with it — the script marks the root only when `FWP.flyout` exists.
 *
 * @param {import('@playwright/test').Page} page Page.
 * @return {Promise<boolean>} Whether the flyout is available.
 */
async function flyoutReady( page ) {
	await page
		.waitForFunction( () => window.FWP && window.FWP.loaded, null, {
			timeout: 15000,
		} )
		.catch( () => {} );

	return page.evaluate( () =>
		document.documentElement.classList.contains( 'sd-has-filter-flyout' )
	);
}

test.describe( 'Search results filter rail', () => {
	test( 'opens with the keyword box, above the rail on desktop @responsive', async ( {
		page,
		visit,
	} ) => {
		await page.setViewportSize( { width: 1280, height: 900 } );
		await visit( SEARCH );
		await flyoutReady( page );

		const field = page.locator(
			'aside.sd-search-filters > .wp-block-search'
		);
		await expect( field ).toBeVisible();
		await expect( field.locator( 'input[type="search"]' ) ).toHaveValue(
			'safari'
		);
		await expect( page.locator( '.sd-filters-toggle' ) ).toBeHidden();
	} );

	test( 'keeps the keyword box on phones, above the Filters button @responsive', async ( {
		page,
		visit,
	} ) => {
		await page.setViewportSize( { width: 390, height: 844 } );
		await visit( SEARCH );
		test.skip(
			! ( await flyoutReady( page ) ),
			'FacetWP Flyout is not active here'
		);

		const field = page.locator(
			'aside.sd-search-filters > .wp-block-search'
		);
		const trigger = page.locator(
			'aside.sd-search-filters > .sd-filters-toggle button'
		);

		await expect(
			field,
			'the phone collapse hid the keyword box'
		).toBeVisible();
		await expect( trigger ).toBeVisible();
		await expect(
			page.locator( 'aside.sd-search-filters > .wp-block-heading' )
		).toBeHidden();

		const [ fieldBox, triggerBox ] = await Promise.all( [
			field.boundingBox(),
			trigger.boundingBox(),
		] );
		expect(
			fieldBox.y,
			'the keyword box is not above the Filters button'
		).toBeLessThan( triggerBox.y );

		await trigger.click();

		const panel = page.locator( '.facetwp-flyout' );
		await expect( panel ).toHaveClass( /\bactive\b/ );
		await expect( panel.locator( '.facetwp-facet-post_type' ) ).toHaveCount(
			1
		);
		await expect( panel.locator( '.facetwp-type-sort' ) ).toHaveCount( 0 );
	} );

	/**
	 * `search-filters.js` turns each facet's heading into a disclosure. The unit
	 * tests cover which fold opens first and what survives a re-render; this is
	 * the wiring on a real FacetWP rail: the script loaded, found the markup,
	 * and the controls answer to a pointer and to the keyboard.
	 */
	test( 'a facet heading closes and reopens its panel on click and on Enter', async ( {
		page,
		visit,
	} ) => {
		await page.setViewportSize( { width: 1280, height: 900 } );
		await visit( SEARCH );
		await flyoutReady( page );

		const folds = page.locator(
			'aside.sd-search-filters .facet-wrap.sd-facet-fold'
		);
		test.skip(
			0 === ( await folds.count() ),
			'No facet folds in this environment'
		);

		await expect(
			page.locator( 'aside.sd-search-filters' ),
			'search-filters.js did not run'
		).toHaveClass( /\bsd-filters-collapsible\b/ );

		// The first eligible fold opens by itself, so start from open and close.
		const heading = folds.first().locator( ':scope > .wp-block-heading' );
		await expect( heading ).toHaveAttribute( 'aria-expanded', 'true' );

		await heading.click();
		await expect( heading ).toHaveAttribute( 'aria-expanded', 'false' );
		await expect( folds.first() ).not.toHaveClass( /\bsd-filter-open\b/ );

		await heading.focus();
		await page.keyboard.press( 'Enter' );
		await expect( heading ).toHaveAttribute( 'aria-expanded', 'true' );
	} );

	/**
	 * FacetWP prints a "See N more" / "See less" pair under a long facet and
	 * hides the inactive one with `.facetwp-hidden`. The rail's own
	 * `.sd-search-filters .facetwp-toggle { display: inline-block }` outranked
	 * that, so both showed as "See 5 moreSee less" (2026-10-09).
	 */
	test( 'a long facet shows one of its See more / See less toggles at a time', async ( {
		page,
		visit,
	} ) => {
		await page.setViewportSize( { width: 1280, height: 900 } );
		await visit( '/accommodation-type/safari-lodges/' );
		await flyoutReady( page );

		const fold = page
			.locator( 'aside.sd-search-filters .facet-wrap' )
			.filter( { has: page.locator( '.facetwp-toggle' ) } )
			.first();
		test.skip(
			0 === ( await fold.count() ),
			'No facet with a See more toggle in this environment'
		);

		// search-filters.js folds all but the first facet; open this one.
		const heading = fold.locator( ':scope > .wp-block-heading' );
		if ( 'false' === ( await heading.getAttribute( 'aria-expanded' ) ) ) {
			await heading.click();
			await expect( heading ).toHaveAttribute( 'aria-expanded', 'true' );
		}

		const toggles = fold.locator( '.facetwp-toggle' );
		const shown = toggles.filter( { visible: true } );
		await expect( shown ).toHaveCount( 1 );
		const before = await shown.textContent();

		await shown.click();
		await expect( shown ).toHaveCount( 1 );
		await expect( shown ).not.toHaveText( before );
	} );
} );
