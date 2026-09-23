/**
 * Live-vs-rebuild comparison helpers.
 *
 * What parity means here
 * ----------------------
 * Two different baselines are in play and confusing them produces a suite that
 * is either useless or permanently red:
 *
 *   Live is the FUNCTIONAL and CONTENT reference. Does the URL still resolve?
 *   Is the heading the same? Did the section survive? Is the navigation still
 *   reachable? Content is migrated, not rewritten (AGENTS.md), so these are
 *   fair assertions.
 *
 *   Dev-against-itself is the VISUAL reference. The rebuild carries a
 *   deliberate light refresh, so a pixel comparison against live would fail on
 *   every page by design and tell us nothing. Visual regression lives in
 *   visual/, keyed to dev.
 *
 * Nothing in here compares appearance.
 *
 * Politeness
 * ----------
 * This reads the client's production site. It is opt-in (SD_RUN_PARITY=true),
 * serial, capped, and GET-only. Never add a write, a form submission, or a
 * crawl to this file.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const LIVE_ORIGIN = process.env.SD_LIVE_URL || 'https://southerndestinations.com';

/**
 * Hard cap on live requests per run. The navigation sample is naturally around
 * thirty links; this stops a menu change turning the suite into a crawler.
 */
const MAX_LIVE_REQUESTS = 30;

/**
 * Pause between live requests, in milliseconds.
 */
const LIVE_REQUEST_DELAY = 400;

/**
 * Paths that are not worth comparing even when they appear in the navigation.
 *
 * Each needs a reason. An unexplained exclusion is how a parity suite quietly
 * stops covering the thing it was written for.
 */
const PARITY_EXCLUDES = [
	{ pattern: /^\/wp-admin/, reason: 'Admin, not a front-end page.' },
	{ pattern: /^\/wp-login/, reason: 'Login screen.' },
	{ pattern: /^\/feed/, reason: 'Feed, not a rendered template.' },
	{ pattern: /\.(jpg|jpeg|png|gif|svg|pdf|zip)$/i, reason: 'Asset, not a page.' },
	{
		pattern: /^\/(cart|checkout|my-account)/,
		reason: 'WooCommerce routes on live; the rebuild is enquiry-led and has no booking engine.',
	},
];

/**
 * @param {string} pathname Path to test.
 * @return {string|null} The reason it is excluded, or null when it is in scope.
 */
function excludedReason( pathname ) {
	const match = PARITY_EXCLUDES.find( ( rule ) =>
		rule.pattern.test( pathname )
	);

	return match ? match.reason : null;
}

/**
 * Normalise text for comparison across two renderings of the same content.
 *
 * Collapses whitespace, strips the typographic variants that a migration
 * legitimately changes (curly vs straight quotes, en vs em dashes, non-breaking
 * spaces), and lowercases. A heading that differs only in punctuation is the
 * same heading.
 *
 * @param {string} text Raw text.
 * @return {string} Normalised text.
 */
function normalise( text ) {
	return String( text || '' )
		.replace( / /g, ' ' )
		.replace( /[‘’]/g, "'" )
		.replace( /[“”]/g, '"' )
		.replace( /[–—]/g, '-' )
		.replace( /\s+/g, ' ' )
		.trim()
		.toLowerCase();
}

/**
 * Pull the same-origin links out of a page's primary navigation.
 *
 * Reads the rendered DOM rather than a hardcoded list, so the sample follows
 * live's own information architecture. That is the point: the question is
 * whether every route a visitor can reach today still resolves tomorrow.
 *
 * @param {import('@playwright/test').Page} page   Page on the live site.
 * @param {string}                          origin Live origin.
 * @return {Promise<string[]>} Unique pathnames, capped.
 */
async function navigationPaths( page, origin ) {
	const hrefs = await page
		.locator( 'header a[href], nav a[href], .main-navigation a[href]' )
		.evaluateAll( ( nodes ) =>
			nodes.map( ( node ) => node.getAttribute( 'href' ) || '' )
		);

	const seen = new Set();
	const paths = [];

	for ( const href of hrefs ) {
		let pathname;

		try {
			const url = new URL( href, origin );

			/**
			 * Off-site links are someone else's problem, and a fragment is
			 * the same page.
			 */
			if ( url.origin !== new URL( origin ).origin ) {
				continue;
			}

			pathname = url.pathname;
		} catch {
			continue;
		}

		if ( '' === pathname || seen.has( pathname ) ) {
			continue;
		}

		if ( excludedReason( pathname ) ) {
			continue;
		}

		seen.add( pathname );
		paths.push( pathname );

		if ( paths.length >= MAX_LIVE_REQUESTS ) {
			break;
		}
	}

	return paths;
}

/**
 * Extract the content contract of a rendered page.
 *
 * Deliberately shallow. The aim is "did this page survive the rebuild", not
 * "are these two documents identical" — the second question has no useful
 * answer when one of them has been rebuilt in blocks.
 *
 * @param {import('@playwright/test').Page} page Page under test.
 * @return {Promise<Object>} The contract.
 */
async function contentContract( page ) {
	return page.evaluate( () => {
		const text = ( node ) => ( node ? ( node.textContent || '' ).trim() : '' );

		return {
			title: document.title || '',
			h1: text( document.querySelector( 'h1' ) ),
			headings: Array.from(
				document.querySelectorAll( 'main h2, main h3, h2, h3' )
			)
				.slice( 0, 40 )
				.map( ( node ) => ( node.textContent || '' ).trim() )
				.filter( Boolean ),
			metaDescription:
				document
					.querySelector( 'meta[name="description"]' )
					?.getAttribute( 'content' ) || '',
			canonical:
				document
					.querySelector( 'link[rel="canonical"]' )
					?.getAttribute( 'href' ) || '',
			/**
			 * A rough size signal. A page that renders its chrome and nothing
			 * else is the failure mode the tour and accommodation archives
			 * are already exhibiting.
			 */
			mainTextLength: text(
				document.querySelector( 'main, [role="main"]' )
			).length,
		};
	} );
}

/**
 * @param {number} ms Milliseconds.
 * @return {Promise<void>} Resolves after the delay.
 */
function politePause( ms = LIVE_REQUEST_DELAY ) {
	return new Promise( ( resolve ) => setTimeout( resolve, ms ) );
}

module.exports = {
	LIVE_ORIGIN,
	MAX_LIVE_REQUESTS,
	LIVE_REQUEST_DELAY,
	PARITY_EXCLUDES,
	excludedReason,
	normalise,
	navigationPaths,
	contentContract,
	politePause,
};
