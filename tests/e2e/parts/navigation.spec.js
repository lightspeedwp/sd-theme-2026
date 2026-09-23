/**
 * Mega menu and mobile menu behaviour.
 *
 * Both are driven by the Interactivity API (Ollie's mega menu block and core's
 * responsive navigation container), so the assertions are on the state those
 * directives expose — `aria-expanded`, dialog visibility, focus — rather than on
 * CSS. That is also what a keyboard or screen-reader user actually depends on.
 *
 * The toggles are discovered from the page rather than hardcoded, so a new mega
 * menu is covered the day it is added.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );

const MEGA_MENU_TOGGLE = '.wp-block-ollie-mega-menu__toggle';

/**
 * The row a toggle belongs to — the thing a visitor actually points at.
 *
 * Ollie Menu Designer 0.3.x renders a mega menu that has a URL as a label link
 * plus a separate toggle button, and the header hides that button's chevron
 * (live has none), so the button is a zero-size box that `hover()` can never
 * reach. The menu opens on hover of the row, which is what a mouse does, so
 * the row is hovered and `aria-expanded` is still read off the toggle. Under
 * 0.2.x the toggle *was* the row's only child, and hovering the row is the
 * same gesture.
 *
 * @param {import('@playwright/test').Locator} toggle A mega-menu toggle.
 * @return {import('@playwright/test').Locator} Its `li.wp-block-ollie-mega-menu`.
 */
function rowOf( toggle ) {
	return toggle.locator( 'xpath=ancestor::li[contains(concat(" ", @class, " "), " wp-block-ollie-mega-menu ")][1]' );
}

/**
 * The row's visible label — the toggle's own text is empty when it holds only
 * a hidden chevron.
 *
 * @param {import('@playwright/test').Locator} toggle A mega-menu toggle.
 * @return {Promise<string>} Label text.
 */
async function labelOf( toggle ) {
	return ( await rowOf( toggle ).locator( '.wp-block-navigation-item__label' ).first().innerText() ).trim();
}

/**
 * Move the pointer to the bottom-left of the current viewport, clear of the
 * header. A fixed coordinate falls outside short viewports.
 *
 * @param {import('@playwright/test').Page} page
 */
async function parkPointer( page ) {
	const { height } = page.viewportSize() || { height: 600 };

	await page.mouse.move( 5, height - 5 );
}

