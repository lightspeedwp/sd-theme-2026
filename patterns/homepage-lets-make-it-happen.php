<?php
/**
 * Title: Homepage — Let's Make It Happen
 * Slug: sd-theme-2026/homepage-lets-make-it-happen
 * Description: The three-step invitation that closes the homepage's enquiry run — a script heading over the numbered steps, the two office numbers side by side, and the email action beneath. Centred, on the light ground.
 * Categories: sd-theme-2026/call-to-action, sd-theme-2026/pages
 * Keywords: steps, enquiry, contact, phone, call, email, cta, homepage
 * Viewport Width: 1200
 * Template Types: front-page
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * This replaces the two-column pass that was on dev.
 *
 * That version put the heading and the three steps in a 66% column and the
 * phone numbers and button in a 33% column beside them. Live is not that shape:
 * `.phone-list-wrapper` is a single centred stack — heading, the three steps,
 * a rule, the two numbers side by side, a rule, then the button — on a white
 * ground at 1200px (measured on the live homepage 2026-08-26). Rebuilt to match.
 *
 * ## The steps are paragraphs, not headings
 *
 * Live marks each step up as an `<h3>`. That is a misuse — they are three items
 * in a sequence, not three section headings, and taking them literally would put
 * three empty-sectioned h3s between the h2 and the next band, which is exactly
 * the kind of outline a screen-reader user navigating by heading has to wade
 * through. They are centred paragraphs at the same size instead, which reads
 * identically and leaves the document outline honest. Heading hierarchy is one
 * of the theme's non-negotiables, and this is a markup fault rather than a design
 * decision, so it is not a redesign to correct it.
 *
 * ## The numbers are `tel:` links
 *
 * Live prints them as plain text beside a FontAwesome glyph. Every other place
 * the theme renders these two numbers — parts/dropdown-call-us.html and
 * patterns/footer.php — makes them `tel:` links, so this follows the theme
 * rather than the live markup. The glyph is The Icon Block carrying the same
 * Phosphor phone the rest of the theme uses, with `fill="currentColor"` so the
 * preset colour class actually reaches it.
 *
 * ## The email action goes to /contact/
 *
 * Live's button is `href="send_email"`, a bare relative path its JavaScript
 * intercepts to open Gravity Form 13 in a modal. That modal is the Enquiry
 * module (D-01–D-06, LS-2530) and is M3 work, so this links to `/contact/` —
 * the fallback patterns/safari-expert.php already documents and uses for the
 * same action. Swap the href when the module ships; do not put a Gravity Forms
 * shortcode with inline colours here.
 */

