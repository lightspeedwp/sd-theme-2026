<?php
/**
 * Title: Safari Expert Panel
 * Slug: sd-theme-2026/safari-expert
 * Description: The "Chat to your safari expert" panel — the consultant's portrait and name on a brand-coloured card, a Call Us dropdown carrying the four office numbers, an email action, and the Trustpilot score beneath the card. Resolves the expert for the current page through the sd/safari-expert block.
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
 * ## The card — what is carried from live, and what is not
 *
 * Rebuilt 2026-08-28 against live's own measurements, at Zared's request: the
 * neutral-100 card this had been was not the widget live ships. Live is
 * assets/css/custom.css:3791 onward, and it is carried almost whole —
 *
 *     live (`#safari-expert-box`)              here
 *     --------------------------------------  ---------------------------------
 *     background-color: #cc7f16               brand-600 — see the contrast note
 *     padding: 15px                           spacing 20
 *     border-radius: 1px                      border-radius 200 (8px)
 *     display: flex; align-items: center      flex, wrap, centred
 *     thumb 116 × 116, object-fit: cover      116px, radius 500, base ring
 *     .travel-expert-title  white, uppercase  base, uppercase, wide tracking
 *     .lsx-to-contact-name  white             base, title case, font-size 400
 *     .lsx-to-meta-data     1px #fff, radius 2px, white text
 *                                             is-style-outline-light's clothing
 *     .lsx-to-enquire-form  the amber CTA     is-style-accent-cta
 *     .trust-pilot-box beneath the card       beneath the card
 *
 * The facelift is four things and nothing else: the 1px corner becomes an 8px
 * one, the portrait gains a 2px `base` ring so it separates from the ground,
 * the two actions take the theme's own button clothing rather than live's
 * hand-rolled boxes, and the ground moves one token deeper (below).
 *
 * ### ⚠️ The ground is brand-600, not live's brand-500
 *
 * Live's `#cc7f16` **is** brand-500 exactly — the palette carries it. It is not
 * used here because white text on it measures **3.18:1**, under the 4.5:1
 * AA floor for the eyebrow, the Call Us label and the office numbers; and
 * accent-400 — the "Send an Email" fill — measures **1.83:1** against it, well
 * under the 3:1 SC 1.4.11 floor for a control's own boundary. Both are live
 * failings, inherited rather than introduced.
 *
 * brand-600 is the next step down the same ramp. Measured off the rendered
 * page on local, 2026-08-28:
 *
 *     white eyebrow / name / label   5.18:1   AA at any size
 *     white Call Us outline          5.18:1   SC 1.4.11
 *     accent-400 CTA fill            2.98:1   see below
 *     primary-600 CTA label          7.23:1   AA at any size
 *
 * The CTA fill lands a hundredth under the 3:1 non-text floor, and that is
 * accepted rather than tuned: SC 1.4.11 asks for contrast on "visual
 * information required to identify" a control, and this control is identified
 * by a 19.2px uppercase label at 7.23:1, not by its fill. Moving it would mean
 * either a bespoke colour — no palette token sits between accent-400 and the
 * card — or giving `is-style-accent-cta` a visible boundary everywhere it is
 * used, which is a change to a shared button style and not this panel's to
 * make.
 *
 * Nothing between brand-500 and brand-600 exists in the palette, so the ground
 * is one token step, not a tuned value. **If Zared wants live's exact orange back it is one word** —
 * `brand-600` → `brand-500` on the panel group below — and the panel then ships
 * live's contrast failure with it. AGENTS.md makes a11y non-negotiable, so the
 * accessible token is what is authored; the decision is recorded here rather
 * than taken quietly.
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
 * The name carries `textTransform: none` and `letterSpacing: none` inline
 * because `styles.elements.h2` in theme.json sets uppercase and heading
 * tracking, and an element style outranks a block attribute on the very
 * element it targets — `core/post-title`'s wrapper *is* the `<h2>`. Live's name
 * is title case ("Liesl Matthews"), so it is title case here. The font-size is
 * left as the `has-400-font-size` class, which measurably wins against
 * `elements.h2`'s 500 on this theme's stylesheet order.
 *
 * ## Call Us
 *
 * This is the **header's Call Us**, not the accordion this carried until
 * 2026-08-28 — Zared's call. One construction for the widget across the site:
 * an `ollie/mega-menu` inside a `core/navigation` carrying
 * `is-style-call-us-navigation`, opening on hover, whose panel is
 * parts/dropdown-call-us.html reached through `menuSlug`. That is the point of
 * doing it this way: live builds the widget twice, as a Bootstrap dropdown in
 * the header nav and as a whole `wp_nav_menu()` call here
 * (functions.php:173), and the two drifted to two numbers and four. One file
 * now, and now one *mechanism* too.
 *
 * ### The `ollie/mega-menu` is authored inline — no `ref`
 *
 * The header reaches its copy through `ref` 65909, a `wp_navigation` post. This
 * one does not, and that is deliberate: the objection this file's predecessor
 * raised against the swap — that it "would make a `wp_navigation` post a
 * dependency of a template that renders per post" — only holds for the `ref`
 * form. `WP_Block_Type_Navigation::get_inner_blocks()` (wp-includes/blocks/
 * navigation.php:518) starts from `$block->inner_blocks` and *only* replaces
 * them when `ref` is present in the attributes; with no `ref` the authored
 * inner block renders as written. Verified against WP 7.1's own source.
 *
 * So the panel travels in the theme file, the numbers still come from the one
 * shared template part, and there is no database row this pattern needs.
 *
 * Note that the Site Editor will offer to convert an inline navigation into a
 * saved menu if someone edits and saves a template containing this. That is an
 * editor-side conversion and does not touch this file; theme files are the
 * source of truth here. → wp-db-override-reconciliation
 *
 * ### The landmark label
 *
 * `ariaLabel` is not optional: `core/navigation` renders a `<nav>`, and on a
 * Tour Operator single this is the *third* one in the document — the header's
 * "Call us" and the mobile menu's "Contact numbers" are both already in the
 * DOM. Where two landmarks share a label WordPress disambiguates them by
 * appending a number ("Contact numbers 2"), which is technically distinct and
 * useless to read out, so this one gets a label of its own: "Office numbers".
 *
 * ### The trigger is a box, and the box is the target
 *
 * Live draws its Call Us as a bordered box with a phone glyph inside it
 * (`.lsx-to-meta-data`: `1px solid #fff`, white text, hover `#D59844`), and the
 * whole box is one `<a>`. Here the box is a `core/group` carrying the border,
 * radius and padding as block attributes, holding the icon beside the
 * navigation — because `ollie/mega-menu`'s `label` is a block attribute that
 * `render.php` escapes, so no markup can go inside the button.
 *
 * That would leave the icon as 20px of dead zone inside a box that reads as a
 * button — the cost the header accepts, and the wrong trade here. So the box is
 * the positioning context and the toggle is stretched over it with an
 * `::after`, the same technique the number rows already use. The nav, its
 * container and its item go `position: static` for it; all of that is in
 * assets/styles/ollie-mega-menu.css, along with the hover treatment and the
 * panel's placement at the box's inline start.
 *
 * The icon carries **no** `iconColor`. Its SVG is `fill="currentColor"`, so it
 * takes the panel's `base` on rest and the box's `primary-500` on hover for
 * free — one source of truth instead of a preset colour class that would have
 * to be fought with `!important` (core marks `.has-*-color` important).
 *
 * ⚠️ Phosphor's phone glyph, the same path used in patterns/header.php,
 * homepage-dream-trip.php, homepage-lets-make-it-happen.php and
 * cta-not-sure-where-to-go.php. Written out rather than echoed from a variable,
 * as core's patterns do; if it changes it changes in all five.
 *
 * ### What went with the accordion
 *
 * `is-style-call-us-dropdown` (styles/blocks/accordion/call-us-dropdown.json)
 * and the `.is-style-call-us-dropdown` half of assets/styles/core-accordion.css
 * now have **no placement anywhere in the theme** — this was the last one. They
 * are left in place rather than deleted, because deleting a registered style
 * variation is Zared's call and core-accordion.css only loads where a
 * `core/accordion` renders, so the cost of leaving it is a dead entry in the
 * editor's style picker. → LS-2033 if it is worth a line.
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
<!-- wp:sd/safari-expert {"tagName":"section","className":"sd-expert","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->

	<?php
	/*
	 * The card. `elements.link` is set on this group rather than left to the
	 * global one, which is `contrast` on rest and **brand-600 on hover** — the
	 * card's own ground, so the name would vanish under the pointer. Both
	 * states are `base` here; `styles.blocks.core/post-title` already restores
	 * the underline on hover, which is what carries the state.
	 */
	?>
	<!-- wp:group {"metadata":{"name":"Expert Card"},"className":"sd-expert__panel","style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20"}},"border":{"radius":"var:preset|border-radius|200"},"elements":{"link":{"color":{"text":"var:preset|color|base"},":hover":{"color":{"text":"var:preset|color|base"}}}}},"backgroundColor":"brand-600","textColor":"base","layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
	<div class="wp-block-group sd-expert__panel has-link-color has-base-color has-brand-600-background-color has-text-color has-background" style="border-radius:var(--wp--preset--border-radius--200);padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)">

		<?php
		/*
		 * The portrait. Linked to the consultant's single, as live does.
		 *
		 * `selfStretch: fixedNoShrink` rather than a stylesheet rule: it is the
		 * child-layout support's own way of saying "this flex child is 116px
		 * and does not shrink", and it emits `flex-basis: 116px; flex-shrink: 0`
		 * (wp-includes/block-supports/layout.php:148). Without it the portrait
		 * squashes before the detail column wraps.
		 */
		?>
		<!-- wp:post-featured-image {"isLink":true,"width":"116px","height":"116px","scale":"cover","style":{"border":{"radius":"var:preset|border-radius|500","width":"var(--wp--custom--border-width--200)","style":"solid","color":"var:preset|color|base"},"layout":{"selfStretch":"fixedNoShrink","flexSize":"116px"}},"className":"sd-expert__portrait"} /-->

		<!-- wp:group {"metadata":{"name":"Expert Detail"},"className":"sd-expert__detail","style":{"spacing":{"blockGap":"var:preset|spacing|10"},"layout":{"selfStretch":"fill"}},"layout":{"type":"constrained","justifyContent":"left"}} -->
		<div class="wp-block-group sd-expert__detail">

			<?php /* The eyebrow. A paragraph, not a heading — see the note above. */ ?>
			<!-- wp:paragraph {"className":"sd-expert__eyebrow","style":{"typography":{"fontWeight":"var:custom|font-weight|regular","textTransform":"uppercase","letterSpacing":"var:custom|letter-spacing|wide"}},"fontSize":"100"} -->
			<p class="sd-expert__eyebrow has-100-font-size" style="font-weight:var(--wp--custom--font-weight--regular);letter-spacing:var(--wp--custom--letter-spacing--wide);text-transform:uppercase"><?php esc_html_e( 'Chat to your safari expert', 'sd-theme-2026' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:post-title {"level":2,"isLink":true,"className":"sd-expert__name","style":{"typography":{"textTransform":"none","letterSpacing":"var(--wp--custom--letter-spacing--none)"}},"fontSize":"400"} /-->

			<!-- wp:group {"metadata":{"name":"Expert Actions"},"className":"sd-expert__actions","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"stretch"}} -->
			<div class="wp-block-group sd-expert__actions">

				<?php
				/*
				 * The Call Us box — the icon beside the navigation, both inside
				 * a group that carries live's `.lsx-to-meta-data` clothing as
				 * block attributes. Radius 0 and the button padding token, so
				 * the box matches the height and the corner of the accent CTA
				 * beside it rather than being a second shape.
				 *
				 * See the long note above for why the box (and not the toggle)
				 * is the target, and why the icon takes no colour of its own.
				 */
				?>
				<!-- wp:group {"metadata":{"name":"Call Us"},"className":"sd-expert__call-box","style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:custom|spacing|button|padding-vertical","right":"var:preset|spacing|20","bottom":"var:custom|spacing|button|padding-vertical","left":"var:preset|spacing|20"}},"border":{"radius":"var:preset|border-radius|0","width":"var:custom|border-width|200","style":"solid","color":"var:preset|color|base"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
				<div class="wp-block-group sd-expert__call-box" style="border-color:var(--wp--preset--color--base);border-style:solid;border-width:var(--wp--custom--border-width--200);border-radius:var(--wp--preset--border-radius--0);padding-top:var(--wp--custom--spacing--button--padding-vertical);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--custom--spacing--button--padding-vertical);padding-left:var(--wp--preset--spacing--20)"><!-- wp:outermost/icon-block {"iconName":"","width":"20px"} -->
				<div class="wp-block-outermost-icon-block"><div class="icon-container" style="width:20px"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 256 256"><path d="M222.37,158.46l-47.11-21.11-.13-.06a16,16,0,0,0-15.17,1.4,8.12,8.12,0,0,0-.75.56L134.87,160c-15.42-7.49-31.34-23.29-38.83-38.51l20.78-24.71c.2-.25.39-.5.57-.77a16,16,0,0,0,1.32-15.06l0-.12L97.54,33.64a16,16,0,0,0-16.62-9.52A56.26,56.26,0,0,0,32,80c0,79.4,64.6,144,144,144a56.26,56.26,0,0,0,55.88-48.92A16,16,0,0,0,222.37,158.46ZM176,208A128.14,128.14,0,0,1,48,80,40.2,40.2,0,0,1,82.87,40a.61.61,0,0,0,0,.12l21,47L83.2,111.86a6.13,6.13,0,0,0-.57.77,16,16,0,0,0-1,15.7c9.06,18.53,27.73,37.06,46.46,46.11a16,16,0,0,0,15.75-1.14,8.44,8.44,0,0,0,.74-.56L168.89,152l47,21.05h0s.08,0,.11,0A40.21,40.21,0,0,1,176,208Z"></path></svg></div></div>
				<!-- /wp:outermost/icon-block -->

				<!-- wp:navigation {"overlayMenu":"never","ariaLabel":"<?php esc_attr_e( 'Office numbers', 'sd-theme-2026' ); ?>","className":"is-style-call-us-navigation sd-expert__call","style":{"typography":{"fontWeight":"var:custom|font-weight|semi-bold"},"spacing":{"blockGap":"0"}},"fontSize":"300"} -->
				<!-- wp:ollie/mega-menu {"label":"<?php esc_attr_e( 'Call Us', 'sd-theme-2026' ); ?>","menuSlug":"dropdown-call-us","showOnHover":true,"justifyMenu":"left","width":"custom","customWidth":320,"topSpacing":8,"metadata":{"name":"Call Us"}} /-->
				<!-- /wp:navigation --></div>
				<!-- /wp:group -->

				<?php /* Placeholder for the Enquiry module's modal — see the note above. */ ?>
				<!-- wp:buttons {"className":"sd-expert__email","layout":{"type":"flex","flexWrap":"nowrap"}} -->
				<div class="wp-block-buttons sd-expert__email"><!-- wp:button {"className":"is-style-accent-cta"} -->
				<div class="wp-block-button is-style-accent-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Send an Email', 'sd-theme-2026' ); ?></a></div>
				<!-- /wp:button --></div>
				<!-- /wp:buttons -->

			</div>
			<!-- /wp:group -->

		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

	<?php
	/*
	 * The Trustpilot score, live's `.trust-pilot-box` — *beneath* the card, on
	 * the page's own ground, which is where live puts it and where it belongs:
	 * the badge is the company's, not the consultant's, so it should not read as
	 * part of their card. It sits inside `sd/safari-expert` rather than beside
	 * it so that a page with no resolvable expert shows neither, as live does.
	 *
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

<!-- /wp:sd/safari-expert -->
