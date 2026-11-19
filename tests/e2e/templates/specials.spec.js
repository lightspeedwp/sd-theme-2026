/**
 * Specials — the Specials landing page, finalised 2026-09-23.
 *
 * LS-2021 (Specials). Covers `archive-special.html` →
 * patterns/template-archive-special.php. There is no single template: the site
 * links to an offer as `/specials/#special-{slug}`, never to a page of its own.
 *
 * **The banner** is the hero banner, phone stack included, on the 360px floor —
 * utils/hero-banner.js carries that contract.
 *
 * **The offer band** is one full-width band per special: the featured image as
 * its ground, a 460px scrim panel against the leading edge of odd bands and the
 * trailing edge of even ones, never shorter than 540px, the bands butted
 * together. Below 900px the panel narrows to 90% and centres. Measured from
 * live's `.lsx-to-archive-item` on 2026-09-17 (styles/sections/cards/special-card.json).
 *
 * **The anchor** `special-{slug}` is injected per band by
 * `SD\Enhancements\Specials::add_band_anchor()`; these tests fail if the plugin
 * stops doing so or the band's `is-style-special-card` class is renamed.
 *
 * **Book Special** on every band points at `#to-modal-modal-special`, and the
 * page prints that one dialog however many bands carry the button.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );
const { describeHeroBanners } = require( '../utils/hero-banner.js' );

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
	const sample =
		routes.post( 'tour' ) || routes.post( 'accommodation' ) || '';
	return `${
		sample.startsWith( '/index.php/' ) ? '/index.php' : ''
	}${ path }`;
}

const BAND = 'main .wp-block-post-template > li > .is-style-special-card';
const PANEL = `${ BAND } > .wp-block-group`;
const MODAL = '#to-modal-modal-special';

/**
 * Visit the landing page and skip when it lists no offers.
 *
 * @param {Object}                          fixtures        `{ page, visit, routes }`.
 * @param {import('@playwright/test').Page} fixtures.page   Page under test.
 * @param {Function}                        fixtures.visit  The visit fixture.
 * @param {Object}                          fixtures.routes The routes fixture.
 * @return {Promise<import('@playwright/test').Locator>} The bands.
 */
async function visitSpecials( { page, visit, routes } ) {
	await visit( envPath( routes, '/specials/' ) );

	const response = await page.request.get(
		'/wp-json/wp/v2/special?per_page=1&status=publish&_fields=id'
	);
	expect(
		response.ok(),
		'Could not check whether specials are published'
	).toBe( true );
	const published = await response.json();
	expect(
		Array.isArray( published ),
		'Specials API did not return a list'
	).toBe( true );
	test.skip(
		0 === published.length,
		'No published specials in this environment'
	);

	const bands = page.locator( BAND );
	await expect(
		bands,
		'Published specials have no matching offer bands'
	).not.toHaveCount( 0 );

	return bands;
}

describeHeroBanners( { test, expect }, [
	{
		name: 'specials archive',
		path: ( routes ) => envPath( routes, '/specials/' ),
		strapline: true,
	},
] );

