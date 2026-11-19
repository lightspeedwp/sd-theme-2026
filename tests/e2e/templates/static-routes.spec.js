/**
 * Every route that exists by construction renders its template.
 *
 * This is the suite's floor: if one of these goes red, something is broken for
 * every visitor, not for an edge case.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );
const { STATIC_ROUTES } = require( '../fixtures/routes.js' );
const {
	assertPageContract,
	assertNoEmptyLinks,
	results,
} = require( '../utils/page-contract.js' );

test.describe( 'Static routes', () => {
	for ( const route of STATIC_ROUTES ) {
		test( `${ route.name } (${ route.template }) renders @responsive${
			route.smoke ? ' @smoke' : ''
		}`, async ( { page, visit } ) => {
			await visit( route.path );
			await assertPageContract( page );
		} );
	}

	test( 'front page has no placeholder links', async ( { page, visit } ) => {
		await visit( '/' );
		await assertNoEmptyLinks( page );
	} );

	/**
	 * The archives are the templates most likely to be silently empty: the
	 * query block is easy to mis-scope to the wrong post type, and the page
	 * still returns 200 while showing nothing.
	 */
	const archives = STATIC_ROUTES.filter( ( route ) =>
		route.template.startsWith( 'archive-' )
	);

	/**
	 * /tours/ and /accommodation/ are landing pages, not post listings: their
	 * patterns (template-archive-tour / -accommodation) run a terms query over
	 * travel styles and accommodation types, so they hold no `.wp-block-post`
	 * at all. Their results are the term cards.
	 */
	const TERM_ARCHIVES = [ 'tour archive', 'accommodation archive' ];

	for ( const archive of archives ) {
		test( `${ archive.name } lists results`, async ( { page, visit } ) => {
			await visit( archive.path );

			const found = TERM_ARCHIVES.includes( archive.name )
				? page.locator( 'main .wp-block-term-template > li' )
				: results( page );

			await expect(
				found,
				`${ archive.path } rendered no results in its main landmark — ` +
					"check the query block's post type"
			).not.toHaveCount( 0 );
		} );
	}
} );
