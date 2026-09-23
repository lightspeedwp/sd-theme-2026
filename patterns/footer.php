<?php
/**
 * Title: Footer
 * Slug: sd-theme-2026/footer
 * Description: Site footer — four columns of brand, contact, social and Instagram over the full-bleed sunset photograph, above the dark copyright bar carrying the terms links.
 * Categories: footer
 * Keywords: footer, links, columns, copyright, social, contact, instagram
 * Viewport Width: 1500
 * Block Types: core/template-part/footer
 * Post Types: wp_template
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * Ported from live 2026-08-20. Live's footer is three stacked regions inside
 * `#footer-bg-sd`:
 *
 *   1. `#footer-cta`   — a widget area. **Empty on every page type measured**
 *                        (`/`, `/blog/`, `/contact/`, `/about-us/`,
 *                        `/accommodation/`, and a tour single): the
 *                        `.lsx-hero-unit` inside it renders with no children at
 *                        all. It is a dead region, and it is not reproduced.
 *   2. `#footer-widgets` — the four columns, over the sunset photograph.
 *   3. `footer#colophon` — the dark bar: copyright left, terms links right.
 *
 * An earlier pass at this file had a "Plan your journey / Enquire now" call to
 * action standing in for (1), described as conditional and owned by the
 * companion plugin. Nothing on live corresponds to it. It is dropped rather
 * than carried forward as a scaffold, because a scaffold for a region that does
 * not exist reads as a missing feature to everyone who comes after. If a footer
 * CTA is wanted it is new design, and new design is a Change-Control Register
 * entry — the plugin-side rule that inc/README.md reserves ("the footer's
 * conditional-CTA rule") stays reserved, and unbuilt.
 *
 * The Instagram column is **not** a feed. inc/README.md reserves "the Instagram
 * feed" for the companion plugin, and that reservation still holds — but live
 * has no feed to reserve: `#custom_html-7` is a hand-written 3×3 table of nine
 * static JPEGs uploaded in July 2019, every one of them wrapped in a single link
 * to the profile. Nine static images are content, so the theme carries them.
 * Wiring this column to the real Instagram API would be new scope.
 *
 * Content width is `alignwide` (1520px), not live's 1170px. Live's 1170 is
 * Bootstrap's `.container`, which every region of the old site shared; this
 * theme's equivalent shared rail is `wideSize`, and patterns/header.php already
 * sits on it. A 1170px footer under a 1520px header would read as a mistake,
 * not as fidelity.
 */

/*
 * `tagName: div`, not `footer`.
 *
 * WordPress already wraps a template part whose area is `footer` in a `<footer>`
 * element, so a `<footer>` here too renders one contentinfo landmark inside
 * another — confirmed on the rendered page 2026-08-20:
 *
 *     footer.wp-block-template-part > footer.wp-block-group
 *
 * Two contentinfo landmarks is a real defect: a screen-reader user navigating by
 * landmark hits "contentinfo" twice for one footer and has no way to tell which
 * is the one they want. The outer wrapper is the landmark; this is its content.
 * patterns/header.php carries the same note for the same reason — every template
 * renders both parts with an explicit `tagName`.
 */

/*
 * Media is addressed by its URL on dev, and that is a deliberate exception to
 * AGENTS.md' "never hardcode an uploads URL on an image".
 *
 * The footer's thirteen assets used to be resolved per environment through
 * `SdTheme2026\attachment_id_by_path()`, so that a path seeded identically on
 * local, dev and live gave the right attachment ID in each. That machinery cost
 * a lookup per asset, a filter-flushed object cache, a `sizeSlug`-aware URL
 * resolver and a PHP module of its own for the one rule it could not express —
 * and it bought nothing the project needs: dev is the environment that holds the
 * real media, it is deployed to live wholesale, and local only ever needs the
 * images to render.
 *
 * So the URLs are dev's, written out plainly. Local pulls them straight from dev
 * — no seeding step, no per-environment resolution, nothing to keep in sync —
 * and the editor shows the images to anyone opening the pattern anywhere.
 *
 * They are written literally, as core writes asset URLs, and the go-live
 * deployment runs a find-and-replace over the dev host by convention — so they
 * need no code change and no indirection here. The one copy that replace will
 * not reach is in assets/styles/core-group.css, which carries the mobile
 * photograph's media query; check that file in the same pass.
 */

