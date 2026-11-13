/**
 * Team — the archive grouped by role, and the single team member page.
 *
 * ASD-41 item 21.3 (functional QA against the 2019 scope), gaps TM1–TM7 and G12:
 *
 *   TM1  archive: banner, members grouped under their role headings
 *   TM2  single: name, role, description, image, Get in touch
 *   TM3  single: tiled gallery with a lightbox
 *   TM5  single: Favourite Tours, Favourite Destinations, Blog Posts carousels
 *   TM7  single: a map link in the page navigation, and the map itself
 *   G12  the team breadcrumb gains an "About us" parent link
 *
 * The single is pinned to a consultant with every section populated, and a
 * support-team member with none, because which sections show depends on the
 * member's own content. Trustpilot reviews and the map's marker data are the
 * plugin suite's (integration/trustpilot, team-map).
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );
const { pinned } = require( '../fixtures/routes.js' );

const ROLE_GROUPS = [ 'Management Team', 'Consultants', 'Support Team' ];

/**
 * The breadcrumb trail as text segments and the hrefs it links.
 *
 * @param {import('@playwright/test').Page} page Page.
 * @return {Promise<{labels: string[], linked: string[]}>} Trail.
 */
async function trail( page ) {
	const crumbs = page.locator( 'main .yoast-breadcrumbs' ).first();
	await expect( crumbs, 'no breadcrumb strip' ).toBeVisible();

	return {
		labels: ( await crumbs.innerText() )
			.split( '/' )
			.map( ( part ) => part.trim() )
			.filter( Boolean ),
		linked: await crumbs
			.locator( 'a' )
			.evaluateAll( ( links ) =>
				links.map( ( a ) => a.textContent.trim() )
			),
	};
}

test.describe( 'Team archive', () => {
	test.beforeEach( async ( { page, visit } ) => {
		await page.setViewportSize( { width: 1280, height: 800 } );
		await visit( '/team/' );
	} );

	// requirement: RQ-TM1 / test-case: TC-TM1-01
	test( 'groups the members under a heading for each role, in order', async ( {
		page,
	} ) => {
		// textContent, not innerText: the headings are upper-cased by CSS.
		const headings = await page
			.locator( 'main h2.wp-block-heading' )
			.evaluateAll( ( els ) => els.map( ( el ) => el.textContent ) );
		const roles = headings
			.map( ( text ) => text.trim() )
			.filter( ( text ) => ROLE_GROUPS.includes( text ) );

		expect( roles, 'role headings missing or out of order' ).toEqual(
			ROLE_GROUPS
		);
	} );

	// requirement: RQ-TM1 / test-case: TC-TM1-02
	test( 'lists members beneath each role, each linking to their own page', async ( {
		page,
	} ) => {
		for ( const role of ROLE_GROUPS ) {
			const group = page
				.locator( 'main h2.wp-block-heading', {
					hasText: new RegExp( `^${ role }$` ),
				} )
				.locator( 'xpath=following::ul[1]' );
			const members = group.locator( 'li.wp-block-post' );

			expect(
				await members.count(),
				`no members under "${ role }"`
			).toBeGreaterThan( 0 );

			for ( const href of await members
				.locator( 'a[href*="/team/"]' )
				.evaluateAll( ( links ) => links.map( ( a ) => a.href ) ) ) {
				expect( href ).toMatch( /\/team\/[^/]+\/?$/ );
			}
		}
	} );

	/**
	 * Live outranks the 2019 spec. Live's team archive trail is plain text,
	 * "Home About Us / Meet The Team", with no separate "About Us" link
	 * (measured 2026-11-13). Dev shows the same words, so only the parent's
	 * presence is asserted.
	 */
	// requirement: RQ-G12 / test-case: TC-G12-01
	test( 'the breadcrumb names its "About Us" parent, as live does', async ( {
		page,
	} ) => {
		const { labels } = await trail( page );

		expect( labels, 'trail has no About Us parent' ).toContain(
			'About Us'
		);
	} );
} );

