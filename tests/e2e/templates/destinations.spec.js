/**
 * Destination singles — the banner and the listing cards.
 *
 * Covers the three destination templates through the routes global setup
 * resolves: `destination` (the newest, either kind), `country` (top level) and
 * `region` (has a parent). All three `require` the same section partials, so a
 * failure on one and not the others points at content, not markup.
 *
 * The banner is `patterns/destination-banner.php`, which since 2026-09-23 is
 * `patterns/hero-page-banner.php` configured for destinations: the photograph
 * fills the band from 768px up, and below it shrinks to a 3:1 strip over a
 * neutral-200 plate with the title in brand-500 (style.css, "Hero banner — the
 * phone stack"). That is live's behaviour, measured at 390px on
 * /destination/botswana/ and /destination/botswana/moremi-game-reserve/.
 *
 * The cards are the two listing card styles — `is-style-listing-card-compact`
 * (the accommodation, tour and destination shelves, and the three modal parts)
 * and `is-style-listing-card-list`. theme.json gives `core/paragraph` size 300
 * globally, which used to beat the card's inherited 200, so a card showed its
 * `core/post-terms` rows at 200 and its bound meta paragraphs at 300. Both
 * styles now pin body copy to 200, links to brand-600 with brand-700 on hover,
 * and keep the linked title neutral-700 as live does.
 *
 * Colours and sizes are compared against the resolved preset, never a literal,
 * so a token change does not turn this suite red.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );

const DESTINATION_ROUTES = [
	{ key: 'destination', name: 'single destination' },
	{ key: 'country', name: 'single country' },
	{ key: 'region', name: 'single region' },
];

const BANNER = 'main > .wp-block-cover.is-style-hero-banner';
const BANNER_IMAGE = `${ BANNER } > img.wp-block-cover__image-background`;
const CARD =
	'.is-style-listing-card-compact, .is-style-listing-card-list';

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

test.describe( 'Destination banner', () => {
	for ( const route of DESTINATION_ROUTES ) {
		test.describe( route.name, () => {
			test( 'is the hero banner: one h1, no strapline, 360px floor on desktop @responsive', async ( {
				page,
				visit,
				routes,
			} ) => {
				const target = routes.post( route.key );
				test.skip( ! target, `No published ${ route.key } on ${ routes.baseURL }` );

				await page.setViewportSize( { width: 1280, height: 800 } );
				await visit( target );

				const banner = page.locator( BANNER ).first();
				await expect( banner, 'no hero banner at the top of <main>' ).toBeVisible();

				/**
				 * The banner opens the page: nothing inside <main> comes before
				 * it, and the breadcrumb strip sits outside it, underneath.
				 */
				const isFirst = await banner.evaluate(
					( el ) => el === el.parentElement.firstElementChild
				);
				expect( isFirst, 'the banner is not the first child of <main>' ).toBe( true );

				await expect( banner.locator( 'h1' ) ).toHaveCount( 1 );
				await expect( page.locator( 'h1' ) ).toHaveCount( 1 );

				/**
				 * Live hides the tagline on every single except tours; the
				 * destination banner carries the title alone.
				 */
				await expect(
					banner.locator( '.wp-block-cover__inner-container p' ),
					'destination banners carry no strapline'
				).toHaveCount( 0 );

				const height = ( await banner.boundingBox() ).height;
				expect( height, 'the banner is below its 360px floor' ).toBeGreaterThanOrEqual( 359 );
				expect( height, 'the banner is still on the old 400px floor' ).toBeLessThan( 399 );
			} );

			test( 'fills the band with the photograph on desktop @responsive', async ( {
				page,
				visit,
				routes,
			} ) => {
				const target = routes.post( route.key );
				test.skip( ! target, `No published ${ route.key } on ${ routes.baseURL }` );

				await page.setViewportSize( { width: 1280, height: 800 } );
				await visit( target );

				const image = page.locator( BANNER_IMAGE ).first();
				test.skip(
					0 === ( await image.count() ),
					`${ target } has neither a banner image nor a featured image`
				);

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
				const target = routes.post( route.key );
				test.skip( ! target, `No published ${ route.key } on ${ routes.baseURL }` );

				await page.setViewportSize( { width: 375, height: 667 } );
				await visit( target );

				const banner = page.locator( BANNER ).first();
				await expect( banner ).toBeVisible();

				const [ plate, brand500 ] = await Promise.all( [
					resolvePreset( page, 'background-color', '--wp--preset--color--neutral-200' ),
					resolvePreset( page, 'color', '--wp--preset--color--brand-500' ),
				] );

				await expect( banner ).toHaveCSS( 'background-color', plate );
				await expect( banner.locator( 'h1' ) ).toHaveCSS( 'color', brand500 );

				/**
				 * The phone stack resets the section style's padding. An inline
				 * padding on the cover would outrank it and leave a band of
				 * plate above the photograph — the regression this guards.
				 */
				await expect( banner ).toHaveCSS( 'padding-top', '0px' );

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
} );

