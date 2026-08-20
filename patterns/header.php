<?php
/**
 * Title: Header
 * Slug: sd-theme-2026/header
 * Description: Site header — logo, primary navigation and the enquiry call to action. Scaffold only: the LSX mega menu, sticky behaviour and Trustpilot header image are added when the header is translated from the live site.
 * Categories: header, sd-theme-2026/menu
 * Keywords: header, nav, navigation, logo, enquiry
 * Viewport Width: 1500
 * Block Types: core/template-part/header
 * Post Types: wp_template
 * Inserter: true
 *
 * @package sd-theme-2026
 */

?>
<!-- wp:group {"metadata":{"name":"Header"},"tagName":"header","align":"full","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"constrained"}} -->
<header class="wp-block-group alignfull">

	<!-- wp:group {"metadata":{"name":"Header Row"},"align":"full","className":"is-style-header-row","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull is-style-header-row">
		<!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
		<div class="wp-block-group alignwide">
			<!-- wp:site-logo {"width":140} /-->

			<!-- wp:navigation {"openSubmenusOnClick":true,"className":"is-style-main-navigation","icon":"menu","style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"flex","justifyContent":"center"}} /-->

			<!-- wp:buttons {"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
			<div class="wp-block-buttons">
				<!-- wp:button {"className":"is-style-fill"} -->
				<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Enquire', 'sd-theme-2026' ); ?></a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</header>
<!-- /wp:group -->
