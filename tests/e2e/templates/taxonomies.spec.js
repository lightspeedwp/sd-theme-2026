/**
 * Taxonomy archive templates.
 *
 * Global setup picks the term with the most posts in each taxonomy, so these
 * exercise the populated branch of the template rather than its empty state.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );
const { RESOLVED_TAXONOMIES } = require( '../fixtures/routes.js' );
const {
	assertPageContract,
	results,
} = require( '../utils/page-contract.js' );

test.describe( 'Taxonomy archives', () => {
	for ( const route of RESOLVED_TAXONOMIES ) {
		test( `${ route.name } (${ route.template }) renders @responsive`, async ( {
			page,
			visit,
			routes,
		} ) => {
			const target = routes.term( route.key );

			test.skip(
				! target,
				`No populated term in ${ route.key } on ${ routes.baseURL }`
			);

			await visit( target );
			await assertPageContract( page );

			await expect(
				results( page ),
				`${ target } is a populated term but listed nothing`
			).not.toHaveCount( 0 );
		} );
	}
} );
