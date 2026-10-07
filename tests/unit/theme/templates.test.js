/**
 * Template structure rules from AGENTS.md, checked without a browser.
 *
 * - Every template has exactly one `<main>` landmark — counted through the
 *   template parts and patterns it pulls in, because that is where it often
 *   lives.
 * - Every `wp:template-part` and `wp:pattern` reference resolves to a file.
 * - No per-install IDs: no `wp:navigation` `ref`, no synced-pattern
 *   `wp:block` `ref`. No deploy step can fix either.
 *
 * The e2e suite checks the rendered landmark on dev; this catches the same
 * mistake in the file, before it is deployed anywhere.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const fs = require( 'fs' );
const {
	list,
	markup,
	markupFiles,
	patternHeaders,
	patternsBySlug,
	themePath,
} = require( '../helpers/theme-files' );

const PATTERNS = patternsBySlug();

/**
 * Block comment attributes for every occurrence of a block in markup.
 *
 * @param {string} source Block markup.
 * @param {string} name   Block name without the `core/` prefix.
 * @return {Object[]} Parsed attribute objects (`{}` when there are none).
 */
function blockAttributes( source, name ) {
	const pattern = new RegExp(
		`<!--\\s+wp:${ name }(\\s+(\\{[\\s\\S]*?\\}))?\\s+\\/?-->`,
		'g'
	);

	return [ ...source.matchAll( pattern ) ].map( ( match ) => {
		try {
			return match[ 2 ] ? JSON.parse( match[ 2 ] ) : {};
		} catch {
			return { __unparsable: match[ 2 ] };
		}
	} );
}

/**
 * Markup a file renders, with template parts and patterns expanded in place.
 *
 * @param {string}      file Theme-relative path.
 * @param {Set<string>} seen Files already on the stack, to stop cycles.
 * @return {string} Expanded markup.
 */
function expand( file, seen = new Set() ) {
	if ( seen.has( file ) ) {
		return '';
	}

	const next = new Set( seen ).add( file );
	const own = markup( file );
	const nested = [];

	// References are read from this file's own markup only; each nested file
	// expands its own, so nothing is counted twice.
	for ( const { slug } of blockAttributes( own, 'template-part' ) ) {
		const part = `parts/${ slug }.html`;

		if ( slug && fs.existsSync( themePath( part ) ) ) {
			nested.push( expand( part, next ) );
		}
	}

	for ( const { slug } of blockAttributes( own, 'pattern' ) ) {
		if ( PATTERNS.has( slug ) ) {
			nested.push( expand( PATTERNS.get( slug ), next ) );
		}
	}

	return [ own, ...nested ].join( '\n' );
}

/**
 * Pattern slugs a file pulls in that the theme does not ship — Tour
 * Operator's, for instance — anywhere in its expanded tree.
 *
 * @param {string} expanded Expanded markup.
 * @return {string[]} Foreign slugs.
 */
function foreignPatterns( expanded ) {
	return blockAttributes( expanded, 'pattern' )
		.map( ( { slug } ) => slug )
		.filter( ( slug ) => slug && ! PATTERNS.has( slug ) );
}

/**
 * `<main>` landmarks in markup, counted by block attribute.
 *
 * Only `"tagName":"main"` counts. A static block serialises its tag twice —
 * once in the block comment's attributes and again as the saved `<main …>`
 * element — so counting elements as well would double every landmark.
 *
 * @param {string} expanded Expanded markup.
 * @return {number} Count.
 */
function mainLandmarks( expanded ) {
	return ( expanded.match( /"tagName"\s*:\s*"main"/g ) || [] ).length;
}

describe( 'templates', () => {
	const templates = list( 'templates', '.html' );

	it( 'exist', () => {
		expect( templates ).toContain( 'templates/index.html' );
	} );

	/**
	 * A template that hands its body to a plugin's pattern
	 * (`lsx-tour-operator/single-review`) cannot be counted from here — the
	 * landmark, if any, is in the plugin. Those are held to "at most one" and
	 * left to the e2e page contract, which sees the rendered page.
	 */
	it.each( templates )( '%s has exactly one <main> landmark', ( file ) => {
		const expanded = expand( file );
		const allowed = foreignPatterns( expanded ).length ? [ 0, 1 ] : [ 1 ];

		expect( allowed ).toContain( mainLandmarks( expanded ) );
	} );
} );

describe( 'references', () => {
	it.each( markupFiles() )(
		'%s references only parts and patterns that exist',
		( file ) => {
			const own = markup( file );
			const missing = [
				...blockAttributes( own, 'template-part' )
					.filter(
						( { slug, theme } ) =>
							slug &&
							( ! theme || 'sd-theme-2026' === theme ) &&
							! fs.existsSync(
								themePath( `parts/${ slug }.html` )
							)
					)
					.map( ( { slug } ) => `template part "${ slug }"` ),
				...blockAttributes( own, 'pattern' )
					.filter(
						( { slug } ) =>
							slug?.startsWith( 'sd-theme-2026/' ) &&
							! PATTERNS.has( slug )
					)
					.map( ( { slug } ) => `pattern "${ slug }"` ),
			];

			expect( missing ).toEqual( [] );
		}
	);

	it.each( markupFiles() )( '%s carries no per-install ids', ( file ) => {
		const own = markup( file );
		const offending = [
			...blockAttributes( own, 'navigation' )
				.filter( ( attributes ) => undefined !== attributes.ref )
				.map( ( { ref } ) => `wp:navigation ref ${ ref }` ),
			...blockAttributes( own, 'block' )
				.filter( ( attributes ) => undefined !== attributes.ref )
				.map( ( { ref } ) => `synced pattern wp:block ref ${ ref }` ),
		];

		expect( offending ).toEqual( [] );
	} );
} );

describe( 'patterns', () => {
	const files = list( 'patterns', '.php' );

	it.each( files )( '%s has a Title and a namespaced Slug', ( file ) => {
		const headers = patternHeaders( file );

		expect( headers.title ).toBeTruthy();
		expect( headers.slug ).toMatch( /^sd-theme-2026\/[a-z0-9-]+$/ );
	} );

	it( 'declare unique slugs', () => {
		expect( PATTERNS.size ).toBe( files.length );
	} );
} );
