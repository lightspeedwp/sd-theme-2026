/**
 * Enquiry form field sets, against the 2019 functional spec.
 *
 * ASD-41 item 21.3. forms/enquiry.spec.js proves a form renders and that an
 * empty submit is blocked, but it never names a field. This file does: each
 * label, type and required-ness the 2019 spec lists for the Tour Enquire Now,
 * Specials Enquiry and Contact Us forms.
 *
 * ⚠️ THIS SUITE NEVER SUBMITS. Nothing here clicks a submit control, empty or
 * otherwise. The forms feed Salesforce through CRM Perks (ASD-30). Fields are
 * only inspected, never filled.
 *
 * Graded against the live site, which outranks the 2019 spec (AGENTS.md,
 * "Source-of-truth precedence"). Measured 2026-11-13:
 *
 *   - Live's tour modal (form 1) has Name, Surname, Email, Phone Number and
 *     Comments, and no Arrive, Depart, Adults or Children field. Dev matches,
 *     so those four 2019 fields are not asserted.
 *   - Live's contact form (form 7) leaves Phone Number and the travel-plans box
 *     optional (Name, Surname and Email are required). Dev matches.
 *
 * Skipped on purpose: the Destination Enquiry (F3) and Accommodation Enquiry
 * (F4) forms. Dev serves the same modal form 1 on tours, accommodation and
 * destinations, and whether distinct forms are wanted is an open decision.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );

/**
 * Open a theme modal by the id its triggers point at, and return the form in it.
 *
 * Triggers are `<a aria-controls>`; a page carries several, so the first
 * visible one is used. The dialog is opened, never submitted.
 *
 * @param {import('@playwright/test').Page} page    Page.
 * @param {string}                          modalId Dialog id, without `#`.
 * @return {Promise<import('@playwright/test').Locator|null>} The form, or null when no trigger exists.
 */
async function openModalForm( page, modalId ) {
	const trigger = page
		.locator( `[aria-controls="${ modalId }"]` )
		.filter( { visible: true } )
		.first();

	if ( 0 === ( await trigger.count() ) ) {
		return null;
	}

	await trigger.click();

	const dialog = page.locator( `#${ modalId }` );
	await expect( dialog, `${ modalId } did not open` ).toBeVisible();

	return dialog.locator( '.gform_wrapper form' );
}

/**
 * The form's real fields, with Gravity Forms' honeypot excluded.
 *
 * The honeypot is labelled "Email" or "Company" and would make `getByLabel`
 * ambiguous; it is not a field a visitor sees.
 *
 * @param {import('@playwright/test').Locator} form Form.
 * @return {import('@playwright/test').Locator} Visible field wrappers.
 */
function realFields( form ) {
	return form.locator( '.gfield:not(.gform_validation_container)' );
}

/**
 * The control for a field, by label.
 *
 * Date fields carry a second control, the calendar button, whose accessible
 * name also starts with the field's label. Narrowing to form controls keeps
 * the count meaning "one field".
 *
 * @param {import('@playwright/test').Locator} form  Form.
 * @param {RegExp}                             label Label pattern.
 * @return {import('@playwright/test').Locator} The input, select or textarea.
 */
function fieldInput( form, label ) {
	return realFields( form )
		.getByLabel( label )
		.and( form.page().locator( 'input, select, textarea' ) );
}

/**
 * The tour form (F5). The 2019 spec lists Arrive, Depart, Adults and Children
 * under 12, but live outranks the 2019 spec and live's form 1 carries none of
 * them; it is a Name / Email / Phone / Comments enquiry. These are the fields
 * live and dev share.
 * Label patterns are loose on wording and strict on meaning.
 */
const TOUR_FIELDS = [
	{ name: 'Name', label: /^name/i },
	{ name: 'Phone Number', label: /^phone/i },
	{ name: 'Comments', label: /^comments/i },
];

