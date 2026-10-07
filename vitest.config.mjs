/**
 * Vitest configuration — unit tests for the theme's front-end scripts.
 *
 * `wp-scripts test-unit-js` runs Vitest with this file. jsdom stands in for the
 * browser, and collection is narrowed to `tests/unit`, so the Playwright suite
 * in `tests/e2e` is never picked up. Unit tests cover script logic; e2e covers
 * the DOM wiring.
 *
 *   npm run test:unit
 *   npm run test:unit:coverage
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

import { defineConfig } from 'vitest/config';

export default defineConfig( {
	test: {
		environment: 'jsdom',
		globals: false,
		restoreMocks: true,
		include: [ 'tests/unit/**/*.test.js' ],
		passWithNoTests: true,
		coverage: {
			provider: 'v8',
			include: [ 'assets/js/**/*.js' ],
			exclude: [ '**/build/**', '**/*.min.js' ],
			reportsDirectory: 'tests/unit/coverage',
		},
	},
} );
