/**
 * Static pages — About Us, its three children, Contact Us and Thank You,
 * finalised 2026-09-23 and revised 2026-09-24.
 *
 * LS-2015 (Page Conversion). All six pages sit on `page-no-title.html` →
 * patterns/template-page-full.php, and that template now owns their banner:
 * `patterns/hero-page-banner.php` (featured image, the page title as the h1,
 * `banner_subtitle` through `sd/banner` as the strapline), the breadcrumb strip
 * under it, then the page content and the "Why choose" band. The page content
 * holds no banner of its own — five hand-made copies of an older device lived
 * there until this pass.
 *
 * Deployment still has to remove those legacy cover blocks from the stored page
 * bodies on environments that already contain them. The banner checks below are
 * the verification step: they stay red until the cleanup has happened.
 *
 * Measured against live on 2026-09-23. Live's About children show "About Us"
 * over the page name; here the page name is the h1 and "About Us" is its
 * strapline, the same reading a destination single gives its parent. Contact's
 * line is "Get in Touch". Live's About Us page is a banner and nothing else,
 * with no tagline.
 *
 * **The standfirst** on each page is `is-style-archive-intro` and nothing more:
 * no core `dropCap`, no `<em>`, no font size. The drop cap is the theme's, from
 * 900px up only, on the first paragraph of a standfirst only
 * (assets/styles/core-paragraph.css). Core's `.has-drop-cap` outranks that rule
 * and caps at every width, so its presence anywhere fails the page.
 *
 * **Trustpilot**, as decided 2026-09-24. On Why Book With Us, the Our Reviews
 * badge is the "Why choose" band's stacked badge (logo, stars, then the
 * TrustScore line), but in dark type with the dark logo, because it sits on a
 * light card. On Contact the band is the homepage's TrustBox carousel, not the
 * team single's stacked badge and reviews grid it carried before. Both pages
 * used to carry a pasted, stale copy of the badge; the class checks below are
 * what tell the versions apart.
 *
 * **Thank You** is checked as Zared authored it on 2026-09-24. Its intro is a
 * plain `h2` and paragraph, not a standfirst, so it sits outside the standfirst
 * checks. It does share the banner contract, which means its in-content banner
 * is covered by the same cleanup. Its "Send Us an Email" eyebrow becomes the
 * page's `banner_subtitle`.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );
const { describeHeroBanners, resolvePreset } = require( '../utils/hero-banner.js' );

/**
 * A literal path, prefixed the way this environment's permalinks need.
 *
 * Local Studio runs PATH_INFO permalinks (`/index.php/…`); the prefix is read
 * off a permalink global setup already resolved.
 *
 * @param {Object} routes The `routes` fixture.
 * @param {string} path   Path with a leading slash.
 * @return {string} Path for this environment.
 */
function envPath( routes, path ) {
	const sample = routes.post( 'tour' ) || routes.post( 'accommodation' ) || '';
	return `${ sample.startsWith( '/index.php/' ) ? '/index.php' : '' }${ path }`;
}

const PAGES = [
	{ name: 'About Us', path: '/about-us/', title: 'About Us', strapline: null, intro: 0 },
	{ name: 'Why Book With Us', path: '/about-us/why-book-with-us/', title: 'Why Book With Us', strapline: 'About Us', intro: 1 },
	{ name: 'Social Responsibility', path: '/about-us/social-responsibility/', title: 'Social Responsibility', strapline: 'About Us', intro: 2 },
	{ name: 'Connect With Us', path: '/about-us/connect-with-us/', title: 'Connect With Us', strapline: 'About Us', intro: 1 },
	{ name: 'Contact Us', path: '/contact/', title: 'Contact Us', strapline: 'Get in Touch', intro: 1 },
	{ name: 'Thank You', path: '/thank-you/', title: 'Thank You', strapline: 'Send Us an Email', intro: 0 },
];

/**
 * Assert a row of phone numbers, each a `tel:` link with the phone icon
 * beside it — the office markup of patterns/cta-tell-us-your-trip-ideas.php.
 *
 * @param {import('@playwright/test').Locator} scope  Section holding the numbers.
 * @param {number}                             count  Numbers expected.
 * @param {Function}                           expect Playwright `expect`.
 */
async function expectPhoneRow( scope, count, expect ) {
	const links = scope.locator( 'a[href^="tel:"]' );
	await expect( links ).toHaveCount( count );

	for ( const link of await links.all() ) {
		// The icon is the number's sibling inside the office's nowrap row.
		const row = link.locator( 'xpath=ancestor::div[contains(@class,"wp-block-group")][1]' );
		await expect( row.locator( '.wp-block-outermost-icon-block svg' ), 'a number has lost its phone icon' ).toHaveCount( 1 );
	}
}

