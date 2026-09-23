/**
 * Tours — the three tour templates' banners, the landing grid, the itinerary
 * and the highlights band.
 *
 * LS-2019 (line 9, Tour). Covers:
 *
 *   - `archive-tour.html`     → patterns/template-archive-tour.php
 *   - `single-tour.html`      → patterns/template-single-tour.php
 *   - `taxonomy-travel-style` → patterns/template-taxonomy-travel-style.php
 *
 * **The banner** on all three is the hero banner since 2026-09-23, the
 * destinations banner's device: a 400px floor (down from 454) with the
 * photograph filling it from 768px up, and below that a 3:1 strip over the
 * neutral-200 plate with the title in brand-500 (style.css, "Hero banner — the
 * phone stack"). The tour single is the one banner that keeps a strapline.
 *
 * **The landing grid** lists travel styles, two across, in 3:2 tiles, in
 * live's order — the order the terms were created in (term ID ascending),
 * which is what live's `get_terms()` call with no `orderby` returns.
 *
 * **The itinerary** never prints Tour Operator's `Card Link` placeholder, and
 * its second line carries only top-level (country) destinations.
 *
 * **The highlights** list runs the wide measure, and its check marks are
 * brand-500.
 *
 * Colours and sizes are compared against the resolved preset, never a literal.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );

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
 * The tours archive path in this environment.
 *
 * Local Studio runs PATH_INFO permalinks (`/index.php/tour/…`), so a literal
 * `/tours/` is a 404 there. The prefix is read off a permalink global setup
 * already resolved, rather than hardcoded per environment.
 *
 * @param {Object} routes The `routes` fixture.
 * @return {string} Path with a leading slash.
 */
function tourArchivePath( routes ) {
	const sample = routes.post( 'tour' ) || '';
	return `${ sample.startsWith( '/index.php/' ) ? '/index.php' : '' }/tours/`;
}

const BANNER_ROUTES = [
	{ name: 'tour archive', path: ( routes ) => tourArchivePath( routes ), strapline: true },
	{ name: 'single tour', path: ( routes ) => routes.post( 'tour' ), strapline: true },
	{ name: 'travel style archive', path: ( routes ) => routes.term( 'travel-style' ), strapline: true },
];

