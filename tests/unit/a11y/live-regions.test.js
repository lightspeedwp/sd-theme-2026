/**
 * The live-region helpers — proof that the harness reads what `speak()` writes.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const {
	LIVE_REGION_IDS,
	announcement,
	installWpA11yGlobal,
	resetLiveRegions,
	speak,
} = require( '../helpers/live-regions' );

describe( 'live regions', () => {
	beforeEach( () => {
		resetLiveRegions();
	} );

	it( 'creates both regions with the politeness their ids promise', () => {
		const polite = document.getElementById( LIVE_REGION_IDS.polite );
		const assertive = document.getElementById( LIVE_REGION_IDS.assertive );

		expect( polite ).not.toBeNull();
		expect( assertive ).not.toBeNull();
		expect( polite.getAttribute( 'aria-live' ) ).toBe( 'polite' );
		expect( assertive.getAttribute( 'aria-live' ) ).toBe( 'assertive' );
	} );

	it( 'reads back a polite announcement', () => {
		speak( 'Showing 12 tours' );

		expect( announcement() ).toBe( 'Showing 12 tours' );
		expect( announcement( 'assertive' ) ).toBe( '' );
	} );

	it( 'reads back an assertive announcement', () => {
		speak( 'Enquiry could not be sent', 'assertive' );

		expect( announcement( 'assertive' ) ).toBe(
			'Enquiry could not be sent'
		);
	} );

	it( 'treats a repeated message as the same words', () => {
		speak( 'Image 2 of 9' );
		speak( 'Image 2 of 9' );

		expect( announcement() ).toBe( 'Image 2 of 9' );
	} );

	it( 'puts the package behind window.wp.a11y for classic scripts', () => {
		installWpA11yGlobal();

		window.wp.a11y.speak( 'Filters applied' );

		expect( announcement() ).toBe( 'Filters applied' );
	} );
} );
