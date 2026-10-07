/**
 * Jest configuration — unit tests for the theme's front-end scripts.
 *
 * Extends the `@wordpress/scripts` default (jsdom, babel transform, WP preset)
 * and narrows it to `tests/unit`, so the Playwright suite in `tests/e2e` is
 * never collected. Unit tests cover script logic; e2e covers the DOM wiring.
 *
 *   npm run test:unit
 *   npm run test:unit:coverage
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const defaultConfig = require( '@wordpress/scripts/config/jest-unit.config' );

module.exports = {
	...defaultConfig,
	roots: [ '<rootDir>/tests/unit' ],
	passWithNoTests: true,
	collectCoverageFrom: [ '<rootDir>/assets/js/**/*.js', '!**/*.min.js' ],
	coverageDirectory: '<rootDir>/tests/unit/coverage',
};
