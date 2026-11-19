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
		expect( contrastRatio( expected, ground ) ).toBeGreaterThanOrEqual(
			4.5
		);

		const links = page
			.locator(
				'footer.wp-block-template-part .is-style-site-footer a[href]'
			)
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

		expect(
			textLinks.length,
			'no text links found in the footer widgets'
		).toBeGreaterThan( 0 );

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

		test.skip(
			0 === ( await social.count() ),
			'no labelled social links in the footer'
		);

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
			.locator(
				'footer.wp-block-template-part .is-style-footer-colophon'
			)
			.first();

		await expect( colophon ).toBeVisible();

		const ground = await colophon.evaluate(
			( node ) => getComputedStyle( node ).backgroundColor
		);
		const links = colophon.locator( 'a[href]' ).filter( { hasText: /\S/ } );

		await expect( links ).not.toHaveCount( 0 );

		for ( const link of await links.all() ) {
			await link.hover();
			await expect( link ).toHaveCSS( 'color', expected );
			const ratio = contrastRatio(
				await link.evaluate(
					( node ) => getComputedStyle( node ).color
				),
				ground
			);
			expect(
				ratio,
				`hover contrast ${ ratio.toFixed( 2 ) }:1`
			).toBeGreaterThanOrEqual( 4.5 );
		}
	} );
} );

/**
 * ASD-41 item 21.3 — the 2019 footer: four widgets (Logo, Contact Us, Follow
 * Us, Instagram linked image) and links to Privacy and Terms (G13, G14).
 */
test.describe( 'Footer widgets and legal links (2019 spec)', () => {
	test.beforeEach( async ( { page, visit } ) => {
		await page.setViewportSize( { width: 1280, height: 800 } );
		await visit( '/' );
	} );

	const FOOTER = 'footer.wp-block-template-part';

	// requirement: RQ-G13 / test-case: TC-G13-01
	test( 'has four widgets: logo, Contact Us, Follow Us and Instagram', async ( {
		page,
	} ) => {
		const footer = page.locator( FOOTER );

		await expect(
			footer.locator( 'img[src*="footer-logo"]' ),
			'no logo widget'
		).toHaveAttribute( 'alt', /Southern Destinations/ );
		await expect(
			footer
				.locator( '.wp-block-columns' )
				.first()
				.locator( '> .wp-block-column' ),
			'the widget row is not four columns'
		).toHaveCount( 4 );

		for ( const title of [ 'Contact Us', 'Follow Us', 'Instagram' ] ) {
			await expect(
				footer.getByRole( 'heading', { name: title, exact: true } ),
				`no "${ title }" widget`
			).toBeVisible();
		}
	} );

	// requirement: RQ-G13 / test-case: TC-G13-02
	test( 'Contact Us carries the phone numbers, address and email as links', async ( {
		page,
	} ) => {
		const widget = page
			.locator( FOOTER )
			.getByRole( 'heading', { name: 'Contact Us', exact: true } )
			.locator( 'xpath=..' );

		await expect(
			widget.locator( 'a[href^="tel:"]' ).first()
		).toBeVisible();
		await expect( widget.locator( 'a[href^="mailto:"]' ) ).toHaveCount( 1 );
		await expect( widget.locator( 'a[href*="maps"]' ) ).toHaveCount( 1 );
	} );

	// requirement: RQ-G13 / test-case: TC-G13-03
	test( 'Follow Us links the five social profiles', async ( { page } ) => {
		const widget = page
			.locator( FOOTER )
			.getByRole( 'heading', { name: 'Follow Us', exact: true } )
			.locator( 'xpath=..' );

		for ( const [ name, host ] of [
			[ 'Facebook', 'facebook.com' ],
			[ 'Instagram', 'instagram.com' ],
			[ 'Pinterest', 'pinterest.com' ],
			[ 'LinkedIn', 'linkedin.com' ],
			[ 'YouTube', 'youtube.com' ],
		] ) {
			await expect(
				widget.getByRole( 'link', { name } ),
				`no ${ name } link`
			).toHaveAttribute(
				'href',
				new RegExp( host.replace( '.', '\\.' ) )
			);
		}
	} );

	// requirement: RQ-G13 / test-case: TC-G13-04
	test( 'the Instagram widget is a grid of described, linked images', async ( {
		page,
	} ) => {
		const widget = page
			.locator( FOOTER )
			.getByRole( 'heading', { name: 'Instagram', exact: true } )
			.locator( 'xpath=..' );
		const links = widget.locator( 'a[href*="instagram.com"]:has(img)' );

		expect(
			await links.count(),
			'fewer than four images'
		).toBeGreaterThanOrEqual( 4 );
		for ( const alt of await links
			.locator( 'img' )
			.evaluateAll( ( imgs ) => imgs.map( ( i ) => i.alt ) ) ) {
			expect( alt.trim(), 'an Instagram image has no alt text' ).not.toBe(
				''
			);
		}
	} );

	// requirement: RQ-G14 / test-case: TC-G14-01
	test( 'links Privacy Policy and Terms & Conditions, and both pages load', async ( {
		page,
		request,
	} ) => {
		const footer = page.locator( FOOTER );

		for ( const [ name, path ] of [
			[ 'Privacy Policy', /\/privacy-policy\/?$/ ],
			[ 'Terms & Conditions', /\/terms-conditions\/?$/ ],
		] ) {
			const link = footer.getByRole( 'link', { name } );
			await expect( link, `no ${ name } link in the footer` ).toHaveCount(
				1
			);
			await expect( link ).toHaveAttribute( 'href', path );

			const status = (
				await request.get( await link.getAttribute( 'href' ) )
			).status();
			expect( status, `${ name } returns ${ status }` ).toBe( 200 );
		}
	} );
} );