?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Homepage - Let's make it happen"},"align":"full","className":"is-style-light-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-light-page-section">
	<!-- wp:heading {"textAlign":"center","className":"is-style-script-accent","style":{"elements":{"link":{"color":{"text":"var:preset|color|brand-500"}}}},"textColor":"brand-500","anchor":"h-let-s-make-it-happen"} -->
	<h2 class="wp-block-heading has-text-align-center is-style-script-accent has-brand-500-color has-text-color has-link-color" id="h-let-s-make-it-happen"><?php esc_html_e( 'Let’s make it happen!', 'sd-theme-2026' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:group {"metadata":{"name":"Steps"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph {"align":"center","fontSize":"400"} -->
		<p class="has-text-align-center has-400-font-size"><?php esc_html_e( '1. Give us a little detail', 'sd-theme-2026' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"align":"center","fontSize":"400"} -->
		<p class="has-text-align-center has-400-font-size"><?php esc_html_e( '2. Our safari gurus will craft an itinerary just for you within 24 hours', 'sd-theme-2026' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"align":"center","fontSize":"400"} -->
		<p class="has-text-align-center has-400-font-size"><?php esc_html_e( '3. Confirm the details and get ready for the trip of a lifetime!', 'sd-theme-2026' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"Offices"},"style":{"spacing":{"blockGap":"var:preset|spacing|60"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
	<div class="wp-block-group">
		<!-- wp:group {"metadata":{"name":"Office"},"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"align":"center","style":{"typography":{"fontWeight":"var:custom|font-weight|semi-bold"}}} -->
			<p class="has-text-align-center" style="font-weight:var(--wp--custom--font-weight--semi-bold)"><?php echo esc_html_x( 'US:', 'office phone number label', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
			<div class="wp-block-group">
				<!-- wp:outermost/icon-block {"iconName":"","iconColor":"primary-600","width":"24px"} -->
				<div class="wp-block-outermost-icon-block"><div class="icon-container has-icon-color has-primary-600-color" style="width:24px"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 256 256"><path d="M222.37,158.46l-47.11-21.11-.13-.06a16,16,0,0,0-15.17,1.4,8.12,8.12,0,0,0-.75.56L134.87,160c-15.42-7.49-31.34-23.29-38.83-38.51l20.78-24.71c.2-.25.39-.5.57-.77a16,16,0,0,0,1.32-15.06l0-.12L97.54,33.64a16,16,0,0,0-16.62-9.52A56.26,56.26,0,0,0,32,80c0,79.4,64.6,144,144,144a56.26,56.26,0,0,0,55.88-48.92A16,16,0,0,0,222.37,158.46ZM176,208A128.14,128.14,0,0,1,48,80,40.2,40.2,0,0,1,82.87,40a.61.61,0,0,0,0,.12l21,47L83.2,111.86a6.13,6.13,0,0,0-.57.77,16,16,0,0,0-1,15.7c9.06,18.53,27.73,37.06,46.46,46.11a16,16,0,0,0,15.75-1.14,8.44,8.44,0,0,0,.74-.56L168.89,152l47,21.05h0s.08,0,.11,0A40.21,40.21,0,0,1,176,208Z"></path></svg></div></div>
				<!-- /wp:outermost/icon-block -->

				<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|primary-600"}}}},"textColor":"primary-600"} -->
				<p class="has-primary-600-color has-text-color has-link-color"><a href="tel:+16469068113">+1 646-906-8113</a></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"metadata":{"name":"Office"},"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"align":"center","style":{"typography":{"fontWeight":"var:custom|font-weight|semi-bold"}}} -->
			<p class="has-text-align-center" style="font-weight:var(--wp--custom--font-weight--semi-bold)"><?php echo esc_html_x( 'South Africa:', 'office phone number label', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
			<div class="wp-block-group">
				<!-- wp:outermost/icon-block {"iconName":"","iconColor":"primary-600","width":"24px"} -->
				<div class="wp-block-outermost-icon-block"><div class="icon-container has-icon-color has-primary-600-color" style="width:24px"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 256 256"><path d="M222.37,158.46l-47.11-21.11-.13-.06a16,16,0,0,0-15.17,1.4,8.12,8.12,0,0,0-.75.56L134.87,160c-15.42-7.49-31.34-23.29-38.83-38.51l20.78-24.71c.2-.25.39-.5.57-.77a16,16,0,0,0,1.32-15.06l0-.12L97.54,33.64a16,16,0,0,0-16.62-9.52A56.26,56.26,0,0,0,32,80c0,79.4,64.6,144,144,144a56.26,56.26,0,0,0,55.88-48.92A16,16,0,0,0,222.37,158.46ZM176,208A128.14,128.14,0,0,1,48,80,40.2,40.2,0,0,1,82.87,40a.61.61,0,0,0,0,.12l21,47L83.2,111.86a6.13,6.13,0,0,0-.57.77,16,16,0,0,0-1,15.7c9.06,18.53,27.73,37.06,46.46,46.11a16,16,0,0,0,15.75-1.14,8.44,8.44,0,0,0,.74-.56L168.89,152l47,21.05h0s.08,0,.11,0A40.21,40.21,0,0,1,176,208Z"></path></svg></div></div>
				<!-- /wp:outermost/icon-block -->

				<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|primary-600"}}}},"textColor":"primary-600"} -->
				<p class="has-primary-600-color has-text-color has-link-color"><a href="tel:+27216713090">+27 21 671 3090</a></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button {"className":"is-style-fill"} -->
		<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Send us an email', 'sd-theme-2026' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</section>
<!-- /wp:group -->
