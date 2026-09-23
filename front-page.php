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
	$savta_sections = array(
		'intro'  => 'intro',
		'about'  => 'about',
		'how'    => 'how',
		'who'    => 'who',
		'efrat'  => 'efrat',
		'first'  => 'first-savta',
		'notice' => 'notice',
		'quotes' => 'quotes',
		'faq'    => 'faq',
		'signup' => 'signup',
	);
	foreach ( $savta_sections as $savta_slug => $savta_part ) {
		if ( savta_section_on( $savta_slug ) ) {
			get_template_part( 'template-parts/' . $savta_part );
		}
	}
	?>
</main>
<?php
get_footer();