/**
 * Relative luminance of a computed `rgb()` colour, 0 (black) to 1 (white).
 *
 * @param {string} rgb Computed colour, e.g. `rgb(29, 23, 17)`.
 * @return {number} Relative luminance.
 */
function luminance( rgb ) {
	const [ r, g, b ] = rgb.match( /\d+(\.\d+)?/g ).slice( 0, 3 ).map( ( v ) => {
		const c = Number( v ) / 255;
		return c <= 0.03928 ? c / 12.92 : ( ( c + 0.055 ) / 1.055 ) ** 2.4;
	} );
	return 0.2126 * r + 0.7152 * g + 0.0722 * b;
}

const BANNER = 'main > .wp-block-cover.is-style-hero-banner';
const CONTENT = 'main .wp-block-post-content';
const INTRO = `${ CONTENT } p.is-style-archive-intro`;

/**
 * Read the computed `::first-letter` of an element.
 *
 * @param {import('@playwright/test').Locator} locator Paragraph.
 * @return {Promise<Object>} `{ float, fontSize, parentFontSize, color }`.
 */
function firstLetter( locator ) {
	return locator.evaluate( ( el ) => {
		const cap = getComputedStyle( el, '::first-letter' );
		return {
			float: cap.float,
			fontSize: parseFloat( cap.fontSize ),
			parentFontSize: parseFloat( getComputedStyle( el ).fontSize ),
			color: cap.color,
		};
	} );
}

describeHeroBanners(
	{ test, expect },
	PAGES.map( ( p ) => ( {
		name: p.name,
		path: ( routes ) => envPath( routes, p.path ),
		strapline: !! p.strapline,
	} ) )
);

test.describe( 'Static page banners', () => {
	for ( const p of PAGES ) {
		test( `${ p.name } is titled in the banner, with ${ p.strapline ? `"${ p.strapline }" under it` : 'no strapline' }`, async ( {
			page,
			visit,
			routes,
		} ) => {
			await page.setViewportSize( { width: 1280, height: 800 } );
			await visit( envPath( routes, p.path ) );

			const banner = page.locator( BANNER );
			await expect( banner ).toHaveCount( 1 );
			await expect( banner.locator( 'h1' ) ).toHaveText( p.title );

			const strapline = banner.locator( 'p.is-style-subheading-large' );

			if ( p.strapline ) {
				await expect( strapline ).toHaveText( p.strapline );
				await expect( strapline ).toBeVisible();
			} else {
				// Authored empty and hidden by `p:empty` — no placeholder line, no gap.
				for ( const el of await strapline.all() ) {
					await expect( el ).toBeHidden();
				}
			}
		} );

		test( `${ p.name } holds no banner of its own and sits the trail under the template's`, async ( {
			page,
			visit,
			routes,
		} ) => {
			await visit( envPath( routes, p.path ) );

			await expect(
				page.locator( `${ CONTENT } .wp-block-cover.is-style-hero-banner` ),
				'the page content still carries its own banner'
			).toHaveCount( 0 );

			// The old content banners painted a literal #636c75 overlay.
			await expect( page.locator( `${ CONTENT } [style*="636c75" i]` ) ).toHaveCount( 0 );

			const next = page.locator( `${ BANNER } + *` );
			await expect( next, 'the breadcrumb strip is not directly under the banner' ).toHaveClass(
				/has-primary-100-background-color/
			);
		} );

		test( `${ p.name } closes on the one "Why choose" band`, async ( { page, visit, routes } ) => {
			await visit( envPath( routes, p.path ) );

			await expect(
				page.locator( 'main h2', { hasText: /^Why choose Southern Destinations$/i } )
			).toHaveCount( 1 );
		} );
	}
} );

