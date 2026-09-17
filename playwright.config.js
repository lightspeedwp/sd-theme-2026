/**
 * Playwright configuration — Southern Destinations 2026 block theme.
 *
 * Target selection
 * ----------------
 * `WP_BASE_URL` chooses the environment. It defaults to the dev site, which is
 * the only environment carrying real migrated content (366 accommodations, 122
 * destinations, 112 reviews). Local Studio holds six tours and nothing else, so
 * archive and taxonomy assertions are meaningless there.
 *
 *   npm run test:e2e                                        # dev (default)
 *   WP_BASE_URL=http://localhost:8903 npm run test:e2e      # local Studio
 *
 * Never point this at southerndestinations.com. The suite is read-only, but the
 * live site is the client's production site and is not a test target.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { defineConfig, devices } = require( '@playwright/test' );

const baseURL =
	process.env.WP_BASE_URL || 'https://southerndestinations.lightspeedwp.dev';

if ( /southerndestinations\.com/i.test( baseURL ) ) {
	throw new Error(
		'Refusing to run: WP_BASE_URL points at the production site. Use dev or local.'
	);
}

module.exports = defineConfig( {
	testDir: './tests/e2e',

	/**
	 * Route resolution. Singles and taxonomy archives need a real permalink, and
	 * hardcoded slugs rot. Global setup resolves one live URL per content type
	 * from the REST API and caches it for the run.
	 */
	globalSetup: require.resolve( './tests/e2e/global-setup.js' ),

	/**
	 * Output paths sit under tests/ to match the ignores already in .gitignore
	 * (`tests/**\/test-results/`, `tests/**\/playwright-report/`).
	 */
	outputDir: './tests/e2e/test-results',

	timeout: 45 * 1000,
	expect: {
		timeout: 10 * 1000,
		/**
		 * Visual comparison tolerance. The theme uses webfonts and responsive
		 * images; sub-pixel text rendering differs between machines. 0.2% of
		 * pixels is tight enough to catch a layout break and loose enough to
		 * survive a font rasterisation difference.
		 */
		toHaveScreenshot: {
			maxDiffPixelRatio: 0.002,
			animations: 'disabled',
			caret: 'hide',
			scale: 'css',
		},
	},

	/**
	 * These specs are read-only HTTP GETs against a shared server — they carry
	 * no fixture state and cannot collide with each other, so unlike an admin
	 * suite they parallelise safely. Workers are capped to stay polite to dev.
	 */
	fullyParallel: true,
	workers: process.env.CI ? 2 : 4,
	forbidOnly: !! process.env.CI,
	retries: process.env.CI ? 2 : 0,

	reporter: [
		[ 'html', { outputFolder: './tests/e2e/playwright-report', open: 'never' } ],
		[ 'list' ],
		...( process.env.CI ? [ [ 'github' ] ] : [] ),
	],

	use: {
		baseURL,
		trace: 'on-first-retry',
		screenshot: 'only-on-failure',
		video: 'retain-on-failure',
		/**
		 * Dev is not a fast host and is shared. Give navigation room before
		 * calling a slow page a failure.
		 */
		navigationTimeout: 30 * 1000,
		actionTimeout: 10 * 1000,
		/**
		 * No custom request header here, deliberately.
		 *
		 * An `X-SD-Test-Suite` header seemed a tidy way to make the suite
		 * attributable in dev's access logs, but Playwright applies
		 * `extraHTTPHeaders` to every request the page makes — including
		 * cross-origin ones. Third-party embeds (the WETU itinerary iframe,
		 * Google Fonts) then fail CORS preflight with "Request header field
		 * x-sd-test-suite is not allowed", which surfaces as console errors on
		 * pages that are in fact fine. Identify the suite by user agent or at
		 * the network layer if it is ever needed.
		 */
	},

	projects: [
		{
			name: 'desktop',
			testMatch: [ 'templates/**/*.spec.js', 'parts/**/*.spec.js' ],
			use: {
				...devices[ 'Desktop Chrome' ],
				viewport: { width: 1440, height: 900 },
			},
		},

		/**
		 * Responsive adaptations only — the estimate does not fund bespoke
		 * mobile designs, so this checks that layouts adapt without breaking,
		 * not that they match a separate mobile comp. Specs opt in with
		 * `@responsive` in the title.
		 */
		{
			name: 'mobile',
			testMatch: [ 'templates/**/*.spec.js', 'parts/**/*.spec.js' ],
			grep: /@responsive/,
			use: { ...devices[ 'Pixel 7' ] },
		},

		{
			name: 'a11y',
			testMatch: 'a11y/**/*.spec.js',
			use: {
				...devices[ 'Desktop Chrome' ],
				viewport: { width: 1440, height: 900 },
			},
		},

		/**
		 * Visual baselines are environment-specific: dev and local render
		 * different content. Snapshots are keyed by host so the two cannot
		 * overwrite each other.
		 */
		{
			name: 'visual',
			testMatch: 'visual/**/*.spec.js',
			use: {
				...devices[ 'Desktop Chrome' ],
				viewport: { width: 1440, height: 900 },
			},
		},
	],

	snapshotPathTemplate:
		'./tests/e2e/visual/__screenshots__/{arg}-{projectName}-{platform}{ext}',
} );
