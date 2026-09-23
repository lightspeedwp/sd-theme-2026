/**
 * The base test fixture.
 *
 * Every spec imports `test` from here rather than from `@playwright/test`. That
 * buys three things automatically, on every page the suite visits:
 *
 *   1. A PHP-error guard. Debug display is off on local, but a notice that
 *      escapes into the markup on dev is a real defect and should not need a
 *      dedicated spec to catch it.
 *   2. A console guard. Third-party widgets are noisy, so known offenders are
 *      allowlisted by origin rather than by muting the check.
 *   3. Resolved routes, from global-setup.js, with a `skipUnless` helper so a
 *      type with no content in this environment skips with a stated reason
 *      instead of failing.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const fs = require( 'fs' );
const path = require( 'path' );
const base = require( '@playwright/test' );

const CACHE_PATH = path.join( __dirname, '..', '.resolved-routes.json' );

/**
 * Markup that means PHP spoke when it should not have.
 *
 * Matched against rendered HTML. The `<b>` form is what WordPress emits with
 * `display_errors` on; the bare form covers plain-text output.
 */
const PHP_ERROR_PATTERNS = [
	/<b>(Warning|Notice|Fatal error|Parse error|Deprecated)<\/b>:/i,
	/\b(Fatal error|Parse error):\s/i,
	/\bUncaught\s+(Error|TypeError|ArgumentCountError)\b/i,
	/There has been a critical error on this website/i,
	/Cannot modify header information/i,
];

/**
 * Console noise we do not own and will not fix.
 *
 * Scoped as tightly as possible: a substring match on the message or its source
 * URL. Anything not listed here fails the test, which is the point — the theme's
 * own console errors should be zero.
 */
const CONSOLE_ALLOWLIST = [
	// Third-party review widget, loaded in an iframe we do not control.
	'trustpilot',
	// PixelYourSite's trackers. Every one of these fails CORS or is blocked on
	// a non-production host, and none of it is the theme's to fix.
	'px.ads.linkedin.com',
	'linkedin.com',
	'connect.facebook.net',
	'facebook.com/tr',
	'google-analytics.com',
	'googletagmanager.com',
	'doubleclick.net',
	'analytics.tiktok.com',
	// WETU itinerary embeds and their analytics, loaded cross-origin.
	'wetu.com',
	'staticstuff.net',
	// Google Maps billing/quota chatter on non-production keys.
	'maps.googleapis.com',
	'Google Maps JavaScript API',
	// FacetWP and Envira ship their own console warnings.
	'facetwp',
	'envira',
	// Ad/analytics blockers in the runner's browser profile.
	'ERR_BLOCKED_BY_CLIENT',
	// Favicon 404s are not a theme defect worth failing a suite over.
	'favicon.ico',
];

/**
 * @param {string} text Message or URL to test.
 * @return {boolean} True when the message is known third-party noise.
 */
function isAllowedConsoleNoise( text ) {
	const haystack = String( text ).toLowerCase();

	return CONSOLE_ALLOWLIST.some( ( needle ) =>
		haystack.includes( needle.toLowerCase() )
	);
}

/**
 * Reduce a URL or path to its pathname, so a console message's absolute source
 * URL can be compared against the relative path a spec asked for.
 *
 * @param {string} url URL or path.
 * @return {string} Pathname, with no query or fragment.
 */
function toPathname( url ) {
	try {
		return new URL( url, 'http://placeholder.invalid' ).pathname;
	} catch {
		return String( url ).split( '?' )[ 0 ].split( '#' )[ 0 ];
	}
}

/**
 * Read the routes global setup resolved for this run.
 *
 * @return {Object} Resolved route cache.
 */
function readResolvedRoutes() {
	if ( ! fs.existsSync( CACHE_PATH ) ) {
		throw new Error(
			'No resolved routes. global-setup.js did not run — are you invoking ' +
				'playwright from the theme root?'
		);
	}

	return JSON.parse( fs.readFileSync( CACHE_PATH, 'utf8' ) );
}

