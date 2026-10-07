/**
 * Live-region helpers for Playwright, mirroring tests/unit/helpers/live-regions.js.
 *
 * `@wordpress/a11y`'s `speak()` — which the block editor and any `wp-a11y`
 * consumer use — writes into two visually hidden regions. A spec asserts that
 * something was announced by reading them; this module names them once so no
 * spec hard-codes the ids. The ids are copied rather than imported: requiring
 * the package in Node gives the ids no more authority than this file does, and
 * the unit tests pin them against the real package.
 *
 * Deliberately a twin of the same helper in `sd-enhancements-2026`.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { expect } = require( '@playwright/test' );

const LIVE_REGION_IDS = Object.freeze( {
	polite: 'a11y-speak-polite',
	assertive: 'a11y-speak-assertive',
} );

/**
 * @param {import('@playwright/test').Page} page     Page.
 * @param {'polite'|'assertive'}            ariaLive Which region.
 * @return {import('@playwright/test').Locator} The region.
 */
function liveRegion( page, ariaLive = 'polite' ) {
	return page.locator( `#${ LIVE_REGION_IDS[ ariaLive ] }` );
}

/**
 * Assert that a message was announced.
 *
 * `speak()` empties the region and writes the message on the next tick, and
 * the next announcement replaces it — so poll, and match the words rather than
 * the trailing no-break space `speak()` uses to force a repeat.
 *
 * @param {import('@playwright/test').Page} page     Page.
 * @param {string|RegExp}                   message  Expected words.
 * @param {'polite'|'assertive'}            ariaLive Which region.
 * @return {Promise<void>}
 */
async function expectAnnounced( page, message, ariaLive = 'polite' ) {
	await expect( liveRegion( page, ariaLive ) ).toContainText( message );
}

module.exports = { LIVE_REGION_IDS, liveRegion, expectAnnounced };
