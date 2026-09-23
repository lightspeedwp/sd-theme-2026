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
const { scan, assertNoNewViolations } = require( '../utils/axe.js' );
const { assertHeadingHierarchy } = require( '../utils/page-contract.js' );

test.describe( 'Accessibility — static routes @a11y', () => {
	for ( const route of STATIC_ROUTES ) {
		test( `${ route.name } introduces no new WCAG 2.2 AA violations`, async ( {
			page,
			visit,
		} ) => {
			await visit( route.path );

			assertNoNewViolations(
				await scan( page ),
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
			assertNoNewViolations(
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

			assertNoNewViolations(
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

			assertNoNewViolations(
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
				nodes.map(
					( node ) =>
						node.getAttribute( 'aria-label' ) ||
						node.getAttribute( 'aria-labelledby' ) ||
						''
				)
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
