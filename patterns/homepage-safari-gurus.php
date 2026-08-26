<?php
/**
 * Title: Homepage — Meet Our Safari Gurus
 * Slug: sd-theme-2026/homepage-safari-gurus
 * Description: The consultant row that closes the homepage's middle third — four team members drawn from the Team post type, each a square portrait whose bio links fade up on hover, with the name below in brown. Collapses to a single button through to the team archive on phones, as live does.
 * Categories: sd-theme-2026/features, sd-theme-2026/pages
 * Keywords: team, gurus, consultants, staff, people, query, homepage
 * Viewport Width: 1400
 * Block Types: core/query
 * Template Types: front-page
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * Replaces the hardcoded four-card grid that was authored inline in
 * templates/front-page.html.
 *
 * That grid named Liesl, Lise, Camille and Ilze in the markup, with their
 * portraits' attachment ids, their `/team/…` links and their email addresses
 * written out four times. It matched live's homepage on the day it was written
 * and would have gone stale the first time someone joined or left. This is the
 * same row as a Query Loop over the `team` post type — which is what live
 * itself does, through an LSX Team widget (`.team-widget-home`).
 *
 * ## Which four, and where that decision lives
 *
 * Live shows four of fifteen published team records, and the choice is stored
 * in the widget's own options — so there is nothing in the content to port and
 * the selection has to be re-declared somewhere.
 *
 * It is declared in the plugin, as the `role` term `safari-guru`, by
 * `Queries::constrain_safari_gurus_query()`. The `sd-safari-gurus-query` class
 * on the `core/post-template` below is what that filter matches on. The class
 * goes on the post-template and *not* on the `core/query` wrapper: the
 * `query_loop_block_query_vars` filter is applied by
 * `render_block_core_post_template()`, so the post-template is the only block
 * whose className the filter can see. This is the same mechanism, and the same
 * gotcha, as the mega menu's tours column.
 *
 * The selection is not a `taxQuery` in this file because a Query Loop stores a
 * taxonomy filter as a **term ID**, and local, dev and live do not share term
 * ids. Binding by slug in the plugin keeps the pattern portable and the curated
 * list declared once.
 *
 * **The term is empty on dev as of 2026-08-26.** Until somebody tags the four
 * people, the filter deliberately stands aside and this row renders the four
 * most recent team members instead of nothing — see the fallback note on that
 * method. Ordering (`date`/`asc`) is set here rather than there, and reproduces
 * live's order once the tagging is done.
 *
 * ## The card is the Team Member Card style, used the way live's homepage uses it
 *
 * `is-style-team-member-card` (styles/sections/cards/team-member-card.json)
 * carries the positioning context, the media reset and the hover panel. It was
 * measured from the *team archive*, where the name sits in a 70px scrim strip
 * over the bottom of the photograph — `.team-member-card__name`.
 *
 * The homepage card is not that card. Live overrides it there:
 * `.lsx-to-widget-title.text-center { background: none; position: relative }`
 * (sd-lsx-child/assets/css/partials/_cta.scss:437), which puts the name *below*
 * the photograph in brown with no strip behind it. So the styled group here
 * wraps the media and the overlay only, and the name is a sibling underneath
 * it, outside the style. That is the structure the inline version already had;
 * what it also had was `backgroundColor: base` and a `contrast` heading on that
 * sibling — a white block with black text, which is neither the archive card
 * nor live's homepage. Both are dropped, and the name takes neutral-700, the
 * palette's nearest to live's `$brown` #60483b.
 *
 * The tagline is not rendered. Live stores it in `role` post meta ("Queen Bee")
 * and then hides it on this row —
 * `.lsx-to-widget-tagline.text-center { display: none }`, _cta.scss:453. It is
 * live on the team archive, which is LS-2017's problem, and the meta is
 * deliberately left unregistered until then.
 *
 * ## Why the hover links are buttons
 *
 * They are plain text links on live, and a `core/paragraph` would be the
 * obvious block. It cannot be: a paragraph binding replaces `content`, which is
 * treated as rich text and has its markup stripped, so the `<a>` cannot come
 * from the binding — and the href has to, because it is per-post. `core/button`
 * is the only core block exposing a bindable `url`. `is-style-link-plain`
 * (styles/blocks/button/link-plain.json) exists to take the button chrome back
 * off, so the three read as the stacked links live draws.
 *
 * Their URLs come from the plugin's binding sources:
 *
 *  - **More about me** — `sd/post-field` `permalink`.
 *  - **Read my reviews** — the same, with `fragment: feedback`. The fragment is
 *    a dedicated arg rather than a `suffix`, because that source refuses to
 *    affix a URL; it is run through `sanitize_title()`.
 *  - **Get in Touch** — `sd/post-meta` on `contact_email` with the `mailto`
 *    format, which validates with `is_email()` and returns null on a bad value.
 *    A null URL leaves the authored empty href, so a record with no address —
 *    or with a name in the field, of which the D7 migration left a handful —
 *    renders a dead button rather than `mailto:Liesl`. Hiding it outright needs
 *    Block Visibility's query rules and is not worth a dependency here.
 *
 * ## Phones get the button, not the grid
 *
 * Live runs two containers: `.team-widget-home.hidden-xs` for the grid and
 * `.safari-gurus.hidden-sm.hidden-md.hidden-lg` for a single button through to
 * /team/. Note the heading is *inside* the desktop container — on a phone live
 * shows the button alone, with no section title, and that is reproduced.
 *
 * Both halves use Block Visibility's screen-size control rather than a CSS
 * media query, per the theme's convention (patterns/header.php:310). Its
 * default breakpoints put `small` below 768px, which is where live's `hidden-xs`
 * splits, so the two halves are exactly complementary.
 */

