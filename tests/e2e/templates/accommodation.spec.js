/**
 * Accommodation — the five accommodation templates, finalised 2026-09-23.
 *
 * LS-2017 (line 7, Accommodation) and LS-2018 (line 8, Lodge / Brand). Covers:
 *
 *   - `archive-accommodation.html`        → patterns/template-archive-accommodation.php
 *   - `single-accommodation.html`         → patterns/template-single-accommodation.php
 *   - `taxonomy-accommodation-type.html`  → patterns/template-taxonomy-accommodation-type.php
 *   - `taxonomy-accommodation-brand.html` → patterns/template-taxonomy-accommodation-brand.php
 *   - `page-brands.html`                  → patterns/template-page-brands.php
 *
 * **The banner** on all five is the hero banner, phone stack included, on the
 * 360px floor — utils/hero-banner.js carries that contract.
 *
 * **The landing grid** lists the featured accommodation types in live's order,
 * which is written down as term IDs because live's own order is an accident of
 * an un-ordered `get_terms()` (the note is in the archive pattern).
 *
 * **The list card** runs a 40-word excerpt and carries live's "On Special"
 * badge — accent-500, contrast text, top-right — on a property with a
 * published connected special.
 *
 * **The single's rating** stacks the stars under "This property is rated",
 * aligned left.
 *
 * **The brands page** is three logos across with its section heading hidden,
 * as the Site Editor override on dev had it; **a one-region brand** still
 * gets its region strip.
 *
 * **The filter flyout** — the rail as a "Filters" button and an off-canvas
 * FacetWP Flyout dialog below 782px — is checked on the type archive, the one
 * accommodation template with the full facet rail.
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
	const sample = routes.post( 'accommodation' ) || routes.post( 'tour' ) || '';
	return `${ sample.startsWith( '/index.php/' ) ? '/index.php' : '' }${ path }`;
}

/**
 * Live's grid order for the featured accommodation types, by slug.
 * Measured 2026-09-23 on https://www.southerndestinations.com/accommodation/.
 */
const LIVE_TYPE_ORDER = [
	'safari-lodges',
	'luxury-tented-camps',
	'boutique-hotels',
	'guest-houses',
	'hotels',
	'houseboats',
	'island-lodges',
	'mobile-tented-camps',
	'private-villas',
	'africas-finest',
	'beach-lodges',
];

/**
 * The type archive and the property on it that carries a published special.
 * Zambezi Queen (dev post 42422) is a Houseboat with `special_to_accommodation`
 * set, measured on dev 2026-09-23.
 */
const SPECIAL_ARCHIVE = '/accommodation-type/houseboats/';
const SPECIAL_PROPERTY = /zambezi queen/i;

/** A property with a star rating, measured on dev 2026-09-23. */
const RATED_PROPERTY = '/accommodation/chitwa-chitwa/';

/** A brand with a single region — the page that read as broken without its strip. */
const ONE_REGION_BRAND = '/brand/time-tide/';

const CARD = '.wp-block-columns[class*="is-style-listing-card-list"]';

describeHeroBanners( { test, expect }, [
	{ name: 'accommodation archive', path: ( routes ) => envPath( routes, '/accommodation/' ) },
	{ name: 'single accommodation', path: ( routes ) => routes.post( 'accommodation' ) },
	{ name: 'accommodation type archive', path: ( routes ) => routes.term( 'accommodation-type' ), strapline: true },
	{ name: 'accommodation brand archive', path: ( routes ) => routes.term( 'accommodation-brand' ), strapline: true },
	{ name: 'brands page', path: ( routes ) => envPath( routes, '/brands/' ), strapline: true },
] );

test.describe( 'Accommodation landing', () => {
	test( 'lists the featured types in live\'s order', async ( { page, visit, routes } ) => {
		await visit( envPath( routes, '/accommodation/' ) );

		const slugs = await page
			.locator( '.sd-featured-terms-query .wp-block-term-name a' )
			.evaluateAll( ( links ) =>
				links.map( ( a ) => new URL( a.href ).pathname.split( '/' ).filter( Boolean ).pop() )
			);

		test.skip( 0 === slugs.length, 'No featured accommodation types in this environment' );

		expect( slugs ).toEqual( LIVE_TYPE_ORDER.filter( ( slug ) => slugs.includes( slug ) ) );
		expect( slugs.length, 'a featured type is missing from the grid' ).toBe( LIVE_TYPE_ORDER.length );
	} );
} );

