/**
 * Accessibility scanning.
 *
 * Scope note: AGENTS.md excludes a *certified* accessibility audit from the
 * deliverables. This is not that. It is an automated regression net that catches
 * the mechanical failures — contrast, names, roles, landmarks, form labels — so
 * that a human review can spend its time on the things a scanner cannot see.
 * Roughly a third of WCAG is machine-checkable; treat a clean run as "nothing
 * obviously broken", not "accessible".
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const AxeBuilder = require( '@axe-core/playwright' ).default;
const { A11Y_EXCLUDES } = require( './dynamic-regions.js' );

/**
 * The standard the project commits to: WCAG 2.1 AA.
 */
const WCAG_TAGS = [ 'wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa' ];

/**
 * Run an axe scan against the current page.
 *
 * @param {import('@playwright/test').Page} page      Page under test.
 * @param {Object}                          [options] Options.
 * @param {string}                          [options.include] Restrict the scan to a selector.
 * @param {string[]}                        [options.disableRules] Rules to skip, with a reason in the spec.
 * @return {Promise<Object>} The axe results object.
 */
async function scan( page, options = {} ) {
	const { include, disableRules = [] } = options;

	let builder = new AxeBuilder( { page } ).withTags( WCAG_TAGS );

	if ( include ) {
		builder = builder.include( include );
	}

	for ( const selector of A11Y_EXCLUDES ) {
		builder = builder.exclude( selector );
	}

	if ( disableRules.length ) {
		builder = builder.disableRules( disableRules );
	}

	return builder.analyze();
}

/**
 * Turn axe violations into a message a developer can act on without opening the
 * HTML report: rule, impact, the help URL, and the actual offending markup.
 *
 * @param {Object[]} violations Axe violations.
 * @return {string} Formatted failure message.
 */
function formatViolations( violations ) {
	return violations
		.map( ( violation ) => {
			const nodes = violation.nodes
				.slice( 0, 3 )
				.map( ( node ) => `      ${ node.html.slice( 0, 160 ) }` )
				.join( '\n' );

			const more =
				3 < violation.nodes.length
					? `\n      …and ${ violation.nodes.length - 3 } more`
					: '';

			return (
				`  [${ violation.impact }] ${ violation.id } — ${ violation.help }\n` +
				`    ${ violation.helpUrl }\n` +
				`${ nodes }${ more }`
			);
		} )
		.join( '\n\n' );
}

module.exports = { scan, formatViolations, WCAG_TAGS };
