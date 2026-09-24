/**
 * Static pages — About Us, its three children, and Contact Us, finalised
 * 2026-09-23.
 *
 * LS-2015 (Page Conversion). All five pages sit on `page-no-title.html` →
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
 * **Trustpilot** is the current `patterns/trustpilot-score.php` on Why Book
 * With Us, and on Contact the team single's band — the stacked badge beside
 * the review carousel. Both pages used to carry a pasted, stale copy of the
 * badge; the class checks below are what tell the two apart.
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
];

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

	test( 'carries the current Trustpilot badge and the Read Our Reviews link', async ( {
		page,
		visit,
		routes,
	} ) => {
		await visit( envPath( routes, path ) );

		const badge = page.locator( `${ CONTENT } .sd-trustpilot` );
		await expect( badge ).toHaveCount( 1 );

		// The stale copy set the band word at 100 and left the figures unpinned.
		await expect( badge.locator( '.sd-trustpilot__wording' ) ).toHaveClass( /has-200-font-size/ );
		await expect( badge.locator( '.sd-trustpilot__score' ) ).toHaveClass( /has-neutral-900-color/ );
		await expect( badge.locator( '.sd-trustpilot__count' ) ).toHaveClass( /has-neutral-900-color/ );

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
	test( 'sets the stacked Trustpilot badge beside the review carousel', async ( { page, visit, routes } ) => {
		await visit( envPath( routes, '/contact/' ) );

		await expect( page.locator( `${ CONTENT } .sd-trustpilot` ) ).toHaveCount( 1 );
		await expect( page.locator( `${ CONTENT } .sd-trustpilot.sd-trustpilot--stacked` ) ).toHaveCount( 1 );

		const slider = page.locator( `${ CONTENT } .sd-review-slider .wp-block-sd-trustpilot-reviews` );
		await expect( slider ).toHaveCount( 1 );

		const reviews = slider.locator( '.sd-trustpilot-review' );
		test.skip( 0 === ( await reviews.count() ), 'No Trustpilot reviews cached in this environment' );
		expect( await reviews.count() ).toBeLessThanOrEqual( 3 );
	} );
} );
