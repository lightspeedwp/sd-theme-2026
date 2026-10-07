/**
 * theme.json and every style variation are valid, current-schema JSON.
 *
 * A JSON syntax error in theme.json does not fail loudly: WordPress falls back
 * to core defaults and the site renders unstyled. In a section style it is
 * quieter still — the variation just disappears from the editor.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const { list, read } = require( '../helpers/theme-files' );

const STYLE_FILES = list( 'styles', '.json', true );

describe( 'theme.json', () => {
	const themeJson = JSON.parse( read( 'theme.json' ) );

	it( 'is version 3 against a WordPress schema', () => {
		expect( themeJson.version ).toBe( 3 );
		expect( themeJson.$schema ).toMatch(
			/^https:\/\/schemas\.wp\.org\/(wp\/[\d.]+|trunk)\/theme\.json$/
		);
	} );

	it( 'declares no core default presets, so every slug is the theme’s', () => {
		const {
			color = {},
			spacing = {},
			typography = {},
			shadow = {},
		} = themeJson.settings;

		expect( {
			defaultPalette: color.defaultPalette,
			defaultGradients: color.defaultGradients,
			defaultSpacingSizes: spacing.defaultSpacingSizes,
			defaultFontSizes: typography.defaultFontSizes,
			defaultShadowPresets: shadow.defaultPresets,
		} ).toEqual( {
			defaultPalette: false,
			defaultGradients: false,
			defaultSpacingSizes: false,
			defaultFontSizes: false,
			defaultShadowPresets: false,
		} );
	} );
} );

describe( 'style variations', () => {
	it( 'exist', () => {
		expect( STYLE_FILES.length ).toBeGreaterThan( 0 );
	} );

	it.each( STYLE_FILES )( '%s is valid version 3 JSON', ( file ) => {
		const json = JSON.parse( read( file ) );

		expect( json.version ).toBe( 3 );
	} );

	/**
	 * Core dedupes variations by basename across the tree, so two files with
	 * the same name in different folders silently become one. AGENTS.md →
	 * "styles/ layout rule".
	 */
	it( 'have unique basenames across the whole tree', () => {
		const seen = new Map();
		const duplicates = [];

		for ( const file of STYLE_FILES ) {
			const base = file.split( '/' ).pop();

			if ( seen.has( base ) ) {
				duplicates.push( `${ seen.get( base ) } ↔ ${ file }` );
			}

			seen.set( base, file );
		}

		expect( duplicates ).toEqual( [] );
	} );
} );
