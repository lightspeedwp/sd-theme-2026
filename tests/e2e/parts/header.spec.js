/**
 * Header template part.
 *
 * The header is the one part present on every template, so a regression here is
 * a sitewide regression. Locators follow the org standard: accessible names
 * first, structural block classes only where no accessible handle exists.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );

test.describe( 'Header', () => {
	test.beforeEach( async ( { visit } ) => {
		await visit( '/' );
	} );

	/**
	 * Desktop only, deliberately not tagged `@responsive`. At mobile widths the
	 * primary navigation is CSS-hidden and so leaves the accessibility tree,
	 * which is correct — the mobile menu replaces it, and that path has its own
	 * specs in navigation.spec.js. A role-based locator will not match a hidden
	 * element, and it should not.
	 */
	test( 'renders the primary navigation', async ( { page } ) => {
		const header = page.locator( 'header.wp-block-template-part' );

		await expect( header ).toBeVisible();

		/**
		 * Named landmark rather than a class: the accessible name is the
		 * contract a screen-reader user actually experiences.
		 */
		await expect(
			header.getByRole( 'navigation', { name: 'SD Main Navigation' } )
		).toBeAttached();
	} );

	test( 'links home from the site logo or title', async ( { page } ) => {
		const header = page.locator( 'header.wp-block-template-part' );
		const homeLink = header.locator( 'a[rel="home"], .wp-block-site-logo a' ).first();

		await expect( homeLink ).toBeAttached();

		const href = await homeLink.getAttribute( 'href' );
		expect( href, 'home link has no href' ).toBeTruthy();
	} );

	test( 'exposes a search control', async ( { page } ) => {
		const header = page.locator( 'header.wp-block-template-part' );

		await expect(
			header.locator( '.wp-block-search' ).first()
		).toBeAttached();
	} );

	/**
	 * Responsive behaviour is an adaptation, not a bespoke mobile design — so
	 * the assertion is that the mobile affordance appears and the desktop one
	 * yields, not that it matches a separate comp.
	 */
	test( 'swaps to a mobile menu trigger at small widths', async ( { page } ) => {
		await page.setViewportSize( { width: 390, height: 844 } );

		const opener = page
			.locator( '.wp-block-navigation__responsive-container-open' )
			.first();

		await expect( opener ).toBeVisible();
		await expect( opener ).toHaveAttribute( 'aria-haspopup', 'dialog' );
	} );
} );

/**
 * The header's top-level bands that are actually rendered at this width.
 *
 * The pattern holds two groups — "Header - Desktop" and "Header - Mobile" — and
 * Block Visibility shows exactly one of them per breakpoint. Whether the plugin
 * drops the hidden one from the DOM or hides it with CSS is its business, so
 * "rendered" here means it has a layout box.
 *
 * @param {import('@playwright/test').Page} page
 * @return {Promise<Array<{position: string, top: number, bottom: number}>>}
 */
async function renderedHeaderBands( page ) {
	return page
		.locator( 'header.wp-block-template-part > .wp-block-group' )
		.evaluateAll( ( nodes ) =>
			nodes
				.filter( ( node ) => node.getClientRects().length > 0 )
				.map( ( node ) => {
					const rect = node.getBoundingClientRect();
					return {
						position: getComputedStyle( node ).position,
						top: rect.top,
						bottom: rect.bottom,
					};
				} )
		);
}

/**
 * Desktop header — sticky, with a Call Us pop-out.
 *
 * Breakpoints: 1280px is above Block Visibility's `large` on both local (1200)
 * and dev (992), so the desktop band is the one rendered in either environment.
 */
