<?php
/**
 * Title: Homepage — How To Plan Your Dream Trip
 * Slug: sd-theme-2026/homepage-dream-trip
 * Description: The homepage's opening invitation — the Southern Destinations monogram over a script heading, an italic standfirst, and the "Start here" cue with the SD bird pointing down into the panels below.
 * Categories: sd-theme-2026/features, sd-theme-2026/pages
 * Keywords: intro, dream trip, start here, monogram, bird, homepage
 * Viewport Width: 1400
 * Template Types: front-page
 * Inserter: true
 *
 * @package sd-theme-2026
 */

/*
 * Lifted out of templates/front-page.html unchanged.
 *
 * It was authored inline in the template while the homepage was being blocked
 * out. LS-2014 item 4.9 asks for the homepage sections as patterns, and this is
 * one of the two that had not been extracted — so this file is the same markup,
 * moved, with the three copy strings wrapped for translation. No design change.
 *
 * ## The bird is inline SVG, not an image
 *
 * `outermost/icon-block` carries the mark as inline SVG so it inherits
 * `currentColor` and scales with the heading beside it. It came across from
 * live as a pair of masked paths in brand-500 (#C9831A on live, which is
 * brand-500's neighbour — the token is used rather than the literal). The two
 * `<defs>` ids are namespaced `sd-bird-*`; they are document-unique ids, and
 * live's originals were the generic `path-1`/`mask-2`, which collide with any
 * other inline SVG on the page.
 *
 * The uploads URL is dev's, written literally as core writes asset URLs. The
 * go-live deployment runs a find-and-replace over the dev host by convention,
 * so it needs no code change and no indirection here.
 */

?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Homepage - How to plan your dream trip"},"align":"full","className":"is-style-light-page-section","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-light-page-section"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|80"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide"><!-- wp:image {"id":50292,"width":"300px","sizeSlug":"full","linkDestination":"none","align":"center"} -->
<figure class="wp-block-image aligncenter size-full is-resized"><img src="https://southerndestinations.lightspeedwp.dev/wp-content/uploads/2019/07/home-intro-logo.svg" alt="" class="wp-image-50292" style="width:300px;height:auto"/></figure>
<!-- /wp:image -->

<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"1100px"}} -->
<div class="wp-block-group alignwide"><!-- wp:heading {"textAlign":"center","className":"is-style-script-accent","fontFamily":"accent","anchor":"h-how-to-plan-your-dream-trip-to-africa"} -->
<h2 class="wp-block-heading has-text-align-center is-style-script-accent has-accent-font-family" id="h-how-to-plan-your-dream-trip-to-africa"><?php esc_html_e( 'How to plan your dream trip to Africa…', 'sd-theme-2026' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","fontSize":"400"} -->
<p class="has-text-align-center has-400-font-size"><em><?php esc_html_e( 'So you want to see Africa? You may have an idea where you want to go and what you want to see. Weaving your ideas into a seriously addictive travel experience is where we shine. Your dreams. Our expertise. Together we can help you plan your ultimate African safari!', 'sd-theme-2026' ); ?></em></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3,"className":"is-style-script-accent","style":{"elements":{"link":{"color":{"text":"var:preset|color|brand-500"}}}},"textColor":"brand-500","fontSize":"600","fontFamily":"accent","anchor":"h-start-here"} -->
<h3 id="h-start-here" class="wp-block-heading is-style-script-accent has-brand-500-color has-text-color has-link-color has-accent-font-family has-600-font-size"><?php echo esc_html_x( 'Start here', 'cue pointing down to the homepage panels', 'sd-theme-2026' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:outermost/icon-block {"iconName":""} -->
<div class="wp-block-outermost-icon-block"><div class="icon-container" style="width:48px;transform:rotate(0deg) scaleX(1) scaleY(1)"><svg xmlns="http://www.w3.org/2000/svg" xmlns-xlink="http://www.w3.org/1999/xlink" width="45" height="31"><defs><path id="sd-bird-shape-1" d="M0 .509h37.65v27.76H0z"></path><path id="sd-bird-shape-2" d="M.166.795H19V11H.166z"></path></defs><g fill="none" fill-rule="evenodd"><g transform="translate(0 -.509)"><mask id="sd-bird-mask-1" fill="#fff"><use xlink:href="#sd-bird-shape-1"></use></mask><path d="M28.316 10.396a89.182 89.182 0 0 1-4.674-3.405c-.268-.21-.468-.426-.569-.691-.37.08-.767-.045-1.12-.381l-.19-.15c-.406-.192-1.167-.48-2.219-.87-.974-.33-1.698-.588-2.179-.84-1.642-.975-3.775-1.716-6.406-2.293C8.694 1.353 6.43.938 4.231.516 2.843.48 1.782.577 1.108.728A2.666 2.666 0 0 1 0 1.278c.092.75.325 1.24.746 1.57.115.09 1.405.174 3.948.31 2.786.143 4.32.232 4.638.296 1.133.208 2.61.683 4.508 1.488 2.073.819 3.56 1.362 4.517 1.556.735.636 2.041 1.41 3.94 2.215.331.445.607.722.828.833l.192.15c.425.085.676.159.791.248l-.043.213c3.152 2.277 5.791 4.895 7.957 7.881 2.374 3.273 3.901 6.683 4.54 10.203.218-.198.53-.203.903.029-.108-.886-.073-1.721.185-2.445-.684-.781-.956-1.302-.806-1.494.3-.381.555-.552.76-.576-.256-.384-.772-1.774-1.61-4.093-.598-1.761-1.4-2.944-2.31-3.592l-.336-.202c.026-.348-.645-1.305-2.043-2.829-1.207-1.373-2.112-2.265-2.686-2.714l-.303.072z" fill="#C9831A" mask="url(#sd-bird-mask-1)"></path></g><g transform="translate(26 19.491)"><mask id="sd-bird-mask-2" fill="#fff"><use xlink:href="#sd-bird-shape-2"></use></mask><path d="M.166 3.408c.58 1.044 1.052 1.85 1.32 2.422.561.607 2.114 1.658 4.655 3.054 2.64 1.44 4.323 2.144 5.002 2.115.631-.028 1.976-2.416 4.587-4.96 2.613-2.546 2.724-2.87 2.701-3.404-.027-.63.968-1.866.386-1.84l-1.677 1.37c-1.11 1.313-1.756 1.308-2.914 2.622-1.81 1.927-3.033 2.904-3.712 2.933-.484.022-1.421-.278-2.761-.9-1.39-.668-2.239-1.166-2.596-1.539-.543-.22-.84-.304-.936-.3l-.243.011c-.05-.047-.503-.367-1.258-.966-.557-.51-.956-.736-1.198-.726-.292.013-.527.17-.709.47-.252-.233-.501-.368-.647-.362" fill="#C9831A" mask="url(#sd-bird-mask-2)"></path></g></g></svg></div></div>
<!-- /wp:outermost/icon-block --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