test.describe( 'Destination listing cards', () => {
	/**
	 * The shelves (and, on a tour, the modal parts Tour Operator prints into
	 * the page) are the listing cards' main placements. Every card in the
	 * document is checked, including those inside unopened dialogs — computed
	 * styles resolve on hidden elements.
	 */
	const CARD_ROUTES = [ ...DESTINATION_ROUTES, { key: 'tour', name: 'single tour' } ];

	for ( const route of CARD_ROUTES ) {
		test( `${ route.name }: card copy is font size 200 and links are brand-600`, async ( {
			page,
			visit,
			routes,
		} ) => {
			const target = routes.post( route.key );
			test.skip( ! target, `No published ${ route.key } on ${ routes.baseURL }` );

			await page.setViewportSize( { width: 1280, height: 800 } );
			await visit( target );

			const cardCount = await page.locator( CARD ).count();
			test.skip( 0 === cardCount, `No listing cards on ${ target }` );

			const [ size200, brand600, neutral700 ] = await Promise.all( [
				resolvePreset( page, 'font-size', '--wp--preset--font-size--200' ),
				resolvePreset( page, 'color', '--wp--preset--color--brand-600' ),
				resolvePreset( page, 'color', '--wp--preset--color--neutral-700' ),
			] );

			const report = await page.evaluate( ( selector ) => {
				const text = ( el ) => el.textContent.replace( /\s+/g, ' ' ).trim().slice( 0, 40 );
				const label = ( el ) => {
					const card = el.closest( '[class*="is-style-listing-card-"]' );
					const title = card?.querySelector( '.wp-block-post-title' );
					return `${ title ? text( title ) : 'card' } › ${ el.tagName.toLowerCase() } "${ text( el ) }"`;
				};
				const copy = [];
				const links = [];
				const titles = [];

				for ( const card of document.querySelectorAll( selector ) ) {
					/**
					 * Body copy: every paragraph, taxonomy row and excerpt with
					 * text. A block that sets its own size preset (the search
					 * result's size-100 badge) is authored, not inherited, and
					 * is left out.
					 */
					for ( const el of card.querySelectorAll(
						'p, .wp-block-post-terms, .wp-block-post-excerpt__excerpt'
					) ) {
						if ( ! text( el ) || /has-(?!200-)\d+-font-size/.test( el.className ) ) {
							continue;
						}
						copy.push( [ label( el ), getComputedStyle( el ).fontSize ] );
					}

					for ( const el of card.querySelectorAll(
						'p a, .wp-block-post-terms a, .wp-block-post-excerpt a'
					) ) {
						if ( text( el ) ) {
							links.push( [ label( el ), getComputedStyle( el ).color ] );
						}
					}

					for ( const el of card.querySelectorAll( '.wp-block-post-title a' ) ) {
						titles.push( [ label( el ), getComputedStyle( el ).color ] );
					}
				}

				return { copy, links, titles };
			}, CARD );

			const wrongSize = report.copy.filter( ( [ , size ] ) => size !== size200 );
			expect(
				wrongSize,
				`card copy not at font size 200 (${ size200 }):\n  ${ wrongSize
					.map( ( row ) => row.join( ' → ' ) )
					.join( '\n  ' ) }`
			).toEqual( [] );

			const wrongLink = report.links.filter( ( [ , color ] ) => color !== brand600 );
			expect(
				wrongLink,
				`card links not brand-600 (${ brand600 }):\n  ${ wrongLink
					.map( ( row ) => row.join( ' → ' ) )
					.join( '\n  ' ) }`
			).toEqual( [] );

			const wrongTitle = report.titles.filter( ( [ , color ] ) => color !== neutral700 );
			expect(
				wrongTitle,
				`linked card titles not neutral-700 (${ neutral700 }):\n  ${ wrongTitle
					.map( ( row ) => row.join( ' → ' ) )
					.join( '\n  ' ) }`
			).toEqual( [] );
		} );
	}

	test( 'a card link turns brand-700 on hover', async ( { page, visit, routes } ) => {
		const target = routes.post( 'region' ) || routes.post( 'destination' );
		test.skip( ! target, 'No published destination' );

		await page.setViewportSize( { width: 1280, height: 800 } );
		await visit( target );

		const link = page
			.locator(
				`:is(${ CARD }) :is(p a, .wp-block-post-terms a, .wp-block-post-excerpt a)`
			)
			.filter( { visible: true } )
			.first();

		test.skip( 0 === ( await link.count() ), `No visible card links on ${ target }` );

		const brand700 = await resolvePreset(
			page,
			'color',
			'--wp--preset--color--brand-700'
		);

		await link.scrollIntoViewIfNeeded();
		await link.hover();

		/** `toHaveCSS` retries, which rides out the link's 0.25s colour transition. */
		await expect( link ).toHaveCSS( 'color', brand700 );
	} );
} );
