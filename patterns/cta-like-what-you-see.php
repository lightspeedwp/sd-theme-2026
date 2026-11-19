<?php
/**
 * Title: CTA — Like What You See
 * Slug: sd-theme-2026/cta-like-what-you-see
 * Description: The enquiry band live runs beneath the Specials archive — the same band as CTA — Not Sure Where To Go, carrying that page's own heading over the two office numbers and the "Send us an Email" action, on the warm-grey ground.
 * Categories: sd-theme-2026/call-to-action, sd-theme-2026/pages
 * Keywords: cta, enquiry, contact, phone, call, email, specials, offers, start planning
 * Viewport Width: 1200
 * Template Types: archive
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * What this replaces — live's `sd_call_info_section()`.
 *
 * `#footer-info-cta.footer-cta-section`, drawn by
 * sd-lsx-child/includes/template-tags.php:467 and placed by
 * sd-lsx-child/partials/footer-cta.php. Measured from
 * /destinations/ on 2026-08-26, where it sits directly above the Why Choose
 * band — the two are siblings, emitted together by the same partial.
 *
 * It is the twin of patterns/homepage-lets-make-it-happen.php and follows that
 * file's structure deliberately: same office loop, same Phosphor phone, same
 * enquiry-modal action. The differences are the ground, the heading, and
 * that this one carries no numbered steps.
 *
 * ## This is the specials variant, and it is one copy string
 *
 * `patterns/cta-not-sure-where-to-go.php` is the canonical file — read its
 * docblock for the ground, the colour reasoning, the US-number flag and the
 * enquiry-modal action, none of which are restated here. It records that
 * live's `sd_call_info_section()` takes its title from the caller and that
 * `partials/footer-cta.php` switches on body class to pick one of four, that a
 * block theme turns that conditional into *which pattern each template
 * includes*, and that the specials heading is this issue's to write. This is
 * that file. The only difference between the two is the heading.
 *
 * ## The heading is one of four on live, and that is a template concern
 *
 * `sd_call_info_section()` takes its title from the caller, and
 * partials/footer-cta.php switches on body class to pick it:
 *
 *  - **"Not sure where to go? Chat to one of our safari gurus!"** — the
 *    destination archive and the accommodation-brand archive. This file.
 *  - "Like what you see? Let's start planning!" — the specials archive.
 *  - "Tell us your trip ideas and we'll send you ours!" — everything else.
 *  - "Feeling Lost? Chat to one of our safari gurus!" — 404, from 404.php.
 *
 * The band is suppressed entirely on the accommodation search page and on the
 * accommodation and team archives.
 *
 * A block theme selects per template, so that five-branch conditional does not
 * move anywhere — it becomes *which pattern each template includes*. This is
 * the variant LS-2014 item 4.7 names; the other three are one copy string
 * apart and belong with the templates that call for them (specials → LS-2019,
 * 404 → the 404 template, which already exists as
 * patterns/template-page-404.php and does not yet carry a CTA).
 *
 * ## Not placed in a template yet
 *
 * Deliberately, and for the same reason as patterns/why-choose-sd.php: on live
 * neither band is page content — both are emitted by the child theme beneath
 * the archive, so both are a *template* concern, and where they land is the
 * open question on LS-2033. Defining the band without placing it keeps one
 * definition ready for whichever of parts/footer.html or the archive templates
 * picks it up, without duplicating it into page bodies in the meantime.
 *
 * ## Ground and colour
 *
 * Live's `#footer-info-cta` is `$warm-grey` #f7f5f2, which is neutral-200 —
 * exactly what `is-style-tinted-page-section` already paints, along with the
 * neutral-700 body text that answers live's `$brown` #60483b. So the section
 * sets no colour of its own.
 *
 * The heading is the exception: the section style makes headings neutral-700
 * and live draws this one in `$orange` #cc7f16, which is brand-500 exactly, in
 * `$joeHand` — the accent face, which `is-style-script-accent` carries.
 *
 * The button needs no variation. Live's `.cta-btn` is `$orange` on white,
 * and theme.json's button element is already brand-500 on base.
 *
 * ## Two things flagged rather than decided
 *
 *  - ⚠️ **The US number here is not the one the rest of the theme uses.** Live
 *    hardcodes `+1-844-292-8240` in this one function — a US toll-free line.
 *    Everywhere else, including six other places on this same live page, it is
 *    `+1 646-906-8113`, and that is what parts/dropdown-call-us.html,
 *    patterns/footer.php and patterns/homepage-lets-make-it-happen.php all
 *    carry. Live's own string is ported here rather than harmonised, because a
 *    toll-free number on a high-intent enquiry CTA may well be deliberate and
 *    content is migrated, not rewritten. It is one line to change if it is not.
 *  - The **"Send us an Email" button opens the enquiry modal** —
 *    `href="#to-modal-modal-enquiry"`, which resolves to
 *    parts/modal-enquiry.html. This is the canonical account of that action;
 *    patterns/safari-expert.php, patterns/cta-tell-us-your-trip-ideas.php,
 *    patterns/cta-inspired-by-this-property.php and
 *    patterns/homepage-lets-make-it-happen.php all carry the same href and
 *    point here.
 *
 *    ⚠️ Live *renders* Gravity Form 13 into this CTA's modal but **opens
 *    Gravity Form 1**: `sd_lsx_enquire_modal_output()` prints two modals per
 *    CPT single, both with `id="lsx-enquire-modal"`, and Bootstrap resolves a
 *    `data-target` to the first match. Measured 2026-09-01 on
 *    /tour/best-of-southern-africa/ and
 *    /accommodation/singita-lebombo-lodge/. GF 1 is therefore the form that
 *    carries live's production volume and the one the rebuild keeps — Zared's
 *    decision, 2026-09-01. An earlier revision of this comment said GF 13; it
 *    was reading the DOM, not the behaviour.
 *
 *    It is a plain `core/button`, not `lsx-tour-operator/modal-button`: that
 *    block puts its `className` on the outer `wp-block-buttons` wrapper, where
 *    `is-style-fill` and `is-style-accent-cta` cannot reach it. The modal is
 *    registered off the href by `sd-enhancements`' Enquiry module, which is
 *    where the reasoning lives in full.
 *
 * ## The phone icon is inline SVG, written out per office
 *
 * Phosphor's phone, the same mark as patterns/homepage-lets-make-it-happen.php,
 * written literally into the block markup rather than echoed from a variable —
 * so there is nothing to escape and no phpcs suppression. `fill="currentColor"`
 * rather than the `#000000` the icon ships with, so the icon colour class
 * governs. The two offices are written out in full for the same reason: a
 * pattern is block markup, and core's patterns hold no loops.
 */
?>
<!-- wp:group {"tagName":"section","metadata":{"name":"CTA - Not sure where to go"},"align":"full","className":"is-style-tinted-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-tinted-page-section">

	<!-- wp:heading {"textAlign":"center","className":"is-style-script-accent","style":{"elements":{"link":{"color":{"text":"var:preset|color|brand-500"}}}},"textColor":"brand-500","anchor":"h-like-what-you-see"} -->
	<h2 class="wp-block-heading has-text-align-center is-style-script-accent has-brand-500-color has-text-color has-link-color" id="h-like-what-you-see"><?php esc_html_e( 'Like what you see? Let’s start planning!', 'sd-theme-2026' ); ?></h2>
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
