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
 * The disclosure is a `core/accordion` carrying `is-style-call-us-dropdown`, and
 * the numbers are parts/dropdown-call-us.html — the same construction and the
 * same template part the header utility bar uses. That is the point of doing it
 * this way: live builds the widget twice, as a Bootstrap dropdown in the header
 * nav and as a whole `wp_nav_menu()` call here (functions.php:173), and the two
 * drifted to two numbers and four. One file now.
 *
 * It was the `sd/call-us` plugin block until 2026-08-21. WP 7.1's accordion
 * ships the whole of what that block did — a real `<button>` with
 * `aria-expanded` and `aria-controls`, and the panel state on the Interactivity
 * API — so the block was retired rather than maintained. Escape and
 * click-outside dismissal are the two things core does not do; both are
 * behaviour, so they belong in `sd-enhancements` if they come back. → LS-2033
 *
 * Live's copy of this one opens on **hover** — `ul.menu-call-us:hover
 * .dropdown-menu` in assets/css/custom.css:3871 — with no keyboard path to the
 * numbers at all. It opens on click here.
 *
 * The panel drops from the button's inline start, which is the variation's
 * default and right here because the button sits at the left of its row. The
 * header flips it; see assets/styles/core-accordion.css.
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
			 * The Call Us disclosure. Same construction, same template part as
			 * the header — see the note above. It takes its look from the
			 * pattern's own type tokens: `is-style-call-us-dropdown` styles the
			 * panel and the rows, and the trigger's size and weight are set on
			 * the block, which is where they belong now that `core/accordion`
			 * serialises `typography.fontWeight` properly.
			 *
			 * `showIcon: false` — core's indicator is a `+` that rotates into an
			 * `×`; live's caret is drawn in assets/styles/core-accordion.css.
			 *
			 * `headingLevel: 4`, not the header's 3: this panel's own name is an
			 * h2 (see the outline note above), so the disclosure's heading sits
			 * under it rather than beside it. Core's accordion always wraps its
			 * toggle in a heading — the block's save is `"h" + headingLevel` and
			 * there is no way to opt out — so the only choice is which level.
			 */
			?>
			<!-- wp:accordion {"showIcon":false,"headingLevel":4,"className":"is-style-call-us-dropdown","style":{"typography":{"fontWeight":"var:custom|font-weight|semi-bold"}},"fontSize":"200"} -->
			<div role="group" class="wp-block-accordion is-style-call-us-dropdown has-200-font-size" style="font-weight:var(--wp--custom--font-weight--semi-bold)"><!-- wp:accordion-item -->
			<div class="wp-block-accordion-item"><!-- wp:accordion-heading {"title":"<?php esc_attr_e( 'Call Us', 'sd-theme-2026' ); ?>","level":4,"showIcon":false} -->
			<h4 class="wp-block-accordion-heading"><button type="button" class="wp-block-accordion-heading__toggle"><span class="wp-block-accordion-heading__toggle-title"><?php esc_html_e( 'Call Us', 'sd-theme-2026' ); ?></span></button></h4>
			<!-- /wp:accordion-heading -->

			<!-- wp:accordion-panel {"style":{"spacing":{"blockGap":"0"}}} -->
			<div role="region" class="wp-block-accordion-panel"><!-- wp:template-part {"slug":"dropdown-call-us","area":"menu"} /--></div>
			<!-- /wp:accordion-panel --></div>
			<!-- /wp:accordion-item --></div>
			<!-- /wp:accordion -->

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
