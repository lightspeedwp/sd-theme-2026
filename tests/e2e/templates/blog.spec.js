/**
 * Blog — the landing, the archives and the single post, finalised 2026-09-24.
 *
 * LS-2022 (Blog Templates and Related Posts). Covers:
 *
 *   home.html      → patterns/template-home-blog.php
 *   category.html  → patterns/template-category.php
 *   tag.html       → patterns/template-category.php (since 2026-09-24)
 *   author.html    → patterns/template-category.php (since 2026-09-24)
 *   single.html    → patterns/template-single-post.php
 *
 * **The banner** on the landing and on every archive is the hero banner, phone
 * stack included, on the 360px floor — utils/hero-banner.js carries that
 * contract. The single post has **no** banner: live opens on the byline and
 * the title, and so does this theme (the reasoning is on the pattern).
 *
 * **The archives are one composition.** A category, a tag and an author all
 * print the queried name as the only `h1`, a "Back To Blog" link and the same
 * post rows, and every row belongs to what was queried.
 *
 * **The related shelf** is Tour Operator's related query with
 * `SD\Enhancements\Queries::relate_posts_by_category()` supplying the rule:
 * up to fifteen posts sharing a category with the one being read, never that
 * post itself. These tests fail if the plugin filter stops running — TO's own
 * branch would put the current post on its own shelf.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );
const { BANNER, describeHeroBanners, resolvePreset } = require( '../utils/hero-banner.js' );

/**
 * A literal path, prefixed the way this environment's permalinks need.
 *
 * Local Studio runs PATH_INFO permalinks (`/index.php/…`); the prefix is read
 * off a permalink global setup already resolved. → specials.spec.js
 *
 * @param {Object} routes The `routes` fixture.
 * @param {string} path   Path with a leading slash.
 * @return {string} Path for this environment.
 */
function envPath( routes, path ) {
	const sample = routes.post( 'post' ) || routes.post( 'tour' ) || '';
	return `${ sample.startsWith( '/index.php/' ) ? '/index.php' : '' }${ path }`;
}

/**
 * The path of an author archive for someone who has published a post — read
 * off the byline of the newest post.
 *
 * Dev answers `/wp/v2/users` with a 401 for an anonymous request and 404s
 * `/?author={id}` (user enumeration is blocked), so the byline's own link is
 * the one route to an author archive a visitor can actually follow. Measured
 * 2026-09-24.
 *
 * @param {Object}                                       routes  The `routes` fixture.
 * @param {import('@playwright/test').APIRequestContext} request Request context.
 * @return {Promise<string|null>} Path, or null when there is no post to read.
 */
async function authorPath( routes, request ) {
	const post = routes.post( 'post' );

	if ( ! post ) {
		return null;
	}

	const response = await request.get( post );

	if ( ! response.ok() ) {
		return null;
	}

	const match = ( await response.text() ).match(
		/class="[^"]*wp-block-post-author-name[^"]*"[^>]*>\s*<a href="([^"]+)"/
	);

	return match ? new URL( match[ 1 ] ).pathname : null;
}

/**
 * Fetch posts by slug from the REST API.
 *
 * @param {import('@playwright/test').APIRequestContext} request Request context.
 * @param {string[]}                                     slugs   Post slugs.
 * @return {Promise<Object[]>} `{ id, slug, author, categories }` per post found.
 */
async function postsBySlug( request, slugs ) {
	const response = await request.get(
		`/wp-json/wp/v2/posts?per_page=100&slug=${ slugs.map( encodeURIComponent ).join( ',' ) }&_fields=id,slug,author,categories`
	);
	expect( response.ok(), 'Could not read posts from the REST API' ).toBe( true );
	return response.json();
}

/**
 * The last non-empty path segment — a post's slug from its permalink.
 *
 * @param {string} href Absolute or relative URL.
 * @return {string} Slug.
 */
function slugOf( href ) {
	return new URL( href, 'https://example.test' ).pathname.split( '/' ).filter( Boolean ).pop();
}

const ROW = 'main .wp-block-post-template > li .is-style-blog-card-wide';
const RELATED = '#related .lsx-post-related-post-query li.wp-block-post:not(.slick-cloned)';

