<?php
/**
 * Title: Template: Team Archive
 * Slug: sd-theme-2026/template-archive-team
 * Description: The "Meet the Team" landing page — the About Us banner, the company standfirst, and three role sections (Management Team, Consultants, Support Team), each a Query Loop of consultant tiles filtered to its own `role` term.
 * Categories: hidden
 * Keywords: team, meet the team, about us, consultants, staff, archive, landing, role
 * Block Types: core/query
 * Template Types: archive
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 *
 * @package sd-theme-2026
 */

/*
 * Ported from https://www.southerndestinations.com/team/, measured in the
 * browser on 2026-09-02. Live assembles the page from four things:
 *
 *   1. `#lsx-banner .page-banner` — LSX Banners' photograph, the Joe Hand
 *      script title "About Us" and the strapline "Meet the Team". The same
 *      device as the destinations archive, driven by a per-archive setting.
 *   2. `.lsx-to-archive-description` — the company standfirst, a Tour Operator
 *      setting rather than post content.
 *   3. Three `h3.lsx-to-archive-items-separator.lsx-title` headings, each
 *      followed by a `.lsx-to-archive-items` grid of team members carrying that
 *      `role` term. Tour Operator's `group_items_by_role` setting produces
 *      them from the one main query; here they are three Query Loops.
 *   4. `#footer-choose-cta` — the "Why choose Southern Destinations" value
 *      band, which lives in live's footer region and in this theme's archive
 *      patterns.
 *
 * ## Live puts two `h1`s on the page and only one of them is visible
 *
 * `.archive-header-wrapper` holds `<h1 class="archive-title">Team</h1>` and is
 * `display: none` — measured, not inferred. The visible `h1` is the banner's
 * "About Us", with "Meet the Team" as a paragraph beneath it. So this template
 * has one `h1` and it says About Us, which is live's rendered outline. The
 * hidden "Team" copy is a Tour Operator template artefact; reproducing it would
 * put a second `h1` on the page for no visible gain. The destinations archive
 * carries the same artefact and the same decision.
 *
 * That also means there is no `core/query-title` here. The page title is
 * authored, as it is on patterns/template-archive-destination.php, because the
 * banner title is LSX Banners' own field and is editable independently of the
 * post type's label — "About Us" is not a string `query-title` could produce.
 *
 * ## Why three Query Loops and not the main query
 *
 * Tour Operator groups the archive by role through `posts_orderby`
 * (`to-team/classes/class-to-team-frontend.php:55`), and that filter is gated
 * on `$query->is_main_query()`. It orders the one loop by the terms' own
 * `lsx_to_term_order`; it does not emit the section headings, which the archive
 * partial adds as it walks the results. Neither half is reachable from a block
 * template — a Query Loop is never the main query, and no core block prints a
 * heading when a queried post's term changes.
 *
 * Three loops, one per role, is therefore the block equivalent: the same three
 * groups in the same order, with the headings authored where live computes
 * them. It also makes each section independently editable, which the grouped
 * loop never was.
 *
 * ### The role term IDs are resolved here, not written down
 *
 * `core/query`'s `taxQuery` holds **term IDs** — core runs the values through
 * `intval()` (`wp-includes/blocks.php:2928`), so a slug is silently dropped and
 * the section renders the whole roster. Local, dev and live do not share term
 * IDs, so a literal ID in this file would be wrong in two environments out of
 * three. The IDs are looked up from their slugs below instead: a guarded
 * runtime lookup with a fallback, which is the one thing a pattern may hold in
 * a variable. → AGENTS.md, "Patterns follow core's form"
 *
 * The shape is `{"include":{"role":[id]}}`, which is what the editor writes on
 * WP 7.1 and what this file was reserialised to on the 2026-09-11 import. The
 * older `{"role":[id]}` form still works — `build_query_vars_from_query_block()`
 * keeps a back-compat branch for it (blocks.php:2907) — so the change is a
 * no-op at render time and is carried only to stop the editor rewriting the
 * file every time somebody opens it.
 *
 * The fallback is **-1, not 0**. Core's `array_filter( array_map( 'intval', … ) )`
 * strips a `0` and leaves `terms` empty, and an empty `terms` clause is dropped
 * by `WP_Tax_Query` — which would render every published member under every
 * heading, three times over. `-1` survives the filter and matches no term, so a
 * missing or renamed role renders an empty section instead. That is the failure
 * worth having: it is visible, and it points at the term rather than at this
 * file.
 *
 * The three slugs are the terms live groups by, and they exist on dev with 3, 3
 * and 9 members (checked 2026-09-02, matching live's counts). The `founder`
 * term also exists and has one member — Vanessa Ratcliffe, who is tagged
 * `management-team` as well and appears once, in that section, exactly as live
 * shows her. Live has no Founder section, so neither does this.
 *
 * ### Ordering is `menu_order`
 *
 * Tour Operator's own team ordering (`to-team/classes/class-to-team-admin.php:154`),
 * and the field the CPT exposes for it, so who comes first in a section is a
 * content decision and not a template one. Live's within-section order does not
 * match dev's migrated `menu_order` values on two of the three sections; that
 * is a content fix — drag the members into order — rather than something a
 * template can express.
 *
 * ## What is deliberately not here
 *
 * **A tinted intro band.** The destinations and tours archives put their
 * standfirst on `neutral-200` beside the safari expert panel, because live's
 * `.lsx-to-archive-header-tour` does. The team archive's description is in
 * `.lsx-to-archive-header` *without* the `-tour` suffix — it sits on white,
 * with no expert panel and no drop cap. Measured; that is why it is a plain
 * paragraph on the page ground and not `is-style-archive-intro`. Its alignment
 * and size are no longer live's — see the note on the block itself.
 *
 * **The "Not sure where to go" CTA is here even though live's team page has
 * none.** Live closes on the value band alone; its `#footer-cta` hero unit is
 * empty on this page. The pair is how every other archive in this theme ends
 * (decided 2026-08-28 — see patterns/template-archive-destination.php), so the
 * page ends the way its siblings do. Removing it is one line.
 *
 * `require`, not nested `wp:pattern` references — a pattern referencing another
 * pattern resolves under WP-CLI and is silently dropped on front-end render.
 * → .claude/skills/wp-pattern-runtime-pitfalls
 */