test.describe( 'Accommodation list card', () => {
	test( 'runs an excerpt of at most 40 words', async ( { page, visit, routes } ) => {
		const target = routes.term( 'accommodation-type' );
		test.skip( ! target, 'No accommodation type archive' );

		await visit( target );

		const excerpts = await page.locator( `${ CARD } .wp-block-post-excerpt__excerpt` ).allTextContents();
		test.skip( 0 === excerpts.length, `${ target } renders no cards` );

		for ( const text of excerpts ) {
			// Split the way wp_trim_words() counts (wp-includes/formatting.php): on
			// ASCII whitespace only. `\s` would also split a non-breaking space.
			const words = text.replace( /…|&hellip;/g, ' ' ).trim().split( /[\n\r\t ]+/ ).filter( Boolean );
			expect( words.length, `excerpt runs ${ words.length } words: "${ text.trim() }"` ).toBeLessThanOrEqual( 40 );
		}
	} );

	test( 'badges a property on special like live, with contrast text', async ( { page, visit, routes } ) => {
		await page.setViewportSize( { width: 1280, height: 900 } );
		await visit( envPath( routes, SPECIAL_ARCHIVE ) );

		const card = page.locator( CARD ).filter( { has: page.locator( '.wp-block-post-title', { hasText: SPECIAL_PROPERTY } ) } ).first();
		test.skip( 0 === ( await card.count() ), `${ SPECIAL_ARCHIVE } does not list the probe property` );

		const badge = card.locator( '.listing-card-list__badge--special' );
		await expect( badge ).toBeVisible();
		await expect( badge ).toHaveText( /on special/i );

		const [ ground, ink ] = await Promise.all( [
			resolvePreset( page, 'background-color', '--wp--preset--color--accent-500' ),
			resolvePreset( page, 'color', '--wp--preset--color--contrast' ),
		] );

		await expect( badge ).toHaveCSS( 'background-color', ground );
		await expect( badge ).toHaveCSS( 'color', ink );
		await expect( badge ).toHaveCSS( 'text-transform', 'uppercase' );

		// Pinned to the image's top-right corner, as live's `.special-tag`.
		const media = await card.locator( '.listing-card-list__media' ).boundingBox();
		const box = await badge.boundingBox();

		expect( Math.abs( box.y - media.y ), 'the badge is not on the top edge' ).toBeLessThanOrEqual( 1 );
		expect( Math.abs( box.x + box.width - ( media.x + media.width ) ), 'the badge is not on the trailing edge' ).toBeLessThanOrEqual( 1 );
	} );

	test( 'shows no badge on a property with no special', async ( { page, visit, routes } ) => {
		await visit( envPath( routes, SPECIAL_ARCHIVE ) );

		const cards = page.locator( CARD );
		const count = await cards.count();
		test.skip( 0 === count, `${ SPECIAL_ARCHIVE } renders no cards` );

		for ( let i = 0; i < count; i++ ) {
			const badge = cards.nth( i ).locator( '.listing-card-list__badge--special' );

			if ( ! ( await badge.count() ) ) {
				continue;
			}

			const text = ( await badge.textContent() ).trim();

			if ( '' === text ) {
				await expect( badge, 'an empty badge is showing as a bare square' ).toBeHidden();
			}
		}
	} );
} );

test.describe( 'Single accommodation', () => {
	test( 'stacks the rating stars under the label', async ( { page, visit, routes } ) => {
		// Chitwa Chitwa carries a four-star rating on dev; the newest property
		// global setup resolves may have none.
		const target = envPath( routes, RATED_PROPERTY );

		await page.setViewportSize( { width: 1280, height: 900 } );
		await visit( target );

		const wrapper = page.locator( 'main .lsx-rating-wrapper' ).first();
		const stars = wrapper.locator( '.rating-stars' );
		test.skip( 0 === ( await stars.count() ), `${ target } has no rating` );

		const label = await wrapper.locator( 'h2' ).boundingBox();
		const row = await stars.boundingBox();

		expect( row.y, 'the stars sit beside the label, not under it' ).toBeGreaterThanOrEqual( label.y + label.height - 1 );

		const box = await wrapper.boundingBox();
		expect( Math.abs( row.x - box.x ), 'the stars are not aligned to the left edge' ).toBeLessThanOrEqual( 2 );
	} );
} );

