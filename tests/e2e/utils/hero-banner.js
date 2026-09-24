/**
 * The hero banner contract, as a reusable suite.
 *
 * Every inner-page banner in the theme is `patterns/hero-page-banner.php`
 * configured per template: one `<h1>`, the 360px floor (since 2026-09-23), no
 * inline padding on the cover, the photograph filling the band from 768px up,
 * and below that the phone stack — a 3:1 strip over the neutral-200 plate with
 * the title in brand-500 (style.css, "Hero banner — the phone stack").
 *
 * tours.spec.js and destinations.spec.js carry the same assertions written out
 * by hand; this is the same contract, so a template spec can declare its routes
 * and inherit all of it. Colours are compared against the resolved preset,
 * never a literal.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const BANNER = 'main > .wp-block-cover.is-style-hero-banner';
const BANNER_IMAGE = `${ BANNER } > img.wp-block-cover__image-background`;

/**
 * Resolve a preset custom property to the computed value the browser uses,
 * by applying it to a throwaway element.
 *
 * @param {import('@playwright/test').Page} page     Page.
 * @param {string}                          property CSS property, e.g. `color`.
 * @param {string}                          variable Custom property name.
 * @return {Promise<string>} Computed value, e.g. `rgb(188, 91, 24)`.
 */
async function resolvePreset( page, property, variable ) {
	return page.evaluate(
		( [ prop, name ] ) => {
			const probe = document.createElement( 'div' );
			probe.style.setProperty( prop, `var(${ name })` );
			document.body.append( probe );
			const value = getComputedStyle( probe ).getPropertyValue( prop );
			probe.remove();
			return value;
		},
		[ property, variable ]
	);
}

/**
 * Declare the hero banner tests for a set of routes.
 *
 * @param {Object}   deps        `{ test, expect }` from fixtures/base.js.
 * @param {Object[]} routeConfig `{ name, path( routes ), strapline }` entries.
 */
function describeHeroBanners( { test, expect }, routeConfig ) {
	for ( const route of routeConfig ) {
		test.describe( `${ route.name } banner`, () => {
			test( 'is the hero banner: one h1, the 360px floor, no inline padding @responsive', async ( {
				page,
				visit,
				routes,
			} ) => {
				const target = route.path( routes );
				test.skip( ! target, `No ${ route.name } on ${ routes.baseURL }` );

				await page.setViewportSize( { width: 1280, height: 800 } );
				await visit( target );

				const banner = page.locator( BANNER ).first();
				await expect( banner, 'no hero banner at the top of <main>' ).toBeVisible();

				const isFirst = await banner.evaluate(
					( el ) => el === el.parentElement.firstElementChild
				);
				expect( isFirst, 'the banner is not the first child of <main>' ).toBe( true );

				await expect( banner.locator( 'h1' ) ).toHaveCount( 1 );
				await expect( page.locator( 'h1' ) ).toHaveCount( 1 );

				/**
				 * The upper bound catches a banner left on the old 400 or
				 * 454px floor — the type is short enough never to push the
				 * band past it.
				 */
				const height = ( await banner.boundingBox() ).height;
				expect( height, 'the banner is below its 360px floor' ).toBeGreaterThanOrEqual( 359 );
				expect( height, 'the banner is still on the old 400px floor' ).toBeLessThan( 399 );

				const inlinePadding = await banner.evaluate( ( el ) => el.style.paddingTop );
				expect( inlinePadding, 'the cover still writes its own padding inline' ).toBe( '' );
			} );

			test( 'fills the band with the photograph on desktop @responsive', async ( {
				page,
				visit,
				routes,
			} ) => {
				const target = route.path( routes );
				test.skip( ! target, `No ${ route.name } on ${ routes.baseURL }` );

				await page.setViewportSize( { width: 1280, height: 800 } );
				await visit( target );

				const image = page.locator( BANNER_IMAGE ).first();
				test.skip( 0 === ( await image.count() ), `${ target } has no banner photograph` );

				await expect( image ).toHaveCSS( 'position', 'absolute' );

				const [ bannerBox, imageBox ] = await Promise.all( [
					page.locator( BANNER ).first().boundingBox(),
					image.boundingBox(),
				] );

				expect( Math.abs( imageBox.height - bannerBox.height ) ).toBeLessThanOrEqual( 1 );
			} );

			test( 'stacks the title on the plate under a 3:1 strip on phones @responsive', async ( {
				page,
				visit,
				routes,
			} ) => {
				const target = route.path( routes );
				test.skip( ! target, `No ${ route.name } on ${ routes.baseURL }` );

				await page.setViewportSize( { width: 375, height: 667 } );
				await visit( target );

				const banner = page.locator( BANNER ).first();
				await expect( banner ).toBeVisible();

				const [ plate, brand500, neutral700 ] = await Promise.all( [
					resolvePreset( page, 'background-color', '--wp--preset--color--neutral-200' ),
					resolvePreset( page, 'color', '--wp--preset--color--brand-500' ),
					resolvePreset( page, 'color', '--wp--preset--color--neutral-700' ),
				] );

				await expect( banner ).toHaveCSS( 'background-color', plate );
				await expect( banner ).toHaveCSS( 'padding-top', '0px' );
				await expect( banner.locator( 'h1' ) ).toHaveCSS( 'color', brand500 );

				if ( route.strapline ) {
					const strapline = banner.locator( '.wp-block-cover__inner-container p' ).first();

					if ( await strapline.count() ) {
						await expect( strapline ).toHaveCSS( 'color', neutral700 );
					}
				}

				const image = banner.locator( ':scope > img.wp-block-cover__image-background' );

				if ( await image.count() ) {
					await expect( image ).toHaveCSS( 'position', 'relative' );

					const box = await image.boundingBox();
					expect(
						Math.abs( box.width / box.height - 3 ),
						`the banner strip is ${ box.width }x${ box.height }, not 3:1`
					).toBeLessThan( 0.05 );

					const title = await banner.locator( 'h1' ).boundingBox();
					expect(
						title.y,
						'the title overlaps the photograph instead of sitting under it'
					).toBeGreaterThanOrEqual( box.y + box.height - 1 );
				}
			} );
		} );
	}
}

module.exports = { BANNER, describeHeroBanners, resolvePreset };
