/**
 * Read the theme's authored files from disk, for the contract tests.
 *
 * These tests are static: they read templates, parts, patterns, styles and
 * theme.json as text and check the rules AGENTS.md states. No WordPress, no
 * browser — a full run takes well under a second.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const fs = require( 'fs' );
const path = require( 'path' );

const THEME_ROOT = path.join( __dirname, '..', '..', '..' );

/**
 * @param {string} relative Path relative to the theme root.
 * @return {string} Absolute path.
 */
function themePath( relative ) {
	return path.join( THEME_ROOT, relative );
}

/**
 * @param {string} relative Path relative to the theme root.
 * @return {string} File contents.
 */
function read( relative ) {
	return fs.readFileSync( themePath( relative ), 'utf8' );
}

/**
 * Files in a theme directory, optionally recursive, as theme-relative paths.
 *
 * @param {string}  directory Directory relative to the theme root.
 * @param {string}  extension Extension including the dot.
 * @param {boolean} recursive Whether to descend.
 * @return {string[]} Sorted theme-relative paths.
 */
function list( directory, extension, recursive = false ) {
	const absolute = themePath( directory );

	if ( ! fs.existsSync( absolute ) ) {
		return [];
	}

	return fs
		.readdirSync( absolute, { withFileTypes: true } )
		.flatMap( ( entry ) => {
			const relative = path.posix.join( directory, entry.name );

			if ( entry.isDirectory() ) {
				return recursive ? list( relative, extension, true ) : [];
			}

			return entry.name.endsWith( extension ) ? [ relative ] : [];
		} )
		.sort();
}

/**
 * A file's block markup: the file itself, minus PHP and CSS-style comments.
 *
 * Pattern docblocks quote block delimiters when they explain history ("the
 * mega menus referenced `<!-- wp:block {"ref":65890} /-->`"); a quotation is
 * not markup. Block delimiters themselves are HTML comments and survive.
 *
 * @param {string} relative Path relative to the theme root.
 * @return {string} Markup.
 */
function markup( relative ) {
	const text = read( relative );

	return relative.endsWith( '.php' )
		? text.replace( /\/\*[\s\S]*?\*\//g, '' )
		: text;
}

/**
 * Header fields of a pattern file (`Title:`, `Slug:`, …).
 *
 * @param {string} relative Pattern path relative to the theme root.
 * @return {Object<string, string>} Header values keyed by lower-cased name.
 */
function patternHeaders( relative ) {
	const docblock = read( relative ).match( /\/\*\*([\s\S]*?)\*\// );
	const headers = {};

	if ( ! docblock ) {
		return headers;
	}

	for ( const line of docblock[ 1 ].split( '\n' ) ) {
		const match = line.match( /^\s*\*\s*([A-Za-z ]+):\s*(.+?)\s*$/ );

		if ( match ) {
			headers[ match[ 1 ].trim().toLowerCase() ] = match[ 2 ];
		}
	}

	return headers;
}

/**
 * Every pattern file, keyed by its declared slug.
 *
 * @return {Map<string, string>} Slug → theme-relative path.
 */
function patternsBySlug() {
	const bySlug = new Map();

	for ( const file of list( 'patterns', '.php' ) ) {
		const { slug } = patternHeaders( file );

		if ( slug ) {
			bySlug.set( slug, file );
		}
	}

	return bySlug;
}

/**
 * The block-markup files AGENTS.md's authoring rules apply to.
 *
 * @return {string[]} Theme-relative paths.
 */
function markupFiles() {
	return [
		...list( 'templates', '.html' ),
		...list( 'parts', '.html' ),
		...list( 'patterns', '.php' ),
	];
}

module.exports = {
	THEME_ROOT,
	list,
	markup,
	markupFiles,
	patternHeaders,
	patternsBySlug,
	read,
	themePath,
};
