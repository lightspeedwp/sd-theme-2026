<?php
/**
 * Title: Footer
 * Slug: sd-theme-2026/footer
 * Description: Site footer — enquiry call to action above a four-column footer (Logo, Contact, Follow, Instagram) and the copyright line. Scaffold only: the Instagram feed and the conditional CTA display rule are supplied by the companion block plugin.
 * Categories: footer
 * Keywords: footer, links, columns, copyright, social, contact
 * Viewport Width: 1500
 * Block Types: core/template-part/footer
 * Post Types: wp_template
 * Inserter: true
 *
 * @package sd-theme-2026
 */

?>
<!-- wp:group {"metadata":{"name":"Footer"},"tagName":"footer","align":"full","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"constrained"}} -->
<footer class="wp-block-group alignfull">

	<!-- wp:group {"tagName":"section","metadata":{"name":"Footer CTA","description":"Conditional call to action. Whether this renders is decided by the companion block plugin, not the theme."},"align":"full","className":"is-style-tinted-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
	<section class="wp-block-group alignfull is-style-tinted-page-section" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">
		<!-- wp:heading {"textAlign":"center","level":2} -->
		<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Plan your journey', 'sd-theme-2026' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
		<div class="wp-block-buttons">
			<!-- wp:button {"className":"is-style-cta"} -->
			<div class="wp-block-button is-style-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Enquire now', 'sd-theme-2026' ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</section>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"Footer Columns"},"align":"full","className":"is-style-site-footer","style":{"spacing":{"blockGap":"var:preset|spacing|60","padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|30","right":"var:preset|spacing|20","left":"var:preset|spacing|20"}}},"backgroundColor":"contrast","textColor":"base","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull is-style-site-footer has-base-color has-contrast-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--20)">

		<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
		<div class="wp-block-columns alignwide">

			<!-- wp:column {"metadata":{"name":"Logo"}} -->
			<div class="wp-block-column">
				<!-- wp:site-logo {"width":160} /-->
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"metadata":{"name":"Contact"}} -->
			<div class="wp-block-column">
				<!-- wp:heading {"level":2,"textColor":"base","fontSize":"300"} -->
				<h2 class="wp-block-heading has-base-color has-text-color has-300-font-size"><?php esc_html_e( 'Contact', 'sd-theme-2026' ); ?></h2>
				<!-- /wp:heading -->

				<!-- wp:navigation {"overlayMenu":"never","className":"is-style-footer-navigation","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} /-->
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"metadata":{"name":"Follow"}} -->
			<div class="wp-block-column">
				<!-- wp:heading {"level":2,"textColor":"base","fontSize":"300"} -->
				<h2 class="wp-block-heading has-base-color has-text-color has-300-font-size"><?php esc_html_e( 'Follow', 'sd-theme-2026' ); ?></h2>
				<!-- /wp:heading -->

				<!-- wp:social-links {"iconColor":"base","iconColorValue":"var(--wp--preset--color--base)","className":"is-style-logos-only","layout":{"type":"flex"}} -->
				<ul class="wp-block-social-links has-icon-color is-style-logos-only"></ul>
				<!-- /wp:social-links -->
			</div>
			<!-- /wp:column -->

			<!-- wp:column {"metadata":{"name":"Instagram","description":"Feed markup is rendered by the companion block plugin."}} -->
			<div class="wp-block-column">
				<!-- wp:heading {"level":2,"textColor":"base","fontSize":"300"} -->
				<h2 class="wp-block-heading has-base-color has-text-color has-300-font-size"><?php esc_html_e( 'Instagram', 'sd-theme-2026' ); ?></h2>
				<!-- /wp:heading -->
			</div>
			<!-- /wp:column -->
		</div>
		<!-- /wp:columns -->

		<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group alignwide">
			<!-- wp:separator {"align":"wide","className":"is-style-separator-thin","backgroundColor":"neutral-700"} -->
			<hr class="wp-block-separator alignwide has-text-color has-neutral-700-color has-alpha-channel-opacity has-neutral-700-background-color has-background is-style-separator-thin"/>
			<!-- /wp:separator -->

			<!-- wp:paragraph {"textColor":"neutral-400","fontSize":"100"} -->
			<p class="has-neutral-400-color has-text-color has-100-font-size">
				<?php
				printf(
					/* translators: %1$s: current year, %2$s: site title. */
					esc_html__( '© %1$s %2$s. All rights reserved.', 'sd-theme-2026' ),
					esc_html( gmdate( 'Y' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
				?>
			</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</footer>
<!-- /wp:group -->
