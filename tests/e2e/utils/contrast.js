/**
 * WCAG 2.x contrast, computed from the colours the browser actually resolved.
 *
 * axe checks resting colours only — it never hovers — so a link whose hover
 * state fails AA passes an axe scan. The footer's old `neutral-500` hover was
 * exactly that. These helpers let a spec hover the element itself and measure.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

/**
 * @param {string} value A computed colour: `rgb(r, g, b)` or `rgba(r, g, b, a)`.
 * @return {number[]} The red, green and blue channels, 0–255.
 */
function parseRgb( value ) {
	const channels = String( value ).match( /[\d.]+/g );

	if ( ! channels || channels.length < 3 ) {
		throw new Error( `Not a computed rgb() colour: "${ value }"` );
	}

	return channels.slice( 0, 3 ).map( Number );
}

/**
 * @param {string} value A computed colour.
 * @return {number} Relative luminance, per WCAG 2.x.
 */
function luminance( value ) {
	const [ r, g, b ] = parseRgb( value ).map( ( channel ) => {
		const c = channel / 255;
		return c <= 0.03928 ? c / 12.92 : Math.pow( ( c + 0.055 ) / 1.055, 2.4 );
	} );

	return 0.2126 * r + 0.7152 * g + 0.0722 * b;
}

/**
 * @param {string} foreground A computed colour.
 * @param {string} background A computed colour.
 * @return {number} The contrast ratio, 1–21.
 */
function contrastRatio( foreground, background ) {
	const a = luminance( foreground );
	const b = luminance( background );

	return ( Math.max( a, b ) + 0.05 ) / ( Math.min( a, b ) + 0.05 );
}

/**
 * Resolve a theme.json preset to the `rgb()` string the browser computes for it,
 * so a spec compares like with like instead of hardcoding a hex value that
 * would go stale the next time the palette is re-extracted.
 *
 * @param {import('@playwright/test').Page} page
 * @param {string}                          slug Palette slug, e.g. `brand-700`.
 * @return {Promise<string>} The computed `rgb()` value.
 */
async function presetColor( page, slug ) {
	return page.evaluate( ( name ) => {
		const probe = document.createElement( 'span' );
		probe.style.color = `var(--wp--preset--color--${ name })`;
		document.body.appendChild( probe );
		const value = getComputedStyle( probe ).color;
		probe.remove();
		return value;
	}, slug );
}

module.exports = { contrastRatio, presetColor };
