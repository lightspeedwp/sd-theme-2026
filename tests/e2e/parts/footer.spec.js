/**
 * Footer template part.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );

test.describe( 'Footer', () => {
	test.beforeEach( async ( { visit } ) => {
		await visit( '/' );
	} );

	test( 'renders and carries content @responsive', async ( { page } ) => {
		const footer = page.locator( 'footer.wp-block-template-part' );

		await expect( footer ).toBeVisible();

		const text = ( await footer.innerText() ).trim();
		expect( text.length, 'footer rendered empty' ).toBeGreaterThan( 20 );
	} );

	test( 'footer navigation links resolve', async ( { page } ) => {
		const footer = page.locator( 'footer.wp-block-template-part' );
		const links = footer.locator( 'a[href]' );

		await expect( links ).not.toHaveCount( 0 );

		const placeholders = await links.evaluateAll( ( nodes ) =>
			nodes
				.filter( ( node ) => {
					const href = ( node.getAttribute( 'href' ) || '' ).trim();
					return '' === href || '#' === href;
				} )
				.map( ( node ) => ( node.textContent || '' ).trim() )
		);

		expect(
			placeholders,
			`footer links with no destination: ${ placeholders.join( '; ' ) }`
		).toEqual( [] );
	} );
} );
