/**
 * The contract every front-end page in this theme must satisfy.
 *
 * Kept in one place deliberately. Thirty-one templates asserting the same six
 * things inline is thirty-one places to update when the contract changes, and in
 * practice means the later templates get a weaker check than the early ones.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { expect } = require( '@playwright/test' );

/**
 * A rendered query-loop item.
 *
 * FacetWP renders its "nothing matched" message as an
 * `<li class="facetwp-no-results">` inside the post template, so an empty
 * search would otherwise count as one result.
 */
const POST_ITEM =
	'.wp-block-post, .wp-block-post-template > li:not(.facetwp-no-results)';

/**
 * The main landmark.
 *
 * Everything that counts results must be scoped through this. The header's mega
 * menus contain their own query loops — popular destinations, countries, tour
 * types — so a page-wide count of `.wp-block-post` returns eighteen items on a
 * search that matched nothing. Scoping here is the difference between an
 * assertion that means something and one that can never fail.
 *
 * @param {import('@playwright/test').Page} page Page under test.
 * @return {import('@playwright/test').Locator} The main landmark.
 */
function mainContent( page ) {
	return page.locator( 'main, [role="main"]' ).first();
}

/**
 * Query-loop results inside the main landmark.
 *
 * @param {import('@playwright/test').Page} page Page under test.
 * @return {import('@playwright/test').Locator} Result items.
 */
function results( page ) {
	return mainContent( page ).locator( POST_ITEM );
}

/**
 * Assert the structural invariants of a rendered block-theme page.
 *
 * @param {import('@playwright/test').Page} page      Page under test.
 * @param {Object}                          [options] Options.
 * @param {boolean}                         [options.expectH1] Require exactly one h1.
 */
async function assertPageContract( page, options = {} ) {
	const { expectH1 = true } = options;

	/**
	 * The block theme rendered at all. If this is missing the template failed
	 * to resolve and every other assertion below is noise.
	 */
	await expect(
		page.locator( '.wp-site-blocks' ),
		'block theme wrapper missing — template did not resolve'
	).toHaveCount( 1 );

	await expect(
		page.locator( 'header.wp-block-template-part' ),
		'header template part missing'
	).toHaveCount( 1 );

	await expect(
		page.locator( 'footer.wp-block-template-part' ),
		'footer template part missing'
	).toHaveCount( 1 );

	/**
	 * A landmark-less page is a screen-reader dead end, and it is the single
	 * most common regression when a template is rebuilt from blocks.
	 */
	await expect(
		page.locator( 'main, [role="main"]' ).first(),
		'no main landmark'
	).toBeAttached();

	const title = await page.title();
	expect( title.trim(), 'empty <title>' ).not.toBe( '' );

	if ( expectH1 ) {
		/**
		 * Exactly one h1. Two is the usual symptom of a hero pattern and a
		 * post-title block both claiming the page heading.
		 */
		await expect(
			page.locator( 'h1' ),
			'expected exactly one h1'
		).toHaveCount( 1 );
	}
}

/**
 * Assert no heading level is skipped (h1 → h3 with no h2).
 *
 * A separate check from the contract because a handful of templates legitimately
 * fail it today and should be fixed rather than excused; call it where the
 * hierarchy is expected to be clean.
 *
 * @param {import('@playwright/test').Page} page Page under test.
 */
async function assertHeadingHierarchy( page ) {
	const levels = await page
		.locator( 'h1, h2, h3, h4, h5, h6' )
		.evaluateAll( ( nodes ) =>
			nodes.map( ( node ) => ( {
				level: Number( node.tagName.slice( 1 ) ),
				text: ( node.textContent || '' ).trim().slice( 0, 60 ),
			} ) )
		);

	const skips = [];
	let previous = 0;

	for ( const heading of levels ) {
		if ( previous && heading.level > previous + 1 ) {
			skips.push(
				`h${ previous } → h${ heading.level } at "${ heading.text }"`
			);
		}

		previous = heading.level;
	}

	expect( skips, `skipped heading levels: ${ skips.join( '; ' ) }` ).toEqual(
		[]
	);
}

/**
 * Assert the page has no obviously broken internal links.
 *
 * Checks only same-origin hrefs and only the first `limit` of them — a full
 * crawl belongs in a link checker, not in a rendering suite.
 *
 * @param {import('@playwright/test').Page} page    Page under test.
 * @param {number}                          [limit] Maximum links to check.
 */
async function assertNoEmptyLinks( page, limit = 200 ) {
	const bad = await page
		.locator( 'a[href]' )
		.evaluateAll(
			( nodes, max ) =>
				nodes
					.slice( 0, max )
					.filter( ( node ) => {
						const href = node.getAttribute( 'href' ) || '';

						/**
						 * `#` alone and an empty href are both symptoms of a
						 * binding that failed to resolve a permalink.
						 */
						return '' === href.trim() || '#' === href.trim();
					} )
					.map( ( node ) =>
						( node.textContent || node.outerHTML ).trim().slice( 0, 60 )
					),
			limit
		);

	expect(
		bad,
		`links with empty or placeholder href: ${ bad.join( '; ' ) }`
	).toEqual( [] );
}

module.exports = {
	assertPageContract,
	assertHeadingHierarchy,
	assertNoEmptyLinks,
	mainContent,
	results,
	POST_ITEM,
};
