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
 * This file is required by three templates — patterns/template-archive-destination.php,
 * patterns/template-archive-tour.php and patterns/template-single-tour.php. It is
 * one component in three places by design; a change here lands in all three.
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
 *     border-radius: 1px                      border-radius 0
 *     display: flex; align-items: center      a two-column grid, centred
 *     thumb 116 × 116, object-fit: cover      126px, square, radius 500
 *     .travel-expert-title  white, uppercase  base, uppercase, semi-bold
 *     .lsx-to-contact-name  white             base, title case, font-size 400
 *     .lsx-to-meta-data     1px #fff, radius 2px, white text
 *                                             a bordered box, full-width half
 *     .lsx-to-enquire-form  the amber CTA     is-style-accent-cta, full-width half
 *     .trust-pilot-box beneath the card       beneath the card, centred
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
 * ## The card is one grid, and the arrangement is a container query
 *
 * Three siblings — the portrait, "Expert Identity" (the eyebrow and the name)
 * and "Expert Actions" — placed into a two-column grid by name. Two
 * arrangements:
 *
 *     narrow   "portrait name"      the name beside the portrait,
 *              "actions actions"    the actions across the full card
 *
 *     wide     "portrait name"      the portrait beside a stacked
 *              "portrait actions"   name-over-actions column
 *
 * The tracks and the areas are in assets/styles/core-group.css, because
 * `grid-template-areas` has no block attribute and a `@container` block does
 * not survive a block style's `css` field — the same sanitiser that unwraps
 * `@media` there. The block carries `layout: {type: grid, columnCount: 2}` so
 * the editor shows two columns, and `blockGap` as an object so the row and
 * column gaps (spacing 20 and 30) stay authored in markup. → AGENTS.md
 *
 * ### Why a container query, and what it replaced
 *
 * The identity block used to be authored **twice** — once beside the portrait
 * and once above the actions — with Block Visibility's screen-size control
 * choosing between the copies at its `large` breakpoint. Zared restructured it
 * that way in the Site Editor on 2026-08-28; it was collapsed to one copy on
 * 2026-09-04 because the viewport switch could not be made correct.
 *
 * It produced a band, roughly 990–1250px on the tour single, where the wide
 * arrangement was switched on inside a card too narrow to hold it. The old
 * `.sd-expert__detail` was `flex: 1 1 auto`, so flexbox sized it from its
 * ~470px max-content, could not fit that beside the 126px portrait, and
 * wrapped it to a second flex line — leaving the portrait alone on row one
 * with the eyebrow, the name and both actions stacked underneath it. Measured
 * on local: **313px tall at the switch against 175px at 1440px.**
 *
 * The band was not the same width in two environments, which is what made it
 * hard to pin down. Block Visibility's breakpoints are a global plugin
 * setting, and `large` is **1200px on local and 992px on dev** — so the same
 * fault appeared at 1200–1250 in one place and 992–1250 in the other. The
 * "large ≥ 992px" this comment used to assert was a dev measurement stated as
 * a fact about the plugin.
 *
 * And no viewport breakpoint could have been right anyway. This pattern is
 * required by three templates and the column it sits in is a different width
 * in each — patterns/template-single-tour.php gives it half of `alignwide`,
 * patterns/template-archive-destination.php a re-proportioned column. What
 * decides the arrangement is how wide *the card* is, so that is what is asked.
 *
 * A container query is also more truthful in the editor than the viewport
 * queries were: the canvas is narrower than the viewport, so a `min-width:
 * 992px` media query already reported the wrong arrangement there.
 *
 * ⚠️ **Do not reach for Block Visibility to re-solve this.** Its screen-size
 * control is viewport media queries and cannot express "when the container is
 * narrow" — this is the documented exception to the theme's
 * Block-Visibility-over-CSS-hiding rule, and nothing is hidden now in any
 * case. The header's desktop/mobile split still uses the plugin correctly.
 *
 * ### The eyebrow keeps both sizes
 *
 * 400 beside the portrait, 300 above the name — Zared's sizes, carried over
 * from the two copies. The 400 is the block attribute; the step down to 300
 * lives inside the `@container` block, marked `!important` because core marks
 * every preset font-size class important. The column gap is likewise 30 in the
 * narrow arrangement and 20 in the wide one, as the two nested groups gave it.
 *
 * ## Heading levels
 *
 * The eyebrow is an `h2` and the name is the `core/post-title`'s own `h2`.
 * Live uses `<h5 class="travel-expert-title">` above `<h3 class="lsx-to-contact-name">`
 * — an h5 above an h3, a skipped level in the wrong direction — so the levels
 * are the theme's, not live's. Adjust the eyebrow's `level` if you drop this
 * into a page whose outline needs it deeper; on the three templates that
 * require it today the panel sits under the page `h1` and two sibling `h2`s are
 * correct.
 *
 * The name carries `textTransform: none` inline because `styles.elements.h2` in
 * theme.json sets uppercase, and an element style outranks a block attribute on
 * the very element it targets — `core/post-title`'s wrapper *is* the `<h2>`.
 * Live's name is title case ("Liesl Matthews"), so it is title case here. The
 * font-size is left as the `has-400-font-size` class, which measurably wins
 * against `elements.h2`'s 500 on this theme's stylesheet order.
 *
 * ⚠️ **The name is no longer a link.** It was `isLink: true` until 2026-08-28.
 * The consultant's single is still reachable from the panel — the portrait
 * keeps its link — so this is one link to that page rather than two adjacent
 * ones. Restore `"isLink":true` on the `core/post-title` below if it should
 * come back.
 *
 * ⚠️ **The portrait has no ring.** It carried a 2px `base` ring until
 * 2026-08-28 so it separated from the ground; the import keeps the corner
 * (radius 500 — a circle) and drops the ring, which is what the restructure
 * rendered on dev. The dead `border-width` the editor left behind with no
 * `border-style` beside it is *not* carried, because it draws nothing. To put
 * the ring back, add `"style":{"border":{"width":"var:custom|border-width|200","style":"solid","color":"var:preset|color|base"}}`
 * to the featured image.
 *
 * `selfStretch: fixedNoShrink` is gone with it: the portrait is a `width`
 * plus an `aspectRatio` inside a nowrap row now, and core emits
 * `object-fit: cover` for the ratio on its own — verified on the rendered dev
 * page, 2026-08-28. Image crops are `aspectRatio`, never CSS. → AGENTS.md
 *
 * ## ⚠️ Four blocks here are dynamic, so their custom tokens use `var(--wp--custom--…)`
 *
 * `core/post-title`, `core/navigation` and `ollie/mega-menu` have no saved
 * markup — the server-side style engine builds their inline styles, and it does
 * not expand the `var:custom|…` shorthand for `fontWeight`, `letterSpacing`,
 * `fontStyle`, `textTransform` or `lineHeight`. Measured on local 2026-08-28
 * with `wp_style_engine_get_styles()`:
 *
 *     border.radius   var:preset|border-radius|500        → resolves
 *     border.width    var:custom|border-width|100         → !! DROPPED !!
 *     fontWeight      var:custom|font-weight|medium       → !! DROPPED !!
 *     letterSpacing   var:custom|letter-spacing|narrow    → !! DROPPED !!
 *     fontWeight      var(--wp--custom--font-weight--medium) → resolves
 *
 * No notice and no fallback — the declaration is simply absent and the block
 * inherits. The navigation's `fontWeight` was in the shorthand form and had
 * therefore never rendered; the rendered `<nav>` on dev carries no inline
 * weight at all. Both forms are still token references, so the tokens-over-
 * hardcoding rule holds either way — but on these four blocks it must be the
 * parenthesised one. The static blocks around them (`core/group`,
 * `core/heading`, `core/buttons`) keep the shorthand, because their inline
 * styles are authored in the markup below and the shorthand is only read by the
 * editor. → AGENTS.md, "Authored files"
 *
 * ## The actions are a two-up row
 *
 * Both actions carry `layout.selfStretch: fill` and the button carries
 * `dimensions.width` at the `100` preset, so Call Us and Send an Email each
 * take half the `actions` grid area and the button fills its half. `--wp--preset--dimension--100`
 * is core's own 100% width preset, not a theme token — it is defined in
 * wp-includes/theme.json and rendered on dev, so it resolves without the theme
 * declaring `settings.dimensions`.
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
 * ### The label is uppercased in CSS, not in the string
 *
 * The trigger takes the heading face at semi-bold and reads in caps. The caps
 * are `textTransform: uppercase` on the `ollie/mega-menu` block rather than an
 * uppercase literal, because a literal would hand translators a shouted string
 * and ship casing that no locale can override. It reaches the `<button>`:
 * Ollie's own stylesheet sets `text-transform: inherit` on
 * `.wp-block-ollie-mega-menu__toggle` alongside `font-family: inherit` and
 * `font-weight: inherit`, so all three of the block's typography attributes
 * land on the toggle. Verified against the rendered stylesheet on dev,
 * 2026-08-28 — the block declares `__experimentalTextTransform`,
 * `__experimentalFontFamily` and `__experimentalFontWeight` support, and the
 * classes appear on the `<li class="wp-block-ollie-mega-menu">`.
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
 * ⚠️ **The editor converts this to a saved menu and it must be converted back
 * on the way in.** The `archive-destination` override imported on 2026-08-28
 * carried `"ref":65916` — a `wp_navigation` post titled "Menu" holding exactly
 * the `ollie/mega-menu` below — because saving a template in the Site Editor
 * offers to turn an uncontrolled navigation into a managed one and the offer
 * was taken. The ref is a per-install id that no deploy step can fix
 * (AGENTS.md is explicit), so it is dropped and the inner block restored every
 * time this round-trips. Theme files are the source of truth here.
 * → wp-db-override-reconciliation
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
 * ⚠️ **This is the one *filled* phone glyph in the theme.** Phosphor ships the
 * mark in several weights; this panel takes the fill, changed 2026-08-28, and
 * patterns/header.php, homepage-dream-trip.php,
 * homepage-lets-make-it-happen.php and cta-not-sure-where-to-go.php all keep
 * the regular outline. That divergence is deliberate — the glyph sits on a
 * saturated ground here and the outline reads thin against it — so do **not**
 * "fix" the other four to match, and do not copy this path into them. Written
 * out rather than echoed from a variable, as core's patterns do.
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
 * The **enquiry form**. The "Send an Email" button opens the shared enquiry
 * modal — `href="#to-modal-modal-enquiry"`, resolving to
 * parts/modal-enquiry.html, which is where Gravity Form 1 lives. Do not add a
 * Gravity Forms shortcode here: there is one enquiry form on the site and it is
 * in one template part. patterns/cta-not-sure-where-to-go.php is the canonical
 * account of that action, including why it is a `core/button` rather than
 * `lsx-tour-operator/modal-button` and why the form is GF 1 and not the GF 13
 * an earlier revision of this comment named.
 */
