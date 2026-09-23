/**
 * Live-vs-rebuild parity, on a sample.
 *
 * Opt-in: SD_RUN_PARITY=true. It reads southerndestinations.com, which is the
 * client's production site, so it is not part of a normal run.
 *
 * Sampled, not exhaustive. There are 1,274 content items and an exhaustive
 * comparison is neither funded nor useful — the failures cluster by template,
 * not by item. The sample is live's own primary navigation (capped at 30 paths)
 * plus one resolved single per post type. If the exhaustive version is ever
 * wanted it is a Change-Control Register item, not a quiet expansion of this
 * file.
 *
 * What is compared: URLs resolving, headings, page substance, navigation
 * reachability. What is NOT compared: appearance. The rebuild carries a
 * deliberate light refresh — see utils/parity.js.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test, expect } = require( '../fixtures/base.js' );
const {
	LIVE_ORIGIN,
	normalise,
	navigationPaths,
	contentContract,
	politePause,
} = require( '../utils/parity.js' );

/**
 * Not `serial`. Serial mode skips every test after the first failure, which hid
 * the navigation check behind any content difference — and while the templates
 * are unfinished there is always one. The project config (`workers: 1`,
 * `fullyParallel: false`) already keeps production reads one at a time.
 */

test.describe( 'Live parity @parity', () => {
	/**
	 * @type {string[]}
	 */
	let livePaths = [];

	/**
	 * @type {number}
	 */
	let liveStatus = 0;

	/**
	 * Collected once per worker rather than inside the first test, so a failure
	 * that restarts the worker does not leave the later tests with no sample.
	 */
	test.beforeAll( async ( { browser } ) => {
		const context = await browser.newContext( { baseURL: LIVE_ORIGIN } );
		const page = await context.newPage();

		const response = await page.goto( LIVE_ORIGIN, {
			waitUntil: 'domcontentloaded',
		} );

		liveStatus = response?.status() ?? 0;

		if ( 200 === liveStatus ) {
			livePaths = await navigationPaths( page, LIVE_ORIGIN );
		}

		await context.close();
	} );

	test( 'live is reachable and its navigation is readable', async () => {
		expect(
			liveStatus,
			`${ LIVE_ORIGIN } did not return 200 — parity cannot be measured`
		).toBe( 200 );

		expect(
			livePaths.length,
			'found no same-origin navigation links on live — the selector in ' +
				'utils/parity.js probably needs updating for live markup'
		).toBeGreaterThan( 0 );

		process.stderr.write(
			`\n  Parity sample: ${ livePaths.length } paths from live navigation\n`
		);
	} );

	test( 'every live navigation route still resolves on the rebuild', async ( {
		page,
	} ) => {
		test.skip( 0 === livePaths.length, 'No live paths were collected' );

		/**
		 * The single most valuable parity assertion, and the one with real
		 * commercial weight: a URL that worked on live and 404s on the rebuild
		 * is lost traffic and a lost search ranking.
		 */
		const broken = [];

		for ( const pathname of livePaths ) {
			const response = await page.goto( pathname, {
				waitUntil: 'domcontentloaded',
			} );

			const status = response?.status() ?? 0;

			if ( 200 !== status ) {
				broken.push( `${ pathname } → ${ status }` );
			}

			await politePause( 100 );
		}

		expect(
			broken,
			'Routes that resolve on live but not on the rebuild:\n  ' +
				broken.join( '\n  ' ) +
				'\n\nEach needs either a template, a redirect, or a deliberate ' +
				'decision that it is retired.'
		).toEqual( [] );
	} );

	test( 'sampled pages keep their heading and their substance', async ( {
		browser,
		page,
	} ) => {
		test.skip( 0 === livePaths.length, 'No live paths were collected' );

		/**
		 * A smaller sample again — comparing contracts means two page loads
		 * each, one of them against production.
		 */
		const sample = livePaths.slice( 0, 8 );

		const liveContext = await browser.newContext( { baseURL: LIVE_ORIGIN } );
		const livePage = await liveContext.newPage();

		const differences = [];

		for ( const pathname of sample ) {
			await livePage.goto( pathname, { waitUntil: 'domcontentloaded' } );
			const live = await contentContract( livePage );

			await page.goto( pathname, { waitUntil: 'domcontentloaded' } );
			const rebuilt = await contentContract( page );

			/**
			 * Content is migrated, not rewritten (AGENTS.md), so the primary
			 * heading should survive. Compared normalised — curly quotes and
			 * dashes legitimately change in migration.
			 */
			if (
				live.h1 &&
				normalise( live.h1 ) !== normalise( rebuilt.h1 )
			) {
				differences.push(
					`${ pathname }\n      live h1: "${ live.h1 }"\n      rebuilt: "${ rebuilt.h1 }"`
				);
			}

			/**
			 * A page that renders its chrome and almost nothing else. The tour
			 * and accommodation archives are doing exactly this today, and it
			 * returns 200 while doing it — which is why a status check alone
			 * is not enough.
			 */
			if (
				500 < live.mainTextLength &&
				rebuilt.mainTextLength < live.mainTextLength * 0.25
			) {
				differences.push(
					`${ pathname }\n      live main: ${ live.mainTextLength } chars, ` +
						`rebuilt: ${ rebuilt.mainTextLength } chars — the page ` +
						'renders but is substantially empty'
				);
			}

			await politePause();
		}

		await liveContext.close();

		expect(
			differences,
			'Content parity differences against live:\n  ' +
				differences.join( '\n  ' )
		).toEqual( [] );
	} );

	test( 'the rebuild has not lost a top-level navigation destination', async ( {
		browser,
		page,
	} ) => {
		const liveContext = await browser.newContext( { baseURL: LIVE_ORIGIN } );
		const livePage = await liveContext.newPage();

		await livePage.goto( LIVE_ORIGIN, { waitUntil: 'domcontentloaded' } );
		const liveLabels = await livePage
			.locator( 'header a[href], nav a[href]' )
			.evaluateAll( ( nodes ) =>
				nodes
					.map( ( node ) => ( node.textContent || '' ).trim() )
					.filter( Boolean )
			);

		await liveContext.close();

		await page.goto( '/', { waitUntil: 'domcontentloaded' } );
		const rebuiltLabels = await page
			.locator( 'header a[href], nav a[href]' )
			.evaluateAll( ( nodes ) =>
				nodes
					.map( ( node ) => ( node.textContent || '' ).trim() )
					.filter( Boolean )
			);

		const rebuiltSet = new Set( rebuiltLabels.map( normalise ) );

		/**
		 * Top-level only. Live's mega menus list hundreds of destinations and
		 * the rebuild's do not have to match item for item — but a top-level
		 * section disappearing is a structural regression.
		 */
		const missing = [ ...new Set( liveLabels.map( normalise ) ) ]
			.filter( ( label ) => 2 < label.length && 30 > label.length )
			.filter( ( label ) => ! rebuiltSet.has( label ) );

		/**
		 * Reported, not asserted to zero. Live's navigation includes campaign
		 * links and WooCommerce routes that are deliberately not carried, so a
		 * hard assertion here would be permanently red and would teach people
		 * to ignore the parity project. The list is the deliverable; a human
		 * decides which absences are intentional.
		 */
		if ( missing.length ) {
			test.info().annotations.push( {
				type: 'navigation-labels-not-found',
				description: missing.join( ', ' ),
			} );

			process.stderr.write(
				`\n  ⚠️ ${ missing.length } live navigation label(s) not found on the rebuild:\n` +
					`     ${ missing.slice( 0, 40 ).join( ', ' ) }` +
					( 40 < missing.length
						? ` … and ${ missing.length - 40 } more (see the annotation)`
						: '' ) +
					'\n' +
					'     Review these — some are deliberate (WooCommerce, campaigns), some are not.\n'
			);
		}

		expect(
			rebuiltLabels.length,
			'the rebuild rendered no navigation links at all'
		).toBeGreaterThan( 0 );
	} );
} );