test.describe( 'Tour Enquire Now fields', () => {
	// requirement: RQ-F5 / test-case: TC-F5-01
	for ( const field of TOUR_FIELDS ) {
		test( `the tour enquiry form has a "${ field.name }" field`, async ( {
			page,
			visit,
			routes,
		} ) => {
			const target = routes.post( 'tour' );
			test.skip( ! target, 'No published tour' );

			await visit( target );

			const form = await openModalForm( page, 'to-modal-modal-enquiry' );
			test.skip( ! form, 'No enquiry trigger on this tour' );

			await expect(
				fieldInput( form, field.label ),
				`live's Tour Enquire Now has a "${ field.name }" field, but the form served on ${ target } has none`
			).toHaveCount( 1 );
		} );
	}

	// requirement: RQ-F5 / test-case: TC-F5-02
	test( 'the tour enquiry form asks for the visitor’s email', async ( {
		page,
		visit,
		routes,
	} ) => {
		const target = routes.post( 'tour' );
		test.skip( ! target, 'No published tour' );

		await visit( target );

		const form = await openModalForm( page, 'to-modal-modal-enquiry' );
		test.skip( ! form, 'No enquiry trigger on this tour' );

		/**
		 * Salesforce maps Email, First Name and Last Name for every lead, so
		 * the form must be able to collect an email whatever else changes.
		 */
		await expect(
			realFields( form ).locator( 'input[type="email"]' )
		).toHaveCount( 1 );
	} );

	// requirement: RQ-F4 / test-case: TC-F4-01
	test.skip( 'the accommodation page serves its own Accommodation Enquiry form (F4)', () => {
		// Skipped: open decision on whether distinct Destination and
		// Accommodation enquiry forms exist. Dev serves modal form 1 on these.
	} );

	// requirement: RQ-F3 / test-case: TC-F3-01
	test.skip( 'the destination page serves its own Destination Enquiry form (F3)', () => {
		// Skipped: same open decision as F4.
	} );
} );

/**
 * The specials form (F6). The 2019 spec lists Email, First Name, Last Name,
 * Contact Number, Arrival Date, Departure Date, Message. The build labels
 * them Email, Name, Surname, Phone Number, Arrival Date, Departure Date and
 * Travel Plans, so the patterns accept either wording.
 */
const SPECIAL_FIELDS = [
	{ name: 'Email', label: /^email/i, type: 'email' },
	{ name: 'First Name', label: /^(first )?name\s*\*?$/i },
	{ name: 'Last Name', label: /^(last name|surname)/i },
	{ name: 'Contact Number', label: /^(contact|phone)( number)?/i },
	{ name: 'Arrival Date', label: /^arrival/i },
	{ name: 'Departure Date', label: /^departure/i },
	{ name: 'Message', label: /^(message|travel plans)/i },
];

test.describe( 'Specials Enquiry fields', () => {
	// requirement: RQ-F6 / test-case: TC-F6-01
	for ( const field of SPECIAL_FIELDS ) {
		test( `the specials form has a "${ field.name }" field`, async ( {
			page,
			visit,
		} ) => {
			await visit( '/specials/' );

			const form = await openModalForm( page, 'to-modal-modal-special' );
			test.skip( ! form, 'No Book Special trigger on /specials/' );

			const input = fieldInput( form, field.label );

			await expect(
				input,
				`the 2019 spec lists "${ field.name }" on Specials Enquiry`
			).toHaveCount( 1 );

			if ( field.type ) {
				await expect(
					input,
					`"${ field.name }" is not a ${ field.type } input`
				).toHaveAttribute( 'type', field.type );
			}
		} );
	}
} );

/**
 * The contact form (F2). The 2019 spec stars every field, but live outranks
 * the 2019 spec and live (form 7, measured 2026-11-13) requires only Name,
 * Surname and Email; Phone Number and the travel-plans box are optional.
 */
const CONTACT_FIELDS = [
	{ name: 'Name', label: /^name/i, required: true },
	{ name: 'Surname', label: /^surname/i, required: true },
	{ name: 'Phone Number', label: /^phone/i, required: false },
	{ name: 'Email Address', label: /^email/i, type: 'email', required: true },
	{
		name: 'Please tell us more about your travel plans',
		label: /travel plans/i,
		required: false,
	},
];

test.describe( 'Contact Us fields', () => {
	for ( const field of CONTACT_FIELDS ) {
		// requirement: RQ-F2 / test-case: TC-F2-01
		test( `the contact form has a ${
			field.required ? 'required' : 'optional'
		} "${ field.name }" field`, async ( { page, visit } ) => {
			await visit( '/contact/' );

			/**
			 * Form 7. The page also carries the newsletter form (12) inside
			 * its popup, so the form is addressed by id to keep the two apart.
			 */
			const form = page.locator( '#gform_7' );
			test.skip( 0 === ( await form.count() ), 'No contact form' );

			const input = fieldInput( form, field.label );

			await expect(
				input,
				`the contact form has no "${ field.name }" field`
			).toHaveCount( 1 );

			if ( field.type ) {
				await expect( input ).toHaveAttribute( 'type', field.type );
			}

			if ( field.required ) {
				await expect(
					input,
					`live requires "${ field.name }", but the build leaves it optional`
				).toHaveAttribute( 'aria-required', 'true' );
			} else {
				await expect(
					input,
					`live leaves "${ field.name }" optional, but the build requires it`
				).not.toHaveAttribute( 'aria-required', 'true' );
			}
		} );
	}
} );