test.describe( 'Header — desktop', () => {
	test.use( { viewport: { width: 1280, height: 800 } } );

	test.beforeEach( async ( { visit } ) => {
		await visit( '/' );
	} );

	test( 'renders one band, and it sticks to the top on scroll', async ( { page } ) => {
		const bands = await renderedHeaderBands( page );

		expect( bands, 'expected exactly one rendered header band' ).toHaveLength( 1 );
		expect( bands[ 0 ].position ).toBe( 'sticky' );

		await page.mouse.wheel( 0, 1200 );
		await page.waitForFunction( () => window.scrollY > 600 );

		const [ stuck ] = await renderedHeaderBands( page );
		expect( Math.round( stuck.top ), 'desktop header scrolled away' ).toBe( 0 );
	} );

	/**
	 * "The logo sits a little high and is cut off" — the regression this guards
	 * is any part of the mark falling outside the band that paints it.
	 */
	test( 'the logo sits wholly inside the header band', async ( { page } ) => {
		const [ band ] = await renderedHeaderBands( page );
		const logo = await page
			.locator( 'header.wp-block-template-part .wp-block-site-logo img' )
			.filter( { visible: true } )
			.first()
			.boundingBox();

		expect( logo, 'no visible site logo in the header' ).toBeTruthy();
		expect( logo.y ).toBeGreaterThanOrEqual( band.top );
		expect( logo.y + logo.height ).toBeLessThanOrEqual( band.bottom );
	} );

	/**
	 * Computed styles are read without opening the pop-out: Ollie closes its
	 * panel with opacity and visibility, which leave the box's own radius and
	 * borders resolvable. Skips where menu 65909 does not exist (local), since
	 * the navigation then falls back to a page list with no pop-out at all.
	 */
	test( 'the Call Us pop-out is square-cornered, with no rules between the numbers', async ( {
		page,
	} ) => {
		const panel = page
			.locator( '.is-style-call-us-navigation .wp-block-ollie-mega-menu__menu-container' )
			.first();

		test.skip(
			0 === ( await panel.count() ),
			'Call Us menu (wp_navigation 65909) is not in this environment'
		);

		await expect( panel ).toHaveCSS( 'border-radius', '0px' );

		const ruled = await panel
			.locator( '.wp-block-group' )
			.evaluateAll( ( rows ) =>
				rows
					.filter( ( row ) => '0px' !== getComputedStyle( row ).borderTopWidth )
					.map( ( row ) => ( row.textContent || '' ).trim().slice( 0, 40 ) )
			);

		expect( ruled, `rows still carrying a top rule: ${ ruled.join( '; ' ) }` ).toEqual( [] );
	} );

	/**
	 * Live's header nav has no chevrons. Ollie Menu Designer 0.3.x moved the
	 * mega-menu chevron out of the link into a sibling toggle button, where the
	 * block style's hide rule no longer reached it — so it reappeared beside
	 * each label, outside the hover underline. Both markups are covered: the
	 * icon inside the link (0.2.x, or a mega menu with no URL) and the icon in
	 * the toggle button (0.3.x with a URL).
	 */
	test.describe( 'mega-menu chevrons', () => {
		const ROW = '.is-style-main-navigation > .wp-block-navigation__container > .wp-block-ollie-mega-menu';
		const ICON = [
			`${ ROW } > .wp-block-navigation-item__content > .wp-block-ollie-mega-menu__toggle-icon`,
			`${ ROW } > .wp-block-ollie-mega-menu__submenu-toggle > .wp-block-ollie-mega-menu__toggle-icon`,
		].join( ', ' );

		test( 'no chevron shows beside a top-level label', async ( { page } ) => {
			const icons = page.locator( ICON );
			test.skip( 0 === ( await icons.count() ), 'No mega menus in the main navigation' );

			const shown = await icons.evaluateAll( ( els ) =>
				els
					.filter( ( el ) => 'none' !== getComputedStyle( el ).display )
					.map( ( el ) => el.closest( 'li' ).querySelector( '.wp-block-navigation-item__label' )?.textContent.trim() )
			);

			expect( shown, `chevrons visible beside: ${ shown.join( ', ' ) }` ).toEqual( [] );
		} );

		test( 'a keyboard-focused toggle shows its chevron, so the Tab stop is visible', async ( {
			page,
		} ) => {
			const toggle = page.locator( `${ ROW } > .wp-block-ollie-mega-menu__submenu-toggle` ).first();
			test.skip(
				0 === ( await toggle.count() ),
				'No split link-and-toggle mega menu (Ollie Menu Designer below 0.3)'
			);

			/** A keypress first, so the programmatic focus counts as keyboard focus. */
			await page.keyboard.press( 'Shift' );
			await toggle.focus();

			await expect( toggle.locator( '.wp-block-ollie-mega-menu__toggle-icon' ) ).not.toHaveCSS( 'display', 'none' );
		} );

		test( 'the label keeps its underline while its panel is open', async ( { page } ) => {
			const row = page.locator( ROW ).filter( {
				has: page.locator( ':scope > .wp-block-ollie-mega-menu__submenu-toggle' ),
			} ).first();
			test.skip( 0 === ( await row.count() ), 'No split link-and-toggle mega menu' );

			const link = row.locator( ':scope > .wp-block-navigation-item__content' );
			const underline = () =>
				link.evaluate( ( el ) => getComputedStyle( el, '::after' ).transform );

			expect( await underline(), 'the underline is drawn at rest' ).toBe( 'matrix(1, 0, 0, 0, 0, 0)' );

			/**
			 * Set the state the plugin sets on open, rather than hovering: a
			 * hover would satisfy the link's own `:hover` rule and prove
			 * nothing about the open state.
			 */
			await row
				.locator( ':scope > .wp-block-ollie-mega-menu__submenu-toggle' )
				.evaluate( ( el ) => el.setAttribute( 'aria-expanded', 'true' ) );

			await expect.poll( underline ).toBe( 'matrix(1, 0, 0, 1, 0, 0)' );
		} );
	} );
} );

/**
 * Mobile header — not sticky, with a drop-down search.
 *
 * 390px is live's measured phone width, below `large` in both environments.
 * Deliberately not `@responsive`: the viewport is fixed here, so running it in
 * the tablet and mobile projects as well would repeat the same assertions.
 */
