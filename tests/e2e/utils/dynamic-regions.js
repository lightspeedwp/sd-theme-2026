/**
 * Regions that legitimately differ between two loads of the same URL.
 *
 * This list is the difference between a visual suite that catches layout breaks
 * and one that cries wolf every run. The headline case is the hero: LSX Banners
 * picks one of eleven images **in PHP, per request** — it is not a JS slider, so
 * there is no "wait for it to settle". Two consecutive loads of the front page
 * legitimately show different photographs.
 *
 * Used two ways:
 *   - `VISUAL_MASKS`  painted over before a screenshot comparison.
 *   - `A11Y_EXCLUDES` excluded from axe runs, because we cannot fix third-party
 *                     markup and a permanent known failure trains people to
 *                     ignore the report.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

/**
 * Masked in visual comparisons. Each entry needs a reason — an unexplained mask
 * is how a suite quietly stops testing the thing it was written for.
 */
const VISUAL_MASKS = [
	{
		selector: '.wp-block-cover__image-background',
		reason: 'Rotating banner: LSX Banners picks one of 11 images per request in PHP.',
	},
	{
		selector: '.trustpilot-widget',
		reason: 'Third-party review widget — live review content and star counts.',
	},
	{
		selector: '.envira-gallery-wrap',
		reason: 'Envira galleries lazy-load and reflow after paint.',
	},
	{
		selector: '.facetwp-facet',
		reason: 'Facet counts track content and change as data is migrated.',
	},
	{
		selector: '[class*="lsx-to-map"], .sd-team-map, .wp-block-sd-team-map',
		reason: 'Google Maps tiles render asynchronously and differ per load.',
	},
	{
		selector: 'time, .wp-block-post-date',
		reason: 'Dates advance.',
	},
];

/**
 * Excluded from axe scans — third-party markup we do not own.
 *
 * Keep this list short and justified. Anything the theme or sd-enhancements
 * renders must not appear here; fix the markup instead.
 */
const A11Y_EXCLUDES = [
	'.trustpilot-widget',
	'iframe[src*="trustpilot"]',
	'iframe[src*="google.com/maps"]',
	'iframe[src*="youtube"]',
	'.envira-gallery-wrap',
	// Gravity Forms markup is the plugin's, not ours.
	'.gform_wrapper',
];

/**
 * @return {string[]} Selectors only, for APIs that take a flat list.
 */
function maskSelectors() {
	return VISUAL_MASKS.map( ( mask ) => mask.selector );
}

/**
 * Build Playwright locators for every masked region on a page.
 *
 * @param {import('@playwright/test').Page} page Page under test.
 * @return {import('@playwright/test').Locator[]} Locators to mask.
 */
function masksFor( page ) {
	return maskSelectors().map( ( selector ) => page.locator( selector ) );
}

module.exports = {
	VISUAL_MASKS,
	A11Y_EXCLUDES,
	maskSelectors,
	masksFor,
};
