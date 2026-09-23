<?php
/**
 * One-page landing template: every section is a template part.
 *
 * @package Savta
 */

get_header();
?>
<main id="main" tabindex="-1">
	<?php
	get_template_part( 'template-parts/hero' );
	get_template_part( 'template-parts/intro' );
	get_template_part( 'template-parts/about' );
	get_template_part( 'template-parts/how' );
	get_template_part( 'template-parts/who' );
	get_template_part( 'template-parts/efrat' );
	get_template_part( 'template-parts/first-savta' );
	get_template_part( 'template-parts/notice' );
	get_template_part( 'template-parts/quotes' );
	get_template_part( 'template-parts/faq' );
	get_template_part( 'template-parts/signup' );
	?>
</main>
<?php
get_footer();
