<?php
/**
 * Title: CTA — Inspired By This Property
 * Slug: sd-theme-2026/cta-inspired-by-this-property
 * Description: The enquiry band live runs beneath the accommodation single — a script heading over the two office numbers side by side, with the "Send us an Email" action beneath, on the warm-grey ground.
 * Categories: sd-theme-2026/call-to-action, sd-theme-2026/tour-operator
 * Keywords: cta, enquiry, contact, phone, call, email, accommodation, property, planning
 * Viewport Width: 1200
 * Template Types: single
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * The accommodation branch of live's `sd_call_info_section()`.
 *
 * `sd_lsx_to_accommodation_single_content_bottom()` calls it with
 * "Inspired by this property? Lets' start planning!"
 * (sd-lsx-child/includes/layout.php:147), inside `.lsx-full-width-base-small`,
 * between the rooms band and the Why Choose band. Measured on
 * /accommodation/chitwa-chitwa-private-game-lodge/ 2026-08-31, where the
 * rendered `#footer-info-cta` heading is that string verbatim — the stray
 * apostrophe on "Lets'" included. Content is migrated, not rewritten, so it is
 * ported as live has it.
 *
 * **Everything else about this band — the ground, the offices, the phone mark,
 * the interim `/contact/` action, and why the US number here is the toll-free
 * one rather than the number the rest of the theme carries — is set out in
 * patterns/cta-not-sure-where-to-go.php.** This is the third of the same band's
 * four live headings, and the reason the five-branch body-class conditional in
 * partials/footer-cta.php becomes *which pattern each template includes*.
 *
 * They are separate files rather than one pattern with an editable heading for
 * the reason patterns/cta-tell-us-your-trip-ideas.php gives: a pattern is a
 * copy, not a link, so a shared file whose heading is overwritten on insertion
 * would give the person building a page no way to tell which variant they had.
 *
 * ⚠️ **The three must stay in step.** Any change to the offices, the icon or
 * the action belongs in all of them. The only line that may differ is the
 * heading.
 */
?>
<!-- wp:group {"tagName":"section","metadata":{"name":"CTA - Inspired by this property"},"align":"full","className":"is-style-tinted-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-tinted-page-section">

	<!-- wp:heading {"textAlign":"center","className":"is-style-script-accent","style":{"elements":{"link":{"color":{"text":"var:preset|color|brand-500"}}}},"textColor":"brand-500","anchor":"h-inspired-by-this-property"} -->
	<h2 class="wp-block-heading has-text-align-center is-style-script-accent has-brand-500-color has-text-color has-link-color" id="h-inspired-by-this-property"><?php esc_html_e( 'Inspired by this property? Lets\' start planning!', 'sd-theme-2026' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:group {"metadata":{"name":"Offices"},"style":{"spacing":{"blockGap":"var:preset|spacing|60"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
	<div class="wp-block-group">
		<!-- wp:group {"metadata":{"name":"Office"},"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"align":"center","style":{"typography":{"fontWeight":"var:custom|font-weight|semi-bold"}},"fontSize":"400"} -->
			<p class="has-text-align-center has-400-font-size" style="font-weight:var(--wp--custom--font-weight--semi-bold)"><?php echo esc_html_x( 'US:', 'office phone number label', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
			<div class="wp-block-group">
				<!-- wp:outermost/icon-block {"iconName":"","iconColor":"neutral-700","width":"24px"} -->
				<div class="wp-block-outermost-icon-block"><div class="icon-container has-icon-color has-neutral-700-color" style="width:24px"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 256 256"><path d="M222.37,158.46l-47.11-21.11-.13-.06a16,16,0,0,0-15.17,1.4,8.12,8.12,0,0,0-.75.56L134.87,160c-15.42-7.49-31.34-23.29-38.83-38.51l20.78-24.71c.2-.25.39-.5.57-.77a16,16,0,0,0,1.32-15.06l0-.12L97.54,33.64a16,16,0,0,0-16.62-9.52A56.26,56.26,0,0,0,32,80c0,79.4,64.6,144,144,144a56.26,56.26,0,0,0,55.88-48.92A16,16,0,0,0,222.37,158.46ZM176,208A128.14,128.14,0,0,1,48,80,40.2,40.2,0,0,1,82.87,40a.61.61,0,0,0,0,.12l21,47L83.2,111.86a6.13,6.13,0,0,0-.57.77,16,16,0,0,0-1,15.7c9.06,18.53,27.73,37.06,46.46,46.11a16,16,0,0,0,15.75-1.14,8.44,8.44,0,0,0,.74-.56L168.89,152l47,21.05h0s.08,0,.11,0A40.21,40.21,0,0,1,176,208Z"></path></svg></div></div>
				<!-- /wp:outermost/icon-block -->

				<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|neutral-700"}}}},"textColor":"neutral-700","fontSize":"400"} -->
				<p class="has-neutral-700-color has-text-color has-link-color has-400-font-size"><a href="tel:+18442928240">+1-844-292-8240</a></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"metadata":{"name":"Office"},"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"align":"center","style":{"typography":{"fontWeight":"var:custom|font-weight|semi-bold"}},"fontSize":"400"} -->
			<p class="has-text-align-center has-400-font-size" style="font-weight:var(--wp--custom--font-weight--semi-bold)"><?php echo esc_html_x( 'South Africa:', 'office phone number label', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
			<div class="wp-block-group">
				<!-- wp:outermost/icon-block {"iconName":"","iconColor":"neutral-700","width":"24px"} -->
				<div class="wp-block-outermost-icon-block"><div class="icon-container has-icon-color has-neutral-700-color" style="width:24px"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 256 256"><path d="M222.37,158.46l-47.11-21.11-.13-.06a16,16,0,0,0-15.17,1.4,8.12,8.12,0,0,0-.75.56L134.87,160c-15.42-7.49-31.34-23.29-38.83-38.51l20.78-24.71c.2-.25.39-.5.57-.77a16,16,0,0,0,1.32-15.06l0-.12L97.54,33.64a16,16,0,0,0-16.62-9.52A56.26,56.26,0,0,0,32,80c0,79.4,64.6,144,144,144a56.26,56.26,0,0,0,55.88-48.92A16,16,0,0,0,222.37,158.46ZM176,208A128.14,128.14,0,0,1,48,80,40.2,40.2,0,0,1,82.87,40a.61.61,0,0,0,0,.12l21,47L83.2,111.86a6.13,6.13,0,0,0-.57.77,16,16,0,0,0-1,15.7c9.06,18.53,27.73,37.06,46.46,46.11a16,16,0,0,0,15.75-1.14,8.44,8.44,0,0,0,.74-.56L168.89,152l47,21.05h0s.08,0,.11,0A40.21,40.21,0,0,1,176,208Z"></path></svg></div></div>
				<!-- /wp:outermost/icon-block -->

				<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|neutral-700"}}}},"textColor":"neutral-700","fontSize":"400"} -->
				<p class="has-neutral-700-color has-text-color has-link-color has-400-font-size"><a href="tel:+27216713090">+27 21 671 3090</a></p>
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
		<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Send us an Email', 'sd-theme-2026' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</section>
<!-- /wp:group -->