test.describe( 'Mega menu', () => {
	test.beforeEach( async ( { visit, page } ) => {
		await visit( '/' );

		/**
		 * Park the pointer out of the header. These menus open on hover, so a
		 * cursor left sitting over the nav from a previous action makes the
		 * next assertion read the wrong state.
		 */
		await parkPointer( page );
	} );

	/**
	 * The interaction model, measured rather than assumed: the toggle opens on
	 * hover and from the keyboard, and Escape closes it while the toggle holds
	 * focus. A pointer click is deliberately not asserted — a synthetic click moves the pointer
	 * first, so hover opens the menu and the click immediately toggles it shut.
	 * That is Playwright's pointer behaviour, not a defect.
	 */
	test( 'every toggle opens its own dropdown on hover', async ( { page } ) => {
		const toggles = page.locator( MEGA_MENU_TOGGLE );
		const count = await toggles.count();

		expect(
			count,
			'no mega menu toggles found in the header'
		).toBeGreaterThan( 0 );

		for ( let index = 0; index < count; index++ ) {
			const toggle = toggles.nth( index );
			const label = await labelOf( toggle );
			const dropdownId = await toggle.getAttribute( 'aria-controls' );

			expect(
				dropdownId,
				`mega menu toggle "${ label }" has no aria-controls`
			).toBeTruthy();

			const dropdown = page.locator( `#${ dropdownId }` );

			await expect(
				dropdown,
				`dropdown #${ dropdownId } for "${ label }" is not in the DOM`
			).toBeAttached();

			await rowOf( toggle ).hover();

			await expect(
				toggle,
				`"${ label }" did not report itself expanded on hover`
			).toHaveAttribute( 'aria-expanded', 'true' );

			await expect(
				dropdown,
				`"${ label }" dropdown did not become visible`
			).toBeVisible();

			// Move away so the next iteration starts from a closed state.
			await parkPointer( page );

			await expect(
				toggle,
				`"${ label }" stayed open after the pointer left`
			).toHaveAttribute( 'aria-expanded', 'false' );
		}
	} );

	/**
	 * Two Ollie builds are in play. The local one opens on focus
	 * (`data-wp-on--focus="actions.openMenuOnFocus"`), and pressing Enter
	 * afterwards would toggle it straight back shut. Dev's has no focus handler
	 * and opens on Enter, the WAI-ARIA disclosure pattern. So: focus, and press
	 * Enter only if focus did not open it. Either way the keyboard must open it.
	 */
	test( 'a toggle opens from the keyboard and closes on Escape', async ( {
		page,
	} ) => {
		const toggle = page.locator( MEGA_MENU_TOGGLE ).first();
		const label = await labelOf( toggle );

		await toggle.focus();

		/**
		 * Give an open-on-focus handler a moment to run before deciding —
		 * reading once can catch it mid-update and Enter would then shut it.
		 */
		const openedOnFocus = await expect( toggle )
			.toHaveAttribute( 'aria-expanded', 'true', { timeout: 750 } )
			.then( () => true, () => false );

		if ( ! openedOnFocus ) {
			await page.keyboard.press( 'Enter' );
		}

		await expect(
			toggle,
			`"${ label }" did not open from the keyboard (focus, then Enter)`
		).toHaveAttribute( 'aria-expanded', 'true' );

		await page.keyboard.press( 'Escape' );

		await expect(
			toggle,
			`"${ label }" stayed open after Escape`
		).toHaveAttribute( 'aria-expanded', 'false' );
	} );

	test( 'hovering a second menu closes the first', async ( { page } ) => {
		const toggles = page.locator( MEGA_MENU_TOGGLE );

		test.skip(
			2 > ( await toggles.count() ),
			'Fewer than two mega menus to compare'
		);

		const first = toggles.nth( 0 );
		const second = toggles.nth( 1 );

		await rowOf( first ).hover();
		await expect( first ).toHaveAttribute( 'aria-expanded', 'true' );

		await rowOf( second ).hover();

		await expect(
			first,
			'two mega menus were open at once'
		).toHaveAttribute( 'aria-expanded', 'false' );
		await expect( second ).toHaveAttribute( 'aria-expanded', 'true' );
	} );

	test( 'dropdown links resolve', async ( { page } ) => {
		const toggle = page.locator( MEGA_MENU_TOGGLE ).first();
		const dropdownId = await toggle.getAttribute( 'aria-controls' );

		await rowOf( toggle ).hover();

		const links = page.locator( `#${ dropdownId } a[href]` );

		await expect(
			links,
			'mega menu dropdown has no links'
		).not.toHaveCount( 0 );

		const placeholders = await links.evaluateAll( ( nodes ) =>
			nodes
				.filter( ( node ) => {
					const href = ( node.getAttribute( 'href' ) || '' ).trim();
					return '' === href || '#' === href;
				} )
				.map( ( node ) => ( node.textContent || '' ).trim() )
		);

		expect(
			placeholders,
			`mega menu links with no destination: ${ placeholders.join( '; ' ) }`
		).toEqual( [] );
	} );
} );

test.describe( 'Mobile menu', () => {
	test.use( { viewport: { width: 390, height: 844 } } );

	test.beforeEach( async ( { visit } ) => {
		await visit( '/' );
	} );

	test( 'opens, traps focus, and closes on Escape', async ( { page } ) => {
		const opener = page
			.locator( '.wp-block-navigation__responsive-container-open' )
			.first();

		await expect( opener ).toBeVisible();
		await opener.click();

		const dialog = page
			.locator( '.wp-block-navigation__responsive-container.is-menu-open' )
			.first();

		await expect( dialog, 'mobile menu did not open' ).toBeVisible();

		/**
		 * Core marks the open container with `role="dialog"`. Focus must be
		 * inside it, or a keyboard user is tabbing through the page behind
		 * an overlay they cannot see past.
		 */
		await expect
			.poll(
				() => dialog.evaluate( ( node ) => node.contains( document.activeElement ) ),
				{ message: 'focus stayed outside the open mobile menu' }
			)
			.toBe( true );

		await page.keyboard.press( 'Escape' );

		await expect(
			dialog,
			'mobile menu stayed open after Escape'
		).not.toBeVisible();
	} );

	test( 'close button dismisses the menu', async ( { page } ) => {
		await page
			.locator( '.wp-block-navigation__responsive-container-open' )
			.first()
			.click();

		const dialog = page
			.locator( '.wp-block-navigation__responsive-container.is-menu-open' )
			.first();

		await expect( dialog ).toBeVisible();

		await dialog.getByRole( 'button', { name: 'Close menu' } ).first().click();

		await expect( dialog ).not.toBeVisible();
	} );
} );
