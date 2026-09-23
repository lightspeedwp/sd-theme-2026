/**
 * Keyboard operation.
 *
 * axe cannot press Tab. Everything below is the part of accessibility that only
 * a real interaction test reaches: focus visibility, focus order, focus return
 * after a dialog closes, and the skip link.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );

test.describe( 'Keyboard', () => {
	test( 'the first tab stop reaches a skip link that works', async ( {
		page,
		visit,
	} ) => {
		await visit( '/' );

		await page.keyboard.press( 'Tab' );

		const focused = page.locator( ':focus' );
		const href = await focused.getAttribute( 'href' );

		expect(
			href,
			'first tab stop is not a link — expected a skip link'
		).toBeTruthy();
		expect(
			href.startsWith( '#' ),
			`first tab stop points at ${ href }, not an in-page target`
		).toBe( true );

		/**
		 * A skip link pointing at an id that is not on the page is the usual
		 * way this breaks — it looks right and goes nowhere.
		 */
		await expect(
			page.locator( href ),
			`skip link target ${ href } does not exist`
		).toBeAttached();
	} );

	test( 'focused elements have a visible focus indicator', async ( {
		page,
		visit,
	} ) => {
		await visit( '/' );

		const link = page.locator( 'header.wp-block-template-part a[href]' ).first();
		await link.focus();

		/**
		 * `outline: none` with nothing put back is the single most common
		 * focus regression, and it is invisible until someone tries to use
		 * the site without a mouse.
		 */
		const indicator = await link.evaluate( ( node ) => {
			const style = window.getComputedStyle( node );

			return {
				outlineStyle: style.outlineStyle,
				outlineWidth: style.outlineWidth,
				boxShadow: style.boxShadow,
			};
		} );

		const hasOutline =
			'none' !== indicator.outlineStyle &&
			'0px' !== indicator.outlineWidth;
		const hasShadow = 'none' !== indicator.boxShadow;

		expect(
			hasOutline || hasShadow,
			`focused header link has no visible focus indicator: ${ JSON.stringify(
				indicator
			) }`
		).toBe( true );
	} );

	test( 'mega menus are reachable and operable by keyboard', async ( {
		page,
		visit,
	} ) => {
		await visit( '/' );

		const toggle = page
			.locator( '.wp-block-ollie-mega-menu__toggle' )
			.first();

		test.skip(
			0 === ( await page.locator( '.wp-block-ollie-mega-menu__toggle' ).count() ),
			'No mega menu toggles'
		);

		/**
		 * Two Ollie builds are in play. The local one opens on focus (the
		 * `openMenuOnFocus` directive), and pressing Enter afterwards would
		 * toggle it straight back shut. Dev's has no focus handler and opens on
		 * Enter, the WAI-ARIA disclosure pattern. So: focus, and press Enter
		 * only if focus did not open it. Either way the keyboard must open it.
		 */
		await toggle.focus();
		await expect( toggle ).toBeFocused();

		if ( 'true' !== ( await toggle.getAttribute( 'aria-expanded' ) ) ) {
			await page.keyboard.press( 'Enter' );
		}

		await expect(
			toggle,
			'the mega menu did not open from the keyboard (focus, then Enter)'
		).toHaveAttribute( 'aria-expanded', 'true' );

		const dropdownId = await toggle.getAttribute( 'aria-controls' );

		await expect(
			page.locator( `#${ dropdownId }` ),
			'mega menu reported itself expanded but its dropdown is not visible'
		).toBeVisible();

		await page.keyboard.press( 'Escape' );

		await expect(
			toggle,
			'Escape did not close the mega menu'
		).toHaveAttribute( 'aria-expanded', 'false' );
	} );

	test( 'closing a modal returns focus to its trigger', async ( {
		page,
		visit,
		routes,
	} ) => {
		const target = routes.post( 'tour' );
		test.skip( ! target, 'No published tour' );

		await visit( target );

		const selector =
			'a[aria-haspopup="dialog"][aria-controls]:not(.wp-block-navigation__responsive-container-open), ' +
			'button[aria-haspopup="dialog"][aria-controls]:not(.wp-block-navigation__responsive-container-open)';

		test.skip(
			0 === ( await page.locator( selector ).count() ),
			`No modal triggers on ${ target }`
		);

		const trigger = page.locator( selector ).first();

		await trigger.focus();
		await page.keyboard.press( 'Enter' );

		const controls = await trigger.getAttribute( 'aria-controls' );
		const dialog = page.locator( `#${ controls }` );

		await expect( dialog ).toBeVisible();

		await page.keyboard.press( 'Escape' );

		await expect( dialog ).not.toBeVisible();
		await expect(
			trigger,
			'focus did not return to the modal trigger'
		).toBeFocused();
	} );
} );
