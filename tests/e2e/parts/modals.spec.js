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
const { pinned } = require( '../fixtures/routes.js' );

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

			await expect(
				trigger,
				`modal trigger ${ index } declares aria-haspopup but no aria-controls`
			).toHaveAttribute( 'aria-controls', /\S/ );

			const controls = await trigger.getAttribute( 'aria-controls' );

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
			const id =
				( await dialog.getAttribute( 'id' ) ) || `index ${ index }`;

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
			for ( const ref of ( labelledBy || '' )
				.split( /\s+/ )
				.filter( Boolean ) ) {
				/**
				 * Compared as a plain id, not built into a selector — an id
				 * holding quotes or backslashes would otherwise need escaping.
				 */
				expect(
					await page.evaluate(
						( refId ) => null !== document.getElementById( refId ),
						ref
					),
					`dialog ${ id } is labelled by #${ ref }, which does not exist`
				).toBe( true );
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

		await expect( dialog, 'dialog stayed open after Escape' ).toBeHidden();
	} );
} );

/**
 * ASD-41 item 21.3 — the accommodation modal's content (M1). The 2019 modal is
 * a gallery slider with next/previous arrows, then the title, the meta info
 * and the description. The modal is opened from an itinerary stay on the
 * pinned tour; nothing in it is submitted.
 */
test.describe( 'Accommodation modal (2019 spec)', () => {
	test.beforeEach( async ( { page, visit } ) => {
		await page.setViewportSize( { width: 1280, height: 800 } );
		await visit( pinned( 'tour' ) );

		await page
			.locator( '.sd-itinerary a[href^="#to-modal-"]' )
			.first()
			.click();
	} );

	const DIALOG = 'dialog.wp-block-hm-popup[open]';

	// requirement: RQ-M1 / test-case: TC-M1-01
	test( 'shows the property title, its meta rows and a description', async ( {
		page,
	} ) => {
		const dialog = page.locator( DIALOG );
		await expect( dialog ).toBeVisible();

		await expect(
			dialog.getByRole( 'heading', { level: 2 } ),
			'the modal has no property title'
		).toHaveText( /\S/ );

		for ( const row of [
			/Price Rating:/,
			/Type:/,
			/Destination:/,
			/Rating:/,
		] ) {
			await expect(
				dialog.getByText( row ).first(),
				`no ${ row } meta row`
			).toBeVisible();
		}

		expect(
			(
				await dialog.locator( '.wp-block-post-excerpt' ).innerText()
			).split( /\s+/ ).length,
			'the description is a stub'
		).toBeGreaterThan( 15 );
	} );

	/**
	 * Live outranks the 2019 spec. The 2019 modal was a gallery slider with
	 * next/previous arrows. Live's accommodation modal (measured 2026-11-13 on
	 * the pinned tour and two others) has an empty `.sd-modal-slider` container
	 * with no images and no slider arrows, then the title, meta and
	 * description. Dev shows at most the single featured photograph, so the
	 * slider is a stale requirement and the test asserts no slider controls.
	 */
	// requirement: RQ-M1 / test-case: TC-M1-02
	test( 'has no slider arrows, as on live', async ( { page } ) => {
		const dialog = page.locator( DIALOG );
		await expect( dialog ).toBeVisible();

		await expect(
			dialog.getByRole( 'button', { name: /next|prev/i } ),
			'live has no carousel arrows in the accommodation modal'
		).toHaveCount( 0 );
	} );
} );
