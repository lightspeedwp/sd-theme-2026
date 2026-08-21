<?php
/**
 * Footer — the mobile background swap.
 *
 * Design only. One rule, and it is here rather than in a `styles/**` partial or
 * a static stylesheet for a reason neither of those can work around: the value
 * is an attachment URL.
 *
 * Live carries two footer photographs and swaps them at 600px:
 *
 *     #footer-bg-sd #footer-widgets { background-image: url(../../images/footer-bg.jpg) }
 *
 *     @media (max-width: 600px) {
 *         #footer-bg-sd #footer-widgets { background-image: url(../../images/mobile-footer-bg-img.jpg); min-height: 1400px }
 *     }
 *
 * The swap is not decoration — the desktop file is 1916×840, and at 390px a
 * `cover` fit of a 2.3:1 landscape crops to a band of water with the sun gone.
 * The mobile file is 750×2752, cut for the column.
 *
 * The desktop image is set on the block in patterns/footer.php, where the editor
 * can see and change it. This one cannot be: a media query is not expressible as
 * a block attribute. And it cannot be a static rule in `assets/styles/` either,
 * because both footer photographs now live in the media library — the client's
 * decision, so the footer's assets all sit in the database at the same relative
 * path they hold on live — which means their URLs are per-environment and only
 * resolvable at runtime.
 *
 * ⚠️ `min-height: 1400px` is **not** reproduced. Measured at 390px, live's
 * mobile footer runs ~400px of empty photograph between the Instagram grid and
 * the copyright bar. The band keeps its `cover` fit and its 615px floor and ends
 * where its content ends. Agreed as a deliberate deviation, recorded in
 * style.md.
 *
 * Dropping the height forces the background *position* to change with it, which
 * is the second declaration below. The mobile file is a tall crop — pale sky for
 * its top ~60%, then the treeline, then dark water — and at 390px `cover` scales
 * it to 1431px tall. Live's 1400px band therefore shows almost the whole frame
 * and its text lands on the sky. A 1077px band bottom-anchored (`50% 100%`, the
 * desktop value) shows the bottom 1077px instead, which slides the dark water up
 * behind the "Follow Us" and "Instagram" columns: measured there, the labels sat
 * at roughly 1.5:1. Anchoring to the top keeps the same relationship live has
 * between the text and the sky, at the shorter height — so trimming the dead
 * space costs no legibility.
 *
 * @package sd-theme-2026
 */

namespace SdTheme2026;

/**
 * Uploads-relative path of the footer's mobile background.
 */
const FOOTER_MOBILE_BACKGROUND = '2026/08/mobile-footer-bg-img.jpg';

/**
 * Breakpoint live swaps the footer photograph at.
 */
const FOOTER_MOBILE_BREAKPOINT = '600px';

/**
 * Print the mobile footer background rule.
 *
 * Attached to both the front end and the editor so the swap is visible in the
 * Site Editor's mobile preview, not only on the rendered page.
 *
 * The selector is `.sd-footer__widgets` — the hook class patterns/footer.php
 * puts on the band — doubled up so it out-specifies the inline
 * `style="background-image:…"` that core's background support writes onto the
 * same element. An inline style is (1,0,0), which no selector can beat, so this
 * is the one place in the footer where `!important` is load-bearing rather than
 * lazy.
 */
function footer_mobile_background_css() {
	$src = attachment_src_by_path( FOOTER_MOBILE_BACKGROUND );

	if ( ! $src ) {
		return;
	}

	$css = sprintf(
		'@media (max-width: %1$s) {
			.sd-footer__widgets.sd-footer__widgets {
				background-image: url("%2$s") !important;
				background-position: 50%% 0%% !important;
			}
		}',
		FOOTER_MOBILE_BREAKPOINT,
		esc_url( $src )
	);

	wp_add_inline_style( 'sd-theme-2026', $css );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\footer_mobile_background_css', 20 );
