<?php
/**
 * Title: Homepage — Main Content
 * Slug: sd-theme-2026/homepage-main-content
 * Description: The four stacked photograph panels that carry the homepage's primary routes — Destinations, Accommodation, Tours and the enquiry invitation. Each is a full-bleed background image with a translucent scrim panel over two thirds of the width, alternating side to side, and the scrim runs the full height of its panel.
 * Categories: sd-theme-2026/features, sd-theme-2026/pages
 * Keywords: homepage, panels, destinations, accommodation, tours, scrim, feature
 * Viewport Width: 1400
 * Template Types: front-page
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * Why the scrim is three groups deep, and not a padded column.
 *
 * The shade is a background colour on a `core/column`, and `core/column` has no
 * `dimensions.minHeight` support (wp-includes/blocks/column/block.json) — the
 * only core block in this stack that does is `core/group`. The first build put
 * the 520px minimum on the *outer* group and centred the columns with
 * `verticalAlignment: "center"`, which serialises as `align-self: center` and
 * therefore shrank each column to its own content height. The scrim floated in
 * the middle of the photograph with bare image above and below it.
 *
 * The fix is to drive the height from the inside out:
 *
 *   group   (background image, no minHeight — height comes from its child)
 *   columns (no verticalAlignment, so flex's default stretch gives equal height)
 *   column  (the scrim colour, no padding)
 *   group   ← "Panel Frame": minHeight 520px, flex/vertical,
 *             justifyContent stretch → align-items: stretch
 *             verticalAlignment center → justify-content: center
 *   group   (the original padded, constrained text box, unchanged)
 *
 * `justifyContent` and `verticalAlignment` swap axes under a vertical
 * orientation — wp-includes/block-supports/layout.php:800-814 — so those two
 * values give a full-width, vertically-centred child. The Panel Frame exists
 * only because a constrained group cannot centre its children vertically; the
 * text box beneath it is left constrained so the copy keeps the theme's 900px
 * measure rather than spreading to the column's ~985px.
 *
 * `box-sizing: border-box` is set globally in style.css:30, so the 520px
 * minimum absorbs the frame's own vertical padding instead of adding to it.
 *
 * The scrim colour is a raw `#00000085`. It has no preset because it is a
 * transparent black, and the palette holds no alpha values — flagged for the
 * token pass rather than invented here.
 */

/*
 * Dev uploads URLs, written out plainly. This is the same deliberate exception
 * to AGENTS.md' "never hardcode an uploads URL" that patterns/footer.php makes
 * and documents at length — see the note above `$sd_uploads` there for the
 * reasoning. ⚠️ Rewrite in the same pass as the go-live domain search-replace.
 */
$sd_uploads = 'https://southerndestinations.lightspeedwp.dev/wp-content/uploads/';

/*
 * The arrow flourish, shared by the first three panels. A literal constant, so
 * it is echoed unescaped — there is no input to sanitise.
 *
 * Live makes the arrow itself the link (`uploads/2019/08/arrow.svg` wrapped in
 * an `<a>`). Here the link is on the CTA *paragraph* instead and the arrow stays
 * decorative, for two reasons. The paragraph already carried
 * `elements.link.color` set to the accent — a declaration that styles nothing
 * unless there is a link in it, so the link was plainly intended. And a text
 * link has an accessible name where an icon-only one needs an invented label.
 * The Icon Block can hold an href through `linkUrl`, but doing so swaps its
 * inner `<div class="icon-container">` for an `<a>` of the same class, which is
 * a third-party save() shape this pattern would have to mirror exactly or trip
 * block validation. Not worth it for a flourish.
 *
 * The mask ids are namespaced `sd-arrow-*` rather than left as the `a`/`b` the
 * live site's SVG ships with. Three arrows plus the intro band's bird flourish
 * all declared `id="a"`/`id="b"` on one page; an SVG `mask="url(#b)"` resolves
 * against the *first* matching id in the document, so the bird — which renders
 * above these panels — was capturing the arrows' mask and deforming them.
 */