test.describe( 'Specials offer bands', () => {
	test( 'lists at most four offers, each titled once with an h2', async ( {
		page,
		visit,
		routes,
	} ) => {
		const bands = await visitSpecials( { page, visit, routes } );
		const count = await bands.count();

		expect(
			count,
			"more than live's four offers to a page"
		).toBeLessThanOrEqual( 4 );

		for ( let i = 0; i < count; i++ ) {
			const titles = bands.nth( i ).locator( 'h2.wp-block-post-title' );
			await expect( titles ).toHaveCount( 1 );
			expect(
				( await titles.textContent() ).trim(),
				`band ${ i + 1 } has an empty title`
			).not.toBe( '' );
		}
	} );

	test( 'gives every band a unique special-{slug} anchor', async ( {
		page,
		visit,
		routes,
	} ) => {
		const bands = await visitSpecials( { page, visit, routes } );
		const ids = await bands.evaluateAll( ( els ) =>
			els.map( ( el ) => el.id )
		);

		for ( const id of ids ) {
			expect(
				id,
				'a band has no special-{slug} anchor — is Specials::add_band_anchor() running?'
			).toMatch( /^special-[a-z0-9-]+$/ );
		}

		expect( new Set( ids ).size, 'two bands share an anchor' ).toBe(
			ids.length
		);
	} );

	test( 'scrolls a /specials/#special-{slug} link to its band', async ( {
		page,
		visit,
		routes,
	} ) => {
		const bands = await visitSpecials( { page, visit, routes } );
		const id = await bands.last().getAttribute( 'id' );
		test.skip( ! id, 'The last band has no anchor' );

		// A hash change on the loaded page is not a navigation; start from blank
		// so the browser's own load-time scroll to the fragment is what's tested.
		await page.goto( 'about:blank' );
		await page.setViewportSize( { width: 1280, height: 800 } );
		await visit( `${ envPath( routes, '/specials/' ) }#${ id }` );

		await expect( page.locator( `#${ id }` ) ).toBeInViewport();
	} );

	test( 'draws each band on its photograph, 540px floor, panel alternating edge @responsive', async ( {
		page,
		visit,
		routes,
	} ) => {
		await page.setViewportSize( { width: 1280, height: 900 } );
		const bands = await visitSpecials( { page, visit, routes } );
		const count = await bands.count();

		for ( let i = 0; i < count; i++ ) {
			const band = bands.nth( i );
			const box = await band.boundingBox();

			expect(
				box.height,
				`band ${ i + 1 } is below its 540px floor`
			).toBeGreaterThanOrEqual( 539 );

			const image = band.locator(
				':scope > .wp-block-post-featured-image'
			);

			if ( await image.count() ) {
				await expect( image ).toHaveCSS( 'position', 'absolute' );
				const photograph = image.locator( 'img' );
				await expect( photograph ).toHaveCount( 1 );
				const imageBox = await photograph.boundingBox();
				expect(
					Math.abs( imageBox.width - box.width ),
					`band ${ i + 1 }'s photograph does not fill its width`
				).toBeLessThanOrEqual( 1 );
				expect(
					Math.abs( imageBox.height - box.height ),
					`band ${ i + 1 }'s photograph does not fill it`
				).toBeLessThanOrEqual( 1 );
			}

			const panel = await band
				.locator( ':scope > .wp-block-group' )
				.boundingBox();
			expect(
				panel.width,
				`band ${ i + 1 }'s panel is narrower than 460px`
			).toBeGreaterThanOrEqual( 459 );
			expect(
				panel.width,
				`band ${ i + 1 }'s panel is wider than 460px`
			).toBeLessThanOrEqual( 461 );

			// Odd bands (1st, 3rd) hold the panel on the leading edge, even on the trailing.
			if ( 0 === i % 2 ) {
				expect(
					Math.abs( panel.x - box.x ),
					`band ${ i + 1 }'s panel is not on the leading edge`
				).toBeLessThanOrEqual( 1 );
			} else {
				expect(
					Math.abs( panel.x + panel.width - ( box.x + box.width ) ),
					`band ${ i + 1 }'s panel is not on the trailing edge`
				).toBeLessThanOrEqual( 1 );
			}
		}
	} );

	test( 'butts the bands together with no gap', async ( {
		page,
		visit,
		routes,
	} ) => {
		await page.setViewportSize( { width: 1280, height: 900 } );
		const bands = await visitSpecials( { page, visit, routes } );
		const count = await bands.count();
		test.skip( 2 > count, 'Fewer than two offers to compare' );

		for ( let i = 1; i < count; i++ ) {
			const above = await bands.nth( i - 1 ).boundingBox();
			const below = await bands.nth( i ).boundingBox();

			expect(
				Math.abs( below.y - ( above.y + above.height ) ),
				`a gap sits above band ${ i + 1 }`
			).toBeLessThanOrEqual( 1 );
		}
	} );

	test( 'narrows and centres the panel on phones @responsive', async ( {
		page,
		visit,
		routes,
	} ) => {
		await page.setViewportSize( { width: 390, height: 844 } );
		const bands = await visitSpecials( { page, visit, routes } );
		const count = await bands.count();

		for ( let i = 0; i < count; i++ ) {
			const box = await bands.nth( i ).boundingBox();
			const panel = await page.locator( PANEL ).nth( i ).boundingBox();

			expect(
				panel.width / box.width,
				`band ${ i + 1 }'s panel is not 90% of the band`
			).toBeCloseTo( 0.9, 1 );

			const left = panel.x - box.x;
			const right = box.x + box.width - ( panel.x + panel.width );
			expect(
				Math.abs( left - right ),
				`band ${ i + 1 }'s panel is not centred`
			).toBeLessThanOrEqual( 2 );
		}
	} );
} );

