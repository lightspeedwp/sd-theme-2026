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
 * the suite: it is how you tell which of the 31 templates are actually covered.
 *
 * @package SD_Theme_2026
 * @subpackage Tests
 */

/**
 * Pages that exist regardless of content state.
 *
 * `template` is the file in templates/ that WordPress should resolve to.
 */
const STATIC_ROUTES = [
	{ name: 'front page', path: '/', template: 'front-page.html' },
	{ name: 'blog', path: '/blog/', template: 'index.html' },
	{ name: 'contact', path: '/contact/', template: 'page.html' },
	{ name: 'tour archive', path: '/tours/', template: 'archive-tour.html' },
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
	{ name: 'special archive', path: '/specials/', template: 'archive-special.html' },
	{ name: 'review archive', path: '/reviews/', template: 'archive-review.html' },
	{ name: 'sitemap', path: '/sitemap/', template: 'page.html' },
	{ name: 'brands', path: '/brand/', template: 'page-brands.html' },
	{
		name: 'faceted tour search',
		path: '/search/tours/safari/',
		template: 'archive-tour.html',
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
 * other type is legitimately empty there.
 */
const RESOLVED_ROUTES = [
	{
		key: 'tour',
		name: 'single tour',
		restBase: 'tour',
		template: 'single-tour.html',
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
	{
		key: 'review',
		name: 'single review',
		restBase: 'review',
		template: 'single-review.html',
		optional: true,
	},
	{
		key: 'special',
		name: 'single special',
		restBase: 'special',
		template: 'single-special.html',
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

module.exports = {
	STATIC_ROUTES,
	RESOLVED_ROUTES,
	RESOLVED_TAXONOMIES,
	SYNTHETIC_ROUTES,
};
