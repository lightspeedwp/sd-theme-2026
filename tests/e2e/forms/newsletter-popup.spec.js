/**
 * The popup newsletter form, against the 2019 functional spec (F1).
 *
 * ASD-41 item 21.3. The 2019 spec: "Popup Newsletter form: Name*, Email*,
 * redirect". On dev it is a Popup Maker popup (`newsletter-popup`) holding
 * Gravity Form 12, rendered on every page in the footer area.
 *
 * ⚠️ THIS SUITE NEVER SUBMITS. Form 12 posts to Salesforce and Pardot (ASD-30).
 * The popup is opened and closed; the form is only inspected. The redirect
 * after submission cannot be proven without a submission, so it is skipped.
 *
 * Graded against the live site, which outranks the 2019 spec (AGENTS.md,
 * "Source-of-truth precedence"). Measured 2026-11-13, live and dev agree:
 *
 *   - Name and Email are optional (no `aria-required`), though the 2019 spec
 *     stars both.
 *   - The popup's only trigger is a bare `click_open`, and no element on the
 *     page carries the matching `popmake-` class, so nothing opens it. Dev
 *     reproduces that configuration; it is not a regression.
 *
 * Esc is not asserted: the popup is configured with `esc_press: false`.
 * Popup Maker disables this popup on mobile, so the suite is desktop only.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );

/**
 * The popup that holds a Gravity Form, found by content rather than id. The
 * only other popup on the site is a "Send us an email" lightbox with no form.
 *
 * @param {import('@playwright/test').Page} page Page.
 * @return {import('@playwright/test').Locator} The newsletter popup.
 */
function newsletterPopup( page ) {
	return page
		.locator( '.pum[role="dialog"]' )
		.filter( { has: page.locator( '.gform_wrapper' ) } )
		.first();
}

/**
 * Open the popup the way Popup Maker's own API does, since the page offers a
 * visitor no control for it. Never touches the form.
 *
 * @param {import('@playwright/test').Page} page Page.
 * @return {Promise<import('@playwright/test').Locator>} The open popup.
 */
async function openNewsletterPopup( page ) {
	const popup = newsletterPopup( page );

	test.skip(
		0 === ( await popup.count() ),
		'No newsletter popup is configured on dev'
	);

	const id = await popup.evaluate( ( el ) =>
		Number( el.id.replace( 'pum-', '' ) )
	);

	await page.waitForFunction( () => window.PUM && window.PUM.open );
	await page.evaluate( ( popupId ) => window.PUM.open( popupId ), id );

	await expect( popup, 'the newsletter popup did not open' ).toBeVisible();

	return popup;
}

test.describe( 'Popup newsletter form', () => {
	test.beforeEach( async ( { visit } ) => {
		await visit( '/' );
	} );

	// requirement: RQ-F1 / test-case: TC-F1-01
	test( 'opens as a modal dialog', async ( { page } ) => {
		const popup = await openNewsletterPopup( page );

		await expect( popup ).toHaveAttribute( 'role', 'dialog' );
		await expect( popup ).toHaveAttribute( 'aria-modal', 'true' );
	} );

	// requirement: RQ-F1 / test-case: TC-F1-02
	test( 'has a Name field and an Email field', async ( { page } ) => {
		const popup = await openNewsletterPopup( page );
		const fields = popup.locator(
			'.gfield:not(.gform_validation_container)'
		);

		await expect(
			fields.getByLabel( /^name/i ),
			'no Name field in the newsletter popup'
		).toHaveCount( 1 );

		const email = fields.getByLabel( /^email/i );

		await expect(
			email,
			'no Email field in the newsletter popup'
		).toHaveCount( 1 );

		await expect(
			email,
			'the Email field is not an email input'
		).toHaveAttribute( 'type', 'email' );
	} );

	// requirement: RQ-F1 / test-case: TC-F1-03
	for ( const label of [ /^name/i, /^email/i ] ) {
		test( `${ label } is optional, as on live`, async ( { page } ) => {
			const popup = await openNewsletterPopup( page );

			// Live outranks the 2019 spec; live's form 12 leaves both optional.
			await expect(
				popup
					.locator( '.gfield:not(.gform_validation_container)' )
					.getByLabel( label ),
				'live leaves this field optional, but the build requires it'
			).not.toHaveAttribute( 'aria-required', 'true' );
		} );
	}

	// requirement: RQ-F1 / test-case: TC-F1-04
	test( 'closes from an accessibly named Close button', async ( {
		page,
	} ) => {
		const popup = await openNewsletterPopup( page );

		await popup.getByRole( 'button', { name: 'Close' } ).click();

		await expect(
			popup,
			'the Close button did not dismiss the popup'
		).toBeHidden();
	} );

	/**
	 * Live outranks the 2019 spec, and live's popup 50229 has only a bare
	 * `click_open` trigger with no `popmake-` element pointing at it, exactly
	 * as dev does. So this asserts the shared configuration (a popup holding
	 * the form, opened by Popup Maker's `click_open`) rather than reachability.
	 */
	// requirement: RQ-F1 / test-case: TC-F1-05
	test( 'is configured as on live: a click_open popup with the form', async ( {
		page,
	} ) => {
		const popup = newsletterPopup( page );

		await expect( popup, 'no newsletter popup' ).toHaveCount( 1 );

		const types = await popup.evaluate( ( el ) =>
			( JSON.parse( el.dataset.popmake || '{}' ).triggers || [] ).map(
				( trigger ) => trigger.type
			)
		);

		expect( types, 'the popup lost its click_open trigger' ).toContain(
			'click_open'
		);
	} );

	// requirement: RQ-F1 / test-case: TC-F1-06
	test.skip( 'redirects after a valid submission', () => {
		// Skipped: needs a real submission, which reaches Salesforce (ASD-30).
	} );
} );
