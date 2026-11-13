/**
 * Destination country and region sections — the 2019 spec's single-destination
 * building blocks, asserted on the pages that carry them.
 *
 * ASD-41 item 21.3 (functional QA against the 2019 scope), gaps D4–D8:
 *
 *   D4  the description collapses behind Read more
 *   D5  the interactive map
 *   D6  the gallery and its lightbox
 *   D7  country: Popular Travel Destinations carousel and related tours
 *   D8  region: connected accommodation and tours carousels
 *
 * Pinned to South Africa (a country) and Kruger National Park (a region): the
 * newest destination global setup resolves is a one-region country with no
 * gallery or tours, which cannot carry these sections.
 *
 * Out of this file: "Not sure where to go" is cta.spec.js; template assignment
 * is destinations.spec.js; the layout of the banner is utils/hero-banner.js.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );
const { pinned } = require( '../fixtures/routes.js' );

/**
 * Tour Operator's fallback map centre, Cape Town. A destination with no
 * usable location falls back to it, so a map centred here on any other
 * destination has not been given that destination's coordinates.
 */
const DEFAULT_CENTRE = { lat: '-33.914482', long: '18.3758789' };

const PAGES = [
	{ name: 'country', key: 'country' },
	{ name: 'region', key: 'region' },
];

test.describe( 'Destination — description Read more (D4)', () => {
	// requirement: RQ-D4 / test-case: TC-D4-01
	test( 'a country description shows its first paragraph, and Read more reveals the rest', async ( {
		page,
		visit,
	} ) => {
		await page.setViewportSize( { width: 1280, height: 800 } );
		await visit( pinned( 'country' ) );

		const paragraphs = page.locator(
			'#summary .wp-block-post-content > p'
		);
		const readMore = page
			.locator( '#summary a.wp-block-read-more' )
			.first();
		const total = await paragraphs.count();
		test.skip(
			2 > total,
			'The pinned country has a one-paragraph description'
		);

		await expect( paragraphs.first() ).toBeVisible();
		await expect(
			paragraphs.nth( total - 1 ),
			'the whole description shows with no Read more in play'
		).toBeHidden();

		await expect( readMore ).toBeVisible();
		await readMore.click();

		await expect(
			paragraphs.nth( total - 1 ),
			'Read more did not reveal the rest of the description'
		).toBeVisible();
	} );
} );

test.describe( 'Destination — interactive map (D5)', () => {
	for ( const { name, key } of PAGES ) {
		// requirement: RQ-D5 / test-case: TC-D5-01
		test( `a ${ name } carries a map with a click-to-load preview`, async ( {
			page,
			visit,
		} ) => {
			await page.setViewportSize( { width: 1280, height: 800 } );
			await visit( pinned( key ) );

			const map = page.locator( '.lsx-location-wrapper .lsx-map' );
			await expect( map, `no map on the ${ name }` ).toHaveCount( 1 );
			await expect( map.locator( '.lsx-map-preview a' ) ).toBeVisible();
			await expect( map.locator( '.lsx-map-details' ) ).toHaveAttribute(
				'data-lat',
				/^-?\d/
			);
			await expect( map.locator( '.lsx-map-details' ) ).toHaveAttribute(
				'data-long',
				/^-?\d/
			);
		} );
	}

	// requirement: RQ-D5 / test-case: TC-D5-02
	test( 'activating the preview mounts the map', async ( {
		page,
		visit,
	} ) => {
		await page.setViewportSize( { width: 1280, height: 800 } );
		await visit( pinned( 'country' ) );

		await page.locator( '.lsx-map-preview a' ).first().click();

		await expect(
			page.locator( '.lsx-map .gm-style' ),
			'the map did not mount after the preview was activated'
		).toBeVisible( { timeout: 20000 } );
	} );

	/**
	 * Live outranks the 2019 spec. Live's destination map is also centred on
	 * Tour Operator's Cape Town default (`.lsx-map` data-lat -33.914482,
	 * measured 2026-11-13 on Kruger National Park); the destination's own
	 * places are plotted as `.map-data` markers instead. So the centre is not
	 * asserted, and the check is that the markers carry the destination's real
	 * coordinates, not the fallback.
	 */
	// requirement: RQ-D5 / test-case: TC-D5-03
	test( 'a destination outside Cape Town plots its own markers', async ( {
		page,
		visit,
	} ) => {
		await page.setViewportSize( { width: 1280, height: 800 } );
		await visit( pinned( 'region' ) );

		const markers = await page
			.locator( '.lsx-location-wrapper .map-data[data-lat]' )
			.evaluateAll( ( nodes ) =>
				nodes.map( ( node ) => ( {
					lat: node.dataset.lat,
					long: node.dataset.long,
				} ) )
			);

		expect(
			markers.filter(
				( m ) =>
					m.lat !== DEFAULT_CENTRE.lat ||
					m.long !== DEFAULT_CENTRE.long
			).length,
			'Kruger National Park plots no marker away from the Cape Town fallback'
		).toBeGreaterThan( 0 );
	} );
} );

