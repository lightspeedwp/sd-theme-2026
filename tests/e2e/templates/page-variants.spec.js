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

const fs = require( 'fs' );
const path = require( 'path' );
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
			mainContent( page ).locator(
				':scope > .wp-block-cover.is-style-hero-banner h1.wp-block-post-title'
			),
			"the page title is not the banner's h1"
		).toHaveCount( 1 );
		await expect(
			mainContent( page ).locator(
				'.wp-block-post-content .wp-block-post-title'
			),
			'the page content still carries its own post-title block'
		).toHaveCount( 0 );
	} );
} );

/**
 * Template slugs that pages still store from the LSX theme and that this theme
 * does not have. Each falls back to `page.html`, which is the right outcome for
 * a page with no variant of its own — the slugs are debris, not a break.
 *
 * Measured on dev and on local, 2026-10-09: three published pages. The list is
 * a ratchet. A fourth unregistered slug fails the run; clearing one is fixed
 * by deleting its line here, and the content fix (assign the page to a variant
 * or reset it to the default template) is Zared's call, not the suite's.
 */
const KNOWN_LEGACY_TEMPLATES = {
	'page-templates/template-full-width.php': 'LSX full-width page',
	'page-templates/template-full-width-no-margins.php':
		'LSX full-width page without margins',
	'page-templates/template-sitemap.php':
		'LSX HTML sitemap — the sitemap itself was retired in ASD-42',
};

/**
 * Slugs the theme registers: one per `templates/*.html` file.
 *
 * Read from disk rather than `/wp/v2/templates`, which needs a login. The
 * suite runs from the theme root, and the file set is what a deploy ships.
 */
const REGISTERED_TEMPLATES = new Set(
	fs
		.readdirSync( path.join( __dirname, '..', '..', '..', 'templates' ) )
		.filter( ( file ) => file.endsWith( '.html' ) )
		.map( ( file ) => file.replace( /\.html$/, '' ) )
);

/**
 * Every published page with the template it stores.
 *
 * The unauthenticated endpoint returns published pages only, which is exactly
 * the set a visitor can reach.
 *
 * @param {import('@playwright/test').Page} page Page, for its request context.
 * @return {Promise<Array<{link: string, slug: string, template: string}>>} Pages.
 */
async function publishedPages( page ) {
	const pages = [];
	let totalPages = 1;

	for ( let number = 1; number <= totalPages; number++ ) {
		const response = await page.request.get(
			`/wp-json/wp/v2/pages?per_page=100&page=${ number }&_fields=link,slug,template`
		);
		expect( response.status(), 'pages endpoint' ).toBe( 200 );

		totalPages = Number( response.headers()[ 'x-wp-totalpages' ] || 1 );
		pages.push( ...( await response.json() ) );
	}

	return pages;
}

/**
 * @param {string} template A page's stored template.
 * @return {boolean} True when the page uses the default template.
 */
const isDefaultTemplate = ( template ) =>
	'' === template || 'default' === template;

test.describe( 'Legacy page-template slugs', () => {
	test( 'no published page stores a template the theme lacks, beyond the known legacy ones', async ( {
		page,
	} ) => {
		const unregistered = ( await publishedPages( page ) ).filter(
			( { template } ) =>
				! isDefaultTemplate( template ) &&
				! REGISTERED_TEMPLATES.has( template )
		);

		for ( const { template, link } of unregistered ) {
			if ( KNOWN_LEGACY_TEMPLATES[ template ] ) {
				test.info().annotations.push( {
					type: 'legacy-template',
					description: `${ link } stores ${ template } (${ KNOWN_LEGACY_TEMPLATES[ template ] })`,
				} );
			}
		}

		const unexpected = unregistered.filter(
			( { template } ) => ! KNOWN_LEGACY_TEMPLATES[ template ]
		);

		expect(
			unexpected.map(
				( { link, template } ) => `${ link } → ${ template }`
			),
			'a page stores a template slug the theme does not register and the ' +
				'ratchet does not know — assign it a variant, or add it to ' +
				'KNOWN_LEGACY_TEMPLATES with a reason'
		).toEqual( [] );
	} );

	test( 'a page storing a legacy slug still renders through the default page template', async ( {
		page,
		visit,
	} ) => {
		const legacy = ( await publishedPages( page ) ).filter(
			( { template } ) => KNOWN_LEGACY_TEMPLATES[ template ]
		);
		test.skip( ! legacy.length, 'No published page stores a legacy slug' );

		for ( const { link } of legacy ) {
			await visit( new URL( link ).pathname );

			/**
			 * The fallback is what is under test, not the title: whether the
			 * page has one h1 is the content's to decide (/thank-you-for-
			 * subscribing/ carries its own `<h1>` in the body, measured on
			 * local 2026-10-09), and the page-variant checks above own the
			 * h1 rule for pages that do opt into a template.
			 */
			await assertPageContract( page, { expectH1: false } );
		}
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