?>
<!-- wp:sd/safari-expert {"tagName":"section","className":"sd-expert","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->

	<?php
	/*
	 * The card. `elements.link` is set on this group rather than left to the
	 * global one, which is `contrast` on rest and **brand-600 on hover** — the
	 * card's own ground, so a link would vanish under the pointer. Both states
	 * are `base` here. It still matters with the name unlinked: the Call Us
	 * numbers and the email CTA are inside this group.
	 */
	?>
	<!-- wp:group {"metadata":{"name":"Expert Card"},"className":"sd-expert__panel","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|20","left":"var:preset|spacing|30"},"padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20"}},"border":{"radius":"var:preset|border-radius|0"},"elements":{"link":{"color":{"text":"var:preset|color|base"},":hover":{"color":{"text":"var:preset|color|base"}}}}},"backgroundColor":"brand-600","textColor":"base","layout":{"type":"grid","columnCount":2}} -->
	<div class="wp-block-group sd-expert__panel has-link-color has-base-color has-brand-600-background-color has-text-color has-background" style="border-radius:var(--wp--preset--border-radius--0);padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)">

		<?php
		/*
		 * The portrait — grid area `portrait`. Linked to the consultant's
		 * single, as live does, and the only link to it in the panel.
		 * `aspectRatio` crops it square; core adds `object-fit: cover` for the
		 * ratio itself.
		 */
		?>
		<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"1","width":"126px","className":"sd-expert__portrait","style":{"border":{"radius":"var:preset|border-radius|500"}}} /-->

		<?php /* The eyebrow and the name — grid area `name`. */ ?>
		<!-- wp:group {"metadata":{"name":"Expert Identity"},"className":"sd-expert__identity","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group sd-expert__identity">

			<!-- wp:heading {"className":"sd-expert__eyebrow","style":{"typography":{"fontWeight":"var:custom|font-weight|semi-bold","textTransform":"uppercase"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base","fontSize":"400"} -->
			<h2 class="wp-block-heading sd-expert__eyebrow has-base-color has-text-color has-link-color has-400-font-size" style="font-weight:var(--wp--custom--font-weight--semi-bold);text-transform:uppercase"><?php esc_html_e( 'Chat to your safari expert', 'sd-theme-2026' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:post-title {"className":"sd-expert__name","style":{"typography":{"textTransform":"none","fontWeight":"var(--wp--custom--font-weight--medium)","letterSpacing":"var(--wp--custom--letter-spacing--narrow)"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base","fontSize":"400"} /-->

		</div>
		<!-- /wp:group -->

		<?php /* Call Us and Send an Email — grid area `actions`. */ ?>
		<!-- wp:group {"metadata":{"name":"Expert Actions"},"className":"sd-expert__actions","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"stretch"}} -->
		<div class="wp-block-group sd-expert__actions">

			<?php
			/*
			 * The Call Us box — the icon beside the navigation, both inside
			 * a group that carries live's `.lsx-to-meta-data` clothing as
			 * block attributes. Radius 0 and the button padding token, so
			 * the box matches the height and the corner of the accent CTA
			 * beside it rather than being a second shape, and
			 * `selfStretch: fill` so the two share the row evenly.
			 *
			 * See the long note above for why the box (and not the toggle)
			 * is the target, and why the icon takes no colour of its own.
			 */
			?>
			<!-- wp:group {"metadata":{"name":"Call Us"},"className":"sd-expert__call-box","style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"top":"var:custom|spacing|button|padding-vertical","right":"var:preset|spacing|20","bottom":"var:custom|spacing|button|padding-vertical","left":"var:preset|spacing|20"}},"border":{"radius":"var:preset|border-radius|0","width":"var:custom|border-width|100","style":"solid"},"layout":{"selfStretch":"fill"}},"borderColor":"base","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center","justifyContent":"center"}} -->
			<div class="wp-block-group sd-expert__call-box has-border-color has-base-border-color" style="border-style:solid;border-width:var(--wp--custom--border-width--100);border-radius:var(--wp--preset--border-radius--0);padding-top:var(--wp--custom--spacing--button--padding-vertical);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--custom--spacing--button--padding-vertical);padding-left:var(--wp--preset--spacing--20)"><!-- wp:outermost/icon-block {"iconName":"","width":"20px"} -->
			<div class="wp-block-outermost-icon-block"><div class="icon-container" style="width:20px"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 256 256"><path d="M231.88,175.08A56.26,56.26,0,0,1,176,224C96.6,224,32,159.4,32,80A56.26,56.26,0,0,1,80.92,24.12a16,16,0,0,1,16.62,9.52l21.12,47.15,0,.12A16,16,0,0,1,117.39,96c-.18.27-.37.52-.57.77L96,121.45c7.49,15.22,23.41,31,38.83,38.51l24.34-20.71a8.12,8.12,0,0,1,.75-.56,16,16,0,0,1,15.17-1.4l.13.06,47.11,21.11A16,16,0,0,1,231.88,175.08Z"></path></svg></div></div>
			<!-- /wp:outermost/icon-block -->

			<!-- wp:navigation {"overlayMenu":"never","ariaLabel":"<?php esc_attr_e( 'Office numbers', 'sd-theme-2026' ); ?>","className":"is-style-call-us-navigation sd-expert__call","textColor":"base","style":{"typography":{"fontWeight":"var(--wp--custom--font-weight--semi-bold)"},"spacing":{"blockGap":"0"}},"fontSize":"300"} -->
			<!-- wp:ollie/mega-menu {"label":"<?php esc_attr_e( 'Call Us', 'sd-theme-2026' ); ?>","menuSlug":"dropdown-call-us","showOnHover":true,"justifyMenu":"left","width":"custom","customWidth":320,"topSpacing":8,"fontFamily":"heading","style":{"typography":{"fontWeight":"var(--wp--custom--font-weight--semi-bold)","textTransform":"uppercase"}},"metadata":{"name":"Call Us"}} /-->
			<!-- /wp:navigation --></div>
			<!-- /wp:group -->

			<?php /* Placeholder for the Enquiry module's modal — see the note above. */ ?>
			<!-- wp:buttons {"className":"sd-expert__email","style":{"layout":{"selfStretch":"fill"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
			<div class="wp-block-buttons sd-expert__email"><!-- wp:button {"className":"is-style-accent-cta","style":{"dimensions":{"width":"var:preset|dimension|100"}}} -->
			<div class="wp-block-button is-style-accent-cta"><a class="wp-block-button__link wp-element-button" href="#to-modal-modal-enquiry"><?php esc_html_e( 'Send an Email', 'sd-theme-2026' ); ?></a></div>
			<!-- /wp:button --></div>
			<!-- /wp:buttons -->

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
	 * It is centred under the card as of 2026-08-28. That is a change to
	 * trustpilot-score.php's own layout rather than something imposed here, and
	 * this file is its only consumer — patterns/why-choose-sd.php writes its own
	 * copy of the badge and says why.
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
