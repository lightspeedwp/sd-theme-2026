/**
 * Sign in once, save the session for the `editor` project.
 *
 * Signs in through `RequestUtils` from `@wordpress/e2e-test-utils-playwright`
 * — the same path Gutenberg's own suite uses. `setupRest()` posts to
 * wp-login.php, mints a REST nonce from `admin-ajax.php?action=rest-nonce`, and
 * writes cookies, nonce and REST root to one storage-state file. The `editor`
 * browser project loads the cookies from it; the package's `requestUtils`
 * fixture loads the nonce, so no spec has to mint its own.
 *
 * Credentials come from .env (WP_ADMIN_USER / WP_ADMIN_PASS) and never from
 * source. The saved state lands in tests/e2e/.auth/, which .gitignore excludes —
 * an auth-state file is a credential.
 *
 * The account this uses is deliberately temporary. When it is deleted the
 * `editor` project stops running, which is the intended behaviour: the config
 * drops the project entirely when the variables are unset, so nothing fails.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { test: setup, expect } = require( '@playwright/test' );
const { RequestUtils } = require( '@wordpress/e2e-test-utils-playwright' );
const { STORAGE_STATE_PATH } = require( './utils/env.js' );

setup( 'authenticate as an administrator', async ( { baseURL } ) => {
	const username = process.env.WP_ADMIN_USER;
	const password = process.env.WP_ADMIN_PASS;

	setup.skip(
		! username || ! password,
		'No WP_ADMIN_USER / WP_ADMIN_PASS — see .env.example'
	);

	const requestUtils = await RequestUtils.setup( {
		baseURL,
		user: { username, password },
		storageStatePath: STORAGE_STATE_PATH,
	} );

	/**
	 * A failed login is not an error to wp-login.php — it re-renders the form
	 * with a 200 — so the nonce request that follows fails instead, and
	 * setupRest() retries for a minute before giving up. Say what that
	 * usually means.
	 */
	try {
		await requestUtils.setupRest();
	} catch ( error ) {
		throw new Error(
			`Could not sign in to ${ baseURL } as the test administrator.\n` +
				'If the throwaway test user has been deleted, remove ' +
				'WP_ADMIN_USER and WP_ADMIN_PASS from .env — the editor ' +
				'project will then skip cleanly instead of failing.\n\n' +
				error.message
		);
	}

	const me = await requestUtils.rest( {
		path: '/wp/v2/users/me',
		params: { context: 'edit' },
	} );

	expect(
		me.roles,
		'signed in, but the account is not an administrator'
	).toContain( 'administrator' );

	await requestUtils.request.dispose();

	process.stderr.write(
		`\n  Signed in to ${ baseURL } as the test administrator\n`
	);
} );
