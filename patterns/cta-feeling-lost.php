<?php
/**
 * Title: CTA — Feeling Lost
 * Slug: sd-theme-2026/cta-feeling-lost
 * Description: The enquiry band live runs beneath the 404 page — a script heading over the two office numbers side by side, with the "Send us an Email" action beneath, on the warm-grey ground.
 * Categories: sd-theme-2026/call-to-action, sd-theme-2026/pages
 * Keywords: cta, enquiry, contact, phone, call, email, safari gurus, 404, lost, not found
 * Viewport Width: 1200
 * Template Types: 404
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * The fourth of live's four enquiry-band headings, and the last one to be built.
 *
 * `patterns/cta-not-sure-where-to-go.php` is the canonical account of this band —
 * what it replaces (`sd_call_info_section()`, drawn by
 * sd-lsx-child/includes/template-tags.php:467), why the ground is
 * `is-style-tinted-page-section`, why the heading is `is-style-script-accent` in
 * brand-500, why the US number here is the toll-free `+1-844-292-8240` rather
 * than the `+1 646-906-8113` the rest of the theme carries, why the button is a
 * plain `core/button` pointing at `#to-modal-modal-enquiry`, and why the phone
 * SVG is written out per office instead of looped. **Read that file, not this
 * one** — none of it is repeated here.
 *
 * That file also predicted this one: it lists the four headings
 * `sd-lsx-child/partials/footer-cta.php` switches between, records that a block
 * theme turns that conditional into *which pattern each template includes*, and
 * notes that the 404 variant "belongs with the template that calls for it". This
 * is that variant. It is also the one variant that never reaches the body-class
 * switch: `sd-lsx-child/404.php`, line 57, hands the heading to
 * `sd_call_info_section()` as a literal argument, so the 404's wording is set by
 * the template rather than chosen by `partials/footer-cta.php` like the other three.
 *
 * ⚠️ **One copy string and one anchor separate this file from its sibling.**
 * Everything else — the offices, the icon, the button, the ground — is the same
 * band. A change to one is a change to both.
 *
 * Unlike its sibling this one *is* placed: `patterns/template-page-404.php`
 * requires it, in live's order, directly above `patterns/why-choose-sd.php`.
 */
?><!-- wp:group {"tagName":"section","metadata":{"name":"CTA - Feeling lost"},"align":"full","className":"is-style-tinted-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-tinted-page-section">

	<!-- wp:heading {"textAlign":"center","className":"is-style-script-accent","style":{"elements":{"link":{"color":{"text":"var:preset|color|brand-500"}}}},"textColor":"brand-500","anchor":"h-feeling-lost"} -->
	<h2 class="wp-block-heading has-text-align-center is-style-script-accent has-brand-500-color has-text-color has-link-color" id="h-feeling-lost"><?php esc_html_e( 'Feeling Lost? Chat to one of our safari gurus!', 'sd-theme-2026' ); ?></h2>
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
				<div class="wp-block-outermost-icon-block"><div class="icon-container has-neutral-700-color" style="width:24px;transform:rotate(0deg) scaleX(1) scaleY(1)"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 256 256"><path d="M222.37,158.46l-47.11-21.11-.13-.06a16,16,0,0,0-15.17,1.4,8.12,8.12,0,0,0-.75.56L134.87,160c-15.42-7.49-31.34-23.29-38.83-38.51l20.78-24.71c.2-.25.39-.5.57-.77a16,16,0,0,0,1.32-15.06l0-.12L97.54,33.64a16,16,0,0,0-16.62-9.52A56.26,56.26,0,0,0,32,80c0,79.4,64.6,144,144,144a56.26,56.26,0,0,0,55.88-48.92A16,16,0,0,0,222.37,158.46ZM176,208A128.14,128.14,0,0,1,48,80,40.2,40.2,0,0,1,82.87,40a.61.61,0,0,0,0,.12l21,47L83.2,111.86a6.13,6.13,0,0,0-.57.77,16,16,0,0,0-1,15.7c9.06,18.53,27.73,37.06,46.46,46.11a16,16,0,0,0,15.75-1.14,8.44,8.44,0,0,0,.74-.56L168.89,152l47,21.05h0s.08,0,.11,0A40.21,40.21,0,0,1,176,208Z"></path></svg></div></div>
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
				<div class="wp-block-outermost-icon-block"><div class="icon-container has-neutral-700-color" style="width:24px;transform:rotate(0deg) scaleX(1) scaleY(1)"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 256 256"><path d="M222.37,158.46l-47.11-21.11-.13-.06a16,16,0,0,0-15.17,1.4,8.12,8.12,0,0,0-.75.56L134.87,160c-15.42-7.49-31.34-23.29-38.83-38.51l20.78-24.71c.2-.25.39-.5.57-.77a16,16,0,0,0,1.32-15.06l0-.12L97.54,33.64a16,16,0,0,0-16.62-9.52A56.26,56.26,0,0,0,32,80c0,79.4,64.6,144,144,144a56.26,56.26,0,0,0,55.88-48.92A16,16,0,0,0,222.37,158.46ZM176,208A128.14,128.14,0,0,1,48,80,40.2,40.2,0,0,1,82.87,40a.61.61,0,0,0,0,.12l21,47L83.2,111.86a6.13,6.13,0,0,0-.57.77,16,16,0,0,0-1,15.7c9.06,18.53,27.73,37.06,46.46,46.11a16,16,0,0,0,15.75-1.14,8.44,8.44,0,0,0,.74-.56L168.89,152l47,21.05h0s.08,0,.11,0A40.21,40.21,0,0,1,176,208Z"></path></svg></div></div>
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
		<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="#to-modal-modal-enquiry"><?php esc_html_e( 'Send us an Email', 'sd-theme-2026' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</section>
<!-- /wp:group -->
