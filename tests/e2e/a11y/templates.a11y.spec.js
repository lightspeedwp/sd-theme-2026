/**
 * Automated accessibility scans across the template set.
 *
 * Scope: AGENTS.md excludes a certified accessibility audit from the
 * deliverables, and this is not one. It is the mechanical layer — contrast,
 * accessible names, roles, landmarks, form labels — which is roughly a third of
 * WCAG and all of the part that regresses silently. A clean run means "nothing
 * obviously broken", not "accessible".
 *
 * The gate compares against a recorded baseline rather than demanding zero, per
 * the org rule. See utils/axe.js for why, and a11y-baseline.json for the debt it
 * currently tolerates. Fix violations; never widen the baseline to go green.
 *
 * Until a baseline has been recorded at all, the scans report to stderr and do
 * not fail — the same rule as the plugin's gate. Recording one
 * (`npm run test:a11y:baseline`) is what turns the gate on.
 *
 * Third-party widgets are excluded in utils/dynamic-regions.js. Nothing the
 * theme or sd-enhancements renders is excluded.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );
const {
	STATIC_ROUTES,
	RESOLVED_ROUTES,
	RESOLVED_TAXONOMIES,
	SYNTHETIC_ROUTES,
} = require( '../fixtures/routes.js' );
const {
	scan,
	assertNoNewViolations,
	formatViolations,
	hasBaseline,
	UPDATE_BASELINE,
} = require( '../utils/axe.js' );
const { assertHeadingHierarchy } = require( '../utils/page-contract.js' );

/**
 * Report a scan when no baseline exists yet.
 *
 * Deliberately not a failure and deliberately not silent: an un-baselined gate
 * that prints nothing is indistinguishable from a passing one, and that is how
 * these quietly stop being real. Matches the sd-enhancements-2026 gate, so CI is
 * not red on every template before a baseline has ever been recorded.
 *
 * @param {Object} results  Axe results.
 * @param {string} route    Route key.
 * @param {string} template Template file, for the message.
 */
function reportOnly( results, route, template ) {
	if ( ! results.violations.length ) {
		process.stderr.write( `\n  ✅ ${ route }: no violations to baseline.\n` );

		return;
	}

	process.stderr.write(
		`\n  ⏸  ${ route } (${ template }): ${ results.violations.length } ` +
			'violation(s), NOT failing — no baseline recorded yet.\n' +
			`${ formatViolations( results.violations ) }\n` +
			'     Record with: npm run test:a11y:baseline\n'
	);
}

/**
 * Gate on the baseline once one exists; until then, report.
 *
 * @param {Object} results  Axe results.
 * @param {string} route    Route key, used as the baseline key.
 * @param {string} template Template file, for messages.
 */
function check( results, route, template ) {
	if ( UPDATE_BASELINE || hasBaseline() ) {
		assertNoNewViolations( results, route, template );

		return;
	}

	reportOnly( results, route, template );
}

test.describe( 'Accessibility — static routes @a11y', () => {
	for ( const route of STATIC_ROUTES ) {
		test( `${ route.name } introduces no new WCAG 2.2 AA violations`, async ( {
			page,
			visit,
		} ) => {
			await visit( route.path );

			// `axeDisable` is a per-route decision, documented in routes.js.
			check(
				await scan( page, { disableRules: route.axeDisable || [] } ),
				route.path,
				route.template
			);
		} );
	}
} );

test.describe( 'Accessibility — single templates @a11y', () => {
	for ( const route of RESOLVED_ROUTES ) {
		test( `${ route.name } introduces no new WCAG 2.2 AA violations`, async ( {
			page,
			visit,
			routes,
		} ) => {
			const target = routes.post( route.key );
			test.skip( ! target, `No published ${ route.key }` );

			await visit( target );

			/**
			 * Keyed by post type, not by permalink. The resolved slug changes
			 * as content is migrated, and a baseline keyed on it would go
			 * stale every time the newest tour changed.
			 */
			check(
				await scan( page ),
				`single:${ route.key }`,
				route.template
			);
		} );
	}
} );

test.describe( 'Accessibility — taxonomy archives @a11y', () => {
	for ( const route of RESOLVED_TAXONOMIES ) {
		test( `${ route.name } introduces no new WCAG 2.2 AA violations`, async ( {
			page,
			visit,
			routes,
		} ) => {
			const target = routes.term( route.key );
			test.skip( ! target, `No populated term in ${ route.key }` );

			await visit( target );

			check(
				await scan( page ),
				`taxonomy:${ route.key }`,
				route.template
			);
		} );
	}
} );

test.describe( 'Accessibility — search and 404 @a11y', () => {
	for ( const route of SYNTHETIC_ROUTES ) {
		test( `${ route.name } introduces no new WCAG 2.2 AA violations`, async ( {
			page,
			visit,
		} ) => {
			await visit( route.path, {
				expectStatus: route.expectStatus || 200,
			} );

			check(
				await scan( page ),
				route.path,
				route.template
			);
		} );
	}
} );

test.describe( 'Accessibility — structure @a11y', () => {
	test( 'front page heading hierarchy has no skipped levels', async ( {
		page,
		visit,
	} ) => {
		await visit( '/' );
		await assertHeadingHierarchy( page );
	} );

	test( 'single tour heading hierarchy has no skipped levels', async ( {
		page,
		visit,
		routes,
	} ) => {
		const target = routes.post( 'tour' );
		test.skip( ! target, 'No published tour' );

		await visit( target );
		await assertHeadingHierarchy( page );
	} );

	/**
	 * Landmarks are what a screen-reader user navigates by. Losing the `main`
	 * or ending up with two unlabelled `nav`s is a common casualty of a
	 * template-part rebuild.
	 */
	test( 'landmarks are present and navigations are distinguishable', async ( {
		page,
		visit,
	} ) => {
		await visit( '/' );

		await expect( page.getByRole( 'banner' ) ).toHaveCount( 1 );
		await expect( page.getByRole( 'contentinfo' ) ).toHaveCount( 1 );
		await expect( page.getByRole( 'main' ) ).toHaveCount( 1 );

		const navNames = await page
			.getByRole( 'navigation' )
			.evaluateAll( ( nodes ) =>
				/**
				 * The computed name, not the attribute: two landmarks
				 * labelled by different ids that point at the same heading
				 * share a name, and an id that resolves to nothing is no name.
				 * A `nav` takes its name from `aria-labelledby`, then
				 * `aria-label` — never from its content.
				 */
				nodes.map( ( node ) => {
					const labelledBy = ( node.getAttribute( 'aria-labelledby' ) || '' )
						.split( /\s+/ )
						.filter( Boolean );

					if ( labelledBy.length ) {
						return labelledBy
							.map( ( id ) => document.getElementById( id )?.textContent || '' )
							.join( ' ' )
							.replace( /\s+/g, ' ' )
							.trim();
					}

					return ( node.getAttribute( 'aria-label' ) || '' ).trim();
				} )
			);

		const unnamed = navNames.filter( ( name ) => '' === name );

		expect(
			unnamed.length,
			`${ unnamed.length } navigation landmark(s) have no accessible name`
		).toBe( 0 );

		const duplicates = navNames.filter(
			( name, index ) => name && navNames.indexOf( name ) !== index
		);

		expect(
			duplicates,
			`duplicate navigation names: ${ duplicates.join( ', ' ) }`
		).toEqual( [] );
	} );
} );
