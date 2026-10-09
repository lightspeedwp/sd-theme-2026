/**
 * Load one of the theme's front-end scripts into jsdom.
 *
 * The scripts in `assets/js/` are self-executing IIFEs with no exports, so the
 * only way to run one is to import it — which executes it once and then caches
 * the module. `vi.resetModules()` makes every call run it afresh against
 * whatever DOM the test built first.
 *
 * Several of them also bind to `document`, which outlives a test. Without
 * cleanup the listeners from every earlier load stay attached and a single click
 * toggles a section once per test that has run. `loadScript()` records what the
 * script added to `document` and `unloadScripts()` takes it back off.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

import { vi } from 'vitest';

/**
 * Loaders for every script, keyed by path. Vite resolves these at transform
 * time, which is what a dynamic `import()` of a computed path cannot do under
 * jsdom (where `import.meta.url` is an http URL the loader will not open).
 */
const SCRIPTS = import.meta.glob( '../../../assets/js/*.js' );

/**
 * Listeners added to `document` or `window` by scripts loaded in this test.
 *
 * @type {Array<[EventTarget, string, Function, boolean|Object|undefined]>}
 */
let attached = [];

/**
 * Import a script from `assets/js/`, running it against the current DOM.
 *
 * @param {string} file File name, e.g. `search-filters.js`.
 * @return {Promise<void>} Resolves once the script has run.
 */
export async function loadScript( file ) {
	for ( const target of [ document, window ] ) {
		const add = target.addEventListener.bind( target );

		vi.spyOn( target, 'addEventListener' ).mockImplementation(
			( type, listener, options ) => {
				attached.push( [ target, type, listener, options ] );
				add( type, listener, options );
			}
		);
	}

	vi.resetModules();
	await SCRIPTS[ `../../../assets/js/${ file }` ]();
}

/**
 * Remove every `document` or `window` listener a loaded script added.
 *
 * @return {void}
 */
export function unloadScripts() {
	for ( const [ target, type, listener, options ] of attached ) {
		target.removeEventListener( type, listener, options );
	}

	attached = [];
}