describeHeroBanners( { test, expect }, [
	{ name: 'blog landing', path: ( routes ) => envPath( routes, '/blog/' ) },
	{ name: 'category archive', path: ( routes ) => routes.term( 'category' ) },
	{ name: 'tag archive', path: ( routes ) => routes.term( 'post_tag' ) },
	{ name: 'author archive', path: ( routes, request ) => authorPath( routes, request ) },
] );

test.describe( 'Blog landing', () => {
	test( 'titles the banner "Blog" and heads the intro band with an h2', async ( { page, visit, routes } ) => {
		await visit( envPath( routes, '/blog/' ) );

		await expect( page.locator( `${ BANNER } h1` ) ).toHaveText( /^\s*Blog\s*$/ );

		const intro = page.locator( 'main .is-style-tinted-page-section' ).first();
		await expect( intro.locator( 'h2' ).first() ).toHaveText( /Tales from our trails/i );
	} );

	test( 'shelves the blog categories, each tile linking to its archive', async ( { page, visit, routes } ) => {
		await visit( envPath( routes, '/blog/' ) );

		const tiles = page.locator( 'main .wp-block-terms-query .wp-block-term-name a' );
		await expect( tiles, 'the Browse By Category shelf is empty' ).not.toHaveCount( 0 );

		for ( const href of await tiles.evaluateAll( ( els ) => els.map( ( el ) => el.href ) ) ) {
			expect( href, 'a category tile does not link to a category archive' ).toMatch( /\/category\/[^/]+\/?$/ );
		}
	} );

	test( 'lists the newest posts as rows and pages the rest', async ( { page, visit, routes } ) => {
		const response = await page.request.get( '/wp-json/wp/v2/posts?per_page=1&_fields=id,link' );
		expect( response.ok(), 'Could not read posts from the REST API' ).toBe( true );
		const total = Number( response.headers()[ 'x-wp-total' ] || 0 );
		const [ newest ] = await response.json();
		test.skip( ! newest, 'No published posts in this environment' );

		await visit( envPath( routes, '/blog/' ) );

		const rows = page.locator( ROW );
		const perPage = await rows.count();
		expect( perPage, 'the blog landing lists no posts' ).toBeGreaterThan( 0 );

		const first = await rows.first().locator( '.wp-block-post-title a' ).getAttribute( 'href' );
		expect( slugOf( first ), 'the first row is not the newest post' ).toBe( slugOf( newest.link ) );

		/**
		 * Live's row title is 22px; font size 400 is the nearest token. The
		 * row style had carried 500 (32px), which was never measured.
		 */
		const titleSize = await resolvePreset( page, 'font-size', '--wp--preset--font-size--400' );

		for ( let i = 0; i < perPage; i++ ) {
			const row = rows.nth( i );
			await expect( row.locator( '.wp-block-post-title a' ) ).toHaveCount( 1 );
			await expect( row.locator( '.wp-block-post-date' ) ).toHaveCount( 1 );
			await expect( row.locator( '.wp-block-post-title' ) ).toHaveCSS( 'font-size', titleSize );
		}

		test.skip( total <= perPage, `Only ${ total } posts — one page, nothing to paginate` );

		const next = page.locator( 'main .wp-block-query-pagination-next' );
		await expect( next, `${ total } posts but no next-page link` ).toBeVisible();

		await Promise.all( [ page.waitForURL( /\/page\/2\/?$/ ), next.click() ] );

		const secondFirst = await page.locator( ROW ).first().locator( '.wp-block-post-title a' ).getAttribute( 'href' );
		expect( secondFirst, 'page 2 opens on the same post as page 1' ).not.toBe( first );
	} );
} );

