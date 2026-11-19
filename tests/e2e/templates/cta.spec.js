/**
 * The call-to-action bands, and which template carries which.
 *
 * LS-2014 item 4.6: live's `sd_call_info_section()` switched the band's title on
 * body class, and in a block theme that conditional becomes "which pattern each
 * template includes". Five patterns are the five branches, identical but for
 * their heading, so a template that includes the wrong one (or none) renders a
 * perfectly healthy page with the wrong sentence on it — and nothing else in the
 * suite would notice. Until now they were asserted only as "some enquiry route
 * exists in `main`" (enquiry.spec.js).
 *
 * Each band's heading carries a stable anchor, `h-{slug}`, which is the handle
 * here: the copy can be reworded without breaking the spec, and the check is on
 * *which band*, not what it says. The enquiry action is the band's own button,
 * and it has to resolve to a dialog that exists — an enquiry button with no
 * dialog behind it is the dangling-trigger bug class (ASD-36, gap 1).
 *
 * Read-only: nothing is submitted. The form itself is enquiry.spec.js's.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );

/**
 * Every band, so a page can be asserted to carry its own and none of the others.
 * The key is the pattern slug without its `cta-` prefix, which is also the
 * anchor suffix.
 */
const BANDS = [
	'feeling-lost',
	'inspired-by-this-property',
	'like-what-you-see',
	'not-sure-where-to-go',
	'tell-us-your-trip-ideas',
];

/** The enquiry modal's trigger and its dialog (template part `modal-enquiry`). */
const ENQUIRY_TARGET = '#to-modal-modal-enquiry';

/**
 * Where each band sits, from the template patterns that include it.
 *
 * `path` is a literal path or a function of the resolved routes; a function
 * returning null skips with the reason stated, which is a content fact.
 * `404` is the one placement with a non-200 status.
 */
const PLACEMENTS = [
	{
		band: 'tell-us-your-trip-ideas',
		name: 'single tour',
		path: ( routes ) => routes.post( 'tour' ),
	},
	{
		band: 'inspired-by-this-property',
		name: 'single accommodation',
		path: ( routes ) => routes.post( 'accommodation' ),
	},
	{
		band: 'not-sure-where-to-go',
		name: 'single country',
		path: ( routes ) => routes.post( 'country' ),
	},
	{
		band: 'not-sure-where-to-go',
		name: 'single region',
		path: ( routes ) => routes.post( 'region' ),
	},
	{
		band: 'not-sure-where-to-go',
		name: 'tour archive',
		path: () => '/tours/',
	},
	{
		band: 'not-sure-where-to-go',
		name: 'destination archive',
		path: () => '/destinations/',
	},
	{
		band: 'not-sure-where-to-go',
		name: 'team archive',
		path: () => '/team/',
	},
	{
		band: 'like-what-you-see',
		name: 'special archive',
		path: () => '/specials/',
	},
	{
		band: 'feeling-lost',
		name: '404',
		path: () => '/this-page-does-not-exist-sd-e2e/',
		expectStatus: 404,
	},
];

test.describe( 'CTA bands', () => {
	for ( const placement of PLACEMENTS ) {
		test( `${ placement.name } carries the "${ placement.band }" band and no other`, async ( {
			page,
			visit,
			routes,
		} ) => {
			const target = placement.path( routes );
			test.skip( ! target, `No ${ placement.name } in this environment` );

			await visit( target, {
				expectStatus: placement.expectStatus || 200,
			} );

			const main = page.locator( 'main' ).first();

			for ( const band of BANDS ) {
				await expect(
					main.locator( `h2#h-${ band }` ),
					band === placement.band
						? `${ placement.name } is missing its "${ band }" band — ` +
								'is the pattern still included by its template?'
						: `${ placement.name } carries the "${ band }" band, which belongs to another template`
				).toHaveCount( band === placement.band ? 1 : 0 );
			}

			/**
			 * The band is a `section`; its one button is the enquiry action.
			 * Scoped to the band so a header or card button cannot satisfy it.
			 */
			const band = main
				.locator( 'section', {
					has: page.locator( `h2#h-${ placement.band }` ),
				} )
				.last();
			const action = band.locator( 'a.wp-block-button__link' );

			await expect( action ).toHaveCount( 1 );
			await expect( action ).toHaveAttribute( 'href', ENQUIRY_TARGET );
		} );
	}

	/**
	 * The action's target has to exist. Checked once, on the page every band
	 * shares a footer with; `modals.spec.js` covers opening and closing it.
	 */
	test( 'the enquiry action resolves to a dialog in the page', async ( {
		page,
		visit,
		routes,
	} ) => {
		const target = routes.post( 'tour' );
		test.skip( ! target, 'No published tour' );

		await visit( target );

		await expect(
			page.locator( ENQUIRY_TARGET ).and( page.locator( 'dialog' ) )
		).toHaveCount( 1 );
	} );
} );