$sd_arrow_svg = '<svg xmlns="http://www.w3.org/2000/svg" xmlns-xlink="http://www.w3.org/1999/xlink" width="43" height="24"><defs><path id="sd-arrow-shape" d="M.629.666H13.27V24H.63z"></path></defs><g fill="none" fill-rule="evenodd"><g transform="translate(29 -.665)"><mask id="sd-arrow-mask" fill="#fff"><use xlink:href="#sd-arrow-shape"></use></mask><path d="M3.866 24c1.294-.719 2.292-1.305 3-1.637.753-.695 2.054-2.619 3.784-5.766 1.785-3.271 2.657-5.357 2.62-6.198-.034-.782-2.993-2.448-6.146-5.683C3.972 1.481 3.57 1.341 2.91 1.37 2.127 1.404.597.171.63.892L2.327 2.97c1.626 1.374 1.62 2.174 3.248 3.609 2.387 2.245 3.597 3.758 3.634 4.6.026.601-.345 1.761-1.116 3.421-.828 1.723-1.445 2.774-1.907 3.215-.272.675-.376 1.041-.37 1.161l.012.301c-.057.062-.455.622-1.197 1.558-.632.69-.912 1.184-.899 1.484.016.361.21.654.581.878-.288.314-.455.623-.447.803" fill="#D78B17" mask="url(#sd-arrow-mask)"></path></g><path d="M42.024 10.063c0-.446-.545-1.288-1.685-2.527-.99-1.04-1.784-1.784-2.379-2.18-4.509-3.024-7.086-4.56-7.78-4.56-.05 0-.05.05-.05.1 0 .148.347.544 1.091 1.238.693.694 1.19 1.14 1.486 1.338l3.717 2.825c1.041.842 2.131 1.982 3.32 3.518l-.198.397c-1.982 1.338-5.402 1.982-10.308 1.982h-1.486c-.149 0-.05.248-.446.248-.298 0-.446-.05-.446-.198v-.05c-.843.05-1.933-.05-3.37-.198-1.636-.199-2.726-.298-3.37-.298-.545 0-1.784.2-2.329.2-.248 0-1.933-.249-4.956-.794l-.198.446-.446-.694c-.149.15-.298.198-.545.198-.347 0-.892-.149-1.537-.446-.693-.347-1.189-.495-1.536-.495a.84.84 0 0 0-.496.148c-.94-.148-1.387-.248-1.437-.248h-.347c-1.486-.644-3.27-.99-5.302-.99-.347 0-.694 0-.942.05 0 1.09 0 1.634-.049 1.684 2.032.892 4.51 1.586 7.334 2.081 1.19.198 3.717.496 7.582.942l3.767.594c1.437.05 2.478.1 3.221.1l6.591.247.15-.446c.246.446.742.694 1.485.694.942 0 2.478-.198 4.66-.545 2.13-.396 3.666-.743 4.558-1.04.05.247.198-.248.248-.447l.495.446c.05-.148.397-.446.991-.892.942-.743.645-.892.793-1.635.1-.347.15-.644.15-.793" fill="#D78B17"></path></g></svg>';

/*
 * Panel order and scrim side both follow the live homepage: right, left, right,
 * left, reading down. `cta_color` differs on the accommodation panel — live
 * paints that one line a deeper amber than the other two.
 */
