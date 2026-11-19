/**
 * ESLint flat configuration — `npm run lint:js`.
 *
 * Starts from the `@wordpress/scripts` default — ignores, the
 * `@wordpress/eslint-plugin` recommended set, Vitest rules for `*.test.js`, and
 * the Babel parser defaults — then makes three scoped adjustments:
 *
 * 1. **Shipped scripts are ES5 by design.** `assets/js/**` is enqueued as-is,
 *    with no build step and no `@wordpress/*` imports, so the ES2015 style
 *    rules that assume a bundler — `no-var`, `object-shorthand`,
 *    `prefer-const` — are switched off there and nowhere else. Correctness
 *    rules still apply.
 * 2. **File headers carry `@package` / `@subpackage`.** That is the WordPress PHP
 *    docblock convention, mirrored in JS. JSDoc reads `@package` as an empty
 *    access tag, so `jsdoc/check-tag-names` is told about both tags and
 *    `jsdoc/empty-tags` is off.
 * 3. **Tests get their runner's rules** — Vitest for `tests/unit` (from the
 *    default), Playwright for `tests/e2e` via the plugin's `test-playwright`
 *    config.
 *
 * Deliberately a twin of the same file in `sd-enhancements-2026`.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const globals = require( 'globals' );
const wpPlugin = require( '@wordpress/eslint-plugin' );
const wpScriptsDefault = require( '@wordpress/scripts/config/eslint.config.cjs' );

const HEADER_TAGS = [ 'package', 'subpackage' ];

module.exports = [
	{
		ignores: [
			'**/build/**',
			'**/node_modules/**',
			'**/vendor/**',
			'tests/e2e/playwright-report/**',
			'tests/e2e/test-results/**',
			'tests/unit/coverage/**',
			'artifacts/**',
		],
	},
	...wpScriptsDefault,
	{
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
	},
	{
		files: [ 'assets/js/**/*.js' ],
		languageOptions: {
			sourceType: 'script',
			globals: {
				...globals.browser,
				wp: 'readonly',
				jQuery: 'readonly',
				// FacetWP's global.
				FWP: 'readonly',
			},
		},
		rules: {
			'no-var': 'off',
			'object-shorthand': 'off',
			'prefer-const': 'off',
		},
	},
	{
		files: [ 'tests/unit/**/*.js' ],
		languageOptions: {
			globals: globals.browser,
		},
	},
	...wpPlugin.configs[ 'test-playwright' ].map( ( config ) => ( {
		...config,
		files: [ 'tests/e2e/**/*.js', 'playwright.config.js' ],
	} ) ),
	{
		files: [ 'tests/e2e/**/*.js', 'playwright.config.js' ],
		languageOptions: {
			// `page.evaluate()` callbacks run in the page, not in Node.
			globals: { ...globals.node, getComputedStyle: 'readonly' },
		},
		settings: {
			// auth.setup.js imports `test` as `setup`, Playwright's convention
			// for setup projects.
			playwright: { globalAliases: { test: [ 'setup' ] } },
		},
		rules: {
			// A fixture's `use()` is Playwright's, not React's `use` hook.
			'react-hooks/rules-of-hooks': 'off',
			// Same reason: inside `page.evaluate()` there is no node ref to
			// take an `ownerDocument` from — `document` is the page's own.
			'@wordpress/no-global-active-element': 'off',
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
		files: [
			'*.config.js',
			'*.config.cjs',
			'*.config.mjs',
			'tests/bin/**/*.js',
		],
		languageOptions: {
			globals: globals.node,
		},
	},
];
