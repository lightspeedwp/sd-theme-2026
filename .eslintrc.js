/**
 * ESLint configuration — `npm run lint:js`.
 *
 * `@wordpress/eslint-plugin`'s recommended set, with three scoped adjustments:
 *
 * 1. **Shipped scripts are ES5 by design.** `assets/js/**` is enqueued as-is,
 *    with no build step and no `@wordpress/*` imports, so the ES2015 style
 *    rules that assume a bundler — `no-var`, `object-shorthand`,
 *    `prefer-const` — are switched off there and nowhere else. Correctness
 *    rules still apply. FacetWP's `FWP` is a page global there too.
 * 2. **File headers carry `@package` / `@subpackage`.** That is the WordPress PHP
 *    docblock convention, mirrored in JS. JSDoc reads `@package` as an empty
 *    access tag, so `jsdoc/check-tag-names` is told about both tags and
 *    `jsdoc/empty-tags` is off.
 * 3. **Tests get their runner's globals** — Jest for `tests/unit`, Playwright for
 *    `tests/e2e`, via the plugin's own `test-unit` and `test-playwright` configs.
 *
 * Deliberately a twin of the same file in `sd-enhancements-2026`.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const HEADER_TAGS = [ 'package', 'subpackage' ];

module.exports = {
	root: true,
	extends: [ 'plugin:@wordpress/eslint-plugin/recommended' ],
	settings: {
		jsdoc: {
			mode: 'typescript',
		},
	},
	rules: {
		'jsdoc/check-tag-names': [ 'error', { definedTags: HEADER_TAGS } ],
		// `@package` is an access tag to JSDoc and must be empty; to WordPress it
		// names the package. The WordPress meaning wins in this codebase.
		'jsdoc/empty-tags': 'off',
	},
	overrides: [
		{
			files: [ 'assets/js/**/*.js' ],
			env: { browser: true },
			globals: { wp: 'readonly', jQuery: 'readonly', FWP: 'readonly' },
			rules: {
				'no-var': 'off',
				'object-shorthand': 'off',
				'prefer-const': 'off',
			},
		},
		{
			files: [ 'tests/unit/**/*.js' ],
			extends: [ 'plugin:@wordpress/eslint-plugin/test-unit' ],
			env: { browser: true },
		},
		{
			files: [ 'tests/e2e/**/*.js', 'playwright.config.js' ],
			extends: [ 'plugin:@wordpress/eslint-plugin/test-playwright' ],
			env: { node: true },
			rules: {
				// The suite skips — with a stated reason — when a route has no
				// content or the temporary admin account is absent. That is the
				// documented contract in tests/e2e/README.md, not a forgotten
				// `.skip`.
				'playwright/no-skipped-test': 'off',
				// Data-shaped specs branch on what dev actually holds. Visible,
				// but not a gate.
				'playwright/no-conditional-in-test': 'warn',
			},
		},
		{
			files: [ '*.config.js', '.eslintrc.js' ],
			env: { node: true },
		},
	],
};