const test = base.test.extend( {
	/**
	 * Routes resolved from the live site, plus lookup helpers.
	 */
	routes: [
		async ( {}, use ) => {
			const resolved = readResolvedRoutes();

			await use( {
				...resolved,

				/**
				 * @param {string} key Content type key.
				 * @return {string|null} Permalink path, or null when absent.
				 */
				post: ( key ) => resolved.posts[ key ] || null,

				/**
				 * @param {string} key Taxonomy key.
				 * @return {string|null} Term archive path, or null when absent.
				 */
				term: ( key ) => resolved.terms[ key ] || null,

				/**
				 * A page assigned to a custom page template, resolved at
				 * start-up — these templates render nowhere until a page
				 * opts into them, so there is no fixed URL.
				 *
				 * @param {string} file Template file, e.g. 'page-brands.html'.
				 * @return {string|null} Path, or null when no page uses it.
				 */
				pageTemplate: ( file ) => {
					const map = resolved.pageTemplates || {};

					/**
					 * The REST API reports a block template by slug, with no
					 * extension (`page-no-title`), and a legacy PHP template
					 * by filename (`brands.php`). Accept the file name the
					 * specs use and fall back to the bare slug.
					 */
					return map[ file ] || map[ file.replace( /\.html$/, '' ) ] || null;
				},
			} );
		},
		{ scope: 'worker' },
	],

	/**
	 * Console and page errors collected during the test, asserted empty on
	 * teardown. Set `test.info().annotations` with type `allow-console` to opt a
	 * single spec out.
	 */
	consoleGuard: [
		async ( { page }, use, testInfo ) => {
			const errors = [];

			/**
			 * URLs whose own load failure is the point of the test — the 404
			 * template being the obvious case. Navigating to a 404 makes the
			 * browser log "Failed to load resource: 404" against the document
			 * itself, which is expected, while a 404 on an image or script on
			 * that same page is still a defect worth failing on.
			 */
			const expectedDocumentFailures = new Set();

			page.on( 'console', ( message ) => {
				if ( 'error' !== message.type() ) {
					return;
				}

				const text = message.text();
				const source = message.location()?.url || '';

				if (
					isAllowedConsoleNoise( text ) ||
					isAllowedConsoleNoise( source )
				) {
					return;
				}

				/**
				 * Only the document's own failure is forgiven, matched on the
				 * exact URL — a subresource 404 on the same page still fails.
				 */
				if ( expectedDocumentFailures.has( toPathname( source ) ) ) {
					return;
				}

				errors.push( `console.error: ${ text }` );
			} );

			page.on( 'pageerror', ( error ) => {
				if ( isAllowedConsoleNoise( error.message ) ) {
					return;
				}

				errors.push( `pageerror: ${ error.message }` );
			} );

			await use( {
				errors,

				/**
				 * @param {string} url Absolute URL whose own load failure is expected.
				 */
				allowDocumentError: ( url ) =>
					expectedDocumentFailures.add( toPathname( url ) ),
			} );

			const optedOut = testInfo.annotations.some(
				( annotation ) => 'allow-console' === annotation.type
			);

			if ( ! optedOut && errors.length ) {
				throw new Error(
					`Unexpected browser errors (${ errors.length }):\n  ` +
						errors.join( '\n  ' )
				);
			}
		},
		{ auto: true },
	],

	/**
	 * Navigate and assert the page is structurally sound.
	 *
	 * Returns the response so a spec can assert on status or headers. Every
	 * navigation in the suite should go through this rather than `page.goto`,
	 * so the PHP-error guard is never accidentally skipped.
	 */
	visit: async ( { page, consoleGuard }, use ) => {
		/**
		 * @param {string} url            Path to visit.
		 * @param {Object} [options]      Options.
		 * @param {number} [options.expectStatus] Expected HTTP status.
		 * @return {Promise<import('@playwright/test').Response>} The response.
		 */
		const visit = async ( url, options = {} ) => {
			const { expectStatus = 200 } = options;

			/**
			 * Registered before navigating, because the browser logs the
			 * document's load failure while `goto` is still in flight.
			 */
			if ( 200 !== expectStatus ) {
				consoleGuard.allowDocumentError( url );
			}

			const response = await page.goto( url, {
				waitUntil: 'domcontentloaded',
			} );

			base
				.expect( response, `no response for ${ url }` )
				.not.toBeNull();

			base
				.expect(
					response.status(),
					`unexpected status for ${ url }`
				)
				.toBe( expectStatus );

			const html = await page.content();

			for ( const pattern of PHP_ERROR_PATTERNS ) {
				const match = html.match( pattern );

				if ( match ) {
					/**
					 * Quote a window around the match — the bare pattern name
					 * tells you a notice happened, the surrounding text tells
					 * you which one.
					 */
					const at = html.indexOf( match[ 0 ] );
					const excerpt = html
						.slice( Math.max( 0, at - 120 ), at + 320 )
						.replace( /\s+/g, ' ' );

					throw new Error(
						`PHP error in markup at ${ url }:\n  …${ excerpt }…`
					);
				}
			}

			return response;
		};

		await use( visit );
	},
} );

module.exports = {
	test,
	expect: base.expect,
	PHP_ERROR_PATTERNS,
	CONSOLE_ALLOWLIST,
};