test.describe( 'Header — mobile', () => {
	test.use( { viewport: { width: 390, height: 844 } } );

	test.beforeEach( async ( { visit } ) => {
		await visit( '/' );
	} );

	/**
	 * Live's masthead is `position: relative` at 390px and scrolls away with the
	 * page. Only the desktop header sticks.
	 */
	test( 'renders one band, and it scrolls away with the page', async ( { page } ) => {
		const bands = await renderedHeaderBands( page );

		expect( bands, 'expected exactly one rendered header band' ).toHaveLength( 1 );
		expect( [ 'sticky', 'fixed' ] ).not.toContain( bands[ 0 ].position );

		await page.mouse.wheel( 0, 1200 );
		await page.waitForFunction( () => window.scrollY > 600 );

		const [ scrolled ] = await renderedHeaderBands( page );
		expect( scrolled.bottom, 'mobile header followed the scroll' ).toBeLessThanOrEqual( 0 );
	} );

	test( 'the logo sits wholly inside the header band', async ( { page } ) => {
		const [ band ] = await renderedHeaderBands( page );
		const logo = await page
			.locator( 'header.wp-block-template-part .wp-block-site-logo img' )
			.filter( { visible: true } )
			.first()
			.boundingBox();

		expect( logo, 'no visible site logo in the header' ).toBeTruthy();
		expect( logo.y ).toBeGreaterThanOrEqual( band.top );
		expect( logo.y + logo.height ).toBeLessThanOrEqual( band.bottom );
	} );

	/**
	 * Live's `#mobile-searchform`: the field drops down as a full-width band
	 * under the dark bar. The regression this guards is the old fold-out, which
	 * unrolled sideways and overhung the left edge of the viewport.
	 */
	test( 'the search drops down under the bar, inside the viewport, and takes focus', async ( {
		page,
	} ) => {
		const search = page.locator( '.wp-block-search.is-style-header-search-dropdown' );

		await expect( search ).toBeVisible();

		const trigger = search.locator( '.wp-block-search__button' );
		const field = search.locator( '.wp-block-search__input' );

		await trigger.click();
		await expect( search ).not.toHaveClass( /wp-block-search__searchfield-hidden/ );
		await expect( field, 'the opened field did not take focus' ).toBeFocused();

		const bar = await trigger.evaluate( ( node ) =>
			node.closest( '.wp-block-group.alignfull' ).getBoundingClientRect().toJSON()
		);
		const box = await field.boundingBox();
		const viewport = page.viewportSize();

		expect( box.y, 'field opened over the bar, not under it' ).toBeGreaterThanOrEqual(
			bar.bottom
		);
		expect( box.x ).toBeGreaterThanOrEqual( 0 );
		expect( box.x + box.width ).toBeLessThanOrEqual( viewport.width );
		expect( box.width, 'field did not span the bar' ).toBeGreaterThan( viewport.width * 0.8 );
		expect( box.height ).toBeGreaterThanOrEqual( 44 );
		await expect( field ).toHaveCSS( 'border-radius', '0px' );

		const overflow = await page.evaluate(
			() => document.documentElement.scrollWidth - document.documentElement.clientWidth
		);
		expect( overflow, 'the open field caused horizontal scroll' ).toBe( 0 );

		await page.keyboard.press( 'Escape' );
		await expect( search ).toHaveClass( /wp-block-search__searchfield-hidden/ );
		await expect( trigger, 'focus did not return to the trigger' ).toBeFocused();
	} );

	test.describe( 'menu panel', () => {
		test.beforeEach( async ( { page } ) => {
			await page
				.locator( '.wp-block-navigation__responsive-container-open' )
				.filter( { visible: true } )
				.first()
				.click();
		} );

		const panel = ( page ) =>
			page.locator( '.wp-block-navigation__responsive-container.is-menu-open .sd-mobile-menu' );

		/**
		 * The header's own search is the only one. A second field inside the
		 * panel was a leftover of dev's Site Editor copy of the part.
		 */
		test( 'carries no search field of its own', async ( { page } ) => {
			await expect( panel( page ) ).toBeVisible();
			await expect( panel( page ).locator( '.wp-block-search' ) ).toHaveCount( 0 );
		} );

		test( 'shows a flag beside each of the four phone numbers', async ( { page } ) => {
			const flags = panel( page ).locator( 'img[src*="/flags/"]' );

			await expect( flags ).toHaveCount( 4 );

			for ( const flag of await flags.all() ) {
				await expect( flag ).toBeVisible();
				expect(
					await flag.evaluate( ( img ) => img.complete && img.naturalWidth > 0 ),
					'a flag image failed to load'
				).toBe( true );
			}

			await expect( panel( page ).locator( 'a[href^="tel:"]' ) ).toHaveCount( 4 );
		} );

		/**
		 * The clipped logo on dev was the panel riding up under the viewport's
		 * top edge — a `margin-top: -36px` in the Site Editor copy.
		 */
		test( 'starts at the top of the viewport, with its logo fully on screen', async ( {
			page,
		} ) => {
			const box = await panel( page ).boundingBox();
			expect( box.y, 'panel is dragged above the viewport' ).toBeGreaterThanOrEqual( 0 );

			const logo = await panel( page ).locator( 'img' ).first().boundingBox();
			expect( logo.y, 'panel logo is cut off at the top' ).toBeGreaterThanOrEqual( 0 );
		} );
	} );
} );
