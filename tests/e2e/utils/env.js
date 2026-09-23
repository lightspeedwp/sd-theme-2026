/**
 * Minimal .env loader.
 *
 * Playwright does not read .env by itself, and the org standard requires base
 * URLs and credentials to come from the environment rather than from source.
 * `dotenv` is the usual answer, but AGENTS.md §6 asks for no new dependencies
 * without justification and this is twenty lines — so it lives here instead.
 *
 * Values already present in `process.env` win, so a one-off
 * `WP_BASE_URL=… npm run test:e2e` still overrides the file.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const fs = require( 'fs' );
const path = require( 'path' );

const ENV_PATH = path.join( __dirname, '..', '..', '..', '.env' );

/**
 * Read .env from the theme root into process.env.
 *
 * Handles `KEY=value`, `KEY='value'` and `KEY="value"`. Single quotes matter
 * here: the admin password contains a `$`, and leaving it bare invites a shell
 * or an expanding loader to eat it.
 *
 * @return {void}
 */
function loadEnv() {
	if ( ! fs.existsSync( ENV_PATH ) ) {
		return;
	}

	for ( const line of fs.readFileSync( ENV_PATH, 'utf8' ).split( '\n' ) ) {
		const trimmed = line.trim();

		if ( '' === trimmed || trimmed.startsWith( '#' ) ) {
			continue;
		}

		const at = trimmed.indexOf( '=' );

		if ( 0 >= at ) {
			continue;
		}

		const key = trimmed.slice( 0, at ).trim();

		if ( key in process.env ) {
			continue;
		}

		let value = trimmed.slice( at + 1 ).trim();

		if (
			( value.startsWith( "'" ) && value.endsWith( "'" ) ) ||
			( value.startsWith( '"' ) && value.endsWith( '"' ) )
		) {
			value = value.slice( 1, -1 );
		}

		process.env[ key ] = value;
	}
}

/**
 * @return {boolean} True when admin credentials are available for @auth specs.
 */
function hasAdminCredentials() {
	return Boolean( process.env.WP_ADMIN_USER && process.env.WP_ADMIN_PASS );
}

module.exports = { loadEnv, hasAdminCredentials, ENV_PATH };
