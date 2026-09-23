/**
 * Sign in once, save the session for the `editor` project.
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

const path = require( 'path' );
const { test: setup, expect } = require( '@playwright/test' );

const STORAGE_STATE = path.join( __dirname, '.auth', 'admin.json' );

setup( 'authenticate as an administrator', async ( { page, baseURL } ) => {
	const user = process.env.WP_ADMIN_USER;
	const pass = process.env.WP_ADMIN_PASS;

	setup.skip(
		! user || ! pass,
		'No WP_ADMIN_USER / WP_ADMIN_PASS — see .env.example'
	);

	await page.goto( '/wp-login.php' );

	/**
	 * `#user_login` and `#user_pass` are core's own ids and have been stable
	 * for the whole life of wp-login.php. The accessible names are translated,
	 * so a label lookup would break on a non-English install — this is the one
	 * place an id beats `getByLabel`.
	 */
	await page.locator( '#user_login' ).fill( user );
	await page.locator( '#user_pass' ).fill( pass );
	await page.locator( '#wp-submit' ).click();

	/**
	 * A failed login re-renders wp-login.php with an error div rather than
	 * returning a non-200, so assert on where we landed, not on status.
	 */
	const loginError = page.locator( '#login_error' );
	const adminBar = page.locator( '#wpadminbar' );

	/**
	 * Wait for whichever outcome arrives. Checking the error straight after
	 * the click reads the page before the POST has come back.
	 */
	await adminBar.or( loginError ).first().waitFor( { timeout: 30 * 1000 } );

	if ( await loginError.count() ) {
		const message = ( await loginError.innerText() ).trim();

		throw new Error(
			`Login failed for the test administrator: ${ message }\n` +
				'If the throwaway test user has been deleted, remove ' +
				'WP_ADMIN_USER and WP_ADMIN_PASS from .env — the editor ' +
				'project will then skip cleanly instead of failing.'
		);
	}

	await page.waitForURL( /\/wp-admin\//, { timeout: 30 * 1000 } );

	await expect(
		page.locator( '#wpadminbar' ),
		'signed in but no admin bar — is the account an administrator?'
	).toBeAttached();

	await page.context().storageState( { path: STORAGE_STATE } );

	process.stderr.write( `\n  Signed in to ${ baseURL } as the test administrator\n` );
} );

module.exports = { STORAGE_STATE };
