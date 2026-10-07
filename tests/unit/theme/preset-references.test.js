/**
 * Every preset and custom token an authored file references is defined.
 *
 * The executable form of AGENTS.md's "Run `theme-orphaned-refs` after token
 * changes". An orphaned `var:preset|color|brand-550` does not error anywhere —
 * the declaration resolves to nothing and the element inherits — so a renamed
 * slug quietly breaks every file that used the old one.
 *
 * Covers both spellings: the block-attribute shorthand (`var:preset|…`,
 * `var:custom|…`) and the CSS custom property (`--wp--preset--…`,
 * `--wp--custom--…`).
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { list, markupFiles, read } = require( '../helpers/theme-files' );
const { known } = require( '../helpers/known-violations' );

/**
 * Preset type as it appears in references → where theme.json defines it.
 */
const PRESET_SOURCES = {
	color: [ 'color', 'palette' ],
	gradient: [ 'color', 'gradients' ],
	spacing: [ 'spacing', 'spacingSizes' ],
	'font-size': [ 'typography', 'fontSizes' ],
	'font-family': [ 'typography', 'fontFamilies' ],
	shadow: [ 'shadow', 'presets' ],
};

/**
 * WordPress's `_wp_to_kebab_case()`, for the cases this theme uses: camelCase
 * and letter/number boundaries.
 *
 * @param {string} key Key from settings.custom.
 * @return {string} Kebab-case.
 */
function toKebab( key ) {
	return String( key )
		.replace( /([a-z])([A-Z])/g, '$1-$2' )
		.replace( /([a-zA-Z])(\d)/g, '$1-$2' )
		.replace( /(\d)([a-zA-Z])/g, '$1-$2' )
		.replace( /[\s_]+/g, '-' )
		.toLowerCase();
}

/**
 * Every leaf path in settings.custom, kebab-cased and joined with `--`.
 *
 * @param {Object} node   Subtree.
 * @param {string} prefix Path so far.
 * @return {string[]} Paths.
 */
function customPaths( node, prefix = '' ) {
	return Object.entries( node ).flatMap( ( [ key, value ] ) => {
		const here = prefix
			? `${ prefix }--${ toKebab( key ) }`
			: toKebab( key );

		return value && 'object' === typeof value
			? customPaths( value, here )
			: [ here ];
	} );
}

/**
 * Slugs per preset type, and custom paths, from theme.json plus any style
 * variation that adds its own settings.
 *
 * @return {{presets: Object<string, Set<string>>, custom: Set<string>}} Defined tokens.
 */
function definedTokens() {
	const sources = [
		JSON.parse( read( 'theme.json' ) ),
		...list( 'styles', '.json', true ).map( ( file ) =>
			JSON.parse( read( file ) )
		),
	];

	const presets = Object.fromEntries(
		Object.keys( PRESET_SOURCES ).map( ( type ) => [ type, new Set() ] )
	);
	const custom = new Set();

	for ( const json of sources ) {
		const settings = json.settings || {};

		for ( const [ type, [ group, key ] ] of Object.entries(
			PRESET_SOURCES
		) ) {
			for ( const preset of settings[ group ]?.[ key ] || [] ) {
				presets[ type ].add( preset.slug );
			}
		}

		customPaths( settings.custom || {} ).forEach( ( entry ) =>
			custom.add( entry )
		);
	}

	return { presets, custom };
}

/**
 * Every token reference in a file, outside comments.
 *
 * `/* … *\/` comments are stripped first: CSS and pattern docblocks discuss
 * retired tokens by name ("replaces `--wp--preset--font-family--primary`"),
 * and a reference in prose is not a reference. Block delimiters are HTML
 * comments and are untouched.
 *
 * @param {string} source File contents.
 * @return {{kind: string, type?: string, value: string}[]} References.
 */
function references( source ) {
	const text = source.replace( /\/\*[\s\S]*?\*\//g, '' );
	const found = [];
	const types = Object.keys( PRESET_SOURCES ).join( '|' );

	for ( const match of text.matchAll(
		new RegExp( `var:preset\\|(${ types })\\|([a-z0-9-]+)`, 'g' )
	) ) {
		found.push( { kind: 'preset', type: match[ 1 ], value: match[ 2 ] } );
	}

	for ( const match of text.matchAll(
		new RegExp( `--wp--preset--(${ types })--([a-z0-9-]+)`, 'g' )
	) ) {
		found.push( { kind: 'preset', type: match[ 1 ], value: match[ 2 ] } );
	}

	for ( const match of text.matchAll( /var:custom\|([a-z0-9|-]+)/g ) ) {
		found.push( {
			kind: 'custom',
			value: match[ 1 ].split( '|' ).join( '--' ),
		} );
	}

	for ( const match of text.matchAll( /--wp--custom--([a-z0-9-]+)/g ) ) {
		found.push( { kind: 'custom', value: match[ 1 ] } );
	}

	return found;
}

const FILES = [
	...markupFiles(),
	...list( 'styles', '.json', true ),
	...list( 'assets/styles', '.css' ),
	'style.css',
	'theme.json',
];

describe( 'token references', () => {
	const { presets, custom } = definedTokens();

	it( 'theme.json defines presets to reference', () => {
		expect( presets.color.size ).toBeGreaterThan( 0 );
		expect( presets.spacing.size ).toBeGreaterThan( 0 );
	} );

	it.each( FILES )( '%s references only defined tokens', ( file ) => {
		const orphans = references( read( file ) )
			.filter( ( ref ) =>
				'preset' === ref.kind
					? ! presets[ ref.type ].has( ref.value )
					: ! custom.has( ref.value )
			)
			.map( ( ref ) =>
				'preset' === ref.kind
					? `preset ${ ref.type } "${ ref.value }"`
					: `custom "${ ref.value }"`
			);

		expect( [ ...new Set( orphans ) ] ).toEqual(
			known( 'token-orphans', file )
		);
	} );
} );
