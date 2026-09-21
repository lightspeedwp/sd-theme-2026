<?php
/**
 * Title: FAQ Section
 * Slug: sd-theme-2026/faq-section
 * Description: The one reusable "Frequently asked questions" section — a shared heading (parts/faq-section.html) over a core/accordion list of question/answer pairs. Insert on any approved page and author its questions directly; each insertion is independent, so content differs per placement while the heading and styling stay identical everywhere. If every question is removed, the whole section hides itself on the front end (styles/sections/faq-section.json, measured — see specs/002-faq-system/research.md R-01) — no need to delete the pattern instance, though doing so is also fine.
 * Categories: sd-theme-2026/features
 * Keywords: faq, questions, answers, accordion, help
 * Viewport Width: 1200
 * Inserter: true
 *
 * @package sd-theme-2026
 */

?>
<!-- wp:group {"tagName":"section","metadata":{"name":"FAQ"},"className":"is-style-faq-section","align":"full","style":{"spacing":{"blockGap":"var:preset|spacing|40","padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-faq-section" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)">
	<!-- wp:template-part {"slug":"faq-section"} /-->

	<!-- wp:accordion {"align":"wide","className":"is-style-faq"} -->
	<div class="wp-block-accordion alignwide is-style-faq">
		<!-- wp:accordion-item -->
		<div class="wp-block-accordion-item">
			<!-- wp:accordion-heading {"title":"Add a question"} -->
			<h3 class="wp-block-accordion-heading"><button class="wp-block-accordion-heading__toggle" aria-expanded="false"><span class="wp-block-accordion-heading__toggle-title"><?php esc_html_e( 'Add a question', 'sd-theme-2026' ); ?></span></button></h3>
			<!-- /wp:accordion-heading -->

			<!-- wp:accordion-panel -->
			<div class="wp-block-accordion-panel" hidden="until-found">
				<!-- wp:paragraph -->
				<p><?php esc_html_e( 'Add the answer here.', 'sd-theme-2026' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:accordion-panel -->
		</div>
		<!-- /wp:accordion-item -->
	</div>
	<!-- /wp:accordion -->
</section>
<!-- /wp:group -->