$sd_panels = array(
	array(
		'name'      => 'Explore the destinations',
		'image'     => '2019/08/home-explore-bg-img.jpg',
		'shade'     => 'right',
		'heading'   => __( 'Explore the destinations...', 'sd-theme-2026' ),
		'anchor'    => 'h-explore-the-destinations',
		'body'      => __( 'Our Africa and safari gurus are ready to provide you with their insights and advice on which destinations to include on your must-see list.', 'sd-theme-2026' ),
		'cta'       => __( 'Take a look at our pick of inspiring destinations', 'sd-theme-2026' ),
		'cta_color' => 'accent-400',
		'link'      => '/destinations/',
	),
	array(
		'name'      => 'Review lodges and camps',
		'image'     => '2019/08/home-accommodation-bg-img.jpg',
		'shade'     => 'left',
		'heading'   => __( 'Review the lodges & camps…', 'sd-theme-2026' ),
		'anchor'    => 'h-review-the-lodges-amp-camps',
		'body'      => __( 'Which properties are best for your travel style and budget? Are you getting the best rate available? Are there specials you may not know about? As safari experts with more than 20 years experience and a vast network of hand-picked properties and operators at our fingertips, booking with us will give you the assurance you’re looking for.', 'sd-theme-2026' ),
		'cta'       => __( 'Explore Africa’s finest lodges, safaris and boutique hotels…', 'sd-theme-2026' ),
		'cta_color' => 'accent-500',
		'link'      => '/accommodation/',
	),
	array(
		'name'      => 'Choose your safari',
		'image'     => '2019/08/home-experience-bg-img.jpg',
		'shade'     => 'right',
		'heading'   => __( 'Choose your safari experience…', 'sd-theme-2026' ),
		'anchor'    => 'h-choose-your-safari-experience',
		'body'      => __( 'There’s so much more to an African safari than seeing animals from the back of a game drive vehicle! The options are endless for special, never to be repeated encounters and experiences.', 'sd-theme-2026' ),
		'cta'       => __( 'Take a look at some of our latest tours for inspiration', 'sd-theme-2026' ),
		'cta_color' => 'accent-400',
		'link'      => '/tours/',
	),
	array(
		'name'      => 'Ask us',
		'image'     => '2019/08/home-dream-trip-bg-img.jpg',
		'shade'     => 'left',
		'heading'   => __( 'Ask us to design your dream trip to Africa!', 'sd-theme-2026' ),
		'anchor'    => 'h-ask-us-to-design-your-dream-trip-to-africa',
		'body'      => __( 'There’s no pressure and no obligation, just passionate people ready to make your trip a reality…', 'sd-theme-2026' ),
		'cta'       => '',
		'cta_color' => '',
		'link'      => '',
	),
);
?>
<!-- wp:group {"metadata":{"name":"Homepage - Main content"},"className":"is-style-light-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|0"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-light-page-section">
<?php
foreach ( $sd_panels as $sd_panel ) :
	$sd_image_url = $sd_uploads . $sd_panel['image'];
	?>
	<!-- wp:group {"metadata":{"name":"<?php echo esc_attr( $sd_panel['name'] ); ?>"},"align":"wide","style":{"background":{"backgroundImage":{"url":"<?php echo esc_url( $sd_image_url ); ?>","source":"file"},"backgroundSize":"cover"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:columns {"align":"wide"} -->
		<div class="wp-block-columns alignwide">
			<?php if ( 'right' === $sd_panel['shade'] ) : ?>
				<!-- wp:column {"width":"33.33%"} -->
				<div class="wp-block-column" style="flex-basis:33.33%"></div>
				<!-- /wp:column -->
			<?php endif; ?>

			<!-- wp:column {"width":"66.66%","style":{"color":{"background":"#00000085"}}} -->
			<div class="wp-block-column has-background" style="background-color:#00000085;flex-basis:66.66%">
				<!-- wp:group {"metadata":{"name":"Panel Frame"},"style":{"dimensions":{"minHeight":"520px"},"spacing":{"padding":{"top":"var:preset|spacing|100","bottom":"var:preset|spacing|100"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch","verticalAlignment":"center"}} -->
				<div class="wp-block-group" style="min-height:520px;padding-top:var(--wp--preset--spacing--100);padding-bottom:var(--wp--preset--spacing--100)">
					<!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|40","left":"var:preset|spacing|40","top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"layout":{"type":"constrained"}} -->
					<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--40)">
						<!-- wp:heading {"className":"is-style-script-accent","style":{"elements":{"link":{"color":{"text":"var:preset|color|accent-400"}}},"typography":{"textTransform":"none"}},"textColor":"accent-400","fontFamily":"accent","anchor":"<?php echo esc_attr( $sd_panel['anchor'] ); ?>"} -->
						<h2 id="<?php echo esc_attr( $sd_panel['anchor'] ); ?>" class="wp-block-heading is-style-script-accent has-accent-400-color has-text-color has-link-color has-accent-font-family" style="text-transform:none"><?php echo esc_html( $sd_panel['heading'] ); ?></h2>
						<!-- /wp:heading -->

						<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base"} -->
						<p class="has-base-color has-text-color has-link-color"><?php echo esc_html( $sd_panel['body'] ); ?></p>
						<!-- /wp:paragraph -->

						<?php if ( '' !== $sd_panel['cta'] ) : ?>
							<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
							<div class="wp-block-group">
								<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|<?php echo esc_attr( $sd_panel['cta_color'] ); ?>"}}}},"textColor":"<?php echo esc_attr( $sd_panel['cta_color'] ); ?>"} -->
								<p class="has-<?php echo esc_attr( $sd_panel['cta_color'] ); ?>-color has-text-color has-link-color"><a href="<?php echo esc_url( home_url( $sd_panel['link'] ) ); ?>"><?php echo esc_html( $sd_panel['cta'] ); ?></a></p>
								<!-- /wp:paragraph -->

								<!-- wp:outermost/icon-block {"iconName":""} -->
								<div class="wp-block-outermost-icon-block"><div class="icon-container" style="width:48px;transform:rotate(0deg) scaleX(1) scaleY(1)"><?php echo $sd_arrow_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Literal SVG constant defined above; no dynamic input. ?></div></div>
								<!-- /wp:outermost/icon-block -->
							</div>
							<!-- /wp:group -->
						<?php endif; ?>
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:column -->

			<?php if ( 'left' === $sd_panel['shade'] ) : ?>
				<!-- wp:column {"width":"33.33%"} -->
				<div class="wp-block-column" style="flex-basis:33.33%"></div>
				<!-- /wp:column -->
			<?php endif; ?>
		</div>
		<!-- /wp:columns -->
	</div>
	<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->
