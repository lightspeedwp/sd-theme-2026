/**
 * Custom page templates, and archive pagination.
 *
 * The four page-* variants render nowhere until a page is assigned to them, so
 * global setup asks the pages endpoint which pages carry which template. A
 * variant with no page using it skips with a stated reason — that is a content
 * fact, not a theme defect.
 *
 * Each variant exists to differ from page.html in exactly one way, and that
 * difference is what is asserted. A variant that renders identically to the
 * default is a variant that is not working.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );
const {
	assertPageContract,
	mainContent,
	results,
} = require( '../utils/page-contract.js' );

/**
 * `expectH1: false` where the variant's whole purpose is to suppress the title.
 */
const PAGE_VARIANTS = [
	{
		file: 'page-brands.html',
		name: 'brands page',
		expectH1: true,
	},
	{
		file: 'page-no-header.html',
		name: 'page without a header',
		expectH1: true,
	},
	{
		file: 'page-no-title.html',
		name: 'full-width page with the banner',
		expectH1: true,
	},
	{
		file: 'page-with-sidebar.html',
		name: 'page with a sidebar',
		expectH1: true,
	},
];

test.describe( 'Custom page templates', () => {
	for ( const variant of PAGE_VARIANTS ) {
		test( `${ variant.name } (${ variant.file }) renders @responsive`, async ( {
			page,
			visit,
			routes,
		} ) => {
			const target = routes.pageTemplate( variant.file );

			test.skip(
				! target,
				`No published page is assigned to ${ variant.file }`
			);

			await visit( target );
			await assertPageContract( page, { expectH1: variant.expectH1 } );
		} );
	}

	test( 'the sidebar variant actually renders a sidebar', async ( {
		page,
		visit,
		routes,
	} ) => {
		const target = routes.pageTemplate( 'page-with-sidebar.html' );
		test.skip( ! target, 'No page assigned to page-with-sidebar.html' );

		await visit( target );

		/**
		 * The sidebar is a template part, so it carries the block wrapper
		 * class whatever is inside it. A complementary landmark is the
		 * accessible signal and the better assertion where it exists.
		 */
		const sidebar = page
			.getByRole( 'complementary' )
			.or( page.locator( 'aside, .wp-block-template-part aside' ) );

		await expect(
			sidebar.first(),
			'page-with-sidebar.html rendered no sidebar — it is currently ' +
				'indistinguishable from page.html'
		).toBeAttached();
	} );

	/**
	 * `page-no-title` kept its slug — five pages store it — but since
	 * 2026-09-23 it is the full-width page *with* the hero banner, and the page
	 * title is the banner's `<h1>`. What the variant still has to prove is
	 * that it differs from page.html in the way its slug once promised: no
	 * title of its own in the content column. The one title it renders is the
	 * banner's.
	 */
	test( 'the full-width banner variant titles the page in its banner only', async ( {
		page,
		visit,
		routes,
	} ) => {
		const target = routes.pageTemplate( 'page-no-title.html' );
		test.skip( ! target, 'No page assigned to page-no-title.html' );

		await visit( target );

		const title = await page.title();
		expect( title.trim(), 'document title is empty' ).not.toBe( '' );

		await expect( page.locator( 'h1' ) ).toHaveCount( 1 );
		await expect(
			mainContent( page ).locator( ':scope > .wp-block-cover.is-style-hero-banner h1.wp-block-post-title' ),
			'the page title is not the banner\'s h1'
		).toHaveCount( 1 );
		await expect(
			mainContent( page ).locator( '.wp-block-post-content .wp-block-post-title' ),
			'the page content still carries its own post-title block'
		).toHaveCount( 0 );
	} );
} );

test.describe( 'Archive pagination', () => {
	/**
	 * Page two is where a query loop's `offset`, its `inherit` flag and its
	 * pagination block have to agree with each other. Page one passing tells
	 * you almost nothing about page two.
	 */
	const PAGINATED = [
		{ name: 'tour archive', path: '/tours/' },
		{ name: 'accommodation archive', path: '/accommodation/' },
		{ name: 'blog', path: '/blog/' },
	];

	for ( const archive of PAGINATED ) {
		test( `${ archive.name } page two lists a different set`, async ( {
			page,
			visit,
		} ) => {
			await visit( archive.path );

			const pagination = mainContent( page ).locator(
				'.wp-block-query-pagination, .facetwp-pager, nav[aria-label*="agination" i]'
			);

			test.skip(
				0 === ( await pagination.count() ),
				`${ archive.path } has no pagination — fewer results than one page`
			);

			const firstPageLinks = await results( page )
				.locator( 'a[href]' )
				.evaluateAll( ( nodes ) =>
					nodes.map( ( node ) => node.getAttribute( 'href' ) )
				);

			await visit( `${ archive.path }page/2/` );

			const secondPageItems = results( page );

			await expect(
				secondPageItems,
				`${ archive.path }page/2/ returned 200 but listed nothing — ` +
					'the query loop is probably not inheriting the paged query'
			).not.toHaveCount( 0 );

			const secondPageLinks = await secondPageItems
				.locator( 'a[href]' )
				.evaluateAll( ( nodes ) =>
					nodes.map( ( node ) => node.getAttribute( 'href' ) )
				);

			const overlap = secondPageLinks.filter( ( href ) =>
				firstPageLinks.includes( href )
			);

			/**
			 * Total overlap means page two is re-rendering page one, which is
			 * what a query loop does when it ignores the paged parameter.
			 */
			expect(
				overlap.length,
				`${ archive.path }page/2/ lists the same items as page one`
			).toBeLessThan( secondPageLinks.length );
		} );
	}
} );