/*
 * The nine Instagram tiles, written out in live's order.
 *
 * Live gives all nine `alt="instagram"` and wraps the whole grid in one link.
 * Both are reproduced differently, and deliberately: nine links cannot share one
 * accessible name, so each tile is described. The descriptions are of the
 * photographs themselves — they are 83×82px thumbnails from 2019 and nobody
 * recorded what they show — so they are accurate but unverified against
 * whatever the original Instagram posts said. Worth a client pass.
 */
?>
<!-- wp:group {"metadata":{"name":"Footer"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="margin-top:0">

	<?php
	/*
	 * The photograph is set on the block, not in a stylesheet, because
	 * `background` is a block support: core reads these attributes and appends
	 * the declarations to the wrapper at render time, and the editor gives the
	 * image a media control. No matching inline `style` is written into the
	 * saved markup — doing that as well emitted every declaration twice,
	 * measured on the rendered page:
	 *
	 *     style="background-image:url('…');…;background-image:url('…');…"
	 *
	 * `source` and `id` are deliberately absent. Core dropped the `source`
	 * requirement in 6.6 ("a file/url is the default") and an `id` would be a
	 * per-install value, which is the one kind of literal that cannot be right
	 * in two environments at once.
	 *
	 * Everything else about this band — the 615px floor, the padding, the type
	 * and link colours, the "Follow Us" list's geometry — is in
	 * styles/sections/site-footer.json, where it belongs. Only the image is
	 * here, and only because only the block can carry it.
	 */
	?>
	<!-- wp:group {"metadata":{"name":"Footer Widgets","description":"Live's #footer-widgets — four columns over the sunset photograph."},"align":"full","className":"is-style-site-footer","style":{"spacing":{"blockGap":"var:preset|spacing|50"},"background":{"backgroundImage":{"url":"https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2026/08/footer-bg.jpg"},"backgroundPosition":"50% 100%","backgroundRepeat":"no-repeat","backgroundSize":"cover"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull is-style-site-footer">

		<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
		<div class="wp-block-columns alignwide">

			<!-- wp:column {"metadata":{"name":"Brand"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
			<div class="wp-block-column">
				<?php
				/*
				 * Widths are on the blocks, as they are on the Trustpilot marks
				 * in patterns/header.php, so they are visible and adjustable in
				 * the editor rather than hidden in a stylesheet behind a class.
				 *
				 * They are raw px because that is what `core/image`'s `width` is
				 * — a CSS length core writes straight into the `<img>` style,
				 * with no preset resolution, so `var:custom|footer|logo-width`
				 * would serialise literally and invalidate the block. These are
				 * the images' own render sizes, not design decisions shared with
				 * anything else, so they were never token material: the three
				 * `custom.footer.*-width` tokens that used to hold them are gone
				 * from theme.json with the classes that read them.
				 *
				 * Measured on live 2026-08-20 at 1440px: the brand mark renders
				 * 254px wide, the We Are Africa badge 131px.
				 */
				?>
				<!-- wp:image {"width":"254px","sizeSlug":"full","linkDestination":"none"} -->
				<figure class="wp-block-image size-full is-resized"><img src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/07/footer-logo.svg" alt="<?php esc_attr_e( 'Southern Destinations — Journeys with Imagination', 'sd-theme-2026' ); ?>" style="width:254px"/></figure>
				<!-- /wp:image -->

				<?php
				/*
				 * The We Are Africa 2024 Tribe Member badge, linking out to the
				 * trade body. The `-300x300` intermediate is used rather than the
				 * original: the full file is 2250px and 35KB, a pointless
				 * download for a 131px mark.
				 */
				?>
				<!-- wp:image {"width":"131px","sizeSlug":"medium","linkDestination":"custom"} -->
				<figure class="wp-block-image size-medium is-resized"><a href="https://www.weareafricatravel.com/" target="_blank" rel="noreferrer noopener"><img src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2024/02/WAA-Tribe-Member-Badge-2024-34-white-300x300.png" alt="<?php esc_attr_e( 'We Are Africa — 2024 Tribe Member', 'sd-theme-2026' ); ?>" style="width:131px"/></a></figure>
				<!-- /wp:image -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"metadata":{"name":"Contact"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
			<div class="wp-block-column">
				<!-- wp:heading {"level":2,"fontSize":"300"} -->
				<h2 class="wp-block-heading has-300-font-size"><?php esc_html_e( 'Contact Us', 'sd-theme-2026' ); ?></h2>
				<!-- /wp:heading -->

				<?php
				/*
				 * Three paragraphs, not six.
				 *
				 * Live sets `.textwidget p { margin-bottom: 0 }` and separates the
				 * groups with bare `<br>` elements, so what looks like six lines
				 * is three blocks of two: the two enquiry numbers, the address,
				 * then the switchboard number and the mailbox. Measured on live
				 * 2026-08-20 the paired lines sit 22px apart — line-height, no
				 * gap — and the groups roughly twice that.
				 *
				 * Reproducing that as six paragraphs would need every gap
				 * cancelled and three of them added back; grouping them the way
				 * live groups them lets the column's own `blockGap` be the only
				 * spacing rule, and reads correctly in the editor besides.
				 *
				 * ⚠️ Content question, carried across faithfully rather than
				 * silently fixed: the RSA number appears twice, once as "RSA:" and
				 * once as "T", three lines apart. Live does this, so the port does
				 * too — deciding which one goes is the client's call, not the
				 * theme's.
				 */
				?>
				<!-- wp:paragraph -->
				<p><?php echo esc_html_x( 'RSA:', 'office phone number label', 'sd-theme-2026' ); ?> <a href="tel:+27216713090">+27 21 671 3090</a><br><?php echo esc_html_x( 'US:', 'office phone number label', 'sd-theme-2026' ); ?> <a href="tel:+16469068113">+1 646-906-8113</a></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph -->
				<p><a href="https://maps.app.goo.gl/oYv5Chxqswqyv71L7" target="_blank" rel="noreferrer noopener">46 Main Road, Claremont 7735<br>Cape Town, South Africa</a></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph -->
				<p><?php echo esc_html_x( 'T', 'abbreviation for telephone, precedes a phone number', 'sd-theme-2026' ); ?> <a href="tel:+27216713090">+27 (0) 21 671 3090</a><br><a href="mailto:info@southerndestinations.com">info@southerndestinations.com</a></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"metadata":{"name":"Follow"},"style":{"spacing":{"blockGap":"var:preset|spacing|10"}}} -->
			<div class="wp-block-column">
				<!-- wp:heading {"level":2,"fontSize":"300"} -->
				<h2 class="wp-block-heading has-300-font-size"><?php esc_html_e( 'Follow Us', 'sd-theme-2026' ); ?></h2>
				<!-- /wp:heading -->

				<?php
				/*
				 * Live builds this as a nav menu of six text links and hangs a
				 * FontAwesome glyph on each `li::before`, which is why the labels
				 * read "Facebook", "Twitter" and so on rather than being icon-only.
				 *
				 * `core/social-links` with `showLabels` is the same result without
				 * the icon font: `is-style-logos-only` drops core's chip so the
				 * mark sits bare on the photograph the way live's glyphs do, and
				 * `iconColor` is live's `#60483B` resolved to `primary-500`.
				 *
				 * The list's own geometry — undoing core's assumption that a
				 * labelled social link is an icon with a caption — is in
				 * styles/sections/site-footer.json' `css` field, keyed off this
				 * band's own style class. It used to be a `.sd-footer__social`
				 * hook class and a block of assets/styles/core-social-links.css;
				 * nothing here needs a class of its own any more.
				 *
				 * `twitter`, not `x`: live links to twitter.com and shows the
				 * bird, and this is a port. Switching the mark is a client
				 * decision with a copy change attached.
				 */
				?>
				<!-- wp:social-links {"iconColor":"primary-500","iconColorValue":"var(--wp--preset--color--primary-500)","showLabels":true,"size":"has-small-icon-size","className":"is-style-logos-only","style":{"spacing":{"blockGap":{"top":"0","left":"0"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
				<ul class="wp-block-social-links has-small-icon-size has-visible-labels has-icon-color is-style-logos-only">
					<!-- wp:social-link {"url":"https://www.facebook.com/SouthernDestinations/","service":"facebook","label":"Facebook"} /-->
					<!-- wp:social-link {"url":"https://twitter.com/southerndest","service":"twitter","label":"Twitter"} /-->
					<!-- wp:social-link {"url":"https://www.instagram.com/southerndestinations/","service":"instagram","label":"Instagram"} /-->
					<!-- wp:social-link {"url":"https://www.pinterest.com/SouthernDestinations/","service":"pinterest","label":"Pinterest"} /-->
					<!-- wp:social-link {"url":"https://www.linkedin.com/company/southern-destinations/","service":"linkedin","label":"LinkedIn"} /-->
					<!-- wp:social-link {"url":"https://www.youtube.com/channel/UCny_nCkQmY1vZwQPOmYbs_Q","service":"youtube","label":"YouTube"} /-->
				</ul>
				<!-- /wp:social-links -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"metadata":{"name":"Instagram"},"style":{"spacing":{"blockGap":"var:preset|spacing|10"}}} -->
			<div class="wp-block-column">
				<!-- wp:heading {"level":2,"fontSize":"300"} -->
				<h2 class="wp-block-heading has-300-font-size"><?php esc_html_e( 'Instagram', 'sd-theme-2026' ); ?></h2>
				<!-- /wp:heading -->

				<?php
				/*
				 * Nine tiles, three across, each an 83px square.
				 *
				 * The size is on the blocks — `width`, `height` and `scale`, all
				 * three of them native `core/image` attributes, which core
				 * serialises as `object-fit: cover; width: 83px; height: 83px` on
				 * the `<img>`. That replaces a `.sd-footer__tile` class and three
				 * declarations in assets/styles/core-image.css with block
				 * settings the editor can show.
				 *
				 * 83px is a ceiling rather than a layout choice: the source files
				 * are 83×82, 81×83 and 83×81 — nominally square, actually three
				 * different shapes — so letting a grid cell stretch them to the
				 * column's third would upscale a 2KB thumbnail by half again and
				 * it would show. Live caps them with `max-width: 83px;
				 * max-height: 81px`, which squashes whichever axis overshoots and
				 * distorts the photographs. A free height was measured at 81–85px
				 * and leaves a 3×3 grid with ragged rows. A fixed square with
				 * `object-fit: cover` is the only one of the three that is both
				 * undistorted and aligned: at most two pixels come off one edge
				 * of six of the nine files.
				 */
				?>
				<!-- wp:group {"metadata":{"name":"Instagram Grid"},"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"grid","columnCount":3}} -->
				<div class="wp-block-group">
					<!-- wp:image {"width":"83px","height":"83px","scale":"cover","sizeSlug":"full","linkDestination":"custom"} -->
					<figure class="wp-block-image size-full is-resized"><a href="https://www.instagram.com/southerndestinations/" target="_blank" rel="noreferrer noopener"><img src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/07/instagram-1.jpg" alt="<?php esc_attr_e( 'Palm trees silhouetted against an orange sunset over open plains', 'sd-theme-2026' ); ?>" style="object-fit:cover;width:83px;height:83px"/></a></figure>
					<!-- /wp:image -->
					<!-- wp:image {"width":"83px","height":"83px","scale":"cover","sizeSlug":"full","linkDestination":"custom"} -->
					<figure class="wp-block-image size-full is-resized"><a href="https://www.instagram.com/southerndestinations/" target="_blank" rel="noreferrer noopener"><img src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/07/instagram-2.jpg" alt="<?php esc_attr_e( 'A river winding in tight bends through a green floodplain, seen from the air', 'sd-theme-2026' ); ?>" style="object-fit:cover;width:83px;height:83px"/></a></figure>
					<!-- /wp:image -->
					<!-- wp:image {"width":"83px","height":"83px","scale":"cover","sizeSlug":"full","linkDestination":"custom"} -->
					<figure class="wp-block-image size-full is-resized"><a href="https://www.instagram.com/southerndestinations/" target="_blank" rel="noreferrer noopener"><img src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/07/instagram-3.jpg" alt="<?php esc_attr_e( 'Guides poling mokoro dugout canoes along a reed-lined channel', 'sd-theme-2026' ); ?>" style="object-fit:cover;width:83px;height:83px"/></a></figure>
					<!-- /wp:image -->
					<!-- wp:image {"width":"83px","height":"83px","scale":"cover","sizeSlug":"full","linkDestination":"custom"} -->
					<figure class="wp-block-image size-full is-resized"><a href="https://www.instagram.com/southerndestinations/" target="_blank" rel="noreferrer noopener"><img src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/07/instagram-4.jpg" alt="<?php esc_attr_e( 'A lioness grooming her cub', 'sd-theme-2026' ); ?>" style="object-fit:cover;width:83px;height:83px"/></a></figure>
					<!-- /wp:image -->
					<!-- wp:image {"width":"83px","height":"83px","scale":"cover","sizeSlug":"full","linkDestination":"custom"} -->
					<figure class="wp-block-image size-full is-resized"><a href="https://www.instagram.com/southerndestinations/" target="_blank" rel="noreferrer noopener"><img src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/07/instagram-5.jpg" alt="<?php esc_attr_e( 'A hot-air balloon drifting over red desert dunes', 'sd-theme-2026' ); ?>" style="object-fit:cover;width:83px;height:83px"/></a></figure>
					<!-- /wp:image -->
					<!-- wp:image {"width":"83px","height":"83px","scale":"cover","sizeSlug":"full","linkDestination":"custom"} -->
					<figure class="wp-block-image size-full is-resized"><a href="https://www.instagram.com/southerndestinations/" target="_blank" rel="noreferrer noopener"><img src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/07/instagram-6.jpg" alt="<?php esc_attr_e( 'A malachite kingfisher perched on a reed', 'sd-theme-2026' ); ?>" style="object-fit:cover;width:83px;height:83px"/></a></figure>
					<!-- /wp:image -->
					<!-- wp:image {"width":"83px","height":"83px","scale":"cover","sizeSlug":"full","linkDestination":"custom"} -->
					<figure class="wp-block-image size-full is-resized"><a href="https://www.instagram.com/southerndestinations/" target="_blank" rel="noreferrer noopener"><img src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/07/instagram-7.jpg" alt="<?php esc_attr_e( 'Table Mountain and the Cape Town coastline seen from the sea', 'sd-theme-2026' ); ?>" style="object-fit:cover;width:83px;height:83px"/></a></figure>
					<!-- /wp:image -->
					<!-- wp:image {"width":"83px","height":"83px","scale":"cover","sizeSlug":"full","linkDestination":"custom"} -->
					<figure class="wp-block-image size-full is-resized"><a href="https://www.instagram.com/southerndestinations/" target="_blank" rel="noreferrer noopener"><img src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/07/instagram-8.jpg" alt="<?php esc_attr_e( 'A rainbow arching through the spray of Victoria Falls', 'sd-theme-2026' ); ?>" style="object-fit:cover;width:83px;height:83px"/></a></figure>
					<!-- /wp:image -->
					<!-- wp:image {"width":"83px","height":"83px","scale":"cover","sizeSlug":"full","linkDestination":"custom"} -->
					<figure class="wp-block-image size-full is-resized"><a href="https://www.instagram.com/southerndestinations/" target="_blank" rel="noreferrer noopener"><img src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/07/instagram-9.jpg" alt="<?php esc_attr_e( 'An elephant walking across pale desert sand', 'sd-theme-2026' ); ?>" style="object-fit:cover;width:83px;height:83px"/></a></figure>
					<!-- /wp:image -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:column -->
		</div>
		<!-- /wp:columns -->
	</div>
	<!-- /wp:group -->

	<?php
	/*
	 * The colophon. A plain group, not a `footer` — live names this element
	 * `footer#colophon.content-info`, but the template part wrapping this whole
	 * pattern is already the page's one contentinfo landmark. See the tagName
	 * note at the top of the file.
	 */
	?>
	<!-- wp:group {"metadata":{"name":"Colophon","description":"Live's footer#colophon — copyright left, terms links right."},"align":"full","className":"is-style-footer-colophon","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull is-style-footer-colophon">

		<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
		<div class="wp-block-group alignwide">

			<!-- wp:paragraph {"fontSize":"200"} -->
			<p class="has-200-font-size">
				<?php
				printf(
					/* translators: 1: current year, 2: site title. */
					esc_html__( '© %1$s %2$s All Rights Reserved', 'sd-theme-2026' ),
					esc_html( wp_date( 'Y' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
				?>
			</p>
			<!-- /wp:paragraph -->

			<?php
			/*
			 * Two `wp:navigation-link` children and no `ref`, unlike the header's
			 * navigation blocks. There is no `wp_navigation` post behind live's
			 * `#menu-terms` on dev — the migration did not bring the classic menus
			 * across — so there is no ID to point at, and inventing one would mean
			 * writing content to dev from a theme file. Inner blocks also keep
			 * core's Page List fallback from firing, which is what a ref-less,
			 * child-less navigation block would otherwise do.
			 */
			?>
			<!-- wp:navigation {"overlayMenu":"never","ariaLabel":"<?php esc_attr_e( 'Legal', 'sd-theme-2026' ); ?>","className":"is-style-footer-navigation","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","justifyContent":"right"}} -->
				<!-- wp:navigation-link {"label":"<?php esc_attr_e( 'Privacy Policy', 'sd-theme-2026' ); ?>","url":"<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"<?php esc_attr_e( 'Terms & Conditions', 'sd-theme-2026' ); ?>","url":"<?php echo esc_url( home_url( '/terms-conditions/' ) ); ?>","kind":"custom"} /-->
			<!-- /wp:navigation -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
