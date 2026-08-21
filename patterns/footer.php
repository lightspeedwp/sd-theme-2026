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
 * Media comes out of the database, addressed by uploads-relative path.
 *
 * AGENTS.md: "Never hardcode … an uploads URL on an image." All thirteen assets
 * below are seeded at the same relative path they hold on live, which makes the
 * path the one identifier that is correct in every environment — see
 * SdTheme2026\attachment_id_by_path() for why the path and not the ID.
 *
 * Each block gets both the real attachment ID and the resolved URL, so the
 * images carry `wp-image-<id>`, srcset and the editor's media controls exactly
 * as a hand-inserted image would.
 */
$sd_logo_path  = '2019/07/footer-logo.svg';
$sd_badge_path = '2024/02/WAA-Tribe-Member-Badge-2024-34-white.png';
$sd_bg_path    = '2026/08/footer-bg.jpg';

$sd_logo_id  = SdTheme2026\attachment_id_by_path( $sd_logo_path );
$sd_logo_src = SdTheme2026\attachment_src_by_path( $sd_logo_path );

$sd_badge_id  = SdTheme2026\attachment_id_by_path( $sd_badge_path );
$sd_badge_src = SdTheme2026\attachment_src_by_path( $sd_badge_path, 'medium' );

$sd_bg_id  = SdTheme2026\attachment_id_by_path( $sd_bg_path );
$sd_bg_src = SdTheme2026\attachment_src_by_path( $sd_bg_path );

/*
 * The nine Instagram tiles, in live's order, keyed by path.
 *
 * Live gives all nine `alt="instagram"` and wraps the whole grid in one link.
 * Both are reproduced differently, and deliberately: nine links cannot share one
 * accessible name, so each tile is described. The descriptions are of the
 * photographs themselves — they are 83×82px thumbnails from 2019 and nobody
 * recorded what they show — so they are accurate but unverified against
 * whatever the original Instagram posts said. Worth a client pass.
 */
$sd_instagram = array(
	'2019/07/instagram-1.jpg' => __( 'Palm trees silhouetted against an orange sunset over open plains', 'sd-theme-2026' ),
	'2019/07/instagram-2.jpg' => __( 'A river winding in tight bends through a green floodplain, seen from the air', 'sd-theme-2026' ),
	'2019/07/instagram-3.jpg' => __( 'Guides poling mokoro dugout canoes along a reed-lined channel', 'sd-theme-2026' ),
	'2019/07/instagram-4.jpg' => __( 'A lioness grooming her cub', 'sd-theme-2026' ),
	'2019/07/instagram-5.jpg' => __( 'A hot-air balloon drifting over red desert dunes', 'sd-theme-2026' ),
	'2019/07/instagram-6.jpg' => __( 'A malachite kingfisher perched on a reed', 'sd-theme-2026' ),
	'2019/07/instagram-7.jpg' => __( 'Table Mountain and the Cape Town coastline seen from the sea', 'sd-theme-2026' ),
	'2019/07/instagram-8.jpg' => __( 'A rainbow arching through the spray of Victoria Falls', 'sd-theme-2026' ),
	'2019/07/instagram-9.jpg' => __( 'An elephant walking across pale desert sand', 'sd-theme-2026' ),
);

$sd_instagram_url = 'https://www.instagram.com/southerndestinations/';

/*
 * The photograph.
 *
 * Only the block attributes are set, and deliberately no matching inline style:
 * `background` is a **server-rendered** block support, so core appends the
 * declarations to the wrapper itself at render time. Writing them into the saved
 * markup as well emitted every one of them twice —
 *
 *     style="background-image:url('…');…;background-image:url('…');…"
 *
 * — measured on the rendered page before this was removed.
 *
 * Everything else about this band — the 615px floor, the padding, the type and
 * link colours — is in styles/sections/site-footer.json, where it belongs. Only
 * the image is here, and only because only PHP can resolve it.
 */
$sd_bg_style = array(
	'background' => array(
		'backgroundImage'    => array(
			'url'    => $sd_bg_src,
			'id'     => $sd_bg_id,
			'source' => 'file',
		),
		'backgroundPosition' => '50% 100%',
		'backgroundRepeat'   => 'no-repeat',
		'backgroundSize'     => 'cover',
	),
);

$sd_bg_attrs = $sd_bg_src ? ',"style":' . wp_json_encode( $sd_bg_style ) : '';

