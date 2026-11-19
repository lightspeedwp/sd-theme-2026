/**
 * Every template, part and pattern parses as valid blocks in the editor.
 *
 * "This block contains unexpected or invalid content" is the editor saying the
 * saved HTML of a static block no longer matches what its `save()` produces.
 * The front end renders the stale HTML happily, so nothing outside the editor
 * notices — until someone opens the template and the block is locked behind a
 * recovery prompt. That is a frequent outcome of hand-editing block markup,
 * which is how most of this theme is written.
 *
 * Each file is parsed with the editor's own `wp.blocks.parse()`, inside the
 * Site Editor of the target site, so the blocks, block styles and plugin
 * blocks it has registered are the ones that judge validity:
 *
 * - templates and parts are read from this working copy, so a file is checked
 *   before it is deployed;
 * - patterns come from the REST API, because a .php pattern only becomes
 *   markup once WordPress executes it — that is the deployed version.
 *
 * Driven by `@wordpress/e2e-test-utils-playwright`. Read-only: nothing is
 * inserted or saved, and the Site Editor creates no auto-draft.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

const fs = require( 'fs' );
const path = require( 'path' );
const { test, expect } = require( '@wordpress/e2e-test-utils-playwright' );

const THEME_ROOT = path.join( __dirname, '..', '..', '..' );

/**
 * @param {string} directory Theme-relative directory.
 * @return {{name: string, content: string}[]} Its .html files.
 */
function htmlFiles( directory ) {
	return fs
		.readdirSync( path.join( THEME_ROOT, directory ) )
		.filter( ( file ) => file.endsWith( '.html' ) )
		.sort()
		.map( ( file ) => ( {
			name: `${ directory }/${ file }`,
			content: fs.readFileSync(
				path.join( THEME_ROOT, directory, file ),
				'utf8'
			),
		} ) );
}

/**
 * Parse markup in the editor and report every invalid or unregistered block.
 *
 * Runs in the browser. `core/missing` is what the parser substitutes for a
 * block type the site has not registered.
 *
 * @param {import('@playwright/test').Page}   page  Site Editor page.
 * @param {{name: string, content: string}[]} files Markup to check.
 * @return {Promise<{invalid: string[], missing: string[]}>} Problems.
 */
function validate( page, files ) {
	return page.evaluate( ( sources ) => {
		const invalid = [];
		const missing = [];

		const walk = ( name, blocks, trail ) => {
			for ( const block of blocks ) {
				const here = [ ...trail, block.name ].join( ' › ' );

				if ( 'core/missing' === block.name ) {
					missing.push(
						`${ name }: ${ block.attributes.originalName } (${
							trail.join( ' › ' ) || 'top level'
						})`
					);
				} else if ( ! block.isValid ) {
					const why = ( block.validationIssues || [] )
						.map( ( issue ) =>
							issue.args
								.slice( 1 )
								.map( ( arg ) =>
									'string' === typeof arg
										? arg
										: JSON.stringify( arg )
								)
								.join( ' ' )
						)
						.join( ' / ' )
						.slice( 0, 300 );

					invalid.push( `${ name }: ${ here } — ${ why }` );
				}

				walk( name, block.innerBlocks, [ ...trail, block.name ] );
			}
		};

		for ( const { name, content } of sources ) {
			walk( name, window.wp.blocks.parse( content ), [] );
		}

		return { invalid, missing };
	}, files );
}

test.describe( 'Block validation @auth', () => {
	test.beforeEach( async ( { admin } ) => {
		await admin.visitSiteEditor();
	} );

	/**
	 * Control: a pass below only means something if the checker can fail.
	 *
	 * `parse()` tries a block's deprecations before declaring it invalid, so
	 * loosely-saved paragraphs and headings usually migrate and come back
	 * valid — exactly as they would in the editor. A separator with a class
	 * its `save()` never writes has no deprecation to fall back on.
	 */
	test( 'the checker reports an invalid and an unregistered block', async ( {
		page,
	} ) => {
		const { invalid, missing } = await validate( page, [
			{
				name: 'control',
				content:
					'<!-- wp:paragraph --><p>valid</p><!-- /wp:paragraph -->' +
					'<!-- wp:separator --><hr class="not-from-save"/><!-- /wp:separator -->' +
					'<!-- wp:sd-test/not-registered /-->',
			},
		] );

		expect( invalid ).toHaveLength( 1 );
		expect( missing ).toEqual( [
			'control: sd-test/not-registered (top level)',
		] );
	} );

	test( 'every template and template part is valid block markup', async ( {
		page,
	} ) => {
		const { invalid, missing } = await validate( page, [
			...htmlFiles( 'templates' ),
			...htmlFiles( 'parts' ),
		] );

		expect( invalid, invalid.join( '\n' ) ).toEqual( [] );
		expect(
			missing,
			`blocks this site has not registered:\n${ missing.join( '\n' ) }`
		).toEqual( [] );
	} );

	test( 'every theme pattern is valid block markup', async ( {
		page,
		requestUtils,
	} ) => {
		const patterns = (
			await requestUtils.rest( {
				path: '/wp/v2/block-patterns/patterns',
			} )
		)
			.filter( ( pattern ) =>
				pattern.name.startsWith( 'sd-theme-2026/' )
			)
			.map( ( pattern ) => ( {
				name: pattern.name,
				content: pattern.content,
			} ) );

		expect(
			patterns.length,
			'no sd-theme-2026 patterns registered — is the theme active?'
		).toBeGreaterThan( 0 );

		const { invalid, missing } = await validate( page, patterns );

		expect( invalid, invalid.join( '\n' ) ).toEqual( [] );
		expect(
			missing,
			`blocks this site has not registered:\n${ missing.join( '\n' ) }`
		).toEqual( [] );
	} );
} );
