/**
 * Global setup — resolve one live permalink per content type before the run.
 *
 * Why this exists: the suite has to visit a single tour, a single accommodation,
 * a populated taxonomy archive and so on, but the slugs differ per environment
 * and change as content is migrated. Hardcoding them produces a suite that goes
 * red for reasons that have nothing to do with the theme.
 *
 * So: one REST sweep at start-up, cached to `.resolved-routes.json`, read by the
 * specs. If a type has no content the entry is recorded as `null` and the specs
 * that need it skip with a stated reason, rather than failing.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const fs = require( 'fs' );
const path = require( 'path' );
const { request } = require( '@playwright/test' );

const {
	RESOLVED_ROUTES,
	RESOLVED_TAXONOMIES,
	RESOLVED_ARCHIVES,
} = require( './fixtures/routes.js' );

const CACHE_PATH = path.join( __dirname, '.resolved-routes.json' );

/**
 * Ask the REST API for the newest published item of a type and return its
 * permalink path.
 *
 * @param {import('@playwright/test').APIRequestContext} api      Request context.
 * @param {string}                                       restBase Collection endpoint.
 * @param {string}                                       baseURL  Site origin.
 * @param {string}                                       [query]  Extra query string, e.g. `parent=0`.
 * @return {Promise<string|null>} Path with a leading slash, or null if empty.
 */
async function resolvePost( api, restBase, baseURL, query = '' ) {
	const response = await api.get(
		`/wp-json/wp/v2/${ restBase }?per_page=1&status=publish&orderby=date&order=desc&_fields=link${
			query ? `&${ query }` : ''
		}`
	);

	if ( ! response.ok() ) {
		return null;
	}

	const items = await response.json();

	if ( ! Array.isArray( items ) || 0 === items.length || ! items[ 0 ].link ) {
		return null;
	}

	return toPath( items[ 0 ].link, baseURL );
}

/**
 * Ask for the term with the most posts in a taxonomy.
 *
 * An empty term renders the template's "nothing found" branch, which is not the
 * path worth covering — so order by count descending and take the fullest.
 *
 * @param {import('@playwright/test').APIRequestContext} api      Request context.
 * @param {string}                                       restBase Collection endpoint.
 * @param {string}                                       baseURL  Site origin.
 * @return {Promise<string|null>} Path with a leading slash, or null if empty.
 */
async function resolveTerm( api, restBase, baseURL ) {
	const response = await api.get(
		`/wp-json/wp/v2/${ restBase }?per_page=1&orderby=count&order=desc&hide_empty=true&_fields=link,count`
	);

	if ( ! response.ok() ) {
		return null;
	}

	const terms = await response.json();

	if ( ! Array.isArray( terms ) || 0 === terms.length || ! terms[ 0 ].link ) {
		return null;
	}

	/**
	 * A term with no posts is no better than no term at all — `hide_empty` is
	 * advisory on some endpoints, so check the count we asked for.
	 */
	if ( 0 === terms[ 0 ].count ) {
		return null;
	}

	return toPath( terms[ 0 ].link, baseURL );
}

/**
 * Find one page per custom page template.
 *
 * `templates/page-brands.html` and friends only render when a page is actually
 * assigned to them, so there is no fixed URL to test. Ask the pages endpoint
 * which pages carry which template and take the first of each.
 *
 * @param {import('@playwright/test').APIRequestContext} api     Request context.
 * @param {string}                                       baseURL Site origin.
 * @return {Promise<Object<string, string>>} Template slug to path.
 */
async function resolvePageTemplates( api, baseURL ) {
	const response = await api.get(
		'/wp-json/wp/v2/pages?per_page=100&status=publish&_fields=link,template'
	);

	if ( ! response.ok() ) {
		return {};
	}

	const pages = await response.json();

	if ( ! Array.isArray( pages ) ) {
		return {};
	}

	const found = {};

	for ( const page of pages ) {
		/**
		 * An empty `template` means the default hierarchy, which page.html
		 * already covers through STATIC_ROUTES.
		 */
		if ( ! page.template || found[ page.template ] ) {
			continue;
		}

		found[ page.template ] = toPath( page.link, baseURL );
	}

	return found;
}

/**
 * Find one live author archive that resolves through `archive.html`.
 *
 * Ask the users endpoint for an author with at least one published post and use
 * the archive URL WordPress reports, so the spec stays agnostic to any custom
 * author base.
 *
 * @param {import('@playwright/test').APIRequestContext} api     Request context.
 * @param {string}                                       baseURL Site origin.
 * @return {Promise<string|null>} Path with a leading slash, or null when absent.
 */
