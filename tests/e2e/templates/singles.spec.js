/**
 * Single templates for each custom post type.
 *
 * Routes are resolved live in global-setup.js, so these specs follow content
 * rather than hardcoded slugs. Where an environment has no content of a type —
 * local Studio holds tours only — the spec skips with a stated reason.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );
const { RESOLVED_ROUTES } = require( '../fixtures/routes.js' );
const {
	assertPageContract,
	assertNoEmptyLinks,
} = require( '../utils/page-contract.js' );

test.describe( 'Single templates', () => {
	for ( const route of RESOLVED_ROUTES ) {
		test( `${ route.name } (${ route.template }) renders @responsive${
			route.smoke ? ' @smoke' : ''
		}`, async ( {
			page,
			visit,
			routes,
		} ) => {
			const target = routes.post( route.key );

			test.skip(
				! target,
				`No published ${ route.key } on ${ routes.baseURL }`
			);

			await visit( target );
			await assertPageContract( page );

			/**
			 * A single with an empty content area usually means the template
			 * is rendering but its post-content block lost its binding.
			 */
			const main = page.locator( 'main, [role="main"]' ).first();
			const text = ( await main.innerText() ).trim();

			expect(
				text.length,
				`${ target } rendered an empty main landmark`
			).toBeGreaterThan( 50 );
		} );
	}

	test( 'single tour has no placeholder links', async ( {
		page,
		visit,
		routes,
	} ) => {
		const target = routes.post( 'tour' );
		test.skip( ! target, 'No published tour' );

		await visit( target );
		await assertNoEmptyLinks( page );
	} );
} );