test.describe( 'Brands', () => {
	test( 'runs the logos three across and hides the section heading', async ( { page, visit, routes } ) => {
		await page.setViewportSize( { width: 1280, height: 900 } );
		await visit( envPath( routes, '/brands/' ) );

		await expect( page.locator( '#h-our-preferred-operators' ) ).toHaveCount( 0 );

		const grid = page.locator( '#brands .wp-block-term-template' ).first();
		test.skip( 0 === ( await grid.count() ), 'No brands grid in this environment' );

		const columns = await grid.evaluate( ( el ) =>
			getComputedStyle( el ).gridTemplateColumns.split( ' ' ).filter( Boolean ).length
		);
		expect( columns ).toBe( 3 );
	} );

	test( 'keeps the region strip on a one-region brand', async ( { page, visit, routes } ) => {
		await visit( envPath( routes, ONE_REGION_BRAND ) );

		const nav = page.locator( 'nav.sd-brand-regions' );
		await expect( nav, 'a one-region brand renders no region strip' ).toBeVisible();

		const tabs = nav.locator( 'a' );
		expect( await tabs.count() ).toBeGreaterThanOrEqual( 1 );
		await expect( nav.locator( 'a[aria-current="page"]' ) ).toHaveCount( 1 );
	} );
} );

test.describe( 'Filter flyout', () => {
	/**
	 * Wait for FacetWP's first load, and report whether the Flyout add-on
	 * came with it — the script marks the root only when `FWP.flyout` exists.
	 *
	 * @param {import('@playwright/test').Page} page Page.
	 * @return {Promise<boolean>} Whether the flyout is available.
	 */
	async function flyoutReady( page ) {
		await page.waitForFunction( () => window.FWP && window.FWP.loaded, null, { timeout: 15000 } ).catch( () => {} );

		return page.evaluate( () => document.documentElement.classList.contains( 'sd-has-filter-flyout' ) );
	}

	test( 'leaves the rail alone and hides the trigger on desktop @responsive', async ( { page, visit, routes } ) => {
		const target = routes.term( 'accommodation-type' );
		test.skip( ! target, 'No accommodation type archive' );

		await page.setViewportSize( { width: 1280, height: 900 } );
		await visit( target );
		await flyoutReady( page );

		await expect( page.locator( '.sd-filters-toggle' ) ).toBeHidden();
		await expect( page.locator( 'aside.sd-search-filters .facetwp-facet' ).first() ).toBeVisible();
	} );

	test( 'collapses the rail to a Filters button that opens an accessible dialog on phones @responsive', async ( {
		page,
		visit,
		routes,
	} ) => {
		const target = routes.term( 'accommodation-type' );
		test.skip( ! target, 'No accommodation type archive' );

		await page.setViewportSize( { width: 390, height: 844 } );
		await visit( target );
		test.skip( ! ( await flyoutReady( page ) ), 'FacetWP Flyout is not active here' );

		const trigger = page.locator( '.sd-filters-toggle button' );
		await expect( trigger ).toBeVisible();
		await expect( trigger ).toHaveAttribute( 'aria-expanded', 'false' );
		await expect( page.locator( 'aside.sd-search-filters > .wp-block-heading' ) ).toBeHidden();

		await trigger.click();

		const panel = page.locator( '.facetwp-flyout' );
		await expect( panel ).toHaveClass( /\bactive\b/ );
		await expect( panel ).toHaveAttribute( 'role', 'dialog' );
		await expect( panel ).toHaveAttribute( 'aria-modal', 'true' );
		await expect( trigger ).toHaveAttribute( 'aria-expanded', 'true' );

		const close = panel.locator( 'button.facetwp-flyout-close' );
		await expect( close ).toBeFocused();
		await expect( close ).toHaveText( /close filters/i );

		// The rail's facets moved in; the results toolbar's sort did not.
		await expect( panel.locator( '.facetwp-facet' ).first() ).toBeVisible();
		await expect( panel.locator( '.facetwp-type-sort' ) ).toHaveCount( 0 );

		// Focus stays inside the panel.
		for ( let i = 0; i < 6; i++ ) {
			await page.keyboard.press( 'Tab' );
			const inside = await page.evaluate( () => !! document.activeElement.closest( '.facetwp-flyout' ) );
			expect( inside, 'Tab left the open filter panel' ).toBe( true );
		}

		await page.keyboard.press( 'Escape' );
		await expect( panel ).not.toHaveClass( /\bactive\b/ );
		await expect( trigger ).toBeFocused();
		await expect( trigger ).toHaveAttribute( 'aria-expanded', 'false' );
		await expect( page.locator( 'aside.sd-search-filters .facetwp-facet' ).first() ).toBeAttached();
	} );
} );
