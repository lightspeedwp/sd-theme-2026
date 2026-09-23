/**
 * Visual baselines.
 *
 * Read this before running with --update-snapshots.
 *
 * Baselines are environment- and machine-specific. Dev and local render
 * different content, and macOS and Linux rasterise text differently, so a
 * baseline taken on one will not match the other. The snapshot path template in
 * playwright.config.js keys files by project and platform; CI should generate
 * and keep its own.
 *
 * Masked regions are listed with their reasons in utils/dynamic-regions.js. The
 * one that matters most: the hero picks one of eleven images per request in PHP,
 * so the front page legitimately looks different on every load.
 *
 * AGENTS.md rule 5 puts visual judgement with Zared, not the agent. This suite
 * is a change detector — it tells you a page moved, not whether the move was an
 * improvement.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test } = require( '../fixtures/base.js' );
const { STATIC_ROUTES } = require( '../fixtures/routes.js' );
const { masksFor } = require( '../utils/dynamic-regions.js' );

/**
 * Viewports the theme is expected to adapt to. Responsive adaptations only —
 * the estimate does not fund bespoke mobile or tablet designs.
 */
const VIEWPORTS = [
	{ name: 'desktop', width: 1440, height: 900 },
	{ name: 'tablet', width: 834, height: 1112 },
	{ name: 'mobile', width: 390, height: 844 },
];

/**
 * A screenshot taken mid-animation or mid-lazy-load is a flake generator.
 *
 * @param {import('@playwright/test').Page} page Page under test.
 */
async function settle( page ) {
	/**
	 * Scroll the full height once so lazy-loaded images resolve, then return
	 * to the top. Without this the first fold is sharp and everything below
	 * it is a placeholder.
	 */
	await page.evaluate( async () => {
		await new Promise( ( resolve ) => {
			let position = 0;
			const step = window.innerHeight;

			const timer = setInterval( () => {
				window.scrollTo( 0, position );
				position += step;

				if ( position >= document.body.scrollHeight ) {
					clearInterval( timer );
					window.scrollTo( 0, 0 );
					resolve();
				}
			}, 60 );
		} );
	} );

	await page.waitForLoadState( 'networkidle' ).catch( () => {
		/**
		 * Third-party widgets poll, so networkidle can legitimately never
		 * arrive. Carry on rather than failing the shot.
		 */
	} );

	/**
	 * Freeze animation and disable smooth scrolling, so the comparison is of
	 * layout rather than of timing.
	 */
	await page.addStyleTag( {
		content: `
			*, *::before, *::after {
				animation-duration: 0s !important;
				animation-delay: 0s !important;
				transition-duration: 0s !important;
				transition-delay: 0s !important;
				scroll-behavior: auto !important;
			}
		`,
	} );
}

/**
 * Templates worth a baseline. Deliberately not all thirty-one: a visual suite
 * costs maintenance per snapshot, and the value is in the distinct layouts.
 */
const VISUAL_ROUTES = STATIC_ROUTES.filter( ( route ) =>
	[
		'front-page.html',
		'archive-tour.html',
		'archive-accommodation.html',
		'archive-destination.html',
		'page-brands.html',
		'page.html',
	].includes( route.template )
);

for ( const viewport of VIEWPORTS ) {
	test.describe( `Visual — ${ viewport.name }`, () => {
		test.use( {
			viewport: { width: viewport.width, height: viewport.height },
		} );

		for ( const route of VISUAL_ROUTES ) {
			test( `${ route.name }`, async ( { page, visit } ) => {
				await visit( route.path );
				await settle( page );

				await test
					.expect( page )
					.toHaveScreenshot(
						`${ route.name.toLowerCase().replace( /[^a-z0-9]+/g, '-' ) }-${ viewport.name }.png`,
						{
							fullPage: true,
							mask: masksFor( page ),
						}
					);
			} );
		}
	} );
}

test.describe( 'Visual — single templates', () => {
	test.use( { viewport: { width: 1440, height: 900 } } );

	test( 'single tour', async ( { page, visit, routes } ) => {
		const target = routes.post( 'tour' );
		test.skip( ! target, 'No published tour' );

		await visit( target );
		await settle( page );

		await test.expect( page ).toHaveScreenshot( 'single-tour-desktop.png', {
			fullPage: true,
			mask: masksFor( page ),
		} );
	} );

	test( 'single accommodation', async ( { page, visit, routes } ) => {
		const target = routes.post( 'accommodation' );
		test.skip( ! target, 'No published accommodation' );

		await visit( target );
		await settle( page );

		await test
			.expect( page )
			.toHaveScreenshot( 'single-accommodation-desktop.png', {
				fullPage: true,
				mask: masksFor( page ),
			} );
	} );
} );
