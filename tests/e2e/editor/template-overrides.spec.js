/**
 * Database overrides shadowing theme files.
 *
 * This is the documented trap in AGENTS.md: anything edited in the Site Editor
 * is stored in the database and wins over the theme file, so a template can be
 * correct in the repository and wrong on the site. It is the leading suspect for
 * the front page rendering no `main` landmark while every other route has one.
 *
 * Signed-in, because the templates endpoint requires `edit_theme_options`. The
 * whole project drops out of the run when no credentials are set.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const fs = require( 'fs' );
const path = require( 'path' );
const { test, expect } = require( '@playwright/test' );

const THEME_ROOT = path.join( __dirname, '..', '..', '..' );

/**
 * Template slugs the theme ships as files.
 *
 * Read from disk rather than listed, so a new template is covered the day it
 * lands instead of the day someone remembers to update this array.
 *
 * @return {string[]} Slugs, without the .html extension.
 */
function themeTemplateSlugs() {
	return fs
		.readdirSync( path.join( THEME_ROOT, 'templates' ) )
		.filter( ( file ) => file.endsWith( '.html' ) )
		.map( ( file ) => file.replace( /\.html$/, '' ) )
		.sort();
}

/**
 * Cached REST nonce for this worker.
 *
 * @type {string|null}
 */
let restNonce = null;

/**
 * Get a REST nonce for the signed-in session.
 *
 * The saved storage state carries the auth cookies, but WordPress rejects
 * cookie-authenticated REST requests without a matching nonce — the whole
 * endpoint returns 401 and looks like a failed login. `admin-ajax.php?action=
 * rest-nonce` is core's own way to mint one, and it needs nothing but the
 * cookies we already have.
 *
 * @param {import('@playwright/test').APIRequestContext} request Request context.
 * @return {Promise<string>} The nonce.
 */
async function nonce( request ) {
	if ( restNonce ) {
		return restNonce;
	}

	const response = await request.get(
		'/wp-admin/admin-ajax.php?action=rest-nonce'
	);

	expect(
		response.ok(),
		`could not mint a REST nonce (${ response.status() }) — the saved ` +
			'session has probably expired; delete tests/e2e/.auth/ and re-run'
	).toBe( true );

	restNonce = ( await response.text() ).trim();

	expect( restNonce, 'rest-nonce returned an empty body' ).not.toBe( '' );

	return restNonce;
}

/**
 * @param {import('@playwright/test').APIRequestContext} request Request context.
 * @param {string}                                       route   REST route or absolute link.
 * @return {Promise<Object[]|Object>} Decoded payload.
 */
async function fetchAll( request, route ) {
	const url = route.startsWith( 'http' )
		? route
		: `/wp-json/wp/v2/${ route }?per_page=100&context=edit`;

	const response = await request.get( url, {
		headers: { 'X-WP-Nonce': await nonce( request ) },
	} );

	expect(
		response.ok(),
		`${ route } returned ${ response.status() } — is the session still valid?`
	).toBe( true );

	const items = await response.json();

	return Array.isArray( items ) ? items : [];
}

test.describe( 'Site Editor overrides @auth', () => {
	test( 'no template is overridden in the database', async ( { request } ) => {
		const templates = await fetchAll( request, 'templates' );

		/**
		 * `source: 'custom'` means the rendered template comes from a
		 * `wp_template` post, not from the file. With `has_theme_file: true`
		 * the file exists and is being ignored — which is the dangerous case,
		 * because the repository looks correct.
		 */
		const shadowed = templates
			.filter(
				( template ) =>
					'custom' === template.source && template.has_theme_file
			)
			.map( ( template ) => template.slug )
			.sort();

		expect(
			shadowed,
			'These theme template files are being shadowed by a Site Editor ' +
				'database override — the site renders the database version, ' +
				'not the file:\n  ' +
				shadowed.join( '\n  ' ) +
				'\n\nReconcile with the wp-db-override-reconciliation skill: ' +
				'either import the override back into the file, or clear it so ' +
				'the file wins.'
		).toEqual( [] );
	} );

	test( 'no template part is overridden in the database', async ( {
		request,
	} ) => {
		const parts = await fetchAll( request, 'template-parts' );

		const shadowed = parts
			.filter( ( part ) => 'custom' === part.source && part.has_theme_file )
			.map( ( part ) => part.slug )
			.sort();

		expect(
			shadowed,
			'Template parts shadowed by a database override:\n  ' +
				shadowed.join( '\n  ' )
		).toEqual( [] );
	} );

	test( 'every template file is registered', async ( { request } ) => {
		const templates = await fetchAll( request, 'templates' );
		const registered = new Set(
			templates.map( ( template ) => template.slug )
		);

		const missing = themeTemplateSlugs().filter(
			( slug ) => ! registered.has( slug )
		);

		expect(
			missing,
			'Template files present in templates/ but not registered by ' +
				'WordPress — usually a malformed block comment or a slug that ' +
				'does not match a known template type:\n  ' +
				missing.join( '\n  ' )
		).toEqual( [] );
	} );

	/**
	 * Styles edited in the Site Editor are stored as a `wp_global_styles` post
	 * and win over theme.json in exactly the same way. A non-empty user layer
	 * on a site that is deployed by pull means the next deploy renders
	 * something other than what the repository says.
	 */
	test( 'theme.json is not shadowed by Site Editor global styles', async ( {
		request,
	} ) => {
		const themes = await fetchAll( request, 'themes' );
		const active = themes.find( ( theme ) => theme.status === 'active' );

		test.skip( ! active, 'Could not identify the active theme' );

		const link =
			active._links?.[ 'wp:user-global-styles' ]?.[ 0 ]?.href || null;

		test.skip( ! link, 'No user global styles link on the active theme' );

		const response = await request.get( link, {
			headers: { 'X-WP-Nonce': await nonce( request ) },
		} );
		expect(
			response.ok(),
			`global styles returned ${ response.status() }`
		).toBe( true );

		const globalStyles = await response.json();
		const settings = globalStyles.settings || {};
		const styles = globalStyles.styles || {};

		const customised = [
			...( Object.keys( settings ).length ? [ 'settings' ] : [] ),
			...( Object.keys( styles ).length ? [ 'styles' ] : [] ),
		];

		expect(
			customised,
			'The Site Editor holds user global styles (' +
				customised.join( ', ' ) +
				') which override theme.json. Clear them before deploy, or the ' +
				'site renders the database version of the design tokens.'
		).toEqual( [] );
	} );

	/**
	 * Not an override, but the other half of the front-page question: which
	 * template WordPress should be resolving in the first place.
	 */
	test( 'the front page resolves to a template the theme provides', async ( {
		request,
	} ) => {
		const templates = await fetchAll( request, 'templates' );
		const frontPage = templates.find(
			( template ) => 'front-page' === template.slug
		);

		expect(
			frontPage,
			'No front-page template is registered at all, yet ' +
				'templates/front-page.html exists'
		).toBeTruthy();

		expect(
			frontPage.source,
			`front-page resolves from "${ frontPage.source }" rather than the theme file`
		).toBe( 'theme' );
	} );
} );
