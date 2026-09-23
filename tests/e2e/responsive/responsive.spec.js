/**
 * The responsive contract.
 *
 * Scope: AGENTS.md excludes bespoke mobile and tablet designs from the estimate,
 * so nothing here compares a breakpoint against a separate comp. These are the
 * adaptation failures — a layout that overflows, a control too small to hit, a
 * page that collapses when the reader turns their browser font up.
 *
 * Breakpoints are the org standard's
 * (`agents/testing-agent/CROSS_BROWSER_AND_RESPONSIVE_TESTING.md`): mobile
 * 375×667, tablet 768×1024, desktop 1280×800, wide 1920×1080.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );

const BREAKPOINTS = [
	{ name: 'mobile', width: 375, height: 667 },
	{ name: 'tablet', width: 768, height: 1024 },
	{ name: 'desktop', width: 1280, height: 800 },
	{ name: 'wide', width: 1920, height: 1080 },
];

/**
 * The pages worth checking at every width. Deliberately a sample rather than
 * all thirteen static routes — four breakpoints across thirteen pages is
 * fifty-two navigations for a check that fails in clusters, not individually.
 */
const SAMPLE = [
	{ name: 'front page', path: '/' },
	{ name: 'tour archive', path: '/tours/' },
	{ name: 'accommodation archive', path: '/accommodation/' },
	{ name: 'contact', path: '/contact/' },
];

/**
 * The minimum touch target: 44×44, the org standard's figure. That is WCAG
 * 2.5.5 (Target Size, Enhanced — AAA). The AA criterion, 2.5.8 Target Size
 * (Minimum), asks only for 24×24, so a control failing here can still pass AA.
 */
const MIN_TOUCH_TARGET = 44;

/**
 * @param {import('@playwright/test').Page} page Page under test.
 * @return {Promise<{scrollWidth: number, clientWidth: number}>} Document widths.
 */
async function documentWidths( page ) {
	return page.evaluate( () => ( {
		scrollWidth: document.documentElement.scrollWidth,
		clientWidth: document.documentElement.clientWidth,
	} ) );
}

test.describe( 'Responsive layout', () => {
	for ( const breakpoint of BREAKPOINTS ) {
		test.describe( `${ breakpoint.name } (${ breakpoint.width }px)`, () => {
			test.use( {
				viewport: { width: breakpoint.width, height: breakpoint.height },
			} );

			for ( const route of SAMPLE ) {
				test( `${ route.name } does not scroll horizontally`, async ( {
					page,
					visit,
				} ) => {
					await visit( route.path );

					const { scrollWidth, clientWidth } = await documentWidths(
						page
					);

					/**
					 * One pixel of slack. Sub-pixel layout rounding produces a
					 * 1px overflow on perfectly good pages, and failing on it
					 * teaches people to ignore the check.
					 */
					expect(
						scrollWidth,
						`${ route.path } overflows its viewport at ${ breakpoint.width }px ` +
							`(document is ${ scrollWidth }px wide against a ${ clientWidth }px viewport). ` +
							'Usually a fixed width, a long unbroken string, or a negative margin.'
					).toBeLessThanOrEqual( clientWidth + 1 );
				} );
			}
		} );
	}
} );

test.describe( 'Touch targets', () => {
	test.use( { viewport: { width: 375, height: 667 } } );

	test( 'interactive controls in the header meet the minimum target size', async ( {
		page,
		visit,
	} ) => {
		await visit( '/' );

		const undersized = await page
			.locator(
				'header.wp-block-template-part a[href], header.wp-block-template-part button'
			)
			.evaluateAll( ( nodes, minimum ) => {
				return nodes
					.filter( ( node ) => {
						const rect = node.getBoundingClientRect();

						/**
						 * A zero-size box is hidden, not undersized — the
						 * collapsed search input and the closed mobile menu
						 * both report 0×0 until opened.
						 */
						if ( 0 === rect.width || 0 === rect.height ) {
							return false;
						}

						return (
							rect.width < minimum || rect.height < minimum
						);
					} )
					.map( ( node ) => {
						const rect = node.getBoundingClientRect();
						const label =
							node.getAttribute( 'aria-label' ) ||
							( node.textContent || '' ).trim().slice( 0, 40 ) ||
							node.outerHTML.slice( 0, 60 );

						return `${ label } — ${ Math.round(
							rect.width
						) }×${ Math.round( rect.height ) }`;
					} );
			}, MIN_TOUCH_TARGET );

		expect(
			undersized,
			`Controls below ${ MIN_TOUCH_TARGET }×${ MIN_TOUCH_TARGET }px at 375px wide ` +
				'(org standard; WCAG 2.5.5 Target Size, Enhanced):\n  ' +
				undersized.join( '\n  ' )
		).toEqual( [] );
	} );
} );

test.describe( 'Browser font scaling', () => {
	/**
	 * A reader who turns their browser font up to 150% is the commonest real
	 * accessibility need on a content site, and the commonest thing a
	 * pixel-perfect translation breaks. The check is deliberately weak — that
	 * the page still does not overflow — because anything stricter fails on
	 * design decisions that are not ours to make.
	 */
	for ( const scale of [ 120, 150 ] ) {
		test( `the front page survives ${ scale }% text scaling`, async ( {
			page,
			visit,
		} ) => {
			await page.setViewportSize( { width: 375, height: 667 } );
			await visit( '/' );

			await page.evaluate( ( percentage ) => {
				document.documentElement.style.fontSize = `${
					( 16 * percentage ) / 100
				}px`;
			}, scale );

			/**
			 * The assertion is about the settled layout, not the frame
			 * mid-reflow: wait for webfonts, then poll the measurement.
			 */
			await page.evaluate( () => document.fonts.ready );

			await expect
				.poll(
					async () => {
						const { scrollWidth, clientWidth } = await documentWidths( page );

						return scrollWidth - clientWidth;
					},
					{ message: `the front page overflows at ${ scale }% text size` }
				)
				.toBeLessThanOrEqual( 1 );
		} );
	}
} );