async function resolveAuthorArchive( api, baseURL ) {
	const response = await api.get(
		'/wp-json/wp/v2/users?per_page=1&who=authors&has_published_posts=post&_fields=link'
	);

	if ( ! response.ok() ) {
		return null;
	}

	const authors = await response.json();

	if ( ! Array.isArray( authors ) || 0 === authors.length || ! authors[ 0 ].link ) {
		return null;
	}

	return toPath( authors[ 0 ].link, baseURL );
}

/**
 * Convert an absolute permalink to a path, so specs stay origin-agnostic and
 * `baseURL` remains the single place the environment is chosen.
 *
 * @param {string} link    Absolute URL from the REST API.
 * @param {string} baseURL Site origin.
 * @return {string} Path with a leading slash.
 */
function toPath( link, baseURL ) {
	try {
		return new URL( link, baseURL ).pathname;
	} catch {
		return link;
	}
}

/**
 * @param {import('@playwright/test').FullConfig} config Playwright config.
 */
module.exports = async function globalSetup( config ) {
	const baseURL =
		config.projects[ 0 ]?.use?.baseURL ||
		process.env.WP_BASE_URL ||
		'https://southerndestinations.lightspeedwp.dev';

	const api = await request.newContext( {
		baseURL,
		timeout: 30 * 1000,
	} );

	/**
	 * Fail loudly and early if the target is not up. A hundred timeouts across
	 * a hundred specs tells you far less than one clear message here.
	 */
	const probe = await api.get( '/wp-json/' ).catch( () => null );

	if ( ! probe || ! probe.ok() ) {
		await api.dispose();
		throw new Error(
			`Cannot reach ${ baseURL }. Is the target up?\n` +
				'  dev:   https://southerndestinations.lightspeedwp.dev (default)\n' +
				'  local: start the Studio site, then WP_BASE_URL=http://localhost:8903'
		);
	}

	let resolved;
	let missing;

	try {
		resolved = {
			baseURL,
			posts: {},
			terms: {},
			archives: {},
			pageTemplates: {},
			resolvedAt: new Date().toISOString(),
		};
		missing = [];
		const requiredMissing = [];

		await Promise.all(
			RESOLVED_ROUTES.map( async ( route ) => {
				resolved.posts[ route.key ] = await resolvePost(
					api,
					route.restBase,
					baseURL,
					route.query
				);

				if ( ! resolved.posts[ route.key ] ) {
					( route.optional ? missing : requiredMissing ).push( route.name );
				}
			} )
		);

		await Promise.all(
			RESOLVED_TAXONOMIES.map( async ( route ) => {
				resolved.terms[ route.key ] = await resolveTerm(
					api,
					route.restBase,
					baseURL
				);

				if ( ! resolved.terms[ route.key ] ) {
					( route.optional ? missing : requiredMissing ).push( route.name );
				}
			} )
		);

		await Promise.all(
			RESOLVED_ARCHIVES.map( async ( route ) => {
				resolved.archives[ route.key ] = await resolveAuthorArchive( api, baseURL );

				if ( ! resolved.archives[ route.key ] ) {
					( route.optional ? missing : requiredMissing ).push( route.name );
				}
			} )
		);

		if ( requiredMissing.length ) {
			throw new Error(
				`No content for required route(s): ${ requiredMissing.join( ', ' ) } on ${ baseURL }.`
			);
		}

		resolved.pageTemplates = await resolvePageTemplates( api, baseURL );
	} finally {
		await api.dispose();
	}

	fs.writeFileSync( CACHE_PATH, JSON.stringify( resolved, null, '\t' ) );

	const found =
		Object.values( resolved.posts ).filter( Boolean ).length +
		Object.values( resolved.terms ).filter( Boolean ).length +
		Object.values( resolved.archives ).filter( Boolean ).length;
	const total =
		RESOLVED_ROUTES.length + RESOLVED_TAXONOMIES.length + RESOLVED_ARCHIVES.length;

	/**
	 * stderr, not stdout. The JSON and JUnit reporters write their payload to
	 * stdout, and anything else printed there makes the report unparseable —
	 * which breaks CI in a way that looks like a test failure.
	 */
	const pageTemplateCount = Object.keys( resolved.pageTemplates ).length;

	process.stderr.write(
		`\n  Target:   ${ baseURL }\n` +
			`  Resolved: ${ found }/${ total } content routes\n` +
			`  Page templates in use: ${ pageTemplateCount }\n` +
			( missing.length
				? `  No content: ${ missing.join( ', ' ) } — those specs will skip\n\n`
				: '\n' )
	);
};
