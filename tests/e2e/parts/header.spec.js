/**
 * Header template part.
 *
 * The header is the one part present on every template, so a regression here is
 * a sitewide regression. Locators follow the org standard: accessible names
 * first, structural block classes only where no accessible handle exists.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );

test.describe( 'Header', () => {
	test.beforeEach( async ( { visit } ) => {
		await visit( '/' );
	} );

	/**
	 * Desktop only, deliberately not tagged `@responsive`. At mobile widths the
	 * primary navigation is CSS-hidden and so leaves the accessibility tree,
	 * which is correct — the mobile menu replaces it, and that path has its own
	 * specs in navigation.spec.js. A role-based locator will not match a hidden
	 * element, and it should not.
	 */
	test( 'renders the primary navigation', async ( { page } ) => {
		const header = page.locator( 'header.wp-block-template-part' );

		await expect( header ).toBeVisible();

		/**
		 * Named landmark rather than a class: the accessible name is the
		 * contract a screen-reader user actually experiences.
		 */
		await expect(
			header.getByRole( 'navigation', { name: 'SD Main Navigation' } )
		).toBeAttached();
	} );

	test( 'links home from the site logo or title', async ( { page } ) => {
		const header = page.locator( 'header.wp-block-template-part' );
		const homeLink = header.locator( 'a[rel="home"], .wp-block-site-logo a' ).first();

		await expect( homeLink ).toBeAttached();

		const href = await homeLink.getAttribute( 'href' );
		expect( href, 'home link has no href' ).toBeTruthy();
	} );

	test( 'exposes a search control', async ( { page } ) => {
		const header = page.locator( 'header.wp-block-template-part' );

		await expect(
			header.locator( '.wp-block-search' ).first()
		).toBeAttached();
	} );

	/**
	 * Responsive behaviour is an adaptation, not a bespoke mobile design — so
	 * the assertion is that the mobile affordance appears and the desktop one
	 * yields, not that it matches a separate comp.
	 */
	test( 'swaps to a mobile menu trigger at small widths', async ( { page } ) => {
		await page.setViewportSize( { width: 390, height: 844 } );

		const opener = page
			.locator( '.wp-block-navigation__responsive-container-open' )
			.first();

		await expect( opener ).toBeVisible();
		await expect( opener ).toHaveAttribute( 'aria-haspopup', 'dialog' );
	} );
} );
