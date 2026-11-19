/**
 * The route map — what the suite knows about the site before it runs.
 *
 * Two kinds of route live here:
 *
 *   STATIC_ROUTES   Paths that exist by construction (front page, CPT archives,
 *                   authored pages). Measured on dev 2026-08-20; all 17 returned
 *                   200 under TO 2.2. See PROJECT.md → "Dev site — measured".
 *
 *   RESOLVED_ROUTES Singles and taxonomy archives, which need a real permalink.
 *                   Hardcoding a slug means the suite breaks the day someone
 *                   unpublishes that post, so global-setup.js resolves one live
 *                   URL per entry from the REST API at the start of each run.
 *
 * Each entry names the theme template it exercises. That mapping is the point of
 * the suite: it is how you tell which of the 33 templates are actually covered.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

/**
 * Pages that exist regardless of content state.
 *
 * `template` is the file in templates/ that WordPress should resolve to.
 *
 * `smoke: true` marks the subset that also runs in Firefox and WebKit. The org
 * standard asks for three engines; running all eighteen routes three times over
 * triples the load on a shared dev host for very little extra signal, so the
 * smoke set is the pages where a rendering-engine difference would plausibly
 * show — the front page, an archive with a query loop, and a facet-driven
 * route.
 *
 * `axeDisable` lists axe rules the a11y project skips on that route, each one a
 * recorded decision rather than a way to make a red run green.
 *
 * The static pages (About Us, its three children, Contact Us and Thank You) all
 * sit on `page-no-title.html`. They skip `link-in-text-block`, because their
 * inline links are migrated content styled as live styles them: coloured, not
 * underlined. That matches live, and keeping it was decided 2026-09-24. Colour
 * contrast is still scanned on these pages, so a link that is also too faint
 * still fails.
 */
const STATIC_PAGE_AXE_DISABLE = [ 'link-in-text-block' ];

const STATIC_ROUTES = [
	{ name: 'front page', path: '/', template: 'front-page.html', smoke: true },
	{ name: 'blog', path: '/blog/', template: 'home.html' },
	{
		name: 'about us',
		path: '/about-us/',
		template: 'page-no-title.html',
		axeDisable: STATIC_PAGE_AXE_DISABLE,
	},
	{
		name: 'why book with us',
		path: '/about-us/why-book-with-us/',
		template: 'page-no-title.html',
		axeDisable: STATIC_PAGE_AXE_DISABLE,
	},
	{
		name: 'social responsibility',
		path: '/about-us/social-responsibility/',
		template: 'page-no-title.html',
		axeDisable: STATIC_PAGE_AXE_DISABLE,
	},
	{
		name: 'connect with us',
		path: '/about-us/connect-with-us/',
		template: 'page-no-title.html',
		axeDisable: STATIC_PAGE_AXE_DISABLE,
	},
	{
		name: 'contact',
		path: '/contact/',
		template: 'page-no-title.html',
		axeDisable: STATIC_PAGE_AXE_DISABLE,
	},
	{
		name: 'thank you',
		path: '/thank-you/',
		template: 'page-no-title.html',
		axeDisable: STATIC_PAGE_AXE_DISABLE,
	},
	{
		name: 'tour archive',
		path: '/tours/',
		template: 'archive-tour.html',
		smoke: true,
	},
	{
		name: 'accommodation archive',
		path: '/accommodation/',
		template: 'archive-accommodation.html',
	},
	{
		name: 'destination archive',
		path: '/destinations/',
		template: 'archive-destination.html',
	},
	{ name: 'team archive', path: '/team/', template: 'archive-team.html' },
	{
		name: 'special archive',
		path: '/specials/',
		template: 'archive-special.html',
	},
	{ name: 'brands', path: '/brand/', template: 'page-brands.html' },
	{
		name: 'faceted tour search',
		path: '/search/tours/safari/',
		template: 'archive-tour.html',
		smoke: true,
	},
	{
		name: 'travel style term',
		path: '/travel-style/top-10-safari-tours/',
		template: 'taxonomy-travel-style.html',
	},
];