?>
<!-- wp:group {"metadata":{"name":"Footer"},"align":"full","style":{"spacing":{"blockGap":"0","margin":{"top":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="margin-top:0">

	<!-- wp:group {"metadata":{"name":"Footer Widgets","description":"Live's #footer-widgets — four columns over the sunset photograph."},"align":"full","className":"is-style-site-footer sd-footer__widgets"<?php echo $sd_bg_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON block attributes, built by wp_json_encode() from resolved attachment data. ?>,"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull is-style-site-footer sd-footer__widgets">

		<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
		<div class="wp-block-columns alignwide">

			<!-- wp:column {"metadata":{"name":"Brand"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
			<div class="wp-block-column">
				<?php
				/*
				 * Sizes are set in assets/styles/core-image.css, not on the
				 * blocks. `core/image`'s `width` attribute is a raw CSS length
				 * that core drops straight into the `<img>` style — it is not run
				 * through the preset resolver, so `var:custom|footer|logo-width`
				 * would serialise literally and invalidate the block. The choice
				 * is therefore a raw px literal on the block or the token in CSS,
				 * and AGENTS.md settles that: tokens over hardcoding.
				 */
				?>
				<?php if ( $sd_logo_src ) : ?>
					<!-- wp:image {"id":<?php echo (int) $sd_logo_id; ?>,"sizeSlug":"full","linkDestination":"none","className":"sd-footer__logo"} -->
					<figure class="wp-block-image size-full sd-footer__logo"><img src="<?php echo esc_url( $sd_logo_src ); ?>" alt="<?php esc_attr_e( 'Southern Destinations — Journeys with Imagination', 'sd-theme-2026' ); ?>" class="wp-image-<?php echo (int) $sd_logo_id; ?>"/></figure>
					<!-- /wp:image -->
				<?php endif; ?>

				<?php
				/*
				 * The We Are Africa 2024 Tribe Member badge, linking out to the
				 * trade body. Live renders the `-300x300` size at 131px, which is
				 * what `sizeSlug: medium` plus the `badge-width` token gives —
				 * the 2250px original is 35KB and would be a pointless download
				 * at this size.
				 */
				?>
				<?php if ( $sd_badge_src ) : ?>
					<!-- wp:image {"id":<?php echo (int) $sd_badge_id; ?>,"sizeSlug":"medium","linkDestination":"custom","className":"sd-footer__badge"} -->
					<figure class="wp-block-image size-medium sd-footer__badge"><a href="https://www.weareafricatravel.com/" target="_blank" rel="noreferrer noopener"><img src="<?php echo esc_url( $sd_badge_src ); ?>" alt="<?php esc_attr_e( 'We Are Africa — 2024 Tribe Member', 'sd-theme-2026' ); ?>" class="wp-image-<?php echo (int) $sd_badge_id; ?>"/></a></figure>
					<!-- /wp:image -->
				<?php endif; ?>
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
				<p><?php esc_html_e( 'RSA:', 'sd-theme-2026' ); ?> <a href="tel:+27216713090">+27 21 671 3090</a><br><?php esc_html_e( 'US:', 'sd-theme-2026' ); ?> <a href="tel:+16469068113">+1 646-906-8113</a></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph -->
				<p><a href="https://maps.app.goo.gl/oYv5Chxqswqyv71L7" target="_blank" rel="noreferrer noopener">46 Main Road, Claremont 7735<br>Cape Town, South Africa</a></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph -->
				<p><?php esc_html_e( 'T', 'sd-theme-2026' ); ?> <a href="tel:+27216713090">+27 (0) 21 671 3090</a><br><a href="mailto:info@southerndestinations.com">info@southerndestinations.com</a></p>
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
				 * `twitter`, not `x`: live links to twitter.com and shows the
				 * bird, and this is a port. Switching the mark is a client
				 * decision with a copy change attached.
				 */
				?>
				<!-- wp:social-links {"iconColor":"primary-500","iconColorValue":"var(--wp--preset--color--primary-500)","showLabels":true,"size":"has-small-icon-size","className":"is-style-logos-only sd-footer__social","style":{"spacing":{"blockGap":{"top":"0","left":"0"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
				<ul class="wp-block-social-links has-small-icon-size has-visible-labels has-icon-color is-style-logos-only sd-footer__social">
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
				 * Nine tiles, three across, each at its native 83px.
				 *
				 * The source files are 83×82px, so the tile width is a ceiling
				 * rather than a layout choice — letting a grid cell stretch them
				 * to the column's third would upscale a 2KB thumbnail by half
				 * again and it would show. `instagram-tile` is that ceiling.
				 */
				?>
				<!-- wp:group {"metadata":{"name":"Instagram Grid"},"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"grid","columnCount":3}} -->
				<div class="wp-block-group">
					<?php foreach ( $sd_instagram as $sd_tile_path => $sd_tile_alt ) : ?>
						<?php
						$sd_tile_id  = SdTheme2026\attachment_id_by_path( $sd_tile_path );
						$sd_tile_src = SdTheme2026\attachment_src_by_path( $sd_tile_path );

						if ( ! $sd_tile_src ) {
							continue;
						}
						?>
						<!-- wp:image {"id":<?php echo (int) $sd_tile_id; ?>,"sizeSlug":"full","linkDestination":"custom","className":"sd-footer__tile"} -->
						<figure class="wp-block-image size-full sd-footer__tile"><a href="<?php echo esc_url( $sd_instagram_url ); ?>" target="_blank" rel="noreferrer noopener"><img src="<?php echo esc_url( $sd_tile_src ); ?>" alt="<?php echo esc_attr( $sd_tile_alt ); ?>" class="wp-image-<?php echo (int) $sd_tile_id; ?>"/></a></figure>
						<!-- /wp:image -->
					<?php endforeach; ?>
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
	<!-- wp:group {"metadata":{"name":"Colophon","description":"Live's footer#colophon — copyright left, terms links right."},"align":"full","className":"is-style-footer-colophon","layout":{"type":"constrained"}} -->
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
			<!-- wp:navigation {"overlayMenu":"never","className":"is-style-footer-navigation sd-footer__terms","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","justifyContent":"right"}} -->
				<!-- wp:navigation-link {"label":"<?php echo esc_attr__( 'Privacy Policy', 'sd-theme-2026' ); ?>","url":"<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>","kind":"custom"} /-->
				<!-- wp:navigation-link {"label":"<?php echo esc_attr__( 'Terms & Conditions', 'sd-theme-2026' ); ?>","url":"<?php echo esc_url( home_url( '/terms-conditions/' ) ); ?>","kind":"custom"} /-->
			<!-- /wp:navigation -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