test.describe( 'Tour banners', () => {
	for ( const route of BANNER_ROUTES ) {
		test.describe( route.name, () => {
			test( 'is the hero banner: one h1, on the 400px floor on desktop @responsive', async ( {
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
				 * The floor, and the shortening: 400px since 2026-09-23. The
				 * upper bound is what catches a banner left on the old 454 —
				 * the type is short enough never to push the band past it.
				 */
				const height = ( await banner.boundingBox() ).height;
				expect( height, 'the banner is below its 400px floor' ).toBeGreaterThanOrEqual( 399 );
				expect( height, 'the banner is still on the old 454px floor' ).toBeLessThan( 453 );

				/**
				 * No inline padding on the cover: the section style owns it,
				 * and an inline value is what kept the phone stack off these
				 * templates.
				 */
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
} );

test.describe( 'Tours landing — the travel styles grid', () => {
	const GRID = 'main .sd-featured-terms-query .wp-block-term-template';
	const TILE = `${ GRID } > li`;

	test( 'runs two columns of 3:2 tiles on desktop', async ( { page, visit, routes } ) => {
		await page.setViewportSize( { width: 1280, height: 800 } );
		await visit( tourArchivePath( routes ) );

		const tiles = page.locator( TILE );
		test.skip( 0 === ( await tiles.count() ), 'No travel-style tiles rendered' );

		const columns = await page
			.locator( GRID )
			.first()
			.evaluate( ( el ) => getComputedStyle( el ).gridTemplateColumns.split( ' ' ).length );
		expect( columns, 'the grid is not two columns' ).toBe( 2 );

		const ratios = await page.locator( `${ TILE } img` ).evaluateAll( ( imgs ) =>
			imgs.map( ( img ) => {
				const box = img.getBoundingClientRect();
				return [ img.alt || img.src.split( '/' ).pop(), box.width / box.height ];
			} )
		);

		const off = ratios.filter( ( [ , ratio ] ) => Math.abs( ratio - 1.5 ) > 0.03 );
		expect(
			off,
			`tiles not cropped 3:2:\n  ${ off.map( ( [ n, r ] ) => `${ n } → ${ r.toFixed( 3 ) }` ).join( '\n  ' ) }`
		).toEqual( [] );
	} );

	test( "lists the featured travel styles in live's order: by term ID", async ( {
		page,
		visit,
		routes,
		request,
	} ) => {
		await page.setViewportSize( { width: 1280, height: 800 } );
		await visit( tourArchivePath( routes ) );

		const names = await page
			.locator( `${ TILE } .wp-block-term-name` )
			.evaluateAll( ( els ) => els.map( ( el ) => el.textContent.trim() ) );
		test.skip( 0 === names.length, 'No travel-style tiles rendered' );

		const response = await request.get(
			'/wp-json/wp/v2/travel-style?per_page=100&orderby=id&order=asc&_fields=id,name,count,meta'
		);
		expect( response.ok() ).toBe( true );

		/**
		 * REST returns names HTML-encoded ("Beach &amp; Safari Vacations").
		 * Decode in the page so the comparison is against the same text the
		 * tiles show.
		 */
		const terms = await page.evaluate( ( rows ) => {
			const decode = ( html ) => {
				const el = document.createElement( 'textarea' );
				el.innerHTML = html;
				return el.value;
			};
			return rows.map( ( row ) => ( { ...row, name: decode( row.name ) } ) );
		}, await response.json() );

		const order = terms.map( ( term ) => term.name );
		const positions = names.map( ( name ) => order.indexOf( name ) );

		expect( positions, `tiles not found among the terms: ${ names.join( ', ' ) }` ).not.toContain( -1 );
		expect(
			positions,
			`tiles are not in term-ID order: ${ names.join( ', ' ) }`
		).toEqual( [ ...positions ].sort( ( a, b ) => a - b ) );

		/**
		 * The selection is the `featured` term meta, where the environment
		 * exposes it over REST (dev does). Every tile must be featured, and
		 * every featured term with tours must be a tile.
		 */
		if ( terms.some( ( term ) => term.meta && 'featured' in term.meta ) ) {
			const featured = terms
				.filter( ( term ) => '1' === String( term.meta?.featured ) && 0 < term.count )
				.map( ( term ) => term.name );

			expect( names ).toEqual( featured );
		}
	} );
} );

test.describe( 'Single tour — itinerary', () => {
	test( 'never prints the "Card Link" placeholder for a missing field', async ( {
		page,
		visit,
		routes,
	} ) => {
		const target = routes.post( 'tour' );
		test.skip( ! target, 'No published tour' );

		await page.setViewportSize( { width: 1280, height: 800 } );
		await visit( target );

		const itinerary = page.locator( '.sd-itinerary' ).first();
		test.skip( 0 === ( await itinerary.count() ), `${ target } has no itinerary` );

		/** `innerText` leaves out anything `display: none`, i.e. hidden fields. */
		const text = await itinerary.evaluate( ( el ) => el.innerText );
		expect( text, 'a missing itinerary field printed its placeholder' ).not.toContain( 'Card Link' );
	} );

	test( 'a lodge with no destination after it has no trailing comma', async ( {
		page,
		visit,
		routes,
	} ) => {
		const target = routes.post( 'tour' );
		test.skip( ! target, 'No published tour' );

		await page.setViewportSize( { width: 1280, height: 800 } );
		await visit( target );

		const rows = await page.locator( '.sd-itinerary__stay' ).evaluateAll( ( stays ) =>
			stays.map( ( stay ) => {
				const lodge = stay.querySelector( '.itinerary-accommodation' );
				const location = stay.querySelector( '.itin-location-wrapper' );
				return {
					lodge: lodge?.textContent.trim(),
					hasLocation: !! location && 'none' !== getComputedStyle( location ).display,
					comma: lodge ? getComputedStyle( lodge, '::after' ).content : 'none',
				};
			} )
		);
		test.skip( 0 === rows.length, `${ target } has no itinerary` );

		for ( const row of rows ) {
			const expected = row.hasLocation ? '","' : 'none';
			expect( row.comma, `"${ row.lodge }": comma should be ${ expected }` ).toBe( expected );
		}
	} );

	test( 'the second line names only countries (top-level destinations)', async ( {
		page,
		visit,
		routes,
		request,
	} ) => {
		const target = routes.post( 'tour' );
		test.skip( ! target, 'No published tour' );

		await page.setViewportSize( { width: 1280, height: 800 } );
		await visit( target );

		const shown = await page
			.locator( '.sd-itinerary__stay .itin-country-wrapper' )
			.evaluateAll( ( wrappers ) =>
				wrappers
					.filter( ( el ) => 'none' !== getComputedStyle( el ).display )
					.flatMap( ( el ) => [ ...el.querySelectorAll( 'a' ) ].map( ( a ) => a.textContent.trim() ) )
			);
		test.skip( 0 === shown.length, `${ target } shows no country lines` );

		const response = await request.get(
			'/wp-json/wp/v2/destination?parent=0&per_page=100&_fields=title'
		);
		expect( response.ok() ).toBe( true );

		const countries = await page.evaluate(
			( rows ) =>
				rows.map( ( row ) => {
					const el = document.createElement( 'textarea' );
					el.innerHTML = row.title.rendered;
					return el.value;
				} ),
			await response.json()
		);

		const notCountries = shown.filter( ( name ) => ! countries.includes( name ) );
		expect( notCountries, `non-country destinations on the country line: ${ notCountries.join( ', ' ) }` ).toEqual( [] );
	} );
} );

test.describe( 'Single tour — highlights', () => {
	test( 'the list runs the wide measure and its checks are brand-500', async ( {
		page,
		visit,
		routes,
	} ) => {
		const target = routes.post( 'tour' );
		test.skip( ! target, 'No published tour' );

		await page.setViewportSize( { width: 1440, height: 900 } );
		await visit( target );

		const list = page.locator( '.is-style-highlights-list ul' ).first();
		test.skip( 0 === ( await list.count() ), `${ target } has no highlights` );

		const [ group, ul ] = await Promise.all( [
			page.locator( '.is-style-highlights-list' ).first().boundingBox(),
			list.boundingBox(),
		] );

		/**
		 * The group is `alignwide`; the list used to be clamped back to the
		 * content measure inside it. It now fills the group, less its own
		 * left padding.
		 */
		expect( ul.width, `the list is ${ ul.width }px inside a ${ group.width }px wide band` ).toBeGreaterThan(
			group.width * 0.9
		);

		const brand500 = await resolvePreset( page, 'background-color', '--wp--preset--color--brand-500' );
		const tick = await list
			.locator( 'li' )
			.first()
			.evaluate( ( li ) => getComputedStyle( li, '::before' ).backgroundColor );

		expect( tick ).toBe( brand500 );
	} );
} );
