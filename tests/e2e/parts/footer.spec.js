/**
 * Footer template part.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );
const { contrastRatio, presetColor } = require( '../utils/contrast.js' );

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

/**
 * Hover colours — orange, and AA on the ground they sit on.
 *
 * axe never hovers, so a failing hover colour passes every scan in a11y/. The
 * footer shipped one: `neutral-500` over the photograph's pale band, 2.93:1.
 * These hover the links themselves and read what the browser resolved.
 *
 * Two oranges, because no single one passes on both grounds: `brand-700` on the
 * pale widget band (5.81:1 against neutral-200) and `brand-400` on the dark
 * colophon (5.75:1 against primary-600). `brand-500`, the brand orange itself,
 * fails on both — 2.92:1 and 3.96:1.
 *
 * `toHaveCSS` retries, which is what absorbs the links' 0.25s colour
 * transition.
 */
test.describe( 'Footer link hovers', () => {
	test.beforeEach( async ( { visit } ) => {
		await visit( '/' );
	} );

	test( 'widget-area links hover to brand-700', async ( { page } ) => {
		const expected = await presetColor( page, 'brand-700' );

		/**
		 * The photograph is a background image, so there is no computed colour
		 * to measure against. The links sit in its pale upper band, whose tone
		 * is the header's neutral-200; the ratio is asserted against that.
		 */
		const ground = await presetColor( page, 'neutral-200' );
		expect( contrastRatio( expected, ground ) ).toBeGreaterThanOrEqual( 4.5 );

		const links = page
			.locator( 'footer.wp-block-template-part .is-style-site-footer a[href]' )
			.filter( { hasNot: page.locator( 'img' ) } )
			.filter( { hasText: /\S/ } );

		const textLinks = [];
		for ( const link of await links.all() ) {
			const inSocial = await link.evaluate(
				( node ) => !! node.closest( '.wp-block-social-links' )
			);
			if ( ! inSocial && ( await link.isVisible() ) ) {
				textLinks.push( link );
			}
		}

		expect( textLinks.length, 'no text links found in the footer widgets' ).toBeGreaterThan( 0 );

		for ( const link of textLinks ) {
			await link.hover();
			await expect( link ).toHaveCSS( 'color', expected );
		}
	} );

	/**
	 * The "Follow Us" labels are pinned by the section style's `css` field, so
	 * their hover is a separate rule in assets/styles/core-group.css and needs
	 * its own check.
	 */
	test( 'social labels hover to brand-700', async ( { page } ) => {
		const expected = await presetColor( page, 'brand-700' );
		const social = page.locator(
			'footer.wp-block-template-part .is-style-site-footer .wp-social-link a'
		);

		test.skip( 0 === ( await social.count() ), 'no labelled social links in the footer' );

		for ( const link of await social.all() ) {
			const label = link.locator( '.wp-block-social-link-label' );
			if ( ! ( await label.isVisible() ) ) {
				continue;
			}
			await link.hover();
			await expect( label ).toHaveCSS( 'color', expected );
		}
	} );

	test( 'colophon links hover to brand-400, AA on the colophon ground', async ( {
		page,
	} ) => {
		const expected = await presetColor( page, 'brand-400' );
		const colophon = page
			.locator( 'footer.wp-block-template-part .is-style-footer-colophon' )
			.first();

		await expect( colophon ).toBeVisible();

		const ground = await colophon.evaluate( ( node ) => getComputedStyle( node ).backgroundColor );
		const links = colophon.locator( 'a[href]' ).filter( { hasText: /\S/ } );

		await expect( links ).not.toHaveCount( 0 );

		for ( const link of await links.all() ) {
			await link.hover();
			await expect( link ).toHaveCSS( 'color', expected );
			const ratio = contrastRatio(
				await link.evaluate( ( node ) => getComputedStyle( node ).color ),
				ground
			);
			expect( ratio, `hover contrast ${ ratio.toFixed( 2 ) }:1` ).toBeGreaterThanOrEqual( 4.5 );
		}
	} );
} );