/*
 * Media is addressed by its URL on dev, and written out literally as core
 * writes asset URLs — the same deliberate exception, for the same reasons, that
 * patterns/footer.php and patterns/template-archive-destination.php set out.
 */

$sd_role_management  = get_term_by( 'slug', 'management-team', 'role' );
$sd_role_consultants = get_term_by( 'slug', 'consultants', 'role' );
$sd_role_support     = get_term_by( 'slug', 'support-team', 'role' );

// -1 rather than 0 when the term is missing — see "The fallback is -1" above.
$sd_role_management  = $sd_role_management instanceof \WP_Term ? (int) $sd_role_management->term_id : -1;
$sd_role_consultants = $sd_role_consultants instanceof \WP_Term ? (int) $sd_role_consultants->term_id : -1;
$sd_role_support     = $sd_role_support instanceof \WP_Term ? (int) $sd_role_support->term_id : -1;
?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Team Archive"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0">

	<?php
	/*
	 * The banner. Same construction as the destinations archive, and the notes
	 * there apply here in full: `is-style-hero-banner` owns the floor, the
	 * padding, the scrim and the type colours; `dimRatio: 100` lets that style
	 * be the single source of the scrim rather than multiplying two alphas; the
	 * photograph is decorative, so `alt=""`; and there is no `id`, because an
	 * attachment ID cannot be right in two environments at once.
	 *
	 * `minHeight: 400` matches the sibling archive rather than live's measured
	 * 380px. Live runs 380px on *both* this page and the destinations archive,
	 * and the theme standardises its inner-page banners at one floor: 400px
	 * since 2026-09-23, down from 454 (→ patterns/hero-page-banner.php, and the
	 * style's own description). Keeping the two archives at one height is the
	 * existing decision; this page is not the place to reopen it.
	 *
	 * The content group is flow layout, not constrained, so `alignwide` buys
	 * the children the full 1520px rail instead of handing them back the
	 * content measure. Live's banner type starts at the left edge of its
	 * container.
	 */
	?>
	<!-- wp:cover {"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/11/header-about-us-new.jpg","alt":"","dimRatio":100,"overlayColor":"neutral-900","isUserOverlayColor":true,"minHeight":400,"minHeightUnit":"px","contentPosition":"bottom center","align":"full","className":"is-style-hero-banner","tagName":"section","metadata":{"name":"Banner"},"style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-cover alignfull has-custom-content-position is-position-bottom-center is-style-hero-banner" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);min-height:400px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-900-background-color has-background-dim-100 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/11/header-about-us-new.jpg" data-object-fit="cover"/><div class="wp-block-cover__inner-container">

		<!-- wp:group {"metadata":{"name":"Banner Content"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group alignwide">

			<?php
			/*
			 * Live's `.page-title` — the Joe Hand script face at 60px and
			 * weight 200, which is exactly what `is-style-script-accent`
			 * carries. Only the size is set: the style rests at 600 (40px) for
			 * in-page use and the banner wants 800 (64px).
			 */
			?>
			<!-- wp:heading {"level":1,"className":"is-style-script-accent","fontSize":"800"} -->
			<h1 class="wp-block-heading is-style-script-accent has-800-font-size"><?php esc_html_e( 'About Us', 'sd-theme-2026' ); ?></h1>
			<!-- /wp:heading -->

			<?php
			/*
			 * Live's `.tagline` — heading face, 30px, weight 600, sentence
			 * case. `is-style-subheading-large` carries the size and the snug
			 * leading; the family and the weight are set here. A paragraph and
			 * not a heading: it is a strapline under the `h1` and it heads
			 * nothing.
			 */
			?>
			<!-- wp:paragraph {"className":"is-style-subheading-large","style":{"typography":{"fontWeight":"var:custom|font-weight|medium"}},"fontFamily":"heading"} -->
			<p class="is-style-subheading-large has-heading-font-family" style="font-weight:var(--wp--custom--font-weight--medium)"><?php esc_html_e( 'Meet the Team', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:group -->

	</div></section>
	<!-- /wp:cover -->

	<?php
	/*
	 * The breadcrumb bar, directly under the banner — the same
	 * `patterns/breadcrumbs.php` every other archive and single in this theme
	 * runs, in the same position live puts it. The distinction the note on
	 * `patterns/breadcrumbs.php` records: filtering what Yoast *puts* in the
	 * trail is plugin work, but the band it sits in is a strip of theme markup
	 * around a third-party block, and it deactivates with the theme. This file
	 * used to record the opposite under "deliberately not here"; that note
	 * predated the 2026-09-03 decision and has been removed rather than left to
	 * contradict it.
	 */
	require __DIR__ . '/breadcrumbs.php';
	?>

	<?php
	/*
	 * The page body: the standfirst, then the three role sections.
	 *
	 * `blockGap` at spacing|100 is the space between one section's last row and
	 * the next heading. Live's is `margin-top: 8.5rem` (136px) on the separator,
	 * which is past the top of this theme's spacing scale — 100 resolves to
	 * 100px at 1440px and is the closest token. The scale is generated from the
	 * token map, so widening it for one page is not this file's call.
	 * → DESIGN.md
	 */
	?>
	<!-- wp:group {"tagName":"section","metadata":{"name":"Team"},"align":"full","style":{"spacing":{"blockGap":"var:preset|spacing|100","padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--80)">

		<?php
		/*
		 * The standfirst. Live's `.lsx-to-archive-description`, ending in two
		 * `<br>`s that are markup noise and are not carried.
		 *
		 * ⚠️ **Centred in a 1100px measure, which is a departure from live.**
		 * Live runs it left-aligned and roman at 15px; this is centred at
		 * font-size 300 inside an `Intro` group constrained to 1100px. Authored
		 * in the Site Editor on dev 2026-09-11 (wp_template 65947) and imported
		 * here — Zared's call, not a measurement. The group exists to hold the
		 * measure: the section's own `constrained` layout is the theme's
		 * content width, and the standfirst wanted a narrower one.
		 *
		 * On live this is a Tour Operator *setting*, not post content — there
		 * is no block for it in TO 2.2 — so it is authored here, verbatim.
		 * Content is migrated, not rewritten. It becomes a block binding the
		 * moment `sd-enhancements` exposes the TO archive-description setting
		 * as a source, at which point this string is the fallback rather than
		 * the value; until then, editing it means editing the template.
		 */
		?>
		<!-- wp:group {"metadata":{"name":"Intro"},"align":"wide","layout":{"type":"constrained","contentSize":"1100px"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:paragraph {"align":"wide","className":"is-style-default","style":{"typography":{"textAlign":"center"}},"fontSize":"300"} -->
			<p class="has-text-align-center alignwide is-style-default has-300-font-size"><?php esc_html_e( 'We have been in the wonderful world of travel for 28 years this year and can proudly say that thanks mainly to a rich collection of past clients, staff, suppliers, friends and family, we are well established, respected and unblemished. We started small on solid foundations of passion for our country and dedication to client service.', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:group -->

		<?php
		/*
		 * The three role sections.
		 *
		 * Each is a heading plus a Query Loop, written out rather than looped:
		 * a pattern is block markup that happens to live in a `.php` file.
		 * → AGENTS.md, "No loops and no computed markup"
		 *
		 * The headings are `h2`. Live's separators are `h3` under a hidden
		 * `h1`, which is a skipped level; here they sit directly under the
		 * banner's `h1` and `h2` is the correct level. `is-style-section-title`
		 * is live's own `.lsx-title` device — uppercase heading face at bold in
		 * neutral-700, centred, over the 80x2px accent-500 rule — which is
		 * exactly the class live's separators carry. The style rests at
		 * font-size 500 rather than the 22px live computes here; live's own
		 * `.lsx-title` is 28px and the 22px is an artefact of the separator
		 * being an `h3`, so the level correction takes the size with it. The
		 * gold rule's bottom margin is this section's `blockGap`, which is why
		 * each section sets one.
		 *
		 * `inherit: false` on every loop — the main query is the whole roster,
		 * and each section needs its own slice of it.
		 *
		 * `perPage: 100` with no `core/query-pagination`: live paginates
		 * nothing, the largest section holds nine, and a per-section paginator
		 * would page the whole document. A `core/query-no-results` message
		 * stands in each one so a section whose term has emptied says so
		 * instead of rendering a bare heading.
		 *
		 * ⚠️ **Those three messages do not currently render, and the reason is
		 * not in this file.** FacetWP Blocks Beta hooks
		 * `render_block_core/query-no-results` and returns `''` for every
		 * instance, sitewide —
		 * `facetwp-blocks-beta/includes/class-blocks-integration.php:724`. Its
		 * stated reason is that core already prints no-results content inside
		 * `core/post-template` when the query inherits from the template, which
		 * is true for `inherit: true` and not true for a loop like these. So
		 * every non-inheriting `query-no-results` on this install is blank,
		 * `templates/archive-review.html`'s included. Measured on local
		 * 2026-09-02: with the role terms absent all three sections render a
		 * heading and nothing beneath it. The markup here is correct and is
		 * kept rather than deleted; the suppression is a third-party defect.
		 * → LS-2529
		 *
		 * The tile is patterns/card-team.php, `require`d rather than referenced
		 * as a nested pattern.
		 */
		?>
		<!-- wp:group {"metadata":{"name":"Management Team"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|80"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:heading {"textAlign":"center","className":"is-style-section-title"} -->
			<h2 class="wp-block-heading has-text-align-center is-style-section-title"><?php esc_html_e( 'Management Team', 'sd-theme-2026' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:query {"queryId":0,"query":{"perPage":100,"pages":0,"offset":0,"postType":"team","order":"asc","orderBy":"menu_order","search":"","exclude":[],"sticky":"","inherit":false,"parents":[],"taxQuery":{"include":{"role":[<?php echo (int) $sd_role_management; ?>]}}},"align":"wide","layout":{"type":"default"}} -->
			<div class="wp-block-query alignwide">

				<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":null}} -->
					<?php require __DIR__ . '/card-team.php'; ?>
				<!-- /wp:post-template -->

				<!-- wp:query-no-results -->
					<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}}} -->
					<p class="has-text-align-center"><?php esc_html_e( 'No team members are listed in this role yet.', 'sd-theme-2026' ); ?></p>
					<!-- /wp:paragraph -->
				<!-- /wp:query-no-results -->

			</div>
			<!-- /wp:query -->

		</div>
		<!-- /wp:group -->

		<!-- wp:group {"metadata":{"name":"Consultants"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|80"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:heading {"textAlign":"center","className":"is-style-section-title"} -->
			<h2 class="wp-block-heading has-text-align-center is-style-section-title"><?php esc_html_e( 'Consultants', 'sd-theme-2026' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:query {"queryId":0,"query":{"perPage":100,"pages":0,"offset":0,"postType":"team","order":"asc","orderBy":"menu_order","search":"","exclude":[],"sticky":"","inherit":false,"parents":[],"taxQuery":{"include":{"role":[<?php echo (int) $sd_role_consultants; ?>]}}},"align":"wide","layout":{"type":"default"}} -->
			<div class="wp-block-query alignwide">

				<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":null}} -->
					<?php require __DIR__ . '/card-team.php'; ?>
				<!-- /wp:post-template -->

				<!-- wp:query-no-results -->
					<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}}} -->
					<p class="has-text-align-center"><?php esc_html_e( 'No team members are listed in this role yet.', 'sd-theme-2026' ); ?></p>
					<!-- /wp:paragraph -->
				<!-- /wp:query-no-results -->

			</div>
			<!-- /wp:query -->

		</div>
		<!-- /wp:group -->

		<!-- wp:group {"metadata":{"name":"Support Team"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|80"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:heading {"textAlign":"center","className":"is-style-section-title"} -->
			<h2 class="wp-block-heading has-text-align-center is-style-section-title"><?php esc_html_e( 'Support Team', 'sd-theme-2026' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:query {"queryId":0,"query":{"perPage":100,"pages":0,"offset":0,"postType":"team","order":"asc","orderBy":"menu_order","search":"","exclude":[],"sticky":"","inherit":false,"parents":[],"taxQuery":{"include":{"role":[<?php echo (int) $sd_role_support; ?>]}}},"align":"wide","layout":{"type":"default"}} -->
			<div class="wp-block-query alignwide">

				<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":null}} -->
					<?php require __DIR__ . '/card-team.php'; ?>
				<!-- /wp:post-template -->

				<!-- wp:query-no-results -->
					<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}}} -->
					<p class="has-text-align-center"><?php esc_html_e( 'No team members are listed in this role yet.', 'sd-theme-2026' ); ?></p>
					<!-- /wp:paragraph -->
				<!-- /wp:query-no-results -->

			</div>
			<!-- /wp:query -->

		</div>
		<!-- /wp:group -->

	</section>
	<!-- /wp:group -->

	<?php
	/*
	 * The two closing bands — the value band over its photograph, then the
	 * contact CTA on the tinted ground. This is why `<main>` above carries no
	 * bottom padding: the CTA brings its own, and a padding on the wrapper
	 * would show as a strip of page ground beneath a full-bleed section.
	 */
	require __DIR__ . '/why-choose-sd.php';
	require __DIR__ . '/cta-not-sure-where-to-go.php';
	?>

</main>
<!-- /wp:group -->