test.describe( 'Static page standfirsts', () => {
	for ( const p of PAGES.filter( ( x ) => x.intro ) ) {
		test( `${ p.name } opens on the archive intro, not core's drop cap`, async ( {
			page,
			visit,
			routes,
		} ) => {
			await visit( envPath( routes, p.path ) );

			await expect( page.locator( INTRO ) ).toHaveCount( p.intro );
			await expect( page.locator( `${ CONTENT } .has-drop-cap` ), 'core\'s drop cap is still set' ).toHaveCount( 0 );
			await expect( page.locator( INTRO ).first() ).toHaveCSS( 'font-style', 'italic' );
		} );

		test( `${ p.name } caps its standfirst once, from 900px up @responsive`, async ( {
			page,
			visit,
			routes,
		} ) => {
			await page.setViewportSize( { width: 1280, height: 800 } );
			await visit( envPath( routes, p.path ) );

			const intros = page.locator( INTRO );
			const cap = await firstLetter( intros.first() );
			const brand500 = await resolvePreset( page, 'color', '--wp--preset--color--brand-500' );

			expect( cap.float, 'the standfirst has no drop cap on desktop' ).toBe( 'left' );
			expect( cap.fontSize / cap.parentFontSize, 'the cap is not 3.2em' ).toBeCloseTo( 3.2, 1 );
			expect( cap.color ).toBe( brand500 );

			// A second paragraph of the same standfirst carries no cap of its own.
			if ( 1 < p.intro ) {
				expect( ( await firstLetter( intros.nth( 1 ) ) ).float ).toBe( 'none' );
			}

			await page.setViewportSize( { width: 375, height: 667 } );
			expect( ( await firstLetter( intros.first() ) ).float, 'the cap shows below 900px' ).toBe( 'none' );
		} );
	}
} );

test.describe( 'Why Book With Us', () => {
	const path = '/about-us/why-book-with-us/';

	test( 'sets Our Reviews on the "Why choose" badge, in dark type', async ( {
		page,
		visit,
		routes,
	} ) => {
		await visit( envPath( routes, path ) );

		const badge = page.locator( `${ CONTENT } .sd-trustpilot` );
		await expect( badge ).toHaveCount( 1 );

		// patterns/why-choose-sd.php's stack: logo, stars, then the TrustScore line.
		await expect( badge ).not.toHaveClass( /sd-trustpilot--stacked/ );
		await expect( badge.locator( '.sd-trustpilot__wording' ), 'the stale copy led with the rating word' ).toHaveCount( 0 );
		await expect( badge.locator( '.sd-trustpilot__line .sd-trustpilot__score' ) ).toHaveText( /^TrustScore / );
		await expect( badge.locator( '.sd-trustpilot__line .sd-trustpilot__count' ) ).toHaveText( / reviews$/ );

		// Dark on a light card: the dark logo, not the band's white-green one.
		const logo = badge.locator( '.sd-trustpilot__logo img' );
		await expect( logo ).toHaveAttribute( 'src', /trustpilot-logo\.svg$/ );

		for ( const figure of [ '.sd-trustpilot__score', '.sd-trustpilot__count' ] ) {
			const color = await badge.locator( figure ).evaluate( ( el ) => getComputedStyle( el ).color );
			expect( luminance( color ), `${ figure } is set light, as on the dark band` ).toBeLessThan( 0.2 );
		}
	} );

	test( 'closes on the four office numbers, each with the phone icon', async ( {
		page,
		visit,
		routes,
	} ) => {
		await visit( envPath( routes, path ) );

		const offices = page.locator( `${ CONTENT } .wp-block-group`, {
			has: page.locator( 'a[href="mailto:info@southerndestinations.com"]' ),
		} ).last();

		await expectPhoneRow( offices, 4, expect );
	} );

	test( 'carries the Read Our Reviews link', async ( { page, visit, routes } ) => {
		await visit( envPath( routes, path ) );

		const link = page.locator( `${ CONTENT } a`, { hasText: /^Read Our Reviews$/ } );
		await expect( link ).toHaveAttribute( 'href', /trustpilot\.com\/review\/southerndestinations\.com/ );
		await expect( link ).toHaveAttribute( 'target', '_blank' );
	} );

	test( 'runs the brand-logo carousel in the lodge-operator band', async ( { page, visit, routes } ) => {
		await visit( envPath( routes, path ) );

		const carousel = page.locator( `${ CONTENT } .wp-block-terms-query.is-style-slider-frame` );
		await expect( carousel ).toHaveCount( 1 );
		expect(
			await carousel.locator( '.wp-block-post-featured-image, img' ).count(),
			'fewer than five brand logos — live shows five at a time'
		).toBeGreaterThanOrEqual( 5 );
	} );
} );