/**
 * Routes resolved from the REST API at run time.
 *
 * `restBase` is the collection endpoint; `key` is how specs look the result up
 * on the resolved-routes object. `optional` entries do not fail the run when the
 * environment has no such content — local Studio holds tours only, so every
 * other type is legitimately empty there. An optional `query` string is appended
 * to the collection request to narrow it (see `country` and `region`).
 */
const RESOLVED_ROUTES = [
	{
		key: 'tour',
		name: 'single tour',
		restBase: 'tour',
		template: 'single-tour.html',
		smoke: true,
	},
	{
		key: 'accommodation',
		name: 'single accommodation',
		restBase: 'accommodation',
		template: 'single-accommodation.html',
		optional: true,
	},
	{
		key: 'destination',
		name: 'single destination',
		restBase: 'destination',
		template: 'single-destination.html',
		optional: true,
	},
	/**
	 * A country and a region, resolved separately. `destination` above is
	 * whichever destination is newest, which may be either, so on its own it
	 * cannot promise the regions shelf (a country's) or the accommodation shelf
	 * (a region's) is on the page. `query` is appended to the REST request:
	 * a country is a top-level destination, a region has a parent.
	 *
	 * Since the content was migrated every published destination stores one of
	 * the two templates (measured on dev and local, 2026-10-09: 10 countries
	 * on `single-country`, 90 regions on `single-region`). `single-destination`
	 * is the fallback for a destination with none; the generic `destination`
	 * entry above still names it, but in practice resolves to one of the two.
	 * destinations.spec.js asserts the assignment.
	 */
	{
		key: 'country',
		name: 'single country',
		restBase: 'destination',
		query: 'parent=0',
		template: 'single-country.html',
		optional: true,
	},
	{
		key: 'region',
		name: 'single region',
		restBase: 'destination',
		query: 'parent_exclude=0',
		template: 'single-region.html',
		optional: true,
	},
	{
		key: 'team',
		name: 'single team member',
		restBase: 'team',
		template: 'single-team.html',
		optional: true,
	},
	{
		key: 'post',
		name: 'single post',
		restBase: 'posts',
		template: 'single.html',
		optional: true,
	},
];

/**
 * Taxonomy archives, resolved to a term that actually has posts.
 *
 * An empty term renders the "nothing found" branch of the template, which is a
 * different code path from the one worth testing — so global setup asks for
 * terms with `hide_empty` behaviour by sorting on count.
 */
const RESOLVED_TAXONOMIES = [
	{
		key: 'accommodation-brand',
		name: 'accommodation brand archive',
		restBase: 'accommodation-brand',
		template: 'taxonomy-accommodation-brand.html',
		optional: true,
	},
	{
		key: 'accommodation-type',
		name: 'accommodation type archive',
		restBase: 'accommodation-type',
		template: 'taxonomy-accommodation-type.html',
		optional: true,
	},
	{
		key: 'continent',
		name: 'continent archive',
		restBase: 'continent',
		template: 'taxonomy-continent.html',
		optional: true,
	},
	{
		key: 'facility',
		name: 'facility archive',
		restBase: 'facility',
		template: 'taxonomy-facility.html',
		optional: true,
	},
	{
		key: 'travel-style',
		name: 'travel style archive',
		restBase: 'travel-style',
		template: 'taxonomy-travel-style.html',
		optional: true,
	},
	{
		key: 'category',
		name: 'category archive',
		restBase: 'categories',
		template: 'category.html',
		optional: true,
	},
	{
		key: 'post_tag',
		name: 'tag archive',
		restBase: 'tags',
		template: 'tag.html',
		optional: true,
	},
];

/**
 * Non-taxonomy archives resolved at run time.
 *
 * Author archives render through `author.html`. Dev refuses anonymous
 * `/wp/v2/users` lookups, so this resolves only where the users endpoint is
 * open; blog.spec.js reads its author archive off a byline instead.
 */
const RESOLVED_ARCHIVES = [
	{
		key: 'author',
		name: 'author archive',
		template: 'author.html',
		optional: true,
	},
];