test.describe( 'Book Special', () => {
	test( 'points every band at the one specials dialog', async ( {
		page,
		visit,
		routes,
	} ) => {
		const bands = await visitSpecials( { page, visit, routes } );
		const count = await bands.count();

		const buttons = page.locator( `${ PANEL } .wp-block-button__link` );
		await expect( buttons ).toHaveCount( count );

		for ( let i = 0; i < count; i++ ) {
			await expect( buttons.nth( i ) ).toHaveText( /book special/i );
			await expect( buttons.nth( i ) ).toHaveAttribute( 'href', MODAL );
		}

		// Four bands, one dialog: the module deduplicates by slug.
		await expect( page.locator( `dialog${ MODAL }` ) ).toHaveCount( 1 );
	} );

	test( 'opens the specials dialog', async ( { page, visit, routes } ) => {
		await page.setViewportSize( { width: 1280, height: 900 } );
		await visitSpecials( { page, visit, routes } );

		await page
			.locator( `${ PANEL } .wp-block-button__link` )
			.first()
			.click();

		await expect( page.locator( `dialog${ MODAL }` ) ).toBeVisible();
	} );
} );

/**
 * ASD-41 item 21.3 — Specials, the 2019 loop rules: description length, order
 * and pagination (S2, S4, S5).
 */

/**
 * Published specials in the order the archive should list them, from the REST
 * API: the slug, the full body as plain text and the date.
 *
 * @param {import('@playwright/test').APIRequestContext} request Request context.
 * @return {Promise<Array<{slug: string, words: number, date: string}>>} Specials, newest first.
 */
async function publishedSpecials( request ) {
	const response = await request.get(
		'/wp-json/wp/v2/special?per_page=100&status=publish&orderby=date&order=desc&_fields=slug,date,content'
	);
	expect( response.ok(), 'Could not list published specials' ).toBe( true );

	return ( await response.json() ).map( ( special ) => ( {
		slug: special.slug,
		date: special.date,
		words: special.content.rendered
			.replace( /<[^>]+>/g, ' ' )
			.replace( /&nbsp;|\u00a0/g, ' ' )
			.split( /\s+/ )
			.filter( ( word ) => /\w/.test( word ) ).length,
	} ) );
}