test.describe( 'Destination — gallery (D6)', () => {
	for ( const { name, key } of PAGES ) {
		// requirement: RQ-D6 / test-case: TC-D6-01
		test( `a ${ name } gallery opens a lightbox and Escape closes it`, async ( {
			page,
			visit,
		} ) => {
			await page.setViewportSize( { width: 1280, height: 800 } );
			await visit( pinned( key ) );

			const gallery = page.locator( '#gallery .sd-gallery' );
			await expect( gallery, `no gallery on the ${ name }` ).toHaveCount(
				1
			);

			const links = gallery.locator( 'a.sd-gallery__link' );
			expect(
				await links.count(),
				'the gallery has fewer than two tiles'
			).toBeGreaterThan( 1 );

			const dialog = gallery.locator( 'dialog.sd-gallery__dialog' );
			await links.first().click();
			await expect( dialog ).toHaveAttribute( 'open', '' );
			await expect(
				dialog.locator( '.sd-gallery__stage-image' )
			).toBeVisible();

			await page.keyboard.press( 'Escape' );
			await expect( dialog ).not.toHaveAttribute( 'open', '' );
		} );
	}
} );

/**
 * A carousel shelf: a heading, then a list of cards linking where they say,
 * on a Slick container. Each card link must resolve to its own kind.
 *
 * @param {import('@playwright/test').Page} page      Page.
 * @param {string}                          sectionId Anchor of the shelf's section.
 * @param {string}                          kind      Path segment cards link to.
 * @return {Promise<string[]>} Distinct card hrefs.
 */
async function shelfLinks( page, sectionId, kind ) {
	const shelf = page.locator( `section#${ sectionId }` );
	await expect( shelf, `no #${ sectionId } shelf` ).toHaveCount( 1 );
	await expect( shelf.locator( 'h2' ).first() ).toHaveText( /\S/ );
	await expect(
		shelf.locator( 'ul[data-slick]' ),
		`#${ sectionId } is not a carousel`
	).toHaveCount( 1 );

	const hrefs = await shelf
		.locator( `li.wp-block-post:not(.slick-cloned) a[href*="/${ kind }/"]` )
		.evaluateAll( ( links ) => [
			...new Set( links.map( ( a ) => a.href ) ),
		] );
	expect(
		hrefs.length,
		`#${ sectionId } lists no ${ kind }s`
	).toBeGreaterThan( 0 );

	return hrefs;
}

test.describe( 'Country — popular destinations and related tours (D7)', () => {
	// requirement: RQ-D7 / test-case: TC-D7-01
	test( 'carries a Popular Travel Destinations carousel of its own regions', async ( {
		page,
		visit,
	} ) => {
		await page.setViewportSize( { width: 1280, height: 800 } );
		await visit( pinned( 'country' ) );

		await expect( page.locator( 'section#regions h2' ).first() ).toHaveText(
			/^Popular Travel Destinations in South Africa$/
		);

		const hrefs = await shelfLinks( page, 'regions', 'destination' );
		for ( const href of hrefs ) {
			expect(
				href,
				'a popular destination is not under this country'
			).toContain( pinned( 'country' ) );
		}
	} );

	// requirement: RQ-D7 / test-case: TC-D7-02
	test( 'carries a related-tours carousel', async ( { page, visit } ) => {
		await page.setViewportSize( { width: 1280, height: 800 } );
		await visit( pinned( 'country' ) );

		await expect( page.locator( 'section#tours h2' ).first() ).toHaveText(
			/^South Africa Tours to Inspire You$/
		);
		await shelfLinks( page, 'tours', 'tour' );
	} );
} );

test.describe( 'Region — accommodation, specials and tours carousels (D8)', () => {
	// requirement: RQ-D8 / test-case: TC-D8-01
	test( 'carries a favourite-accommodation carousel', async ( {
		page,
		visit,
	} ) => {
		await page.setViewportSize( { width: 1280, height: 800 } );
		await visit( pinned( 'region' ) );

		await expect(
			page.locator( 'section#accommodation h2' ).first()
		).toHaveText( /^Our Favourite Kruger National Park Accommodations$/ );
		await shelfLinks( page, 'accommodation', 'accommodation' );
	} );

	// requirement: RQ-D8 / test-case: TC-D8-02
	test( 'carries a related-tours carousel', async ( { page, visit } ) => {
		await page.setViewportSize( { width: 1280, height: 800 } );
		await visit( pinned( 'region' ) );

		await expect( page.locator( 'section#tours h2' ).first() ).toHaveText(
			/^Kruger National Park Tours to Inspire You$/
		);
		await shelfLinks( page, 'tours', 'tour' );
	} );

	/**
	 * Skipped, not asserted. Live prints a connected-specials shelf on a region
	 * that has a special connected to it; the block theme has no such shelf yet
	 * (patterns/template-single-region.php records the specials tile as
	 * unbuilt). No pinned dev region is known to have a connected special, so a
	 * red here would say "this region has none", not "the template lacks it".
	 * Pin a region with a connected special to turn this into an assertion.
	 */
	// requirement: RQ-D8 / test-case: TC-D8-03
	test.skip( 'carries a connected-specials carousel', () => {} );
} );
