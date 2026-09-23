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
 * The gate is a baseline comparison, not an absolute one. That is the org rule,
 * verbatim: "Assert no new violations against the recorded Accessibility
 * baseline, not zero violations outright — an absolute gate fails on day one
 * against existing debt and gets disabled." The measured debt on dev at the time
 * of writing is 19 pages with contrast failures and four critical ARIA rules
 * clustered around the navigation; an absolute gate would be switched off within
 * a week and would then catch nothing.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const fs = require( 'fs' );
const path = require( 'path' );
const AxeBuilder = require( '@axe-core/playwright' ).default;
const { expect } = require( '@playwright/test' );
const { A11Y_EXCLUDES } = require( './dynamic-regions.js' );

/**
 * The standard the project commits to: WCAG 2.2 AA.
 *
 * ⚠️ The org standards disagree with themselves here — `agents/testing-agent/`
 * targets 2.2 AA, `docs/TESTING.md` says 2.1 AA. 2.2 is a superset of 2.1, so
 * taking the newer one cannot lose coverage. Recorded rather than hidden: if
 * the org settles on 2.1, drop the `wcag22aa` tag and regenerate the baseline.
 */
const WCAG_TAGS = [
	'wcag2a',
	'wcag2aa',
	'wcag21a',
	'wcag21aa',
	'wcag22aa',
];

const BASELINE_PATH = path.join( __dirname, '..', 'a11y', 'a11y-baseline.json' );

/**
 * Set SD_UPDATE_A11Y_BASELINE=true to rewrite the baseline from this run.
 *
 * Widening a baseline to make a red run green is how these gates rot, so it is
 * an explicit, separate action — never a fallback when an assertion fails.
 */
const UPDATE_BASELINE = 'true' === process.env.SD_UPDATE_A11Y_BASELINE;

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

/**
 * @return {Object} The recorded baseline, keyed by route.
 */
function readBaseline() {
	if ( ! fs.existsSync( BASELINE_PATH ) ) {
		return {};
	}

	try {
		return JSON.parse( fs.readFileSync( BASELINE_PATH, 'utf8' ) );
	} catch {
		return {};
	}
}

/**
 * @return {boolean} True when a baseline has been recorded at all.
 */
function hasBaseline() {
	return fs.existsSync( BASELINE_PATH );
}

/**
 * Collapse a scan to `{ ruleId: nodeCount }`, which is what the baseline stores.
 *
 * Node counts rather than a bare rule list, so fixing four of five contrast
 * failures on a page still registers as progress and adding a fifth still
 * registers as a regression.
 *
 * @param {Object[]} violations Axe violations.
 * @return {Object<string, number>} Rule id to node count.
 */
function summarise( violations ) {
	const summary = {};

	for ( const violation of violations ) {
		summary[ violation.id ] = violation.nodes.length;
	}

	return summary;
}

/**
 * Write the baseline back to disk.
 *
 * Merged rather than replaced, because a partial run (`--grep`, or a route that
 * skipped for want of content) would otherwise silently delete the entries it
 * did not visit.
 *
 * @param {string}                 route   Route key.
 * @param {Object<string, number>} summary Rule id to node count.
 */
function writeBaseline( route, summary ) {
	const baseline = readBaseline();

	baseline[ route ] = summary;

	const ordered = {};

	for ( const key of Object.keys( baseline ).sort() ) {
		ordered[ key ] = baseline[ key ];
	}

	fs.writeFileSync(
		BASELINE_PATH,
		JSON.stringify( ordered, null, '\t' ) + '\n'
	);
}

/**
 * Assert a page has introduced no accessibility violation the baseline does not
 * already record.
 *
 * Three outcomes:
 *   - a rule not in the baseline at all  → fail, it is new
 *   - more nodes than the baseline holds → fail, it got worse
 *   - fewer                              → pass, and say so, so the baseline
 *                                          can be tightened deliberately
 *
 * @param {Object} results  Axe results.
 * @param {string} route    Route key, used as the baseline key.
 * @param {string} template Template file, for the failure message.
 */
function assertNoNewViolations( results, route, template ) {
	const current = summarise( results.violations );

	if ( UPDATE_BASELINE ) {
		writeBaseline( route, current );

		return;
	}

	const baseline = readBaseline()[ route ] || {};
	const regressions = [];
	const improvements = [];

	for ( const [ rule, count ] of Object.entries( current ) ) {
		const recorded = baseline[ rule ] || 0;

		if ( ! recorded ) {
			regressions.push( `${ rule }: new (${ count } node(s))` );
		} else if ( count > recorded ) {
			regressions.push(
				`${ rule }: ${ recorded } → ${ count } node(s)`
			);
		}
	}

	for ( const [ rule, recorded ] of Object.entries( baseline ) ) {
		const count = current[ rule ] || 0;

		if ( count < recorded ) {
			improvements.push( `${ rule }: ${ recorded } → ${ count }` );
		}
	}

	if ( improvements.length ) {
		/**
		 * Not a failure. Reported so that a fix is visible and the baseline
		 * gets tightened on purpose rather than drifting upward forever.
		 */
		process.stderr.write(
			`\n  ✅ ${ route } improved: ${ improvements.join( ', ' ) }\n` +
				'     Re-record with SD_UPDATE_A11Y_BASELINE=true to lock it in.\n'
		);
	}

	expect(
		regressions,
		`New accessibility violations on ${ route } (${ template }):\n  ` +
			regressions.join( '\n  ' ) +
			'\n\n' +
			formatViolations(
				results.violations.filter( ( violation ) =>
					regressions.some( ( entry ) =>
						entry.startsWith( `${ violation.id }:` )
					)
				)
			) +
			'\n\nThis gate compares against tests/e2e/a11y/a11y-baseline.json. ' +
			'Fix the violation — do not widen the baseline to make this pass.'
	).toEqual( [] );
}

module.exports = {
	scan,
	formatViolations,
	assertNoNewViolations,
	hasBaseline,
	summarise,
	readBaseline,
	WCAG_TAGS,
	BASELINE_PATH,
	UPDATE_BASELINE,
};