// get_post_type_archive_link() returns false when the Team post type is not
// registered — Tour Operator deactivated, or a context where its post types
// have not been declared. Falling back to live's literal path keeps the button
// pointing somewhere real rather than emitting href="".
$sd_team_archive = get_post_type_archive_link( 'team' );

if ( ! is_string( $sd_team_archive ) || '' === $sd_team_archive ) {
	$sd_team_archive = home_url( '/team/' );
}
?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Homepage - Meet our safari gurus"},"align":"full","className":"is-style-light-page-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-light-page-section">

	<!-- wp:group {"metadata":{"name":"Gurus Grid"},"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"},"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"small":true}}}}]}} -->
	<div class="wp-block-group alignwide">

		<!-- wp:heading {"textAlign":"center","className":"is-style-section-title","anchor":"h-meet-our-safari-gurus"} -->
		<h2 class="wp-block-heading has-text-align-center is-style-section-title" id="h-meet-our-safari-gurus"><?php esc_html_e( 'Meet our safari gurus', 'sd-theme-2026' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:query {"query":{"perPage":4,"pages":0,"offset":0,"postType":"team","order":"asc","orderBy":"date","inherit":false},"align":"wide"} -->
		<div class="wp-block-query alignwide">

			<!-- wp:post-template {"className":"sd-safari-gurus-query","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":4}} -->

				<!-- wp:group {"metadata":{"name":"Team Member Card"},"style":{"spacing":{"blockGap":"var:preset|spacing|0"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group">

					<!-- wp:group {"metadata":{"name":"Content"},"className":"is-style-team-member-card","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"constrained"}} -->
					<div class="wp-block-group is-style-team-member-card">

						<!-- wp:group {"metadata":{"name":"Media"},"className":"team-member-card__media","style":{"spacing":{"blockGap":"0","padding":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
						<div class="wp-block-group team-member-card__media" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
							<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"1","width":"100%","sizeSlug":"medium"} /-->
						</div>
						<!-- /wp:group -->

						<!-- wp:group {"metadata":{"name":"Overlay"},"className":"team-member-card__overlay","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"},"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
						<div class="wp-block-group team-member-card__overlay" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">

							<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
							<div class="wp-block-buttons">

								<!-- wp:button {"className":"is-style-link-plain","metadata":{"bindings":{"url":{"source":"sd/post-field","args":{"field":"permalink"}}}}} -->
								<div class="wp-block-button is-style-link-plain"><a class="wp-block-button__link wp-element-button" href=""><?php echo esc_html_x( 'More about me', 'link to a team member’s profile', 'sd-theme-2026' ); ?></a></div>
								<!-- /wp:button -->

								<!-- wp:button {"className":"is-style-link-plain","metadata":{"bindings":{"url":{"source":"sd/post-field","args":{"field":"permalink","fragment":"feedback"}}}}} -->
								<div class="wp-block-button is-style-link-plain"><a class="wp-block-button__link wp-element-button" href=""><?php echo esc_html_x( 'Read my reviews', 'link to a team member’s guest feedback', 'sd-theme-2026' ); ?></a></div>
								<!-- /wp:button -->

								<!-- wp:button {"className":"is-style-link-plain","metadata":{"bindings":{"url":{"source":"sd/post-meta","args":{"key":"contact_email","format":"mailto"}}}}} -->
								<div class="wp-block-button is-style-link-plain"><a class="wp-block-button__link wp-element-button" href=""><?php echo esc_html_x( 'Get in Touch', 'mailto link on a team member card', 'sd-theme-2026' ); ?></a></div>
								<!-- /wp:button -->

							</div>
							<!-- /wp:buttons -->

						</div>
						<!-- /wp:group -->

					</div>
					<!-- /wp:group -->

					<!-- wp:group {"metadata":{"name":"Name"},"className":"team-member-card__footer","style":{"spacing":{"blockGap":"var:preset|spacing|5","padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|20","bottom":"0","left":"var:preset|spacing|20"}}},"layout":{"type":"constrained"}} -->
					<div class="wp-block-group team-member-card__footer" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--20);padding-bottom:0;padding-left:var(--wp--preset--spacing--20)">
						<!-- wp:post-title {"textAlign":"center","level":3,"isLink":true,"style":{"typography":{"fontWeight":"var:custom|font-weight|regular","lineHeight":"var:custom|line-height|heading","textTransform":"capitalize","fontStyle":"normal"},"elements":{"link":{"color":{"text":"var:preset|color|neutral-700"},":hover":{"color":{"text":"var:preset|color|brand-500"}}}}},"textColor":"neutral-700","fontSize":"400","fontFamily":"heading"} /-->
					</div>
					<!-- /wp:group -->

				</div>
				<!-- /wp:group -->

			<!-- /wp:post-template -->

		</div>
		<!-- /wp:query -->

	</div>
	<!-- /wp:group -->

	<!-- wp:buttons {"metadata":{"name":"Gurus Link (phones)"},"layout":{"type":"flex","justifyContent":"center"},"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"large":true,"medium":true}}}}]}} -->
	<div class="wp-block-buttons">
		<!-- wp:button {"className":"is-style-outline"} -->
		<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $sd_team_archive ); ?>"><?php esc_html_e( 'Meet our safari gurus', 'sd-theme-2026' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</section>
<!-- /wp:group -->
