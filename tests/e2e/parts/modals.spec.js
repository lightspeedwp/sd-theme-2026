/**
 * Enquiry and content modals.
 *
 * These are Tour Operator's `<dialog>` popups, with sd-enhancements' ModalA11y
 * module adding the `aria-labelledby` and trigger semantics that upstream
 * strips. That module is the reason these specs exist: if it stops running, the
 * dialogs still open and look fine, and are silently unlabelled.
 *
 * Dev renders stub templates, so where a single carries no modal the spec skips
 * with a reason rather than failing — but the skip is worth reading, because on
 * a finished template it would be a defect.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );

/**
 * Content-modal triggers only.
 *
 * `aria-haspopup="dialog"` alone is too broad: core's responsive navigation
 * opener carries it too, and that button belongs to the mobile menu specs. The
 * `aria-controls` requirement and the `:not()` clause together select the Tour
 * Operator popups and nothing else.
 */
const MODAL_TRIGGER =
	'a[aria-haspopup="dialog"][aria-controls]:not(.wp-block-navigation__responsive-container-open), ' +
	'button[aria-haspopup="dialog"][aria-controls]:not(.wp-block-navigation__responsive-container-open)';

/**
 * Triggers that claim to open a dialog. Used to catch the ones that declare
 * `aria-haspopup` but never say what they control.
 */
const DIALOG_CLAIMANT =
	'a[aria-haspopup="dialog"]:not(.wp-block-navigation__responsive-container-open), ' +
	'button[aria-haspopup="dialog"]:not(.wp-block-navigation__responsive-container-open)';

test.describe( 'Modals', () => {
	test( 'every dialog trigger controls a dialog that exists', async ( {
		page,
		visit,
		routes,
	} ) => {
		const target = routes.post( 'tour' );
		test.skip( ! target, 'No published tour' );

		await visit( target );

		const triggers = page.locator( DIALOG_CLAIMANT );
		const count = await triggers.count();

		test.skip( 0 === count, `No modal triggers on ${ target }` );

		for ( let index = 0; index < count; index++ ) {
			const trigger = triggers.nth( index );
			const controls = await trigger.getAttribute( 'aria-controls' );

			expect(
				controls,
				`modal trigger ${ index } declares aria-haspopup but no aria-controls`
			).toBeTruthy();

			/**
			 * ModalA11y only claims `aria-controls` over a dialog that is
			 * actually present — so a dangling reference means the module
			 * did not run, or the dialog was not rendered.
			 */
			await expect(
				page.locator( `#${ controls }` ),
				`aria-controls="${ controls }" points at nothing`
			).toBeAttached();
		}
	} );

	test( 'every dialog has an accessible name', async ( {
		page,
		visit,
		routes,
	} ) => {
		const target = routes.post( 'tour' );
		test.skip( ! target, 'No published tour' );

		await visit( target );

		const dialogs = page.locator( 'dialog' );
		const count = await dialogs.count();

		test.skip( 0 === count, `No dialogs on ${ target }` );

		const unnamed = [];

		for ( let index = 0; index < count; index++ ) {
			const dialog = dialogs.nth( index );
			const id = ( await dialog.getAttribute( 'id' ) ) || `index ${ index }`;

			const labelledBy = await dialog.getAttribute( 'aria-labelledby' );
			const label = await dialog.getAttribute( 'aria-label' );

			if ( ! labelledBy && ! label ) {
				unnamed.push( id );
				continue;
			}

			/**
			 * An `aria-labelledby` pointing at a missing element is worse
			 * than none — it reads as an empty name.
			 */
			if ( labelledBy ) {
				await expect(
					page.locator( `#${ labelledBy }` ),
					`dialog ${ id } is labelled by #${ labelledBy }, which does not exist`
				).toBeAttached();
			}
		}

		expect(
			unnamed,
			`dialogs with no accessible name: ${ unnamed.join( ', ' ) } — ` +
				'is the sd-enhancements ModalA11y module active?'
		).toEqual( [] );
	} );

	test( 'a modal opens and closes on Escape', async ( {
		page,
		visit,
		routes,
	} ) => {
		const target = routes.post( 'tour' );
		test.skip( ! target, 'No published tour' );

		await visit( target );

		const trigger = page.locator( MODAL_TRIGGER ).first();
		test.skip(
			0 === ( await page.locator( MODAL_TRIGGER ).count() ),
			`No modal triggers on ${ target }`
		);

		const controls = await trigger.getAttribute( 'aria-controls' );
		const dialog = page.locator( `#${ controls }` );

		await trigger.click();

		await expect(
			dialog,
			'dialog did not become visible after its trigger was clicked'
		).toBeVisible();

		/**
		 * `showModal()` puts the dialog in the top layer and makes the rest
		 * of the document inert. `open` is the attribute that reflects it.
		 */
		await expect( dialog ).toHaveAttribute( 'open', '' );

		await page.keyboard.press( 'Escape' );

		await expect(
			dialog,
			'dialog stayed open after Escape'
		).not.toBeVisible();
	} );
} );