test.describe( 'Blog archives', () => {
	const ARCHIVES = [
		{
			name: 'category',
			path: ( routes ) => routes.term( 'category' ),
			rowField: '.wp-block-post-terms.taxonomy-category',
		},
		{
			name: 'tag',
			path: ( routes ) => routes.term( 'post_tag' ),
			rowField: '.wp-block-post-terms.taxonomy-post_tag',
		},
		{
			name: 'author',
			path: ( routes, request ) => authorPath( routes, request ),
			rowField: '.wp-block-post-author-name',
		},
	];

	for ( const archive of ARCHIVES ) {
		test( `the ${ archive.name } archive titles the banner with its name and links back to the blog`, async ( {
			page,
			visit,
			routes,
		} ) => {
			const target = await archive.path( routes, page.request );
			test.skip( ! target, `No ${ archive.name } archive on ${ routes.baseURL }` );

			await visit( target );

			const title = page.locator( `${ BANNER } h1.wp-block-query-title` );
			await expect( title, 'the banner h1 is not the query title' ).toHaveCount( 1 );
			await expect( title, 'the archive title kept its "Category:"/"Tag:"/"Author:" prefix' ).not.toHaveText(
				/^(Category|Tag|Author|Archives):/i
			);

			const back = page.locator( 'main .is-style-back-link a' );
			await expect( back ).toHaveText( /Back To Blog/i );
			expect( await back.getAttribute( 'href' ) ).toMatch( /\/blog\/?$/ );
		} );

		test( `the ${ archive.name } archive draws the back link as live does: uppercase, 200, a leading arrow`, async ( {
			page,
			visit,
			routes,
		} ) => {
			const target = await archive.path( routes, page.request );
			test.skip( ! target, `No ${ archive.name } archive on ${ routes.baseURL }` );

			await visit( target );

			const back = page.locator( 'main .is-style-back-link a' );
			const size = await resolvePreset( page, 'font-size', '--wp--preset--font-size--200' );

			await expect( back, 'live\'s a.back-to-blog is uppercase' ).toHaveCSS( 'text-transform', 'uppercase' );
			await expect( back ).toHaveCSS( 'font-size', size );

			/**
			 * The arrow is a masked ::before with empty content, so it must
			 * exist, carry a mask, and stay out of the accessible name.
			 */
			const arrow = await back.evaluate( ( el ) => {
				const before = getComputedStyle( el, '::before' );
				return {
					content: before.content,
					mask: before.maskImage || before.webkitMaskImage,
					width: parseFloat( before.inlineSize || before.width ),
				};
			} );

			expect( arrow.content, 'the back link lost its arrow' ).toBe( '""' );
			expect( arrow.mask, 'the arrow has no mask to draw' ).toMatch( /^url\(/ );
			expect( arrow.width ).toBeGreaterThan( 0 );
			await expect( back ).toHaveAccessibleName( /^Back To Blog$/i );
		} );

		test( `every row on the ${ archive.name } archive belongs to it`, async ( { page, visit, routes } ) => {
			const target = await archive.path( routes, page.request );
			test.skip( ! target, `No ${ archive.name } archive on ${ routes.baseURL }` );

			await visit( target );

			const name = ( await page.locator( `${ BANNER } h1` ).textContent() ).trim();
			const rows = page.locator( ROW );
			const count = await rows.count();
			expect( count, `${ target } lists no posts` ).toBeGreaterThan( 0 );

			const titleSize = await resolvePreset( page, 'font-size', '--wp--preset--font-size--400' );

			for ( let i = 0; i < count; i++ ) {
				await expect( rows.nth( i ).locator( '.wp-block-post-title' ) ).toHaveCSS( 'font-size', titleSize );

				const field = rows.nth( i ).locator( archive.rowField );
				await expect(
					field,
					`row ${ i + 1 } does not show "${ name }" — the archive is not scoped to what was queried`
				).toContainText( name, { ignoreCase: true } );
			}
		} );
	}
} );

test.describe( 'Single post', () => {
	/**
	 * Visit the newest post and read its REST record.
	 *
	 * @param {Object} fixtures `{ page, visit, routes }`.
	 * @return {Promise<Object>} `{ id, slug, author, categories }`.
	 */
	async function visitPost( { page, visit, routes } ) {
		const target = routes.post( 'post' );
		test.skip( ! target, `No published post on ${ routes.baseURL }` );

		await visit( target );

		const [ post ] = await postsBySlug( page.request, [ slugOf( target ) ] );
		expect( post, `${ target } is not in the posts endpoint` ).toBeTruthy();

		return post;
	}

	test( 'opens on the byline and the title, with no banner and no featured image @responsive', async ( {
		page,
		visit,
		routes,
	} ) => {
		const post = await visitPost( { page, visit, routes } );

		await expect( page.locator( 'main > .wp-block-cover' ), 'the single post grew a banner' ).toHaveCount( 0 );
		await expect( page.locator( 'h1' ) ).toHaveCount( 1 );

		const article = page.locator( 'main > article' );
		await expect( article, 'no <article> directly inside <main>' ).toHaveCount( 1 );
		await expect(
			article.locator( '.wp-block-post-featured-image' ),
			'the article renders the featured image — live does not'
		).toHaveCount( 0 );

		/**
		 * Live's order: date and author above the h1, categories below it.
		 * Compared by document position, so a layout change that moves them
		 * visually but keeps the source order still passes.
		 */
		const order = await article.evaluate( ( el ) => {
			const at = ( selector ) => {
				const node = el.querySelector( selector );
				return node ? [ ...el.querySelectorAll( '*' ) ].indexOf( node ) : -1;
			};
			return {
				date: at( '.wp-block-post-date' ),
				author: at( '.wp-block-post-author-name' ),
				title: at( 'h1.wp-block-post-title' ),
				terms: at( '.wp-block-post-terms.taxonomy-category' ),
				content: at( '.wp-block-post-content' ),
			};
		} );

		/**
		 * `core/post-author-name` prints nothing for a post with no author —
		 * local Studio's fixture posts carry `post_author` 0. Every migrated
		 * post has one, so the field is only waived when the record says so.
		 */
		if ( ! post.author ) {
			delete order.author;
		}

		for ( const [ field, index ] of Object.entries( order ) ) {
			expect( index, `the article has no ${ field }` ).toBeGreaterThanOrEqual( 0 );
		}

		expect( order.date, 'the date is not above the title' ).toBeLessThan( order.title );
		if ( undefined !== order.author ) {
			expect( order.author, 'the author is not above the title' ).toBeLessThan( order.title );
		}
		expect( order.terms, 'the categories are not below the title' ).toBeGreaterThan( order.title );
		expect( order.content, 'the body does not follow the header' ).toBeGreaterThan( order.terms );
	} );

	test( 'shelves up to fifteen related posts sharing a category, never itself', async ( { page, visit, routes } ) => {
		const post = await visitPost( { page, visit, routes } );

		await expect( page.locator( '#related h2' ).first() ).toHaveText( /Related Posts/i );

		const hrefs = await page
			.locator( `${ RELATED } .wp-block-post-title a` )
			.evaluateAll( ( els ) => els.map( ( el ) => el.getAttribute( 'href' ) ) );

		expect( hrefs.length, 'the related shelf is empty' ).toBeGreaterThan( 0 );
		expect( hrefs.length, 'more than fifteen related posts' ).toBeLessThanOrEqual( 15 );

		const slugs = hrefs.map( slugOf );
		expect( slugs, 'the shelf offers the reader the post they are on' ).not.toContain( post.slug );
		expect( new Set( slugs ).size, 'a post appears on the shelf twice' ).toBe( slugs.length );

		/**
		 * Uncategorised posts fall back to the most recent posts, by design
		 * (Queries::relate_posts_by_category()); only a categorised post can be
		 * held to the category rule.
		 */
		test.skip( ! post.categories?.length, `${ post.slug } has no categories to share` );

		const related = await postsBySlug( page.request, slugs );

		for ( const item of related ) {
			const shared = item.categories.filter( ( id ) => post.categories.includes( id ) );
			expect( shared.length, `${ item.slug } shares no category with ${ post.slug }` ).toBeGreaterThan( 0 );
		}
	} );

	test( 'closes the tinted band on the previous/next pager', async ( { page, visit, routes } ) => {
		await visitPost( { page, visit, routes } );

		const pager = page.locator( '#related .sd-post-nav a' );
		await expect( pager, 'no previous or next post link' ).not.toHaveCount( 0 );

		for ( const label of await pager.allInnerTexts() ) {
			expect( label, 'a pager link lost its "Previous Post"/"Next Post" label' ).toMatch( /(Previous|Next) Post/i );
		}
	} );
} );
