<?php
/**
 * Title: Card — Team Member
 * Slug: sd-theme-2026/card-team
 * Description: The consultant tile the team landing page grids — a portrait with a warm-dark band along its foot carrying the member's name in uppercase and their role beneath it.
 * Categories: sd-theme-2026/card, sd-theme-2026/tour-operator
 * Keywords: card, tile, team, consultant, staff, guru, person, role
 * Viewport Width: 480
 * Block Types: core/post-template
 * Post Types: team
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * Ported from https://www.southerndestinations.com/team/, measured in the
 * browser on 2026-09-02. Live builds the tile from Tour Operator's archive
 * partial and then reduces it to almost nothing with CSS; what survives is a
 * photograph and a two-line caption band. The measurements that matter:
 *
 *     live                                              here
 *     ------------------------------------------------  --------------------------
 *     .lsx-to-archive-thumb            360 x 280        aspectRatio 4/3
 *     .lsx-to-archive-content          70px, bottom 0   the caption band
 *       background rgba(26, 18, 5, 0.7)                 neutral-900 at 70%
 *     .lsx-to-archive-content-title    22px, uppercase  elements.heading, size 400
 *     .lsx-to-meta-data-role           13px, white      font-size 100, base
 *     .lsx-to-meta-data-key            display: none    no `prefix` on the binding
 *     .lsx-to-meta-data-phone/-email   display: none    not rendered
 *     .lsx-to-single-link              display: none    not rendered
 *
 * `is-style-team-archive-card` (styles/sections/cards/team-archive-card.json)
 * owns the positioning context, the band's ground and the caption type. Only
 * the composition is here.
 *
 * ## Four things live renders and this does not
 *
 * The phone, the email, the socials and the "More about {name} ›" link are all
 * in live's markup and all four are `display: none` on this template — the
 * child theme hides them at
 * `.post-type-archive-lsx-to-team … .lsx-to-archive-meta-data .lsx-to-meta-data-phone`
 * and friends. They are not reproduced. The member's own page carries them, and
 * the portrait and the name both link to it.
 *
 * The `Role:` label goes with them: `.lsx-to-meta-data-key { display: none }`,
 * so the band shows the bare value ("Queen Bee", "Support Travel Guru"). That
 * is why the binding below carries no `prefix`, unlike Tour Operator's own
 * `lsx-tour-operator/team-role` group variation and unlike
 * patterns/card-tour-compact.php's duration row.
 *
 * ## The crop is 4/3, not live's 9/7
 *
 * Live's thumb is a 360 x 280 box with `overflow: hidden` over a 360 x 368
 * image — a 9/7 crop, which is not a ratio anything else on the site uses. 4/3
 * is the nearest standard ratio (1.333 against live's 1.286) and it is already
 * the crop the review archive and the previous team-archive stub carried, so
 * the two archives stay one system. Image crops are `aspectRatio`, never CSS.
 * → AGENTS.md
 *
 * ## The band sizes itself
 *
 * Live pins the band at `height: 70px` and absolutely positions the name inside
 * it, so a name that wraps to two lines is clipped. Here the band is a flow
 * group with padding and it grows instead. At the sizes above a one-line name
 * lands at roughly live's 70px; nothing on the current roster wraps.
 *
 * ## The role paragraph when a member has none
 *
 * `lsx/post-meta` returns an empty string, which leaves the authored `<p></p>`
 * in place — an empty line inside the band rather than a shorter band. All
 * fifteen published members carry a `role` value on dev (checked 2026-09-02),
 * so this is latent rather than live. Hiding it outright would need Block
 * Visibility's query rules, which is not worth a dependency for a card.
 *
 * ## Two links, as live has two
 *
 * The portrait and the name both link to the member's page, which is what live
 * does and what patterns/card-media-overlay.php already does on the sibling
 * archives. No `sdLinkTo: "post"` — a whole-tile link would be a third target
 * over the top of those two.
 */

?>
<!-- wp:group {"metadata":{"name":"Team Member Card"},"className":"is-style-team-archive-card","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-team-archive-card">
	<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3"} /-->

	<?php
	/*
	 * Flow layout, not constrained. A constrained group emits
	 * `has-global-padding`, which lands the site's root edge padding inside a
	 * ~470px card, and re-clamps its children to `contentSize`. Neither is
	 * wanted; the band has two stacked children and flow is what it needs.
	 * → AGENTS.md
	 */
	?>
	<!-- wp:group {"metadata":{"name":"Caption"},"className":"team-archive-card__caption","style":{"spacing":{"blockGap":"var:preset|spacing|5","padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|10","bottom":"var:preset|spacing|20","left":"var:preset|spacing|10"}}},"layout":{"type":"default"}} -->
	<div class="wp-block-group team-archive-card__caption" style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--10)">
		<!-- wp:post-title {"level":3,"isLink":true,"style":{"typography":{"textAlign":"center"}}} /-->

		<!-- wp:paragraph {"metadata":{"name":"Role","bindings":{"content":{"source":"lsx/post-meta","args":{"key":"role"}}}},"style":{"typography":{"textAlign":"center"}},"fontSize":"100"} -->
		<p class="has-text-align-center has-100-font-size"></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
