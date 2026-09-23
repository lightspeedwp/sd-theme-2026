/**
 * Playwright configuration — Southern Destinations 2026 block theme.
 *
 * Target selection
 * ----------------
 * `WP_BASE_URL` chooses the environment, read from `.env` at the theme root (see
 * `.env.example`). It defaults to the dev site, which is the only environment
 * carrying real migrated content. Local Studio holds six tours and nothing else,
 * so archive and taxonomy assertions are meaningless there.
 *
 *   npm run test:e2e                                        # dev (default)
 *   WP_BASE_URL=http://localhost:8903 npm run test:e2e      # local Studio
 *
 * Never point this at southerndestinations.com. The suite is read-only, but the
 * live site is the client's production site and is not a test target. The one
 * deliberate exception is the `parity` project, which reads a small sample of
 * live pages to compare content against — it is opt-in and never navigates with
 * production as `baseURL`.
 *
 * Project matrix
 * --------------
 * Breakpoints follow the org standard in `lightspeedwp/.github`
 * (`agents/testing-agent/CROSS_BROWSER_AND_RESPONSIVE_TESTING.md`): mobile
 * 375×667, tablet 768×1024, desktop 1280×800, wide 1920×1080 (optional).
 *
 * The same standard asks for Chromium, Firefox and WebKit as a minimum. Running
 * the whole suite three times over triples the load on a shared dev host for
 * very little signal, so Firefox and WebKit run the `@smoke` subset — the
 * routes where an engine difference would actually show — and Chromium carries
 * the full sweep.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { defineConfig, devices } = require( '@playwright/test' );
const { loadEnv, hasAdminCredentials } = require( './tests/e2e/utils/env.js' );

loadEnv();

const baseURL =
	process.env.WP_BASE_URL || 'https://southerndestinations.lightspeedwp.dev';

if ( /southerndestinations\.com/i.test( baseURL ) ) {
	throw new Error(
		'Refusing to run: WP_BASE_URL points at the production site. Use dev or local.'
	);
}

/**
 * Opt-in projects. Both cost something someone else pays for — `wide` costs
 * runtime, `parity` costs requests against the client's production site — so
 * neither runs unless asked for.
 */
const runWide = 'true' === process.env.SD_RUN_WIDE;
const runParity = 'true' === process.env.SD_RUN_PARITY;

/**
 * Functional specs, run at every breakpoint that opts in with `@responsive`.
 */
const FUNCTIONAL = [ 'templates/**/*.spec.js', 'parts/**/*.spec.js', 'forms/**/*.spec.js' ];

/**
 * Chromium over HTTP/2, not HTTP/3.
 *
 * Dev sits behind Cloudflare, which advertises `h3` in `alt-svc`. Chromium
 * takes it up, and on 2026-09-23 a desktop run logged twelve
 * `net::ERR_QUIC_PROTOCOL_ERROR` resource failures on /contact/ — transport
 * errors between the runner and the edge, which the console guard rightly
 * fails on but which say nothing about the theme. A real browser falls back to
 * HTTP/2 silently; this just makes the runner start there. Chromium-only:
 * Firefox and WebKit do not accept the flag.
 */
const chromium = { launchOptions: { args: [ '--disable-quic' ] } };

const chrome = { ...devices[ 'Desktop Chrome' ], ...chromium };

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
		/**
		 * Signs in once and saves the session. Everything in `editor` depends
		 * on it. Skips itself when no credentials are set, rather than failing
		 * the run — the test user is temporary by design.
		 */
		...( hasAdminCredentials()
			? [ { name: 'setup', testMatch: /auth\.setup\.js/ } ]
			: [] ),

		{
			name: 'desktop',
			testMatch: FUNCTIONAL,
			use: { ...chrome, viewport: { width: 1280, height: 800 } },
		},

		/**
		 * Responsive adaptations only — the estimate does not fund bespoke
		 * mobile or tablet designs, so these check that layouts adapt without
		 * breaking, not that they match a separate comp. Specs opt in with
		 * `@responsive` in the title.
		 */
		{
			name: 'tablet',
			testMatch: FUNCTIONAL,
			grep: /@responsive/,
			use: { ...chrome, viewport: { width: 768, height: 1024 }, isMobile: false },
		},
		{
			name: 'mobile',
			testMatch: FUNCTIONAL,
			grep: /@responsive/,
			use: {
				...devices[ 'Pixel 7' ],
				...chromium,
				viewport: { width: 375, height: 667 },
			},
		},
		...( runWide
			? [
					{
						name: 'wide',
						testMatch: FUNCTIONAL,
						grep: /@responsive/,
						use: {
							...chrome,
							viewport: { width: 1920, height: 1080 },
						},
					},
			  ]
			: [] ),

		/**
		 * Engine coverage. `@smoke` is the subset where a rendering-engine
		 * difference would plausibly show — not a second full pass.
		 */
		{
			name: 'firefox',
			testMatch: FUNCTIONAL,
			grep: /@smoke/,
			use: { ...devices[ 'Desktop Firefox' ], viewport: { width: 1280, height: 800 } },
		},
		{
			name: 'webkit',
			testMatch: FUNCTIONAL,
			grep: /@smoke/,
			use: { ...devices[ 'Desktop Safari' ], viewport: { width: 1280, height: 800 } },
		},

		/**
		 * The responsive contract itself — horizontal overflow, touch target
		 * size, and layout survival under browser font scaling. Drives its own
		 * viewports, so it is not duplicated across the breakpoint projects.
		 */
		{
			name: 'responsive',
			testMatch: 'responsive/**/*.spec.js',
			use: { ...chrome },
		},

		{
			name: 'a11y',
			testMatch: 'a11y/**/*.spec.js',
			use: { ...chrome, viewport: { width: 1280, height: 800 } },
		},

		/**
		 * Visual baselines are environment-specific: dev and local render
		 * different content. Snapshots are keyed by host so the two cannot
		 * overwrite each other.
		 */
		{
			name: 'visual',
			testMatch: 'visual/**/*.spec.js',
			use: { ...chrome, viewport: { width: 1280, height: 800 } },
		},

		/**
		 * Signed-in checks. The one that matters: whether a Site Editor
		 * database override is shadowing a theme template file — the documented
		 * trap in AGENTS.md, and the suspected cause of the front page having
		 * no `main` landmark.
		 */
		...( hasAdminCredentials()
			? [
					{
						name: 'editor',
						testMatch: 'editor/**/*.spec.js',
						dependencies: [ 'setup' ],
						use: {
							...chrome,
							viewport: { width: 1280, height: 800 },
							storageState: './tests/e2e/.auth/admin.json',
						},
					},
			  ]
			: [] ),

		/**
		 * Live-vs-rebuild parity, on a sample. Opt-in with SD_RUN_PARITY=true
		 * because it reads the client's production site — a handful of GETs,
		 * serially, never on every run.
		 */
		...( runParity
			? [
					{
						name: 'parity',
						testMatch: 'parity/**/*.spec.js',
						fullyParallel: false,
						workers: 1,
						use: { ...chrome, viewport: { width: 1280, height: 800 } },
					},
			  ]
			: [] ),
	],

	snapshotPathTemplate:
		'./tests/e2e/visual/__screenshots__/{arg}-{projectName}-{platform}{ext}',
} );
