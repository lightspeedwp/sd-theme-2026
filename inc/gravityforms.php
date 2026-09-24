<?php
/**
 * Gravity Forms button styling.
 *
 * Attaches assets/styles/gravityforms-form.css to the `gravityforms/form`
 * block, so it loads only on a page that renders a form: Contact Us, Connect
 * With Us, and the enquiry forms.
 *
 * ## Why this needs a module at all
 *
 * The form's markup is Gravity Forms' own, and so is its look. The Orbital
 * theme framework styles it from `gravity-forms-theme-framework.min.css`
 * through `--gf-*` custom properties declared on `.gform-theme--framework`.
 * A `theme.json` `styles.blocks` entry compiles against the block wrapper,
 * which the form does not use for its controls, and `styles.elements.button`
 * never reaches the submit, because it is a plain `<input>`/`<button>` with
 * Gravity Forms' classes and not a `.wp-element-button`. The framework's
 * variables are the handle, and only authored CSS can set them.
 *
 * `enqueue_custom_block_styles()` in functions.php globs `core-*.css` only, so
 * a non-core block's stylesheet is registered by its own module here, as
 * inc/facetwp.php does.
 *
 * ## Why this belongs in the theme
 *
 * It is presentation and nothing else. The forms, their fields, notifications
 * and Salesforce routing are Gravity Forms and CRM Perks configuration.
 * Deactivating this theme must leave every form submitting, just in Orbital's
 * default weight.
 *
 * @package sd-theme-2026
 */

namespace SdTheme2026;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the form stylesheet against the block that renders it.
 *
 * `wp_enqueue_block_style()` is lazy: the stylesheet is printed only when the
 * block appears on the page, and inlined when it is small enough. Passing
 * `path` is what enables that inlining, so it is set alongside `src`.
 */
function enqueue_gravityforms_form_style() {
	$relative = 'assets/styles/gravityforms-form.css';

	wp_enqueue_block_style(
		'gravityforms/form',
		array(
			'handle' => 'sd-theme-2026-block-gravityforms-form',
			'src'    => get_theme_file_uri( $relative ),
			'path'   => get_theme_file_path( $relative ),
			'ver'    => asset_version( $relative ),
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\\enqueue_gravityforms_form_style' );