test.describe( 'Specials — description length (2019 spec)', () => {
	/**
	 * The 2019 spec shortens each description to 40-50 words. Neither live nor
	 * this build does: both print the offer's complete body copy, authored
	 * lists and terms included (measured 2026-11-13: live bands run 56-138
	 * words), and the template records "not an excerpt, no read more" as
	 * FR-012. The 40-50 word cut is therefore not asserted; what is asserted is
	 * the behaviour that replaced it, that nothing is cut short.
	 */
	// requirement: RQ-S2 / test-case: TC-S2-01
	test( 'prints each offer\u2019s complete copy, not a cut-down excerpt', async ( {
		page,
		visit,
		routes,
		request,
	} ) => {
		const bands = await visitSpecials( { page, visit, routes } );
		const published = await publishedSpecials( request );
		const count = await bands.count();

		for ( let i = 0; i < count; i++ ) {
			const band = bands.nth( i );
			const slug = ( await band.getAttribute( 'id' ) ).replace(
				/^special-/,
				''
			);
			const special = published.find( ( s ) => s.slug === slug );
			if ( ! special ) {
				continue;
			}

			const copy = await band
				.locator( '.wp-block-post-content' )
				.innerText();
			const shown = copy
				.split( /\s+/ )
				.filter( ( word ) => /\w/.test( word ) ).length;

			expect(
				Math.abs( shown - special.words ),
				`"${ slug }" shows ${ shown } words of ${ special.words }`
			).toBeLessThanOrEqual( 2 );
			await expect(
				band.locator( 'a.wp-block-read-more' ),
				`"${ slug }" has a Read more`
			).toHaveCount( 0 );
		}
	} );

	// requirement: RQ-S2 / test-case: TC-S2-02
	test.skip( 'shortens each description to 40-50 words', () => {
		// Deliberately not built: FR-012 and live both print the full copy.
		// Kept as a skip so the 2019 line is visibly accounted for.
	} );
} );

test.describe( 'Specials — order and pagination (2019 spec)', () => {
	/**
	 * The 2019 loop queries by the special's start date. The start date is an
	 * admin-side field the public API does not expose, so what can be observed
	 * is the order the archive actually uses: newest first by publish date, and
	 * one unbroken run across pages. If the order key ever changes, this fails.
	 */
	// requirement: RQ-S4 / test-case: TC-S4-01
	test( 'lists offers newest first, as one run across the pages', async ( {
		page,
		visit,
		routes,
		request,
	} ) => {
		const published = await publishedSpecials( request );
		test.skip( 2 > published.length, 'Fewer than two published specials' );

		const first = await visitSpecials( { page, visit, routes } );
		const shown = (
			await first.evaluateAll( ( els ) =>
				els.map( ( el ) => el.id.replace( /^special-/, '' ) )
			)
		).slice();

		if ( published.length > shown.length ) {
			await visit( envPath( routes, '/specials/page/2/' ) );
			shown.push(
				...( await page
					.locator( BAND )
					.evaluateAll( ( els ) =>
						els.map( ( el ) => el.id.replace( /^special-/, '' ) )
					) )
			);
		}

		expect( shown, 'the archive order differs from newest-first' ).toEqual(
			published.map( ( s ) => s.slug ).slice( 0, shown.length )
		);
	} );

	// requirement: RQ-S5 / test-case: TC-S5-01
	test( 'paginates when there are more offers than fit a page', async ( {
		page,
		visit,
		routes,
		request,
	} ) => {
		const published = await publishedSpecials( request );
		const first = await visitSpecials( { page, visit, routes } );
		const perPage = await first.count();
		test.skip(
			published.length <= perPage,
			`Only ${ published.length } published specials: one page`
		);

		const nav = page.locator( 'main nav.wp-block-query-pagination' );
		await expect(
			nav,
			'more offers than a page but no pagination'
		).toBeVisible();
		await expect(
			nav.locator( 'a.wp-block-query-pagination-next' )
		).toHaveAttribute( 'href', /\/specials\/page\/2\/?$/ );

		const onFirst = await first.evaluateAll( ( els ) =>
			els.map( ( el ) => el.id )
		);
		await visit( envPath( routes, '/specials/page/2/' ) );
		const onSecond = await page
			.locator( BAND )
			.evaluateAll( ( els ) => els.map( ( el ) => el.id ) );

		expect( onSecond.length, 'page 2 is empty' ).toBeGreaterThan( 0 );
		expect(
			onSecond.filter( ( id ) => onFirst.includes( id ) ),
			'an offer is on both pages'
		).toEqual( [] );
		expect(
			onFirst.length + onSecond.length,
			'offers went missing between the pages'
		).toBe( published.length );
	} );
} );
