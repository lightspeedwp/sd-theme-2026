/**
 * Live-region helpers built on `@wordpress/a11y`.
 *
 * `@wordpress/a11y` is core's announcer: `speak()` writes into two visually
 * hidden ARIA live regions, `#a11y-speak-polite` and `#a11y-speak-assertive`,
 * which `setup()` creates. Anything in WordPress that announces — the editor,
 * `wp.a11y.speak()` from a classic script — goes through those two nodes, so
 * reading them back is how a test asserts that a screen reader was told.
 *
 * The shipped scripts here are ES5 with no imports, so they reach the same
 * announcer as the `wp.a11y` global (script handle `wp-a11y`).
 * `installWpA11yGlobal()` puts the real package behind that global for a test.
 *
 * Deliberately a twin of the same helper in `sd-enhancements-2026`.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const a11y = require( '@wordpress/a11y' );

/**
 * The live region ids `@wordpress/a11y` creates, by politeness.
 */
const LIVE_REGION_IDS = Object.freeze( {
	polite: 'a11y-speak-polite',
	assertive: 'a11y-speak-assertive',
} );

/**
 * Remove any live regions left by an earlier test and create fresh ones.
 *
 * @return {void}
 */
function resetLiveRegions() {
	document
		.querySelectorAll(
			'#a11y-speak-polite, #a11y-speak-assertive, #a11y-speak-intro-text'
		)
		.forEach( ( node ) => node.remove() );

	a11y.setup();
}

/**
 * What a live region currently holds, as a screen reader would hear it.
 *
 * `speak()` alternates a trailing no-break space so that saying the same
 * message twice still counts as a change; that is normalised away here.
 *
 * @param {'polite'|'assertive'} [ariaLive='polite'] Which region.
 * @return {string} The announcement, or '' when there is none.
 */
function announcement( ariaLive = 'polite' ) {
	const region = document.getElementById( LIVE_REGION_IDS[ ariaLive ] );

	return region ? region.textContent.replace( /\u00a0/g, ' ' ).trim() : '';
}

/**
 * Expose the real package as `window.wp.a11y`, as the `wp-a11y` handle would.
 *
 * @return {void}
 */
function installWpA11yGlobal() {
	window.wp = window.wp || {};
	window.wp.a11y = { speak: a11y.speak, setup: a11y.setup };
}

module.exports = {
	LIVE_REGION_IDS,
	announcement,
	installWpA11yGlobal,
	resetLiveRegions,
	speak: a11y.speak,
};