/**
 * Routes that exercise a template without needing any content to exist.
 */
const SYNTHETIC_ROUTES = [
	{
		name: 'search results',
		path: '/?s=safari',
		template: 'search.html',
	},
	{
		name: 'empty search results',
		path: '/?s=zzzznotarealquery',
		template: 'search.html',
	},
	{
		name: '404',
		path: '/this-page-does-not-exist-sd-e2e/',
		template: '404.html',
		expectStatus: 404,
	},
];

/**
 * Content pages the 2019 functional list names that the rest of the suite does
 * not sweep. Kept apart from STATIC_ROUTES on purpose: that list feeds the
 * a11y, visual and template-resolution sweeps, and putting a page there signs
 * it up for every one of them. These are asserted for what the 2019 spec says
 * they hold, in templates/static-pages.spec.js.
 *
 * Measured on dev 2026-11-13 (REST `pages`):
 *
 *   faq     page-faq template, 200.
 *   terms   `/terms-conditions/`, default page template, 200.
 *   privacy `/privacy-policy/`, 200.
 *   sitemap `/sitemap/` is NOT a page on dev: it 301s to `/` (live serves a
 *           200 HTML sitemap there). Listed so the defect is asserted, not
 *           silently left out.
 *
 * There is no "send us an email" page on dev or live (`/send-us-an-email/` is
 * a 404 on both): the 2019 spec's page is the Contact Us form today.
 */
const CONTENT_PAGE_ROUTES = [
	{ key: 'faq', name: 'FAQ', path: '/faq/', template: 'page-faq.html' },
	{
		key: 'terms',
		name: 'Terms & Conditions',
		path: '/terms-conditions/',
		template: 'page.html',
	},
	{
		key: 'privacy',
		name: 'Privacy Policy',
		path: '/privacy-policy/',
		template: 'page.html',
	},
	{
		key: 'sitemap',
		name: 'Sitemap',
		path: '/sitemap/',
		template: 'page.html',
	},
];

/**
 * Content the ASD-41 functional specs pin by slug, because the assertion needs
 * a page with a known shape rather than "whichever is newest" (global setup
 * resolves the newest, which for destinations is a one-region country).
 *
 * Each was measured on dev 2026-11-13. A pinned page that is later unpublished
 * fails at `visit()`'s status check with its path in the message, so it is
 * noticed and re-pinned rather than silently skipped.
 *
 *   tour                       the sample tour: itinerary, WETU map, related shelf.
 *   accommodationNoRooms       `bagatelle`: no units, so no Rooms band.
 *   accommodationFewRooms      two units.
 *   accommodationManyRooms     four units.
 *   country                    South Africa: regions, gallery, map, tours.
 *   region                     Kruger National Park: accommodation and tours shelves.
 *   teamMember                 a consultant with every section populated.
 *   teamMemberBare             a support-team member with no gallery or shelves.
 */
const PINNED = {
	tour: '/tour/south-africa-mozambique/',
	accommodationNoRooms: '/accommodation/bagatelle/',
	accommodationFewRooms: '/accommodation/etosha-oberland-lodge/',
	accommodationManyRooms: '/accommodation/khowarib-lodge-and-safaris/',
	country: '/destination/south-africa/',
	region: '/destination/south-africa/kruger-national-park/',
	teamMember: '/team/ilze-fox/',
	teamMemberBare: '/team/leigh-skibbe/',
};

/**
 * A pinned path, prefixed the way this environment's permalinks need.
 *
 * @param {string} key Key of PINNED.
 * @return {string} Path with a leading slash.
 */
function pinned( key ) {
	if ( ! PINNED[ key ] ) {
		throw new Error( `No pinned route "${ key }"` );
	}
	return PINNED[ key ];
}

module.exports = {
	CONTENT_PAGE_ROUTES,
	PINNED,
	pinned,
	STATIC_ROUTES,
	RESOLVED_ROUTES,
	RESOLVED_TAXONOMIES,
	RESOLVED_ARCHIVES,
	SYNTHETIC_ROUTES,
};