test.describe( 'Team single', () => {
	test.beforeEach( async ( { page, visit } ) => {
		await page.setViewportSize( { width: 1280, height: 800 } );
		await visit( pinned( 'teamMember' ) );
	} );

	// requirement: RQ-TM2 / test-case: TC-TM2-01
	test( 'shows name, role, description, photograph and a Get in touch button', async ( {
		page,
	} ) => {
		await expect( page.locator( 'main h1' ) ).toHaveText( 'Ilze Fox' );

		const summary = page.locator( '#summary' );
		await expect( summary.locator( '.lsx-role-wrapper' ) ).toHaveText(
			/\S/
		);
		expect(
			(
				await summary.locator( '.wp-block-post-content' ).innerText()
			).split( /\s+/ ).length,
			'the description is a stub'
		).toBeGreaterThan( 20 );
		await expect(
			summary.locator( 'img.wp-post-image' ),
			'no photograph'
		).toBeVisible();

		const cta = summary.getByRole( 'link', { name: 'Get in touch' } );
		await expect( cta ).toBeVisible();
		await expect( cta ).toHaveAttribute( 'href', /^(mailto:|https?:)/ );
	} );

	// requirement: RQ-TM3 / test-case: TC-TM3-01
	test( 'has a tiled gallery whose lightbox opens and closes on Escape', async ( {
		page,
	} ) => {
		const gallery = page.locator( '#gallery .sd-gallery' );
		await expect( gallery, 'no gallery' ).toHaveCount( 1 );

		const dialog = gallery.locator( 'dialog.sd-gallery__dialog' );
		await gallery.locator( 'a.sd-gallery__link' ).first().click();
		await expect( dialog ).toHaveAttribute( 'open', '' );

		await page.keyboard.press( 'Escape' );
		await expect( dialog ).not.toHaveAttribute( 'open', '' );
	} );

	// requirement: RQ-TM5 / test-case: TC-TM5-01
	for ( const shelf of [
		{ id: 'tours', heading: /Favourite Tours$/, kind: 'tour' },
		{
			id: 'destination',
			heading: /Favourite Destinations$/,
			kind: 'destination',
		},
		{ id: 'posts', heading: /Blog$/, kind: null },
	] ) {
		test( `carries the "${ shelf.id }" carousel`, async ( { page } ) => {
			const section = page.locator( `section#${ shelf.id }` );
			await expect( section ).toHaveCount( 1 );
			await expect( section.locator( 'h2' ).first() ).toHaveText(
				shelf.heading
			);
			await expect(
				section.locator( 'ul[data-slick]' ),
				`#${ shelf.id } is not a carousel`
			).toHaveCount( 1 );

			const cards = section.locator(
				shelf.kind
					? `li.wp-block-post:not(.slick-cloned) a[href*="/${ shelf.kind }/"]`
					: 'li.wp-block-post:not(.slick-cloned) a[href]'
			);
			expect(
				await cards.count(),
				`#${ shelf.id } lists nothing`
			).toBeGreaterThan( 0 );
		} );
	}

	/**
	 * Live outranks the 2019 spec. Live's team single has the `#map` section
	 * but nothing on the page links to it (no `a[href$="#map"]`, measured
	 * 2026-11-13), so the 2019 submenu "map" link is not asserted.
	 */
	// requirement: RQ-TM7 / test-case: TC-TM7-01
	test( 'has a map section, as live does', async ( { page } ) => {
		await expect(
			page
				.locator(
					'section#map .lsx-map-preview, section#map a[role="button"]'
				)
				.first()
		).toBeVisible();
	} );

	/**
	 * Live's single trail is "Home > About Us / Meet The Team > Name", where the
	 * middle crumb is one link whose text starts with "About Us".
	 */
	// requirement: RQ-G12 / test-case: TC-G12-02
	test( 'the single’s breadcrumb links its "About Us" parent, as live does', async ( {
		page,
	} ) => {
		const { labels, linked } = await trail( page );

		expect(
			labels.slice( -1 )[ 0 ],
			'trail does not end on the member'
		).toBe( 'Ilze Fox' );
		expect(
			linked.some( ( text ) => /^About Us/.test( text ) ),
			'"About Us" is not a link in the member trail'
		).toBe( true );
	} );
} );

test.describe( 'Team single — a member with no extras', () => {
	/**
	 * A member with no gallery, favourites or posts must not print empty
	 * headings: the shelves belong to the member's own content.
	 */
	// requirement: RQ-TM5 / test-case: TC-TM5-02
	test( 'drops the sections the member has nothing for', async ( {
		page,
		visit,
	} ) => {
		await page.setViewportSize( { width: 1280, height: 800 } );
		await visit( pinned( 'teamMemberBare' ) );

		await expect( page.locator( 'main h1' ) ).toHaveText( 'Leigh Skibbe' );
		await expect( page.locator( '#summary' ) ).toBeVisible();
		for ( const id of [ 'gallery', 'tours', 'destination', 'posts' ] ) {
			await expect(
				page.locator( `section#${ id }` ),
				`an empty #${ id } section is showing`
			).toHaveCount( 0 );
		}
	} );
} );
