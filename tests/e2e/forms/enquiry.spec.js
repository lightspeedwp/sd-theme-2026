/**
 * The enquiry form.
 *
 * ⚠️ THIS SUITE NEVER COMPLETES A VALID SUBMISSION.
 *
 * The enquiry path is wired to Gravity Forms and on to Salesforce through the
 * CRM Perks add-on. A successful submission creates a real lead in a real CRM.
 * Everything here therefore stops at the submit boundary: the form renders, its
 * required fields are enforced, and the context the enquiry carries is present.
 *
 * Submitting an empty form is safe — Gravity Forms fails validation before any
 * feed runs, so no entry is created and nothing reaches Salesforce.
 *
 * End-to-end delivery into Salesforce is being tested separately by a
 * colleague. **That work still needs to be confirmed done**; this file does not
 * cover it and must not be extended to.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );
const { mainContent } = require( '../utils/page-contract.js' );

/**
 * What an enquiry control is called. SD's own label is "Send an Email" /
 * "Send us an Email" — on live as well as dev — so `email` has to be in here;
 * without it the only thing that ever matched was the header's "Contact Us".
 */
const ENQUIRY_LABEL =
	/enquir|contact|book (?:now|a|this|your)\b|send (?:us )?an? e-?mail|e-?mail us/i;

/**
 * Gravity Forms' own wrapper. There is no accessible handle for "the form
 * plugin's container", so this is one of the few structural selectors in the
 * suite — see the org locator rule, which permits it where no accessible
 * locator reaches.
 */
const GF_WRAPPER = '.gform_wrapper';

test.describe( 'Enquiry form', () => {
	test( 'a tour single offers an enquiry route @responsive @smoke', async ( {
		page,
		visit,
		routes,
	} ) => {
		const target = routes.post( 'tour' );
		test.skip( ! target, 'No published tour' );

		await visit( target );

		/**
		 * Either an in-page form or a trigger that opens one. Both are valid —
		 * the question is whether a visitor on a tour page can start an
		 * enquiry at all, which is the site's commercial function.
		 */
		/**
		 * Scoped to `main`. Page-wide, the header navigation's "Contact Us"
		 * link satisfied this on every tour, so it could not fail.
		 */
		const main = mainContent( page );
		const inPageForm = main.locator( GF_WRAPPER );
		const trigger = main
			.getByRole( 'link', { name: ENQUIRY_LABEL } )
			.or( main.getByRole( 'button', { name: ENQUIRY_LABEL } ) );

		const routesToEnquiry =
			0 < ( await inPageForm.count() ) || 0 < ( await trigger.count() );

		expect(
			routesToEnquiry,
			`${ target } offers no way to enquire — no Gravity Form and no ` +
				'enquiry trigger. This is the commercial path of the site.'
		).toBe( true );
	} );

	test( 'the enquiry modal opens and contains a form', async ( {
		page,
		visit,
		routes,
	} ) => {
		const target = routes.post( 'tour' );
		test.skip( ! target, 'No published tour' );

		await visit( target );

		const trigger = page
			.locator(
				'a[aria-haspopup="dialog"][aria-controls]:not(.wp-block-navigation__responsive-container-open), ' +
					'button[aria-haspopup="dialog"][aria-controls]:not(.wp-block-navigation__responsive-container-open)'
			)
			.filter( { hasText: ENQUIRY_LABEL } )
			.first();

		test.skip(
			0 === ( await trigger.count() ),
			'No enquiry modal trigger on this tour'
		);

		await trigger.click();

		const controls = await trigger.getAttribute( 'aria-controls' );
		const dialog = page.locator( `#${ controls }` );

		await expect( dialog, 'the enquiry modal did not open' ).toBeVisible();

		await expect(
			dialog.locator( GF_WRAPPER ),
			'the enquiry modal opened but contains no form'
		).toHaveCount( 1 );
	} );

	test( 'the contact form enforces its required fields', async ( {
		page,
		visit,
	} ) => {
		await visit( '/contact/' );

		const form = page.locator( GF_WRAPPER ).first();

		test.skip(
			0 === ( await form.count() ),
			'No Gravity Form on /contact/'
		);

		const submit = form.locator( 'input[type="submit"], button[type="submit"]' ).first();

		test.skip(
			0 === ( await submit.count() ),
			'Contact form has no submit control'
		);

		/**
		 * Guard before the empty submit. With no required field there is
		 * nothing to stop the POST, and the Salesforce feed would run.
		 */
		const requiredFields = form.locator(
			'.gfield_contains_required, .gfield--required, [aria-required="true"], [required]'
		);

		expect(
			await requiredFields.count(),
			'the contact form has no required fields — not submitting it empty, the Salesforce feed would run'
		).toBeGreaterThan( 0 );

		/**
		 * Submitting empty. Gravity Forms rejects this at validation, before
		 * any feed runs — no entry, nothing to Salesforce. Do not replace this
		 * with valid data.
		 */
		await submit.click();

		/**
		 * Either the browser blocked it on a `required` attribute and we never
		 * navigated, or Gravity Forms re-rendered with its validation
		 * messages. Both are correct behaviour; a submission that goes through
		 * with no data is not.
		 */
		const validationMessages = form.locator(
			'.gfield_error, .validation_message, .gform_validation_errors'
		);

		const stillOnForm = 0 < ( await page.locator( GF_WRAPPER ).count() );

		expect(
			stillOnForm,
			'an empty submission navigated away from the form — required ' +
				'fields are not being enforced'
		).toBe( true );

		/**
		 * Polled, not read once. The form is `novalidate` and posts through
		 * Gravity Forms' AJAX iframe, so the validation markup arrives some
		 * time after the click — reading it immediately always sees none.
		 */
		await expect
			.poll(
				async () =>
					0 < ( await validationMessages.count() ) ||
					0 <
						( await form
							.locator( 'input:invalid, textarea:invalid, select:invalid' )
							.count() ),
				{
					message:
						'an empty submission produced no validation feedback at all',
					timeout: 15 * 1000,
				}
			)
			.toBe( true );
	} );

	test( 'the enquiry page leaks no integration credentials into the markup', async ( {
		page,
		visit,
	} ) => {
		await visit( '/contact/' );

		const html = await page.content();

		/**
		 * Production Salesforce credentials are stored in plaintext in
		 * wp_options on this install (a known, off-project security item). The
		 * risk this guards is one of them reaching the rendered page.
		 *
		 * Deliberately reports only the NAME of what matched. Never echo a
		 * credential into a test report, a CI log or a chat — AGENTS.md is
		 * explicit, and a failing assertion message is exactly the kind of
		 * place one ends up pasted from.
		 */
		const markers = [
			{ name: 'Salesforce settings blob', pattern: /gf_salesforce_settings/i },
			{ name: 'Salesforce client secret', pattern: /client[_-]?secret/i },
			{ name: 'Salesforce refresh token', pattern: /refresh[_-]?token/i },
			{ name: 'Salesforce security token', pattern: /security[_-]?token/i },
			{ name: 'generic API key assignment', pattern: /api[_-]?key["']?\s*[:=]\s*["'][A-Za-z0-9_\-]{16,}/i },
		];

		const found = markers
			.filter( ( marker ) => marker.pattern.test( html ) )
			.map( ( marker ) => marker.name );

		expect(
			found,
			'Possible credential material in the rendered markup: ' +
				found.join( ', ' ) +
				'. Inspect the page source directly — the value is deliberately ' +
				'not reproduced here.'
		).toEqual( [] );
	} );
} );