test.describe( 'Connect With Us', () => {
	test( 'lists up to six posts on the current post-grid card', async ( { page, visit, routes } ) => {
		await visit( envPath( routes, '/about-us/connect-with-us/' ) );

		const cards = page.locator( `${ CONTENT } .wp-block-query .is-style-post-grid-card` );
		const count = await cards.count();
		test.skip( 0 === count, 'No published posts in this environment' );

		expect( count ).toBeLessThanOrEqual( 6 );

		// The stale copy was cropped 3:2 and carried its own inline radius.
		const image = cards.first().locator( '.wp-block-post-featured-image img' );
		if ( await image.count() ) {
			await expect( image ).toHaveAttribute( 'style', /aspect-ratio:\s*16\s*\/\s*9/ );
		}
		expect( ( await cards.first().getAttribute( 'style' ) ) || '' ).not.toMatch( /border-top-left-radius/ );
	} );
} );

test.describe( 'Contact Us', () => {
	const path = '/contact/';

	test( 'runs the homepage TrustBox carousel, not a badge and reviews grid', async ( { page, visit, routes } ) => {
		await visit( envPath( routes, path ) );

		// patterns/homepage-sd-difference.php's widget, same template and business unit.
		const widget = page.locator( `${ CONTENT } .trustpilot-widget` );
		await expect( widget ).toHaveCount( 1 );
		await expect( widget ).toHaveAttribute( 'data-template-id', '53aa8912dec7e10d38f59f36' );
		await expect( widget ).toHaveAttribute( 'data-businessunit-id', '564399480000ff0005856b81' );

		// The fallback link is what shows with sd-enhancements off or JavaScript disabled.
		await expect( widget.locator( 'a[href*="trustpilot.com/review/southerndestinations.com"]' ) ).toHaveCount( 1 );

		await expect( page.locator( `${ CONTENT } .sd-trustpilot` ), 'the old score badge is still on the page' ).toHaveCount( 0 );
		await expect( page.locator( `${ CONTENT } .sd-trustpilot-review` ), 'the old reviews grid is still on the page' ).toHaveCount( 0 );
	} );

	test( 'loads the TrustBox script for its widget', async ( { page, visit, routes } ) => {
		await visit( envPath( routes, path ) );

		// sd-enhancements enqueues the bootstrap only on a render that holds a widget.
		await expect( page.locator( 'script[src*="widget.trustpilot.com"]' ) ).not.toHaveCount( 0 );
	} );

	test( 'sets the safari gurus four across, as the homepage does @responsive', async ( { page, visit, routes } ) => {
		await page.setViewportSize( { width: 1280, height: 800 } );
		await visit( envPath( routes, path ) );

		const grid = page.locator( `${ CONTENT } .sd-safari-gurus-query` );
		await expect( grid ).toHaveCount( 1 );

		const cards = grid.locator( '> li' );
		test.skip( 0 === ( await cards.count() ), 'No team members in this environment' );
		expect( await cards.count() ).toBeLessThanOrEqual( 4 );

		const columns = () =>
			grid.evaluate( ( el ) => getComputedStyle( el ).gridTemplateColumns.split( ' ' ).length );

		expect( await columns(), 'the gurus grid is not four across on desktop' ).toBe( 4 );

		// Four across would crush a 375px card; the grid falls to one column.
		await page.setViewportSize( { width: 375, height: 667 } );
		expect( await columns(), 'the gurus grid does not stack on a phone' ).toBe( 1 );
	} );
} );

test.describe( 'Thank You', () => {
	const path = '/thank-you/';

	test( 'thanks the enquirer and links the social profiles', async ( { page, visit, routes } ) => {
		await visit( envPath( routes, path ) );

		await expect(
			page.locator( `${ CONTENT } h2`, { hasText: /^Thank you for your travel enquiry$/ } )
		).toHaveCount( 1 );
		await expect(
			page.locator( `${ CONTENT } p`, { hasText: /^We will be in contact with you shortly\./ } )
		).toHaveCount( 1 );

		// As authored: a plain intro, not a standfirst, and no core drop cap either.
		await expect( page.locator( `${ CONTENT } .has-drop-cap` ) ).toHaveCount( 0 );

		const social = page.locator( `${ CONTENT } .wp-block-social-links .wp-social-link a` );
		await expect( social ).toHaveCount( 6 );
		for ( const link of await social.all() ) {
			await expect( link ).toHaveAttribute( 'href', /^https:\/\// );
		}
	} );

	test( 'offers the safari gurus\' numbers, each with the phone icon', async ( { page, visit, routes } ) => {
		await visit( envPath( routes, path ) );

		const heading = page.locator( `${ CONTENT } h2`, { hasText: /^Want to chat to one of our safari gurus\?$/ } );
		await expect( heading ).toHaveCount( 1 );

		await expectPhoneRow( heading.locator( 'xpath=..' ), 2, expect );
	} );
} );
