<?php
/**
 * Title: Safari Expert Panel
 * Slug: sd-theme-2026/safari-expert
 * Description: The "Chat to your safari expert" panel — the consultant's portrait and name, a Call Us disclosure carrying the four office numbers, an email action, and the Trustpilot score beneath. Resolves the expert for the current page through the sd/safari-expert block.
 * Categories: sd-theme-2026/call-to-action, sd-theme-2026/tour-operator
 * Keywords: expert, consultant, guru, team, contact, call, safari
 * Viewport Width: 720
 * Post Types: lsx-to-tour, lsx-to-accommodation, lsx-to-destination
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * What this replaces — K-01 / K-02.
 *
 * `sd_lsx_to_contact()` and `sd_lsx_to_enquiry_contact()` in
 * sd-lsx-child/includes/functions.php, which between them emitted
 * `<article id="safari-expert-box">` plus a `.trust-pilot-box` beneath it. Both
 * are ported here as markup; the *resolution* — which consultant this page gets
 * — is `sd/safari-expert`, because it changes the subject the inner blocks run
 * against and no binding can do that. Resolution order is the term's `expert`
 * meta → the post's `team_to_{post_type}` connection → a random member of the
 * `expert-*` pool. Nothing renders if none resolves.
 *
 * ## Heading levels
 *
 * Live uses `<h5 class="travel-expert-title">` for the eyebrow and
 * `<h3 class="lsx-to-contact-name">` for the name — an h5 above an h3, which is
 * a skipped level in the wrong direction. Here the eyebrow is a plain paragraph
 * (it labels the panel, it does not head a section) and the name is the h2 the
 * panel actually needs. Adjust the name's level if you drop this into a page
 * whose outline already uses h2.
 *
 * ## Call Us
 *
 * The disclosure is `sd/call-us` and the numbers are
 * parts/dropdown-call-us.html — the same block and the same template part the
 * header utility bar uses. That is the point of doing it this way: live builds
 * the widget twice, as a Bootstrap dropdown in the header nav and as a whole
 * `wp_nav_menu()` call here (functions.php:173), and the two drifted to two
 * numbers and four. One file now.
 *
 * Live's copy of this one opens on **hover** — `ul.menu-call-us:hover
 * .dropdown-menu` in assets/css/custom.css:3871 — with no keyboard path to the
 * numbers at all. It opens on click here.
 *
 * `placement: start` because the button sits at the left of its row.
 *
 * ## What is deliberately not here
 *
 * The **"Send an Email" modal**. Live's `sd_lsx_to_enquire_modal()` opens
 * Gravity Form 13 in a Bootstrap modal; that is the Enquiry module (D-01–D-06,
 * LS-2530) and it is M3 work. Until it lands this is a link to `/contact/`,
 * which is the fallback the plugin's own playbook prescribes. Swap the href for
 * the modal trigger when the module ships — do not add a Gravity Forms shortcode
 * with inline colours here.
 */
?>
<!-- wp:sd/safari-expert {"tagName":"section","className":"sd-expert","style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}}},"backgroundColor":"neutral-100","layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->

	<?php /* The portrait. Linked to the consultant's single, as live does. */ ?>
	<!-- wp:post-featured-image {"isLink":true,"width":"120px","height":"120px","scale":"cover","style":{"border":{"radius":"999px"}},"className":"sd-expert__portrait"} /-->

	<!-- wp:group {"metadata":{"name":"Expert Detail"},"className":"sd-expert__detail","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"constrained","justifyContent":"left"}} -->
	<div class="wp-block-group sd-expert__detail">

		<?php /* The eyebrow. A paragraph, not a heading — see the note above. */ ?>
		<!-- wp:paragraph {"className":"sd-expert__eyebrow","style":{"typography":{"fontWeight":"var:custom|font-weight|semi-bold","textTransform":"uppercase","letterSpacing":"var:custom|letter-spacing|wide"}},"textColor":"neutral-600","fontSize":"100"} -->
		<p class="sd-expert__eyebrow has-neutral-600-color has-text-color has-100-font-size" style="font-weight:var(--wp--custom--font-weight--semi-bold);letter-spacing:var(--wp--custom--letter-spacing--wide);text-transform:uppercase"><?php esc_html_e( 'Chat to your safari expert', 'sd-theme-2026' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:post-title {"level":2,"isLink":true,"className":"sd-expert__name","fontSize":"400"} /-->

		<!-- wp:group {"metadata":{"name":"Expert Actions"},"className":"sd-expert__actions","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
		<div class="wp-block-group sd-expert__actions">

			<?php
			/*
			 * The Call Us disclosure. Same block, same template part as the
			 * header — see the note above. The button takes its look from the
			 * pattern's own type tokens rather than a button style variation,
			 * because a `<button>` is not a `core/button` and cannot carry
			 * `is-style-*`; if this needs to read as a bordered button, add the
			 * rule to assets/styles/sd-call-us.css scoped to `.sd-expert`.
			 */
			?>
			<!-- wp:sd/call-us {"label":"<?php esc_attr_e( 'Call Us', 'sd-theme-2026' ); ?>","placement":"start","className":"sd-expert__call","style":{"typography":{"fontWeight":"var:custom|font-weight|semi-bold"}},"fontSize":"200"} -->
			<!-- wp:template-part {"slug":"dropdown-call-us","area":"menu"} /-->
			<!-- /wp:sd/call-us -->

			<?php /* Placeholder for the Enquiry module's modal — see the note above. */ ?>
			<!-- wp:buttons {"className":"sd-expert__email","layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-buttons sd-expert__email"><!-- wp:button {"className":"is-style-outline"} -->
			<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Send an Email', 'sd-theme-2026' ); ?></a></div>
			<!-- /wp:button --></div>
			<!-- /wp:buttons -->

		</div>
		<!-- /wp:group -->

		<?php
		/*
		 * The Trustpilot score, live's `.trust-pilot-box` beneath the panel.
		 * `require`, not a nested `wp:pattern` reference — a nested reference is
		 * dropped on front-end render while resolving fine under WP-CLI.
		 * → .claude/skills/wp-pattern-runtime-pitfalls
		 *
		 * Note that on a `team` single this badge shows the *company* score, not
		 * the consultant's: `sd/trustpilot` reads the business unit. The
		 * per-consultant reviews are `sd/trustpilot-reviews`, which picks up the
		 * team member's `truspilot_id` from context on its own.
		 */
		require __DIR__ . '/trustpilot-score.php';
		?>

	</div>
	<!-- /wp:group -->

<!-- /wp:sd/safari-expert -->
